<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Iklan Baru - Ficusbenjamina</title>
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

    <!-- Main Content -->
    <section class="form-section">
        <div class="container">
            <div class="form-wrapper">
                <h1>Buat Iklan Baru</h1>
                <p class="subtitle">Isi form di bawah untuk membuat iklan gratis Anda</p>

                <form id="create-ads-form" method="POST" enctype="multipart/form-data">
                    <!-- Basic Information -->
                    <fieldset>
                        <legend>Informasi Dasar</legend>
                        
                        <div class="form-group">
                            <label for="title">Judul Iklan *</label>
                            <input type="text" id="title" name="title" required 
                                   placeholder="Contoh: Jual Sepeda Motor Bekas 2020"
                                   maxlength="255">
                            <small>Maksimal 255 karakter</small>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Kategori *</label>
                            <select id="category_id" name="category_id" required>
                                <option value="">-- Pilih Kategori --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi *</label>
                            <textarea id="description" name="description" required 
                                      placeholder="Jelaskan detail produk/jasa Anda..."
                                      rows="6"></textarea>
                        </div>
                    </fieldset>

                    <!-- Pricing & Location -->
                    <fieldset>
                        <legend>Harga & Lokasi</legend>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Harga (Rp) *</label>
                                <input type="number" id="price" name="price" required 
                                       placeholder="0" min="0" step="1000">
                                <small>Masukkan 0 untuk gratis/tawar-menawar</small>
                            </div>

                            <div class="form-group">
                                <label for="location">Lokasi *</label>
                                <input type="text" id="location" name="location" required 
                                       placeholder="Contoh: Jakarta Selatan, DKI Jakarta"
                                       maxlength="100">
                            </div>
                        </div>
                    </fieldset>

                    <!-- Contact Information -->
                    <fieldset>
                        <legend>Informasi Kontak</legend>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_phone">Nomor Telepon *</label>
                                <input type="tel" id="contact_phone" name="contact_phone" required 
                                       placeholder="628xxxxxxxxx"
                                       pattern="[0-9+\-\s()]*"
                                       maxlength="15">
                            </div>

                            <div class="form-group">
                                <label for="contact_email">Email *</label>
                                <input type="email" id="contact_email" name="contact_email" required 
                                       placeholder="email@example.com">
                            </div>
                        </div>
                    </fieldset>

                    <!-- Images -->
                    <fieldset>
                        <legend>Foto Produk</legend>
                        <p class="info">Upload hingga 3 foto untuk membuat iklan Anda lebih menarik</p>

                        <div class="form-group">
                            <label for="image_1">Foto Utama *</label>
                            <input type="file" id="image_1" name="image_1" required 
                                   accept="image/*" 
                                   onchange="previewImage(this, 'preview-1')">
                            <div id="preview-1" class="image-preview"></div>
                        </div>

                        <div class="form-group">
                            <label for="image_2">Foto Tambahan 1</label>
                            <input type="file" id="image_2" name="image_2" 
                                   accept="image/*"
                                   onchange="previewImage(this, 'preview-2')">
                            <div id="preview-2" class="image-preview"></div>
                        </div>

                        <div class="form-group">
                            <label for="image_3">Foto Tambahan 2</label>
                            <input type="file" id="image_3" name="image_3" 
                                   accept="image/*"
                                   onchange="previewImage(this, 'preview-3')">
                            <div id="preview-3" class="image-preview"></div>
                        </div>
                    </fieldset>

                    <!-- Status -->
                    <fieldset>
                        <legend>Status Iklan</legend>

                        <div class="form-group">
                            <label>
                                <input type="radio" name="status" value="draft" checked>
                                Simpan Sebagai Draft (Bisa diedit nanti)
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="radio" name="status" value="published">
                                Publikasikan Sekarang (Iklan langsung aktif)
                            </label>
                        </div>
                    </fieldset>

                    <!-- Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            ✓ Buat Iklan
                        </button>
                        <a href="ads.php" class="btn btn-secondary btn-lg">Batal</a>
                    </div>
                </form>
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
        // Load categories on page load
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch('/api/ads/categories.php');
                const data = await response.json();
                if (data.success) {
                    const select = document.getElementById('category_id');
                    data.data.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.id;
                        option.textContent = cat.icon + ' ' + cat.name;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        });

        // Image preview
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.getElementById(previewId);
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form submission
        document.getElementById('create-ads-form').addEventListener('submit', (e) => {
            AdsModule.handleUploadForm('create-ads-form');
        });
    </script>
</body>
</html>
