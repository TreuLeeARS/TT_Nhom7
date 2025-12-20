<?php

namespace App\Controllers;

use App\Models\Category;
use App\Support\Auth;
use App\Support\Flash;

class CategoryController extends BaseController
{
    private $categories;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->categories = new Category($pdo);
    }

    public function index()
    {
        Auth::requireAdmin();

        if (isset($_GET['delete'])) {
            $this->categories->delete($_GET['delete']);
            Flash::set('Đã xóa chuyên mục thành công');
            header('Location: categories.php');
            exit;
        }

        $categories = $this->categories->all();

        $this->renderAdmin('categories/index', [
            'pageTitle' => 'Quản lý chuyên mục',
            'categories' => $categories
        ]);
    }

    public function create($input = [], $files = [])
    {
        Auth::requireAdmin();

        if (!empty($input)) {
            $slug = $this->slugify($input['title']);

            // Basic validation
            if (empty($input['title'])) {
                Flash::set('Vui lòng nhập tên chuyên mục', 'danger');
            } else {
                $this->categories->create([
                    'title' => $input['title'],
                    'slug' => $slug,
                    'description' => $input['description'] ?? '',
                    'image' => null // Todo: Handle image upload if needed
                ]);

                Flash::set('Thêm chuyên mục thành công');
                header('Location: categories.php');
                exit;
            }
        }

        $this->renderAdmin('categories/form', [
            'pageTitle' => 'Thêm chuyên mục mới',
            'category' => []
        ]);
    }

    public function edit($id, $input = [], $files = [])
    {
        Auth::requireAdmin();

        $category = $this->categories->find($id);
        if (!$category) {
            Flash::set('Không tìm thấy chuyên mục', 'danger');
            header('Location: categories.php');
            exit;
        }

        if (!empty($input)) {
            $slug = $this->slugify($input['title']); // Regenerate slug or keep old? Usually regenerate if title changes.

            if (empty($input['title'])) {
                Flash::set('Vui lòng nhập tên chuyên mục', 'danger');
            } else {
                $this->categories->update($id, [
                    'title' => $input['title'],
                    'slug' => $slug,
                    'description' => $input['description'] ?? '',
                    'image' => $category['image'] // Preserve image for now
                ]);

                Flash::set('Cập nhật chuyên mục thành công');
                header('Location: categories.php');
                exit;
            }
        }

        $this->renderAdmin('categories/form', [
            'pageTitle' => 'Sửa chuyên mục',
            'category' => $category
        ]);
    }

    private function slugify($text)
    {
        // Simple slugify function for Vietnamese
        $text = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);
        $text = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text);
        $text = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $text);
        $text = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text);
        $text = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text);
        $text = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text);
        $text = preg_replace("/(đ)/", 'd', $text);
        $text = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text);
        $text = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text);
        $text = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text);
        $text = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text);
        $text = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text);
        $text = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text);
        $text = preg_replace("/(Đ)/", 'D', $text);
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9-]+/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }
}
