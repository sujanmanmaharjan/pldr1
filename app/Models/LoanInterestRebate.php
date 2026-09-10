<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanInterestRebate extends Model
{
    use HasFactory;

    protected $table = 'loan_interest_rebates';

    protected $fillable = [
        'bad_loan_id',
        'proposal_received_date',
        'decision_date',
        'decision_authority',
        'interest_paid',
        'interest_rebate',
        'remarks',
        'collateral',
        'write_off_principal',
        'write_off_regular_interest',
        'write_off_penal_interest',
        'write_off_collateral',
        'write_off_remarks',
    ];

    protected function casts(): array
    {
        return [
            'proposal_received_date' => 'date',
            'decision_date' => 'date',
            'interest_paid' => 'decimal:2',
            'interest_rebate' => 'decimal:2',
            'write_off_principal' => 'decimal:2',
            'write_off_regular_interest' => 'decimal:2',
            'write_off_penal_interest' => 'decimal:2',
        ];
    }

    public function badLoan(): BelongsTo
    {
        return $this->belongsTo(BadLoan::class);
    }
}

