<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Iklan - Ficusbenjamina</title>
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
                <h1>Edit Iklan</h1>
                <p class="subtitle">Perbarui informasi iklan Anda</p>

                <form id="edit-ads-form" method="POST" enctype="multipart/form-data">
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
                        <p class="info">Anda dapat mengganti foto yang sudah ada</p>

                        <div class="form-group">
                            <label for="image_1">Foto Utama</label>
                            <div id="current-image-1" class="current-image"></div>
                            <input type="file" id="image_1" name="image_1" 
                                   accept="image/*" 
                                   onchange="previewImage(this, 'preview-1')">
                            <div id="preview-1" class="image-preview"></div>
                        </div>

                        <div class="form-group">
                            <label for="image_2">Foto Tambahan 1</label>
                            <div id="current-image-2" class="current-image"></div>
                            <input type="file" id="image_2" name="image_2" 
                                   accept="image/*"
                                   onchange="previewImage(this, 'preview-2')">
                            <div id="preview-2" class="image-preview"></div>
                        </div>

                        <div class="form-group">
                            <label for="image_3">Foto Tambahan 2</label>
                            <div id="current-image-3" class="current-image"></div>
                            <input type="file" id="image_3" name="image_3" 
                                   accept="image/*"
                                   onchange="previewImage(this, 'preview-3')">
                            <div id="preview-3" class="image-preview"></div>
                        </div>
                    </fieldset>

                    <!-- Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            ✓ Simpan Perubahan
                        </button>
                        <a href="my-ads.php" class="btn btn-secondary btn-lg">Batal</a>
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

    <style>
        .current-image {
            margin: 10px 0;
        }

        .current-image img {
            max-width: 150px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>

    <script src="js/ads.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const adId = urlParams.get('id');

        if (!adId) {
            document.body.innerHTML = '<p class="error">Iklan tidak ditemukan</p>';
        }

        // Load categories
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

                // Load ad detail
                await loadAdForEdit(adId);
            } catch (error) {
                console.error('Error:', error);
            }
        });

        // Load ad for editing
        async function loadAdForEdit(adId) {
            try {
                const response = await fetch(`/api/ads/detail.php?id=${adId}`);
                const data = await response.json();
                
                if (data.success) {
                    const ad = data.data;
                    document.getElementById('title').value = ad.title;
                    document.getElementById('category_id').value = ad.category_id || '';
                    document.getElementById('description').value = ad.description;
                    document.getElementById('price').value = ad.price;
                    document.getElementById('location').value = ad.location;
                    document.getElementById('contact_phone').value = ad.contact_phone;
                    document.getElementById('contact_email').value = ad.contact_email;

                    // Show current images
                    for (let i = 1; i <= 3; i++) {
                        const imageKey = `image_${i}`;
                        const imageUrl = ad[imageKey];
                        if (imageUrl) {
                            document.getElementById(`current-image-${i}`).innerHTML = 
                                `<img src="${imageUrl}" alt="Current image ${i}">`;
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading ad:', error);
                alert('Terjadi kesalahan saat memuat iklan');
            }
        }

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
        document.getElementById('edit-ads-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(document.getElementById('edit-ads-form'));
            formData.append('ad_id', adId);

            try {
                const response = await fetch('/api/ads/edit.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('Iklan berhasil diperbarui');
                    window.location.href = 'my-ads.php';
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim data');
            }
        });
    </script>
</body>
</html>
