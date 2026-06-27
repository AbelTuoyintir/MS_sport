const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage();

  // Set viewport to a common desktop size
  await page.setViewportSize({ width: 1280, height: 720 });

  // 1. Login
  await page.goto('http://127.0.0.1:8000/login');
  await page.fill('input[name="email"]', 'manager@test.com');
  await page.fill('input[name="password"]', 'password');
  await page.click('button[type="submit"]');

  await page.waitForURL('**/manager/dashboard');

  // 2. Take screenshot of dashboard
  await page.screenshot({ path: 'manager_dashboard.png' });

  // 3. Open Add Player modal
  await page.click('button:has-text("Add Player")');
  await page.waitForSelector('#addPlayerModal', { state: 'visible' });
  await page.screenshot({ path: 'manager_modal.png' });

  await browser.close();
})();
