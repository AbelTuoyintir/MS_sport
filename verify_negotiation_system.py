import asyncio
from playwright.async_api import async_playwright
import os

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        os.makedirs("verification/screenshots", exist_ok=True)

        # 1. Login as Admin and view Admin Scouts Registry
        await page.goto("http://localhost:8000/login")
        await page.fill('input[name="email"]', "admin@league.com")
        await page.fill('input[name="password"]', "password")
        await page.click('button[type="submit"]')
        await page.wait_for_timeout(1000)

        await page.goto("http://localhost:8000/admin/scouts")
        await page.wait_for_timeout(1000)
        await page.screenshot(path="verification/screenshots/admin_scout_registry.png")

        # 2. Login as Manager and view Manager Scouts Hub
        await page.goto("http://localhost:8000/login")
        await page.fill('input[name="email"]', "accralions@league.com")
        await page.fill('input[name="password"]', "password")
        await page.click('button[type="submit"]')
        await page.wait_for_timeout(1000)

        await page.goto("http://localhost:8000/manager/scouts")
        await page.wait_for_timeout(1000)
        await page.screenshot(path="verification/screenshots/manager_scouts_hub.png")

        # 3. View Manager Transfers & Loans Hub
        await page.goto("http://localhost:8000/manager/transfers")
        await page.wait_for_timeout(1000)
        await page.screenshot(path="verification/screenshots/manager_transfers_hub.png")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(main())
