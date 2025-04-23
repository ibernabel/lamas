<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Check if a customer NID exists.
     *
     * @param  string  $nid
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNidExists(string $nid): JsonResponse
    {
        // Validate the NID: must be exactly 11 digits
        $validator = Validator::make(['nid' => $nid], [
            'nid' => ['required', 'string', 'regex:/^[0-9]{11}$/'],
        ],
        [
            'nid.required' => 'El NID es requerido.',
            'nid.string'   => 'El NID debe ser una cadena de texto.',
            'nid.regex'    => 'El NID debe contener exactamente 11 dígitos numéricos.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'exists' => false, // Indicate non-existence due to invalid format
                'message' => 'Formato de NID inválido.',
                'errors' => $validator->errors()
            ], 400); // Bad Request
        }

        // Check if customer exists using the validated NID
        $customerExists = Customer::where('nid', $nid)->exists();

        return response()->json([
            'exists' => $customerExists,
            'message' => $customerExists ? 'El NID del cliente fue encontrado.' : 'El NID del cliente no fue encontrado.'
        ]);
    }
}
