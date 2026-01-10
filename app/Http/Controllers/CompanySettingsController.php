<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanySettingsRequest;
use App\Services\QRService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySettingsController extends Controller
{
    protected $qrService;

    public function __construct(QRService $qrService)
    {
        $this->qrService = $qrService;
    }

    /**
     * Show the company settings page
     */
    public function show()
    {
        $user = Auth::user();
        
        // Only admin/supervisor can access
        if (!$user || (!$user->isAdmin() && !$user->isSupervisor())) {
            abort(403);
        }

        $company = $user->company;
        
        if (!$company) {
            abort(404);
        }

        // Get fixed QR token if exists
        $fixedQR = $this->qrService->getFixedQRToken($company);

        return view('company.settings', compact('company', 'fixedQR'));
    }

    /**
     * Update company settings
     * Only Admin can update settings.
     */
    public function update(UpdateCompanySettingsRequest $request)
    {
        $user = Auth::user();
        
        // Only Admin can update settings
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Solo los administradores pueden actualizar la configuración de la empresa.');
        }
        
        $company = $user->company;

        $data = $request->validated();
        
        $company->update($data);

        return redirect()->route('company.settings')
            ->with('message', __('companies.settings_updated_successfully'));
    }

    /**
     * Generate or regenerate fixed QR token
     * Only Admin can generate QR tokens.
     */
    public function generateFixedQR()
    {
        $user = Auth::user();
        
        // Only Admin can generate
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Solo los administradores pueden generar códigos QR.');
        }

        $company = $user->company;
        
        if (!$company) {
            abort(404);
        }

        $qrData = $this->qrService->generateFixedQRToken($company);

        return redirect()->route('company.settings')
            ->with('message', __('companies.fixed_qr_generated_successfully'))
            ->with('qr_data', $qrData['qr_data']);
    }

    /**
     * Download fixed QR code as image
     * Both Admin and Supervisor can download (read-only access).
     */
    public function downloadFixedQR()
    {
        $user = Auth::user();
        
        // Admin and Supervisor can download
        if (!$user || (!$user->isAdmin() && !$user->isSupervisor())) {
            abort(403);
        }

        $company = $user->company;
        
        if (!$company || !$company->fixed_qr_token) {
            abort(404, __('companies.no_fixed_qr_token'));
        }

        $qrData = $this->qrService->getFixedQRToken($company);
        
        if (!$qrData) {
            abort(404, __('companies.no_fixed_qr_token'));
        }

        // Generate QR code using a simple API or library
        // Using a CDN-based QR generator for simplicity
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($qrData['qr_data']);
        
        // Download and return the image
        $imageContent = file_get_contents($qrUrl);
        
        $filename = 'qr-checkin-' . $company->slug . '-' . date('Y-m-d') . '.png';
        
        return response($imageContent)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}

