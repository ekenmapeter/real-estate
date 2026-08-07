<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\DocumentService;
use Illuminate\Console\Command;

class GenerateMonthlyStatements extends Command
{
    protected $signature = 'documents:statements-monthly {--period= : YYYY-MM period to generate (defaults to previous month)}';

    protected $description = 'Generate monthly finance statements for all users with account activity';

    public function handle(DocumentService $documents): int
    {
        $period = $this->option('period') ?: now()->subMonth()->format('Y-m');

        $users = User::where(function ($q) {
            $q->whereHas('transactions')
                ->orWhereHas('deposits')
                ->orWhereHas('withdrawals');
        })->get();

        $generated = 0;
        foreach ($users as $user) {
            if ($documents->monthlyStatement($user, $period)) {
                $generated++;
            }
        }

        $this->info("Generated statements for {$generated} user(s) for period {$period}.");

        return self::SUCCESS;
    }
}
