from playwright.sync_api import sync_playwright

def run_cuj(page):
    page.goto("http://127.0.0.1:8000/tournaments")
    page.wait_for_timeout(1000)

    # Fill predictor form
    page.fill("input[name='user_name']", "CupChampionTester")
    page.wait_for_timeout(500)

    page.select_option("select[name='predicted_champion']", index=0)
    page.wait_for_timeout(500)

    page.fill("input[name='final_score_home']", "3")
    page.wait_for_timeout(500)

    page.fill("input[name='final_score_away']", "1")
    page.wait_for_timeout(500)

    page.click("button[type='submit']")
    page.wait_for_timeout(1500)

    page.screenshot(path="/home/jules/verification/screenshots/tournaments_verification.png")
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
