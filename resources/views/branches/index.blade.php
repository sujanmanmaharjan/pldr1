@extends('layouts.app')

@section('title', 'Branch Management')
@section('page-title', 'Branch Directory')

@section('content')
    <div class="row g-3 mb-4">
        <!-- Header & Add Button -->
        <div class="col-12">
            <div class="card card-custom p-3 bg-white card-accent-top">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="eyebrow">ORGANIZATION JURISDICTIONS</span>
                        <h5 class="mb-0 fw-bold" style="color: var(--brand-primary);"><i
                                class="bi bi-buildings-fill me-2"></i>Bank Branches Directory</h5>
                        <small class="text-muted">Manage bank branch network where overdue loans originate and branch
                            officers operate.</small>
                    </div>
                    <button type="button" class="btn btn-brand-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#createBranchModal">
                        <i class="bi bi-plus-lg me-1"></i>Add New Branch
                    </button>
                </div>
            </div>
        </div>

        <!-- Branches Table -->
        <div class="col-12">
            <div class="card card-custom bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th class="text-center">Assigned Users</th>
                                <th class="text-center">Bad Loans Count</th>
                                <th>Created Date</th>
                                <th class="text-center" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branches as $b)
                                <tr>
                                    <td class="text-muted fw-semibold">#{{ $b->id }}</td>
                                    <td>
                                        <span class="badge-brand font-monospace fs-7 px-2 py-1">{{ $b->code }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold" style="color: var(--brand-primary);">{{ $b->name }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-brand px-2 py-1">
                                            <i class="bi bi-person me-1"></i>{{ $b->users_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-brand-accent px-2 py-1">
                                            <i class="bi bi-file-earmark-text me-1"></i>{{ $b->bad_loans_count }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $b->created_at ? $b->created_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('branches.edit', $b->id) }}" class="btn btn-outline-secondary"
                                                title="Edit Branch Page">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#editBranchModal{{ $b->id }}"
                                                title="Quick Edit Modal">
                                                <i class="bi bi-window"></i>
                                            </button>
                                            @if ($b->bad_loans_count == 0 && $b->users_count == 0)
                                                <form action="{{ route('branches.destroy', $b->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Delete branch {{ $b->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"
                                                        title="Delete Branch">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-outline-secondary text-muted" disabled
                                                    title="Cannot delete branch with active loans or assigned users">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-buildings fs-1 d-block mb-2 text-muted"></i>
                                        No branches recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($branches->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $branches->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODALS (Properly placed outside the table container)                      -->
    <!-- ========================================================================= -->

    <!-- Create Branch Modal -->
    <div class="modal fade" id="createBranchModal" tabindex="-1" aria-labelledby="createBranchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('branches.store') }}" method="POST" class="modal-content card-accent-top">
                @csrf
                <div class="modal-header">
                    <div>
                        <span class="eyebrow" style="font-size: 10px;">PROVISIONING</span>
                        <h5 class="modal-title fw-bold" id="createBranchModalLabel" style="color: var(--brand-primary);">
                            <i class="bi bi-building-add me-2"></i>Add New Bank Branch
                        </h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Branch Code <span class="required">*</span></label>
                        <input type="text" name="code" class="form-control font-monospace" placeholder="e.g. BIR-03"
                            required>
                        <div class="form-text small">Unique branch identifier code (e.g. KTM-01, PKR-02).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch Name <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control"
                            placeholder="e.g. Biratnagar Regional Branch" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i>Save Branch
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Branch Modals (Properly detached from <tbody>) -->
    @foreach ($branches as $b)
        <div class="modal fade" id="editBranchModal{{ $b->id }}" tabindex="-1"
            aria-labelledby="editBranchModalLabel{{ $b->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('branches.update', $b->id) }}" method="POST"
                    class="modal-content card-accent-top">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <div>
                            <span class="eyebrow" style="font-size: 10px;">BRANCH MODIFICATION</span>
                            <h5 class="modal-title fw-bold" id="editBranchModalLabel{{ $b->id }}"
                                style="color: var(--brand-primary);">
                                <i class="bi bi-pencil-square me-2"></i>Edit Branch: {{ $b->name }}
                            </h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Branch Code <span class="required">*</span></label>
                            <input type="text" name="code" class="form-control font-monospace"
                                value="{{ old('code', $b->code) }}" required>
                            <div class="form-text small">Unique branch identifier code.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Branch Name <span class="required">*</span></label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $b->name) }}" required>
                        </div>

                        <div class="p-2 border rounded bg-light small mt-3">
                            <span class="text-muted">Currently linked:</span>
                            <strong>{{ $b->users_count }}</strong> assigned users,
                            <strong>{{ $b->bad_loans_count }}</strong> active bad loans.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-brand-primary btn-sm">
                            <i class="bi bi-arrow-repeat me-1"></i>Update Branch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection
