<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminVoucherController extends Controller
{
    /**
     * List all vouchers
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = Voucher::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }

        $vouchers = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Voucher::count(),
            'active' => Voucher::where('is_active', true)->count(),
            'total_used' => Voucher::sum('used_count'),
        ];

        return view('admin.vouchers.index', compact('vouchers', 'search', 'stats'));
    }

    /**
     * Store new voucher
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'name' => 'required|string|max:150',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:1',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'valid_until' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $voucher = Voucher::create([
            'code' => strtoupper(trim($validated['code'])),
            'name' => $validated['name'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'min_spend' => $validated['min_spend'] ?: 0,
            'max_discount' => $validated['max_discount'] ?: null,
            'quota' => $validated['quota'],
            'used_count' => 0,
            'valid_until' => $validated['valid_until'] ?: null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.vouchers.index')
            ->with('success', "Voucher '{$voucher->code}' berhasil dibuat!");
    }

    /**
     * Update voucher
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $voucher = Voucher::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $id,
            'name' => 'required|string|max:150',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:1',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'valid_until' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $voucher->update([
            'code' => strtoupper(trim($validated['code'])),
            'name' => $validated['name'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'min_spend' => $validated['min_spend'] ?: 0,
            'max_discount' => $validated['max_discount'] ?: null,
            'quota' => $validated['quota'],
            'valid_until' => $validated['valid_until'] ?: null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.vouchers.index')
            ->with('success', "Voucher '{$voucher->code}' berhasil diperbarui!");
    }

    /**
     * Toggle active status
     */
    public function toggleActive(int $id): RedirectResponse
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        $statusText = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Voucher {$voucher->code} berhasil {$statusText}.");
    }

    /**
     * Delete voucher
     */
    public function destroy(int $id): RedirectResponse
    {
        $voucher = Voucher::findOrFail($id);
        $code = $voucher->code;
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', "Voucher '{$code}' berhasil dihapus.");
    }
}
