// Rekam recorda-mockup-reel.html jadi video .webm pakai Playwright.
// Login admin dulu agar halaman /dashboard, /kelola-produk, /kelola-transaksi tampil asli.
const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const BASE = 'https://recorda-production-4424.up.railway.app';
const ADMIN = { email: 'admin@recorda.com', password: 'recorda123' };

(async () => {
  const VIEW = { width: 1440, height: 900 };
  const fileUrl = 'file://' + path.resolve(__dirname, 'recorda-mockup-reel.html').replace(/\\/g, '/');
  const outDir = path.resolve(__dirname, 'mockup-video');
  fs.mkdirSync(outDir, { recursive: true });

  // Durasi tiap scene (samakan dgn array SCENES di html)
  const SCENE_MS = [7000, 7000, 6500, 6500, 6500, 5500, 5500, 7000, 6500, 6500, 6500];
  const total = SCENE_MS.reduce((a, b) => a + b, 0);
  const RECORD_MS = total + 4000;

  const browser = await chromium.launch();

  // 1) Login admin di context terpisah -> ambil cookie/session
  console.log('Login admin...');
  const authCtx = await browser.newContext({ viewport: VIEW });
  const lp = await authCtx.newPage();
  await lp.goto(BASE + '/login', { waitUntil: 'load' });
  await lp.fill('input[name="email"]', ADMIN.email);
  await lp.fill('input[name="password"]', ADMIN.password);
  await Promise.all([
    lp.waitForLoadState('load'),
    lp.click('button[type="submit"]'),
  ]).catch(() => {});
  await lp.waitForTimeout(1500);
  const loggedIn = !/\/login/.test(lp.url());
  console.log('Status login admin:', loggedIn ? 'BERHASIL (' + lp.url() + ')' : 'GAGAL (tetap di login)');
  const storageState = await authCtx.storageState();
  await authCtx.close();

  // 2) Context perekaman dgn session admin
  console.log('Mulai rekam ~', Math.round(RECORD_MS / 1000), 'detik...');
  const context = await browser.newContext({
    viewport: VIEW,
    recordVideo: { dir: outDir, size: VIEW },
    storageState,
  });
  const page = await context.newPage();
  await page.goto(fileUrl, { waitUntil: 'load' });
  const video = page.video();
  await page.waitForTimeout(RECORD_MS);
  await context.close(); // flush video

  // 3) Rapikan nama file
  let saved = null;
  if (video) { try { saved = await video.path(); } catch (e) {} }
  if (!saved) {
    const files = fs.readdirSync(outDir).filter(f => f.endsWith('.webm'));
    if (files.length) saved = path.join(outDir, files.sort().pop());
  }
  if (saved && fs.existsSync(saved)) {
    const dest = path.join(outDir, 'recorda-mockup.webm');
    try { if (path.resolve(saved) !== path.resolve(dest)) fs.renameSync(saved, dest); saved = dest; } catch (e) {}
    const mb = (fs.statSync(saved).size / 1048576).toFixed(1);
    console.log('SELESAI ->', saved, '(' + mb + ' MB)');
  } else {
    console.log('Tidak ada file video dihasilkan.');
  }
  await browser.close();
})();
