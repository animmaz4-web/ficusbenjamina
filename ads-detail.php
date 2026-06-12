<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Iklan - Ficusbenjamina</title>
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

    <!-- Detail Section -->
    <section class="detail-section">
        <div class="container">
            <div id="ad-detail" class="ad-detail-wrapper">
                <!-- Detail akan dimuat oleh JavaScript -->
            </div>
        </div>
    </section>

    <!-- Comments Section -->
    <section class="comments-section">
        <div class="container">
            <h2>Pertanyaan & Komentar</h2>
            
            <div class="comment-form">
                <h3>Ajukan Pertanyaan</h3>
                <form id="comment-form" onsubmit="submitComment(event)">
                    <div class="form-group">
                        <textarea id="comment-text" name="comment" placeholder="Tulis pertanyaan Anda di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>

            <div id="comments-list" class="comments-list">
                <!-- Komentar akan dimuat oleh JavaScript -->
            </div>
        </div>
    </section>

    <!-- Similar Ads Section -->
    <section class="similar-ads">
        <div class="container">
            <h2>Iklan Serupa</h2>
            <div id="similar-ads-container" class="ads-grid">
                <!-- Iklan serupa akan dimuat oleh JavaScript -->
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
    <script>
        // Get ad ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const adId = urlParams.get('id');

        if (!adId) {
            document.getElementById('ad-detail').innerHTML = '<p class="error">Iklan tidak ditemukan</p>';
        } else {
            // Load ad detail
            AdsModule.loadAdDetail(adId);
        }

        // Submit comment
        async function submitComment(e) {
            e.preventDefault();
            const comment = document.getElementById('comment-text').value;
            
            try {
                const response = await fetch('/api/ads/comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `ad_id=${adId}&comment=${encodeURIComponent(comment)}`
                });
                
                const data = await response.json();
                if (data.success) {
                    alert(data.message);
                    document.getElementById('comment-form').reset();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim komentar');
            }
        }
    </script>
</body>
</html>
