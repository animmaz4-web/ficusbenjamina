-- ============================================
-- DATABASE BLOGGER2026
-- ============================================

-- Buat Database
CREATE DATABASE IF NOT EXISTS blogger2026;
USE blogger2026;

-- ============================================
-- TABEL USERS (Untuk Login & Register)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    avatar VARCHAR(255),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    INDEX idx_email (email),
    INDEX idx_username (username)
);

-- ============================================
-- TABEL RADIO STATIONS (Data Stasiun Radio)
-- ============================================
CREATE TABLE IF NOT EXISTS radio_stations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    logo VARCHAR(255),
    stream_url VARCHAR(255),
    frequency VARCHAR(20),
    city VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- ============================================
-- TABEL BLOG CATEGORIES (Kategori Blog)
-- ============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABEL BLOG POSTS (Artikel Blog)
-- ============================================
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    content LONGTEXT NOT NULL,
    excerpt VARCHAR(500),
    featured_image VARCHAR(255),
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_category_id (category_id),
    INDEX idx_status (status),
    FULLTEXT INDEX ft_title_content (title, content)
);

-- ============================================
-- TABEL COMMENTS (Komentar Blog)
-- ============================================
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_post_id (post_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
);

-- ============================================
-- TABEL SOCIAL MEDIA LINKS
-- ============================================
CREATE TABLE IF NOT EXISTS social_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- ============================================
-- INSERT DATA SAMPLE - STASIUN RADIO
-- ============================================
INSERT INTO radio_stations (name, description, frequency, city, status) VALUES
('Sonora FM', 'Dengarkan musik terbaik dari Sonora FM', '99.0 FM', 'Jakarta', 'active'),
('Bestari Tanjungpinang', 'Bestari Tanjungpinang - Konten Berkualitas', '102.5 FM', 'Tanjungpinang', 'active'),
('Prambors Jakarta', 'Prambors Jakarta - Top Hits', '102.2 FM', 'Jakarta', 'active');

-- ============================================
-- INSERT DATA SAMPLE - KATEGORI BLOG
-- ============================================
INSERT INTO categories (name, slug, description, icon) VALUES
('Blogging Tips', 'blogging-tips', 'Tips dan trik untuk memulai blog', '📝'),
('Tech Reviews', 'tech-reviews', 'Review dan ulasan teknologi terbaru', '💻'),
('Personal Development', 'personal-development', 'Artikel tentang pengembangan diri', '🚀');

-- ============================================
-- INSERT DATA SAMPLE - SOCIAL MEDIA
-- ============================================
INSERT INTO social_media (platform, url, icon, status) VALUES
('WhatsApp', 'https://wa.me/62', '💬', 'active'),
('Facebook', 'https://facebook.com', '👍', 'active'),
('Instagram', 'https://instagram.com', '📷', 'active');

-- ============================================
-- CREATE VIEWS
-- ============================================

-- View untuk menampilkan blog posts dengan informasi user dan category
CREATE OR REPLACE VIEW v_blog_posts_detail AS
SELECT 
    bp.id,
    bp.title,
    bp.slug,
    bp.excerpt,
    bp.content,
    bp.featured_image,
    bp.views,
    bp.created_at,
    bp.published_at,
    bp.status,
    u.id as user_id,
    u.fullname as author,
    u.avatar,
    c.id as category_id,
    c.name as category_name,
    c.slug as category_slug
FROM blog_posts bp
JOIN users u ON bp.user_id = u.id
LEFT JOIN categories c ON bp.category_id = c.id
WHERE bp.status = 'published'
ORDER BY bp.published_at DESC;
