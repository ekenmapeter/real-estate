<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WalletLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'deposit_id',
        'credit_swap_id',
        'transaction_type',
        'reference_code',
        'credit_amount',
        'debit_amount',
        'balance_before',
        'balance_after',
        'approved_by',
        'description',
        'status',
    ];

    protected $casts = [
        'credit_amount' => 'decimal:2',
        'debit_amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Atomically credit user balance via double-entry ledger.
     * Prevents duplicate crediting.
     */
    public static function creditUser(
        User $user,
        float $amount,
        string $transactionType,
        string $referenceCode,
        string $description,
        ?int $depositId = null,
        ?int $approvedBy = null
    ): WalletLedger {
        return DB::transaction(function () use ($user, $amount, $transactionType, $referenceCode, $description, $depositId, $approvedBy) {
            // Lock user row for update
            /** @var User $lockedUser */
            $lockedUser = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

            // Check if reference has already been credited in ledger
            $existingLedger = self::where('reference_code', $referenceCode)->first();
            if ($existingLedger) {
                return $existingLedger;
            }

            $balanceBefore = (float) $lockedUser->wallet_balance;
            $balanceAfter = round($balanceBefore + $amount, 2);

            // Update user wallet balance
            $lockedUser->wallet_balance = $balanceAfter;
            $lockedUser->save();

            // Create ledger entry
            $ledger = self::create([
                'user_id' => $lockedUser->id,
                'deposit_id' => $depositId,
                'transaction_type' => $transactionType,
                'reference_code' => $referenceCode,
                'credit_amount' => $amount,
                'debit_amount' => 0.00,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'approved_by' => $approvedBy,
                'description' => $description,
                'status' => 'completed',
            ]);

            // Sync or create Transaction log for user history
            Transaction::firstOrCreate(
                ['reference' => $referenceCode],
                [
                    'user_id' => $lockedUser->id,
                    'type' => 'deposit',
                    'amount' => $amount,
                    'description' => $description,
                    'status' => 'completed',
                ]
            );

            return $ledger;
        });
    }
}
