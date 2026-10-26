<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:in,out,income,expense',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Transaction type is required.',
                        'type.in' => 'Invalid transaction type. Use: in, out, income, or expense.',
                        'amount.required' => 'Amount is required.',
                        'amount.numeric' => 'Amount must be a number.',
                        'amount.min' => 'Amount cannot be negative.',
                        'category.required' => 'Category is required.',
                        'transaction_date.required' => 'Transaction date is required.',
                        'transaction_date.date' => 'Invalid date format.',
        ];
    }

    /**
         * Normalize transaction type before validation.
         * Accepts: in, out, income, expense
         * Stores in database: income or expense
         */
    protected function prepareForValidation(): void
    {
        $type = $this->input('type');
        if ($type === 'in') {
            $this->merge(['type' => 'income']);
        } elseif ($type === 'out') {
            $this->merge(['type' => 'expense']);
        }
    }
}
