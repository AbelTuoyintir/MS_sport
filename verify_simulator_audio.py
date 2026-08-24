import os
import time
from playwright.sync_api import sync_playwright

def run_verification(page):
    page.goto("http://127.0.0.1:8000/matches/1")
    page.wait_for_timeout(1000)

    # Click start simulation button
    start_btn = page.locator("#start-sim-btn")
    if start_btn.is_visible():
        start_btn.click()
        page.wait_for_timeout(1000)

    # Cheer home & away
    page.locator("#cheer-home-btn").click()
    page.wait_for_timeout(500)
    page.locator("#cheer-away-btn").click()
    page.wait_for_timeout(500)

    # Toggle Audio
    audio_btn = page.locator("#sim-audio-btn")
    if audio_btn.is_visible():
        audio_btn.click()
        page.wait_for_timeout(500)
        audio_btn.click()
        page.wait_for_timeout(500)

    # Take screenshot
    page.screenshot(path="/home/jules/verification/screenshots/match_simulator_audio.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()
        try:
            run_verification(page)
        finally:
            context.close()
            browser.close()
