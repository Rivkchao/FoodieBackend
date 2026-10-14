<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan #{{ $order->order_id }} - Foodie</title>
    <!-- Favicon (Same as Frontend 🍔) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍔</text></svg>">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f5f4;
            padding: 24px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
        }

        /* Container Tombol Aksi */
        .no-print {
            width: 80mm;
            max-width: 100%;
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-print {
            flex: 2;
            background-color: #ea580c;
            color: #ffffff;
            border: none;
            box-shadow: 0 2px 6px rgba(234, 88, 12, 0.3);
        }

        .btn-print:hover {
            background-color: #c2410c;
        }

        .btn-secondary {
            flex: 1;
            background-color: #ffffff;
            color: #44403c;
            border: 1px solid #d6d3d1;
        }

        .btn-secondary:hover {
            background-color: #f5f5f4;
        }

        /* Struk Thermal Fit-To-Content */
        .receipt-card {
            background-color: #ffffff;
            width: 80mm;
            padding: 20px 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12.5px;
            color: #1c1917;
            line-height: 1.4;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }

        .divider {
            border-top: 1px dashed #78716c;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 3px 0;
            vertical-align: top;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Print Media Styles: Fit to content without browser headers */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .receipt-card {
                width: 100% !important;
                max-width: 80mm !important;
                margin: 0 auto;
                padding: 4mm 2mm;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
            }
            @page {
                size: 80mm auto;
                margin: 2mm 0;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Aksi Layar Browser -->
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Cetak / Download PDF
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            Tutup
        </button>
    </div>

    <!-- Fisik Struk Thermal -->
    <div class="receipt-card">
        <div class="text-center">
            <div style="font-size: 20px; font-weight: 900; letter-spacing: 1px; color: #ea580c;">🍔 FOODIE</div>
            <div style="font-size: 11px; margin-top: 2px; color: #57534e;">Delicious Food Delivered Fast</div>
            <div style="font-size: 10px; color: #78716c;">Order Hotline: 0812-3456-7890</div>
        </div>

        <div class="divider"></div>

        <table>
            <tr>
                <td>No. Pesanan</td>
                <td class="text-right bold">{{ $order->order_id }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td class="text-right">{{ $order->customer_name }}</td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td class="text-right">{{ $order->customer_phone }}</td>
            </tr>
            <tr>
                <td>Waktu Pesan</td>
                <td class="text-right">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Status Pesanan</td>
                <td class="text-right bold">{{ $order->status_label }}</td>
            </tr>
            <tr>
                <td>Layanan</td>
                <td class="text-right bold">{{ $order->delivery_type === 'express' ? 'Prioritas Kilat' : 'Reguler Foodie' }}</td>
            </tr>
            <tr>
                <td>Status Bayar</td>
                <td class="text-right">
                    @if($order->status === 'processing' || $order->status === 'completed' || $order->status === 'on_delivery' || $order->paid_at)
                        <span class="status-badge status-paid">LUNAS</span>
                    @else
                        <span class="status-badge status-pending">PENDING (COD)</span>
                    @endif
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Daftar Menu Makanan -->
        <table>
            @foreach($order->items as $item)
                <tr>
                    <td colspan="2" class="bold">{{ $item->food_name }}</td>
                </tr>
                <tr>
                    <td>{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <!-- Rincian Biaya -->
        <table>
            <tr>
                <td>Subtotal Menu</td>
                <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Ongkos Kirim</td>
                <td class="text-right">
                    @if($order->delivery_fee == 0)
                        GRATIS
                    @else
                        Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Biaya Layanan</td>
                <td class="text-right">Rp {{ number_format($order->platform_fee, 0, ',', '.') }}</td>
            </tr>
            @if($order->discount_amount > 0)
                <tr style="color: #ea580c; font-weight: bold;">
                    <td>Diskon Voucher ({{ $order->voucher_code ?? 'PROMO' }})</td>
                    <td class="text-right">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="bold" style="font-size: 14px;">
                <td style="padding-top: 6px;">TOTAL AKHIR</td>
                <td class="text-right" style="padding-top: 6px; color: #ea580c;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Metode Bayar</td>
                <td class="text-right bold" style="font-size: 11px;">
                    {{ $order->payment_channel ?? ($order->payment_method === 'cash' ? 'Tunai (COD)' : 'Midtrans Digital') }}
                </td>
            </tr>
        </table>

        @if($order->customer_address)
            <div class="divider"></div>
            <div style="font-size: 10.5px; color: #57534e;">
                <div class="bold" style="color: #1c1917; margin-bottom: 2px;">Alamat Pengantaran:</div>
                <div>{{ $order->customer_address }}</div>
                @if($order->notes)
                    <div style="margin-top: 4px; font-style: italic;">Catatan: {{ $order->notes }}</div>
                @endif
            </div>
        @endif

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 10px;">
            <div class="bold">Terima Kasih Atas Pesanan Anda!</div>
            <div style="font-size: 10px; color: #78716c; margin-top: 3px;">Selamat Menikmati Hidangan Lezat Foodie</div>
        </div>
    </div>

</body>
</html>
