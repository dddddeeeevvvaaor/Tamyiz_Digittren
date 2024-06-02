<?php

namespace Database\Seeders;

use App\Models\WhatsAppAPI;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhatsappApiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WhatsAppAPI::create([
            'token' => 'kIRmPDVUV4kzyzx7lHizarLznu1Nly0ML68cIQJf6opyT7FF8PY1uc2g4SPRkdZu',
            'base_server' => 'https://jogja.wablas.com',
        ]);
    }
}
