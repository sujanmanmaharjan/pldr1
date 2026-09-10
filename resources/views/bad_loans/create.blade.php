@extends('layouts.app')

@section('title', 'New Bad Loan')
@section('page-title', 'Create Bad Loan Entry')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card card-custom bg-white card-accent-top">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="eyebrow">BASIC LOAN INFORMATION</span>
                        <h5 class="mb-0 fw-bold" style="color: var(--brand-primary);"><i
                                class="bi bi-file-earmark-plus me-2"></i>New Overdue Borrower Record</h5>
                    </div>
                    <a href="{{ route('bad-loans.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to Directory
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('bad-loans.store') }}" method="POST">
                        @csrf

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
                                        <option value="">Select Branch</option>
                                        @foreach ($branches as $b)
                                            <option value="{{ $b->id }}"
                                                {{ old('branch_id') == $b->id ? 'selected' : '' }}>
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
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required placeholder="Official registered entity name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-{{ Auth::user()->isCentral() ? '4' : '6' }}">
                                <label class="form-label">Proprietor Name</label>
                                <input type="text" name="proprietor"
                                    class="form-control @error('proprietor') is-invalid @enderror"
                                    value="{{ old('proprietor') }}" placeholder="Key person / Proprietor">
                                @error('proprietor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Business / Residence Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}"
                                    placeholder="Street, Ward, City, District">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Registration No.</label>
                                <input type="text" name="reg_no" class="form-control" value="{{ old('reg_no') }}"
                                    placeholder="e.g. 109283/078">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Reg. Authority Date</label>
                                <input type="date" name="reg_authority_date" class="form-control"
                                    value="{{ old('reg_authority_date') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">PAN Number</label>
                                <input type="text" name="pan" class="form-control font-monospace"
                                    value="{{ old('pan') }}" placeholder="9-digit PAN">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Citizenship No. (CTZ)</label>
                                <input type="text" name="ctz" class="form-control font-monospace"
                                    value="{{ old('ctz') }}" placeholder="Citizenship number">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">National ID (NIN)</label>
                                <input type="text" name="nin" class="form-control font-monospace"
                                    value="{{ old('nin') }}" placeholder="National ID number">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Guarantor Details</label>
                                <textarea name="guarantor_details" class="form-control" rows="2"
                                    placeholder="Full names, relations, citizenship/PAN of guarantors">{{ old('guarantor_details') }}</textarea>
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
                                    value="{{ old('client_code') }}" placeholder="e.g. C-10029">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Main Code</label>
                                <input type="text" name="main_code" class="form-control font-monospace"
                                    value="{{ old('main_code') }}" placeholder="e.g. M-5501">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Loan Type / Product</label>
                                <input type="text" name="loan_type" class="form-control"
                                    value="{{ old('loan_type') }}" placeholder="e.g. Demand, Term, Overdraft">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Sanction Limit (Rs.)</label>
                                <input type="number" step="0.01" name="limit" class="form-control font-monospace"
                                    value="{{ old('limit', '0.00') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Sanction Date</label>
                                <input type="date" name="sanction_date" class="form-control"
                                    value="{{ old('sanction_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Last Renew Date</label>
                                <input type="date" name="last_renew_date" class="form-control"
                                    value="{{ old('last_renew_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Last Repayment Date</label>
                                <input type="date" name="last_repayment_date" class="form-control"
                                    value="{{ old('last_repayment_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control"
                                    value="{{ old('expiry_date') }}">
                            </div>
                        </div>

                        <!-- Section 3: Overdue Exposure & Notice Tracker -->
                        <div class="section-header-notch">
                            <h6>3. Overdue Exposure & Notice Tracker</h6>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label text-danger">Principal O/S (Rs.) <span
                                        class="required">*</span></label>
                                <input type="number" step="0.01" name="principal_os"
                                    class="form-control font-monospace @error('principal_os') is-invalid @enderror"
                                    value="{{ old('principal_os', '0.00') }}" required>
                                @error('principal_os')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label text-secondary">Interest O/S (Rs.) <span
                                        class="required">*</span></label>
                                <input type="number" step="0.01" name="interest_os"
                                    class="form-control font-monospace @error('interest_os') is-invalid @enderror"
                                    value="{{ old('interest_os', '0.00') }}" required>
                                @error('interest_os')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Loan Classification</label>
                                <select name="loan_class" class="form-select">
                                    <option value="">-- Select Classification --</option>
                                    <option value="Substandard"
                                        {{ old('loan_class') == 'Substandard' ? 'selected' : '' }}>Substandard</option>
                                    <option value="Doubtful" {{ old('loan_class') == 'Doubtful' ? 'selected' : '' }}>
                                        Doubtful</option>
                                    <option value="Loss" {{ old('loan_class') == 'Loss' ? 'selected' : '' }}>Loss
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Overall Loan Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="In Recovery" {{ old('status') == 'In Recovery' ? 'selected' : '' }}>In
                                        Recovery</option>
                                    <option value="Settled" {{ old('status') == 'Settled' ? 'selected' : '' }}>Settled
                                    </option>
                                    <option value="Written Off" {{ old('status') == 'Written Off' ? 'selected' : '' }}>
                                        Written Off</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">7 Days Notice Publication Date</label>
                                <input type="date" name="7_days_notice_date" class="form-control"
                                    value="{{ old('7_days_notice_date') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">15 Days Notice Publication Date</label>
                                <input type="date" name="15_days_notice_date" class="form-control"
                                    value="{{ old('15_days_notice_date') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">21 Days Notice Publication Date</label>
                                <input type="date" name="21_days_notice_date" class="form-control"
                                    value="{{ old('21_days_notice_date') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('bad-loans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-brand-primary px-4">
                                <i class="bi bi-save me-1"></i>Save Loan Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
