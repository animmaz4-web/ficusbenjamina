-- ============================================
-- EXTENSION DATABASE UNTUK FITUR IKLAN
-- ============================================

USE blogger2026;

-- ============================================
-- TABEL ADS (Data Iklan)
-- ============================================
CREATE TABLE IF NOT EXISTS ads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description LONGTEXT NOT NULL,
    category_id INT,
    price DECIMAL(12, 2) DEFAULT 0,
    contact_phone VARCHAR(15),
    contact_email VARCHAR(100),
    location VARCHAR(100),
    image_1 VARCHAR(255),
    image_2 VARCHAR(255),
    image_3 VARCHAR(255),
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    status ENUM('draft', 'published', 'expired', 'sold', 'archived') DEFAULT 'draft',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES ad_categories(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_category_id (category_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    FULLTEXT INDEX ft_title_description (title, description)
);

-- ============================================
-- TABEL AD_CATEGORIES (Kategori Iklan)
-- ============================================
CREATE TABLE IF NOT EXISTS ad_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- ============================================
-- TABEL AD_COMMENTS (Komentar/Pertanyaan pada Iklan)
-- ============================================
CREATE TABLE IF NOT EXISTS ad_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_ad_id (ad_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
);

-- ============================================
-- TABEL AD_FAVORITES (Iklan Favorit User)
-- ============================================
CREATE TABLE IF NOT EXISTS ad_favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ad_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_ad (user_id, ad_id),
    INDEX idx_user_id (user_id),
    INDEX idx_ad_id (ad_id)
);

-- ============================================
-- INSERT DATA SAMPLE - KATEGORI IKLAN
-- ============================================
INSERT INTO ad_categories (name, slug, description, icon, status) VALUES
('Elektronik', 'elektronik', 'Iklan untuk produk elektronik', '📱', 'active'),
('Kendaraan', 'kendaraan', 'Iklan untuk kendaraan dan otomotif', '🚗', 'active'),
('Properti', 'properti', 'Iklan untuk rumah, tanah, dan ruko', '🏠', 'active'),
('Fashion', 'fashion', 'Iklan untuk pakaian dan aksesoris', '👗', 'active'),
('Furniture', 'furniture', 'Iklan untuk furniture dan peralatan rumah', '🛋️', 'active'),
('Jasa', 'jasa', 'Iklan untuk jasa dan layanan', '🔧', 'active'),
('Kesehatan', 'kesehatan', 'Iklan untuk produk kesehatan', '💊', 'active'),
('Hobi & Olahraga', 'hobi-olahraga', 'Iklan untuk hobi dan perlengkapan olahraga', '⚽', 'active');

-- ============================================
-- CREATE VIEWS UNTUK IKLAN
-- ============================================

-- View untuk menampilkan iklan dengan informasi user dan category
CREATE OR REPLACE VIEW v_ads_detail AS
SELECT 
    a.id,
    a.title,
    a.description,
    a.price,
    a.contact_phone,
    a.contact_email,
    a.location,
    a.image_1,
    a.image_2,
    a.image_3,
    a.views,
    a.created_at,
    a.published_at,
    a.expires_at,
    a.status,
    u.id as user_id,
    u.fullname as seller_name,
    u.avatar as seller_avatar,
    u.phone as seller_phone,
    ac.id as category_id,
    ac.name as category_name,
    ac.slug as category_slug,
    ac.icon as category_icon
FROM ads a
JOIN users u ON a.user_id = u.id
LEFT JOIN ad_categories ac ON a.category_id = ac.id
WHERE a.status = 'published' AND (a.expires_at IS NULL OR a.expires_at > NOW())
ORDER BY a.published_at DESC;

-- View untuk menampilkan statistik iklan per user
CREATE OR REPLACE VIEW v_user_ads_stats AS
SELECT 
    u.id as user_id,
    u.fullname,
    COUNT(a.id) as total_ads,
    SUM(CASE WHEN a.status = 'published' THEN 1 ELSE 0 END) as published_ads,
    SUM(CASE WHEN a.status = 'draft' THEN 1 ELSE 0 END) as draft_ads,
    SUM(a.views) as total_views
FROM users u
LEFT JOIN ads a ON u.id = a.user_id
GROUP BY u.id, u.fullname;
