import asyncio
from playwright.async_api import async_playwright
import os

async def verify_ai_scouting():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={'width': 1280, 'height': 900})
        page = await context.new_page()

        print("--- Verifying Manager AI Scouting Assistant ---")

        # Login as manager
        await page.goto("http://localhost:8000/login")
        await page.fill("input[name='email']", "accralions@league.com")
        await page.fill("input[name='password']", "password")
        await page.click("button[type='submit']")

        # Wait for dashboard
        await page.wait_for_url("**/manager/dashboard")
        print("Logged in successfully as manager.")

        # Navigate to AI Scouting
        await page.goto("http://localhost:8000/manager/scouting/ai")
        print("Navigated to AI Scouting Assistant page.")

        # Select parameters
        # Fill in the form fields
        await page.select_option("select[name='opponent_team_id']", index=1) # select second option (first is empty/default)
        await page.select_option("select[name='opponent_formation']", value="4-3-3")
        await page.select_option("select[name='tactic_style']", value="Tiki-Taka")
        await page.select_option("select[name='intensity']", value="Balanced")

        # Take a pre-generate screenshot
        await page.screenshot(path="verification/screenshots/manager_ai_scouting_form.png")
        print("Captured pre-generate form screenshot.")

        # Click Generate button inside the form to avoid clicking signout!
        await page.click("form[action*='scouting/ai/generate'] button[type='submit']")
        await page.wait_for_selector("text=Executive Scouting Summary")
        print("Tactical report successfully generated!")

        # Take a post-generate screenshot
        await page.screenshot(path="verification/screenshots/manager_ai_scouting_report.png", full_page=True)
        print("Captured post-generate report screenshot.")

        await browser.close()

if __name__ == "__main__":
    if not os.path.exists("verification/screenshots"):
        os.makedirs("verification/screenshots")
    asyncio.run(verify_ai_scouting())
