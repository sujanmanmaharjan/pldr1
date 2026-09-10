@extends('layouts.app')

@section('title', 'User & Role Management')
@section('page-title', 'User & Role Management')

@section('content')
    <div class="row g-3 mb-4">
        <!-- Brand Metric Counters -->
        <div class="col-md-4">
            <div class="metric-card">
                <span class="metric-label">Total System Users</span>
                <div class="metric-value">{{ $totalUsers }}</div>
                <small class="text-muted" style="font-size: 11px;">Active accounts across network</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="metric-card">
                <span class="metric-label">Central Recovery Staff</span>
                <div class="metric-value" style="color: var(--brand-accent);">{{ $centralCount }}</div>
                <small class="text-muted" style="font-size: 11px;">Full recovery & supervisory rights</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="metric-card">
                <span class="metric-label">Branch Loan Officers</span>
                <div class="metric-value" style="color: var(--brand-primary);">{{ $branchCount }}</div>
                <small class="text-muted" style="font-size: 11px;">Branch-restricted data entry users</small>
            </div>
        </div>

        <!-- Role Access & Security Policy Card -->
        <div class="col-12">
            <div class="card card-custom bg-white p-3 border card-accent-top">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-shield-check text-success fs-5"></i>
                    <h6 class="mb-0 fw-bold" style="color: var(--brand-primary);">Role-Based Access Control (RBAC)
                        Architecture</h6>
                </div>
                <div class="row g-2 small text-muted">
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light">
                            <strong class="text-danger"><i class="bi bi-building-fill-gear me-1"></i>Central Recovery
                                Staff:</strong>
                            <ul class="mb-0 ps-3 mt-1">
                                <li>Full system oversight across <strong>all branches</strong> without restrictions.</li>
                                <li>Exclusive authority to update the <strong>6 Recovery Modules</strong> (Blacklist,
                                    Auction, Partial Release, NBA Book, Interest Rebate/Write-Off, DRT).</li>
                                <li>Full control over User Accounts, Roles, and Branch Directory.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light">
                            <strong style="color: var(--brand-primary);"><i class="bi bi-geo-alt-fill me-1"></i>Branch
                                User:</strong>
                            <ul class="mb-0 ps-3 mt-1">
                                <li>Strictly isolated to bad loans belonging to their assigned <code>branch_id</code>.</li>
                                <li>Can create, edit, update, delete, and bulk Excel import basic loan records.</li>
                                <li>Forbidden from viewing or managing recovery stages or user accounts.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Actions Header -->
        <div class="col-12">
            <div class="card card-custom p-3 bg-white">
                <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control"
                                placeholder="Search user name or email..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="role" class="form-select form-select-sm">
                            <option value="">-- All Roles --</option>
                            <option value="central" {{ request('role') == 'central' ? 'selected' : '' }}>Central Recovery
                                Staff</option>
                            <option value="branch" {{ request('role') == 'branch' ? 'selected' : '' }}>Branch User</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="branch_id" class="form-select form-select-sm">
                            <option value="">-- All Branches --</option>
                            @foreach ($branches as $b)
                                <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->code }} - {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-brand-primary btn-sm"><i
                                class="bi bi-filter me-1"></i>Filter</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>

                    <div class="col-auto ms-auto">
                        <a href="{{ route('users.create') }}" class="btn btn-brand-primary btn-sm">
                            <i class="bi bi-person-plus-fill me-1"></i>Create New User
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="col-12">
            <div class="card card-custom bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Details</th>
                                <th>Email Address</th>
                                <th>Assigned Role</th>
                                <th>Assigned Branch</th>
                                <th>Registered On</th>
                                <th class="text-center" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                <tr>
                                    <td class="text-muted fw-semibold">#{{ $u->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 36px; height: 36px; background: var(--brand-tint); color: var(--brand-primary); font-size: 13px;">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $u->name }}</div>
                                                @if (Auth::id() === $u->id)
                                                    <span class="badge bg-success-subtle text-success-emphasis"
                                                        style="font-size: 0.7rem;">Current User</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-monospace text-muted">{{ $u->email }}</span>
                                    </td>
                                    <td>
                                        @if ($u->isCentral())
                                            <span class="badge badge-brand-accent">
                                                <i class="bi bi-shield-lock-fill me-1"></i>Central Staff
                                            </span>
                                        @else
                                            <span class="badge badge-brand">
                                                <i class="bi bi-person-badge me-1"></i>Branch User
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($u->isCentral())
                                            <span class="text-muted fst-italic">All Branches (Global Access)</span>
                                        @else
                                            <span class="badge-brand">
                                                {{ $u->branch->code ?? 'Unassigned' }} - {{ $u->branch->name ?? 'N/A' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $u->created_at ? $u->created_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('users.edit', $u->id) }}" class="btn btn-outline-secondary"
                                                title="Edit User & Role">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if (Auth::id() !== $u->id)
                                                <form action="{{ route('users.destroy', $u->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete user account: {{ $u->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"
                                                        title="Delete User">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-people fs-1 d-block mb-2 text-muted"></i>
                                        No user accounts found matching your query.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
