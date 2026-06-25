<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed == $request->signature_key) {
            $order_id = explode('-', $request->order_id)[0];
            $transaksi = Transaksi::find($order_id);
            
            if ($transaksi) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $transaksi->update(['status_pembayaran' => 'sukses']);
                } else if ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                    $transaksi->update(['status_pembayaran' => 'gagal']);
                }
            }
            
            return response()->json(['status' => 'success']);
        }
        
        Log::error('Invalid signature from Midtrans', $request->all());
        return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
    }
}
