<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanAuction extends Model
{
    use HasFactory;

    protected $table = 'loan_auctions';

    protected $fillable = [
        'bad_loan_id',
        'proposal_received_date',
        'collateral_value',
        'valuators_name',
        'valuation_date',
        'first_auction_notice',
        'first_auction_status',
        'first_auction_recover',
        'second_notice',
        'second_status',
        'second_recover',
        'third_notice',
        'third_status',
        'third_recover',
        'remarks',
        'client_application',
        'client_deposit_amount',
        'client_timeline',
    ];

    protected function casts(): array
    {
        return [
            'proposal_received_date' => 'date',
            'valuation_date' => 'date',
            'first_auction_notice' => 'date',
            'second_notice' => 'date',
            'third_notice' => 'date',
            'collateral_value' => 'decimal:2',
            'first_auction_recover' => 'decimal:2',
            'second_recover' => 'decimal:2',
            'third_recover' => 'decimal:2',
            'client_deposit_amount' => 'decimal:2',
        ];
    }

    public function badLoan(): BelongsTo
    {
        return $this->belongsTo(BadLoan::class);
    }
}

