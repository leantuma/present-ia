<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QRService
{
    /**
     * TTL del token QR en segundos (30-60 segundos)
     */
    private const QR_TOKEN_TTL = 45;

    /**
     * Genera un nuevo token QR para una empresa
     *
     * @param Company $company
     * @return array ['token' => string, 'expires_at' => Carbon, 'qr_data' => string]
     */
    public function generateQRToken(Company $company): array
    {
        // Generar token único
        $token = Str::random(32);
        $expiresAt = Carbon::now()->addSeconds(self::QR_TOKEN_TTL);

        // Actualizar token en la empresa
        $company->update([
            'qr_token' => $token,
            'qr_token_expires_at' => $expiresAt,
        ]);

        // Datos para el QR (JSON con token y company_id)
        $qrData = json_encode([
            'token' => $token,
            'company_id' => $company->id,
            'expires_at' => $expiresAt->toIso8601String(),
        ]);

        return [
            'token' => $token,
            'expires_at' => $expiresAt,
            'qr_data' => $qrData,
            'ttl' => self::QR_TOKEN_TTL,
        ];
    }

    /**
     * Valida un token QR
     *
     * @param string $token
     * @param int $companyId
     * @return Company|null
     */
    public function validateQRToken(string $token, int $companyId): ?Company
    {
        $company = Company::where('id', $companyId)
            ->where('qr_token', $token)
            ->where('qr_token_expires_at', '>', Carbon::now())
            ->where('qr_login_enabled', true)
            ->where('is_active', true)
            ->first();

        return $company;
    }

    /**
     * Consume el token QR (lo invalida después de usarlo)
     *
     * @param Company $company
     * @return void
     */
    public function consumeQRToken(Company $company): void
    {
        $company->update([
            'qr_token' => null,
            'qr_token_expires_at' => null,
        ]);
    }

    /**
     * Obtiene el token QR actual de una empresa (sin regenerarlo)
     *
     * @param Company $company
     * @return array|null
     */
    public function getCurrentQRToken(Company $company): ?array
    {
        if (!$company->qr_token || !$company->qr_token_expires_at) {
            return null;
        }

        if ($company->qr_token_expires_at->isPast()) {
            return null;
        }

        $qrData = json_encode([
            'token' => $company->qr_token,
            'company_id' => $company->id,
            'expires_at' => $company->qr_token_expires_at->toIso8601String(),
        ]);

        return [
            'token' => $company->qr_token,
            'expires_at' => $company->qr_token_expires_at,
            'qr_data' => $qrData,
            'ttl' => max(0, Carbon::now()->diffInSeconds($company->qr_token_expires_at)),
        ];
    }
}

