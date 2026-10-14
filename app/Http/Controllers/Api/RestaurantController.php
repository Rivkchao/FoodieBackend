<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{
    /**
     * Get list of featured partner restaurants
     */
    public function index(): JsonResponse
    {
        $restaurants = Restaurant::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar restoran mitra berhasil diambil',
            'data' => $restaurants,
        ]);
    }
}
