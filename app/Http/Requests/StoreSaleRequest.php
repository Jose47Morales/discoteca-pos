<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return in_array(Auth::user()->role, ['admin', 'vendedor', 'caja']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'table_id' => 'required|exists:tables,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'sometimes|string|in:efectivo,transferencia',
        ];

        if (in_array(Auth::user()->role, ['admin', 'vendedor'])) {
            $rules['cash_register_id'] = 'required|exists:cash_registers,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'cash_register_id.required' => 'Debes seleccionar una caja abierta.',
            'cash_register_id.exists' => 'La caja seleccionada no existe.',
        ];
    }
}
