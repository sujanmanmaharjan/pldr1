<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanDrt extends Model
{
    use HasFactory;

    protected $table = 'loan_drts';

    protected $fillable = [
        'bad_loan_id',
        'proposal_received_date',
        'letter_to_legal_department',
        'letter_to_drt',
        'case_filed_returned',
        'decision_from_drt',
        'decision_date',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'proposal_received_date' => 'date',
            'letter_to_legal_department' => 'date',
            'letter_to_drt' => 'date',
            'decision_date' => 'date',
        ];
    }

    public function badLoan(): BelongsTo
    {
        return $this->belongsTo(BadLoan::class);
    }
}

