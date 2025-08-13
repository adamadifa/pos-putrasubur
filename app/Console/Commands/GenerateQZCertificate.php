<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateQZCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qz:generate-certificate {--force : Force overwrite existing certificate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate self-signed certificate untuk QZ Tray';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 Generating QZ Tray Certificate...');

        $qzDir = storage_path('app/public/qz-tray');
        $certificatePath = $qzDir . '/certificate.pem';
        $privateKeyPath = $qzDir . '/private-key.pem';

        // Check if certificate already exists
        if (file_exists($certificatePath) && !$this->option('force')) {
            if (!$this->confirm('Certificate already exists. Do you want to overwrite it?')) {
                $this->info('Certificate generation cancelled.');
                return 0;
            }
        }

        try {
            // Create directory if not exists
            if (!is_dir($qzDir)) {
                mkdir($qzDir, 0755, true);
                $this->info("📁 Created directory: {$qzDir}");
            }

            // Generate private key
            $this->info('🔑 Generating private key...');
            $privateKey = openssl_pkey_new([
                "digest_alg" => "sha256",
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ]);

            if (!$privateKey) {
                throw new \Exception('Failed to generate private key: ' . openssl_error_string());
            }

            // Certificate details
            $dn = [
                "countryName" => "ID",
                "stateOrProvinceName" => "Indonesia",
                "localityName" => "Jakarta",
                "organizationName" => "POS System",
                "organizationalUnitName" => "IT Department",
                "commonName" => "localhost",
                "emailAddress" => "admin@possystem.local"
            ];

            // Generate certificate signing request
            $this->info('📝 Generating certificate signing request...');
            $csr = openssl_csr_new($dn, $privateKey, ["digest_alg" => "sha256"]);

            if (!$csr) {
                throw new \Exception('Failed to generate CSR: ' . openssl_error_string());
            }

            // Generate self-signed certificate
            $this->info('📜 Generating self-signed certificate...');
            $x509 = openssl_csr_sign($csr, null, $privateKey, 365, ["digest_alg" => "sha256"]);

            if (!$x509) {
                throw new \Exception('Failed to generate certificate: ' . openssl_error_string());
            }

            // Export private key
            openssl_pkey_export($privateKey, $privateKeyOut);
            file_put_contents($privateKeyPath, $privateKeyOut);
            $this->info("✅ Private key saved: {$privateKeyPath}");

            // Export certificate
            openssl_x509_export($x509, $certificateOut);
            file_put_contents($certificatePath, $certificateOut);
            $this->info("✅ Certificate saved: {$certificatePath}");

            // Try to copy certificate to QZ Tray directory
            $this->copyToQZTrayDirectory($certificateOut);

            $this->info('');
            $this->info('🎉 Certificate generated successfully!');
            $this->info('');
            $this->info('📋 Next steps:');
            $this->info('1. Restart QZ Tray service');
            $this->info('2. Refresh your browser');
            $this->info('3. Test connection in Pengaturan Printer');
            $this->info('');

            // Show certificate info
            $certInfo = openssl_x509_parse($x509);
            $this->info('📄 Certificate Information:');
            $this->info("   Subject: {$certInfo['name']}");
            $this->info("   Valid from: " . date('Y-m-d H:i:s', $certInfo['validFrom_time_t']));
            $this->info("   Valid to: " . date('Y-m-d H:i:s', $certInfo['validTo_time_t']));

            Log::info('QZ Tray certificate generated successfully via command');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to generate certificate: ' . $e->getMessage());
            Log::error('QZ certificate generation failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Copy certificate to QZ Tray directory as override.crt
     */
    private function copyToQZTrayDirectory($certificateContent)
    {
        $possiblePaths = [
            'C:\Program Files\QZ Tray\override.crt', // Windows
            '/opt/qz-tray/override.crt', // Linux
            '/Applications/QZ Tray.app/Contents/Resources/override.crt' // macOS
        ];

        $copied = false;
        foreach ($possiblePaths as $path) {
            $dir = dirname($path);
            if (is_dir($dir)) {
                try {
                    if (is_writable($dir)) {
                        file_put_contents($path, $certificateContent);
                        $this->info("✅ Certificate copied to QZ Tray: {$path}");
                        $copied = true;
                        break;
                    } else {
                        $this->warn("⚠️  QZ Tray directory not writable: {$dir}");
                    }
                } catch (\Exception $e) {
                    $this->warn("⚠️  Failed to copy to {$path}: " . $e->getMessage());
                }
            }
        }

        if (!$copied) {
            $this->warn('⚠️  Could not automatically copy certificate to QZ Tray directory.');
            $this->info('   Please manually copy certificate.pem to QZ Tray directory as override.crt');
            $this->info('   Windows: C:\Program Files\QZ Tray\override.crt');
            $this->info('   Linux: /opt/qz-tray/override.crt');
            $this->info('   macOS: /Applications/QZ Tray.app/Contents/Resources/override.crt');
        }
    }
}
