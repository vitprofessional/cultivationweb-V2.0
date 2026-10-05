import test, { before, after } from 'node:test';
import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { mkdir } from 'node:fs/promises';
import { join } from 'node:path';
const { chromium } = createRequire(import.meta.url)('playwright');
const base = process.env.WEBSITE_QA_URL || 'http://localhost/cultivationweb-V2.0';
let browser;
before(async () => { browser = await chromium.launch({ headless: true, ...(process.env.ASYNC_BROWSER_EXECUTABLE ? { executablePath: process.env.ASYNC_BROWSER_EXECUTABLE } : {}) }); });
after(async () => { await browser?.close(); });
for (const width of [390,768,1280]) {
    test(`real homepage, About, Login Hub and message at ${width}px`, async () => {
        const context = await browser.newContext({ viewport: { width, height: 900 }, reducedMotion: 'reduce' });
        const page = await context.newPage();
        let aboutUrl;
        try {
            for (const path of ['/', '/about-us', '/login', '/head-of-institute-message']) {
                const response = await page.goto(base + path, { waitUntil:'domcontentloaded' });
                assert.equal(response.status(), 200, path);
                await page.waitForLoadState('load');
                await page.locator('#loader').waitFor({state:'hidden',timeout:15000});
                assert.ok(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1), `${path} has no page overflow`);
                assert.equal(await page.locator('#primary-navigation a').filter({hasText:/^Login$/}).count(), 0);
                assert.equal(await page.locator('.header-portal-cta').count(), 1);
                if (path === '/') {
                    const hero = page.locator('.rs-slider .slider-content').first();
                    assert.ok((await hero.boundingBox()).height >= (width===390?400:width===768?460:540));
                    const about = page.locator('#rs-about-welcome img').first();
                    const src = await about.getAttribute('src');
                    aboutUrl = src;
                    assert.ok(!src.includes('/public/public/'));
                    assert.equal((await page.request.get(src)).status(), 200);
                    assert.ok(await about.evaluate(img => img.complete && img.naturalWidth > 0), 'About image loads');
                }
                if (path === '/about-us') assert.equal(await page.locator(`img[src="${aboutUrl}"]`).count(),1, 'homepage and About share heroImg');
                if (path === '/head-of-institute-message') {
                    const portrait = page.locator('.hoi-avatar');
                    if (await portrait.count()) {
                        assert.ok(await portrait.evaluate(img => img.complete && img.naturalWidth > 0), 'Principal portrait loads');
                        assert.ok(!(await portrait.getAttribute('src')).includes('/public/public/'));
                    }
                }
                if (path === '/login') {
                    assert.equal(await page.locator('[data-portal]').count(),4);
                    assert.equal(await page.locator('[data-portal="representative"]').count(),0);
                    assert.equal(await page.locator('.portal-grid').evaluate(el=>getComputedStyle(el).gridTemplateColumns.split(' ').length),width<576?1:width<992?2:4);
                }
                if (process.env.WEBSITE_QA_SCREENSHOTS) {
                    await mkdir(process.env.WEBSITE_QA_SCREENSHOTS,{recursive:true});
                    await page.screenshot({path:join(process.env.WEBSITE_QA_SCREENSHOTS,`${path==='/login'?'login':path==='/'?'home':'about'}-${width}.png`),fullPage:true});
                }
            }
        } finally { await context.close(); }
    });
}
