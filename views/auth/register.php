<?php
use App\Support\Auth;

$errors = $errors ?? [];
$input = $input ?? [];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - MDB CMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            animation: moveBackground 20s linear infinite;
            opacity: 0.3;
        }

        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-50%, -50%); }
        }

        .auth-container {
            position: relative;
            z-index: 1;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 3rem;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .auth-logo i {
            font-size: 2rem;
            color: white;
        }

        .auth-title {
            font-size: 2rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: #718096;
            font-size: 1rem;
        }

        .alert-danger {
            background: #fee;
            border-left: 4px solid #e53e3e;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 1.5rem;
            color: #c53030;
        }

        .alert-danger li {
            margin: 0.25rem 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2d3748;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-label i {
            margin-right: 0.5rem;
            color: #667eea;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f7fafc;
            font-family: 'Inter', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: #a0aec0;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #718096;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .form-group-password {
            position: relative;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-submit:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit i {
            margin-right: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .btn-text {
            position: relative;
            z-index: 1;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            color: #718096;
        }

        .auth-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
        }

        .auth-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .password-strength {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s;
            border-radius: 2px;
        }

        .password-hint {
            font-size: 0.85rem;
            color: #718096;
            margin-top: 0.5rem;
        }

        @media (max-width: 640px) {
            .auth-container {
                padding: 2rem 1.5rem;
            }

            .auth-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="auth-title">Đăng ký</h1>
            <p class="auth-subtitle">Tạo tài khoản mới để bắt đầu</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert-danger">
                <i class="fas fa-exclamation-circle"></i> <strong>Lỗi:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" id="registerForm">
            <!-- Username -->
            <div class="form-group">
                <label class="form-label" for="username">
                    <i class="fas fa-user"></i> Tên người dùng
                </label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-input"
                    placeholder="Nhập tên người dùng"
                    value="<?php echo htmlspecialchars($input['username'] ?? ''); ?>"
                    required
                />
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label" for="email">
                    <i class="fas fa-envelope"></i> Email
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input"
                    placeholder="your@email.com"
                    value="<?php echo htmlspecialchars($input['email'] ?? ''); ?>"
                    required
                />
            </div>

            <!-- Password -->
            <div class="form-group form-group-password">
                <label class="form-label" for="password">
                    <i class="fas fa-lock"></i> Mật khẩu
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input"
                    placeholder="Tối thiểu 6 ký tự"
                    minlength="6"
                    required
                />
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                </div>
                <div class="password-hint" id="passwordHint">Độ mạnh: Yếu</div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group form-group-password">
                <label class="form-label" for="password_confirm">
                    <i class="fas fa-check-circle"></i> Xác nhận mật khẩu
                </label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    class="form-input"
                    placeholder="Nhập lại mật khẩu"
                    required
                />
                <i class="fas fa-eye password-toggle" id="togglePasswordConfirm"></i>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">
                <i class="fas fa-user-plus"></i>
                <span class="btn-text">Đăng ký tài khoản</span>
            </button>

            <div class="auth-footer">
                <p>
                    Đã có tài khoản? 
                    <a href="index.php">Đăng nhập tại đây</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const passwordInput = document.getElementById('password_confirm');
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const hint = document.getElementById('passwordHint');
            
            let strength = 0;
            let strengthText = 'Yếu';
            let strengthColor = '#e53e3e';
            
            if (password.length >= 6) strength += 25;
            if (password.length >= 10) strength += 25;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 15;
            if (/[^a-zA-Z0-9]/.test(password)) strength += 10;
            
            if (strength >= 75) {
                strengthText = 'Rất mạnh';
                strengthColor = '#38a169';
            } else if (strength >= 50) {
                strengthText = 'Mạnh';
                strengthColor = '#48bb78';
            } else if (strength >= 25) {
                strengthText = 'Trung bình';
                strengthColor = '#ed8936';
            }
            
            strengthBar.style.width = strength + '%';
            strengthBar.style.background = strengthColor;
            hint.textContent = 'Độ mạnh: ' + strengthText;
            hint.style.color = strengthColor;
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirm').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Mật khẩu phải có ít nhất 6 ký tự!');
                return false;
            }
        });

        // Add smooth focus animation
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.form-label').style.color = '#667eea';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.form-label').style.color = '#2d3748';
            });
        });
    </script>
</body>
</html>
