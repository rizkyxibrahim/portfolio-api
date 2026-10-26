<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreFinanceTransactionRequest;
use App\Http\Requests\Api\V1\UpdateFinanceTransactionRequest;
use App\Http\Resources\Api\V1\FinanceTransactionResource;
use App\Models\FinanceTransaction;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class FinanceTransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = FinanceTransaction::where('user_id', $user->id);

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(15);

        return ApiResponse::success(
            data: FinanceTransactionResource::collection($transactions),
            message: 'Finance transactions retrieved.',
            code: 'TRANSACTIONS_RETRIEVED',
        );
    }

    public function store(StoreFinanceTransactionRequest $request)
    {
        $user = $request->user();
        $transaction = FinanceTransaction::create(array_merge($request->validated(), [
            'user_id' => $user->id,
        ]));

        return ApiResponse::success(
            data: new FinanceTransactionResource($transaction),
            message: 'Finance transaction created.',
            code: 'TRANSACTION_CREATED',
            status: 201,
        );
    }

    public function show(Request $request, FinanceTransaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        return ApiResponse::success(
            data: new FinanceTransactionResource($transaction),
            message: 'Finance transaction retrieved.',
            code: 'TRANSACTION_RETRIEVED',
        );
    }

    public function update(UpdateFinanceTransactionRequest $request, FinanceTransaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $transaction->update($request->validated());

        return ApiResponse::success(
            data: new FinanceTransactionResource($transaction),
            message: 'Finance transaction updated.',
            code: 'TRANSACTION_UPDATED',
        );
    }

    public function destroy(Request $request, FinanceTransaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $transaction->delete();

        return ApiResponse::success(
            message: 'Finance transaction deleted.',
            code: 'TRANSACTION_DELETED',
        );
    }
}