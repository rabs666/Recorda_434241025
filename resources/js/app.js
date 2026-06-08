import './bootstrap';

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

		if (email !== roleCredential.email || password !== roleCredential.password) {
			setFeedback(`Email atau password ${roleLabel} tidak sesuai.`, false);
			return;
		}
		setFeedback(`Login dummy ${roleLabel} berhasil, mengalihkan...`, true);

		if (submitButton) {
			submitButton.disabled = true;
		}

		const userRedirect = dummyLoginForm.dataset.successRedirect || 'home';
		const adminRedirect = dummyLoginForm.dataset.adminRedirect || 'dashboard';
		const redirectTarget = resolvedRole === 'admin' ? adminRedirect : userRedirect;

		window.setTimeout(() => {
			const resolvedUrl = new URL(redirectTarget, window.location.href);
			window.location.href = resolvedUrl.toString();
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
const productMain = document.querySelector('[data-product-main]');

// Real photo thumbnails (use background-image)
const productPhotos = document.querySelectorAll('[data-product-photo]');

productPhotos.forEach((thumb) => {
	thumb.addEventListener('click', () => {
		const photoUrl = thumb.dataset.productPhoto;
		if (productMain && photoUrl) {
			productMain.style.backgroundImage = `url('${photoUrl}')`;
		}
		productPhotos.forEach((t) => t.classList.remove('is-active'));
		thumb.classList.add('is-active');
	});
});

// Cover-class fallback thumbnails (no real photo available)
const productThumbs = document.querySelectorAll('[data-product-thumb]');

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
if (productPhotos.length > 0) {
	productPhotos[0].classList.add('is-active');
} else if (productThumbs.length > 0) {
	productThumbs[0].classList.add('is-active');
}

// Add to cart button
const addToCartButtons = document.querySelectorAll('[data-add-to-cart]');

addToCartButtons.forEach((button) => {
	button.addEventListener('click', () => {
		const productName = button.dataset.productName || 'Product';
		const productPrice = button.dataset.productPrice || '0';
		
		// Simulate adding to cart (store in localStorage)
		let cart = JSON.parse(localStorage.getItem('recorda_cart') || '[]');
		cart.push({
			id: Date.now(),
			name: productName,
			price: productPrice,
			qty: 1
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

// Checkout form handler
const placeOrderButton = document.querySelector('[data-place-order]');

if (placeOrderButton) {
	placeOrderButton.addEventListener('click', () => {
		const firstNameInput = document.querySelector('[data-checkout-first-name]');
		const lastNameInput = document.querySelector('[data-checkout-last-name]');
		const addressInput = document.querySelector('[data-checkout-address]');
		const cityInput = document.querySelector('[data-checkout-city]');
		const postalInput = document.querySelector('[data-checkout-postal]');
		const phoneInput = document.querySelector('[data-checkout-phone]');
		const paymentSelected = document.querySelector('[data-payment-option].is-active');

		// Validasi semua field terisi
		if (!firstNameInput?.value || !lastNameInput?.value || !addressInput?.value || !cityInput?.value || !postalInput?.value || !phoneInput?.value || !paymentSelected) {
			alert('Harap lengkapi semua data pengiriman dan pilih metode pembayaran.');
			return;
		}

		// Generate order number
		const orderNumber = 'ORD-' + Date.now().toString().slice(-8).toUpperCase();

		// Show confirmation modal
		const modal = document.querySelector('[data-order-modal]');
		const orderNumberElement = document.querySelector('[data-order-number]');

		if (modal && orderNumberElement) {
			orderNumberElement.textContent = orderNumber;
			modal.style.display = 'flex';

			// Clear cart after 3 seconds
			window.setTimeout(() => {
				localStorage.removeItem('recorda_cart');
			}, 3000);
		}
	});
}

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

// Admin delete action with confirmation
const deleteButtons = document.querySelectorAll('[data-delete-btn]');

deleteButtons.forEach((button) => {
	button.addEventListener('click', () => {
		if (confirm('Yakin ingin menghapus item ini?')) {
			const row = button.closest('[data-table-row]');
			if (row) {
				row.style.opacity = '0.5';
				button.disabled = true;
				window.setTimeout(() => {
					row.style.display = 'none';
				}, 300);
			}
		}
	});
});

// Admin edit action
const editButtons = document.querySelectorAll('[data-edit-btn]');

editButtons.forEach((button) => {
	button.addEventListener('click', () => {
		alert('Fitur edit sedang dalam pengembangan.');
	});
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
					<div class="cart-thumb cover-a"></div>
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
		const qtyWrappers = cartListContainer.querySelectorAll('[data-qty]');
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
			subtotal += parseInt(item.price, 10) * item.qty;
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

// User menu dropdown toggle
const userMenuTrigger = document.querySelector('[data-user-menu-trigger]');
const userMenuWrapper = document.querySelector('[data-user-menu-wrapper]');

if (userMenuTrigger && userMenuWrapper) {
	userMenuTrigger.addEventListener('click', (event) => {
		event.stopPropagation();
		userMenuWrapper.classList.toggle('is-open');
	});

	// Close menu when clicking outside
	document.addEventListener('click', (event) => {
		if (!userMenuWrapper.contains(event.target)) {
			userMenuWrapper.classList.remove('is-open');
		}
	});
}
