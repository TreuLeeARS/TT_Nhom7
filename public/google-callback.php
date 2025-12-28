<?php

require __DIR__ . '/../config/app.php';

use App\Models\User;
use App\Support\Auth;
use App\Support\Flash;

// --- CẤU HÌNH GOOGLE ---
// 1. Vào https://console.cloud.google.com/
// 2. Tạo Project -> Credentials -> Create Credentials -> OAuth Client ID
// 3. Web Application
// 4. Authorized redirect URIs: Điền chính xác URL của file này (VD: https://domain.com/public/google-callback.php)
// 5. Copy Client ID và Client Secret vào bên dưới:

$clientId = '233792464598-8qns74p68arh512vgd7q29b424oi25iv.apps.googleusercontent.com';
$clientSecret = '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';
// URL này phải Trùng Khớp hoàn toàn với cái bạn khai báo trên Google Console
$redirectUri = 'https://trieuminhweb.xo.je/public/google-callback.php';

if (empty($_GET['code'])) {
    Flash::set('Lỗi: Không nhận được mã ủy quyền từ Google', 'danger');
    header('Location: index.php');
    exit;
}

// 1. Đổi code lấy Access Token
$tokenUrl = 'https://oauth2.googleapis.com/token';
$postData = [
    'code' => $_GET['code'],
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectUri,
    'grant_type' => 'authorization_code'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// KHÔNG NÊN tắt SSL trên môi trường production, nhưng trên host free/local fix lỗi SSL certificate
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    Flash::set('Lỗi kết nối Google: ' . curl_error($ch), 'danger');
    header('Location: index.php');
    exit;
}
curl_close($ch);

$tokenData = json_decode($response, true);

if (!isset($tokenData['access_token'])) {
    // Log response để debug nếu cần
    Flash::set('Lỗi đăng nhập: Không lấy được token từ Google', 'danger');
    header('Location: index.php');
    exit;
}

// 2. Lấy thông tin người dùng từ Google
$userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $tokenData['access_token']]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$googleUser = json_decode($response, true);

if (!isset($googleUser['email'])) {
    Flash::set('Lỗi: Không nhận được email từ Google', 'danger');
    header('Location: index.php');
    exit;
}

// 3. Xử lý đăng nhập/đăng ký vào hệ thống CMS
$userModel = new User($pdo);
$email = $googleUser['email'];
$name = $googleUser['name'] ?? 'Google User';
// Lấy ảnh đại diện nếu có (nếu bạn muốn lưu thêm avatar)
// $avatar = $googleUser['picture'] ?? '';

// Kiểm tra user có tồn tại không
$user = $userModel->findByEmail($email);

if ($user) {
    // CASE A: User đã tồn tại -> Đăng nhập ngay
    Auth::login($user);
    Flash::set('Chào mừng trở lại, ' . $user['username']);
} else {
    // CASE B: User chưa tồn tại -> Tự động đăng ký
    // Tạo mật khẩu ngẫu nhiên
    $randomPass = bin2hex(random_bytes(8));

    // Xử lý username trùng
    $tempUsername = $name;
    $counter = 1;
    while ($userModel->findByUsername($tempUsername)) {
        $tempUsername = $name . $counter;
        $counter++;
    }

    // Tạo user mới
    $userModel->create([
        'username' => $tempUsername,
        'email' => $email,
        'password' => sha1($randomPass), // Hash password
        'active' => 1,
        'role' => 'user'
    ]);

    // Login user mới tạo
    $user = $userModel->findByEmail($email);
    if ($user) {
        Auth::login($user);
        Flash::set('Đăng ký thành công qua Google! Mật khẩu ngẫu nhiên đã được tạo cho bạn.');
    } else {
        Flash::set('Lỗi tạo tài khoản mới.', 'danger');
        header('Location: index.php');
        exit;
    }
}

// Chuyển hướng về trang Author page của họ
header('Location: author.php?id=' . ($user['id'] ?? 1));
exit;
