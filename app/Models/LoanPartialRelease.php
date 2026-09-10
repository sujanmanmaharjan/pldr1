<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanPartialRelease extends Model
{
    use HasFactory;

    protected $table = 'loan_partial_releases';

    protected $fillable = [
        'bad_loan_id',
        'proposal_received_date',
        'partial_release',
        'plot_no',
        'value_of_land',
        'lending_time',
        'recovery_time',
        'received_amount',
        'restructure_reschedule',
        'main_decision',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'proposal_received_date' => 'date',
            'value_of_land' => 'decimal:2',
            'received_amount' => 'decimal:2',
        ];
    }

    public function badLoan(): BelongsTo
    {
        return $this->belongsTo(BadLoan::class);
    }
}

