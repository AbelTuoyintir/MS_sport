import asyncio
from playwright.async_api import async_playwright
import os

async def verify_fan():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        print("--- Verifying Fan Experience ---")
        await page.goto("http://localhost:8000")

        # Check title
        title = await page.title()
        print(f"Page Title: {title}")

        # Check Hero
        await page.wait_for_selector("text=The Premier")
        print("Hero section loaded.")

        # Check Standings
        await page.wait_for_selector("#public-table")
        standings_text = await page.inner_text("#public-table")
        print(f"Standings loaded: {'Table has content' if len(standings_text) > 50 else 'Table empty'}")

        # Check Top Scorers
        await page.wait_for_selector("#public-scorers")
        scorers_text = await page.inner_text("#public-scorers")
        print(f"Top Scorers loaded: {'Has content' if len(scorers_text) > 10 else 'Empty'}")

        # Check Matches and Prediction
        await page.wait_for_selector("#matches-grid")

        # Find an upcoming match and click predict
        # Note: Switching to upcoming tab
        tabs = await page.query_selector_all(".mtab")
        for tab in tabs:
            tab_text = await tab.inner_text()
            if "Upcoming" in tab_text:
                await tab.click()
                print("Switched to Upcoming matches tab.")
                break

        await asyncio.sleep(1) # Wait for animation/render

        predict_button = await page.query_selector("text=Predict Score")
        if predict_button:
            await predict_button.click()
            print("Clicked Predict Score button.")

            # Wait for the prediction form fields to appear
            await page.wait_for_selector("input[placeholder='Your Name']")
            await page.wait_for_selector("input[placeholder='Home']")
            await page.wait_for_selector("input[placeholder='Away']")
            print("Prediction form appeared correctly.")

            # Take a screenshot of the prediction form
            await page.screenshot(path="verification/screenshots/fan_prediction_form.png")
        else:
            print("No upcoming matches with prediction button found.")
            await page.screenshot(path="verification/screenshots/fan_no_upcoming.png")

        # Check News
        await page.wait_for_selector("#news-grid")
        news_count = await page.locator("#news-grid > div").count()
        print(f"News articles found: {news_count}")

        await page.screenshot(path="verification/screenshots/fan_homepage_full.png", full_page=True)
        await browser.close()

if __name__ == "__main__":
    if not os.path.exists("verification/screenshots"):
        os.makedirs("verification/screenshots")
    asyncio.run(verify_fan())
