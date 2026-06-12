<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasang Iklan Gratis - Ficusbenjamina</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/ads.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">🌿 Ficusbenjamina</a>
            </div>
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="radio.php">Radio</a></li>
                <li><a href="ads.php" class="highlight">📢 Pasang Iklan Gratis</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Pasang Iklan Gratis</h1>
            <p>Jual, beli, dan cari produk atau jasa dengan mudah</p>
            <a href="ads-create.php" class="btn btn-primary btn-lg">+ Buat Iklan Baru</a>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filters">
        <div class="container">
            <div class="filter-group">
                <input type="text" id="search-input" placeholder="Cari iklan..." class="search-box">
                <button onclick="AdsModule.loadAdsList(1, null, document.getElementById('search-input').value);" class="btn btn-secondary">
                    🔍 Cari
                </button>
            </div>
            
            <div class="sort-group">
                <label>Urutkan:</label>
                <select id="sort-select" onchange="AdsModule.loadAdsList(1, null, '', this.value);">
                    <option value="newest">Terbaru</option>
                    <option value="popular">Paling Dilihat</option>
                    <option value="price_low">Harga Terendah</option>
                    <option value="price_high">Harga Tertinggi</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <h2>Kategori</h2>
            <div id="categories-container" class="categories-grid">
                <!-- Kategori akan dimuat oleh JavaScript -->
            </div>
        </div>
    </section>

    <!-- Ads List Section -->
    <section class="ads-section">
        <div class="container">
            <div id="ads-container" class="ads-grid">
                <!-- Daftar iklan akan dimuat oleh JavaScript -->
            </div>
        </div>
    </section>

    <!-- Pagination -->
    <section class="pagination-section">
        <div class="container">
            <div id="pagination" class="pagination">
                <!-- Pagination akan dimuat oleh JavaScript -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Ficusbenjamina. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/ads.js"></script>
</body>
</html>
