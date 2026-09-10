<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class BadLoan extends Model
{
    use HasFactory;

    protected $table = 'bad_loans';

    protected $fillable = [
        'branch_id',
        'name',
        'address',
        'proprietor',
        'reg_no',
        'reg_authority_date',
        'pan',
        'ctz',
        'nin',
        'guarantor_details',
        'client_code',
        'main_code',
        'loan_type',
        'limit',
        'sanction_date',
        'last_renew_date',
        'last_repayment_date',
        'expiry_date',
        'principal_os',
        'interest_os',
        'loan_class',
        '7_days_notice_date',
        '15_days_notice_date',
        '21_days_notice_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'reg_authority_date' => 'date',
            'sanction_date' => 'date',
            'last_renew_date' => 'date',
            'last_repayment_date' => 'date',
            'expiry_date' => 'date',
            '7_days_notice_date' => 'date',
            '15_days_notice_date' => 'date',
            '21_days_notice_date' => 'date',
            'limit' => 'decimal:2',
            'principal_os' => 'decimal:2',
            'interest_os' => 'decimal:2',
        ];
    }

    /**
     * Scope query to only show loans visible to given user.
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->isCentral()) {
            return $query;
        }

        return $query->where('branch_id', $user->branch_id);
    }

    /* Relationships */

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function blacklist(): HasOne
    {
        return $this->hasOne(LoanBlacklist::class);
    }

    public function auction(): HasOne
    {
        return $this->hasOne(LoanAuction::class);
    }

    public function partialRelease(): HasOne
    {
        return $this->hasOne(LoanPartialRelease::class);
    }

    public function nba(): HasOne
    {
        return $this->hasOne(LoanNba::class);
    }

    public function interestRebate(): HasOne
    {
        return $this->hasOne(LoanInterestRebate::class);
    }

    public function drt(): HasOne
    {
        return $this->hasOne(LoanDrt::class);
    }

    public function getTotalOutstandingAttribute(): float
    {
        return (float) ($this->principal_os + $this->interest_os);
    }
}

