<?php

namespace App\Http\Controllers\Api\Factory;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Http\Resources\DealResource;
use App\Enums\FactoryResponse\Status as ResponseStatus;
use Illuminate\Support\Facades\DB;
use App\Mail\FinalPaymentRequestMail;
use Illuminate\Support\Facades\Mail;

class DealController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // dd($user);
        $deals = Deal::with(['request.startup', 'factoryResponse'])
            ->where('factory_id', $user->id)
            ->get();
        if (!$deals) {
            return response()->errors('No deals found');
        }
        return response()->paginate_resource(DealResource::collection($deals));
    }

    public function show($id)
    {
        $user = auth()->user();
        $deal = Deal::with(['request.startup', 'factoryResponse'])
            ->where('id', $id)
            ->where('factory_id', $user->id)
            ->first();

        if (!$deal) {
            return response()->errors('Deal not found');
        }
        return response()->success(new DealResource($deal));
    }



    public function orderDone($dealId)
    {
        $user = auth()->user();

        return DB::transaction(function () use ($user, $dealId) {
            $deal = Deal::with('factoryResponse')->where('id', $dealId)
                ->where('factory_id', $user->id)
                ->first();

            if (!$deal) {
                return response()->errors('Deal not found or unauthorized');
            }

            if ($deal->is_done) {
                return response()->errors('Deal already marked as done');
            }

            $deal->factoryResponse->update([
                'status' => ResponseStatus::DONE(),
            ]);

            // dd($deal->request->startup->email);
            // Send mail to startup
            Mail::to($deal->request->startup->email)
                ->send(new FinalPaymentRequestMail(
                    $deal->factory->name,
                    $deal->request->description,
                    $deal->price - $deal->deposit_amount
                ));
            return response()->success([
                'message' => 'Deal marked as done. Awaiting final payment from startup.',
                'deal' => new DealResource($deal->fresh(['request.startup', 'factoryResponse'])),
            ]);
        });
    }
}
