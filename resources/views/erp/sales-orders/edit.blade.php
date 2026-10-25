@extends('layouts.erp', ['activePage' => 'sales-orders.edit-page', 'pageTitle' => 'Edit Sales Order'])

@section('content')
<div class="space-y-5 max-w-5xl">
    <x-erp.page-header
        title="Edit Sales Order (SO)"
        subtitle="Perbarui data pesanan penjualan, status deliveri, dan rincian item produk: {{ $order->number }}"
        :breadcrumbs="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Sales & CRM', 'url' => route('sales-crm.index')], ['label' => 'Sales Orders', 'url' => route('sales-orders.index')], ['label' => 'Edit Order']]"
    />

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-xs">
        <form method="post" action="{{ route('sales-orders.update', $order) }}" class="space-y-6">
            @csrf
            @method('put')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Judul Sales Order <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $order->title) }}" required autofocus
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Order <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="order_date" value="{{ old('order_date', \Carbon\Carbon::parse($order->order_date)->format('Y-m-d')) }}" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Dokumen Order <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="draft" @selected($order->status === 'draft')>Draft</option>
                        <option value="confirmed" @selected($order->status === 'confirmed')>Confirmed</option>
                        <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                        <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Sumber Proposal Penawaran
                    </label>
                    <select name="proposal_id"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Proposal (Opsional) --</option>
                        @foreach($proposals as $proposal)
                            <option value="{{ $proposal->id }}" @selected($order->proposal_id === $proposal->id)>
                                {{ $proposal->number ? $proposal->number.' - ' : '' }}{{ $proposal->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Klien / Pemesan
                    </label>
                    <select name="client_id"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">
                        <option value="">-- Pilih Klien --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected($order->client_id === $client->id)>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Catatan / Syarat Deliveri
                    </label>
                    <textarea name="notes" rows="2"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-xs text-slate-900 focus:border-[#002875] focus:outline-none focus:ring-1 focus:ring-[#002875]">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </div>

            {{-- Line Items Table --}}
            <div class="border-t border-slate-200 pt-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#002875]">Rincian Item Produk Pesanan</h3>
                    <button type="button" id="btn-add-row" class="inline-flex items-center gap-1 rounded bg-slate-100 px-2.5 py-1 text-xs font-semibold text-[#002875] hover:bg-slate-200 transition-colors">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                        Tambah Baris Produk
                    </button>
                </div>

                <div class="border border-slate-200 rounded-md overflow-hidden">
                    <table class="table-folk w-full text-left text-xs" id="items-table">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-3 py-2.5 font-semibold text-slate-600 uppercase">Item Produk</th>
                                <th class="px-3 py-2.5 font-semibold text-slate-600 uppercase w-28">Kuantitas</th>
                                <th class="px-3 py-2.5 font-semibold text-slate-600 uppercase w-44">Harga Satuan (Rp)</th>
                                <th class="px-3 py-2.5 font-semibold text-slate-600 uppercase text-right w-44">Subtotal (Rp)</th>
                                <th class="px-3 py-2.5 w-10"></th>
                            </tr>
                        </thead>
                        <tbody id="items-body" class="divide-y divide-slate-100">
                            @forelse($order->items as $i => $item)
                                <tr class="item-row">
                                    <td class="px-3 py-2">
                                        <select name="items[{{ $i }}][product_id]" class="item-product w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none">
                                            <option value="">-- Pilih produk --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" @selected($item->product_id === $product->id)>{{ $product->code }} - {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="any" min="0" class="item-qty w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none" name="items[{{ $i }}][quantity]" value="{{ $item->quantity }}">
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="any" min="0" class="item-price w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none" name="items[{{ $i }}][unit_price]" value="{{ $item->unit_price }}">
                                    </td>
                                    <td class="px-3 py-2 text-right font-bold text-slate-900 item-subtotal">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" class="btn-remove-row text-red-500 hover:text-red-700 font-bold text-base" title="Hapus baris">×</button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="item-row">
                                    <td class="px-3 py-2">
                                        <select name="items[0][product_id]" class="item-product w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none">
                                            <option value="">-- Pilih produk --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->code }} - {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="any" min="0" class="item-qty w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none" name="items[0][quantity]" value="1">
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="any" min="0" class="item-price w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none" name="items[0][unit_price]">
                                    </td>
                                    <td class="px-3 py-2 text-right font-bold text-slate-900 item-subtotal">0</td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" class="btn-remove-row text-red-500 hover:text-red-700 font-bold text-base" title="Hapus baris">×</button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 flex items-center justify-between p-3 rounded-md bg-slate-50 border border-slate-200">
                    <span class="text-xs font-semibold text-slate-600">Total Nilai Order:</span>
                    <div class="text-base font-bold text-[#002875]">
                        Rp <span id="items-total">{{ number_format($order->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
                <input type="hidden" name="amount" value="{{ old('amount', $order->amount) }}" required>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-[#002875] px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-[#001B50] transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    Perbarui Sales Order
                </button>
                <a href="{{ route('sales-orders.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const body = document.getElementById('items-body');
    const totalEl = document.getElementById('items-total');
    const amountInput = document.querySelector('input[name="amount"]');
    const productOptions = @json($productOptions);

    function reindex() {
        body.querySelectorAll('.item-row').forEach((row, i) => {
            row.querySelector('.item-product').name = `items[${i}][product_id]`;
            row.querySelector('.item-qty').name = `items[${i}][quantity]`;
            row.querySelector('.item-price').name = `items[${i}][unit_price]`;
        });
    }

    function subtotal(row) {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const sub = qty * price;
        row.querySelector('.item-subtotal').textContent = sub.toLocaleString('id-ID', { maximumFractionDigits: 0 });
        return sub;
    }

    function recalc() {
        let total = 0;
        body.querySelectorAll('.item-row').forEach((row) => { total += subtotal(row); });
        totalEl.textContent = total.toLocaleString('id-ID', { maximumFractionDigits: 0 });
        if (amountInput) amountInput.value = total ? total.toFixed(2) : 0;
    }

    function newRow() {
        const tpl = document.createElement('tr');
        tpl.className = 'item-row';
        tpl.innerHTML = `
            <td class="px-3 py-2"><select class="item-product w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none">
                <option value="">-- Pilih produk --</option>
                ${productOptions.map(p => `<option value="${p.id}" data-price="${p.price}">${p.code} - ${p.name}</option>`).join('')}
            </select></td>
            <td class="px-3 py-2"><input type="number" step="any" min="0" class="item-qty w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none" value="1"></td>
            <td class="px-3 py-2"><input type="number" step="any" min="0" class="item-price w-full rounded border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:border-[#002875] focus:outline-none"></td>
            <td class="px-3 py-2 text-right font-bold text-slate-900 item-subtotal">0</td>
            <td class="px-3 py-2 text-center"><button type="button" class="btn-remove-row text-red-500 hover:text-red-700 font-bold text-base" title="Hapus baris">×</button></td>`;
        body.appendChild(tpl);
        reindex();
    }

    body.addEventListener('click', (e) => {
        if (e.target.closest('.btn-remove-row')) {
            if (body.querySelectorAll('.item-row').length > 1) {
                e.target.closest('.item-row').remove();
                reindex();
                recalc();
            }
        }
    });

    body.addEventListener('change', (e) => {
        if (e.target.classList.contains('item-product')) {
            const price = e.target.selectedOptions[0]?.dataset?.price || 0;
            e.target.closest('.item-row').querySelector('.item-price').value = price;
        }
        recalc();
    });

    body.addEventListener('input', (e) => {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) recalc();
    });

    document.getElementById('btn-add-row').addEventListener('click', newRow);
    recalc();
})();
</script>
@endsection
