import asyncio
from playwright.async_api import async_playwright
import os

async def verify_match_simulator():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        print("Navigating to Match Details...")
        await page.goto("http://localhost:8000/matches/1")

        # Verify simulator is on page
        print("Checking for simulator container...")
        await page.wait_for_selector("#apex-simulator-container")

        # Click the start simulation button
        print("Clicking Start Simulation button...")
        await page.click("#start-sim-btn")

        # Wait a bit for clock and commentary to run
        print("Waiting for simulation to run...")
        await asyncio.sleep(2.5)

        # Click the cheer buttons
        print("Cheering for teams...")
        await page.click("#cheer-home-btn")
        await page.click("#cheer-home-btn")
        await page.click("#cheer-away-btn")

        await asyncio.sleep(1.5)

        # Take screenshot of the simulator specifically
        os.makedirs('verification/screenshots', exist_ok=True)
        simulator_elem = page.locator("#apex-simulator-container")
        await simulator_elem.screenshot(path='verification/screenshots/match_day_simulator.png')
        print("Screenshot saved to verification/screenshots/match_day_simulator.png")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify_match_simulator())
