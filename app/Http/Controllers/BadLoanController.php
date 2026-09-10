<?php

namespace App\Http\Controllers;

use App\Models\BadLoan;
use App\Models\Branch;
use App\Http\Requests\StoreBadLoanRequest;
use App\Http\Requests\UpdateBadLoanRequest;
use App\Imports\BadLoansImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BadLoanController extends Controller
{
    /**
     * Display a listing of bad loans with role-based filtering and search.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = BadLoan::with('branch')
            ->forUser($user)
            ->with(['blacklist', 'auction', 'partialRelease', 'nba', 'interestRebate', 'drt']);

        // Search by name, PAN, client code, proprietor
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('pan', 'like', "%{$search}%")
                  ->orWhere('client_code', 'like', "%{$search}%")
                  ->orWhere('proprietor', 'like', "%{$search}%");
            });
        }

        // Branch filter for Central users
        if ($user->isCentral() && $request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        // Loan Class filter
        if ($request->filled('loan_class')) {
            $query->where('loan_class', $request->input('loan_class'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $badLoans = $query->latest()->paginate(15)->withQueryString();
        $branches = $user->isCentral() ? Branch::orderBy('name')->get() : collect([$user->branch]);

        return view('bad_loans.index', compact('badLoans', 'branches'));
    }

    /**
     * Show form for creating a new bad loan.
     */
    public function create()
    {
        $user = Auth::user();
        $branches = $user->isCentral() ? Branch::orderBy('name')->get() : collect([$user->branch]);

        return view('bad_loans.create', compact('branches'));
    }

    /**
     * Store a newly created bad loan.
     */
    public function store(StoreBadLoanRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        // Enforce branch separation
        if ($user->isBranch()) {
            $data['branch_id'] = $user->branch_id;
        }

        BadLoan::create($data);

        return redirect()
            ->route('bad-loans.index')
            ->with('success', 'Bad Loan record created successfully.');
    }

    /**
     * Show detailed view of a bad loan.
     */
    public function show(BadLoan $badLoan)
    {
        $this->authorize('view', $badLoan);

        $badLoan->load(['branch', 'blacklist', 'auction', 'partialRelease', 'nba', 'interestRebate', 'drt']);

        return view('bad_loans.show', compact('badLoan'));
    }

    /**
     * Show form for editing a bad loan.
     */
    public function edit(BadLoan $badLoan)
    {
        $this->authorize('update', $badLoan);

        $user = Auth::user();
        $branches = $user->isCentral() ? Branch::orderBy('name')->get() : collect([$user->branch]);

        return view('bad_loans.edit', compact('badLoan', 'branches'));
    }

    /**
     * Update the specified bad loan.
     */
    public function update(UpdateBadLoanRequest $request, BadLoan $badLoan)
    {
        $this->authorize('update', $badLoan);

        $data = $request->validated();
        $user = Auth::user();

        if ($user->isBranch()) {
            $data['branch_id'] = $user->branch_id;
        }

        $badLoan->update($data);

        return redirect()
            ->route('bad-loans.index')
            ->with('success', 'Bad Loan updated successfully.');
    }

    /**
     * Delete the specified bad loan.
     */
    public function destroy(BadLoan $badLoan)
    {
        $this->authorize('delete', $badLoan);

        $badLoan->delete();

        return redirect()
            ->route('bad-loans.index')
            ->with('success', 'Bad Loan record deleted successfully.');
    }

    /**
     * Handle bulk Excel/CSV import of bad loans.
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
            'branch_id'  => ['nullable', 'exists:branches,id'],
        ]);

        $user = Auth::user();
        $branchId = $user->isBranch() ? $user->branch_id : $request->input('branch_id');

        try {
            Excel::import(new BadLoansImport($branchId), $request->file('excel_file'));

            return redirect()
                ->route('bad-loans.index')
                ->with('success', 'Bad loans imported successfully.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('bad-loans.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Download sample CSV template.
     */
    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bad_loans_sample_template.csv"',
        ];

        $columns = [
            'branch_id', 'name', 'address', 'proprietor', 'reg_no', 'reg_authority_date',
            'pan', 'ctz', 'nin', 'guarantor_details', 'client_code', 'main_code',
            'loan_type', 'limit', 'sanction_date', 'last_renew_date', 'last_repayment_date',
            'expiry_date', 'principal_os', 'interest_os', 'loan_class',
            '7_days_notice_date', '15_days_notice_date', '21_days_notice_date', 'status'
        ];

        $sampleRow = [
            '1', 'ABC Trading Pvt Ltd', 'Kathmandu Ward 4', 'Ram Sharma', '109283/078', '2022-01-15',
            '600123456', '27-01-72-01234', '9876543210', 'Shyam Sharma (Father), Sita Sharma', 'C-10029', 'M-5501',
            'Demand Loan', '5000000.00', '2021-06-10', '2023-06-10', '2023-11-20',
            '2024-06-10', '4500000.00', '320000.00', 'Doubtful',
            '2024-01-10', '2024-01-25', '2024-02-15', 'Active'
        ];

        return response()->stream(function () use ($columns, $sampleRow) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            fputcsv($handle, $sampleRow);
            fclose($handle);
        }, 200, $headers);
    }
}

