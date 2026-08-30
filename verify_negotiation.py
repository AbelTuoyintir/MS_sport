import asyncio
from playwright.async_api import async_playwright
import os

async def verify_negotiation_system():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        print("--- Verifying Scouting Agent & Negotiation System ---")

        # 1. Login as Admin & Verify Scout Registry
        await page.goto("http://127.0.0.1:8000/login")
        await page.fill("input[name='email']", "admin@mpleague.com")
        await page.fill("input[name='password']", "password")
        await page.click("button[type='submit']")
        await page.wait_for_url("**/admin/dashboard")
        print("Logged in as Admin.")

        await page.goto("http://127.0.0.1:8000/admin/scouts")
        await page.wait_for_selector("text=Scouting Agent Registry")
        await page.screenshot(path="verification/screenshots/admin_scout_registry.png")
        print("Admin Scout Registry loaded & screenshot captured.")

        # 2. Login as Manager & Verify Scout Hub
        await page.goto("http://127.0.0.1:8000/login")
        await page.fill("input[name='email']", "accralions@league.com")
        await page.fill("input[name='password']", "password")
        await page.click("button[type='submit']")
        await page.wait_for_url("**/manager/dashboard")
        print("Logged in as Manager.")

        await page.goto("http://127.0.0.1:8000/manager/scouts")
        await page.wait_for_selector("text=Manager Scouting & Talent Submission Hub")
        await page.screenshot(path="verification/screenshots/manager_scout_hub.png")
        print("Manager Scout Hub loaded & screenshot captured.")

        # 3. Verify Transfer & Negotiation Market
        await page.goto("http://127.0.0.1:8000/manager/transfers")
        await page.wait_for_selector("text=Transfer & Negotiation Hub")
        await page.screenshot(path="verification/screenshots/manager_transfer_hub.png")
        print("Manager Transfer & Negotiation Hub loaded & screenshot captured.")

        await browser.close()

if __name__ == "__main__":
    if not os.path.exists("verification/screenshots"):
        os.makedirs("verification/screenshots")
    asyncio.run(verify_negotiation_system())
