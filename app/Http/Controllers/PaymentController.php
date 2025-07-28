<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Checkout;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['checkout.user', 'checkout.paymentMethod'])->get();

        $formatted = $payments->map(function ($payment) {
            $method = $payment->checkout->paymentMethod;

            return [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'transfer_proof' => $payment->transfer_proof,
                'note' => $payment->note,
                'status' => $payment->status,
                'checkout_id' => $payment->checkout_id,
                'user' => $payment->checkout->user->name ?? null,
                'bank_name' => $method->name ?? null,
                'account_number' => $method->account_number ?? null,
                'account_holder' => $method->account_name ?? null,
                'created_at' => $payment->created_at,
            ];
        });

        return response()->json($formatted);
    }

    public function show($id)
    {
        $payment = Payment::with(['checkout.user', 'checkout.paymentMethod'])->findOrFail($id);
        $method = $payment->checkout->paymentMethod;

        return response()->json([
            'id' => $payment->id,
            'amount' => $payment->amount,
            'transfer_proof' => $payment->transfer_proof,
            'note' => $payment->note,
            'status' => $payment->status,
            'checkout_id' => $payment->checkout_id,
            'user' => $payment->checkout->user->name ?? null,
            'bank_name' => $method->name ?? null,
            'account_number' => $method->account_number ?? null,
            'account_holder' => $method->account_name ?? null,
            'created_at' => $payment->created_at,
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'checkout_id' => 'required|exists:checkouts,id',
        'transfer_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // validasi file
    ]);

    $checkout = Checkout::with('paymentMethod')->findOrFail($request->checkout_id);

    $data = [
        'checkout_id' => $checkout->id,
        'payment_method_id' => $checkout->payment_method_id,
        'amount' => $checkout->total,
        'status' => 'pending',
    ];

    // Hanya isi transfer_proof jika benar file
    if ($request->hasFile('transfer_proof')) {
        $data['transfer_proof'] = $request->file('transfer_proof')->store('transfer_proofs', 'public');
        \Log::info('Stored path:', [$data['transfer_proof']]);
        \Log::info('Payment data:', $data);
    }

    $payment = Payment::create($data);
    return response()->json($payment, 201);
}


    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'transfer_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'note' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $data = $request->only(['note', 'status']);

        if ($request->hasFile('transfer_proof')) {
            if ($payment->transfer_proof) {
                Storage::disk('public')->delete($payment->transfer_proof);
            }

            $data['transfer_proof'] = $request->file('transfer_proof')->store('transfer_proofs', 'public');
        }

        $payment->update($data);
        return response()->json($payment);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->transfer_proof) {
            Storage::disk('public')->delete($payment->transfer_proof);
        }

        $payment->delete();
        return response()->json(['message' => 'Payment deleted']);
    }

    public function confirmPayment($id)
{
    $payment = Payment::with('checkout.items')->findOrFail($id);

    if ($payment->status === 'success') {
        return response()->json(['message' => 'Payment already confirmed'], 400);
    }

    DB::transaction(function () use ($payment) {
        $payment->status = 'success';
        $payment->save();

        foreach ($payment->checkout->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock -= $item->quantity;
                $product->stock = max($product->stock, 0); // supaya gak minus
                $product->save();
            }
        }
    });

    return response()->json(['message' => 'Payment confirmed and stock updated']);
}

}
