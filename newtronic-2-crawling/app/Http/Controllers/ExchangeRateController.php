<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\ExchangeRate;

class ExchangeRateController extends Controller
{
    public function crawlAndStore()
    {
        $client = new Client();
        $url = 'https://www.smartdeal.co.id/rates/dki_banten';

        // Request halaman
        $response = $client->get($url);
        $html = (string) $response->getBody();

        // Crawler untuk parsing HTML
        $crawler = new Crawler($html);

        // Ambil data dari tabel kurs
        $crawler->filter('table tr')->each(function (Crawler $node) {
            // Abaikan baris pertama (header)
            if ($node->children()->count() > 1) {
                $currency = $node->children()->eq(0)->text();
                $buyRate = $node->children()->eq(1)->text();
                $sellRate = $node->children()->eq(2)->text();

                // Simpan data ke database
                ExchangeRate::create([
                    'currency' => trim($currency),
                    'buy_rate' => trim($buyRate),
                    'sell_rate' => trim($sellRate),
                ]);
            }
        });

        // Ambil semua data yang telah disimpan
        $exchangeRates = ExchangeRate::all();

        // Tampilkan data dalam format JSON
        return response()->json($exchangeRates);
    }
}
