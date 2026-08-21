from playwright.sync_api import sync_playwright

def run_cuj(page):
    page.goto("http://127.0.0.1:8000/login")
    page.wait_for_timeout(500)

    # Login as manager
    page.fill('input[name="email"]', 'accralions@league.com')
    page.fill('input[name="password"]', 'password')
    page.click('button[type="submit"]')
    page.wait_for_timeout(1000)

    # Navigate to AI Scouting
    page.goto("http://127.0.0.1:8000/manager/scouting/ai")
    page.wait_for_timeout(1000)

    page.screenshot(path="/home/jules/verification/screenshots/ai_scouting.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
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
