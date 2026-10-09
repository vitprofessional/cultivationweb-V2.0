import test from 'node:test';
import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { execFileSync } from 'node:child_process';
import { mkdir } from 'node:fs/promises';
import os from 'node:os';
import path from 'node:path';
const { chromium } = createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE || 'playwright');
const expected = JSON.parse(execFileSync('php', ['-r', 'require "vendor/autoload.php"; $app=require "bootstrap/app.php"; $app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap(); $data=App\\Models\\InstituteDetails::first(); echo json_encode(["about"=>$data?->insDetails,"mission"=>$data?->mission,"vision"=>$data?->vision,"image"=>app(App\\Services\\PublicMediaUrl::class)->institutionAboutImage($data?->heroImg)],JSON_UNESCAPED_UNICODE);'], { encoding: 'utf8' }));
const normalize = text => text.replace(/\s+/gu, ' ').trim();
const output = path.join(os.tmpdir(), 'phase46-about');
for (const width of [390, 768, 1280]) test(`About presentation ${width}px`, async () => {
    const browser = await chromium.launch({ headless: true, executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
    const page = await browser.newPage({ viewport: { width, height: 1000 } });
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    try {
        const response = await page.goto('http://localhost/cultivationweb-V2.0/about-us', { waitUntil: 'domcontentloaded', timeout: 60000 });
        assert.equal(response.status(), 200);
        await page.locator('#loader').waitFor({ state: 'hidden', timeout: 15000 });
        assert.equal(await page.locator('#about-page h1').innerText(), 'About Us');
        assert.equal(await page.locator('.about-purpose-card').count(), 2);
        assert.equal(await page.locator('.about-purpose-card h3').allInnerTexts().then(t => t.join('|')), 'Our Mission|Our Vision');
        for (const [selector, value] of [['[data-about-content]', expected.about], ['[data-purpose="mission"]', expected.mission], ['[data-purpose="vision"]', expected.vision]]) {
            assert.equal(normalize(await page.locator(selector).innerText()), normalize(value), 'authored content is complete');
        }
        assert.equal(await page.locator('[data-purpose="values"], .about-contact, .about-hero img').count(), 0);
        assert.equal(await page.locator('.about-hero-visual, .about-journey-visual').count(), 2, 'fixed local education artwork in hero and journey');
        for (const selector of ['.about-campus img']) {
            assert.equal(await page.locator(selector).getAttribute('src'), expected.image);
            await page.locator(selector).evaluate(img => img.decode());
            assert.ok(await page.locator(selector).evaluate(img => img.naturalWidth > 0));
        }
        assert.equal(await page.locator('.about-actions a').count(), 2);
        const columns = await page.locator('.about-purpose').evaluate(el => getComputedStyle(el).gridTemplateColumns.split(' ').length);
        assert.equal(columns, width === 1280 ? 2 : 1);
        assert.ok(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1));
        await mkdir(output, { recursive: true });
        await page.screenshot({ path: path.join(output, `about-${width}.png`), fullPage: true });
        await page.screenshot({ path: path.join(output, `about-top-${width}.png`) });
        assert.deepEqual(errors, []);
    } finally { await browser.close(); }
});
