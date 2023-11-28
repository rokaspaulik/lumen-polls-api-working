<?php

namespace App\Http\Controllers\V2;

class StatusCheckController extends Controller
{
    public function __invoke()
    {
        return response(json_encode([
            'status' => 'Service is up and running (V2)'
        ]))->withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }
}
