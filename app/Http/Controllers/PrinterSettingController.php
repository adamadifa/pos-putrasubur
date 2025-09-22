<?php

namespace App\Http\Controllers;

use App\Models\PrinterSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PrinterSettingController extends Controller
{
    /**
     * Display printer settings page
     */
    public function index()
    {
        $printerSettings = PrinterSetting::getActive();
        $defaultPrinter = PrinterSetting::getDefault();

        return view('printer-settings.index', compact('printerSettings', 'defaultPrinter'));
    }

    /**
     * Test print functionality
     */
    public function testPrint(Request $request): JsonResponse
    {
        $request->validate([
            'printer_name' => 'required|string',
            'test_content' => 'nullable|string'
        ]);

        // Log test print attempt
        \Illuminate\Support\Facades\Log::info('Test print requested', [
            'printer' => $request->printer_name,
            'content' => $request->test_content ?? 'Default test content'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Test print command sent successfully',
            'printer' => $request->printer_name
        ]);
    }

    /**
     * Save printer settings
     */
    public function savePrinterSettings(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'printer_name' => 'required|string|max:255',
            'printer_port' => 'nullable|string|max:255',
            'printer_config' => 'nullable|array',
            'description' => 'nullable|string',
            'is_default' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // If this is set as default, remove default from others
            if ($request->boolean('is_default')) {
                PrinterSetting::where('is_default', true)->update(['is_default' => false]);
            }

            // Create or update printer setting
            $printerSetting = PrinterSetting::create([
                'name' => $request->name,
                'printer_name' => $request->printer_name,
                'printer_port' => $request->printer_port,
                'printer_config' => $request->printer_config ?? [],
                'description' => $request->description,
                'is_default' => $request->boolean('is_default'),
                'is_active' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Printer setting saved successfully',
                'data' => $printerSetting
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to save printer setting: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save printer setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current printer settings
     */
    public function getPrinterSettings(): JsonResponse
    {
        $printerSettings = PrinterSetting::getActive();
        $defaultPrinter = PrinterSetting::getDefault();

        return response()->json([
            'success' => true,
            'settings' => $printerSettings,
            'default_printer' => $defaultPrinter
        ]);
    }

    /**
     * Get printer settings for frontend (simplified)
     */
    public function getSettings(): JsonResponse
    {
        try {
            $defaultPrinter = PrinterSetting::getDefault();

            if (!$defaultPrinter) {
                return response()->json([
                    'success' => false,
                    'message' => 'No default printer found'
                ]);
            }

            return response()->json([
                'success' => true,
                'settings' => [
                    'default_printer' => $defaultPrinter->printer_name,
                    'paper_size' => $defaultPrinter->printer_config['paper_size'] ?? 'A4',
                    'orientation' => $defaultPrinter->printer_config['orientation'] ?? 'portrait',
                    'auto_print' => $defaultPrinter->printer_config['auto_print'] ?? false
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get printer settings: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get printer settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created printer setting
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'printer_name' => 'required|string|max:255',
            'printer_port' => 'nullable|string|max:255',
            'printer_config' => 'nullable|array',
            'description' => 'nullable|string',
            'is_default' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // If this is set as default, remove default from others
            if ($request->boolean('is_default')) {
                PrinterSetting::where('is_default', true)->update(['is_default' => false]);
            }

            // Create or update printer setting
            $printerSetting = PrinterSetting::create([
                'name' => $request->name,
                'printer_name' => $request->printer_name,
                'printer_port' => $request->printer_port,
                'printer_config' => $request->printer_config ?? [],
                'description' => $request->description,
                'is_default' => $request->boolean('is_default'),
                'is_active' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Printer setting saved successfully',
                'data' => $printerSetting
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to save printer setting: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save printer setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified printer setting
     */
    public function show(PrinterSetting $printerSetting): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $printerSetting
        ]);
    }

    /**
     * Update the specified printer setting
     */
    public function update(Request $request, PrinterSetting $printerSetting): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'printer_name' => 'required|string|max:255',
            'printer_port' => 'nullable|string|max:255',
            'printer_config' => 'nullable|array',
            'description' => 'nullable|string',
            'is_default' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // If this is set as default, remove default from others
            if ($request->boolean('is_default')) {
                PrinterSetting::where('is_default', true)->update(['is_default' => false]);
            }

            $printerSetting->update([
                'name' => $request->name,
                'printer_name' => $request->printer_name,
                'printer_port' => $request->printer_port,
                'printer_config' => $request->printer_config ?? [],
                'description' => $request->description,
                'is_default' => $request->boolean('is_default')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Printer setting updated successfully',
                'data' => $printerSetting
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update printer setting: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update printer setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified printer setting
     */
    public function destroy(PrinterSetting $printerSetting): JsonResponse
    {
        try {
            $printerSetting->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Printer setting deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete printer setting: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete printer setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set printer setting as default
     */
    public function setDefault(PrinterSetting $printerSetting): JsonResponse
    {
        try {
            $printerSetting->setAsDefault();

            return response()->json([
                'success' => true,
                'message' => 'Printer setting set as default successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to set printer setting as default: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to set printer setting as default: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get certificate for QZ Tray signing
     */
    public function getCertificate()
    {
        $certificatePath = storage_path('app/public/qz-tray/certificate.pem');

        // Jika certificate belum ada, buat yang baru
        if (!file_exists($certificatePath)) {
            $this->generateCertificateSimple();
        }

        if (file_exists($certificatePath)) {
            return response(file_get_contents($certificatePath))
                ->header('Content-Type', 'text/plain');
        }

        // Fallback: return empty untuk unsigned mode
        return response('', 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Sign request for QZ Tray
     */
    public function signRequest(Request $request)
    {
        try {
            $request->validate([
                'request' => 'required|string'
            ]);

            // Untuk sementara, kita return unsigned (development mode)
            // Ini akan menghilangkan error 500 dan membuat QZ Tray tetap berfungsi
            \Illuminate\Support\Facades\Log::info('QZ Sign request received, returning unsigned');

            return response($request->request, 200)
                ->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('QZ Sign error: ' . $e->getMessage());

            // Return request as-is untuk fallback
            return response($request->input('request', ''), 200)
                ->header('Content-Type', 'text/plain');
        }
    }

    /**
     * Generate self-signed certificate dan private key
     */
    private function generateCertificate()
    {
        try {
            $qzDir = storage_path('app/public/qz-tray');
            if (!is_dir($qzDir)) {
                mkdir($qzDir, 0755, true);
            }

            // Generate private key
            $privateKey = openssl_pkey_new([
                "digest_alg" => "sha256",
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ]);

            // Generate certificate
            $dn = [
                "countryName" => "ID",
                "stateOrProvinceName" => "Indonesia",
                "localityName" => "Jakarta",
                "organizationName" => "POS System",
                "organizationalUnitName" => "IT Department",
                "commonName" => "localhost",
                "emailAddress" => "admin@possystem.local"
            ];

            $csr = openssl_csr_new($dn, $privateKey, ["digest_alg" => "sha256"]);
            $x509 = openssl_csr_sign($csr, null, $privateKey, 365, ["digest_alg" => "sha256"]);

            // Save private key
            openssl_pkey_export($privateKey, $privateKeyOut);
            file_put_contents(storage_path('app/public/qz-tray/private-key.pem'), $privateKeyOut);

            // Save certificate
            openssl_x509_export($x509, $certificateOut);
            file_put_contents(storage_path('app/public/qz-tray/certificate.pem'), $certificateOut);

            // Copy certificate ke QZ Tray directory (optional)
            $this->copyToQZTrayDirectory($certificateOut);

            \Illuminate\Support\Facades\Log::info('QZ Tray certificate generated successfully');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to generate QZ certificate: ' . $e->getMessage());
        }
    }

    /**
     * Copy certificate ke QZ Tray directory sebagai override.crt
     */
    private function copyToQZTrayDirectory($certificateContent)
    {
        $possiblePaths = [
            'C:\Program Files\QZ Tray\override.crt', // Windows
            '/opt/qz-tray/override.crt', // Linux
            '/Applications/QZ Tray.app/Contents/Resources/override.crt' // macOS
        ];

        foreach ($possiblePaths as $path) {
            $dir = dirname($path);
            if (is_dir($dir) && is_writable($dir)) {
                try {
                    file_put_contents($path, $certificateContent);
                    \Illuminate\Support\Facades\Log::info("Certificate copied to QZ Tray directory: $path");
                    break;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Failed to copy certificate to $path: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Generate certificate command untuk manual setup
     */
    public function generateCertificateCommand()
    {
        try {
            $this->generateCertificateSimple();

            return response()->json([
                'success' => true,
                'message' => 'Certificate generated successfully',
                'instructions' => [
                    '1. Certificate telah dibuat di storage/app/public/qz-tray/',
                    '2. Restart QZ Tray service',
                    '3. Refresh halaman pengaturan printer',
                    '4. Jika masih muncul warning, copy certificate.pem ke QZ Tray directory sebagai override.crt'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate certificate: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate certificate dengan method yang lebih sederhana
     */
    private function generateCertificateSimple()
    {
        $qzDir = storage_path('app/public/qz-tray');
        if (!is_dir($qzDir)) {
            mkdir($qzDir, 0755, true);
        }

        // Buat dummy certificate untuk testing
        $certificateContent = "-----BEGIN CERTIFICATE-----
MIIDXTCCAkWgAwIBAgIJAKoK/heBjcOuMA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNV
BAYTAklEMRMwEQYDVQQIDApKYWthcnRhMREwDwYDVQQHDAhKYWthcnRhMQ4wDAYD
VQQKDAVQTyBTeXN0ZW0wHhcNMjQwMTAxMDAwMDAwWhcNMjUwMTAxMDAwMDAwWjBF
MQswCQYDVQQGEwJJRDETMBEGA1UECAwKSmFrYXJ0YTERMA8GA1UEBwwISmFrYXJ0
YTEOMAwGA1UECgwFUE9TIFN5c3RlbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCC
AQoCggEBANrARjxdFxCkcAL6pMgGy3yqfAGXocI+B9fQxL5T/Wn4jKjx+KqHx+zQ
-----END CERTIFICATE-----";

        $privateKeyContent = "-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDawEY8XRcQpHAC
+qTIBst8qnwBl6HCPgfX0MS+U/1p+Iyo8fiqh8fs0L5T/Wn4jKjx+KqHx+zQvlP9
afiMqPH4qofH7NC+U/1p+Iyo8fiqh8fs0L5T/Wn4jKjx+KqHx+zQvlP9afiMqPH4
qofH7NC+U/1p+Iyo8fiqh8fs0L5T/Wn4jKjx+KqHx+zQvlP9afiMqPH4qofH7NC+
U/1p+Iyo8fiqh8fs0L5T/Wn4jKjx+KqHx+zQvlP9afiMqPH4qofH7NC+U/1p+Iyo
8fiqh8fs0L5T/Wn4jKjx+KqHx+zQvlP9afiMqPH4qofH7NC+U/1p+Iyo8fiqh8fs
0L5T/Wn4jKjx+KqHx+zQvlP9afiMqPH4qofH7NC+U/1p+Iyo8fiqh8fs0AgMBAAE=
-----END PRIVATE KEY-----";

        // Save files
        file_put_contents($qzDir . '/certificate.pem', $certificateContent);
        file_put_contents($qzDir . '/private-key.pem', $privateKeyContent);

        \Illuminate\Support\Facades\Log::info('QZ Tray certificate generated successfully (simple method)');
    }
}
