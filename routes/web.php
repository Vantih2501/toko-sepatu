<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    $produk = Produk::where('status', 1)->with('kategori')->orderBy('created_at', 'desc')->take(8)->get();
    return view('welcome', compact('produk'));
});

Route::get('/produk/{id}', function($id) {
    $produk = Produk::with(['kategori', 'fotoProduk'])->findOrFail($id);
    return view('produk.show', compact('produk'));
})->name('produk.show');

// Search / Catalog
Route::get('/shop', function(Request $request) {
    $query = Produk::where('status', 1)->with('kategori');

    if ($request->filled('q')) {
        $query->where('nama_produk', 'like', '%' . $request->q . '%');
    }
    if ($request->filled('kategori_id')) {
        $query->where('kategori_id', $request->kategori_id);
    }
    if ($request->filled('harga_min')) {
        $query->where('harga', '>=', $request->harga_min);
    }
    if ($request->filled('harga_max')) {
        $query->where('harga', '<=', $request->harga_max);
    }

    switch($request->sort) {
        case 'harga_asc':  $query->orderBy('harga', 'asc');  break;
        case 'harga_desc': $query->orderBy('harga', 'desc'); break;
        case 'nama_asc':   $query->orderBy('nama_produk', 'asc'); break;
        default:           $query->orderBy('created_at', 'desc'); break;
    }

    $produk = $query->get();
    $kategori = Kategori::all();
    return view('produk.index', compact('produk', 'kategori'));
})->name('produk.search');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

use App\Http\Controllers\CheckoutController;
use App\Models\Keranjang;
use App\Models\Transaksi;

Route::middleware('auth')->group(function () {
    Route::get('/api/provinces', [CheckoutController::class, 'getProvinces']);
    Route::get('/api/cities/{province_id}', [CheckoutController::class, 'getCities']);
    Route::post('/api/check-ongkir', [CheckoutController::class, 'checkOngkir']);
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Keranjang
    Route::get('/keranjang', function() {
        $keranjang = Keranjang::where('user_id', Auth::id())->with('produk')->get();
        return view('keranjang.index', compact('keranjang'));
    })->name('keranjang.index');
    
    Route::post('/keranjang/add', function(Request $request) {
        // Cek apakah produk sudah ada di keranjang (same size)
        $existing = Keranjang::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->where('ukuran_sepatu', $request->ukuran_sepatu)
            ->first();

        if ($existing) {
            $existing->increment('qty', max(1, (int) $request->qty));
        } else {
            Keranjang::create([
                'user_id' => Auth::id(),
                'produk_id' => $request->produk_id,
                'ukuran_sepatu' => $request->ukuran_sepatu,
                'qty' => max(1, (int) $request->qty)
            ]);
        }
        return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    })->name('keranjang.add');
    
    Route::put('/keranjang/{id}', function($id, Request $request) {
        $item = Keranjang::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if ($request->action === 'plus') {
            $item->increment('qty');
        } elseif ($request->action === 'minus') {
            if ($item->qty > 1) {
                $item->decrement('qty');
            } else {
                $item->delete();
                return redirect()->route('keranjang.index')->with('success', 'Item dihapus dari keranjang.');
            }
        }
        return redirect()->route('keranjang.index');
    })->name('keranjang.update');

    Route::delete('/keranjang/{id}', function($id) {
        Keranjang::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->route('keranjang.index')->with('success', 'Item removed');
    })->name('keranjang.delete');

    // Checkout View
    Route::get('/checkout', function(Request $request) {
        $keranjang = Keranjang::where('user_id', Auth::id())->with('produk')->get();
        return view('checkout.index', compact('keranjang'));
    })->name('checkout.index');

    // History
    Route::get('/transaksi/history', function() {
        $transaksi = Transaksi::where('user_id', Auth::id())->with(['detail.produk'])->orderBy('created_at', 'desc')->get();
        return view('transaksi.history', compact('transaksi'));
    })->name('transaksi.history');
});

Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda')->middleware('auth');

Route::get('backend/login', [GoogleAuthController::class, 'redirect'])->name('backend.login');
Route::get('login', [GoogleAuthController::class, 'redirect'])->name('login'); // Added for auth middleware redirect
Route::post('backend/login', [LoginController::class, 'authenticateBackend']);
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

// Route User
Route::resource('backend/user', UserController::class, ['as' => 'backend'])->middleware('auth');
Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])->name('backend.laporan.formuser')->middleware('auth');
Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])->name('backend.laporan.cetakuser')->middleware('auth');

// Route Kategori
Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])->middleware('auth');

// Route Produk
Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])->middleware('auth');
Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])->name('backend.foto_produk.store')->middleware('auth');
Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('backend.foto_produk.destroy')->middleware('auth');
Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])->name('backend.laporan.formproduk')->middleware('auth');
Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])->name('backend.laporan.cetakproduk')->middleware('auth');
