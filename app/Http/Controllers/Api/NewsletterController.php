<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Subscribe email to newsletter
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('email'),
            ], 422);
        }

        $email = strtolower(trim($request->input('email')));

        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'already_subscribed' => true,
                'message' => 'Email Anda sudah pernah terdaftar di newsletter Foodie! Gunakan kode promo WELCOME10 untuk diskon belanja Anda.',
                'voucher_code' => 'WELCOME10',
            ]);
        }

        $subscriber = NewsletterSubscriber::create([
            'email' => $email,
            'voucher_code' => 'WELCOME10',
        ]);

        return response()->json([
            'success' => true,
            'already_subscribed' => false,
            'message' => 'Terima kasih! Email Anda berhasil didaftarkan. Nikmati voucher sambutan WELCOME10 untuk diskon belanja pertamamu.',
            'voucher_code' => 'WELCOME10',
        ]);
    }
}
