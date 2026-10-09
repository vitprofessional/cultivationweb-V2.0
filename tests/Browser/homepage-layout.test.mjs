import test from 'node:test';
import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { readFile } from 'node:fs/promises';
import path from 'node:path';
const { chromium } = createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE || 'playwright');
const output = process.env.HOMEPAGE_QA_DIR;
assert.ok(output, 'HOMEPAGE_QA_DIR is required');
for (const width of [390, 768, 1280]) {
    test(`Modern and Classic homepage ${width}px`, async () => {
        const browser = await chromium.launch({ headless: true, executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
        const page = await browser.newPage({ viewport: { width, height: 950 } });
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        try {
            const url = 'http://localhost/cultivationweb-V2.0/';
            await page.route(url, route => route.fulfill({ contentType: 'text/html', path: path.join(output, 'modern.html') }));
            await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
            await page.locator('#modern-homepage').waitFor();
            await page.locator('#loader').waitFor({ state: 'hidden', timeout: 15000 });
            // Exercise lazy media before full-page captures, including broken-gallery handling.
            for (const section of await page.locator('#modern-homepage > section').all()) {
                await section.scrollIntoViewIfNeeded();
                await page.waitForTimeout(180);
            }
            await page.waitForTimeout(500);
            const validPhotos = page.locator('.mh-gallery a:not([hidden])');
            if (await validPhotos.count()) {
                await validPhotos.first().click();
                await page.locator('.mfp-wrap .mfp-img').waitFor();
                assert.ok(await page.locator('.mfp-img').evaluate(img => img.complete && img.naturalWidth > 0));
                assert.equal(await page.locator('.mfp-arrow').count(), (await validPhotos.count()) > 1 ? 2 : 0);
                await page.keyboard.press('Escape');
                await page.locator('.mfp-wrap').waitFor({ state: 'detached' });
                // Duplicate a real loaded photo in browser-only fixture to exercise multi-image navigation.
                await validPhotos.first().evaluate(link => {
                    const duplicate = link.cloneNode(true);
                    duplicate.dataset.qaDuplicate = 'true';
                    link.parentElement.append(duplicate);
                });
                await validPhotos.first().click();
                await page.locator('.mfp-arrow-right').waitFor();
                await page.locator('.mfp-arrow-right').click();
                await page.waitForFunction(() => window.jQuery.magnificPopup.instance.index === 1);
                await page.keyboard.press('ArrowLeft');
                await page.waitForFunction(() => window.jQuery.magnificPopup.instance.index === 0);
                await page.locator('.mfp-close').click();
                await page.locator('.mfp-wrap').waitFor({ state: 'detached' });
                await page.locator('[data-qa-duplicate]').evaluate(el => {
                    const gallery = el.parentElement;
                    el.remove();
                    gallery.dataset.visibleCount = gallery.querySelectorAll('a:not([hidden])').length;
                });
            }
            const gap = await page.evaluate(() => document.querySelector('.footer-info-strip').getBoundingClientRect().top - document.querySelector('.mh-admission').getBoundingClientRect().bottom);
            assert.ok(Math.abs(gap) <= 1, `CTA/footer gap ${gap}px`);
            assert.ok(await page.getByRole('link', { name: 'View All Photos' }).getAttribute('href'));
            await page.evaluate(() => scrollTo({ top: 0, behavior: 'instant' }));
            assert.equal(await page.locator('.mh-quick a').count(), 4);
            assert.equal(await page.locator('.mh-slide:not([hidden])').count(), 1);
            assert.equal(await page.getByText(/sonar bangla college|lorem ipsum/i).count(), 0);
            assert.ok((await page.locator('.mh-slide:not([hidden])').boundingBox()).height >= (width > 900 ? 445 : width > 540 ? 390 : 340));
            assert.equal(await page.locator('.mh-about-text').evaluate(el => getComputedStyle(el).overflowY), 'visible');
            for (const portrait of await page.locator('.mh-teacher .people-photo-frame').all()) {
                const box = await portrait.boundingBox();
                assert.ok(Math.abs(box.width / box.height - 0.8) < 0.02, 'Shared 4:5 portrait geometry');
            }
            if (await page.locator('.mh-slide').count() > 1) {
                await page.locator('[data-slide-direction="1"]').click();
                assert.equal(await page.locator('.mh-slide:not([hidden])').count(), 1);
                assert.equal(await page.locator('.mh-slider-dots [aria-current="true"]').count(), 1);
            }
            assert.equal(await page.getByText('Upcoming Events', { exact: true }).count(), 0);
            for (const link of await page.locator('#modern-homepage a').all()) assert.ok(await link.getAttribute('href'));
            assert.ok(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1));
            await page.evaluate(() => scrollTo({ top: 600, behavior: 'instant' }));
            await page.waitForTimeout(250);
            assert.equal(await page.locator('.full-width-header').evaluate(el => getComputedStyle(el).position), 'sticky');
            const menu = await page.locator('.menu-sticky').boundingBox();
            assert.ok(Math.abs(menu.y) < 2);
            await page.waitForFunction(() => !document.getElementById('scrollUp').hidden);
            await page.locator('#scrollUp').click();
            await page.waitForFunction(() => scrollY < 2);
            if (width < 1101) {
                await page.locator('.rs-menu-toggle').click();
                assert.equal(await page.locator('.rs-menu-toggle').getAttribute('aria-expanded'), 'true');
                await page.keyboard.press('Escape');
            }
            await page.evaluate(() => scrollTo({ top: 0, behavior: 'instant' }));
            await page.waitForTimeout(250);
            await page.screenshot({ path: path.join(output, `modern-${width}.png`), fullPage: true });
            await page.screenshot({ path: path.join(output, `modern-${width}-viewport.png`) });
            await page.unroute(url);
            await page.route(url, route => route.fulfill({ contentType: 'text/html', path: path.join(output, 'classic.html') }));
            await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
            assert.equal(await page.locator('#modern-homepage').count(), 0);
            assert.ok(await page.locator('.rs-slider').count());
            assert.ok(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1));
            assert.deepEqual(errors, []);
        } finally { await browser.close(); }
    });
}
