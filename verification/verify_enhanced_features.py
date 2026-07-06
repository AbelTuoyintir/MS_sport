from playwright.sync_api import sync_playwright, expect

def verify_stats_page(page):
    print("Verifying Stats page...")
    page.goto("http://localhost:8000/stats")
    expect(page.get_by_role("heading", name="League Statistics")).to_be_visible()
    page.screenshot(path="verification/screenshots/stats_page.png")
    print("Stats page verified.")

def verify_match_h2h(page):
    print("Verifying Match H2H...")
    # Find a finished match to see H2H
    page.goto("http://localhost:8000")
    page.click("text=Results")
    page.wait_for_timeout(1000)
    match_card = page.locator("#matches-grid > div").first
    match_card.click()

    expect(page.get_by_role("heading", name="Head to Head")).to_be_visible()
    page.screenshot(path="verification/screenshots/match_h2h.png")
    print("Match H2H verified.")

def verify_manager_tactics(page):
    print("Verifying Manager Tactics...")
    page.goto("http://localhost:8000/login")
    page.fill('input[name="email"]', 'accralions@league.com')
    page.fill('input[name="password"]', 'password')
    page.click('button[type="submit"]')

    page.goto("http://localhost:8000/manager/tactics")
    expect(page.get_by_role("heading", name="Manage Tactics")).to_be_visible()

    # Change formation
    page.select_option('select[name="formation"]', '4-3-3')

    # Select 11 players (assuming seeder added enough)
    checkboxes = page.locator('input[type="checkbox"]')
    for i in range(11):
        checkboxes.nth(i).check()

    page.screenshot(path="verification/screenshots/manager_tactics_before_save.png")
    page.click('button:has-text("Save Tactics")')

    expect(page.locator("text=Tactics updated successfully.")).to_be_visible()
    page.screenshot(path="verification/screenshots/manager_tactics_after_save.png")
    print("Manager Tactics verified.")

if __name__ == "__main__":
    import os
    if not os.path.exists("verification/screenshots"):
        os.makedirs("verification/screenshots")

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        try:
            verify_stats_page(page)
            verify_match_h2h(page)
            verify_manager_tactics(page)
        except Exception as e:
            print(f"Verification failed: {e}")
            page.screenshot(path="verification/screenshots/error.png")
        finally:
            browser.close()
