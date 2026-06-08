const revealElements = document.querySelectorAll('.reveal');

if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15 }
    );

    revealElements.forEach((element) => revealObserver.observe(element));
} else {
    revealElements.forEach((element) => element.classList.add('is-visible'));
}

const filterButtons = document.querySelectorAll('[data-filter]');
const articleRows = document.querySelectorAll('[data-tags]');
const searchInput = document.querySelector('[data-search]');
let activeFilter = 'all';

const applyFilters = () => {
    const query = searchInput ? searchInput.value.trim().toLowerCase() : '';

    articleRows.forEach((row) => {
        const tags = row.dataset.tags || '';
        const matchesFilter = activeFilter === 'all' || tags.includes(activeFilter);
        const matchesSearch = query.length === 0 || row.textContent.toLowerCase().includes(query);
        row.style.display = matchesFilter && matchesSearch ? '' : 'none';
    });
};

filterButtons.forEach((button) => {
    button.addEventListener('click', () => {
        activeFilter = button.dataset.filter || 'all';
        filterButtons.forEach((item) => item.classList.remove('is-active'));
        button.classList.add('is-active');
        applyFilters();
    });
});

if (searchInput) {
    searchInput.addEventListener('input', applyFilters);
}

const passwordToggles = document.querySelectorAll('[data-password-toggle]');

passwordToggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
        const targetId = toggle.dataset.target;
        const target = targetId ? document.getElementById(targetId) : null;

        if (!target) {
            return;
        }

        const isPassword = target.type === 'password';
        target.type = isPassword ? 'text' : 'password';
        toggle.textContent = isPassword ? 'Sembunyi' : 'Lihat';
    });
});

const strengthInputs = document.querySelectorAll('[data-strength-input]');

const strengthLabel = (score) => {
    if (score >= 4) {
        return 'Sangat kuat';
    }

    if (score === 3) {
        return 'Kuat';
    }

    if (score === 2) {
        return 'Sedang';
    }

    return 'Lemah';
};

strengthInputs.forEach((input) => {
    const meter = document.querySelector(`[data-strength-meter="${input.id}"] .strength-bar`);
    const label = document.querySelector(`[data-strength-meter="${input.id}"] .strength-label`);

    if (!meter || !label) {
        return;
    }

    const updateStrength = () => {
        const value = input.value;
        let score = 0;

        if (value.length >= 8) score += 1;
        if (/[A-Z]/.test(value)) score += 1;
        if (/[0-9]/.test(value)) score += 1;
        if (/[^A-Za-z0-9]/.test(value)) score += 1;

        const percent = Math.min((score / 4) * 100, 100);
        meter.style.setProperty('--strength', `${percent}%`);
        label.textContent = `Kekuatan password: ${strengthLabel(score)}`;
    };

    updateStrength();
    input.addEventListener('input', updateStrength);
});

const dummyLoginForm = document.querySelector('[data-dummy-login]');

if (dummyLoginForm) {
    const feedback = dummyLoginForm.querySelector('[data-login-feedback]');
    const submitButton = dummyLoginForm.querySelector('button[type="submit"]');
    const emailInput = dummyLoginForm.querySelector('input[type="email"]');
    const passwordInput = dummyLoginForm.querySelector('#login-password');
    const roleToggle = dummyLoginForm.querySelector('[data-role-toggle]');
    const roleButtons = roleToggle ? roleToggle.querySelectorAll('[data-role]') : [];
    const credentials = {
        user: { email: 'user@recorda.com', password: 'recorda123' },
        admin: { email: 'admin@recorda.com', password: 'recorda123' },
    };

    const setFeedback = (message, isSuccess) => {
        if (!feedback) {
            return;
        }

        feedback.textContent = message;
        feedback.classList.toggle('is-success', Boolean(isSuccess));
    };

    dummyLoginForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const email = emailInput ? emailInput.value.trim().toLowerCase() : '';
        const password = passwordInput ? passwordInput.value.trim() : '';

        if (!email || !password) {
            setFeedback('Email dan password harus diisi.', false);
            return;
        }

        const selectedRole = dummyLoginForm.dataset.loginRole || 'user';
        const resolvedRole = email === credentials.admin.email
            ? 'admin'
            : (email === credentials.user.email ? 'user' : selectedRole);
        const roleLabel = resolvedRole === 'admin' ? 'admin' : 'user';
        const roleCredential = credentials[resolvedRole] || credentials.user;
        const managedUser = typeof getManagedUserByEmail === 'function' ? getManagedUserByEmail(email) : null;

        if (managedUser && managedUser.status === 'nonaktif') {
            setFeedback('Akun ini sedang nonaktif.', false);
            return;
        }

        if (email !== roleCredential.email || password !== roleCredential.password) {
            setFeedback(`Email atau password ${roleLabel} tidak sesuai.`, false);
            return;
        }
        setFeedback(`Login dummy ${roleLabel} berhasil, mengalihkan...`, true);

        if (submitButton) {
            submitButton.disabled = true;
        }

        // Save auth state first
        authState.setUser(email, resolvedRole);

        const userRedirect = dummyLoginForm.dataset.successRedirect || 'home';
        const adminRedirect = dummyLoginForm.dataset.adminRedirect || 'dashboard';
        const redirectTarget = resolvedRole === 'admin' ? adminRedirect : userRedirect;

        window.setTimeout(() => {
            // Ensure path has leading slash
            const path = redirectTarget.startsWith('/') ? redirectTarget : '/' + redirectTarget;
            window.location.href = path;
        }, 900);
    });

    roleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const role = button.dataset.role || 'user';
            dummyLoginForm.dataset.loginRole = role;
            roleButtons.forEach((item) => item.classList.remove('is-active'));
            button.classList.add('is-active');
        });
    });
}

const qtyWrappers = document.querySelectorAll('[data-qty]');

qtyWrappers.forEach((wrapper) => {
    const valueElement = wrapper.querySelector('[data-qty-value]');
    const buttons = wrapper.querySelectorAll('[data-qty-action]');

    if (!valueElement) {
        return;
    }

    let currentValue = parseInt(valueElement.textContent, 10) || 1;

    const updateValue = (nextValue) => {
        currentValue = Math.max(1, nextValue);
        valueElement.textContent = String(currentValue);
    };

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const action = button.dataset.qtyAction;
            const nextValue = action === 'plus' ? currentValue + 1 : currentValue - 1;
            updateValue(nextValue);
        });
    });
});

const paymentOptions = document.querySelectorAll('[data-payment-option]');
const paymentLabel = document.querySelector('[data-payment-label]');

paymentOptions.forEach((option) => {
    option.addEventListener('click', () => {
        paymentOptions.forEach((item) => item.classList.remove('is-active'));
        option.classList.add('is-active');

        if (paymentLabel && option.dataset.paymentName) {
            paymentLabel.textContent = option.dataset.paymentName;
        }
    });
});

// Register form handler
const registerForm = document.querySelector('[data-dummy-register]');

if (registerForm) {
    const feedback = registerForm.querySelector('[data-register-feedback]');
    const submitButton = registerForm.querySelector('button[type="submit"]');
    const firstName = registerForm.querySelector('[data-first-name]');
    const lastName = registerForm.querySelector('[data-last-name]');
    const email = registerForm.querySelector('[data-register-email]');
    const password = registerForm.querySelector('[data-register-password]');
    const confirmPassword = registerForm.querySelector('[data-confirm-password]');
    const termsCheckbox = registerForm.querySelector('[data-terms-checkbox]');

    const setFeedback = (message, isSuccess) => {
        if (!feedback) return;
        feedback.textContent = message;
        feedback.classList.toggle('is-success', Boolean(isSuccess));
    };

    registerForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const firstNameVal = firstName ? firstName.value.trim() : '';
        const lastNameVal = lastName ? lastName.value.trim() : '';
        const emailVal = email ? email.value.trim().toLowerCase() : '';
        const passwordVal = password ? password.value.trim() : '';
        const confirmPasswordVal = confirmPassword ? confirmPassword.value.trim() : '';
        const termsChecked = termsCheckbox ? termsCheckbox.checked : false;

        if (!firstNameVal || !lastNameVal) {
            setFeedback('Nama depan dan belakang harus diisi.', false);
            return;
        }

        if (!emailVal) {
            setFeedback('Email harus diisi.', false);
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
            setFeedback('Format email tidak valid.', false);
            return;
        }

        if (!passwordVal || passwordVal.length < 8) {
            setFeedback('Password minimal 8 karakter.', false);
            return;
        }

        if (passwordVal !== confirmPasswordVal) {
            setFeedback('Password dan konfirmasi password tidak cocok.', false);
            return;
        }

        if (!termsChecked) {
            setFeedback('Anda harus menyetujui Syarat dan Ketentuan.', false);
            return;
        }

        setFeedback('Pendaftaran berhasil! Mengalihkan ke login...', true);
        if (submitButton) submitButton.disabled = true;

        window.setTimeout(() => {
            window.location.href = new URL('login', window.location.href).toString();
        }, 1200);
    });
}

// Forgot password form handler
const forgotForm = document.querySelector('[data-dummy-forgot]');

if (forgotForm) {
    const feedback = forgotForm.querySelector('[data-forgot-feedback]');
    const submitButton = forgotForm.querySelector('button[type="submit"]');
    const emailInput = forgotForm.querySelector('[data-forgot-email]');

    const setFeedback = (message, isSuccess) => {
        if (!feedback) return;
        feedback.textContent = message;
        feedback.classList.toggle('is-success', Boolean(isSuccess));
    };

    forgotForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const emailVal = emailInput ? emailInput.value.trim().toLowerCase() : '';

        if (!emailVal) {
            setFeedback('Email harus diisi.', false);
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
            setFeedback('Format email tidak valid.', false);
            return;
        }

        setFeedback('Link reset password telah dikirim! Cek email Anda.', true);
        if (submitButton) submitButton.disabled = true;

        window.setTimeout(() => {
            emailInput.value = '';
            if (submitButton) submitButton.disabled = false;
            setFeedback('');
        }, 3000);
    });
}

// Product gallery thumbnail click
const productThumbs = document.querySelectorAll('[data-product-thumb]');
const productMain = document.querySelector('[data-product-main]');

productThumbs.forEach((thumb) => {
    thumb.addEventListener('click', () => {
        const thumbClass = thumb.dataset.productThumb;
        if (productMain) {
            // Remove old cover class
            productMain.className = productMain.className.replace(/cover-[a-d]/i, '');
            // Add new cover class
            productMain.classList.add(thumbClass);
        }
        // Update thumb selection style
        productThumbs.forEach((t) => t.classList.remove('is-active'));
        thumb.classList.add('is-active');
    });
});

// Mark first thumb as active
if (productThumbs.length > 0) {
    productThumbs[0].classList.add('is-active');
}

// Product gallery thumbnail click (foto asli/galeri) — ganti foto utama
const photoThumbs = document.querySelectorAll('[data-product-photo]');
if (photoThumbs.length > 0 && productMain) {
    photoThumbs.forEach((thumb) => {
        thumb.addEventListener('click', () => {
            productMain.style.backgroundImage = `url('${thumb.dataset.productPhoto}')`;
            productMain.style.backgroundSize = 'cover';
            productMain.style.backgroundPosition = 'center';
            photoThumbs.forEach((t) => t.classList.remove('is-active'));
            thumb.classList.add('is-active');
        });
    });
    photoThumbs[0].classList.add('is-active');
}

// Add to cart button
const addToCartButtons = document.querySelectorAll('[data-add-to-cart]');

addToCartButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const productName = button.dataset.productName || 'Product';
        const productPrice = button.dataset.productPrice || '0';
        const productSlug = button.dataset.productSlug || '';
        const productImage = button.dataset.productImage || '';

        // Simulate adding to cart (store in localStorage)
        let cart = JSON.parse(localStorage.getItem('recorda_cart') || '[]');
        const normalizedPrice = parseInt(productPrice, 10) || 0;
        cart.push({
            id: Date.now(),
            slug: productSlug,
            name: productName,
            price: normalizedPrice,
            image: productImage,
            qty: 1,
            quantity: 1
        });
        localStorage.setItem('recorda_cart', JSON.stringify(cart));
        
        // Show feedback
        const originalText = button.textContent;
        button.textContent = '✓ Ditambahkan!';
        button.disabled = true;
        
        window.setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
        }, 2000);
    });
});

// NOTE: checkout handling is implemented inline in the checkout Blade template
// to avoid duplicate handlers interfering with order creation and status.

// Admin search & pagination
const adminSearchInputs = document.querySelectorAll('[data-admin-search]');

adminSearchInputs.forEach((searchInput) => {
    const table = document.querySelector('[data-admin-table]');
    if (!table) return;

    const rows = table.querySelectorAll('[data-table-row]');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim().toLowerCase();

        rows.forEach((row) => {
            const searchText = row.querySelector('[data-search-text]');
            if (!searchText) return;

            const matches = searchText.textContent.toLowerCase().includes(query);
            row.style.display = matches ? '' : 'none';
        });
    });
});

// Admin pagination
const paginationContainers = document.querySelectorAll('[data-pagination]');

paginationContainers.forEach((paginationContainer) => {
    const pageButtons = paginationContainer.querySelectorAll('[data-page]');
    const prevButton = paginationContainer.querySelector('[data-page-prev]');
    const nextButton = paginationContainer.querySelector('[data-page-next]');

    pageButtons.forEach((button) => {
        button.addEventListener('click', () => {
            pageButtons.forEach((b) => b.classList.remove('is-active'));
            button.classList.add('is-active');
        });
    });

    if (prevButton) {
        prevButton.addEventListener('click', () => {
            const active = paginationContainer.querySelector('[data-page].is-active');
            const activeNum = parseInt(active.dataset.page, 10);
            if (activeNum > 1) {
                pageButtons[activeNum - 2].click();
            }
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            const active = paginationContainer.querySelector('[data-page].is-active');
            const activeNum = parseInt(active.dataset.page, 10);
            if (activeNum < pageButtons.length) {
                pageButtons[activeNum].click();
            }
        });
    }
});

const TRANSACTION_STATUS_SEQUENCE = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];

function getNextTransactionStatus(status) {
    const currentIndex = TRANSACTION_STATUS_SEQUENCE.indexOf(status);
    if (currentIndex === -1 || currentIndex === TRANSACTION_STATUS_SEQUENCE.length - 1) {
        return TRANSACTION_STATUS_SEQUENCE[0];
    }

    return TRANSACTION_STATUS_SEQUENCE[currentIndex + 1];
}

function formatTransactionStatus(status) {
    const map = {
        pending: 'Menunggu',
        processing: 'Diproses',
        shipped: 'Dikirim',
        completed: 'Selesai',
        cancelled: 'Dibatalkan',
    };

    return map[status] || 'Menunggu';
}

const adminModalState = {
    onSubmit: null,
};

function getAdminModalElements() {
    return {
        overlay: document.querySelector('[data-admin-modal]'),
        eyebrow: document.querySelector('[data-admin-modal-eyebrow]'),
        title: document.querySelector('[data-admin-modal-title]'),
        body: document.querySelector('[data-admin-modal-body]'),
        submit: document.querySelector('[data-admin-modal-submit]'),
        close: document.querySelector('[data-admin-modal-close]'),
        close2: document.querySelector('[data-admin-modal-close-2]'),
    };
}

function closeAdminModal() {
    const elements = getAdminModalElements();
    if (!elements.overlay) {
        return;
    }

    elements.overlay.style.display = 'none';
    adminModalState.onSubmit = null;
}

function openAdminModal({ eyebrow = 'Admin Panel', title = 'Modal', bodyHTML = '', submitLabel = 'Simpan', hideSubmit = false, onSubmit = null }) {
    const elements = getAdminModalElements();
    if (!elements.overlay || !elements.body || !elements.submit) {
        return;
    }

    if (elements.eyebrow) elements.eyebrow.textContent = eyebrow;
    if (elements.title) elements.title.textContent = title;
    elements.body.innerHTML = bodyHTML;
    elements.submit.textContent = submitLabel;
    elements.submit.style.display = hideSubmit ? 'none' : 'inline-flex';
    adminModalState.onSubmit = typeof onSubmit === 'function' ? onSubmit : null;
    elements.overlay.style.display = 'flex';
}

function readFieldValue(fieldName) {
    const input = document.querySelector(`[data-admin-field="${fieldName}"]`);
    if (!input) {
        return '';
    }

    if (input.type === 'checkbox') {
        return input.checked;
    }

    return input.value;
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function renderProductModalForm(product) {
    return `
        <div class="form-grid">
            <label class="form-control"><span>Key</span><input class="input" type="text" data-admin-field="key" value="${escapeHtml(product.key || '')}"></label>
            <label class="form-control"><span>Nama</span><input class="input" type="text" data-admin-field="name" value="${escapeHtml(product.name || '')}"></label>
        </div>
        <div class="form-grid">
            <label class="form-control"><span>Artist</span><input class="input" type="text" data-admin-field="artist" value="${escapeHtml(product.artist || '')}"></label>
            <label class="form-control"><span>Genre</span><input class="input" type="text" data-admin-field="genre" value="${escapeHtml(product.genre || '')}"></label>
        </div>
        <div class="form-grid">
            <label class="form-control"><span>Format</span><input class="input" type="text" data-admin-field="format" value="${escapeHtml(product.format || '')}"></label>
            <label class="form-control"><span>Tahun</span><input class="input" type="text" data-admin-field="year" value="${escapeHtml(product.year || '')}"></label>
        </div>
        <div class="form-grid">
            <label class="form-control"><span>Harga</span><input class="input" type="text" data-admin-field="price" value="${escapeHtml(product.price || 0)}"></label>
            <label class="form-control"><span>Stok</span><input class="input" type="text" data-admin-field="stock" value="${escapeHtml(product.stock || 0)}"></label>
        </div>
        <label class="form-control"><span>Badge</span><input class="input" type="text" data-admin-field="badge" value="${escapeHtml(product.badge || '')}"></label>
        <label class="form-control"><span>Deskripsi</span><textarea class="input" rows="4" data-admin-field="description">${escapeHtml(product.description || '')}</textarea></label>
    `;
}

function renderArticleModalForm(article) {
    return `
        <label class="form-control"><span>Key</span><input class="input" type="text" data-admin-field="key" value="${escapeHtml(article.key || '')}"></label>
        <label class="form-control"><span>Judul</span><input class="input" type="text" data-admin-field="title" value="${escapeHtml(article.title || '')}"></label>
        <div class="form-grid">
            <label class="form-control"><span>Kategori</span><input class="input" type="text" data-admin-field="category" value="${escapeHtml(article.category || '')}"></label>
            <label class="form-control"><span>Tanggal</span><input class="input" type="text" data-admin-field="date" value="${escapeHtml(article.date || '')}"></label>
        </div>
        <label class="form-control"><span>Ringkasan</span><textarea class="input" rows="3" data-admin-field="excerpt">${escapeHtml(article.excerpt || '')}</textarea></label>
        <label class="form-control"><span>Isi artikel</span><textarea class="input" rows="6" data-admin-field="body">${escapeHtml(Array.isArray(article.body) ? article.body.join('\n\n') : '')}</textarea></label>
    `;
}

function renderUserModalForm(user) {
    return `
        <label class="form-control"><span>Email</span><input class="input" type="text" data-admin-field="email" value="${escapeHtml(user.email || '')}"></label>
        <label class="form-control"><span>Nama</span><input class="input" type="text" data-admin-field="name" value="${escapeHtml(user.name || '')}"></label>
        <div class="form-grid">
            <label class="form-control"><span>Role</span><select class="input" data-admin-field="role"><option value="user" ${user.role === 'user' ? 'selected' : ''}>user</option><option value="admin" ${user.role === 'admin' ? 'selected' : ''}>admin</option></select></label>
            <label class="form-control"><span>Status</span><select class="input" data-admin-field="status"><option value="aktif" ${user.status === 'aktif' ? 'selected' : ''}>aktif</option><option value="nonaktif" ${user.status === 'nonaktif' ? 'selected' : ''}>nonaktif</option></select></label>
        </div>
    `;
}

function renderTransactionModal(transaction) {
    return `
        <div class="summary-list" style="margin-bottom: 16px;">
            <div class="summary-line"><span>Order</span><strong class="mono">#${escapeHtml(transaction.orderNumber || '')}</strong></div>
            <div class="summary-line"><span>Pengguna</span><span>${escapeHtml(transaction.userName || transaction.userEmail || '-')}</span></div>
            <div class="summary-line"><span>Item</span><span>${escapeHtml(transaction.items || '-')}</span></div>
            <div class="summary-line"><span>Total</span><strong class="mono">Rp ${Number(transaction.total || 0).toLocaleString('id-ID')}</strong></div>
        </div>
        <label class="form-control"><span>Status transaksi</span><select class="input" data-admin-field="status"><option value="pending" ${transaction.status === 'pending' ? 'selected' : ''}>Menunggu</option><option value="processing" ${transaction.status === 'processing' ? 'selected' : ''}>Diproses</option><option value="shipped" ${transaction.status === 'shipped' ? 'selected' : ''}>Dikirim</option><option value="completed" ${transaction.status === 'completed' ? 'selected' : ''}>Selesai</option><option value="cancelled" ${transaction.status === 'cancelled' ? 'selected' : ''}>Dibatalkan</option></select></label>
    `;
}

const adminModalElement = getAdminModalElements();
if (adminModalElement.close) {
    adminModalElement.close.addEventListener('click', closeAdminModal);
}
if (adminModalElement.close2) {
    adminModalElement.close2.addEventListener('click', closeAdminModal);
}
if (adminModalElement.overlay) {
    adminModalElement.overlay.addEventListener('click', (event) => {
        if (event.target === adminModalElement.overlay) {
            closeAdminModal();
        }
    });
}
if (adminModalElement.submit) {
    adminModalElement.submit.addEventListener('click', () => {
        if (adminModalState.onSubmit) {
            adminModalState.onSubmit();
        }
    });
}
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeAdminModal();
    }
});

document.addEventListener('click', (event) => {
    const addProductButton = event.target.closest('[data-add-product]');
    if (addProductButton) {
        openAdminModal({
            eyebrow: 'Kelola Produk',
            title: 'Tambah Produk',
            bodyHTML: renderProductModalForm({ key: '', name: '', artist: '', year: '', format: '', genre: '', price: 0, stock: 0, badge: '', description: '' }),
            submitLabel: 'Tambah Produk',
            onSubmit: () => {
                const nextProduct = {
                    key: normalizeCmsValue(readFieldValue('key')),
                    name: readFieldValue('name').trim(),
                    artist: readFieldValue('artist').trim(),
                    year: parseInt(readFieldValue('year').replace(/[^\d]/g, ''), 10) || new Date().getFullYear(),
                    format: readFieldValue('format').trim() || 'CD',
                    genre: readFieldValue('genre').trim() || 'K-Pop',
                    price: parseInt(readFieldValue('price').replace(/[^\d]/g, ''), 10) || 0,
                    stock: parseInt(readFieldValue('stock').replace(/[^\d]/g, ''), 10) || 0,
                    badge: readFieldValue('badge').trim() || 'New',
                    description: readFieldValue('description').trim(),
                    hidden: false,
                };

                if (!nextProduct.key || !nextProduct.name) {
                    window.alert('Key dan nama produk wajib diisi.');
                    return;
                }

                const products = getCmsCollection('products');
                const nextProducts = products.some((item) => normalizeCmsValue(item.key) === normalizeCmsValue(nextProduct.key))
                    ? products.map((item) => normalizeCmsValue(item.key) === normalizeCmsValue(nextProduct.key) ? nextProduct : item)
                    : [nextProduct, ...products];
                setCmsCollection('products', nextProducts);
                window.location.reload();
            },
        });
        return;
    }

    const addArticleButton = event.target.closest('[data-add-article]');
    if (addArticleButton) {
        openAdminModal({
            eyebrow: 'Kelola Artikel',
            title: 'Tambah Artikel',
            bodyHTML: renderArticleModalForm({ key: '', title: '', category: 'Review', date: '1 Mei 2026', excerpt: '', body: [''], hidden: false }),
            submitLabel: 'Tambah Artikel',
            onSubmit: () => {
                const nextArticle = {
                    key: normalizeCmsValue(readFieldValue('key')),
                    title: readFieldValue('title').trim(),
                    category: readFieldValue('category').trim() || 'Review',
                    date: readFieldValue('date').trim() || '1 Mei 2026',
                    excerpt: readFieldValue('excerpt').trim(),
                    body: readFieldValue('body').split('\n\n').map((paragraph) => paragraph.trim()).filter(Boolean),
                    hidden: false,
                };

                if (!nextArticle.key || !nextArticle.title) {
                    window.alert('Key dan judul artikel wajib diisi.');
                    return;
                }

                const articles = getCmsCollection('articles');
                const nextArticles = articles.some((item) => normalizeCmsValue(item.key) === normalizeCmsValue(nextArticle.key))
                    ? articles.map((item) => normalizeCmsValue(item.key) === normalizeCmsValue(nextArticle.key) ? nextArticle : item)
                    : [nextArticle, ...articles];
                setCmsCollection('articles', nextArticles);
                window.location.reload();
            },
        });
        return;
    }

    const exportTransactionsButton = event.target.closest('[data-export-transactions]');
    if (exportTransactionsButton) {
        const payload = JSON.stringify(getCmsCollection('transactions'), null, 2);
        const blob = new Blob([payload], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const anchor = document.createElement('a');
        anchor.href = url;
        anchor.download = 'recorda-transactions.json';
        document.body.appendChild(anchor);
        anchor.click();
        anchor.remove();
        window.setTimeout(() => URL.revokeObjectURL(url), 1000);
        return;
    }

    const productEditButton = event.target.closest('[data-edit-product]');
    if (productEditButton) {
        const row = productEditButton.closest('[data-admin-product-row]');
        if (!row) return;

        const current = getCmsProductByKey(row.dataset.productKey) || {
            key: row.dataset.productKey,
            name: row.dataset.productName || 'Produk',
            artist: row.dataset.productArtist || '',
            year: row.dataset.productYear || '',
            format: row.dataset.productFormat || '',
            genre: row.dataset.productGenre || '',
            price: parseInt(row.dataset.productPrice || '0', 10) || 0,
            stock: parseInt(row.dataset.productStock || '0', 10) || 0,
            badge: row.dataset.productBadge || 'Aktif',
            description: row.dataset.productDescription || '',
            hidden: false,
        };

        openAdminModal({
            eyebrow: 'Kelola Produk',
            title: current.key ? `Edit ${current.name}` : 'Tambah Produk',
            bodyHTML: renderProductModalForm(current),
            submitLabel: 'Simpan Produk',
            onSubmit: () => {
                const updatedProduct = {
                    ...current,
                    key: normalizeCmsValue(readFieldValue('key')) || current.key,
                    name: readFieldValue('name').trim() || current.name,
                    artist: readFieldValue('artist').trim() || current.artist,
                    year: parseInt(readFieldValue('year').replace(/[^\d]/g, ''), 10) || current.year,
                    format: readFieldValue('format').trim() || current.format,
                    genre: readFieldValue('genre').trim() || current.genre,
                    price: parseInt(readFieldValue('price').replace(/[^\d]/g, ''), 10) || current.price,
                    stock: parseInt(readFieldValue('stock').replace(/[^\d]/g, ''), 10) || current.stock,
                    badge: readFieldValue('badge').trim() || current.badge,
                    description: readFieldValue('description').trim() || current.description,
                    hidden: false,
                };

                const products = getCmsCollection('products');
                const nextProducts = products.some((item) => normalizeCmsValue(item.key) === normalizeCmsValue(updatedProduct.key))
                    ? products.map((item) => normalizeCmsValue(item.key) === normalizeCmsValue(updatedProduct.key) ? updatedProduct : item)
                    : [updatedProduct, ...products];
                setCmsCollection('products', nextProducts);
                window.location.reload();
            },
        });
        return;
    }

    const productDeleteButton = event.target.closest('[data-delete-product]');
    if (productDeleteButton) {
        const row = productDeleteButton.closest('[data-admin-product-row]');
        if (!row) return;

        if (!window.confirm('Yakin ingin menghapus produk ini?')) {
            return;
        }

        const key = row.dataset.productKey;
        const products = getCmsCollection('products').map((item) => normalizeCmsValue(item.key) === normalizeCmsValue(key) ? { ...item, hidden: true } : item);
        setCmsCollection('products', products);
        window.location.reload();
        return;
    }

    const articleEditButton = event.target.closest('[data-edit-article]');
    if (articleEditButton) {
        const row = articleEditButton.closest('[data-table-row], [data-article-key]');
        if (!row) return;

        const key = row.dataset.articleKey;
        const existing = getCmsArticleByKey(key) || {
            key,
            title: row.querySelector('h3')?.textContent || 'Artikel',
            category: row.querySelector('.meta')?.textContent?.split(' - ').at(-1) || 'Review',
            date: row.querySelector('.meta')?.textContent?.split(' - ')[1] || '1 Mei 2026',
            excerpt: row.querySelector('.muted')?.textContent || '',
            body: [row.querySelector('.muted')?.textContent || ''],
            hidden: false,
        };

        openAdminModal({
            eyebrow: 'Kelola Artikel',
            title: existing.key ? `Edit ${existing.title}` : 'Tambah Artikel',
            bodyHTML: renderArticleModalForm(existing),
            submitLabel: 'Simpan Artikel',
            onSubmit: () => {
                const updatedArticle = {
                    ...existing,
                    key: normalizeCmsValue(readFieldValue('key')) || existing.key,
                    title: readFieldValue('title').trim() || existing.title,
                    category: readFieldValue('category').trim() || existing.category,
                    date: readFieldValue('date').trim() || existing.date,
                    excerpt: readFieldValue('excerpt').trim() || existing.excerpt,
                    body: readFieldValue('body').split('\n\n').map((paragraph) => paragraph.trim()).filter(Boolean),
                    hidden: false,
                };

                const articles = getCmsCollection('articles');
                const nextArticles = articles.some((item) => normalizeCmsValue(item.key) === normalizeCmsValue(updatedArticle.key))
                    ? articles.map((item) => normalizeCmsValue(item.key) === normalizeCmsValue(updatedArticle.key) ? updatedArticle : item)
                    : [updatedArticle, ...articles];
                setCmsCollection('articles', nextArticles);
                window.location.reload();
            },
        });
        return;
    }

    const articleDeleteButton = event.target.closest('[data-delete-article]');
    if (articleDeleteButton) {
        const row = articleDeleteButton.closest('[data-table-row], [data-article-key]');
        if (!row) return;

        if (!window.confirm('Yakin ingin menghapus artikel ini?')) {
            return;
        }

        const key = row.dataset.articleKey;
        const articles = getCmsCollection('articles').map((item) => normalizeCmsValue(item.key) === normalizeCmsValue(key) ? { ...item, hidden: true } : item);
        setCmsCollection('articles', articles);
        window.location.reload();
        return;
    }

    const userEditButton = event.target.closest('[data-edit-user]');
    if (userEditButton) {
        const row = userEditButton.closest('[data-admin-user-row]');
        if (!row) return;

        const email = row.dataset.userEmail;
        const existing = getManagedUserByEmail(email) || {
            email,
            name: row.querySelector('.user-cell')?.textContent?.replace(/^[A-Z]\s+/, '').trim() || 'User',
            role: row.querySelector('.status-badge.is-info') ? 'admin' : 'user',
            status: row.querySelector('.status-badge.is-danger') ? 'nonaktif' : 'aktif',
            hidden: false,
        };

        openAdminModal({
            eyebrow: 'Kelola Pengguna',
            title: `Detail ${existing.name}`,
            bodyHTML: renderUserModalForm(existing),
            submitLabel: 'Simpan Pengguna',
            onSubmit: () => {
                const updatedUser = {
                    ...existing,
                    email: normalizeCmsValue(readFieldValue('email')) || existing.email,
                    name: readFieldValue('name').trim() || existing.name,
                    role: normalizeCmsValue(readFieldValue('role')) === 'admin' ? 'admin' : 'user',
                    status: normalizeCmsValue(readFieldValue('status')) === 'nonaktif' ? 'nonaktif' : 'aktif',
                    hidden: false,
                };

                persistManagedUser(updatedUser);
                window.location.reload();
            },
        });
        return;
    }

    const userToggleButton = event.target.closest('[data-toggle-user-status]');
    if (userToggleButton) {
        const row = userToggleButton.closest('[data-admin-user-row]');
        if (!row) return;

        const email = row.dataset.userEmail;
        const existing = getManagedUserByEmail(email) || {
            email,
            name: row.querySelector('.user-cell')?.textContent?.replace(/^[A-Z]\s+/, '').trim() || 'User',
            role: row.querySelector('.status-badge.is-info') ? 'admin' : 'user',
            status: row.querySelector('.status-badge.is-danger') ? 'nonaktif' : 'aktif',
            hidden: false,
        };

        const updatedUser = {
            ...existing,
            status: existing.status === 'aktif' ? 'nonaktif' : 'aktif',
        };

        persistManagedUser(updatedUser);
        window.location.reload();
        return;
    }

    const transactionDetailButton = event.target.closest('[data-view-transaction]');
    if (transactionDetailButton) {
        const row = transactionDetailButton.closest('[data-transaction-key], [data-tags]');
        const key = row?.dataset.transactionKey || row?.querySelector('.mono')?.textContent?.replace('#', '');
        const transaction = getCmsTransactionByKey(key);
        if (transaction) {
            openAdminModal({
                eyebrow: 'Detail Transaksi',
                title: `#${transaction.orderNumber}`,
                bodyHTML: renderTransactionModal(transaction),
                submitLabel: 'Tutup',
                onSubmit: closeAdminModal,
                hideSubmit: false,
            });
        } else {
            window.alert('Detail transaksi tidak ditemukan.');
        }
        return;
    }

    const transactionUpdateButton = event.target.closest('[data-update-transaction]');
    if (transactionUpdateButton) {
        const row = transactionUpdateButton.closest('[data-transaction-key], [data-tags]');
        if (!row) return;

        const key = row.dataset.transactionKey || row.querySelector('.mono')?.textContent?.replace('#', '');
        const transaction = getCmsTransactionByKey(key);
        if (!transaction) {
            return;
        }

        openAdminModal({
            eyebrow: 'Update Transaksi',
            title: `#${transaction.orderNumber}`,
            bodyHTML: renderTransactionModal(transaction),
            submitLabel: 'Simpan Status',
            onSubmit: () => {
                const nextStatus = normalizeCmsValue(readFieldValue('status')) || transaction.status;
                const transactions = getCmsCollection('transactions').map((item) => normalizeCmsValue(item.orderNumber) === normalizeCmsValue(transaction.orderNumber)
                    ? { ...item, status: nextStatus }
                    : item);
                setCmsCollection('transactions', transactions);

                const orders = getDashboardOrders().map((order) => normalizeCmsValue(order.orderNumber || order.orderId) === normalizeCmsValue(transaction.orderNumber)
                    ? { ...order, status: nextStatus }
                    : order);
                localStorage.setItem('recorda_orders', JSON.stringify(orders));
                if (orders[0]) {
                    localStorage.setItem('recorda_order', JSON.stringify(orders[0]));
                }

                window.location.reload();
            },
        });
        return;
    }
});

// Newsletter form
const newsletterForm = document.querySelector('[data-newsletter-form]');

if (newsletterForm) {
    const emailInput = newsletterForm.querySelector('[data-newsletter-email]');
    const feedback = document.querySelector('[data-newsletter-feedback]');
    const submitButton = newsletterForm.querySelector('button[type="submit"]');

    const setFeedback = (message, isSuccess) => {
        if (!feedback) return;
        feedback.textContent = message;
        feedback.classList.toggle('is-success', Boolean(isSuccess));
    };

    newsletterForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const emailVal = emailInput ? emailInput.value.trim().toLowerCase() : '';

        if (!emailVal) {
            setFeedback('Email harus diisi.', false);
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
            setFeedback('Format email tidak valid.', false);
            return;
        }

        setFeedback('✓ Email berhasil didaftarkan!', true);
        if (submitButton) submitButton.disabled = true;

        window.setTimeout(() => {
            emailInput.value = '';
            if (submitButton) submitButton.disabled = false;
            setFeedback('');
        }, 2000);
    });
}

// Cart page - render items from localStorage
const cartListContainer = document.querySelector('[data-cart-list]');

if (cartListContainer) {
    const renderCart = () => {
        const cart = JSON.parse(localStorage.getItem('recorda_cart') || '[]');
        cartListContainer.innerHTML = '';

        if (cart.length === 0) {
            cartListContainer.innerHTML = '<p class="muted" style="padding: 40px; text-align: center;">Keranjang kosong</p>';
            updateSummary();
            return;
        }

        cart.forEach((item, index) => {
            const itemHTML = `
                <article class="cart-item" data-cart-index="${index}">
                    <div class="cart-thumb cover-a"${item.image ? ` style="background-image:url('${item.image}'); background-size:cover; background-position:center;"` : ''}></div>
                    <div class="cart-info">
                        <h3>${item.name}</h3>
                        <p class="muted">Album</p>
                    </div>
                    <div class="qty-stepper">
                        <button class="qty-button" type="button" data-qty-action="minus">-</button>
                        <span class="qty-value" data-qty-value>${item.qty}</span>
                        <button class="qty-button" type="button" data-qty-action="plus">+</button>
                    </div>
                    <span class="price mono" data-item-price>Rp ${parseInt(item.price).toLocaleString('id-ID')}</span>
                    <button class="btn btn-danger btn-compact" style="margin-left: 10px;" data-remove-item>Hapus</button>
                </article>
            `;
            cartListContainer.insertAdjacentHTML('beforeend', itemHTML);
        });

        // Attach event listeners to qty buttons
        const qtyWrappers = cartListContainer.querySelectorAll('[data-cart-index]');
        qtyWrappers.forEach((wrapper) => {
            const valueElement = wrapper.querySelector('[data-qty-value]');
            const buttons = wrapper.querySelectorAll('[data-qty-action]');
            const removeButton = wrapper.querySelector('[data-remove-item]');
            const cartIndex = wrapper.dataset.cartIndex;

            buttons.forEach((button) => {
                button.addEventListener('click', () => {
                    const action = button.dataset.qtyAction;
                    let currentQty = parseInt(valueElement.textContent, 10);
                    const newQty = action === 'plus' ? currentQty + 1 : Math.max(1, currentQty - 1);

                    // Update localStorage
                    cart[cartIndex].qty = newQty;
                    cart[cartIndex].quantity = newQty;
                    localStorage.setItem('recorda_cart', JSON.stringify(cart));

                    // Update UI
                    valueElement.textContent = newQty;
                    const itemPrice = wrapper.querySelector('[data-item-price]');
                    const pricePerUnit = parseInt(cart[cartIndex].price, 10);
                    itemPrice.textContent = `Rp ${(pricePerUnit * newQty).toLocaleString('id-ID')}`;

                    updateSummary();
                });
            });

            if (removeButton) {
                removeButton.addEventListener('click', () => {
                    cart.splice(cartIndex, 1);
                    localStorage.setItem('recorda_cart', JSON.stringify(cart));
                    renderCart(); // Re-render
                });
            }
        });

        updateSummary();
    };

    const updateSummary = () => {
        const cart = JSON.parse(localStorage.getItem('recorda_cart') || '[]');
        let subtotal = 0;

        cart.forEach((item) => {
            const itemQty = item.quantity || item.qty || 1;
            subtotal += parseInt(item.price, 10) * itemQty;
        });

        const shipping = 30000;
        const total = subtotal + shipping;

        const subtotalElement = document.querySelector('[data-subtotal]');
        const totalElement = document.querySelector('[data-total]');

        if (subtotalElement) {
            subtotalElement.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
        }
        if (totalElement) {
            totalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
        }
    };

    // Initial render
    renderCart();
}

// ========== User Menu & Auth State Management ==========
const authState = {
    isLoggedIn: () => !!localStorage.getItem('recorda_user'),
    getUser: () => {
        const userJson = localStorage.getItem('recorda_user');
        return userJson ? JSON.parse(userJson) : null;
    },
    setUser: (email, role = 'user') => {
        localStorage.setItem('recorda_user', JSON.stringify({ email, role, loginTime: Date.now() }));
        updateUserMenu();
    },
    logout: () => {
        localStorage.removeItem('recorda_user');
        updateUserMenu();
        window.location.href = '/';
    }
};

const updateUserMenu = () => {
    const userMenuWrapper = document.querySelector('[data-user-menu-wrapper]');
    const loginButton = document.querySelector('[data-login-button]');
    const userEmail = document.querySelector('[data-user-email]');
    const userName = document.querySelector('[data-user-name]');
    const userRoleBadge = document.querySelector('[data-user-role-badge]');
    const userStatusBadge = document.querySelector('[data-user-status-badge]');

    if (authState.isLoggedIn()) {
        const user = authState.getUser();
        const managedUser = typeof getManagedUserByEmail === 'function' ? getManagedUserByEmail(user.email) : null;
        const roleLabel = managedUser?.role || user.role || 'user';
        const statusLabel = managedUser?.status || 'aktif';
        if (userMenuWrapper) userMenuWrapper.style.display = 'block';
        if (loginButton) loginButton.style.display = 'none';
        if (userEmail) userEmail.textContent = user.email;
        if (userName) userName.textContent = user.email.split('@')[0];
        if (userRoleBadge) userRoleBadge.textContent = `Role: ${roleLabel}`;
        if (userStatusBadge) userStatusBadge.textContent = `Status: ${statusLabel}`;
    } else {
        if (userMenuWrapper) userMenuWrapper.style.display = 'none';
        if (loginButton) loginButton.style.display = 'block';
        if (userRoleBadge) userRoleBadge.textContent = '';
        if (userStatusBadge) userStatusBadge.textContent = '';
    }
};

// Handle user menu dropdown toggle
const userMenuTrigger = document.querySelector('[data-user-menu-trigger]');
const userMenuWrapper = document.querySelector('[data-user-menu-wrapper]');

if (userMenuTrigger && userMenuWrapper) {
    userMenuTrigger.addEventListener('click', () => {
        userMenuWrapper.classList.toggle('is-open');
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!userMenuWrapper.contains(e.target) && e.target !== userMenuTrigger) {
            userMenuWrapper.classList.remove('is-open');
        }
    });
}

// Handle logout
const logoutButton = document.querySelector('[data-logout-button]');
if (logoutButton) {
    logoutButton.addEventListener('click', () => {
        authState.logout();
    });
}

const adminAccountButtons = document.querySelectorAll('.admin-account');
adminAccountButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const adminUser = authState.getUser();
        const managedUser = adminUser ? getManagedUserByEmail(adminUser.email) : null;
        const roleLabel = managedUser?.role || adminUser?.role || 'admin';
        const statusLabel = managedUser?.status || 'aktif';
        window.alert(`Akun admin\nEmail: ${adminUser?.email || 'admin@recorda.com'}\nRole: ${roleLabel}\nStatus: ${statusLabel}`);
    });
});

// Auth state kini dikelola server-side (Blade @auth/@guest); handler localStorage dinonaktifkan.
// document.addEventListener('DOMContentLoaded', updateUserMenu);

// ========== Shared CMS State ==========
const CMS_STORAGE_KEYS = {
    products: 'recorda_cms_products',
    articles: 'recorda_cms_articles',
    users: 'recorda_cms_users',
    transactions: 'recorda_cms_transactions',
};

const CMS_DEFAULTS = {
    products: [
        { key: 'abbey-road', name: 'Abbey Road', artist: 'The Beatles', year: 1969, format: 'Vinyl', genre: 'Rock', price: 450000, stock: 12, badge: 'Limited', cover: 'cover-a', hidden: false },
        { key: 'rumours', name: 'Rumours', artist: 'Fleetwood Mac', year: 1977, format: 'CD', genre: 'Pop', price: 250000, stock: 15, badge: 'Ready', cover: 'cover-b', hidden: false },
        { key: 'blue-train', name: 'Blue Train', artist: 'John Coltrane', year: 1958, format: 'Vinyl', genre: 'Jazz', price: 520000, stock: 5, badge: 'Collector', cover: 'cover-c', hidden: false },
        { key: 'after-hours', name: 'After Hours', artist: 'The Weeknd', year: 2020, format: 'Kaset', genre: 'R&B', price: 185000, stock: 7, badge: 'New', cover: 'cover-d', hidden: false },
        { key: 'amber-lights', name: 'Amber Lights', artist: 'Fictional Act', year: 2014, format: 'Vinyl', genre: 'Indie', price: 390000, stock: 6, badge: 'Preorder', cover: 'cover-b', hidden: false },
        { key: 'quiet-motion', name: 'Quiet Motion', artist: 'Midnight City', year: 2022, format: 'CD', genre: 'Alternative', price: 210000, stock: 9, badge: 'Limited', cover: 'cover-c', hidden: false },
        { key: 'ad-mare', name: 'AD MARE', artist: 'NMIXX', year: 2022, format: 'CD', genre: 'K-Pop', price: 180000, stock: 8, badge: 'Ready', cover: 'cover-a', hidden: false },
        { key: 'entwurf', name: 'ENTWURF', artist: 'NMIXX', year: 2022, format: 'CD', genre: 'K-Pop', price: 195000, stock: 6, badge: 'Limited', cover: 'cover-b', hidden: false },
        { key: 'a-midsummer-nmixxs-dream', name: "A Midsummer NMIXX's Dream", artist: 'NMIXX', year: 2023, format: 'CD', genre: 'K-Pop', price: 200000, stock: 10, badge: 'Ready', cover: 'cover-c', hidden: false },
        { key: 'expergo', name: 'Expérgo', artist: 'NMIXX', year: 2023, format: 'CD', genre: 'K-Pop', price: 205000, stock: 7, badge: 'Ready', cover: 'cover-d', hidden: false },
        { key: 'fe3o4-break', name: 'Fe3O4: BREAK', artist: 'NMIXX', year: 2024, format: 'CD', genre: 'K-Pop', price: 210000, stock: 9, badge: 'Limited', cover: 'cover-a', hidden: false },
        { key: 'fe3o4-stick-out', name: 'Fe3O4: STICK OUT', artist: 'NMIXX', year: 2024, format: 'CD', genre: 'K-Pop', price: 210000, stock: 8, badge: 'Ready', cover: 'cover-b', hidden: false },
        { key: 'fe3o4-forward', name: 'Fe3O4: FORWARD', artist: 'NMIXX', year: 2025, format: 'CD', genre: 'K-Pop', price: 215000, stock: 11, badge: 'New', cover: 'cover-c', hidden: false },
        { key: 'heavy-serenade', name: 'Heavy Serenade', artist: 'NMIXX', year: 2026, format: 'CD', genre: 'K-Pop', price: 220000, stock: 14, badge: 'Limited', cover: 'cover-d', hidden: false },
        { key: 'born-pink', name: 'Born Pink', artist: 'BLACKPINK', year: 2022, format: 'CD', genre: 'K-Pop', price: 220000, stock: 13, badge: 'Ready', cover: 'cover-a', hidden: false },
        { key: 'savage', name: 'Savage', artist: 'aespa', year: 2021, format: 'CD', genre: 'K-Pop', price: 170000, stock: 10, badge: 'Ready', cover: 'cover-b', hidden: false },
        { key: 'spicy', name: 'Spicy', artist: 'aespa', year: 2023, format: 'CD', genre: 'K-Pop', price: 175000, stock: 9, badge: 'Ready', cover: 'cover-d', hidden: false },
    ],
    articles: [
        { key: 'heavy-serenade-review', title: 'Review album heavy serenade NMIXX', category: 'Review', date: '12 Mei 2026', excerpt: 'NMIXX melakukan debut terbarunya dengan warna synth dan brass yang tebal.', body: ['Album ini dibuka dengan tekstur synth tebal yang langsung mengarah ke era 80an, tapi tetap punya punch modern dari drum yang presisi.', 'Di track kedua, vokal layered memberi ruang pada bassline yang santai, cocok untuk didengar di malam hari saat lampu kota mulai meredup.', 'Secara keseluruhan, mixing terasa bersih dan detail, terutama di bagian chorus yang terasa lebih luas tanpa kehilangan fokus pada vokal utama.', 'Jika kamu suka pop dengan sentuhan brass dan atmosfer sinematik, album ini wajib ada di rak koleksi.'], hidden: false },
        { key: 'turntable-setup', title: 'Checklist setup turntable pemula', category: 'Tips', date: '10 Mei 2026', excerpt: 'Panduan ringkas mulai dari cartridge sampai setting anti skate.', body: ['Checklist ini bantu setup awal biar turntable ga bikin pusing.', 'Mulai dari posisi meja, cartridge, sampai anti-skate yang pas, semuanya perlu dicek pelan-pelan.'], hidden: false },
        { key: 'indie-cassette-reissue', title: 'Rilis ulang kaset indie lokal dari tahun 2000an', category: 'Rilis', date: '08 Mei 2026', excerpt: 'Recorda menghadirkan ulang kaset langka dengan jumlah press terbatas.', body: ['Rilisan ulang ini sengaja dibikin terbatas biar nuansa koleksinya masih kerasa.', 'Kaset indie lokal ini punya karakter analog yang hangat dan masih dicari kolektor.'], hidden: false },
        { key: 'vinyl-jazz-soundstage', title: 'Soundstage vinyl jazz dan cara menikmati detailnya', category: 'Review', date: '06 Mei 2026', excerpt: 'Tips memilih pressing dan cara merawat piringan agar tetap bersih.', body: ['Vinyl jazz paling enak dinikmati kalau soundstage-nya kebuka dan detail instrumennya kebaca.', 'Perawatan piringan yang rapi bikin hasil dengarnya tetap bersih dan tahan lama.'], hidden: false },
        { key: 'stylus-cleaning', title: 'Membersihkan stylus dengan aman di rumah', category: 'Tips', date: '02 Mei 2026', excerpt: 'Langkah sederhana agar stylus tetap tajam dan musik tetap jernih.', body: ['Stylus itu kecil tapi pengaruhnya besar, jadi harus dibersihin pelan-pelan.', 'Pakai alat yang aman dan jangan asal gosok biar ga merusak jarum.'], hidden: false },
        { key: 'folk-pressing-review', title: 'Review pressing ulang album klasik folk 1972', category: 'Review', date: '29 April 2026', excerpt: 'Dibandingkan versi lama, pressing baru punya detail vokal lebih jelas.', body: ['Versi pressing ulang ini terasa lebih rapi dan vokalnya naik satu level.', 'Buat yang suka warna folk klasik, rilisan ini masih nyaman banget didengerin.'], hidden: false },
    ],
    users: [
        { email: 'user@recorda.com', name: 'User Recorda', role: 'user', status: 'aktif', hidden: false },
        { email: 'admin@recorda.com', name: 'Admin Recorda', role: 'admin', status: 'aktif', hidden: false },
        { email: 'andy@email.com', name: 'Andy Rafaela', role: 'user', status: 'aktif', hidden: false },
        { email: 'budi@email.com', name: 'Budi Santoso', role: 'user', status: 'aktif', hidden: false },
        { email: 'sari@email.com', name: 'Sari Dewi', role: 'admin', status: 'aktif', hidden: false },
        { email: 'cahyo@email.com', name: 'Cahyo Pratama', role: 'user', status: 'nonaktif', hidden: false },
        { email: 'rina@email.com', name: 'Rina Lestari', role: 'user', status: 'aktif', hidden: false },
        { email: 'dodi@email.com', name: 'Dodi Wijaya', role: 'user', status: 'aktif', hidden: false },
    ],
    transactions: [
        { orderNumber: 'REC-048', userEmail: 'andy@email.com', userName: 'Andy Rafaela', items: 'Abbey Road (1)', total: 450000, status: 'completed', createdAt: '2026-05-10T09:00:00.000Z', hidden: false },
        { orderNumber: 'REC-047', userEmail: 'budi@email.com', userName: 'Budi Santoso', items: 'Kind of Blue (1)', total: 320000, status: 'processing', createdAt: '2026-05-10T10:00:00.000Z', hidden: false },
        { orderNumber: 'REC-046', userEmail: 'sari@email.com', userName: 'Sari Dewi', items: 'Nevermind, Abbey (2)', total: 890000, status: 'pending', createdAt: '2026-05-09T10:00:00.000Z', hidden: false },
        { orderNumber: 'REC-045', userEmail: 'cahyo@email.com', userName: 'Cahyo P.', items: 'Tapestry (1)', total: 405000, status: 'shipped', createdAt: '2026-05-08T10:00:00.000Z', hidden: false },
        { orderNumber: 'REC-044', userEmail: 'rina@email.com', userName: 'Rina L.', items: 'Ziggy Stardust (1)', total: 380000, status: 'cancelled', createdAt: '2026-05-07T10:00:00.000Z', hidden: false },
    ],
};

function cloneCmsDefaults(value) {
    return JSON.parse(JSON.stringify(value));
}

function normalizeCmsValue(value) {
    return String(value || '').trim().toLowerCase().replace(/\s+/g, ' ');
}

function loadCmsCollection(collectionName) {
    const storageKey = CMS_STORAGE_KEYS[collectionName];
    const defaults = CMS_DEFAULTS[collectionName] || [];
    const storedValue = localStorage.getItem(storageKey);

    if (storedValue) {
        try {
            const parsed = JSON.parse(storedValue);
            if (Array.isArray(parsed)) {
                return parsed;
            }
        } catch (error) {
            // fall through to defaults
        }
    }

    const seededDefaults = cloneCmsDefaults(defaults);
    localStorage.setItem(storageKey, JSON.stringify(seededDefaults));
    return seededDefaults;
}

function saveCmsCollection(collectionName, items) {
    localStorage.setItem(CMS_STORAGE_KEYS[collectionName], JSON.stringify(items));
}

function getCmsCollection(collectionName) {
    return loadCmsCollection(collectionName);
}

function setCmsCollection(collectionName, items) {
    saveCmsCollection(collectionName, items);
    return items;
}

function getCmsProductByKey(key) {
    const targetKey = normalizeCmsValue(key);
    return getCmsCollection('products').find((item) => normalizeCmsValue(item.key) === targetKey);
}

function getCmsProductByName(name) {
    const targetName = normalizeCmsValue(name);
    return getCmsCollection('products').find((item) => normalizeCmsValue(item.name) === targetName);
}

function getCmsArticleByKey(key) {
    const targetKey = normalizeCmsValue(key);
    return getCmsCollection('articles').find((item) => normalizeCmsValue(item.key) === targetKey);
}

function getManagedUserByEmail(email) {
    const targetEmail = normalizeCmsValue(email);
    return getCmsCollection('users').find((item) => normalizeCmsValue(item.email) === targetEmail);
}

function getCmsTransactionByKey(key) {
    const targetKey = normalizeCmsValue(key);
    return getCmsCollection('transactions').find((item) => normalizeCmsValue(item.orderNumber) === targetKey);
}

function persistManagedUser(nextUser) {
    const users = getCmsCollection('users');
    const nextUsers = users.some((item) => normalizeCmsValue(item.email) === normalizeCmsValue(nextUser.email))
        ? users.map((item) => normalizeCmsValue(item.email) === normalizeCmsValue(nextUser.email) ? nextUser : item)
        : [nextUser, ...users];

    setCmsCollection('users', nextUsers);

    const currentUserJson = localStorage.getItem('recorda_user');
    if (currentUserJson) {
        try {
            const currentUser = JSON.parse(currentUserJson);
            if (normalizeCmsValue(currentUser.email) === normalizeCmsValue(nextUser.email)) {
                if (nextUser.status === 'nonaktif') {
                    localStorage.removeItem('recorda_user');
                } else {
                    localStorage.setItem('recorda_user', JSON.stringify({
                        ...currentUser,
                        role: nextUser.role || currentUser.role,
                    }));
                }
            }
        } catch (error) {
            // ignore malformed auth state
        }
    }
}

function applyCmsToPublicPages() {
    const productCards = document.querySelectorAll('.catalog-grid .product-card');
    const detailProductSection = document.querySelector('[data-product-key]');
    const articleRows = document.querySelectorAll('[data-article-key]');
    const articleHero = document.querySelector('[data-article-key][data-article-body]');

    productCards.forEach((card) => {
        const titleElement = card.querySelector('h3');
        const linkElement = card.querySelector('a[href*="/produk/"]');
        const priceElement = card.querySelector('.price');
        const metaElement = card.querySelector('.muted');
        const badgeElement = card.querySelector('.product-badge');

        if (!titleElement || !linkElement) {
            return;
        }

        const slug = linkElement.getAttribute('href').split('/').filter(Boolean).pop();
        const product = getCmsProductByKey(slug) || getCmsProductByName(titleElement.textContent);

        if (!product || product.hidden) {
            card.style.display = product && product.hidden ? 'none' : '';
            return;
        }

        titleElement.textContent = product.name;
        if (metaElement) {
            metaElement.textContent = `${product.artist} - ${product.year}`;
        }
        if (priceElement) {
            priceElement.textContent = `Rp ${Number(product.price || 0).toLocaleString('id-ID')}`;
        }
        if (badgeElement && product.badge) {
            badgeElement.textContent = product.badge;
        }
    });

    if (detailProductSection) {
        const productKey = detailProductSection.dataset.productKey;
        const product = getCmsProductByKey(productKey);

        if (product) {
            const mainTitle = detailProductSection.querySelector('h1');
            const meta = detailProductSection.querySelector('.meta');
            const lead = detailProductSection.querySelector('.lead');
            const price = detailProductSection.querySelector('.product-price .price');
            const stock = detailProductSection.querySelector('.stock-badge');
            const productMain = detailProductSection.querySelector('[data-product-main]');

            if (mainTitle) mainTitle.textContent = product.name;
            if (meta) meta.textContent = `${product.format || 'CD'} - ${product.genre || 'K-Pop'}`;
            if (lead) lead.textContent = product.description || lead.textContent;
            if (price) price.textContent = `Rp ${Number(product.price || 0).toLocaleString('id-ID')}`;
            if (stock) stock.textContent = `Stok ${product.stock ?? 0}`;
            if (productMain) {
                productMain.className = `product-main ${product.cover || 'cover-a'}`;
            }
        }
    }

    articleRows.forEach((row) => {
        const article = getCmsArticleByKey(row.dataset.articleKey);
        if (!article || article.hidden) {
            row.style.display = article && article.hidden ? 'none' : '';
            return;
        }

        const titleElement = row.querySelector('h3');
        const metaElement = row.querySelector('.meta');
        const excerptElement = row.querySelector('.muted');

        if (titleElement) titleElement.textContent = article.title;
        if (metaElement) metaElement.textContent = `Admin - ${article.date} - ${article.category}`;
        if (excerptElement) excerptElement.textContent = article.excerpt;
    });

    if (articleHero) {
        const article = getCmsArticleByKey(articleHero.dataset.articleKey);
        if (article) {
            const heroTitle = articleHero.querySelector('h1');
            const heroMeta = articleHero.querySelector('.meta');
            const content = document.querySelector('[data-article-body]');

            if (heroTitle) heroTitle.textContent = article.title;
            if (heroMeta) heroMeta.textContent = `Admin - ${article.date} - ${article.category}`;
            if (content) {
                content.innerHTML = `
                    ${article.body.map((paragraph) => `<p>${paragraph}</p>`).join('')}
                    <div class="quote-block">
                        <p>${article.quote || 'Music gives a soul to the universe.'}</p>
                        <span class="mono">Recorda note</span>
                    </div>
                `;
            }
        }
    }

    const userMenuRoleBadge = document.querySelector('[data-user-role-badge]');
    const userMenuStatusBadge = document.querySelector('[data-user-status-badge]');
    if (authState.isLoggedIn()) {
        const currentUser = authState.getUser();
        const managedUser = currentUser ? getManagedUserByEmail(currentUser.email) : null;
        if (userMenuRoleBadge) userMenuRoleBadge.textContent = managedUser ? `Role: ${managedUser.role}` : '';
        if (userMenuStatusBadge) userMenuStatusBadge.textContent = managedUser ? `Status: ${managedUser.status}` : '';
    }
}

// Konten publik kini dirender dari database (server-side); overlay CMS localStorage dinonaktifkan.
// document.addEventListener('DOMContentLoaded', applyCmsToPublicPages);

// ========== Admin Dashboard Chart ==========
const getDashboardOrders = () => {
    const ordersJson = localStorage.getItem('recorda_orders');
    const legacyOrderJson = localStorage.getItem('recorda_order');

    if (ordersJson) {
        try {
            const parsedOrders = JSON.parse(ordersJson);
            return Array.isArray(parsedOrders) ? parsedOrders : [];
        } catch (error) {
            return [];
        }
    }

    if (legacyOrderJson) {
        try {
            const legacyOrder = JSON.parse(legacyOrderJson);
            return legacyOrder ? [legacyOrder] : [];
        } catch (error) {
            return [];
        }
    }

    return [];
};

const normalizeDashboardOrder = (order) => ({
    ...order,
    total: Number(order.total) || 0,
    createdAt: order.createdAt || new Date().toISOString(),
});

const renderDashboardChart = () => {
    const chartContainer = document.querySelector('[data-dashboard-chart]');
    if (!chartContainer) {
        return;
    }

    const chartLabel = document.querySelector('[data-dashboard-chart-label]');
    const rangeButtons = document.querySelectorAll('[data-dashboard-range]');
    const revenueStat = document.querySelector('[data-dashboard-stat="revenue"] h3');
    const ordersStat = document.querySelector('[data-dashboard-stat="orders"] h3');

    const demoOrders = [
        { total: 1175000, createdAt: '2026-05-10T10:00:00.000Z' },
        { total: 405000, createdAt: '2026-05-02T10:00:00.000Z' },
        { total: 690000, createdAt: '2026-04-28T10:00:00.000Z' },
        { total: 720000, createdAt: '2026-04-20T10:00:00.000Z' },
        { total: 250000, createdAt: '2026-04-12T10:00:00.000Z' },
    ];

    const sourceOrders = getDashboardOrders();
    const orders = sourceOrders.length > 0 ? sourceOrders : demoOrders;
    const normalizedOrders = orders.map(normalizeDashboardOrder);

    const updateStats = () => {
        const revenue = normalizedOrders.reduce((sum, order) => sum + order.total, 0);
        const orderCount = normalizedOrders.length;

        if (revenueStat) {
            revenueStat.textContent = `Rp ${revenue.toLocaleString('id-ID')}`;
        }
        if (ordersStat) {
            ordersStat.textContent = String(orderCount);
        }
    };

    const buildSeries = (days) => {
        const endDate = new Date();
        endDate.setHours(23, 59, 59, 999);

        const startDate = new Date(endDate);
        startDate.setDate(startDate.getDate() - (days - 1));
        startDate.setHours(0, 0, 0, 0);

        const series = [];
        for (let index = 0; index < days; index += 1) {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + index);
            const key = currentDate.toISOString().slice(0, 10);
            const label = currentDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            series.push({ key, label, value: 0 });
        }

        normalizedOrders.forEach((order) => {
            const orderDate = new Date(order.createdAt);
            if (Number.isNaN(orderDate.getTime())) {
                return;
            }

            if (orderDate < startDate || orderDate > endDate) {
                return;
            }

            const key = orderDate.toISOString().slice(0, 10);
            const point = series.find((item) => item.key === key);
            if (point) {
                point.value += order.total;
            }
        });

        return series;
    };

    const renderSeries = (days) => {
        const series = buildSeries(days);
        const maxValue = Math.max(...series.map((item) => item.value), 1);
        const width = 760;
        const height = 220;
        const paddingX = 28;
        const paddingY = 24;
        const usableWidth = width - (paddingX * 2);
        const usableHeight = height - (paddingY * 2);

        const points = series.map((item, index) => {
            const x = paddingX + (usableWidth * (series.length === 1 ? 0 : index / (series.length - 1)));
            const y = height - paddingY - ((item.value / maxValue) * usableHeight);
            return { ...item, x, y };
        });

        const linePoints = points.map((point) => `${point.x},${point.y}`).join(' ');
        const baseline = height - paddingY;
        const gridLines = [0.25, 0.5, 0.75].map((ratio) => {
            const y = paddingY + (usableHeight * ratio);
            return `<line x1="${paddingX}" y1="${y}" x2="${width - paddingX}" y2="${y}" stroke="rgba(0,0,0,0.08)" stroke-dasharray="4 4"></line>`;
        }).join('');

        chartContainer.innerHTML = `
            <svg viewBox="0 0 ${width} ${height}" role="img" aria-label="Grafik penjualan ${days} hari terakhir" style="width: 100%; height: 100%;">
                ${gridLines}
                <line x1="${paddingX}" y1="${baseline}" x2="${width - paddingX}" y2="${baseline}" stroke="rgba(0,0,0,0.16)"></line>
                <polyline fill="none" stroke="#C75B35" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" points="${linePoints}"></polyline>
                ${points.map((point) => `<circle cx="${point.x}" cy="${point.y}" r="4" fill="#C75B35"></circle>`).join('')}
                ${points.map((point) => `
                    <text x="${point.x}" y="${height - 6}" text-anchor="middle" font-size="10" fill="#6E5B4C">${point.label}</text>
                `).join('')}
            </svg>
        `;

        if (chartLabel) {
            chartLabel.textContent = `${days} hari terakhir`;
        }
    };

    const setActiveRange = (days) => {
        rangeButtons.forEach((button) => {
            button.classList.toggle('is-active', button.dataset.dashboardRange === String(days));
        });
        renderSeries(days);
    };

    rangeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            setActiveRange(parseInt(button.dataset.dashboardRange, 10) || 7);
        });
    });

    updateStats();
    setActiveRange(7);
};

document.addEventListener('DOMContentLoaded', renderDashboardChart);

