<?php

namespace App\Http\Controllers\V1;

class StatusCheckController extends Controller
{
    public function __invoke()
    {
        return response(json_encode([
            'status' => 'Service is up and running (V1)'
        ]))->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }
}
