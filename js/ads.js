// js/ads.js - JavaScript untuk fitur iklan

const AdsModule = {
    apiBase: '/api/ads',
    
    // Menampilkan daftar iklan
    async loadAdsList(page = 1, categoryId = null, search = '', sort = 'newest') {
        try {
            const params = new URLSearchParams({
                page,
                category_id: categoryId || '',
                search,
                sort
            });
            
            const response = await fetch(`${this.apiBase}/list.php?${params}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderAdsList(data.data);
                this.renderPagination(data.pagination);
            }
        } catch (error) {
            console.error('Error loading ads:', error);
        }
    },

    // Render daftar iklan
    renderAdsList(ads) {
        const container = document.getElementById('ads-container');
        if (!container) return;

        container.innerHTML = ads.map(ad => `
            <div class="ad-card">
                <div class="ad-image">
                    <img src="${ad.image_1 || 'images/placeholder.jpg'}" alt="${ad.title}">
                    <span class="price">Rp ${this.formatPrice(ad.price)}</span>
                </div>
                <div class="ad-info">
                    <h3><a href="ads-detail.php?id=${ad.id}">${ad.title}</a></h3>
                    <p class="location">📍 ${ad.location}</p>
                    <p class="category">${ad.category_name}</p>
                    <div class="ad-footer">
                        <span class="views">👁️ ${ad.views} views</span>
                        <small>${this.timeAgo(ad.created_at)}</small>
                    </div>
                    <div class="ad-seller">
                        <img src="${ad.seller_avatar}" alt="${ad.seller_name}" class="avatar">
                        <span>${ad.seller_name}</span>
                    </div>
                </div>
            </div>
        `).join('');
    },

    // Render pagination
    renderPagination(pagination) {
        const container = document.getElementById('pagination');
        if (!container) return;

        let html = '';
        for (let i = 1; i <= pagination.total_pages; i++) {
            html += `
                <a href="#" class="page-btn ${i === pagination.page ? 'active' : ''}" 
                   onclick="AdsModule.loadAdsList(${i}); return false;">
                    ${i}
                </a>
            `;
        }
        container.innerHTML = html;
    },

    // Load kategori
    async loadCategories() {
        try {
            const response = await fetch(`${this.apiBase}/categories.php`);
            const data = await response.json();
            
            if (data.success) {
                this.renderCategories(data.data);
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    },

    // Render kategori
    renderCategories(categories) {
        const container = document.getElementById('categories-container');
        if (!container) return;

        container.innerHTML = categories.map(cat => `
            <a href="#" class="category-btn" onclick="AdsModule.loadAdsList(1, ${cat.id}); return false;">
                <span class="icon">${cat.icon}</span>
                <span class="name">${cat.name}</span>
            </a>
        `).join('');
    },

    // Load detail iklan
    async loadAdDetail(adId) {
        try {
            const response = await fetch(`${this.apiBase}/detail.php?id=${adId}`);
            const data = await response.json();
            
            if (data.success) {
                this.renderAdDetail(data.data);
            }
        } catch (error) {
            console.error('Error loading ad detail:', error);
        }
    },

    // Render detail iklan
    renderAdDetail(ad) {
        const container = document.getElementById('ad-detail');
        if (!container) return;

        container.innerHTML = `
            <div class="detail-gallery">
                <img id="mainImage" src="${ad.image_1}" alt="${ad.title}">
                <div class="thumbnails">
                    ${ad.image_1 ? `<img src="${ad.image_1}" onclick="document.getElementById('mainImage').src=this.src;">` : ''}
                    ${ad.image_2 ? `<img src="${ad.image_2}" onclick="document.getElementById('mainImage').src=this.src;">` : ''}
                    ${ad.image_3 ? `<img src="${ad.image_3}" onclick="document.getElementById('mainImage').src=this.src;">` : ''}
                </div>
            </div>
            
            <div class="detail-info">
                <h1>${ad.title}</h1>
                <div class="price-section">
                    <h2 class="price">Rp ${this.formatPrice(ad.price)}</h2>
                </div>
                
                <div class="seller-info">
                    <img src="${ad.seller_avatar}" alt="${ad.fullname}" class="seller-avatar">
                    <div>
                        <h3>${ad.fullname}</h3>
                        <p>📍 ${ad.location}</p>
                        <p>📞 ${ad.contact_phone}</p>
                        <p>📧 ${ad.contact_email}</p>
                    </div>
                </div>
                
                <div class="description">
                    <h3>Deskripsi</h3>
                    <p>${ad.description}</p>
                </div>
                
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="this.href='https://wa.me/${ad.contact_phone}'; window.open(this.href);">
                        💬 Chat via WhatsApp
                    </button>
                    <button class="btn btn-secondary" onclick="AdsModule.toggleFavorite(${ad.id})">
                        ♡ Simpan ke Favorit
                    </button>
                </div>
            </div>
        `;
    },

    // Toggle favorit
    async toggleFavorite(adId) {
        try {
            const response = await fetch(`${this.apiBase}/favorite.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `ad_id=${adId}&action=add`
            });
            
            const data = await response.json();
            if (data.success) {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error toggling favorite:', error);
        }
    },

    // Upload form
    handleUploadForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            try {
                const response = await fetch(`${this.apiBase}/create.php`, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('Iklan berhasil dibuat! ID: ' + data.ad_id);
                    form.reset();
                    window.location.href = 'my-ads.php';
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim data');
            }
        });
    },

    // Helper functions
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    },

    timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'Baru saja';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
        return Math.floor(seconds / 86400) + ' hari lalu';
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('ads-container')) {
        AdsModule.loadCategories();
        AdsModule.loadAdsList();
    }
});
