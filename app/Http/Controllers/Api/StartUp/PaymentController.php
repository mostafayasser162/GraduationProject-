<?php

namespace App\Http\Controllers\Api\StartUp;

use App\Enums\FactoryResponse\Status;
use App\Http\Controllers\Controller;
use App\Mail\DepositPaidNotification;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\DealResource;
use App\Mail\FinalPaymentConfirmationMail;

class PaymentController extends Controller
{
    public function payDeposit(Request $request, $dealId)
    {
        $user = auth()->user();
        return DB::transaction(function () use ($user, $dealId) {

            $deal = Deal::where('id', $dealId)
                ->whereHas('request', function ($q) use ($user) {
                    $q->where('startup_id', $user->id);
                })
                ->first();

            if (!$deal) {
                return response()->errors('Deal not found or not authorized');
            }

            if ($deal->is_deposit_paid == 1) {
                return response()->errors('Deposit already paid');
            }

            if ($deal->is_done) {
                return response()->errors('Deal is already completed');
            }

            $deal->update([
                'is_deposit_paid' => 1,
                'deposit_paid_at' => now()
            ]);
            // Mail::to($deal->factory->email)->send(new DepositPaidNotification($deal));
            Mail::to($deal->factory->email)->send(new DepositPaidNotification($deal));

            return response()->success([
                'message' => 'Deposit paid successfully',
                // 'deal_status' => $deal->status,
            ]);
        });
    }

    public function payFinal($dealId)
    {
        $user = auth()->user();

        return DB::transaction(function () use ($user, $dealId) {
            $deal = Deal::with('request')->where('id', $dealId)
                ->whereHas('request', function ($q) use ($user) {
                    $q->where('startup_id', $user->id);
                })
                ->first();

            if (!$deal) {
                return response()->errors('Deal not found or unauthorized');
            }

            if ($deal->is_final_paid) {
                return response()->errors('Final payment already made');
            }

            if ($deal->factoryResponse->status != Status::DONE()) {
                return response()->errors('Deal not ready for final payment');
            }

            $deal->update([
                'is_final_paid' => true,
                'final_paid_at' => now(),
                'is_done' => true,
            ]);

            return response()->success([
                'message' => 'Final payment successful. Deal marked as completed.',
                'deal' => new DealResource($deal->fresh()),
            ]);
        });
    }
}
