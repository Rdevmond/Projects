import { test, expect } from '@playwright/test';

const BASE_URL = 'http://localhost:8000';

test.describe('Cisco NetRiders PCR Portal Simulation', () => {

    test('Guest Journey: Home Page & Navigation', async ({ page }) => {
        await page.goto(BASE_URL);

        // 1. Verify Hero Section
        await expect(page.locator('h1')).toContainText('Master Your Network');
        
        // 2. Test Smooth Scroll to About
        await page.click('a[href="#about"]');
        await page.waitForTimeout(1000); 
        await expect(page.locator('#about')).toBeInViewport();

        // 3. Navigate to Login (Fast Transition)
        const startTime = Date.now();
        await page.click('text=Portal Login');
        await expect(page).toHaveURL(`${BASE_URL}/login`);
        const duration = Date.now() - startTime;
        
        expect(duration).toBeLessThan(3000); 
    });

    test('Admin Journey: Login & Home CMS', async ({ page }) => {
        await page.goto(`${BASE_URL}/login`);

        // 1. Login as Admin
        await page.fill('input[name="username"]', 'admin1');
        await page.fill('input[name="password"]', 'admin123');
        await page.click('button[type="submit"]');

        await expect(page).toHaveURL(`${BASE_URL}/admin/dashboard`);

        // 2. Go to Home CMS
        await page.click('text=Home CMS');
        await expect(page.locator('h1')).toContainText('Welcome Page Settings');

        // 3. Test Text Update
        await page.fill('input[name="hero_title"]', 'Updated Networking Excellence');
        await page.click('form[action$="welcome-settings"] button[type="submit"]');

        // Check for success message
        await expect(page.locator('text=updated successfully')).toBeVisible();

        // 4. Verify on Home Page
        await page.goto(BASE_URL);
        await expect(page.locator('h1')).toContainText('Updated Networking Excellence');

        // (Cleanup) Revert change
        await page.goto(`${BASE_URL}/admin/welcome-settings`);
        await page.fill('input[name="hero_title"]', 'Master Your Network, Shape Your Future.');
        await page.click('form[action$="welcome-settings"] button[type="submit"]');
    });

    test('Student Journey: Login & Dashboard', async ({ page }) => {
        await page.goto(`${BASE_URL}/login`);

        // 1. Login as Student
        await page.fill('input[name="username"]', 'student');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');

        await expect(page).toHaveURL(`${BASE_URL}/exam-list`);
        await expect(page.locator('h1')).toContainText('Student Dashboard');
    });

});
