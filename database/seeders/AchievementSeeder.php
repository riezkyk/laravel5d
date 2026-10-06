<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Seed the built-in achievements for Fish Store Penjualan Ikan.
     */
    public function run(): void
    {
        $achievements = [
            ['Penjualan Perdana', 'Mencatat transaksi penjualan ikan pertama.', 'logs:1'],
            ['Juragan Ikan 7 Hari', 'Melakukan transaksi penjualan ikan selama 7 hari berturut-turut.', 'streak:7'],
            ['Super Seller 30 Hari', 'Konsisten melakukan penjualan ikan selama 30 hari.', 'streak:30'],
            ['Ulasan Pembeli', 'Mendapatkan ulasan transaksi dari pembeli.', 'moods:14'],
            ['Laporan Harian', 'Menulis 10 catatan laporan transaksi penjualan ikan.', 'journals:10'],
        ];

        foreach ($achievements as [$name, $description, $criteria]) {
            Achievement::updateOrCreate(['name' => $name], compact('description', 'criteria'));
        }
    }
}
