<?php

// Example using PHP

use Illuminate\Support\Facades\Http;

$response = Http::post('your-api-url/api/login', [
  'email' => 'user@example.com',
  'password' => 'password'
]);

// The response will contain your token if authentication is successful

$response = Http::withHeaders([
  'Authorization' => 'Bearer ' . $token,
])->get('your-api-url/api/V1/loan-applications');
