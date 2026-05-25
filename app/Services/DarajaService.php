<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DarajaService
{
    private string $baseUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private string $b2cConsumerKey;
    private string $b2cConsumerSecret;
    private string $shortcode;
    private string $passkey;
    private string $b2cShortcode;
    private string $initiatorName;
    private string $initiatorPassword;

    public function __construct()
    {
        $sandbox = config('daraja.env') === 'sandbox';

        $this->baseUrl           = $sandbox
            ? 'https://sandbox.safaricom.co.ke'
            : 'https://api.safaricom.co.ke';
        $this->consumerKey       = config('daraja.consumer_key');
        $this->consumerSecret    = config('daraja.consumer_secret');
        $this->b2cConsumerKey    = config('daraja.b2c_consumer_key') ?: config('daraja.consumer_key');
        $this->b2cConsumerSecret = config('daraja.b2c_consumer_secret') ?: config('daraja.consumer_secret');
        $this->shortcode         = config('daraja.shortcode');
        $this->passkey           = config('daraja.passkey');
        $this->b2cShortcode      = config('daraja.b2c_shortcode');
        $this->initiatorName     = config('daraja.b2c_initiator_name');
        $this->initiatorPassword = config('daraja.b2c_initiator_password');
    }

    public function getAccessToken(): ?string
    {
        $cached = Cache::get('daraja_token');
        if ($cached) return $cached;

        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

        if ($response->successful()) {
            $token = $response->json('access_token');
            Cache::put('daraja_token', $token, 3500);
            Log::info('Daraja token fetched', ['token_preview' => substr($token, 0, 8) . '...']);
            return $token;
        }

        Log::error('Daraja token error', ['status' => $response->status(), 'body' => $response->json()]);
        return null;
    }

    public function stkPush(int $amount, string $phone, string $accountRef, string $description): array
    {
        $token     = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);
        $phone     = $this->formatPhone($phone);

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", [
                'BusinessShortCode' => $this->shortcode,
                'Password'          => $password,
                'Timestamp'         => $timestamp,
                'TransactionType'   => config('daraja.transaction_type', 'CustomerBuyGoodsOnline'),
                'Amount'            => $amount,
                'PartyA'            => $phone,
                'PartyB'            => $this->shortcode,
                'PhoneNumber'       => $phone,
                'CallBackURL'       => config('daraja.callback_url'),
                'AccountReference'  => $accountRef,
                'TransactionDesc'   => $description,
            ]);

        Log::info('STK Push', ['phone_masked' => substr($phone, 0, 6) . '****', 'amount' => $amount, 'response' => $response->json()]);

        return $response->json() ?? [];
    }

    public function getB2cAccessToken(): ?string
    {
        $cached = Cache::get('daraja_b2c_token');
        if ($cached) return $cached;

        $response = Http::withBasicAuth($this->b2cConsumerKey, $this->b2cConsumerSecret)
            ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

        if ($response->successful()) {
            $token = $response->json('access_token');
            Cache::put('daraja_b2c_token', $token, 3500);
            return $token;
        }

        Log::error('Daraja B2C token error', ['status' => $response->status(), 'body' => $response->json()]);
        return null;
    }

    public function b2cPayout(int $amount, string $phone, string $remarks): array
    {
        $token = $this->getB2cAccessToken();
        $phone = $this->formatPhone($phone);

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/b2c/v3/paymentrequest", [
                'OriginatorConversationID' => 'PRG-' . uniqid(),
                'InitiatorName'            => $this->initiatorName,
                'SecurityCredential'       => $this->encryptInitiatorPassword(),
                'CommandID'                => 'BusinessPayment',
                'Amount'                   => $amount,
                'PartyA'                   => $this->b2cShortcode,
                'PartyB'                   => $phone,
                'Remarks'                  => $remarks,
                'QueueTimeOutURL'          => config('daraja.b2c_timeout_url'),
                'ResultURL'                => config('daraja.b2c_result_url'),
                'Occasion'                 => 'Pregota Gift',
            ]);

        Log::info('B2C Payout', ['phone_masked' => substr($phone, 0, 6) . '****', 'amount' => $amount, 'response' => $response->json()]);

        return $response->json() ?? [];
    }

    public function b2bPayout(int $amount, string $destination, string $type, string $accountRef, string $remarks = 'Pregota Bill Split'): array
    {
        $token = $this->getB2cAccessToken();

        $commandId      = $type === 'till' ? 'BusinessBuyGoods' : 'BusinessPayBill';
        $receiverIdType = $type === 'till' ? 2                   : 4;

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/b2b/v1/paymentrequest", [
                'Initiator'              => $this->initiatorName,
                'SecurityCredential'     => $this->encryptInitiatorPassword(),
                'CommandID'              => $commandId,
                'SenderIdentifierType'   => 4,
                'RecieverIdentifierType' => $receiverIdType,
                'Amount'                 => $amount,
                'PartyA'                 => $this->b2cShortcode,
                'PartyB'                 => $destination,
                'AccountReference'       => $accountRef,
                'Remarks'                => substr($remarks, 0, 100),
                'QueueTimeOutURL'        => config('daraja.b2b_timeout_url'),
                'ResultURL'              => config('daraja.b2b_result_url'),
            ]);

        Log::info('B2B Payout', ['destination' => $destination, 'type' => $type, 'amount' => $amount, 'response' => $response->json()]);

        return $response->json() ?? [];
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }
        if (str_starts_with($phone, '+')) {
            $phone = ltrim($phone, '+');
        }
        return $phone;
    }

    private function encryptInitiatorPassword(): string
    {
        $certPath = config('daraja.env') === 'sandbox'
            ? storage_path('app/daraja/sandbox-cert.cer')
            : storage_path('app/daraja/production-cert.cer');

        $cert      = file_get_contents($certPath);
        $publicKey = openssl_get_publickey($cert);
        openssl_public_encrypt($this->initiatorPassword, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);

        return base64_encode($encrypted);
    }
}
