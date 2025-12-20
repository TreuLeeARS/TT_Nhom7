
-- BLOG CMS - DATABASE STRUCTURE

-- Drop tables if exists 
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;


-- Bảng USERS (Người dùng)

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(100) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_email (email),
  INDEX idx_role (role),
  INDEX idx_active (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Bảng CATEGORIES (Chuyên mục)
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT,
  image VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng POSTS (Bài viết)
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT DEFAULT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  content TEXT NOT NULL,
  excerpt VARCHAR(500) DEFAULT NULL,
  author INT NOT NULL,
  date DATETIME DEFAULT CURRENT_TIMESTAMP,
  views INT DEFAULT 0,
  status VARCHAR(20) NOT NULL DEFAULT 'publish',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (author) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
  
  INDEX idx_author (author),
  INDEX idx_category (category_id),
  INDEX idx_date (date),
  INDEX idx_status (status),
  INDEX idx_slug (slug),
  FULLTEXT INDEX idx_search (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng COMMENTS (Bình luận)
CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  user_id INT NOT NULL,
  content TEXT NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'approved',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  
  INDEX idx_post (post_id),
  INDEX idx_user (user_id),
  INDEX idx_status (status),
  INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SAMPLE DATA (Dữ liệu mẫu)

-- Insert Users (Admin + Normal Users)
INSERT INTO users (username, email, password, role, active) VALUES
('admin', 'admin@admin.com', SHA1('admin123'), 'admin', 1),
('john_doe', 'john@example.com', SHA1('123456'), 'user', 1),
('jane_smith', 'jane@example.com', SHA1('123456'), 'user', 1),
('testuser', 'test@test.com', SHA1('123456'), 'user', 1);

-- Insert Categories
INSERT INTO categories (title, slug, description) VALUES
('Công nghệ', 'cong-nghe', 'Tin tức và xu hướng công nghệ mới nhất'),
('Lập trình', 'lap-trinh', 'Hướng dẫn lập trình và phát triển phần mềm'),
('Thiết kế', 'thiet-ke', 'Thiết kế UI/UX và đồ họa'),
('Tin tức', 'tin-tuc', 'Tin tức tổng hợp hàng ngày'),
('Review', 'review', 'Đánh giá sản phẩm công nghệ');

-- Insert Posts (Sample blog posts)
INSERT INTO posts (category_id, title, slug, content, excerpt, author, date, views, status) VALUES
(1, 'Xu hướng công nghệ AI năm 2024', 'xu-huong-cong-nghe-ai-2024', 
 'Trí tuệ nhân tạo (AI) đang thay đổi mọi khía cạnh của cuộc sống. Từ chatbots thông minh như ChatGPT đến các hệ thống tự lái, AI đang định hình tương lai của nhân loại. Trong bài viết này, chúng ta sẽ khám phá những xu hướng AI mới nhất và tác động của chúng đối với xã hội.', 
 'Khám phá những xu hướng AI mới nhất đang định hình tương lai', 
 1, NOW() - INTERVAL 1 DAY, 234, 'publish'),

(2, 'Hướng dẫn PHP MVC cho người mới bắt đầu', 'huong-dan-php-mvc', 
 'MVC (Model-View-Controller) là một mô hình kiến trúc phần mềm phổ biến. Model quản lý dữ liệu, View hiển thị giao diện, Controller xử lý logic. Bài viết này sẽ hướng dẫn bạn xây dựng ứng dụng PHP theo mô hình MVC từ cơ bản đến nâng cao.', 
 'Tìm hiểu cách xây dựng ứng dụng PHP với mô hình MVC', 
 2, NOW() - INTERVAL 2 DAY, 456, 'publish'),

(3, 'Thiết kế giao diện người dùng hiện đại', 'thiet-ke-ui-hien-dai', 
 'UI/UX design là yếu tố quyết định thành công của một ứng dụng. Gradient backgrounds, glass morphism, và dark mode là những xu hướng thiết kế đang thịnh hành. Hãy cùng tìm hiểu cách áp dụng những xu hướng này vào dự án của bạn.', 
 'Khám phá xu hướng thiết kế UI/UX mới nhất', 
 1, NOW() - INTERVAL 3 DAY, 189, 'publish'),

(2, 'JavaScript ES2024: Những tính năng mới', 'javascript-es2024', 
 'JavaScript không ngừng phát triển với những tính năng mới mỗi năm. ES2024 mang đến nhiều cải tiến quan trọng như temporal API, decorators, và pattern matching. Bài viết này sẽ giới thiệu chi tiết những tính năng mới và cách sử dụng chúng hiệu quả.', 
 'Tìm hiểu những tính năng JavaScript mới nhất trong ES2024', 
 3, NOW() - INTERVAL 5 DAY, 567, 'publish'),

(1, 'Cloud Computing: AWS vs Azure vs Google Cloud', 'cloud-computing-comparison', 
 'So sánh chi tiết ba nền tảng cloud computing hàng đầu: Amazon AWS, Microsoft Azure và Google Cloud Platform. Phân tích ưu nhược điểm, giá cả, và use cases phù hợp cho từng nền tảng để giúp bạn đưa ra quyết định đúng đắn.', 
 'So sánh AWS, Azure, và Google Cloud Platform', 
 2, NOW() - INTERVAL 7 DAY, 890, 'publish'),

(5, 'Review MacBook Pro M3 2024', 'review-macbook-pro-m3', 
 'MacBook Pro với chip M3 mới nhất của Apple mang đến hiệu năng vượt trội. Với 18 giờ pin, màn hình Retina 14 inch, và khả năng xử lý đồ họa mạnh mẽ, đây là chiếc laptop hoàn hảo cho developers và designers. Hãy cùng xem đánh giá chi tiết.', 
 'Đánh giá chi tiết MacBook Pro M3 2024', 
 1, NOW() - INTERVAL 10 DAY, 1234, 'publish'),

(4, 'Top 10 công cụ lập trình viên nên biết năm 2024', 'top-10-cong-cu-lap-trinh', 
 'Danh sách 10 công cụ thiết yếu mà mọi lập trình viên nên biết: VS Code, Git, Docker, Postman, và nhiều hơn nữa. Mỗi công cụ được phân tích chi tiết với hướng dẫn sử dụng cơ bản để tăng năng suất làm việc của bạn.', 
 'Công cụ thiết yếu cho lập trình viên hiện đại', 
 3, NOW() - INTERVAL 14 DAY, 678, 'publish');

-- Insert Comments (Sample comments)
INSERT INTO comments (post_id, user_id, content, status, created_at) VALUES
(1, 2, 'Bài viết rất hay và chi tiết! Cảm ơn tác giả đã chia sẻ.', 'approved', NOW() - INTERVAL 12 HOUR),
(1, 3, 'AI đang thực sự thay đổi thế giới. Mong có thêm nhiều bài viết về chủ đề này.', 'approved', NOW() - INTERVAL 10 HOUR),
(1, 4, 'Rất hữu ích cho người mới bắt đầu tìm hiểu về AI.', 'approved', NOW() - INTERVAL 8 HOUR),

(2, 3, 'Hướng dẫn rất dễ hiểu. Em đã apply được vào project của mình.', 'approved', NOW() - INTERVAL 6 HOUR),
(2, 4, 'Code example rất rõ ràng. Thanks anh!', 'approved', NOW() - INTERVAL 5 HOUR),

(3, 2, 'UI design trends này đang rất hot. Mình đã áp dụng vào dự án startup.', 'approved', NOW() - INTERVAL 4 HOUR),
(3, 4, 'Gradient background đẹp thật. Có tutorial làm không ạ?', 'approved', NOW() - INTERVAL 3 HOUR),

(4, 2, 'ES2024 có nhiều feature hay quá! Temporal API cuối cùng cũng có rồi.', 'approved', NOW() - INTERVAL 2 HOUR),

(5, 3, 'So sánh rất khách quan. Đang cân nhắc chuyển sang AWS.', 'approved', NOW() - INTERVAL 1 HOUR),
(5, 4, 'Google Cloud có vẻ phù hợp với startup hơn vì free tier tốt.', 'approved', NOW() - INTERVAL 30 MINUTE),

(6, 2, 'M3 chip quá mạnh! Render video nhanh gấp đôi M1.', 'approved', NOW() - INTERVAL 20 MINUTE),
(6, 3, 'Giá hơi cao nhưng xứng đáng với hiệu năng.', 'approved', NOW() - INTERVAL 10 MINUTE);

-- UPDATE VIEWS RANDOMLY 

UPDATE posts SET views = views + FLOOR(RAND() * 500) WHERE id > 0;

-- VERIFICATION QUERIES
-- Kiểm tra số lượng records
SELECT 'Users' as Table_Name, COUNT(*) as Count FROM users
UNION ALL
SELECT 'Categories', COUNT(*) FROM categories
UNION ALL
SELECT 'Posts', COUNT(*) FROM posts
UNION ALL
SELECT 'Comments', COUNT(*) FROM comments;



