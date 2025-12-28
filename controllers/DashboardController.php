<?php

namespace App\Controllers;

use App\Support\Auth;
use PDO;

class DashboardController extends BaseController
{
    public function index($query = [])
    {
        Auth::requireLogin();
        $currentUser = Auth::user();

        $page = isset($query['page']) ? max(1, (int) $query['page']) : 1;

        if ($currentUser['role'] === 'admin') {
            // ADMIN DASHBOARD (User Management Focus)
            $stats = $this->getAdminStatistics();
            $this->renderAdmin('dashboard/index', [
                'pageTitle' => 'Admin Dashboard',
                'user' => $currentUser,
                'stats' => $stats,
            ]);
        } else {
            // USER DASHBOARD (Author Focus)
            $stats = $this->getUserStatistics($currentUser['id']);

            // Get paginated posts for this user
            $postModel = new \App\Models\Post($this->pdo);
            $postsData = $postModel->publicList($page, 2, ['author_id' => $currentUser['id']]);

            $this->renderAdmin('dashboard/user', [
                'pageTitle' => 'Dashboard Tác giả',
                'user' => $currentUser,
                'stats' => $stats,
                'data' => $postsData, // Pass paginated data
            ]);
        }
    }

    private function getAdminStatistics()
    {
        // 1. Total Users
        $totalUsers = $this->pdo->query('SELECT COUNT(*) as total FROM users')->fetch()['total'] ?? 0;

        // 2. New Users (This Month)
        $newUsers = $this->pdo->query("SELECT COUNT(*) as total FROM users WHERE created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')")->fetch()['total'] ?? 0;

        // 3. Active Users
        $activeUsers = $this->pdo->query("SELECT COUNT(*) as total FROM users WHERE active = 1")->fetch()['total'] ?? 0;

        // 4. Role Distribution
        $stmt = $this->pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
        $roles = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // 5. Account Status (Group by active)
        $stmt = $this->pdo->query("SELECT active, COUNT(*) as count FROM users GROUP BY active");
        $statuses = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // 6. Recent Users
        $stmt = $this->pdo->query('SELECT * FROM users ORDER BY id DESC LIMIT 5');
        $recentUsers = $stmt->fetchAll();

        return [
            'total_users' => $totalUsers,
            'new_users' => $newUsers,
            'active_users' => $activeUsers,
            'roles' => $roles,
            'statuses' => $statuses,
            'recent_users' => $recentUsers
        ];
    }

    private function getUserStatistics($userId)
    {
        // 1. My Posts Count
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as total FROM posts WHERE author = ?');
        $stmt->execute([$userId]);
        $myPostsCount = $stmt->fetch()['total'] ?? 0;

        // 2. My Total Views
        $stmt = $this->pdo->prepare('SELECT SUM(views) as total FROM posts WHERE author = ?');
        $stmt->execute([$userId]);
        $myTotalViews = $stmt->fetch()['total'] ?? 0;

        // 3. Comments on My Posts
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total 
            FROM comments c
            JOIN posts p ON p.id = c.post_id
            WHERE p.author = ?
        ");
        $stmt->execute([$userId]);
        $myCommentsCount = $stmt->fetch()['total'] ?? 0;

        // 4. Recent Posts
        $stmt = $this->pdo->prepare('SELECT * FROM posts WHERE author = ? ORDER BY date DESC LIMIT 5');
        $stmt->execute([$userId]);
        $recentPosts = $stmt->fetchAll();

        return [
            'my_posts_count' => $myPostsCount,
            'my_total_views' => $myTotalViews,
            'my_comments_count' => $myCommentsCount,
            'recent_posts' => $recentPosts
        ];
    }
}
