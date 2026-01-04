<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\QRService;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QRController extends Controller
{
    protected $qrService;

    public function __construct(QRService $qrService)
    {
        $this->qrService = $qrService;
    }

    /**
     * Generar nuevo QR token (solo admins)
     */
    public function generate(Request $request)
    {
        $company = Auth::user()->company;

        if (!$company->isQrLoginEnabled()) {
            return response()->json([
                'message' => 'El login por QR está deshabilitado para esta empresa',
            ], 403);
        }

        $qrData = $this->qrService->generateQRToken($company);

        return response()->json([
            'qr_data' => $qrData['qr_data'],
            'token' => $qrData['token'],
            'expires_at' => $qrData['expires_at']->toIso8601String(),
            'ttl' => $qrData['ttl'],
        ]);
    }

    /**
     * Obtener QR token actual (sin regenerar)
     */
    public function current(Request $request)
    {
        $company = Auth::user()->company;

        $qrData = $this->qrService->getCurrentQRToken($company);

        if (!$qrData) {
            return response()->json([
                'message' => 'No hay token QR activo',
            ], 404);
        }

        return response()->json([
            'qr_data' => $qrData['qr_data'],
            'token' => $qrData['token'],
            'expires_at' => $qrData['expires_at']->toIso8601String(),
            'ttl' => $qrData['ttl'],
        ]);
    }

    /**
     * Validar QR token (público, sin auth)
     */
    public function validate(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'company_id' => 'required|integer',
        ]);

        $company = $this->qrService->validateQRToken(
            $request->token,
            $request->company_id
        );

        if (!$company) {
            return response()->json([
                'valid' => false,
                'message' => 'Token QR inválido o expirado',
            ], 400);
        }

        // Consumir el token (invalidarlo)
        $this->qrService->consumeQRToken($company);

        return response()->json([
            'valid' => true,
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
            ],
        ]);
    }
}
