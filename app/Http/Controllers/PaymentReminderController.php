<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\PaymentReminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentReminderController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $reminders = $this->applyListFilters(
            PaymentReminder::with('invoice:id,number,due_date')->latest(),
            $request,
            ['subject', 'message']
        )->paginate(20)->withQueryString();

        if ($request->filled('channel') && in_array($request->input('channel'), ['email', 'whatsapp', 'phone'])) {
            $reminders->where('channel', $request->input('channel'));
        }
        $unpaidInvoices = \App\Models\Invoice::whereNotIn('status', ['paid', 'void'])->orderBy('due_date')->get(['id', 'number', 'due_date']);
        return view('erp.payment-reminders.index', compact('reminders', 'unpaidInvoices'));
    }

    public function create(): View
    {
        $unpaidInvoices = \App\Models\Invoice::whereNotIn('status', ['paid', 'void'])->orderBy('due_date')->get(['id', 'number', 'due_date']);
        return view('erp.payment-reminders.create', compact('unpaidInvoices'));
    }

    public function edit(PaymentReminder $paymentReminder): View
    {
        $unpaidInvoices = \App\Models\Invoice::whereNotIn('status', ['paid', 'void'])->orderBy('due_date')->get(['id', 'number', 'due_date']);
        return view('erp.payment-reminders.edit', compact('paymentReminder', 'unpaidInvoices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $reminder = PaymentReminder::create($request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'reminder_date' => ['required', 'date'],
            'channel' => ['required', Rule::in(['email', 'whatsapp', 'phone'])],
            'status' => ['required', Rule::in(['scheduled', 'sent', 'cancelled'])],
            'message' => ['nullable'],
        ]));

        $this->audit('created', $reminder, 'Payment reminder dibuat');

        return redirect()->route('payment-reminders.index')->with('status', 'Reminder pembayaran berhasil dijadwalkan.');
    }

    public function update(Request $request, PaymentReminder $paymentReminder): RedirectResponse
    {
        $old = $paymentReminder->toArray();
        $paymentReminder->update($request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'reminder_date' => ['required', 'date'],
            'channel' => ['required', Rule::in(['email', 'whatsapp', 'phone'])],
            'status' => ['required', Rule::in(['scheduled', 'sent', 'cancelled'])],
            'message' => ['nullable'],
        ]));

        $this->audit('updated', $paymentReminder, 'Payment reminder diedit', $old, $paymentReminder->fresh()->toArray());

        return redirect()->route('payment-reminders.index')->with('status', 'Reminder pembayaran berhasil diupdate.');
    }

    public function destroy(PaymentReminder $paymentReminder): RedirectResponse
    {
        $this->audit('deleted', $paymentReminder, 'Payment reminder dihapus', $paymentReminder->toArray());
        $paymentReminder->delete();

        return redirect()->route('payment-reminders.index')->with('status', 'Reminder pembayaran berhasil dihapus.');
    }
}
