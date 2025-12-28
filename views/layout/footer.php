<?php
use App\Support\Auth;
?>

<!-- Modern Footer -->
<footer class="modern-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3><i class="fas fa-blog"></i> MDB Blog</h3>
            <p>Nền tảng blog hiện đại được xây dựng với PHP MVC. Chia sẻ kiến thức, kết nối cộng đồng.</p>
            <div style="margin-top: 1rem;">
                <a href="#" style="display: inline-block; margin-right: 1rem; font-size: 1.5rem;">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" style="display: inline-block; margin-right: 1rem; font-size: 1.5rem;">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" style="display: inline-block; margin-right: 1rem; font-size: 1.5rem;">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" style="display: inline-block; font-size: 1.5rem;">
                    <i class="fab fa-github"></i>
                </a>
            </div>
        </div>

        <div class="footer-section">
            <h3>Danh mục</h3>
            <a href="index.php"><i class="fas fa-angle-right"></i> Trang chủ</a>
            <a href="index.php"><i class="fas fa-angle-right"></i> Bài viết mới</a>
            <?php if (Auth::check()): ?>
                <a href="dashboard.php"><i class="fas fa-angle-right"></i> Dashboard</a>
            <?php else: ?>
                <a href="register.php"><i class="fas fa-angle-right"></i> Đăng ký</a>
            <?php endif; ?>
        </div>

        <div class="footer-section">
            <h3>Thông tin</h3>
            <a href="#"><i class="fas fa-angle-right"></i> Về chúng tôi</a>
            <a href="#"><i class="fas fa-angle-right"></i> Liên hệ</a>
            <a href="#"><i class="fas fa-angle-right"></i> Điều khoản sử dụng</a>
            <a href="#"><i class="fas fa-angle-right"></i> Chính sách bảo mật</a>
        </div>

        <div class="footer-section">
            <h3>Liên hệ</h3>
            <p><i class="fas fa-envelope"></i> leminhtrieu35@gmail.com</p>
            <p><i class="fas fa-phone"></i> +84 363 977 304</p>
            <p><i class="fas fa-map-marker-alt"></i> Hồ Chí Minh, Việt Nam</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> MDB Blog CMS. Designed with <i class="fas fa-heart"
                style="color: #e74c3c;"></i> by Students</p>
    </div>
</footer>

<script src="js/mdb.min.js"></script>

<!-- Login Modal -->
<?php if (!Auth::check()): ?>
    <div id="loginModal" class="auth-modal">
        <div class="auth-modal-overlay" onclick="closeLoginModal()"></div>
        <div class="auth-modal-content">
            <button class="auth-modal-close" onclick="closeLoginModal()">
                <i class="fas fa-times"></i>
            </button>

            <div class="auth-modal-header">
                <div class="auth-modal-logo">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h2 class="auth-modal-title">Đăng nhập</h2>
                <p class="auth-modal-subtitle">Chào mừng bạn quay lại!</p>
            </div>

            <div id="loginError" class="auth-modal-alert" style="display: none;">
                <i class="fas fa-exclamation-circle"></i>
                <span id="loginErrorMessage"></span>
            </div>

            <form method="post" action="index.php" id="loginForm" class="auth-modal-form">
                <div class="auth-form-group">
                    <label class="auth-form-label">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" name="email" class="auth-form-input" placeholder="your@email.com" required />
                </div>

                <div class="auth-form-group" style="position: relative;">
                    <label class="auth-form-label">
                        <i class="fas fa-lock"></i> Mật khẩu
                    </label>
                    <input type="password" id="loginPassword" name="password" class="auth-form-input" placeholder="••••••••"
                        required />
                    <i class="fas fa-eye auth-password-toggle" onclick="togglePassword('loginPassword', this)"></i>
                </div>

                <button type="submit" class="auth-modal-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Đăng nhập</span>
                </button>

                <div class="auth-modal-footer">
                    <p>
                        Chưa có tài khoản?
                        <a href="#" onclick="switchToRegister(); return false;">Đăng ký ngay</a>
                    </p>
                    <div class="auth-separator">
                        <span>Hoặc tiếp tục với</span>
                    </div>
                    <a href="https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=233792464598-8qns74p68arh512vgd7q29b424oi25iv.apps.googleusercontent.com&redirect_uri=https://trieuminhweb.xo.je/public/google-callback.php&scope=email%20profile"
                        class="auth-google-btn">
                        <svg class="google-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                            <path fill="#FFC107"
                                d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
                            <path fill="#FF3D00"
                                d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
                            <path fill="#4CAF50"
                                d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
                            <path fill="#1976D2"
                                d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
                        </svg>
                        <span>Đăng nhập với Google</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Register Modal -->
    <div id="registerModal" class="auth-modal">
        <div class="auth-modal-overlay" onclick="closeRegisterModal()"></div>
        <div class="auth-modal-content">
            <button class="auth-modal-close" onclick="closeRegisterModal()">
                <i class="fas fa-times"></i>
            </button>

            <div class="auth-modal-header">
                <div class="auth-modal-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="auth-modal-title">Đăng ký</h2>
                <p class="auth-modal-subtitle">Tạo tài khoản mới để bắt đầu</p>
            </div>

            <div id="registerError" class="auth-modal-alert" style="display: none;">
                <i class="fas fa-exclamation-circle"></i>
                <span id="registerErrorMessage"></span>
            </div>

            <form method="post" action="register.php" id="registerFormModal" class="auth-modal-form">
                <div class="auth-form-group">
                    <label class="auth-form-label">
                        <i class="fas fa-user"></i> Tên người dùng
                    </label>
                    <input type="text" name="username" class="auth-form-input" placeholder="Nhập tên người dùng" required />
                </div>

                <div class="auth-form-group">
                    <label class="auth-form-label">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input type="email" name="email" class="auth-form-input" placeholder="your@email.com" required />
                </div>

                <div class="auth-form-group" style="position: relative;">
                    <label class="auth-form-label">
                        <i class="fas fa-lock"></i> Mật khẩu
                    </label>
                    <input type="password" id="registerPassword" name="password" class="auth-form-input"
                        placeholder="Tối thiểu 6 ký tự" minlength="6" required />
                    <i class="fas fa-eye auth-password-toggle" onclick="togglePassword('registerPassword', this)"></i>
                    <div class="password-strength-bar">
                        <div class="password-strength-fill" id="registerStrengthBar"></div>
                    </div>
                    <div class="password-hint" id="registerPasswordHint">Độ mạnh: Yếu</div>
                </div>

                <div class="auth-form-group" style="position: relative;">
                    <label class="auth-form-label">
                        <i class="fas fa-check-circle"></i> Xác nhận mật khẩu
                    </label>
                    <input type="password" id="registerPasswordConfirm" name="password_confirm" class="auth-form-input"
                        placeholder="Nhập lại mật khẩu" required />
                    <i class="fas fa-eye auth-password-toggle"
                        onclick="togglePassword('registerPasswordConfirm', this)"></i>
                </div>

                <button type="submit" class="auth-modal-btn">
                    <i class="fas fa-user-plus"></i>
                    <span>Đăng ký tài khoản</span>
                </button>

                <div class="auth-modal-footer">
                    <p>
                        Đã có tài khoản?
                        <a href="#" onclick="switchToLogin(); return false;">Đăng nhập tại đây</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Modal Styles */
        .auth-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            animation: fadeIn 0.3s ease-out;
        }

        .auth-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }

        .auth-modal-content {
            position: relative;
            background: white;
            border-radius: 24px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.4s ease-out;
            z-index: 10001;
        }

        .auth-modal-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: #f7fafc;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #718096;
            font-size: 1.2rem;
        }

        .auth-modal-close:hover {
            background: #667eea;
            color: white;
            transform: rotate(90deg);
        }

        .auth-modal-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-modal-logo {
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

        .auth-modal-logo i {
            font-size: 2rem;
            color: white;
        }

        .auth-modal-title {
            font-size: 2rem;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }

        .auth-modal-subtitle {
            color: #718096;
            font-size: 1rem;
        }

        .auth-modal-alert {
            background: #fee;
            border-left: 4px solid #e53e3e;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            color: #c53030;
            animation: shake 0.5s;
        }

        .auth-modal-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .auth-form-group {
            position: relative;
        }

        .auth-form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2d3748;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .auth-form-label i {
            margin-right: 0.5rem;
            color: #667eea;
        }

        .auth-form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f7fafc;
            font-family: 'Inter', sans-serif;
        }

        .auth-form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .auth-password-toggle {
            position: absolute;
            right: 1rem;
            top: 2.8rem;
            cursor: pointer;
            color: #718096;
            transition: color 0.3s;
        }

        .auth-password-toggle:hover {
            color: #667eea;
        }

        .password-strength-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .password-strength-fill {
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

        .auth-modal-btn {
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

        .auth-modal-btn::before {
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

        .auth-modal-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .auth-modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.6);
        }

        .auth-modal-btn i {
            margin-right: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .auth-modal-btn span {
            position: relative;
            z-index: 1;
        }

        .auth-modal-footer {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
            color: #718096;
        }

        .auth-modal-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
        }

        .auth-modal-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        /* Google Login Styles */
        .auth-separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #a0aec0;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .auth-separator::before,
        .auth-separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .auth-separator span {
            padding: 0 1rem;
        }

        .auth-google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 0.8rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            color: #2d3748;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .auth-google-btn svg {
            width: 24px;
            height: 24px;
            transition: transform 0.3s ease;
        }

        .auth-google-btn:hover {
            border-color: #cdd6e0;
            background-color: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            color: #1a202c;
        }

        .auth-google-btn:hover svg {
            transform: scale(1.1);
        }

        .auth-google-btn:active {
            transform: translateY(0);
            box-shadow: none;
        }

        @media (max-width: 640px) {
            .auth-modal-content {
                padding: 2rem 1.5rem;
            }

            .auth-modal-title {
                font-size: 1.75rem;
            }
        }
    </style>

    <script>
        // Modal Functions
        function openLoginModal() {
            document.getElementById('loginModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        function openRegisterModal() {
            document.getElementById('registerModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        function switchToRegister() {
            closeLoginModal();
            setTimeout(openRegisterModal, 200);
        }

        function switchToLogin() {
            closeRegisterModal();
            setTimeout(openLoginModal, 200);
        }

        // Toggle password visibility
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }

        // Password strength indicator for register
        const registerPasswordInput = document.getElementById('registerPassword');
        if (registerPasswordInput) {
            registerPasswordInput.addEventListener('input', function () {
                const password = this.value;
                const strengthBar = document.getElementById('registerStrengthBar');
                const hint = document.getElementById('registerPasswordHint');

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
        }

        // Form validation for register
        const registerForm = document.getElementById('registerFormModal');
        if (registerForm) {
            registerForm.addEventListener('submit', function (e) {
                const password = document.getElementById('registerPassword').value;
                const confirmPassword = document.getElementById('registerPasswordConfirm').value;

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
        }

        // Close modal when pressing ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeLoginModal();
                closeRegisterModal();
            }
        });

        // Focus animation for inputs
        document.querySelectorAll('.auth-form-input').forEach(input => {
            input.addEventListener('focus', function () {
                const label = this.parentElement.querySelector('.auth-form-label');
                if (label) label.style.color = '#667eea';
            });

            input.addEventListener('blur', function () {
                const label = this.parentElement.querySelector('.auth-form-label');
                if (label) label.style.color = '#2d3748';
            });
        });
    </script>
<?php endif; ?>

<?php if (isset($loginError) && $loginError): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            openLoginModal();
            const errorAlert = document.getElementById('loginError');
            const errorMessage = document.getElementById('loginErrorMessage');
            if (errorAlert && errorMessage) {
                errorMessage.textContent = "<?php echo addslashes($loginError); ?>";
                errorAlert.style.display = 'block';
            }
        });
    </script>
<?php endif; ?>

<script>
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || href.startsWith('#login') || href.startsWith('#register')) {
                return;
            }
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Toast notification
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.innerHTML = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 3000);
    }

    // Confirm delete function
    function confirmDelete(message) {
        return confirm(message || 'Bạn có chắc chắn muốn xóa?');
    }
</script>

</body>

</html>