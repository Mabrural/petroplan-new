<div>
    <!-- Info Text -->
    <div class="mb-4">
        <h5 class="fw-bold text-danger">Delete Account</h5>
        <p class="text-muted small">
            Once your account is deleted, all of its data and resources will be permanently removed. Please ensure you’ve saved anything important before continuing.
        </p>
    </div>

    <!-- Trigger Button -->
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        Delete Account
    </button>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('profile.destroy') }}" class="modal-content">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Once your account is deleted, all of its data and resources will be permanently removed.
                        Please enter your password to confirm this action.
                    </p>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="delete_password" class="form-label">Password</label>
                        <input type="password"
                               name="password"
                               id="delete_password"
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               placeholder="Enter your password"
                               required>
                        @error('password', 'userDeletion')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
