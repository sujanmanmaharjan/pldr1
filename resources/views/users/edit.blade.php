@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)
@section('page-title', 'Edit User Account & Role')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom bg-white card-accent-top">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="eyebrow">USER MODIFICATION</span>
                        <h5 class="mb-0 fw-bold" style="color: var(--brand-primary);"><i
                                class="bi bi-pencil-square me-2"></i>Update User: {{ $user->name }}</h5>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to Users
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name & Email -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="required">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address <span class="required">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role & Branch Selection -->
                        <div class="row g-3 mb-4 p-3 rounded border"
                            style="background: var(--brand-tint); border-color: #d4def7 !important;">
                            <div class="col-md-6">
                                <label class="form-label">System Role <span class="required">*</span></label>
                                <select name="role" id="roleSelect"
                                    class="form-select @error('role') is-invalid @enderror" required
                                    onchange="handleRoleChange()">
                                    <option value="branch" {{ old('role', $user->role) == 'branch' ? 'selected' : '' }}>
                                        Branch User (Restricted to Branch)</option>
                                    <option value="central" {{ old('role', $user->role) == 'central' ? 'selected' : '' }}>
                                        Central Staff (Full Recovery & All Branches)</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small" id="roleHelp">Select the authority level for this user.</div>
                            </div>

                            <div class="col-md-6" id="branchContainer">
                                <label class="form-label" id="branchLabel">Assigned Branch <span
                                        class="required">*</span></label>
                                <select name="branch_id" id="branchSelect"
                                    class="form-select @error('branch_id') is-invalid @enderror">
                                    <option value="">-- Select Branch --</option>
                                    @foreach ($branches as $b)
                                        <option value="{{ $b->id }}"
                                            {{ old('branch_id', $user->branch_id) == $b->id ? 'selected' : '' }}>
                                            {{ $b->code }} - {{ $b->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text small" id="branchHelp">Required for branch users.</div>
                            </div>
                        </div>

                        <!-- Optional Password Change -->
                        <div class="border rounded p-3 mb-4 bg-light">
                            <h6 class="fw-semibold mb-2" style="color: var(--brand-primary); font-size: 13px;">Change
                                Password (Optional)</h6>
                            <small class="text-muted d-block mb-3">Leave both password fields blank if you do not wish to
                                change the password.</small>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Min 8 characters">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Repeat new password">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-brand-primary px-4">
                                <i class="bi bi-arrow-repeat me-1"></i>Update User Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function handleRoleChange() {
            const role = document.getElementById('roleSelect').value;
            const branchSelect = document.getElementById('branchSelect');
            const branchHelp = document.getElementById('branchHelp');
            const roleHelp = document.getElementById('roleHelp');

            if (role === 'central') {
                branchSelect.value = '';
                branchSelect.disabled = true;
                branchSelect.required = false;
                branchHelp.innerHTML =
                    '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Central Staff has access to all branches globally.</span>';
                roleHelp.innerText =
                    'Central Staff has exclusive access to the 6 Recovery modules and User/Branch management.';
            } else {
                branchSelect.disabled = false;
                branchSelect.required = true;
                branchHelp.innerText = 'Required. This user will only be able to view and manage records for this branch.';
                roleHelp.innerText = 'Branch users can only manage basic loans for their assigned branch.';
            }
        }

        document.addEventListener('DOMContentLoaded', handleRoleChange);
    </script>
@endpush
