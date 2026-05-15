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

	const setFeedback = (message, isSuccess) => {
		if (!feedback) {
			return;
		}

		feedback.textContent = message;
		feedback.classList.toggle('is-success', Boolean(isSuccess));
	};

	dummyLoginForm.addEventListener('submit', (event) => {
		event.preventDefault();

		const email = emailInput ? emailInput.value.trim() : '';
		const password = passwordInput ? passwordInput.value.trim() : '';

		if (!email || !password) {
			setFeedback('Email dan password harus diisi.', false);
			return;
		}

		setFeedback('Login dummy berhasil, mengalihkan...', true);

		if (submitButton) {
			submitButton.disabled = true;
		}

		const redirectTarget = dummyLoginForm.dataset.successRedirect || '/home';
		window.setTimeout(() => {
			window.location.href = redirectTarget;
		}, 900);
	});
}
