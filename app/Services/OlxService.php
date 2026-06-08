<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class OlxService
{
    public function fetchPrice($url)
    {
        try {
            $response = Http::withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36')
                ->get($url);

            $html = $response->body();

            $marker = 'window.__PRERENDERED_STATE__= "';
            $startPos = strpos($html, $marker);

            $jsonStart = $startPos + strlen($marker);

            $htmlFromJson = substr($html, $jsonStart);

            $endPos = strpos($htmlFromJson, '";');

            $rawJson = substr($htmlFromJson, 0, $endPos);

            $cleanJsonString = json_decode('"' . $rawJson . '"');
            $data = json_decode($cleanJsonString, true);
            $ad = $data['ad']['ad'] ?? null;

            if (!$ad) {
                throw new Exception("Could not extract ad data from the page.");
            }
            return [
                'olx_id'   => (int) ($ad['id'] ?? 0),
                'price'    => (float) ($ad['price']['regularPrice']['value'] ?? 0),
                'currency' => (string) ($ad['price']['regularPrice']['currencyCode'] ?? 'UAH'),
                'title'    => (string) ($ad['title'] ?? ''),
            ];
        } catch (Exception $e) {
            activity('parser')->error($e->getMessage());
            return null;
        }
    }

    public function getPrice(int $olxId): ?float
    {
        $response = Http::withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36')
            ->get('https://m.olx.ua/api/v1/offers/' . $olxId);

        if ($response->failed()) {
            return null;
        }

        $params = $response->json('data.params') ?? [];
        $priceParam = collect($params)->firstWhere('key', 'price');

        $price = $priceParam['value']['value'] ?? null;

        return $price ? (float)$price : null;
    }
}