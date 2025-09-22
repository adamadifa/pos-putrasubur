<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TransactionService;
use App\Models\KasBank;

class RecalculateKasBankSaldo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kas-bank:recalculate-saldo {--kas-bank-id= : ID kas/bank tertentu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghitung ulang saldo kas/bank berdasarkan transaksi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactionService = new TransactionService();
        $kasBankId = $this->option('kas-bank-id');

        if ($kasBankId) {
            // Recalculate specific kas/bank
            $result = $transactionService->recalculateKasBankSaldo($kasBankId);

            if ($result['success']) {
                $this->info($result['message']);
                $this->table(
                    ['Kas/Bank ID', 'Saldo Awal', 'Total Masuk', 'Total Keluar', 'Saldo Terkini'],
                    [[
                        $result['data']['kas_bank_id'],
                        number_format($result['data']['saldo_awal'], 0, ',', '.'),
                        number_format($result['data']['total_masuk'], 0, ',', '.'),
                        number_format($result['data']['total_keluar'], 0, ',', '.'),
                        number_format($result['data']['saldo_terkini'], 0, ',', '.')
                    ]]
                );
            } else {
                $this->error($result['message']);
            }
        } else {
            // Recalculate all kas/bank
            $kasBanks = KasBank::all();
            $this->info('Menghitung ulang saldo untuk ' . $kasBanks->count() . ' kas/bank...');

            $bar = $this->output->createProgressBar($kasBanks->count());
            $bar->start();

            $successCount = 0;
            $errorCount = 0;

            foreach ($kasBanks as $kasBank) {
                $result = $transactionService->recalculateKasBankSaldo($kasBank->id);

                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                    $this->newLine();
                    $this->error("Error pada kas/bank ID {$kasBank->id}: " . $result['message']);
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("Selesai! Berhasil: {$successCount}, Error: {$errorCount}");
        }
    }
}
