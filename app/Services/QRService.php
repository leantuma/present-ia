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
     * Valida un token QR (fijo o temporal)
     *
     * @param string $token
     * @param int $companyId
     * @return array|null ['company' => Company, 'is_fixed' => bool]
     */
    public function validateQRToken(string $token, int $companyId): ?array
    {
        // Primero intentar validar como token fijo (no expira)
        $company = $this->validateFixedQRToken($token, $companyId);
        if ($company) {
            return [
                'company' => $company,
                'is_fixed' => true,
            ];
        }

        // Si no es fijo, validar como token temporal (con expiración)
        $company = Company::where('id', $companyId)
            ->where('qr_token', $token)
            ->where('qr_token_expires_at', '>', Carbon::now())
            ->where('qr_login_enabled', true)
            ->where('is_active', true)
            ->first();

        if ($company) {
            return [
                'company' => $company,
                'is_fixed' => false,
            ];
        }

        return null;
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

    /**
     * Genera un token QR fijo permanente para una empresa
     *
     * @param Company $company
     * @return array ['token' => string, 'qr_data' => string]
     */
    public function generateFixedQRToken(Company $company): array
    {
        // Generar token único
        $token = 'fixed_' . Str::random(32);

        // Actualizar token en la empresa
        $company->update([
            'fixed_qr_token' => $token,
        ]);

        // Datos para el QR (JSON con tipo, token y company_id)
        $qrData = json_encode([
            'type' => 'checkin',
            'company_id' => $company->id,
            'token' => $token,
        ]);

        return [
            'token' => $token,
            'qr_data' => $qrData,
        ];
    }

    /**
     * Obtiene el token QR fijo de una empresa (sin regenerarlo)
     *
     * @param Company $company
     * @return array|null
     */
    public function getFixedQRToken(Company $company): ?array
    {
        if (!$company->fixed_qr_token) {
            return null;
        }

        $qrData = json_encode([
            'type' => 'checkin',
            'company_id' => $company->id,
            'token' => $company->fixed_qr_token,
        ]);

        return [
            'token' => $company->fixed_qr_token,
            'qr_data' => $qrData,
        ];
    }

    /**
     * Valida un token QR fijo
     *
     * @param string $token
     * @param int $companyId
     * @return Company|null
     */
    public function validateFixedQRToken(string $token, int $companyId): ?Company
    {
        $company = Company::where('id', $companyId)
            ->where('fixed_qr_token', $token)
            ->where('qr_login_enabled', true)
            ->where('is_active', true)
            ->first();

        return $company;
    }
}

