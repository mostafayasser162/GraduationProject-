<?php

namespace App\Http\Controllers\Api\StartUp;

use App\Http\Controllers\Controller;
use App\Http\Resources\FactoryResponseResource;
use App\Mail\StartupAcceptResponseMail;
use App\Mail\StartupRejectedResponseMail;
use App\Mail\StartupShouldPayDepositMail;
use App\Models\FactoryResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Deal;
use Illuminate\Support\Facades\Mail;

use App\Enums\FactoryResponse\Status as ResponseStatus;

class ResponseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $factoryResponses = FactoryResponse::where('status', ResponseStatus::PENDING())
            ->whereHas('request', function ($query) use ($user) {
                $query->where('startup_id', $user->id);
            })
            ->with('request.startup')
            ->get();
        $factoryResponses = FactoryResponseResource::collection($factoryResponses);
        return response()->paginate_resource($factoryResponses);
    }

    public function show($id)
    {
        $user = auth()->user();

        $factoryResponse = FactoryResponse::where('id', $id)
            ->whereHas('request', function ($query) use ($user) {
                $query->where('startup_id', $user->id);
            })
            ->with('request.startup') // إذا تحتاج تفاصيل الطلب والشركة الناشئة
            ->first();

        if (!$factoryResponse) {
            return response()->errors('Factory response not found or not authorized');
        }

        return response()->success(new FactoryResponseResource($factoryResponse));
    }


    public function acceptFactoryResponse($responseId)
    {
        $user = auth()->user();
        return DB::transaction(function () use ($responseId, $user) {

            $response = FactoryResponse::where('id', $responseId)
                ->whereHas('request', function ($q) use ($user) {
                    $q->where('startup_id', $user->id);
                })
                ->with('request.startup')
                ->first();

            if (!$response) {
                return response()->errors('Response not found or unauthorized');
            }
            if ($response->status != ResponseStatus::PENDING()) {
                return response()->errors('Response Status not Pending ');
            }

            $response->update(['status' => ResponseStatus::ACCEPTED()]);
            // update status of request responses
            $response->request->update(['status' => ResponseStatus::ACCEPTED()]);

            FactoryResponse::where('request_id', $response->request_id)
                ->where('id', '!=', $response->id)
                ->update(['status' => ResponseStatus::REJECTED()]);
            $depositAmount = $response->price * 0.2;
            $final_payment_amount = $response->price - $depositAmount;
            $response->request->deals()->attach($response->factory_id, [
                'price' => $response->price,
                'deposit_amount' => $depositAmount,
                'final_payment_amount' => $final_payment_amount,
                'deal_date' => now(),
                'factory_response_id' => $response->id,

            ]);
            // dd($response->factory->email);
            Mail::to($response->factory->email)->send(
                new StartupAcceptResponseMail(
                    $response->request->startup->name,
                    $response->request->description
                )
            );

            Mail::to($response->request->startup->email)->send(
                new StartupShouldPayDepositMail(
                    $response->request->startup->name,
                    $depositAmount
                )
            );

            return response()->success(new FactoryResponseResource($response), 'Response accepted and deal created');
        });
    }

    public function rejectFactoryResponse($responseId)
    {
        $user = auth()->user();

        return DB::transaction(function () use ($responseId, $user) {

            $response = FactoryResponse::where('id', $responseId)
                ->whereHas('request', function ($q) use ($user) {
                    $q->where('startup_id', $user->id);
                })
                ->first();

            if (!$response) {
                return response()->errors('Response not found or unauthorized');
            }

            if ($response->status != ResponseStatus::PENDING()) {
                return response()->errors('Response Status not Pending ');
            }

            $response->update([
                'status' => ResponseStatus::REJECTED(),
            ]);
            // Mail::to($response->factory->email)->send(
            //     new StartupRejectedResponseMail(
            //         $response->request->startup->name,
            //         $response->request->description
            //     )
            // );
            return response()->success('Response rejected successfully');
        });
    }
}
