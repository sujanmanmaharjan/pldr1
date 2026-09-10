@extends('layouts.app')

@section('title', 'Bad Loans Management')
@section('page-title', 'Bad Loans Directory')

@section('content')
    <div class="row g-3 mb-4">
        <!-- Top Brand Metric Counters -->
        <div class="col-md-3 col-sm-6">
            <div class="metric-card">
                <span class="metric-label">Total Bad Loans</span>
                <div class="metric-value">{{ $badLoans->total() }}</div>
                <small class="text-muted" style="font-size: 11px;">Tracked across branch network</small>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="metric-card">
                <span class="metric-label">Principal Outstanding</span>
                <div class="metric-value" style="color: var(--brand-accent);">
                    Rs. {{ number_format($badLoans->sum('principal_os'), 2) }}
                </div>
                <small class="text-muted" style="font-size: 11px;">Current page total</small>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="metric-card">
                <span class="metric-label">Interest Outstanding</span>
                <div class="metric-value" style="color: #d97706;">
                    Rs. {{ number_format($badLoans->sum('interest_os'), 2) }}
                </div>
                <small class="text-muted" style="font-size: 11px;">Current page total</small>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="metric-card">
                <span class="metric-label">Assigned Branches</span>
                <div class="metric-value">{{ $branches->count() }}</div>
                <small class="text-muted" style="font-size: 11px;">Active recovery jurisdictions</small>
            </div>
        </div>

        <!-- Filters & Action Header -->
        <div class="col-12">
            <div class="card card-custom p-3 bg-white card-accent-top">
                <form method="GET" action="{{ route('bad-loans.index') }}" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control"
                                placeholder="Search Borrower, PAN, Code..." value="{{ request('search') }}">
                        </div>
                    </div>

                    @if (Auth::user()->isCentral())
                        <div class="col-md-2">
                            <select name="branch_id" class="form-select form-select-sm">
                                <option value="">-- All Branches --</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->id }}"
                                        {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->code }} -
                                        {{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-2">
                        <select name="loan_class" class="form-select form-select-sm">
                            <option value="">-- All Loan Classes --</option>
                            <option value="Substandard" {{ request('loan_class') == 'Substandard' ? 'selected' : '' }}>
                                Substandard</option>
                            <option value="Doubtful" {{ request('loan_class') == 'Doubtful' ? 'selected' : '' }}>Doubtful
                            </option>
                            <option value="Loss" {{ request('loan_class') == 'Loss' ? 'selected' : '' }}>Loss</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">-- All Statuses --</option>
                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="In Recovery" {{ request('status') == 'In Recovery' ? 'selected' : '' }}>In
                                Recovery</option>
                            <option value="Settled" {{ request('status') == 'Settled' ? 'selected' : '' }}>Settled</option>
                            <option value="Written Off" {{ request('status') == 'Written Off' ? 'selected' : '' }}>Written
                                Off</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-brand-primary btn-sm"><i
                                class="bi bi-filter me-1"></i>Filter</button>
                        <a href="{{ route('bad-loans.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>

                    <div class="col-auto ms-auto d-flex gap-2">
                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#importModal">
                            <i class="bi bi-file-earmark-excel me-1"></i>Excel Import
                        </button>
                        <a href="{{ route('bad-loans.create') }}" class="btn btn-brand-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>New Bad Loan
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bad Loans Data Table -->
        <div class="col-12">
            <div class="card card-custom bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="text-nowrap">
                            <tr>
                                <th>#</th>
                                <th>Branch</th>
                                <th>Borrower / Proprietor</th>
                                <th>PAN / Reg No</th>
                                <th>Client Code / Type</th>
                                <th class="text-end">Sanction / Limit</th>
                                <th class="text-end">Principal O/S</th>
                                <th class="text-end">Interest O/S</th>
                                <th>Loan Class</th>
                                <th>Notices (7 / 15 / 21)</th>
                                <th>Recovery Pipeline</th>
                                <th class="text-center" style="width: 110px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($badLoans as $loan)
                                <tr>
                                    <td class="text-muted fw-semibold">#{{ $loan->id }}</td>
                                    <td>
                                        <span class="badge-brand">{{ $loan->branch->code ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold" style="color: var(--brand-primary);">{{ $loan->name }}</div>
                                        @if ($loan->proprietor)
                                            <small class="text-muted"><i
                                                    class="bi bi-person me-1"></i>{{ $loan->proprietor }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div><span class="text-muted">PAN:</span> <span
                                                class="font-monospace fw-semibold">{{ $loan->pan ?? '-' }}</span></div>
                                        <small class="text-muted">Reg: {{ $loan->reg_no ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="badge-brand">{{ $loan->client_code ?? 'N/A' }}</div>
                                        <div class="small text-muted mt-1">{{ $loan->loan_type ?? '-' }}</div>
                                    </td>
                                    <td class="text-end font-monospace">
                                        <div>{{ number_format($loan->limit, 2) }}</div>
                                        @if ($loan->sanction_date)
                                            <small class="text-muted">{{ $loan->sanction_date->format('Y-m-d') }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end font-monospace fw-bold" style="color: var(--brand-accent);">
                                        {{ number_format($loan->principal_os, 2) }}
                                    </td>
                                    <td class="text-end font-monospace text-secondary">
                                        {{ number_format($loan->interest_os, 2) }}
                                    </td>
                                    <td>
                                        @php
                                            $classBadge = match (strtolower($loan->loan_class ?? '')) {
                                                'substandard' => 'warning',
                                                'doubtful' => 'danger',
                                                'loss' => 'dark',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span
                                            class="badge bg-{{ $classBadge }}">{{ $loan->loan_class ?? 'Unclassified' }}</span>
                                    </td>
                                    <td class="small">
                                        <div><span class="text-muted">7d:</span>
                                            {{ $loan->{'7_days_notice_date'} ? $loan->{'7_days_notice_date'}->format('Y-m-d') : '-' }}
                                        </div>
                                        <div><span class="text-muted">15d:</span>
                                            {{ $loan->{'15_days_notice_date'} ? $loan->{'15_days_notice_date'}->format('Y-m-d') : '-' }}
                                        </div>
                                        <div><span class="text-muted">21d:</span>
                                            {{ $loan->{'21_days_notice_date'} ? $loan->{'21_days_notice_date'}->format('Y-m-d') : '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <!-- Recovery Module Badges -->
                                        <div class="d-flex flex-wrap gap-1" style="max-width: 140px;">
                                            <span
                                                class="badge {{ $loan->blacklist ? 'badge-brand-accent' : 'bg-light text-muted border' }}"
                                                title="Blacklist">BL</span>
                                            <span
                                                class="badge {{ $loan->auction ? 'bg-warning text-dark' : 'bg-light text-muted border' }}"
                                                title="Auction">AUC</span>
                                            <span
                                                class="badge {{ $loan->partialRelease ? 'bg-info text-dark' : 'bg-light text-muted border' }}"
                                                title="Partial Release">PR</span>
                                            <span
                                                class="badge {{ $loan->nba ? 'badge-brand' : 'bg-light text-muted border' }}"
                                                title="NBA Book">NBA</span>
                                            <span
                                                class="badge {{ $loan->interestRebate ? 'bg-success' : 'bg-light text-muted border' }}"
                                                title="Interest Rebate/Write-off">IR</span>
                                            <span
                                                class="badge {{ $loan->drt ? 'bg-dark' : 'bg-light text-muted border' }}"
                                                title="DRT">DRT</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            @if (Auth::user()->isCentral())
                                                <a href="{{ route('recovery.manage', $loan->id) }}"
                                                    class="btn btn-outline-danger" title="Manage 6 Recovery Modules">
                                                    <i class="bi bi-diagram-3-fill"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('bad-loans.edit', $loan->id) }}"
                                                class="btn btn-outline-secondary" title="Edit Basic Info">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('bad-loans.destroy', $loan->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this bad loan record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-secondary text-danger"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                                        No bad loan records found matching the criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($badLoans->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $badLoans->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Excel Bulk Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('bad-loans.import') }}" method="POST" enctype="multipart/form-data"
                class="modal-content card-accent-top">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="importModalLabel">
                        <i class="bi bi-file-earmark-excel text-success me-2"></i>Bulk Import Bad Loans
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">
                        Upload an Excel (.xlsx, .xls) or CSV spreadsheet containing overdue loan records. Column headers
                        must conform to the standard PLRD format.
                    </p>

                    @if (Auth::user()->isCentral())
                        <div class="mb-3">
                            <label class="form-label">Target Branch (Optional if branch_id is in file):</label>
                            <select name="branch_id" class="form-select">
                                <option value="">-- Read from Excel or specify branch --</option>
                                @foreach ($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Select File <span class="required">*</span></label>
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx, .xls, .csv"
                            required>
                    </div>

                    <div class="p-3 rounded border small"
                        style="background: var(--brand-tint); border-color: #d4def7 !important;">
                        <i class="bi bi-info-circle text-primary me-1"></i>
                        Need the official format?
                        <a href="{{ route('bad-loans.template.download') }}" class="fw-bold text-decoration-none"
                            style="color: var(--brand-primary);">
                            Download Standard CSV Template &rarr;
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand-primary btn-sm">
                        <i class="bi bi-cloud-arrow-up me-1"></i>Execute Import
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
