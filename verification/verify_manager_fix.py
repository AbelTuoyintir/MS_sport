import asyncio
from playwright.async_api import async_playwright
import os

async def verify_manager():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        # Login
        print("Logging in...")
        await page.goto("http://localhost:8000/login")
        await page.fill('input[name="email"]', 'accralions@league.com')
        await page.fill('input[name="password"]', 'password')
        await page.click('button[type="submit"]')

        await page.wait_for_url("http://localhost:8000/manager/dashboard")
        print("Dashboard loaded.")

        # Take screenshot
        os.makedirs('verification/screenshots', exist_ok=True)
        await page.screenshot(path='verification/screenshots/manager_dashboard_fixed.png', full_page=True)
        print("Screenshot saved to verification/screenshots/manager_dashboard_fixed.png")

        # Check for specific elements
        title = await page.inner_text('h1')
        print(f"Page Title: {title}")

        # Verify stats are present
        stats_count = await page.locator('.stat-card').count()
        print(f"Found {stats_count} stat cards.")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify_manager())
