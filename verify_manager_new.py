import asyncio
from playwright.async_api import async_playwright
import os

async def verify_manager_dashboard():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        print("--- Verifying Manager Dashboard ---")

        # Login as manager
        await page.goto("http://localhost:8000/login")
        await page.fill("input[name='email']", "accralions@league.com")
        await page.fill("input[name='password']", "password")
        await page.click("button[type='submit']")

        await page.wait_for_url("**/manager/dashboard")
        print("Logged in as manager.")

        # Check for new stats
        await page.wait_for_selector("text=Squad")
        await page.wait_for_selector("text=Goals")
        await page.wait_for_selector("text=Rating")
        print("New statistics cards found.")

        # Check for Squad List
        await page.wait_for_selector("#players-list")
        print("Squad list table found.")

        # Take screenshot
        await page.screenshot(path="verification/screenshots/manager_dashboard.png", full_page=True)
        print("Screenshot saved to verification/screenshots/manager_dashboard.png")

        await browser.close()

if __name__ == "__main__":
    if not os.path.exists("verification/screenshots"):
        os.makedirs("verification/screenshots")
    asyncio.run(verify_manager_dashboard())
