<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FinanceTransaction;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            data: $transactions,
            message: 'Finance transactions retrieved.',
            code: 'TRANSACTIONS_RETRIEVED',
        );
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $transaction = FinanceTransaction::create(array_merge($validator->validated(), [
            'user_id' => $user->id,
        ]));
        return ApiResponse::success(
            data: $transaction,
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
            data: $transaction,
            message: 'Finance transaction retrieved.',
            code: 'TRANSACTION_RETRIEVED',
        );
    }

    public function update(Request $request, FinanceTransaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|in:income,expense',
            'amount' => 'sometimes|numeric|min:0',
            'category' => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'transaction_date' => 'sometimes|date',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $transaction->update($validator->validated());
        return ApiResponse::success(
            data: $transaction,
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
