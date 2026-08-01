import asyncio
from playwright.async_api import async_playwright
import os

async def verify_stats_and_predictions():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        print("--- Verifying Stats Page & Prediction Leaderboard ---")

        # 1. Navigate to the stats page
        await page.goto("http://localhost:8000/stats")
        await page.wait_for_selector("text=League Hub")
        print("Stats page loaded successfully.")

        # Take screenshot of the "Player Statistics" tab
        await page.screenshot(path="verification/screenshots/stats_player_stats.png", full_page=True)
        print("Screenshot of Player Statistics saved.")

        # 2. Click the "Prediction Leaderboard" tab button
        leaderboard_btn = await page.query_selector("#predictions-tab-btn")
        if leaderboard_btn:
            await leaderboard_btn.click()
            print("Clicked Prediction Leaderboard tab.")
            await asyncio.sleep(1) # Wait for tab switch rendering

            # Take screenshot of the "Prediction Leaderboard" tab
            await page.screenshot(path="verification/screenshots/stats_prediction_leaderboard.png", full_page=True)
            print("Screenshot of Prediction Leaderboard saved.")
        else:
            print("Prediction Leaderboard button not found.")

        await browser.close()

if __name__ == "__main__":
    if not os.path.exists("verification/screenshots"):
        os.makedirs("verification/screenshots")
    asyncio.run(verify_stats_and_predictions())
