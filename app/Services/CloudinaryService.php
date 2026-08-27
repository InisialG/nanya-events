<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CloudinaryService
{
    /**
     * Upload ke Cloudinary — mendukung 3 tipe input:
     * 1. UploadedFile instance (dari form upload biasa)
     * 2. String path file absolut
     * 3. Base64 data URI (dari chunked JS upload, format: "data:image/jpeg;base64,...")
     */
    public static function upload($fileInput, $folder = 'nanya-payment-proofs')
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            return [
                'success' => false,
                'error' => 'Credentials Cloudinary belum lengkap di file .env'
            ];
        }

        if (empty($fileInput)) {
            return [
                'success' => false,
                'error' => 'File tidak boleh kosong.'
            ];
        }

        $timestamp = time();
        $stringToSign = "folder={$folder}&timestamp={$timestamp}" . $apiSecret;
        $signature = sha1($stringToSign);

        $url = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

        try {
            // Deteksi tipe input: base64 data URI atau file
            $isBase64 = is_string($fileInput) && preg_match('/^data:image\//', $fileInput);

            if ($isBase64) {
                // Validasi: pastikan base64 body tidak kosong
                $commaPos = strpos($fileInput, ',');
                if ($commaPos === false || strlen($fileInput) <= $commaPos + 10) {
                    return [
                        'success' => false,
                        'error' => 'Base64 data kosong atau terlalu pendek (panjang: ' . strlen($fileInput) . ')'
                    ];
                }

                // === MODE BASE64 DATA URI ===
                // Cloudinary API menerima data URI sebagai form-encoded 'file' parameter
                // HARUS pakai asForm() — bukan JSON!
                $response = Http::asForm()->post($url, [
                    'file' => $fileInput,
                    'api_key' => $apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                    'folder' => $folder,
                ]);
            } else {
                // === MODE FILE (UploadedFile atau path string) ===
                $realPath = is_string($fileInput) ? $fileInput : $fileInput->getRealPath();

                if (empty($realPath) || !file_exists($realPath)) {
                    return [
                        'success' => false,
                        'error' => 'File fisik tidak ditemukan di: ' . ($realPath ?: 'path kosong')
                    ];
                }

                $response = Http::attach(
                    'file',
                    file_get_contents($realPath),
                    basename($realPath)
                )->post($url, [
                    'api_key' => $apiKey,
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                    'folder' => $folder,
                ]);
            }

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'url' => $data['secure_url'] ?? null
                ];
            }

            // Gunakan error_log bukan Log::error (storage/logs read-only di CWP)
            error_log('Cloudinary Upload Failed: ' . $response->body());
            return [
                'success' => false,
                'error' => 'Cloudinary gagal: ' . ($response->json()['error']['message'] ?? $response->body())
            ];
        } catch (\Exception $e) {
            error_log('Cloudinary Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
