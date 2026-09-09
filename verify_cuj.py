import os
import glob
from playwright.sync_api import sync_playwright

def run_cuj(page):
    # 1. Login as Admin & view scouts
    page.goto("http://127.0.0.1:8000/login")
    page.wait_for_timeout(500)
    page.fill('input[name="email"]', 'admin@mpleague.com')
    page.wait_for_timeout(300)
    page.fill('input[name="password"]', 'admin123')
    page.wait_for_timeout(300)
    page.click('button[type="submit"]')
    page.wait_for_timeout(800)

    page.goto("http://127.0.0.1:8000/admin/scouts")
    page.wait_for_timeout(1000)

    # 2. Login as Manager & view scout network & transfers
    page.goto("http://127.0.0.1:8000/login")
    page.wait_for_timeout(500)
    page.fill('input[name="email"]', 'accralions@league.com')
    page.wait_for_timeout(300)
    page.fill('input[name="password"]', 'password')
    page.wait_for_timeout(300)
    page.click('button[type="submit"]')
    page.wait_for_timeout(800)

    page.goto("http://127.0.0.1:8000/manager/scouts")
    page.wait_for_timeout(1000)

    page.goto("http://127.0.0.1:8000/manager/transfers")
    page.wait_for_timeout(1000)

    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    page.screenshot(path="/home/jules/verification/screenshots/negotiation_system.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
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
