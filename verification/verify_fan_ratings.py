import asyncio
from playwright.async_api import async_playwright
import os

async def verify_fan_ratings():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={'width': 1280, 'height': 900})
        page = await context.new_page()

        print("--- Verifying Match Details & Fan Ratings ---")

        # Navigate to a finished match details page
        await page.goto("http://localhost:8000/matches/1")
        print("Match details page loaded successfully.")

        # Rate a player
        # Find the first rate player form select and button
        await page.select_option("select[name='rating']", "9")
        print("Selected 9 rating.")

        # Click the submit button inside that form
        await page.click("button[type='submit'] >> text=Submit")
        print("Clicked submit player rating.")

        await page.wait_for_timeout(2000)

        # Confirm success alert or average rating displayed
        await page.wait_for_selector("text=⭐")
        print("Average rating display confirmed!")

        # Take screenshot
        screenshot_path = "verification/screenshots/fan_ratings_upgrade.png"
        await page.screenshot(path=screenshot_path, full_page=True)
        print(f"Screenshot saved to {screenshot_path}")

        await browser.close()

if __name__ == "__main__":
    if not os.path.exists("verification/screenshots"):
        os.makedirs("verification/screenshots")
    asyncio.run(verify_fan_ratings())
