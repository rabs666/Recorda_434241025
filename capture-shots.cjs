// Tangkap screenshot tiap halaman Recorda (desktop 1440 + mobile 390),
// login admin dulu agar halaman admin tampil asli (bukan redirect login).
const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const BASE = 'https://recorda-production-4424.up.railway.app';
const ADMIN = { email: 'admin@recorda.com', password: 'recorda123' };

// name -> dipakai utk nama file; path -> URL; auth -> butuh sesi admin
const PAGES = [
  { name: 'beranda',    path: '/' },
  { name: 'katalog',    path: '/katalog' },
  { name: 'artikel',    path: '/arsip-artikel' },
  { name: 'keranjang',  path: '/keranjang' },
  { name: 'login',      path: '/login' },
  { name: 'register',   path: '/register' },
  { name: 'dashboard',  path: '/dashboard',        auth: true },
  { name: 'produk',     path: '/kelola-produk',    auth: true },
  { name: 'transaksi',  path: '/kelola-transaksi', auth: true },
];

(async () => {
  const outDir = path.resolve(__dirname, 'mockup-video', 'shots');
  fs.mkdirSync(outDir, { recursive: true });
  const browser = await chromium.launch();

  // login admin
  console.log('Login admin...');
  const authCtx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const lp = await authCtx.newPage();
  await lp.goto(BASE + '/login', { waitUntil: 'load' });
  await lp.fill('input[name="email"]', ADMIN.email);
  await lp.fill('input[name="password"]', ADMIN.password);
  await Promise.all([lp.waitForLoadState('load'), lp.click('button[type="submit"]')]).catch(() => {});
  await lp.waitForTimeout(1500);
  console.log('Login admin:', /\/login/.test(lp.url()) ? 'GAGAL' : 'BERHASIL');
  const storageState = await authCtx.storageState();
  await authCtx.close();

  // dua context: tamu (publik) & admin (auth)
  const guest = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const admin = await browser.newContext({ viewport: { width: 1440, height: 900 }, storageState });

  async function shoot(ctx, p, device, vp) {
    const page = await ctx.newPage();
    await page.setViewportSize(vp);
    await page.goto(BASE + p.path, { waitUntil: 'networkidle' }).catch(() => {});
    await page.waitForTimeout(1400); // animasi/lazy load
    const file = path.join(outDir, `${p.name}-${device}.png`);
    await page.screenshot({ path: file, fullPage: true });
    const h = await page.evaluate(() => document.body.scrollHeight);
    await page.close();
    console.log(`  ${p.name}-${device}.png  (tinggi ~${h}px)`);
    return { file: `${p.name}-${device}.png`, height: h };
  }

  const meta = {};
  for (const p of PAGES) {
    const ctx = p.auth ? admin : guest;
    console.log('Capture', p.name, '...');
    const d = await shoot(ctx, p, 'd', { width: 1440, height: 900 });
    const m = await shoot(ctx, p, 'm', { width: 390, height: 844 });
    meta[p.name] = { d, m };
  }

  fs.writeFileSync(path.join(outDir, 'shots.json'), JSON.stringify(meta, null, 2));
  console.log('Metadata ->', path.join(outDir, 'shots.json'));
  await browser.close();
  console.log('SELESAI capture.');
})();
