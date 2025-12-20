<?php

namespace App\Controllers;

use App\Models\User;
use App\Support\Auth;
use App\Support\Flash;

class UserController extends BaseController
{
    private $users;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->users = new User($pdo);
    }

    public function index($query = [])
    {
        Auth::requireAdmin(); // CHỈ ADMIN MỚI XEM ĐƯỢC DANH SÁCH USER

        if (!empty($query['delete'])) {
            $id = (int) $query['delete'];
            $this->users->delete($id);
            Flash::set('Đã xóa người dùng #' . $id);
            header('Location: users.php');
            exit;
        }

        $page = isset($query['page']) ? max(1, (int) $query['page']) : 1;
        $search = $query['search'] ?? '';

        // Nếu có tìm kiếm
        if (!empty($search)) {
            $data = $this->users->search(trim($search), $page, 20);
        } else {
            $data = $this->users->paginate($page, 20);
        }

        $this->renderAdmin('users/index', [
            'pageTitle' => 'Quản lý người dùng',
            'data' => $data,
            'search' => $search,
        ]);
    }

    public function create($input = [])
    {
        Auth::requireAdmin(); // CHỈ ADMIN MỚI TẠO ĐƯỢC USER MỚI

        if (!empty($input)) {
            $this->users->create([
                'username' => $input['username'] ?? '',
                'email' => $input['email'] ?? '',
                'password' => !empty($input['password']) ? sha1($input['password']) : '',
                'active' => isset($input['active']) ? 1 : 0,
                'role' => $input['role'] ?? 'user',
            ]);

            Flash::set('Đã tạo người dùng mới thành công');
            header('Location: users.php');
            exit;
        }

        $this->renderAdmin('users/form', [
            'pageTitle' => 'Thêm người dùng',
            'title' => 'Thêm người dùng mới',
            'user' => [
                'username' => '',
                'email' => '',
                'active' => 1,
                'role' => 'user',
            ],
        ]);
    }

    public function edit($id, $input = [])
    {
        Auth::requireAdmin(); // CHỈ ADMIN MỚI SỬA ĐƯỢC USER

        $user = $this->users->find($id);
        if (!$user) {
            Flash::set('Không tìm thấy người dùng', 'danger');
            header('Location: users.php');
            exit;
        }

        if (!empty($input)) {
            $user['username'] = $input['username'] ?? $user['username'];
            $user['email'] = $input['email'] ?? $user['email'];
            $user['active'] = isset($input['active']) ? 1 : 0;
            $user['role'] = $input['role'] ?? $user['role'];

            $password = $user['password'];
            if (!empty($input['password'])) {
                $password = sha1($input['password']);
            }

            $this->users->update($id, [
                'username' => $user['username'],
                'email' => $user['email'],
                'password' => $password,
                'active' => $user['active'],
                'role' => $user['role'],
            ]);

            Flash::set('Đã cập nhật người dùng thành công');
            header('Location: users.php');
            exit;
        }

        $this->renderAdmin('users/form', [
            'pageTitle' => 'Sửa người dùng',
            'title' => 'Sửa thông tin người dùng',
            'user' => $user,
        ]);
    }

    public function register($input = [])
    {
        // Nếu đã đăng nhập, chuyển về dashboard
        if (Auth::check()) {
            header('Location: dashboard.php');
            exit;
        }

        $errors = [];

        if (!empty($input)) {
            // Validation
            if (empty($input['username'])) {
                $errors[] = 'Vui lòng nhập tên người dùng';
            }

            if (empty($input['email'])) {
                $errors[] = 'Vui lòng nhập email';
            } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            } else {
                // Kiểm tra email đã tồn tại chưa
                if ($this->users->findByEmail($input['email'])) {
                    $errors[] = 'Email đã được sử dụng';
                }
            }

            if (empty($input['password'])) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($input['password']) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }

            if (empty($input['password_confirm'])) {
                $errors[] = 'Vui lòng xác nhận mật khẩu';
            } elseif ($input['password'] !== $input['password_confirm']) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }

            // Nếu không có lỗi, tạo tài khoản
            if (empty($errors)) {
                $this->users->create([
                    'username' => $input['username'],
                    'email' => $input['email'],
                    'password' => sha1($input['password']),
                    'active' => 1,
                    'role' => 'user',
                ]);

                // Tự động đăng nhập sau khi đăng ký
                $user = $this->users->findByEmail($input['email']);
                if ($user) {
                    Auth::login($user);
                    Flash::set('Đăng ký thành công! Chào mừng ' . $user['username']);
                    // Chuyển hướng về trang cá nhân của họ
                    header('Location: author.php?id=' . $user['id']);
                    exit;
                }
            }
        }

        $this->render('auth/register', [
            'errors' => $errors,
            'input' => $input,
        ]);
    }
}
