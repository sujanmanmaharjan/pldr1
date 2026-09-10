@extends('layouts.app')

@section('title', 'Edit Bad Loan')
@section('page-title', 'Edit Bad Loan: ' . $badLoan->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card card-custom bg-white card-accent-top">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="eyebrow">UPDATE RECORD</span>
                        <h5 class="mb-0 fw-bold" style="color: var(--brand-primary);"><i
                                class="bi bi-pencil-square me-2"></i>Borrower File #{{ $badLoan->id }}</h5>
                    </div>
                    <div class="d-flex gap-2">
                        @if (Auth::user()->isCentral())
                            <a href="{{ route('recovery.manage', $badLoan->id) }}" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-diagram-3-fill me-1"></i>Manage Recovery Pipeline
                            </a>
                        @endif
                        <a href="{{ route('bad-loans.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back to Directory
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('bad-loans.update', $badLoan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Section 1: Borrower Identification -->
                        <div class="section-header-notch">
                            <h6>1. Borrower Identification & KYC</h6>
                        </div>
                        <div class="row g-3 mb-4">
                            @if (Auth::user()->isCentral())
                                <div class="col-md-4">
                                    <label class="form-label">Branch <span class="required">*</span></label>
                                    <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror"
                                        required>
                                        @foreach ($branches as $b)
                                            <option value="{{ $b->id }}"
                                                {{ old('branch_id', $badLoan->branch_id) == $b->id ? 'selected' : '' }}>
                                                {{ $b->code }} - {{ $b->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('branch_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="col-md-{{ Auth::user()->isCentral() ? '4' : '6' }}">
                                <label class="form-label">Borrower / Firm Name <span class="required">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $badLoan->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-{{ Auth::user()->isCentral() ? '4' : '6' }}">
                                <label class="form-label">Proprietor Name</label>
                                <input type="text" name="proprietor" class="form-control"
                                    value="{{ old('proprietor', $badLoan->proprietor) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', $badLoan->address) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Registration No.</label>
                                <input type="text" name="reg_no" class="form-control"
                                    value="{{ old('reg_no', $badLoan->reg_no) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Reg. Authority Date</label>
                                <input type="date" name="reg_authority_date" class="form-control"
                                    value="{{ old('reg_authority_date', optional($badLoan->reg_authority_date)->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">PAN Number</label>
                                <input type="text" name="pan" class="form-control font-monospace"
                                    value="{{ old('pan', $badLoan->pan) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Citizenship No. (CTZ)</label>
                                <input type="text" name="ctz" class="form-control font-monospace"
                                    value="{{ old('ctz', $badLoan->ctz) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">National ID (NIN)</label>
                                <input type="text" name="nin" class="form-control font-monospace"
                                    value="{{ old('nin', $badLoan->nin) }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Guarantor Details</label>
                                <textarea name="guarantor_details" class="form-control" rows="2">{{ old('guarantor_details', $badLoan->guarantor_details) }}</textarea>
                            </div>
                        </div>

                        <!-- Section 2: Facility & Account Codes -->
                        <div class="section-header-notch">
                            <h6>2. Loan Facility & Limits</h6>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Client Code</label>
                                <input type="text" name="client_code" class="form-control font-monospace"
                                    value="{{ old('client_code', $badLoan->client_code) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Main Code</label>
                                <input type="text" name="main_code" class="form-control font-monospace"
                                    value="{{ old('main_code', $badLoan->main_code) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Loan Type</label>
                                <input type="text" name="loan_type" class="form-control"
                                    value="{{ old('loan_type', $badLoan->loan_type) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Sanction Limit (Rs.)</label>
                                <input type="number" step="0.01" name="limit" class="form-control font-monospace"
                                    value="{{ old('limit', $badLoan->limit) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Sanction Date</label>
                                <input type="date" name="sanction_date" class="form-control"
                                    value="{{ old('sanction_date', optional($badLoan->sanction_date)->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Last Renew Date</label>
                                <input type="date" name="last_renew_date" class="form-control"
                                    value="{{ old('last_renew_date', optional($badLoan->last_renew_date)->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Last Repayment Date</label>
                                <input type="date" name="last_repayment_date" class="form-control"
                                    value="{{ old('last_repayment_date', optional($badLoan->last_repayment_date)->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control"
                                    value="{{ old('expiry_date', optional($badLoan->expiry_date)->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <!-- Section 3: Balances & Notices -->
                        <div class="section-header-notch">
                            <h6>3. Overdue Exposure & Notice Tracker</h6>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label text-danger">Principal O/S (Rs.) <span
                                        class="required">*</span></label>
                                <input type="number" step="0.01" name="principal_os"
                                    class="form-control font-monospace @error('principal_os') is-invalid @enderror"
                                    value="{{ old('principal_os', $badLoan->principal_os) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-secondary">Interest O/S (Rs.) <span
                                        class="required">*</span></label>
                                <input type="number" step="0.01" name="interest_os"
                                    class="form-control font-monospace @error('interest_os') is-invalid @enderror"
                                    value="{{ old('interest_os', $badLoan->interest_os) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Loan Classification</label>
                                <select name="loan_class" class="form-select">
                                    <option value="">-- Select --</option>
                                    <option value="Substandard"
                                        {{ old('loan_class', $badLoan->loan_class) == 'Substandard' ? 'selected' : '' }}>
                                        Substandard</option>
                                    <option value="Doubtful"
                                        {{ old('loan_class', $badLoan->loan_class) == 'Doubtful' ? 'selected' : '' }}>
                                        Doubtful</option>
                                    <option value="Loss"
                                        {{ old('loan_class', $badLoan->loan_class) == 'Loss' ? 'selected' : '' }}>Loss
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Overall Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active"
                                        {{ old('status', $badLoan->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="In Recovery"
                                        {{ old('status', $badLoan->status) == 'In Recovery' ? 'selected' : '' }}>In
                                        Recovery</option>
                                    <option value="Settled"
                                        {{ old('status', $badLoan->status) == 'Settled' ? 'selected' : '' }}>Settled
                                    </option>
                                    <option value="Written Off"
                                        {{ old('status', $badLoan->status) == 'Written Off' ? 'selected' : '' }}>Written
                                        Off</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">7 Days Notice Publication Date</label>
                                <input type="date" name="7_days_notice_date" class="form-control"
                                    value="{{ old('7_days_notice_date', optional($badLoan->{'7_days_notice_date'})->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">15 Days Notice Publication Date</label>
                                <input type="date" name="15_days_notice_date" class="form-control"
                                    value="{{ old('15_days_notice_date', optional($badLoan->{'15_days_notice_date'})->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">21 Days Notice Publication Date</label>
                                <input type="date" name="21_days_notice_date" class="form-control"
                                    value="{{ old('21_days_notice_date', optional($badLoan->{'21_days_notice_date'})->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('bad-loans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-brand-primary px-4">
                                <i class="bi bi-arrow-repeat me-1"></i>Update Borrower Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
