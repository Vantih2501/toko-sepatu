<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Produk;

class SneakerSeeder extends Seeder
{
    public function run()
    {
        $kategori = Kategori::firstOrCreate(['nama_kategori' => 'Sneakers']);
        
        $images = [
            'image.png' => 'Nike Dunk Low Retro',
            'image (1).png' => 'Air Jordan 1 Mid',
            'image (2).png' => 'Adidas Samba OG',
            'image (3).png' => 'New Balance 550',
            'image (4).png' => 'Nike Air Force 1',
            'image (5).png' => 'Yeezy Boost 350',
            'image (6).png' => 'Converse Chuck 70',
            'image (7).png' => 'Vans Old Skool',
            'image (8).png' => 'Puma Suede Classic'
        ];

        foreach ($images as $img => $name) {
            Produk::create([
                'kategori_id' => $kategori->id,
                'user_id' => 1,
                'status' => 1,
                'nama_produk' => $name,
                'detail' => 'Premium authentic sneaker with top quality materials. Step up your style with this iconic piece.',
                'harga' => rand(15, 35) * 100000,
                'stok' => 50,
                'berat' => 800,
                'foto' => $img
            ]);
        }
    }
}
