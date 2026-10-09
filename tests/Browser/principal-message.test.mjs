import test from 'node:test';
import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { mkdir } from 'node:fs/promises';
import path from 'node:path';
import os from 'node:os';
import { execFileSync } from 'node:child_process';
const { chromium } = createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE || 'playwright');
const output = path.join(os.tmpdir(), 'phase4522-message');
// Read the canonical local message without writing data; compare every authored word.
const storedMessage = JSON.parse(execFileSync('php', ['-r', 'require "vendor/autoload.php"; $app=require "bootstrap/app.php"; $app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap(); echo json_encode(app(App\\Services\\PrincipalProfile::class)->read()["message"], JSON_UNESCAPED_UNICODE);'], { encoding: 'utf8' }));
const normalize = text => text.replace(/\s+/gu, ' ').trim();
for (const width of [390, 768, 1280]) test(`Principal editorial message ${width}px`, async () => {
    const browser = await chromium.launch({ headless: true, executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
    const page = await browser.newPage({ viewport: { width, height: 1000 } });
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    try {
        const response = await page.goto('http://localhost/cultivationweb-V2.0/head-of-institute-message', { waitUntil: 'domcontentloaded', timeout: 60000 });
        assert.equal(response.status(), 200);
        await page.locator('#loader').waitFor({ state: 'hidden', timeout: 15000 });
        assert.equal(await page.locator('.hoi-hero h1').count(), 1);
        assert.equal(await page.locator('.hoi-hero h1').innerText(), 'প্রতিষ্ঠান প্রধানের বাণী');
        assert.equal(await page.locator('.hoi-hero-name, .hoi-hero-role, .hoi-signature strong').count(), 0);
        assert.equal(await page.locator('.hoi-info .hoi-name').count(), 1);
        assert.equal(await page.locator('.hoi-info .hoi-role').count(), 1);
        const introduction = await page.locator('.hoi-photo-card .hoi-quote').allInnerTexts();
        const paragraphs = await page.locator('.hoi-body-text p').allInnerTexts();
        assert.equal(normalize([...introduction, ...paragraphs].join('\n\n')), normalize(storedMessage), 'complete authored message, including sign-off, is preserved');
        assert.equal(await page.locator('.edu-page-title').count(), 0);
        assert.equal(await page.locator('.hoi-print, [onclick*="print"]').count(), 0);
        assert.equal(await page.getByRole('button', { name: /print/i }).count(), 0);
        assert.equal(await page.locator('.hoi-sidebar-values li').count(), 3);
        assert.deepEqual((await page.locator('.hoi-sidebar-values li').allInnerTexts()).map(normalize), [
            'Quality Education for a Brighter Future', 'Discipline, Character and Leadership', 'Student-Centered Learning',
        ]);
        assert.equal(await page.locator('.hoi-value-icon').count(), 3);
        const portrait = page.locator('[data-people-portrait]');
        await portrait.evaluate(img => img.decode());
        assert.ok(await portrait.evaluate(img => img.naturalWidth > 0));
        const frame = await page.locator('.hoi-photo-header .people-photo-frame').boundingBox();
        assert.ok(Math.abs(frame.width / frame.height - .8) < .02, 'leadership portrait keeps a 4:5 frame');
        const sidebarWidth = await page.locator('.hoi-photo-card').evaluate(el => el.clientWidth);
        assert.ok(frame.width / sidebarWidth >= .8 && frame.width / sidebarWidth <= .85, 'portrait occupies 80–85% of sidebar width');
        assert.equal(await page.locator('.hoi-photo-header .people-photo-frame').evaluate(el => getComputedStyle(el).borderRadius), '12px', 'rounded rectangle, not a circle');
        const crop = await portrait.evaluate(img => ({ fit: getComputedStyle(img).objectFit, position: getComputedStyle(img).objectPosition, transform: getComputedStyle(img).transform }));
        assert.equal(crop.fit, 'cover', 'natural portrait fills frame');
        assert.equal(crop.transform, 'none', 'no extra zoom');
        assert.equal(crop.position, '50% 28%', 'face-friendly headroom');
        const geometry = await page.evaluate(() => {
            const hero = document.querySelector('.hoi-hero').getBoundingClientRect();
            const content = document.querySelector('.hoi-message-layout').getBoundingClientRect();
            return { heroWidth: hero.width, viewport: innerWidth, overlap: hero.bottom - content.top, columns: getComputedStyle(document.querySelector('.hoi-message-layout')).gridTemplateColumns.split(' ').length };
        });
        assert.ok(Math.abs(geometry.heroWidth - geometry.viewport) < 2, 'full-width mockup hero');
        assert.ok(geometry.overlap > 20 && geometry.overlap < 50, 'overlapping content card');
        assert.equal(geometry.columns, width === 390 ? 1 : 2);
        assert.ok(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1));
        assert.ok(await page.locator('.hoi-body-text').evaluate(el => parseFloat(getComputedStyle(el).lineHeight) / parseFloat(getComputedStyle(el).fontSize) >= 1.75));
        await mkdir(output, { recursive: true });
        await page.screenshot({ path: path.join(output, `message-${width}.png`), fullPage: true });
        await page.locator('.hoi-photo-header').screenshot({ path: path.join(output, `portrait-${width}.png`) });
        console.log(`Portrait ${width}px: ${frame.width.toFixed(1)} × ${frame.height.toFixed(1)}px`);
        assert.equal(await page.locator('.hoi-photo-card').evaluate(el => getComputedStyle(el).position), 'static');
        assert.equal(await page.locator('.hoi-msg-header, .hoi-msg-body .hoi-quote').count(), 0);
        assert.equal(await page.locator('.hoi-photo-card .hoi-quote').count(), 1);
        const before = await page.locator('.hoi-photo-card').evaluate(el => el.getBoundingClientRect().top);
        await page.evaluate(() => window.scrollTo(0, 450));
        await page.waitForFunction(() => scrollY === 450);
        const after = await page.locator('.hoi-photo-card').evaluate(el => el.getBoundingClientRect().top);
        assert.ok(Math.abs(before - after - 450) < 2, 'sidebar stays in document flow');
        await page.evaluate(() => window.scrollTo(0, 0));
        const originalGeometry = await page.locator('.hoi-photo-card').boundingBox();
        // Simulate a failed media request in the browser only; no stored image changes.
        await page.route('**/qa-missing-principal-photo.png', route => route.fulfill({ status: 404, body: '' }));
        await portrait.evaluate(img => { img.src = new URL('qa-missing-principal-photo.png', location.href).href; });
        await page.waitForFunction(() => {
            const img = document.querySelector('[data-people-portrait]');
            return img.dataset.portraitFallbackActive === 'true' && img.complete && img.naturalWidth > 0 && img.src.startsWith('data:image/svg+xml');
        });
        await portrait.evaluate(img => img.decode());
        const fallbackFrame = await page.locator('.hoi-photo-header .people-photo-frame').boundingBox();
        assert.equal(await portrait.evaluate(img => getComputedStyle(img).objectPosition), '50% 50%', 'fallback stays centered');
        assert.equal(await portrait.evaluate(img => getComputedStyle(img).transform), 'none', 'fallback scaling unchanged');
        assert.equal(fallbackFrame.width, frame.width);
        assert.equal(fallbackFrame.height, frame.height);
        assert.deepEqual(await page.locator('.hoi-photo-card').boundingBox(), originalGeometry, 'broken-image fallback causes no layout shift');
        await page.screenshot({ path: path.join(output, `message-fallback-${width}.png`), fullPage: true });
        // Empty/missing sources use this same component-provided local silhouette.
        await portrait.evaluate(img => { img.src = img.dataset.portraitFallback; });
        await portrait.evaluate(img => img.decode());
        assert.deepEqual(await page.locator('.hoi-photo-header .people-photo-frame').boundingBox(), fallbackFrame);
        assert.ok(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1));
        assert.deepEqual(errors, []);
    } finally { await browser.close(); }
});
