@extends('layouts.main')

@section('container')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit User</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user-management.index') }}">User Management</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Edit User</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit User Information</div>
                    </div>
                    <form method="POST" action="{{ route('user-management.update', $user->slug) }}" id="userForm">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        <div id="emailFeedback" class="invalid-feedback"></div>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">New Password (Leave blank to keep current)</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password">
                                        <small id="passwordHelp" class="form-text text-muted">Minimum 8 characters</small>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password-confirm">Confirm New Password</label>
                                        <input type="password" class="form-control" 
                                               id="password-confirm" name="password_confirmation">
                                        <div id="confirmFeedback" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Update User</button>
                            <a href="{{ route('user-management.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password-confirm');
    const submitBtn = document.getElementById('submitBtn');
    const emailFeedback = document.getElementById('emailFeedback');
    const passwordHelp = document.getElementById('passwordHelp');
    const confirmFeedback = document.getElementById('confirmFeedback');
    
    let emailValid = true; // Assume valid initially for edit
    let passwordValid = true; // Optional field
    let passwordMatch = true; // Optional field
    
    // Debounce function to limit how often we check the email
    function debounce(func, timeout = 500) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }
    
    // Check if email exists in database (excluding current user)
    const checkEmail = debounce(async (email) => {
        if (!email) return;
        
        try {
            const response = await fetch('/api/check-email?email=' + encodeURIComponent(email) + '&exclude={{ $user->id }}');
            const data = await response.json();
            
            if (data.exists) {
                emailInput.classList.add('is-invalid');
                emailFeedback.textContent = 'This email is already registered';
                emailValid = false;
            } else {
                emailInput.classList.remove('is-invalid');
                emailFeedback.textContent = '';
                emailValid = true;
            }
            updateSubmitButton();
        } catch (error) {
            console.error('Error checking email:', error);
        }
    });
    
    // Validate password length (only if provided)
    function validatePassword(password) {
        if (!password) {
            passwordValid = true;
            return;
        }
        
        if (password.length >= 8) {
            passwordInput.classList.remove('is-invalid');
            passwordHelp.textContent = '';
            passwordHelp.style.color = '#6c757d';
            passwordValid = true;
        } else {
            passwordInput.classList.add('is-invalid');
            passwordHelp.textContent = 'Password must be at least 8 characters';
            passwordHelp.style.color = '#dc3545';
            passwordValid = false;
        }
        updateSubmitButton();
    }
    
    // Check if passwords match (only if provided)
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = passwordConfirmInput.value;
        
        if (!password && !confirmPassword) {
            passwordMatch = true;
            passwordConfirmInput.classList.remove('is-invalid');
            confirmFeedback.textContent = '';
            updateSubmitButton();
            return;
        }
        
        if (password && confirmPassword && password === confirmPassword) {
            passwordConfirmInput.classList.remove('is-invalid');
            confirmFeedback.textContent = '';
            passwordMatch = true;
        } else if (password || confirmPassword) {
            passwordConfirmInput.classList.add('is-invalid');
            confirmFeedback.textContent = 'Passwords do not match';
            passwordMatch = false;
        }
        updateSubmitButton();
    }
    
    // Update submit button state
    function updateSubmitButton() {
        submitBtn.disabled = !(emailValid && passwordValid && passwordMatch);
    }
    
    // Event listeners
    emailInput.addEventListener('input', (e) => {
        checkEmail(e.target.value);
    });
    
    passwordInput.addEventListener('input', (e) => {
        validatePassword(e.target.value);
        checkPasswordMatch();
    });
    
    passwordConfirmInput.addEventListener('input', checkPasswordMatch);
    
    // Initial validation check
    emailInput.addEventListener('blur', (e) => {
        if (!e.target.value) {
            emailInput.classList.add('is-invalid');
            emailFeedback.textContent = 'Email is required';
            emailValid = false;
            updateSubmitButton();
        }
    });
    
    passwordInput.addEventListener('blur', (e) => {
        if (e.target.value && e.target.value.length < 8) {
            passwordInput.classList.add('is-invalid');
            passwordHelp.textContent = 'Password must be at least 8 characters';
            passwordHelp.style.color = '#dc3545';
            passwordValid = false;
            updateSubmitButton();
        }
    });
});
</script>
@endsection