<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'shipping_address' => [
                'required',
                'string',
                'max:500',
                'min:10',
                'regex:/^[a-zA-Z0-9\s\,\.\-\#\/]+$/', // Address characters only
            ],
            'shipping_city' => [
                'required',
                'string',
                'max:100',
                'min:2',
                'regex:/^[a-zA-Z\s\-\']+$/', // City name characters
            ],
            'shipping_state' => [
                'required',
                'string',
                'max:50',
                'min:2',
                'regex:/^[a-zA-Z\s\-\']+$/',
            ],
            'shipping_postal_code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9\s\-]+$/i', // Postal code format
            ],
            'shipping_country' => [
                'required',
                'string',
                'max:100',
                'in:United States,Canada,United Kingdom,Australia', // Supported countries
            ],
            'billing_same_as_shipping' => 'boolean',
            'billing_address' => [
                'required_if:billing_same_as_shipping,false',
                'nullable',
                'string',
                'max:500',
                'min:10',
                'regex:/^[a-zA-Z0-9\s\,\.\-\#\/]+$/',
            ],
            'billing_city' => [
                'required_if:billing_same_as_shipping,false',
                'nullable',
                'string',
                'max:100',
                'min:2',
                'regex:/^[a-zA-Z\s\-\']+$/',
            ],
            'billing_state' => [
                'required_if:billing_same_as_shipping,false',
                'nullable',
                'string',
                'max:50',
                'min:2',
                'regex:/^[a-zA-Z\s\-\']+$/',
            ],
            'billing_postal_code' => [
                'required_if:billing_same_as_shipping,false',
                'nullable',
                'string',
                'max:20',
                'regex:/^[A-Z0-9\s\-]+$/i',
            ],
            'billing_country' => [
                'required_if:billing_same_as_shipping,false',
                'nullable',
                'string',
                'max:100',
                'in:United States,Canada,United Kingdom,Australia',
            ],
            'payment_method' => [
                'required',
                'string',
                'in:credit_card,paypal,bank_transfer',
            ],
            'card_number' => [
                'required_if:payment_method,credit_card',
                'nullable',
                'string',
                'regex:/^[0-9]{13,19}$/', // Credit card number format
            ],
            'card_expiry' => [
                'required_if:payment_method,credit_card',
                'nullable',
                'string',
                'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/', // MM/YY format
            ],
            'card_cvv' => [
                'required_if:payment_method,credit_card',
                'nullable',
                'string',
                'regex:/^[0-9]{3,4}$/',
            ],
            'order_notes' => [
                'nullable',
                'string',
                'max:1000',
                'regex:/^[a-zA-Z0-9\s\.\,\!\?\-\'\"]+$/', // Safe characters only
            ],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'shipping_address.required' => 'Shipping address is required.',
            'shipping_address.regex' => 'Shipping address contains invalid characters.',
            'shipping_city.required' => 'Shipping city is required.',
            'shipping_city.regex' => 'City name contains invalid characters.',
            'shipping_state.required' => 'Shipping state/province is required.',
            'shipping_postal_code.required' => 'Postal code is required.',
            'shipping_postal_code.regex' => 'Invalid postal code format.',
            'shipping_country.required' => 'Shipping country is required.',
            'shipping_country.in' => 'We do not ship to the selected country.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method selected.',
            'card_number.required_if' => 'Credit card number is required.',
            'card_number.regex' => 'Invalid credit card number format.',
            'card_expiry.required_if' => 'Card expiry date is required.',
            'card_expiry.regex' => 'Card expiry must be in MM/YY format.',
            'card_cvv.required_if' => 'CVV code is required.',
            'card_cvv.regex' => 'CVV must be 3 or 4 digits.',
            'order_notes.max' => 'Order notes cannot exceed 1000 characters.',
            'order_notes.regex' => 'Order notes contain invalid characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'shipping_address' => trim(strip_tags($this->shipping_address)),
            'shipping_city' => trim(strip_tags($this->shipping_city)),
            'shipping_state' => trim(strip_tags($this->shipping_state)),
            'shipping_postal_code' => trim(strtoupper($this->shipping_postal_code)),
            'billing_address' => $this->billing_address ? trim(strip_tags($this->billing_address)) : null,
            'billing_city' => $this->billing_city ? trim(strip_tags($this->billing_city)) : null,
            'billing_state' => $this->billing_state ? trim(strip_tags($this->billing_state)) : null,
            'billing_postal_code' => $this->billing_postal_code ? trim(strtoupper($this->billing_postal_code)) : null,
            'card_number' => $this->card_number ? preg_replace('/\s+/', '', $this->card_number) : null,
            'order_notes' => $this->order_notes ? trim(strip_tags($this->order_notes)) : null,
            'billing_same_as_shipping' => (bool) $this->billing_same_as_shipping,
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'shipping_address' => 'shipping address',
            'shipping_city' => 'shipping city',
            'shipping_state' => 'shipping state/province',
            'shipping_postal_code' => 'shipping postal code',
            'shipping_country' => 'shipping country',
            'billing_address' => 'billing address',
            'billing_city' => 'billing city',
            'billing_state' => 'billing state/province',
            'billing_postal_code' => 'billing postal code',
            'billing_country' => 'billing country',
            'payment_method' => 'payment method',
            'card_number' => 'credit card number',
            'card_expiry' => 'expiry date',
            'card_cvv' => 'CVV code',
            'order_notes' => 'order notes',
        ];
    }
}
