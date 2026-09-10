@extends('layouts.app')

@section('title', 'Edit Branch - ' . $branch->name)
@section('page-title', 'Edit Bank Branch')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom bg-white card-accent-top">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="eyebrow">ORGANIZATION JURISDICTION</span>
                        <h5 class="mb-0 fw-bold" style="color: var(--brand-primary);">
                            <i class="bi bi-pencil-square me-2"></i>Edit Branch: {{ $branch->name }}
                        </h5>
                    </div>
                    <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to Branch Directory
                    </a>
                </div>

                <div class="card-body p-4">
                    <!-- Branch Metrics Strip -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light text-center">
                                <span class="text-muted small text-uppercase fw-semibold d-block">Branch ID</span>
                                <span class="fw-bold font-monospace fs-5">#{{ $branch->id }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light text-center">
                                <span class="text-muted small text-uppercase fw-semibold d-block">Assigned Users</span>
                                <span class="badge badge-brand fs-6 px-3 py-1 mt-1">
                                    <i class="bi bi-person me-1"></i>{{ $branch->users_count }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light text-center">
                                <span class="text-muted small text-uppercase fw-semibold d-block">Linked Bad Loans</span>
                                <span class="badge badge-brand-accent fs-6 px-3 py-1 mt-1">
                                    <i class="bi bi-file-earmark-text me-1"></i>{{ $branch->bad_loans_count }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if ($branch->bad_loans_count > 0)
                        <div class="alert alert-warning d-flex align-items-center mb-4 py-2 px-3 small rounded"
                            role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-6 text-warning"></i>
                            <div>
                                This branch currently has <strong>{{ $branch->bad_loans_count }}</strong> active bad loan
                                records. Changing the branch code will update the reference for all associated accounts.
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">
                                    Branch Code <span class="required">*</span>
                                </label>
                                <input type="text" name="code"
                                    class="form-control font-monospace @error('code') is-invalid @enderror"
                                    value="{{ old('code', $branch->code) }}" placeholder="e.g. KTM-01" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small">Unique branch identifier code.</div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">
                                    Branch Name <span class="required">*</span>
                                </label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $branch->name) }}" placeholder="e.g. Kathmandu Main Branch"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small">Official administrative title of the bank branch.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-brand-primary px-4">
                                <i class="bi bi-arrow-repeat me-1"></i>Update Branch Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
