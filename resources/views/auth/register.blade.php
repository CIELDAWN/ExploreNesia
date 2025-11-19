<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - ExploreNesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #10b981;
            --accent-color: #f59e0b;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            max-width: 600px;
            margin: 50px auto;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .register-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
        }

        .register-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }

        .register-body {
            padding: 40px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .input-group-text {
            background: white;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .role-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 10px;
        }

        .role-card:hover {
            border-color: var(--primary-color);
            background: #f0f9ff;
        }

        .role-card.active {
            border-color: var(--primary-color);
            background: #eff6ff;
        }

        .role-card input[type="radio"] {
            margin-right: 10px;
        }

        .role-icon {
            font-size: 24px;
            margin-right: 15px;
            color: var(--primary-color);
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #6b7280;
            font-size: 14px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        #mitra-fields {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-card">
                <div class="register-header">
                    <h2><i class="fas fa-compass"></i> ExploreNesia</h2>
                    <p>Daftar untuk memulai petualangan Anda</p>
                </div>

                <div class="register-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register.submit') }}" method="POST">
                        @csrf

                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-user"></i> Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" class="form-control" placeholder="contoh@email.com" value="{{ old('email') }}" required>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-phone"></i> Nomor Telepon</label>
                            <input type="tel" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="togglePassword"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock"></i> Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="togglePasswordConfirm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="divider">
                            <span>Pilih Jenis Akun</span>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-3">
                            <div class="role-card" onclick="selectRole('user')">
                                <label style="cursor: pointer; display: flex; align-items: center; width: 100%;">
                                    <input type="radio" name="role" value="user" id="role-user" checked>
                                    <span class="role-icon"><i class="fas fa-user-circle"></i></span>
                                    <div>
                                        <strong>Wisatawan</strong>
                                        <div style="font-size: 13px; color: #6b7280;">Untuk mencari dan memesan destinasi wisata</div>
                                    </div>
                                </label>
                            </div>

                            <div class="role-card" onclick="selectRole('mitra')">
                                <label style="cursor: pointer; display: flex; align-items: center; width: 100%;">
                                    <input type="radio" name="role" value="mitra" id="role-mitra">
                                    <span class="role-icon"><i class="fas fa-briefcase"></i></span>
                                    <div>
                                        <strong>Mitra</strong>
                                        <div style="font-size: 13px; color: #6b7280;">Untuk hotel, tempat wisata, atau restoran</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Mitra Fields (Hidden by default) -->
                        <div id="mitra-fields" style="display: none;">
                            <h6 class="mb-3"><i class="fas fa-info-circle"></i> Informasi Mitra</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Jenis Mitra</label>
                                <select name="mitra_type" class="form-select">
                                    <option value="">Pilih Jenis Mitra</option>
                                    <option value="hotel">Hotel / Penginapan</option>
                                    <option value="wisata">Tempat Wisata</option>
                                    <option value="restoran">Restoran / Kuliner</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Usaha</label>
                                <input type="text" name="business_name" class="form-control" placeholder="Nama hotel/wisata/restoran" value="{{ old('business_name') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Usaha</label>
                                <textarea name="business_address" class="form-control" rows="3" placeholder="Alamat lengkap usaha">{{ old('business_address') }}</textarea>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya menyetujui <a href="#" style="color: var(--primary-color);">Syarat & Ketentuan</a> dan <a href="#" style="color: var(--primary-color);">Kebijakan Privasi</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-register">
                            <i class="fas fa-user-plus"></i> Daftar Sekarang
                        </button>

                        <div class="login-link">
                            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Password Visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = fieldId === 'password' ? document.getElementById('togglePassword') : document.getElementById('togglePasswordConfirm');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Role Selection
        function selectRole(role) {
            const roleCards = document.querySelectorAll('.role-card');
            roleCards.forEach(card => card.classList.remove('active'));
            
            const selectedCard = event.currentTarget;
            selectedCard.classList.add('active');
            
            const radio = document.getElementById('role-' + role);
            radio.checked = true;
            
            toggleMitraFields(role);
        }

        // Function to toggle mitra fields
        function toggleMitraFields(role) {
            const mitraFields = document.getElementById('mitra-fields');
            const mitraTypeSelect = document.querySelector('select[name="mitra_type"]');
            const businessNameInput = document.querySelector('input[name="business_name"]');
            const businessAddressTextarea = document.querySelector('textarea[name="business_address"]');
            
            if (role === 'mitra') {
                mitraFields.style.display = 'block';
                mitraTypeSelect.required = true;
                businessNameInput.required = true;
                businessAddressTextarea.required = true;
            } else {
                mitraFields.style.display = 'none';
                mitraTypeSelect.required = false;
                businessNameInput.required = false;
                businessAddressTextarea.required = false;
                // Clear values when switching to user
                mitraTypeSelect.value = '';
                businessNameInput.value = '';
                businessAddressTextarea.value = '';
            }
        }

        // Add event listeners to radio buttons
        document.getElementById('role-user').addEventListener('change', function() {
            if (this.checked) {
                toggleMitraFields('user');
            }
        });

        document.getElementById('role-mitra').addEventListener('change', function() {
            if (this.checked) {
                toggleMitraFields('mitra');
            }
        });

        // Initialize on page load - default to user
        document.addEventListener('DOMContentLoaded', function() {
            toggleMitraFields('user');
        });
    </script>
</body>
</html>