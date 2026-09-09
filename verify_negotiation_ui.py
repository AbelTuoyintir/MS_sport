import asyncio
from playwright.async_api import async_playwright
import os

async def main():
    os.makedirs('verification/screenshots', exist_ok=True)
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page()

        # 1. Login as Admin
        await page.goto('http://127.0.0.1:8000/login')
        await page.fill('input[name="email"]', 'admin@mpleague.com')
        await page.fill('input[name="password"]', 'password')
        await page.click('button[type="submit"]')
        await page.wait_for_load_state('networkidle')

        # Visit Admin Scouting Agent Registry
        await page.goto('http://127.0.0.1:8000/admin/scouts')
        await page.wait_for_load_state('networkidle')
        await page.screenshot(path='verification/screenshots/admin_scouts.png', full_page=True)
        print("Captured admin_scouts.png")

        # 2. Login as Manager
        await page.goto('http://127.0.0.1:8000/login')
        await page.fill('input[name="email"]', 'manager@mpleague.com')
        await page.fill('input[name="password"]', 'password')
        await page.click('button[type="submit"]')
        await page.wait_for_load_state('networkidle')

        # Visit Manager Scout Network Hub
        await page.goto('http://127.0.0.1:8000/manager/scouts')
        await page.wait_for_load_state('networkidle')
        await page.screenshot(path='verification/screenshots/manager_scouts.png', full_page=True)
        print("Captured manager_scouts.png")

        # Visit Manager Transfers & Negotiation Hub
        await page.goto('http://127.0.0.1:8000/manager/transfers')
        await page.wait_for_load_state('networkidle')
        await page.screenshot(path='verification/screenshots/manager_transfers.png', full_page=True)
        print("Captured manager_transfers.png")

        await browser.close()

if __name__ == '__main__':
    asyncio.run(main())
