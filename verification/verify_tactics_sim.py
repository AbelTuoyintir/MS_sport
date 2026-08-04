import os
from playwright.sync_api import sync_playwright

def run_cuj(page):
    # 1. Login
    print("Navigating to login...")
    page.goto("http://localhost:8000/login")
    page.wait_for_timeout(1000)

    print("Logging in as manager...")
    page.fill('input[name="email"]', 'accralions@league.com')
    page.fill('input[name="password"]', 'password')
    page.get_by_role("button", name="Sign In").click()
    page.wait_for_timeout(1000)

    # 2. Go to tactics page
    print("Navigating to tactics page...")
    page.goto("http://localhost:8000/manager/tactics")
    page.wait_for_timeout(1000)

    # 3. Choose opponent and start simulation
    print("Selecting opponent...")
    page.select_option('#opponent-select', label='Cape Coast Stars')
    page.wait_for_timeout(1000)

    print("Launching friendly simulation...")
    page.click('#start-simulation-btn')

    # Wait for simulation to complete (approx 12-15 seconds for step-by-step commentary logs)
    print("Simulation running, waiting for commentary...")
    page.wait_for_timeout(12000)

    # 4. Take final screenshot
    print("Taking screenshot of completed simulator...")
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    page.screenshot(path="/home/jules/verification/screenshots/tactics_sim_completed.png", full_page=True)
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    os.makedirs("/home/jules/verification/videos", exist_ok=True)
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            record_video_dir="/home/jules/verification/videos",
            viewport={'width': 1280, 'height': 800}
        )
        page = context.new_page()
        try:
            run_cuj(page)
        finally:
            context.close()
            browser.close()
            print("Finished simulation verification.")
