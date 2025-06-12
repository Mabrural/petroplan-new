<div>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div class="form-group mb-3">
            <label for="update_password_current_password" class="form-label">Current Password</label>
            <input type="password"
                   name="current_password"
                   id="update_password_current_password"
                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                   autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- New Password -->
        <div class="form-group mb-3">
            <label for="update_password_password" class="form-label">New Password</label>
            <input type="password"
                   name="password"
                   id="update_password_password"
                   class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                   autocomplete="new-password">
            @error('password', 'updatePassword')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Confirm New Password -->
        <div class="form-group mb-4">
            <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
            <input type="password"
                   name="password_confirmation"
                   id="update_password_password_confirmation"
                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                   autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Save Button -->
        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary">Save</button>

            @if (session('status') === 'password-updated')
                <span class="text-success small">Saved.</span>
            @endif
        </div>
    </form>
</div>
