<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
@verbatim
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Laporan Penjualan</x:Name>
    <x:WorksheetOptions>
     <x:DisplayGridlines/>
     <x:Print>
      <x:ValidPrinterInfo/>
      <x:Orientation>Landscape</x:Orientation>
     </x:Print>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
@endverbatim
<style>
  body {
    font-family: 'Calibri', 'Segoe UI', Arial, sans-serif;
    font-size: 11pt;
    color: #1e293b;
  }
  table {
    border-collapse: collapse;
    width: 100%;
  }
  .title-header {
    font-size: 16pt;
    font-weight: bold;
    color: #0f172a;
    height: 35px;
  }
  .subtitle {
    font-size: 10.5pt;
    color: #475569;
  }
  .meta-title {
    font-weight: bold;
    color: #334155;
  }
  .th-header {
    background-color: #0f172a;
    color: #ffffff;
    font-weight: bold;
    text-align: center;
    border: 1px solid #334155;
    height: 30px;
    vertical-align: middle;
  }
  .td-cell {
    border: 1px solid #cbd5e1;
    padding: 6px 8px;
    vertical-align: middle;
  }
  .td-center {
    border: 1px solid #cbd5e1;
    padding: 6px 8px;
    text-align: center;
    vertical-align: middle;
  }
  .td-number {
    border: 1px solid #cbd5e1;
    padding: 6px 8px;
    text-align: right;
    mso-number-format: "\#\,\#\#0";
    vertical-align: middle;
  }
  .td-text {
    border: 1px solid #cbd5e1;
    padding: 6px 8px;
    mso-number-format: "\@";
    vertical-align: middle;
  }
  .td-date {
    border: 1px solid #cbd5e1;
    padding: 6px 8px;
    text-align: center;
    mso-number-format: "dd\/mm\/yyyy hh\:mm";
    vertical-align: middle;
  }
  .row-even {
    background-color: #ffffff;
  }
  .row-odd {
    background-color: #f8fafc;
  }
  .total-row {
    background-color: #e2e8f0;
    font-weight: bold;
    border-top: 2px solid #0f172a;
    border-bottom: 2px solid #0f172a;
    height: 28px;
  }
  .summary-box {
    background-color: #f1f5f9;
    font-weight: bold;
    border: 1px solid #cbd5e1;
    padding: 6px 10px;
  }
  .summary-val {
    border: 1px solid #cbd5e1;
    padding: 6px 10px;
    text-align: right;
    mso-number-format: "\#\,\#\#0";
    font-weight: bold;
  }
</style>
</head>
<body>

  <!-- Judul Laporan -->
  <table>
    <tr>
      <td colspan="17" class="title-header">FOODIE EXPRESS - LAPORAN PENJUALAN & TRANSAKSI</td>
    </tr>
    <tr>
      <td colspan="17" class="subtitle">
        Periode: <strong>{{ $start->format('d/m/Y') }}</strong> s/d <strong>{{ $end->format('d/m/Y') }}</strong>
        &nbsp;|&nbsp; Digenerate pada: {{ now()->format('d/m/Y H:i') }} WIB
        &nbsp;|&nbsp; Oleh: Admin Foodie
      </td>
    </tr>
    <tr>
      <td colspan="17">&nbsp;</td>
    </tr>
  </table>

  <!-- Ringkasan Eksekutif -->
  <table>
    <tr>
      <td colspan="3" class="summary-box">RINGKASAN METRIK PERIODE INI</td>
      <td colspan="14">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="summary-box">Total Seluruh Pesanan:</td>
      <td class="summary-val">{{ number_format($summary['total_orders'], 0, ',', '.') }}</td>
      <td colspan="14">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="summary-box">Pesanan Berhasil / Selesai:</td>
      <td class="summary-val">{{ number_format($summary['completed_orders'], 0, ',', '.') }}</td>
      <td colspan="14">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="summary-box">Total Omset Pesanan Selesai (Rp):</td>
      <td class="summary-val">{{ number_format($summary['total_completed_sales'], 0, ',', '.') }}</td>
      <td colspan="14">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="summary-box">Total Keseluruhan Nilai Transaksi (Rp):</td>
      <td class="summary-val">{{ number_format($summary['total_gross_sales'], 0, ',', '.') }}</td>
      <td colspan="14">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="summary-box">Total Potongan Diskon / Voucher (Rp):</td>
      <td class="summary-val">{{ number_format($summary['total_discounts'], 0, ',', '.') }}</td>
      <td colspan="14">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="17">&nbsp;</td>
    </tr>
  </table>

  <!-- Tabel Utama Data Transaksi -->
  <table>
    <thead>
      <tr>
        <th class="th-header" style="width: 40px;">No</th>
        <th class="th-header" style="width: 120px;">ID Pesanan</th>
        <th class="th-header" style="width: 130px;">Tanggal & Waktu</th>
        <th class="th-header" style="width: 150px;">Nama Pelanggan</th>
        <th class="th-header" style="width: 120px;">No. Telepon</th>
        <th class="th-header" style="width: 250px;">Alamat Pengiriman</th>
        <th class="th-header" style="width: 110px;">Layanan Antar</th>
        <th class="th-header" style="width: 100px;">Metode Bayar</th>
        <th class="th-header" style="width: 110px;">Kanal Bayar</th>
        <th class="th-header" style="width: 100px;">Status</th>
        <th class="th-header" style="width: 110px;">Subtotal (Rp)</th>
        <th class="th-header" style="width: 100px;">Ongkir (Rp)</th>
        <th class="th-header" style="width: 100px;">Layanan (Rp)</th>
        <th class="th-header" style="width: 110px;">Kode Voucher</th>
        <th class="th-header" style="width: 110px;">Diskon (Rp)</th>
        <th class="th-header" style="width: 120px;">Total (Rp)</th>
        <th class="th-header" style="width: 300px;">Rincian Item Menu</th>
      </tr>
    </thead>
    <tbody>
      @php
        $sumSubtotal = 0;
        $sumDelivery = 0;
        $sumPlatform = 0;
        $sumDiscount = 0;
        $sumTotal = 0;
      @endphp
      @forelse($orders as $idx => $order)
        @php
          $sumSubtotal += $order->subtotal;
          $sumDelivery += $order->delivery_fee;
          $sumPlatform += $order->platform_fee;
          $sumDiscount += $order->discount_amount;
          $sumTotal += $order->total;

          $itemList = $order->items->map(function ($it) {
              return "{$it->food_name} ({$it->qty}x)";
          })->implode(', ');
          
          $rowClass = $idx % 2 === 0 ? 'row-even' : 'row-odd';
        @endphp
        <tr class="{{ $rowClass }}">
          <td class="td-center">{{ $idx + 1 }}</td>
          <td class="td-text" style="font-weight: bold;">{{ $order->order_id }}</td>
          <td class="td-date">{{ $order->created_at->format('d/m/Y H:i:s') }}</td>
          <td class="td-cell">{{ $order->customer_name }}</td>
          <td class="td-text">{{ $order->customer_phone }}</td>
          <td class="td-cell">{{ $order->customer_address }}</td>
          <td class="td-center">{{ $order->delivery_type === 'express' ? 'Prioritas Kilat' : 'Reguler' }}</td>
          <td class="td-center">{{ in_array(strtolower($order->payment_method), ['cash', 'cod', 'tunai']) ? 'Tunai' : 'Transfer' }}</td>
          <td class="td-center">{{ $order->payment_channel ?? '-' }}</td>
          <td class="td-center" style="font-weight: bold;">{{ $order->status_label }}</td>
          <td class="td-number">{{ $order->subtotal }}</td>
          <td class="td-number">{{ $order->delivery_fee }}</td>
          <td class="td-number">{{ $order->platform_fee }}</td>
          <td class="td-center">{{ $order->voucher_code ?? '-' }}</td>
          <td class="td-number">{{ $order->discount_amount }}</td>
          <td class="td-number" style="font-weight: bold;">{{ $order->total }}</td>
          <td class="td-cell">{{ $itemList }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="17" class="td-center" style="padding: 20px; font-style: italic; color: #64748b;">
            Tidak ada transaksi pada rentang periode yang dipilih.
          </td>
        </tr>
      @endforelse
    </tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="10" class="td-center" style="font-weight: bold; text-align: center;">TOTAL KESELURUHAN</td>
        <td class="td-number" style="font-weight: bold;">{{ $sumSubtotal }}</td>
        <td class="td-number" style="font-weight: bold;">{{ $sumDelivery }}</td>
        <td class="td-number" style="font-weight: bold;">{{ $sumPlatform }}</td>
        <td class="td-center">-</td>
        <td class="td-number" style="font-weight: bold;">{{ $sumDiscount }}</td>
        <td class="td-number" style="font-weight: bold; color: #0f172a;">{{ $sumTotal }}</td>
        <td class="td-cell">&nbsp;</td>
      </tr>
    </tfoot>
  </table>

</body>
</html>
