<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Branch;
use App\Models\User;
use App\Models\BadLoan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Branches
        $branchKtm = Branch::firstOrCreate(
            ['code' => 'KTM-01'],
            ['name' => 'Kathmandu Main Branch']
        );

        $branchPkr = Branch::firstOrCreate(
            ['code' => 'PKR-02'],
            ['name' => 'Pokhara Regional Branch']
        );

        // 2. Seed Central Staff
        User::firstOrCreate(
            ['email' => 'central@bank.com'],
            [
                'name'      => 'Central Recovery Admin',
                'password'  => Hash::make('password'),
                'role'      => 'central',
                'branch_id' => null,
            ]
        );

        // 3. Seed Branch Users
        User::firstOrCreate(
            ['email' => 'branch@bank.com'],
            [
                'name'      => 'Kathmandu Loan Officer',
                'password'  => Hash::make('password'),
                'role'      => 'branch',
                'branch_id' => $branchKtm->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'pokhara@bank.com'],
            [
                'name'      => 'Pokhara Loan Officer',
                'password'  => Hash::make('password'),
                'role'      => 'branch',
                'branch_id' => $branchPkr->id,
            ]
        );

        // 4. Seed Sample Bad Loan for KTM Branch
        $badLoan = BadLoan::firstOrCreate(
            ['client_code' => 'C-10029'],
            [
                'branch_id'           => $branchKtm->id,
                'name'                => 'Himalayan Enterprises Pvt Ltd',
                'address'             => 'New Road, Ward 22, Kathmandu',
                'proprietor'          => 'Ram Bahadur Shrestha',
                'reg_no'              => '109283/078',
                'reg_authority_date'  => '2022-01-15',
                'pan'                 => '600123456',
                'ctz'                 => '27-01-72-01234',
                'nin'                 => '9876543210',
                'guarantor_details'   => 'Shyam Shrestha (Brother), Sita Shrestha (Spouse)',
                'main_code'           => 'M-5501',
                'loan_type'           => 'Demand Loan',
                'limit'               => 5000000.00,
                'sanction_date'       => '2021-06-10',
                'last_renew_date'     => '2023-06-10',
                'last_repayment_date' => '2023-11-20',
                'expiry_date'         => '2024-06-10',
                'principal_os'        => 4500000.00,
                'interest_os'         => 320000.00,
                'loan_class'          => 'Doubtful',
                '7_days_notice_date'  => '2024-01-10',
                '15_days_notice_date' => '2024-01-25',
                '21_days_notice_date' => '2024-02-15',
                'status'              => 'In Recovery',
            ]
        );

        // 5. Seed sample recovery records for Central Staff testing
        $badLoan->blacklist()->firstOrCreate(
            ['bad_loan_id' => $badLoan->id],
            [
                'proposal_received_date'  => '2024-03-01',
                '35_days_notice'          => '2024-03-05',
                'black_list_no'           => 'BL-2024-089',
                'date'                    => '2024-04-15',
                'remarks'                 => 'Notice issued via national daily newspaper.',
                'client_deposit_amount'   => 50000.00,
                'client_timeline'         => 'Within 15 days',
            ]
        );
    }
}
