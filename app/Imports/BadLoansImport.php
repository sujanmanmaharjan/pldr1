<?php

namespace App\Imports;

use App\Models\BadLoan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class BadLoansImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected ?int $branchId;

    public function __construct(?int $branchId = null)
    {
        $this->branchId = $branchId;
    }

    public function model(array $row)
    {
        // Determine branch_id: passed-in branch ID (e.g. from logged-in user or selection) or row value
        $branchId = $this->branchId ?? ($row['branch_id'] ?? null);

        if (! $branchId) {
            return null; // Skip if no branch associated
        }

        return new BadLoan([
            'branch_id'           => $branchId,
            'name'                => $row['name'] ?? $row['borrower_name'] ?? 'Unknown',
            'address'             => $row['address'] ?? null,
            'proprietor'          => $row['proprietor'] ?? null,
            'reg_no'              => $row['reg_no'] ?? null,
            'reg_authority_date'  => $this->transformDate($row['reg_authority_date'] ?? null),
            'pan'                 => $row['pan'] ?? null,
            'ctz'                 => $row['ctz'] ?? null,
            'nin'                 => $row['nin'] ?? null,
            'guarantor_details'   => $row['guarantor_details'] ?? null,
            'client_code'         => $row['client_code'] ?? null,
            'main_code'           => $row['main_code'] ?? null,
            'loan_type'           => $row['loan_type'] ?? null,
            'limit'               => (float) ($row['limit'] ?? 0),
            'sanction_date'       => $this->transformDate($row['sanction_date'] ?? null),
            'last_renew_date'     => $this->transformDate($row['last_renew_date'] ?? null),
            'last_repayment_date' => $this->transformDate($row['last_repayment_date'] ?? null),
            'expiry_date'         => $this->transformDate($row['expiry_date'] ?? null),
            'principal_os'        => (float) ($row['principal_os'] ?? 0),
            'interest_os'         => (float) ($row['interest_os'] ?? 0),
            'loan_class'          => $row['loan_class'] ?? null,
            '7_days_notice_date'  => $this->transformDate($row['7_days_notice_date'] ?? $row['notice_7_days'] ?? null),
            '15_days_notice_date' => $this->transformDate($row['15_days_notice_date'] ?? $row['notice_15_days'] ?? null),
            '21_days_notice_date' => $this->transformDate($row['21_days_notice_date'] ?? $row['notice_21_days'] ?? null),
            'status'              => $row['status'] ?? 'Active',
        ]);
    }

    public function batchSize(): int
    {
        return 250;
    }

    public function chunkSize(): int
    {
        return 250;
    }

    /**
     * Parse excel date numbers or text date strings safely.
     */
    private function transformDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}

