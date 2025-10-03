<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only admin users can create products. Use admin guard.
     */
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_\.\(\)]+$/', // Only allow safe characters
            ],
            'description' => [
                'required',
                'string',
                'max:2000',
            ],
            'short_description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'sku' => [
                'required',
                'string',
                'max:100',
                'unique:products,sku',
                'regex:/^[A-Z0-9\-_]+$/', // SKU format validation
            ],
            'price' => [
                'required',
                'numeric',
                'between:0.01,99999.99',
            ],
            'sale_price' => [
                'nullable',
                'numeric',
                'between:0.01,99999.99',
                'lt:price', // Sale price must be less than regular price
            ],
            'stock_quantity' => [
                'required',
                'integer',
                'min:0',
                'max:99999',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'weight' => [
                'nullable',
                'string',
                'max:50',
            ],
            'dimensions' => [
                'nullable',
                'string',
                'max:100',
            ],
            'is_active' => [
                'boolean',
            ],
            'is_featured' => [
                'boolean',
            ],
            'meta_title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB max
                'dimensions:max_width=2000,max_height=2000',
            ],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'Product name contains invalid characters. Only letters, numbers, spaces, hyphens, underscores, dots, and parentheses are allowed.',
            'sku.regex' => 'SKU format is invalid. Only uppercase letters, numbers, hyphens, and underscores are allowed.',
            'sku.unique' => 'This SKU is already in use. Please choose a different SKU.',
            'price.between' => 'Price must be between $0.01 and $99,999.99.',
            'sale_price.lt' => 'Sale price must be less than the regular price.',
            'sale_price.between' => 'Sale price must be between $0.01 and $99,999.99.',
            'stock_quantity.max' => 'Stock quantity cannot exceed 99,999 items.',
            'image.max' => 'Image size cannot exceed 2MB.',
            'image.dimensions' => 'Image dimensions cannot exceed 2000x2000 pixels.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
            'name' => trim($this->name),
            'description' => trim($this->description),
            'sku' => strtoupper(trim($this->sku)),
        ]);
    }
}
