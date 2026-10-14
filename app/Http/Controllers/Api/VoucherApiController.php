<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherApiController extends Controller
{
    /**
     * Check & Apply Voucher to current cart subtotal
     */
    public function apply(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $subtotal = (float) $request->input('subtotal');

        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher "' . $code . '" tidak ditemukan. Periksa kembali penulisan huruf kapital.',
            ], 404);
        }

        $check = $voucher->checkValidity($subtotal);
        if (!$check['valid']) {
            return response()->json([
                'success' => false,
                'message' => $check['message'],
            ], 422);
        }

        $discountAmount = $voucher->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'message' => 'Voucher ' . $voucher->code . ' berhasil digunakan!',
            'data' => [
                'code' => $voucher->code,
                'name' => $voucher->name,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
                'discount_amount' => $discountAmount,
                'min_spend' => $voucher->min_spend,
                'max_discount' => $voucher->max_discount,
            ],
        ]);
    }

    /**
     * List active public vouchers
     */
    public function list(): JsonResponse
    {
        $vouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now()->toDateString());
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $vouchers,
        ]);
    }
}
