import os
from playwright.sync_api import sync_playwright

def run_cuj(page):
    # Set viewport to desktop
    page.set_viewport_size({"width": 1280, "height": 800})

    # Navigate to homepage
    page.goto("http://127.0.0.1:8000")
    page.wait_for_timeout(1000)

    # Click desktop Power Rankings link
    page.goto("http://127.0.0.1:8000/rankings")
    page.wait_for_timeout(1000)

    # Scroll down through Rankings page to show CPI Leaderboard
    page.evaluate("window.scrollBy(0, 300)")
    page.wait_for_timeout(800)

    # Take screenshot at key moment
    page.screenshot(path="/home/jules/verification/screenshots/club_rankings.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    os.makedirs("/home/jules/verification/videos", exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()
        try:
            run_cuj(page)
        finally:
            context.close()
            browser.close()
