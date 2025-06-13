@extends('layouts.main')

@section('container')
<div class="container">
    <div class="page-inner">
        <div class="container">
            <h3>Assign Role/Permission to {{ $user->name }}</h3>
    
            <form action="{{ route('role-permissions.store', $user->slug) }}" method="POST">
                @csrf
    
                <div class="row">
                    <!-- Role Selection -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="permission" class="form-label fw-bold">Select Role</label>
                            <select class="form-select @error('permission') is-invalid @enderror" id="permission" name="permission" required>
                                <option value="" selected disabled>-- Select Role --</option>
                                <option value="admin_officer" {{ old('permission') == 'admin_officer' ? 'selected' : '' }}>Admin Officer</option>
                                <option value="operasion" {{ old('permission') == 'operasion' ? 'selected' : '' }}>Operation</option>
                            </select>
                            @error('permission')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
    
                <button type="submit" class="btn btn-primary">Assign</button>
                <a href="{{ route('role-permissions.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection