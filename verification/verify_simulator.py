import asyncio
from playwright.async_api import async_playwright
import os

async def verify_simulator():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={'width': 1280, 'height': 1500})
        page = await context.new_page()

        print("--- Verifying Tactical Simulator ---")

        # Login as manager
        await page.goto("http://localhost:8000/login")
        await page.fill("input[name='email']", "accralions@league.com")
        await page.fill("input[name='password']", "password")
        await page.click("button[type='submit']")

        await page.wait_for_url("**/manager/dashboard")
        print("Logged in successfully.")

        # Go to tactics page
        await page.goto("http://localhost:8000/manager/tactics")
        print("Navigated to tactics page.")

        # Click on Tactical Match Simulator
        await page.click("text=Tactical Match Simulator")
        await page.wait_for_url("**/manager/tactics/simulate")
        print("Navigated to Tactical Match Simulator form page.")

        # Select strategy "attacking" by clicking its text
        await page.click("text=Attacking")
        # Select first available opponent team option in the select dropdown
        await page.select_option("select[name='opponent_team_id']", index=1)
        print("Configured strategy and selected opponent.")

        # Click Simulate Match button
        await page.click("text=Simulate Match")

        await page.wait_for_selector("text=Match Statistics")
        print("Simulation executed successfully!")

        # Take a screenshot of the simulation result
        await page.screenshot(path="verification/screenshots/simulator_results.png", full_page=True)
        print("Screenshot of simulator results saved to verification/screenshots/simulator_results.png")

        await browser.close()

if __name__ == "__main__":
    if not os.path.exists("verification/screenshots"):
        os.makedirs("verification/screenshots")
    asyncio.run(verify_simulator())
