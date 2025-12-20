<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Support\Auth;
use App\Support\Flash;

class PostController extends BaseController
{
    private $posts;
    private $users;
    private $categories;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->posts = new Post($pdo);
        $this->users = new User($pdo);
        $this->categories = new Category($pdo);
    }

    public function home($query = [], $input = [])
    {
        $page = isset($query['page']) ? max(1, (int) $query['page']) : 1;
        $filters = [];

        if (!empty($query['category'])) {
            $filters['category'] = $query['category'];
        }

        // Trang chủ (index.php) chỉ hiện bài viết của Admin (ID = 1)
        // Đây là "Official Blog", còn User thì có trang riêng (Author Page)
        $filters['author_id'] = 1;

        // Home feed luôn chỉ hiện bài Public (trừ khi có logic khác, nhưng Private chỉ nên hiện ở Author Page của chủ sở hữu)
        $filters['status'] = 'publish';

        $categories = $this->categories->all();
        $data = $this->posts->publicList($page, 10, $filters);

        $loginError = null;

        if (!empty($input['email']) && !Auth::check()) {
            $user = $this->users->attempt($input['email'], $input['password'] ?? '');
            if ($user) {
                Auth::login($user);
                Flash::set('Xin chào ' . $user['username']);

                // Chuyển hướng về "Trang chủ của họ" (Trang cá nhân author.php)
                header('Location: author.php?id=' . $user['id']);
                exit;
            }

            $loginError = 'Sai tài khoản hoặc mật khẩu!';
        }

        $this->render('posts/home', [
            'data' => $data,
            'categories' => $categories,
            'currentCategory' => $query['category'] ?? null,
            'loginError' => $loginError,
        ]);
    }

    public function author($id, $query = [])
    {
        $user = $this->users->find($id);
        if (!$user) {
            Flash::set('Người dùng không tồn tại', 'danger');
            header('Location: index.php');
            exit;
        }

        $page = isset($query['page']) ? max(1, (int) $query['page']) : 1;

        // Filter by author_id
        $filters = ['author_id' => $id];

        // Nếu người xem không phải là chủ sở hữu trang này (và không phải Admin), chỉ hiện bài Public
        $currentUserId = Auth::check() ? Auth::user()['id'] : null;
        if ($currentUserId != $id && !Auth::isAdmin()) {
            $filters['status'] = 'publish';
        }

        $categories = $this->categories->all();
        $data = $this->posts->publicList($page, 10, $filters);

        $this->render('posts/home', [
            'data' => $data,
            'categories' => $categories,
            'author' => $user, // Pass author info to view
            'pageTitle' => 'Bài viết của ' . $user['username']
        ]);
    }

    public function index($query = [])
    {
        Auth::requireLogin();

        if (!empty($query['delete'])) {
            $id = (int) $query['delete'];
            $post = $this->posts->find($id);

            // Chỉ cho phép xóa nếu là Admin hoặc là Tác giả bài viết
            if ($post && (Auth::isAdmin() || (Auth::user()['id'] == $post['author']))) {
                $this->posts->delete($id);
                Flash::set('Đã xóa bài viết #' . $id);
            } else {
                Flash::set('Bạn không có quyền xóa bài viết này!', 'danger');
            }

            header('Location: posts.php');
            exit;
        }

        $page = isset($query['page']) ? max(1, (int) $query['page']) : 1;
        $search = $query['search'] ?? '';

        // Nếu không phải Admin, chỉ lấy bài viết của user đó
        // Cần thêm logic filter vào search/paginate trong Model hoặc dùng publicList
        // Ở đây ta dùng publicList cho tiện vì nó đã có filter logic
        if (!Auth::isAdmin()) {
            $filters = ['author_id' => Auth::user()['id']];
            if (!empty($search)) {
                $filters['keyword'] = trim($search);
            }
            $data = $this->posts->publicList($page, 10, $filters);
        } else {
            // Admin thấy hết
            if (!empty($search)) {
                $data = $this->posts->search(trim($search), $page);
            } else {
                $data = $this->posts->paginate($page);
            }
        }

        $this->renderAdmin('posts/index', [
            'pageTitle' => 'Quản lý bài viết',
            'data' => $data,
            'search' => $search,
        ]);
    }

    public function create($input = [], $files = [])
    {
        Auth::requireLogin();

        if (!empty($input)) {
            $image = $this->storeImage($files['image'] ?? null);
            $newId = $this->posts->create([
                'title' => $input['title'] ?? '',
                'image' => $image,
                'content' => $input['content'] ?? '',
                'author' => Auth::user()['id'] ?? null,
                'date' => $input['date'] ?? date('Y-m-d'),
                'category_id' => $input['category_id'] ?? null,
                'status' => $input['status'] ?? 'publish',
            ]);

            Flash::set('Đã tạo bài viết mới thành công');
            if ($newId) {
                header('Location: post_comments.php?id=' . $newId);
            } else {
                header('Location: posts.php');
            }
            exit;
        }

        $categories = $this->categories->all();
        $this->renderAdmin('posts/form', [
            'pageTitle' => 'Thêm bài viết',
            'title' => 'Thêm bài viết mới',
            'post' => [
                'title' => '',
                'content' => '',
                'image' => '',
                'date' => date('Y-m-d'),
                'category_id' => '',
            ],
            'categories' => $categories,
        ]);
    }

    public function edit($id, $input = [], $files = [])
    {
        Auth::requireLogin();

        $post = $this->posts->find($id);
        if (!$post) {
            Flash::set('Không tìm thấy bài viết', 'danger');
            header('Location: posts.php');
            exit;
        }

        $currentUser = Auth::user();
        $isOwner = $currentUser && $post['author'] == $currentUser['id'];
        if (!Auth::isAdmin() && !$isOwner) {
            Flash::set('Bạn không có quyền sửa bài viết này!', 'danger');
            header('Location: posts.php');
            exit;
        }

        if (!empty($input)) {
            $image = $post['image'];
            $newImage = $this->storeImage($files['image'] ?? null);
            if ($newImage) {
                $image = $newImage;
            }

            $this->posts->update($id, [
                'title' => $input['title'] ?? '',
                'content' => $input['content'] ?? '',
                'image' => $image,
                'author' => Auth::user()['id'] ?? $post['author'],
                'date' => $input['date'] ?? $post['date'],
                'category_id' => $input['category_id'] ?? null,
                'status' => $input['status'] ?? $post['status'],
            ]);

            Flash::set('Đã cập nhật bài viết thành công');
            header('Location: posts.php');
            exit;
        }

        $categories = $this->categories->all();
        $this->renderAdmin('posts/form', [
            'pageTitle' => 'Sửa bài viết',
            'title' => 'Sửa bài viết',
            'post' => $post,
            'categories' => $categories,
        ]);
    }

    public function comments($id, $input = [])
    {
        $post = $this->posts->find($id);

        if (!$post) {
            Flash::set('Không tìm thấy bài viết', 'danger');
            header('Location: posts.php');
            exit;
        }

        if (!empty($input['comment'])) {
            Auth::requireLogin();

            $content = trim($input['comment']);
            if ($content !== '') {
                $this->posts->addComment($id, Auth::user()['id'], $content);
                Flash::set('Đã thêm bình luận');
                header('Location: post_comments.php?id=' . $id);
                exit;
            }
        }

        $comments = $this->posts->comments($id);

        $this->render('posts/detail', [
            'pageTitle' => $post['title'],
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    private function storeImage($image)
    {
        if (empty($image) || $image['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $folder = __DIR__ . '/../public/images/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('post_', true) . '.' . $ext;
        $path = $folder . $fileName;

        if (move_uploaded_file($image['tmp_name'], $path)) {
            return 'images/' . $fileName;
        }

        return null;
    }
}
