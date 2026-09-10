<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanBlacklist extends Model
{
    use HasFactory;

    protected $table = 'loan_blacklists';

    protected $fillable = [
        'bad_loan_id',
        'proposal_received_date',
        '35_days_notice',
        'black_list_no',
        'date',
        'black_list_release_date',
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
            'date' => 'date',
            'black_list_release_date' => 'date',
            'client_deposit_amount' => 'decimal:2',
        ];
    }

    public function badLoan(): BelongsTo
    {
        return $this->belongsTo(BadLoan::class);
    }
}

