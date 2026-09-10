@extends('layouts.app')

@section('title', 'Recovery Pipeline - ' . $badLoan->name)
@section('page-title', 'Central Recovery Pipeline')

@section('content')
    <div class="row g-3">
        <!-- Borrower Quick Summary Banner -->
        <div class="col-12">
            <div class="card card-custom bg-white card-accent-top">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <span class="eyebrow mb-1">CENTRAL RECOVERY MANAGEMENT</span>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="mb-0 fw-bold" style="color: var(--brand-primary);">{{ $badLoan->name }}</h4>
                                <span class="badge badge-brand-accent">Central Recovery</span>
                                <span class="badge badge-brand">{{ $badLoan->branch->code }}
                                    ({{ $badLoan->branch->name }})</span>
                            </div>
                            <div class="text-muted small mt-2">
                                <span class="me-3"><i class="bi bi-person me-1"></i>Proprietor:
                                    <strong>{{ $badLoan->proprietor ?? 'N/A' }}</strong></span>
                                <span class="me-3"><i class="bi bi-upc-scan me-1"></i>PAN: <strong
                                        class="font-monospace">{{ $badLoan->pan ?? 'N/A' }}</strong></span>
                                <span class="me-3"><i class="bi bi-hash me-1"></i>Client Code: <strong
                                        class="font-monospace">{{ $badLoan->client_code ?? 'N/A' }}</strong></span>
                                <span><i class="bi bi-tag me-1"></i>Class:
                                    <strong>{{ $badLoan->loan_class ?? 'N/A' }}</strong></span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-4 text-end">
                            <div>
                                <div class="text-muted small">Sanction Limit</div>
                                <div class="fw-bold font-monospace">Rs. {{ number_format($badLoan->limit, 2) }}</div>
                            </div>
                            <div>
                                <div class="text-muted small">Principal O/S</div>
                                <div class="fw-bold font-monospace" style="color: var(--brand-accent);">
                                    Rs. {{ number_format($badLoan->principal_os, 2) }}
                                </div>
                            </div>
                            <div>
                                <div class="text-muted small">Interest O/S</div>
                                <div class="fw-bold text-secondary font-monospace">
                                    Rs. {{ number_format($badLoan->interest_os, 2) }}
                                </div>
                            </div>
                            <a href="{{ route('bad-loans.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i>Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recovery Pipeline Tabs -->
        <div class="col-12">
            <div class="card card-custom bg-white">
                <div class="card-header bg-white border-bottom p-0">
                    @php
                        $activeTab = request('tab', 'blacklist');
                    @endphp
                    <ul class="nav nav-tabs px-3 pt-2" id="recoveryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'blacklist' ? 'active' : '' }}" id="blacklist-tab"
                                data-bs-toggle="tab" data-bs-target="#blacklist" type="button" role="tab">
                                <i class="bi bi-slash-circle text-danger me-1"></i> 1. Blacklist
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'auction' ? 'active' : '' }}" id="auction-tab"
                                data-bs-toggle="tab" data-bs-target="#auction" type="button" role="tab">
                                <i class="bi bi-hammer text-warning me-1"></i> 2. Auction
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'partial_release' ? 'active' : '' }}" id="partial-tab"
                                data-bs-toggle="tab" data-bs-target="#partial" type="button" role="tab">
                                <i class="bi bi-pie-chart text-info me-1"></i> 3. Partial Release / Restructure
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'nba' ? 'active' : '' }}" id="nba-tab"
                                data-bs-toggle="tab" data-bs-target="#nba" type="button" role="tab">
                                <i class="bi bi-building text-primary me-1"></i> 4. NBA Book
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'interest_rebate' ? 'active' : '' }}" id="rebate-tab"
                                data-bs-toggle="tab" data-bs-target="#rebate" type="button" role="tab">
                                <i class="bi bi-percent text-success me-1"></i> 5. Interest Rebate / Write-Off
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab == 'drt' ? 'active' : '' }}" id="drt-tab"
                                data-bs-toggle="tab" data-bs-target="#drt" type="button" role="tab">
                                <i class="bi bi-bank text-dark me-1"></i> 6. DRT Tribunal
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="recoveryTabsContent">

                        <!-- ============================================== -->
                        <!-- TAB 1: BLACKLIST                               -->
                        <!-- ============================================== -->
                        <div class="tab-pane fade {{ $activeTab == 'blacklist' ? 'show active' : '' }}" id="blacklist"
                            role="tabpanel">
                            <form action="{{ route('recovery.blacklist.update', $badLoan->id) }}" method="POST">
                                @csrf
                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Blacklist
                                    Proceedings</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Proposal Received Date</label>
                                        <input type="date" name="proposal_received_date" class="form-control"
                                            value="{{ old('proposal_received_date', optional($badLoan->blacklist?->proposal_received_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">35 Days Notice Published</label>
                                        <input type="date" name="35_days_notice" class="form-control"
                                            value="{{ old('35_days_notice', optional($badLoan->blacklist?->{'35_days_notice'})->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Black List No.</label>
                                        <input type="text" name="black_list_no" class="form-control"
                                            value="{{ old('black_list_no', $badLoan->blacklist?->black_list_no) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Blacklist Date</label>
                                        <input type="date" name="date" class="form-control"
                                            value="{{ old('date', optional($badLoan->blacklist?->date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Black List Release Date</label>
                                        <input type="date" name="black_list_release_date" class="form-control"
                                            value="{{ old('black_list_release_date', optional($badLoan->blacklist?->black_list_release_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label fw-semibold">Remarks</label>
                                        <input type="text" name="remarks" class="form-control"
                                            value="{{ old('remarks', $badLoan->blacklist?->remarks) }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Client's
                                    Response</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Application Details</label>
                                        <textarea name="client_application" class="form-control" rows="2"
                                            placeholder="Letter/Application particulars">{{ old('client_application', $badLoan->blacklist?->client_application) }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Deposit Amount</label>
                                        <input type="number" step="0.01" name="client_deposit_amount"
                                            class="form-control"
                                            value="{{ old('client_deposit_amount', $badLoan->blacklist?->client_deposit_amount ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Timeline Given</label>
                                        <input type="text" name="client_timeline" class="form-control"
                                            placeholder="e.g. within 30 days"
                                            value="{{ old('client_timeline', $badLoan->blacklist?->client_timeline) }}">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-save me-1"></i>Save
                                        Blacklist Details</button>
                                </div>
                            </form>
                        </div>

                        <!-- ============================================== -->
                        <!-- TAB 2: AUCTION                                 -->
                        <!-- ============================================== -->
                        <div class="tab-pane fade {{ $activeTab == 'auction' ? 'show active' : '' }}" id="auction"
                            role="tabpanel">
                            <form action="{{ route('recovery.auction.update', $badLoan->id) }}" method="POST">
                                @csrf
                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Valuation &
                                    Collateral</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Proposal Received Date</label>
                                        <input type="date" name="proposal_received_date" class="form-control"
                                            value="{{ old('proposal_received_date', optional($badLoan->auction?->proposal_received_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Collateral Value</label>
                                        <input type="number" step="0.01" name="collateral_value"
                                            class="form-control"
                                            value="{{ old('collateral_value', $badLoan->auction?->collateral_value ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Valuator's Name</label>
                                        <input type="text" name="valuators_name" class="form-control"
                                            value="{{ old('valuators_name', $badLoan->auction?->valuators_name) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Valuation Date</label>
                                        <input type="date" name="valuation_date" class="form-control"
                                            value="{{ old('valuation_date', optional($badLoan->auction?->valuation_date)->format('Y-m-d')) }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Auction
                                    Rounds Tracking</h6>
                                <div class="row g-3 mb-4">
                                    <!-- 1st Auction -->
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded bg-light">
                                            <h6 class="fw-bold text-primary mb-2">1st Auction</h6>
                                            <label class="form-label small fw-semibold">35 Days Notice Published</label>
                                            <input type="date" name="first_auction_notice"
                                                class="form-control form-control-sm mb-2"
                                                value="{{ old('first_auction_notice', optional($badLoan->auction?->first_auction_notice)->format('Y-m-d')) }}">
                                            <label class="form-label small fw-semibold">Auction Status</label>
                                            <input type="text" name="first_auction_status"
                                                class="form-control form-control-sm mb-2"
                                                placeholder="e.g. Unsuccessful / Bid received"
                                                value="{{ old('first_auction_status', $badLoan->auction?->first_auction_status) }}">
                                            <label class="form-label small fw-semibold">Recover Amount</label>
                                            <input type="number" step="0.01" name="first_auction_recover"
                                                class="form-control form-control-sm"
                                                value="{{ old('first_auction_recover', $badLoan->auction?->first_auction_recover ?? '0.00') }}">
                                        </div>
                                    </div>

                                    <!-- 2nd Auction -->
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded bg-light">
                                            <h6 class="fw-bold text-primary mb-2">2nd Notice / Auction</h6>
                                            <label class="form-label small fw-semibold">35 / 15 Days Notice
                                                Published</label>
                                            <input type="date" name="second_notice"
                                                class="form-control form-control-sm mb-2"
                                                value="{{ old('second_notice', optional($badLoan->auction?->second_notice)->format('Y-m-d')) }}">
                                            <label class="form-label small fw-semibold">Auction Status</label>
                                            <input type="text" name="second_status"
                                                class="form-control form-control-sm mb-2" placeholder="Status"
                                                value="{{ old('second_status', $badLoan->auction?->second_status) }}">
                                            <label class="form-label small fw-semibold">Recover Amount</label>
                                            <input type="number" step="0.01" name="second_recover"
                                                class="form-control form-control-sm"
                                                value="{{ old('second_recover', $badLoan->auction?->second_recover ?? '0.00') }}">
                                        </div>
                                    </div>

                                    <!-- 3rd Auction -->
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded bg-light">
                                            <h6 class="fw-bold text-primary mb-2">3rd Auction</h6>
                                            <label class="form-label small fw-semibold">35 / 15 Days Notice
                                                Published</label>
                                            <input type="date" name="third_notice"
                                                class="form-control form-control-sm mb-2"
                                                value="{{ old('third_notice', optional($badLoan->auction?->third_notice)->format('Y-m-d')) }}">
                                            <label class="form-label small fw-semibold">Auction Status</label>
                                            <input type="text" name="third_status"
                                                class="form-control form-control-sm mb-2" placeholder="Status"
                                                value="{{ old('third_status', $badLoan->auction?->third_status) }}">
                                            <label class="form-label small fw-semibold">Recover Amount</label>
                                            <input type="number" step="0.01" name="third_recover"
                                                class="form-control form-control-sm"
                                                value="{{ old('third_recover', $badLoan->auction?->third_recover ?? '0.00') }}">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Remarks</label>
                                        <input type="text" name="remarks" class="form-control"
                                            value="{{ old('remarks', $badLoan->auction?->remarks) }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Client's
                                    Response</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Application Details</label>
                                        <textarea name="client_application" class="form-control" rows="2">{{ old('client_application', $badLoan->auction?->client_application) }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Deposit Amount</label>
                                        <input type="number" step="0.01" name="client_deposit_amount"
                                            class="form-control"
                                            value="{{ old('client_deposit_amount', $badLoan->auction?->client_deposit_amount ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Timeline Given</label>
                                        <input type="text" name="client_timeline" class="form-control"
                                            value="{{ old('client_timeline', $badLoan->auction?->client_timeline) }}">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-warning"><i class="bi bi-save me-1"></i>Save
                                        Auction Details</button>
                                </div>
                            </form>
                        </div>

                        <!-- ============================================== -->
                        <!-- TAB 3: PARTIAL RELEASE / RESTRUCTURE           -->
                        <!-- ============================================== -->
                        <div class="tab-pane fade {{ $activeTab == 'partial_release' ? 'show active' : '' }}"
                            id="partial" role="tabpanel">
                            <form action="{{ route('recovery.partial-release.update', $badLoan->id) }}" method="POST">
                                @csrf
                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Partial
                                    Release / Restructure / Reschedule</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Proposal Received Date</label>
                                        <input type="date" name="proposal_received_date" class="form-control"
                                            value="{{ old('proposal_received_date', optional($badLoan->partialRelease?->proposal_received_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Partial Release Particulars</label>
                                        <input type="text" name="partial_release" class="form-control"
                                            value="{{ old('partial_release', $badLoan->partialRelease?->partial_release) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Plot No.</label>
                                        <input type="text" name="plot_no" class="form-control"
                                            value="{{ old('plot_no', $badLoan->partialRelease?->plot_no) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Value of Land</label>
                                        <input type="number" step="0.01" name="value_of_land" class="form-control"
                                            value="{{ old('value_of_land', $badLoan->partialRelease?->value_of_land ?? '0.00') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Lending Time</label>
                                        <input type="text" name="lending_time" class="form-control"
                                            value="{{ old('lending_time', $badLoan->partialRelease?->lending_time) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Recovery Time</label>
                                        <input type="text" name="recovery_time" class="form-control"
                                            value="{{ old('recovery_time', $badLoan->partialRelease?->recovery_time) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Received Amount</label>
                                        <input type="number" step="0.01" name="received_amount" class="form-control"
                                            value="{{ old('received_amount', $badLoan->partialRelease?->received_amount ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Main Decision</label>
                                        <input type="text" name="main_decision" class="form-control"
                                            value="{{ old('main_decision', $badLoan->partialRelease?->main_decision) }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Restructure / Reschedule Details</label>
                                        <textarea name="restructure_reschedule" class="form-control" rows="2">{{ old('restructure_reschedule', $badLoan->partialRelease?->restructure_reschedule) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $badLoan->partialRelease?->remarks) }}</textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-info text-white"><i
                                            class="bi bi-save me-1"></i>Save Partial Release Details</button>
                                </div>
                            </form>
                        </div>

                        <!-- ============================================== -->
                        <!-- TAB 4: NBA BOOK                                -->
                        <!-- ============================================== -->
                        <div class="tab-pane fade {{ $activeTab == 'nba' ? 'show active' : '' }}" id="nba"
                            role="tabpanel">
                            <form action="{{ route('recovery.nba.update', $badLoan->id) }}" method="POST">
                                @csrf
                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">NBA Book
                                    (Non-Banking Asset Acquisition)</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Committee's Valuation</label>
                                        <input type="number" step="0.01" name="committees_valuation"
                                            class="form-control"
                                            value="{{ old('committees_valuation', $badLoan->nba?->committees_valuation ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Proposal Received Date</label>
                                        <input type="date" name="proposal_received_date" class="form-control"
                                            value="{{ old('proposal_received_date', optional($badLoan->nba?->proposal_received_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Decision From Board</label>
                                        <input type="text" name="decision_from_board" class="form-control"
                                            value="{{ old('decision_from_board', $badLoan->nba?->decision_from_board) }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">35 Days Notice to Clients</label>
                                        <input type="date" name="35_days_notice" class="form-control"
                                            value="{{ old('35_days_notice', optional($badLoan->nba?->{'35_days_notice'})->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Letter to Land Reg. Office</label>
                                        <input type="date" name="letter_to_land_reg_office" class="form-control"
                                            value="{{ old('letter_to_land_reg_office', optional($badLoan->nba?->letter_to_land_reg_office)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Ownership Transfer Date</label>
                                        <input type="date" name="ownership_transfer" class="form-control"
                                            value="{{ old('ownership_transfer', optional($badLoan->nba?->ownership_transfer)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Value of Property</label>
                                        <input type="number" step="0.01" name="value_of_property"
                                            class="form-control"
                                            value="{{ old('value_of_property', $badLoan->nba?->value_of_property ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Remaining Loan</label>
                                        <input type="number" step="0.01" name="remaining_loan" class="form-control"
                                            value="{{ old('remaining_loan', $badLoan->nba?->remaining_loan ?? '0.00') }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">After NBA
                                    Book</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Revaluation</label>
                                        <input type="number" step="0.01" name="revaluation" class="form-control"
                                            value="{{ old('revaluation', $badLoan->nba?->revaluation ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Auction Notice</label>
                                        <input type="date" name="auction_notice" class="form-control"
                                            value="{{ old('auction_notice', optional($badLoan->nba?->auction_notice)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Remarks</label>
                                        <input type="text" name="remarks" class="form-control"
                                            value="{{ old('remarks', $badLoan->nba?->remarks) }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Client's
                                    Response</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Application Details</label>
                                        <textarea name="client_application" class="form-control" rows="2">{{ old('client_application', $badLoan->nba?->client_application) }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Deposit Amount</label>
                                        <input type="number" step="0.01" name="client_deposit_amount"
                                            class="form-control"
                                            value="{{ old('client_deposit_amount', $badLoan->nba?->client_deposit_amount ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Timeline Given</label>
                                        <input type="text" name="client_timeline" class="form-control"
                                            value="{{ old('client_timeline', $badLoan->nba?->client_timeline) }}">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Save
                                        NBA Details</button>
                                </div>
                            </form>
                        </div>

                        <!-- ============================================== -->
                        <!-- TAB 5: INTEREST REBATE / WRITE OFF             -->
                        <!-- ============================================== -->
                        <div class="tab-pane fade {{ $activeTab == 'interest_rebate' ? 'show active' : '' }}"
                            id="rebate" role="tabpanel">
                            <form action="{{ route('recovery.interest-rebate.update', $badLoan->id) }}" method="POST">
                                @csrf
                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Interest
                                    Rebate Scheme</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Proposal Received Date</label>
                                        <input type="date" name="proposal_received_date" class="form-control"
                                            value="{{ old('proposal_received_date', optional($badLoan->interestRebate?->proposal_received_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Decision Date</label>
                                        <input type="date" name="decision_date" class="form-control"
                                            value="{{ old('decision_date', optional($badLoan->interestRebate?->decision_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Decision Authority</label>
                                        <input type="text" name="decision_authority" class="form-control"
                                            placeholder="e.g. Board / CEO / Committee"
                                            value="{{ old('decision_authority', $badLoan->interestRebate?->decision_authority) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Interest Paid</label>
                                        <input type="number" step="0.01" name="interest_paid" class="form-control"
                                            value="{{ old('interest_paid', $badLoan->interestRebate?->interest_paid ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Interest Rebate Amount</label>
                                        <input type="number" step="0.01" name="interest_rebate" class="form-control"
                                            value="{{ old('interest_rebate', $badLoan->interestRebate?->interest_rebate ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label fw-semibold">Remarks</label>
                                        <input type="text" name="remarks" class="form-control"
                                            value="{{ old('remarks', $badLoan->interestRebate?->remarks) }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Write-off
                                    Loan Execution</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Write-off Principal</label>
                                        <input type="number" step="0.01" name="write_off_principal"
                                            class="form-control"
                                            value="{{ old('write_off_principal', $badLoan->interestRebate?->write_off_principal ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Regular Interest Written Off</label>
                                        <input type="number" step="0.01" name="write_off_regular_interest"
                                            class="form-control"
                                            value="{{ old('write_off_regular_interest', $badLoan->interestRebate?->write_off_regular_interest ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Penal Interest Written Off</label>
                                        <input type="number" step="0.01" name="write_off_penal_interest"
                                            class="form-control"
                                            value="{{ old('write_off_penal_interest', $badLoan->interestRebate?->write_off_penal_interest ?? '0.00') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Collateral Status</label>
                                        <input type="text" name="write_off_collateral" class="form-control"
                                            placeholder="Collateral released / retained"
                                            value="{{ old('write_off_collateral', $badLoan->interestRebate?->write_off_collateral) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Write-off Remarks</label>
                                        <input type="text" name="write_off_remarks" class="form-control"
                                            value="{{ old('write_off_remarks', $badLoan->interestRebate?->write_off_remarks) }}">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Save
                                        Interest Rebate & Write-Off</button>
                                </div>
                            </form>
                        </div>

                        <!-- ============================================== -->
                        <!-- TAB 6: DRT                                     -->
                        <!-- ============================================== -->
                        <div class="tab-pane fade {{ $activeTab == 'drt' ? 'show active' : '' }}" id="drt"
                            role="tabpanel">
                            <form action="{{ route('recovery.drt.update', $badLoan->id) }}" method="POST">
                                @csrf
                                <h6 class="text-secondary fw-bold text-uppercase fs-7 mb-3 border-bottom pb-2">Debt
                                    Recovery Tribunal (DRT) Proceedings</h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Proposal Received Date</label>
                                        <input type="date" name="proposal_received_date" class="form-control"
                                            value="{{ old('proposal_received_date', optional($badLoan->drt?->proposal_received_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Letter to Legal Department</label>
                                        <input type="date" name="letter_to_legal_department" class="form-control"
                                            value="{{ old('letter_to_legal_department', optional($badLoan->drt?->letter_to_legal_department)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Letter to DRT</label>
                                        <input type="date" name="letter_to_drt" class="form-control"
                                            value="{{ old('letter_to_drt', optional($badLoan->drt?->letter_to_drt)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Case Filed / Returned</label>
                                        <input type="text" name="case_filed_returned" class="form-control"
                                            placeholder="Filed / Case No."
                                            value="{{ old('case_filed_returned', $badLoan->drt?->case_filed_returned) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Decision From DRT</label>
                                        <input type="text" name="decision_from_drt" class="form-control"
                                            value="{{ old('decision_from_drt', $badLoan->drt?->decision_from_drt) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Decision Date</label>
                                        <input type="date" name="decision_date" class="form-control"
                                            value="{{ old('decision_date', optional($badLoan->drt?->decision_date)->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $badLoan->drt?->remarks) }}</textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-dark"><i class="bi bi-save me-1"></i>Save DRT
                                        Details</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Persist active tab when switching tabs
        document.addEventListener('DOMContentLoaded', function() {
            const tabTriggerList = [].slice.call(document.querySelectorAll(
                '#recoveryTabs button[data-bs-toggle="tab"]'));
            tabTriggerList.forEach(function(tabEl) {
                tabEl.addEventListener('shown.bs.tab', function(event) {
                    const tabId = event.target.getAttribute('data-bs-target').replace('#', '');
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabId);
                    window.history.replaceState({}, '', url);
                });
            });
        });
    </script>
@endpush
