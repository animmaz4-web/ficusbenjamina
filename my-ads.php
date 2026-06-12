<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iklan Saya - Ficusbenjamina</title>
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

    <!-- Header Section -->
    <section class="my-ads-header">
        <div class="container">
            <h1>Iklan Saya</h1>
            <div class="header-actions">
                <a href="ads-create.php" class="btn btn-primary btn-lg">+ Buat Iklan Baru</a>
            </div>
        </div>
    </section>

    <!-- Tabs Section -->
    <section class="tabs-section">
        <div class="container">
            <div class="tabs">
                <button class="tab-btn active" onclick="filterAds('all')">Semua (0)</button>
                <button class="tab-btn" onclick="filterAds('draft')">Draft (0)</button>
                <button class="tab-btn" onclick="filterAds('published')">Aktif (0)</button>
                <button class="tab-btn" onclick="filterAds('expired')">Expired (0)</button>
            </div>
        </div>
    </section>

    <!-- My Ads List -->
    <section class="my-ads-section">
        <div class="container">
            <div id="my-ads-container" class="my-ads-table">
                <!-- Tabel iklan akan dimuat oleh JavaScript -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Ficusbenjamina. All rights reserved.</p>
        </div>
    </footer>

    <style>
        .my-ads-header {
            background: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%);
            color: white;
            padding: 40px 0;
            margin-top: 70px;
        }

        .my-ads-header h1 {
            font-size: 2.5em;
            margin: 0 0 20px 0;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .tabs-section {
            background: white;
            padding: 20px 0;
            border-bottom: 1px solid #ddd;
            margin-bottom: 30px;
        }

        .tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 20px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: bold;
        }

        .tab-btn:hover {
            border-color: #4CAF50;
        }

        .tab-btn.active {
            background: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .my-ads-section {
            padding: 20px 0;
            min-height: calc(100vh - 400px);
        }

        .my-ads-table {
            display: grid;
            gap: 20px;
        }

        .ad-row {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 20px;
            align-items: center;
        }

        .ad-thumbnail {
            width: 120px;
            height: 120px;
            border-radius: 5px;
            object-fit: cover;
            background: #f0f0f0;
        }

        .ad-info-col h3 {
            margin: 0 0 10px 0;
        }

        .ad-info-col p {
            margin: 5px 0;
            color: #666;
        }

        .ad-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .status-draft {
            background: #ffebee;
            color: #c62828;
        }

        .status-published {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-expired {
            background: #fff3e0;
            color: #e65100;
        }

        .ad-stats {
            display: flex;
            gap: 15px;
            margin: 10px 0;
            font-size: 0.9em;
        }

        .ad-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .ad-action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .btn-view {
            background: #2196F3;
            color: white;
        }

        .btn-view:hover {
            background: #1976D2;
        }

        .btn-edit {
            background: #FFC107;
            color: white;
        }

        .btn-edit:hover {
            background: #FFA000;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background: #d32f2f;
        }

        .btn-renew {
            background: #4CAF50;
            color: white;
        }

        .btn-renew:hover {
            background: #388E3C;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state h2 {
            margin-bottom: 15px;
        }

        .empty-state .btn {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .ad-row {
                grid-template-columns: 1fr;
            }

            .ad-thumbnail {
                width: 100%;
                height: auto;
                min-height: 150px;
            }

            .ad-actions {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .ad-action-btn {
                flex: 1;
                min-width: 100px;
            }
        }
    </style>

    <script src="js/ads.js"></script>
    <script>
        let currentStatus = 'all';

        // Load user's ads on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadMyAds('all');
        });

        // Filter ads by status
        async function filterAds(status) {
            currentStatus = status;
            
            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            await loadMyAds(status);
        }

        // Load user's ads
        async function loadMyAds(status = 'all') {
            try {
                const statusParam = status === 'all' ? '' : status;
                const response = await fetch(`/api/ads/my-ads.php?status=${statusParam}`);
                const data = await response.json();

                if (data.success) {
                    renderMyAds(data.data);
                } else {
                    document.getElementById('my-ads-container').innerHTML = 
                        '<div class="empty-state"><h2>Anda belum login</h2><p>Silakan login terlebih dahulu</p><a href="login.php" class="btn btn-primary">Login</a></div>';
                }
            } catch (error) {
                console.error('Error loading ads:', error);
                document.getElementById('my-ads-container').innerHTML = 
                    '<p class="error">Terjadi kesalahan saat memuat iklan</p>';
            }
        }

        // Render ads list
        function renderMyAds(ads) {
            const container = document.getElementById('my-ads-container');

            if (ads.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <h2>Belum ada iklan</h2>
                        <p>Buat iklan pertama Anda sekarang</p>
                        <a href="ads-create.php" class="btn btn-primary btn-lg">+ Buat Iklan Baru</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = ads.map(ad => `
                <div class="ad-row">
                    <img src="${ad.image_1 || 'images/placeholder.jpg'}" alt="${ad.title}" class="ad-thumbnail">
                    
                    <div class="ad-info-col">
                        <h3>${ad.title}</h3>
                        <p><strong>Rp ${AdsModule.formatPrice(ad.price)}</strong></p>
                        <p class="ad-category">${ad.category_name}</p>
                        <div class="ad-stats">
                            <span>👁️ ${ad.views} views</span>
                            <span class="ad-status status-${ad.status}">${ad.status.toUpperCase()}</span>
                        </div>
                        <small>Dibuat: ${new Date(ad.created_at).toLocaleDateString('id-ID')}</small>
                    </div>

                    <div class="ad-actions">
                        <a href="ads-detail.php?id=${ad.id}" class="ad-action-btn btn-view">👁️ Lihat</a>
                        <button class="ad-action-btn btn-edit" onclick="editAd(${ad.id})">✏️ Edit</button>
                        <button class="ad-action-btn btn-delete" onclick="deleteAd(${ad.id})">🗑️ Hapus</button>
                        ${ad.status === 'expired' ? `<button class="ad-action-btn btn-renew" onclick="renewAd(${ad.id})">🔄 Perbarui</button>` : ''}
                    </div>
                </div>
            `).join('');
        }

        // Edit ad
        function editAd(adId) {
            window.location.href = `ads-edit.php?id=${adId}`;
        }

        // Delete ad
        async function deleteAd(adId) {
            if (!confirm('Apakah Anda yakin ingin menghapus iklan ini?')) {
                return;
            }

            try {
                const response = await fetch('/api/ads/delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `ad_id=${adId}`
                });

                const data = await response.json();
                if (data.success) {
                    alert('Iklan berhasil dihapus');
                    loadMyAds(currentStatus);
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus iklan');
            }
        }

        // Renew ad
        async function renewAd(adId) {
            if (!confirm('Perbarui iklan ini untuk menampilkannya lagi?')) {
                return;
            }

            try {
                const response = await fetch('/api/ads/edit.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `ad_id=${adId}&status=published`
                });

                const data = await response.json();
                if (data.success) {
                    alert('Iklan berhasil diperbarui');
                    loadMyAds(currentStatus);
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui iklan');
            }
        }
    </script>
</body>
</html>
