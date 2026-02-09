import { test, expect } from "@playwright/test";

const BASE_URL = "http://localhost:8000";

test.describe("Branding and Notifications Verification", () => {
    test("Verify Branding consistency", async ({ page }) => {
        await page.goto(BASE_URL);

        // 1. Check Footer Branding
        await expect(page.locator("footer")).toContainText(/NetRider/i);

        // 2. Check Welcome Page Hero Branding (subtitle)
        await expect(page.locator("section.bg-slate-900")).toContainText(
            /NetRider/i,
        );

        // 3. Check Nav branding alt text
        const navLogo = page.locator('nav img[alt*="NetRider"]');
        await expect(navLogo).toBeVisible();
    });

    test("Verify Custom Modal in Student Directory", async ({ page }) => {
        // Login as Admin
        await page.goto(`${BASE_URL}/login`);
        await page.fill('input[name="username"]', 'Super Admin');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');

        // Check if redirect worked
        await expect(page).not.toHaveURL(/login/);

        // Go to Student Directory
        await page.goto(`${BASE_URL}/admin/users`);

        // Trigger Delete Modal
        const deleteButton = page.locator('button[aria-label="Delete Student"]').first();
        try {
            await deleteButton.click({ timeout: 5000 });
        } catch (e) {
            await page.screenshot({ path: 'test-results/delete-button-fail.png' });
            throw e;
        }

        // Check if custom modal is visible
        const modal = page.locator('div[x-show="isOpen"]');
        await expect(modal).toBeVisible();
        await expect(modal).toContainText(/Delete/i);
        await expect(modal).toHaveClass(/bg-slate-900/);

        // Verify dynamic border (type: danger should have border-l-[#E2231A])
        await expect(modal).toHaveClass(/border-l-\[#E2231A\]/);

        // Cancel modal
        await page.click('button:has-text("Cancel")');
        await expect(modal).not.toBeVisible();
    });

    test("Verify Exam beforeunload warning", async ({ page }) => {
        // Login as Student
        await page.goto(`${BASE_URL}/login`);
        await page.fill('input[name="username"]', "joe");
        await page.fill('input[name="password"]', "password");
        await page.click('button[type="submit"]');

        // Go to an exam (assuming one exists, using ID 1 if possible or finding one)
        await page.goto(`${BASE_URL}/exam-list`);
        const startExamLink = page.locator('a:has-text("Start Exam")').first();
        if (await startExamLink.isVisible()) {
            await startExamLink.click();

            // Confirm starting the exam (if there's a confirmation modal)
            const confirmBtn = page.locator('button:has-text("Start Now")');
            if (await confirmBtn.isVisible()) {
                await confirmBtn.click();
            }

            // Now in take_exam page
            // Try to navigate away
            page.on("dialog", async (dialog) => {
                expect(dialog.message()).toContain(
                    "Are you sure you want to leave?",
                );
                await dialog.dismiss();
            });

            await page.click('a[href="/profile"]'); // Try navigating to profile
        }
    });
});
