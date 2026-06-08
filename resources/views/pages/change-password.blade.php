@extends('layouts.recorda')

@section('title', 'Recorda - Ubah Kata Sandi')
@section('body_class', 'page-change-password')

@section('content')
<section class="change-password-container reveal">
    <div class="change-password-box">
        <div>
            <h1>Ubah Kata Sandi</h1>
            <p class="muted">Perbarui kata sandi akun Recorda-mu untuk keamanan maksimal.</p>
        </div>
        <a class="text-link" href="{{ route('recorda.account') }}">← Kembali ke profil</a>
    </div>

    <div class="change-password-form">
        <form class="form-stack" data-change-password-form>
            <label class="form-control">
                <span>Kata Sandi Saat Ini</span>
                <div class="input-with-button">
                    <input class="input" id="current-password" type="password" placeholder="Masukkan kata sandi saat ini" required>
                    <button class="password-toggle" type="button" data-password-toggle data-target="current-password">Lihat</button>
                </div>
                <p class="form-helper"><a class="text-link" href="{{ route('recorda.forgot') }}">Lupa kata sandi?</a></p>
            </label>

            <label class="form-control">
                <span>Kata Sandi Baru</span>
                <div class="input-with-button">
                    <input class="input" id="new-password" type="password" placeholder="Buat kata sandi baru" required data-password-strength>
                    <button class="password-toggle" type="button" data-password-toggle data-target="new-password">Lihat</button>
                </div>
                <div class="password-meter" data-password-meter>
                    <div style="--strength: 0%;"></div>
                </div>
                <p class="form-feedback" data-password-label></p>
            </label>

            <label class="form-control">
                <span>Konfirmasi Kata Sandi Baru</span>
                <div class="input-with-button">
                    <input class="input" id="confirm-password" type="password" placeholder="Ketik ulang kata sandi baru" required>
                    <button class="password-toggle" type="button" data-password-toggle data-target="confirm-password">Lihat</button>
                </div>
            </label>

            <div class="password-requirements">
                <p class="muted">Kata sandi minimal harus:</p>
                <ul>
                    <li><span class="requirement-check">✓</span> 8 karakter</li>
                    <li><span class="requirement-check">✓</span> Huruf besar (A-Z)</li>
                    <li><span class="requirement-check">✓</span> Huruf kecil (a-z)</li>
                    <li><span class="requirement-check">✓</span> Angka (0-9)</li>
                </ul>
            </div>

            <p class="form-feedback" data-change-password-feedback aria-live="polite"></p>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Ubah Kata Sandi</button>
                <a class="btn btn-ghost" href="{{ route('recorda.account') }}">Batalkan</a>
            </div>
        </form>
    </div>

    <div class="security-tips reveal">
        <div>
            <h2>Tips Keamanan</h2>
            <ul>
                <li>Gunakan kombinasi huruf, angka, dan simbol</li>
                <li>Hindari menggunakan informasi pribadi seperti nama atau tanggal lahir</li>
                <li>Jangan gunakan kata sandi yang sama di platform lain</li>
                <li>Ubah kata sandi secara berkala (minimal setiap 3 bulan)</li>
                <li>Jika akun terasa tidak aman, ubah kata sandi segera</li>
            </ul>
        </div>
    </div>
</section>

<style>
.change-password-container {
    max-width: 600px;
    margin: 48px auto;
    padding: 0 24px;
}

.change-password-box {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
}

.change-password-box h1 {
    margin-bottom: 8px;
}

.change-password-form {
    background: var(--cream-50);
    padding: 32px;
    border-radius: var(--radius-lg);
    margin-bottom: 48px;
}

.input-with-button {
    display: flex;
    gap: 8px;
}

.input-with-button .input {
    flex: 1;
}

.password-toggle {
    padding: 8px 16px;
    background: var(--white);
    border: 1px solid rgba(59, 49, 42, 0.15);
    border-radius: 8px;
    color: var(--ink-800);
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    transition: background var(--transition);
}

.password-toggle:hover {
    background: rgba(59, 49, 42, 0.05);
}

.password-meter {
    height: 6px;
    background: rgba(59, 49, 42, 0.1);
    border-radius: 3px;
    margin-top: 8px;
    overflow: hidden;
}

.password-meter > div {
    height: 100%;
    background: linear-gradient(90deg, #c75b35, #b14828);
    width: var(--strength, 0%);
    transition: width var(--transition);
}

.form-feedback {
    font-size: 12px;
    margin-top: 8px;
    color: var(--ink-700);
}

.password-requirements {
    background: var(--white);
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 16px;
    border: 1px solid rgba(59, 49, 42, 0.1);
}

.password-requirements p {
    margin: 0 0 12px 0;
}

.password-requirements ul {
    margin: 0;
    padding: 0;
    list-style: none;
    display: grid;
    gap: 8px;
}

.password-requirements li {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: var(--ink-700);
}

.requirement-check {
    display: inline-block;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: rgba(59, 49, 42, 0.1);
    text-align: center;
    line-height: 16px;
    font-size: 12px;
    color: var(--ink-700);
    flex-shrink: 0;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.security-tips {
    background: var(--cream-100);
    padding: 24px;
    border-radius: var(--radius-lg);
    border-left: 4px solid var(--terracotta-500);
}

.security-tips h2 {
    margin-top: 0;
    font-size: 18px;
}

.security-tips ul {
    margin: 0;
    padding: 0 0 0 24px;
}

.security-tips li {
    margin-bottom: 8px;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .change-password-box {
        flex-direction: column;
        gap: 16px;
    }

    .change-password-form {
        padding: 24px;
    }

    .form-actions {
        flex-direction: column;
    }

    .form-actions .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-change-password-form]');
    const currentPassword = document.getElementById('current-password');
    const newPassword = document.getElementById('new-password');
    const confirmPassword = document.getElementById('confirm-password');
    const feedback = document.querySelector('[data-change-password-feedback]');

    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const current = currentPassword ? currentPassword.value.trim() : '';
        const newPwd = newPassword ? newPassword.value.trim() : '';
        const confirm = confirmPassword ? confirmPassword.value.trim() : '';

        // Reset feedback
        if (feedback) {
            feedback.textContent = '';
            feedback.classList.remove('is-success', 'is-error');
        }

        // Validation
        if (!current || !newPwd || !confirm) {
            if (feedback) {
                feedback.textContent = 'Semua field harus diisi.';
                feedback.classList.add('is-error');
            }
            return;
        }

        if (newPwd.length < 8) {
            if (feedback) {
                feedback.textContent = 'Kata sandi baru minimal 8 karakter.';
                feedback.classList.add('is-error');
            }
            return;
        }

        if (newPwd !== confirm) {
            if (feedback) {
                feedback.textContent = 'Kata sandi baru tidak cocok dengan konfirmasi.';
                feedback.classList.add('is-error');
            }
            return;
        }

        if (current === newPwd) {
            if (feedback) {
                feedback.textContent = 'Kata sandi baru tidak boleh sama dengan yang sekarang.';
                feedback.classList.add('is-error');
            }
            return;
        }

        // Dummy validation - in demo, current password is "recorda123"
        if (current !== 'recorda123') {
            if (feedback) {
                feedback.textContent = 'Kata sandi saat ini tidak sesuai. (Demo: recorda123)';
                feedback.classList.add('is-error');
            }
            return;
        }

        // Success
        if (feedback) {
            feedback.textContent = 'Kata sandi berhasil diubah. Mengalihkan...';
            feedback.classList.add('is-success');
        }

        setTimeout(() => {
            window.location.href = '/akun';
        }, 1000);
    });
});
</script>
@endsection
