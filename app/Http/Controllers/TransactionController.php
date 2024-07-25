<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    //
    public function index()
    {
        return Transaction::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'customer_id' => 'nullable|exists:customers,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|in:cash,bank_transfer',
        ]);

        $transaction = Transaction::create($request->all());

        return response()->json($transaction, 201);
    }

    public function show(Transaction $transaction)
    {
        return $transaction;
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'order_id' => 'sometimes|exists:orders,id',
            'customer_id' => 'sometimes|exists:customers,id',
            'amount' => 'sometimes|numeric',
            'payment_method' => 'sometimes|in:cash,bank_transfer',
        ]);

        $transaction->update($request->all());

        return response()->json($transaction, 200);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json(null, 204);
    }
}
