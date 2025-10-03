<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
                function ($attribute, $value, $fail) {
                    $product = Product::find($value);
                    if (!$product || !$product->is_active) {
                        $fail('The selected product is not available.');
                    }
                },
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                function ($attribute, $value, $fail) {
                    $product = Product::find($this->product_id);
                    if ($product && $value > $product->stock_quantity) {
                        $fail("Only {$product->stock_quantity} items available in stock.");
                    }
                },
            ],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Product selection is required.',
            'product_id.exists' => 'The selected product does not exist.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Minimum quantity is 1.',
            'quantity.max' => 'Maximum quantity per order is 99.',
            'quantity.integer' => 'Quantity must be a whole number.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'quantity' => (int) $this->quantity,
            'product_id' => (int) $this->product_id,
        ]);
    }
}
