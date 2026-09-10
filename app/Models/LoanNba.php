<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanNba extends Model
{
    use HasFactory;

    protected $table = 'loan_nbas';

    protected $fillable = [
        'bad_loan_id',
        'proposal_received_date',
        'committees_valuation',
        'decision_from_board',
        '35_days_notice',
        'letter_to_land_reg_office',
        'ownership_transfer',
        'value_of_property',
        'remaining_loan',
        'revaluation',
        'auction_notice',
        'remarks',
        'client_application',
        'client_deposit_amount',
        'client_timeline',
    ];

    protected function casts(): array
    {
        return [
            'proposal_received_date' => 'date',
            '35_days_notice' => 'date',
            'letter_to_land_reg_office' => 'date',
            'ownership_transfer' => 'date',
            'auction_notice' => 'date',
            'committees_valuation' => 'decimal:2',
            'value_of_property' => 'decimal:2',
            'remaining_loan' => 'decimal:2',
            'revaluation' => 'decimal:2',
            'client_deposit_amount' => 'decimal:2',
        ];
    }

    public function badLoan(): BelongsTo
    {
        return $this->belongsTo(BadLoan::class);
    }
}

