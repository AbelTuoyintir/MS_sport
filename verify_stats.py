import asyncio
from playwright.async_api import async_playwright
import os

async def verify_stats_page():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        print("Navigating to League Statistics page...")
        await page.goto("http://localhost:8000/stats")
        await page.wait_for_selector("text=League Statistics")
        print("Stats page loaded successfully.")

        # Take screenshot of the Attacking tab
        os.makedirs('verification/screenshots', exist_ok=True)
        await page.screenshot(path='verification/screenshots/stats_attacking.png', full_page=True)
        print("Attacking stats screenshot saved.")

        # Switch to Defending tab
        await page.click("#btn-defending")
        await page.wait_for_timeout(500)
        await page.screenshot(path='verification/screenshots/stats_defending.png', full_page=True)
        print("Defending stats screenshot saved.")

        # Switch to Discipline tab
        await page.click("#btn-discipline")
        await page.wait_for_timeout(500)
        await page.screenshot(path='verification/screenshots/stats_discipline.png', full_page=True)
        print("Discipline stats screenshot saved.")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify_stats_page())
