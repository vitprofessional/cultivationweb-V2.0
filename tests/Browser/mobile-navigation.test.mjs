import test, { before, after } from 'node:test';
import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import { createRequire } from 'node:module';

const { chromium } = createRequire(import.meta.url)('playwright');
const root = new URL('../../', import.meta.url);
const headerPath = new URL('resources/views/frontend/cultivation-v2/partials/_header.blade.php', root);
let browser;

before(async () => { browser = await chromium.launch({ headless: true, ...(process.env.ASYNC_BROWSER_EXECUTABLE ? { executablePath: process.env.ASYNC_BROWSER_EXECUTABLE } : {}) }); });
after(async () => { await browser?.close(); });

async function makePage(width, homepage) {
    const source = await readFile(headerPath, 'utf8');
    const style = source.match(/<style>([\s\S]*?)<\/style>/i)?.[1];
    const script = source.match(/<script>([\s\S]*?const navigation = document\.getElementById\('primary-navigation'\)[\s\S]*?)<\/script>/i)?.[1];
    assert.ok(style && script, 'shared header must provide navigation styles and controller');
    const context = await browser.newContext({ viewport: { width, height: 844 }, reducedMotion: 'reduce' });
    const page = await context.newPage();
    page.on('pageerror', error => console.error('navigation page error:', error.message));
    await page.setContent(`<!doctype html><html><head><style>${style}</style></head><body class="home-style2 ${homepage ? 'v2-homepage' : ''}">
        <div class="full-width-header header-style2"><header class="rs-header"><div class="menu-area menu-sticky"><div class="main-menu">
        <div class="mobile-menu"><button class="rs-menu-toggle" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="primary-navigation"><i class="fa fa-bars"></i></button></div>
        <nav id="primary-navigation" class="rs-menu rs-menu-close" aria-label="Primary navigation"><ul class="nav-menu">
        <li><a href="#home">Home</a></li><li class="menu-item-has-children"><button class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-institute">Institute</button><ul id="nav-submenu-institute" class="sub-menu"><li><a href="#about">About Us</a></li><li><a href="#message">Head of Institution Message</a></li><li><a href="#teachers">Teacher Directory</a></li></ul></li>
        <li class="menu-item-has-children"><button class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-academic">Academic</button><ul id="nav-submenu-academic" class="sub-menu"><li><a href="#notices">Notices</a></li></ul></li>
        <li class="menu-item-has-children"><button class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-result">Result</button><ul id="nav-submenu-result" class="sub-menu"><li><a href="#results">Results</a></li></ul></li>
        <li class="menu-item-has-children"><button class="rs-menu-link" aria-expanded="false" aria-controls="nav-submenu-gallery">Gallery</button><ul id="nav-submenu-gallery" class="sub-menu"><li><a href="#gallery">Gallery</a></li></ul></li>
        </ul></nav></div></div></header></div><main style="height:1200px">Content</main><script>${script}</script></body></html>`);
    return { context, page };
}

for (const width of [390, 768]) {
    for (const homepage of [true, false]) {
        test(`mobile navigation ${homepage ? 'homepage' : 'inner page'} at ${width}px`, async () => {
            const { context, page } = await makePage(width, homepage);
            try {
                const toggle = page.locator('.rs-menu-toggle');
                const nav = page.locator('#primary-navigation');
                assert.equal(await nav.isVisible(), false, 'menu starts collapsed');
                await toggle.click();
                assert.equal(await toggle.getAttribute('aria-expanded'), 'true');
                assert.equal(await nav.isVisible(), true, await nav.evaluate(element => JSON.stringify({ className: element.className, display: getComputedStyle(element).display, height: getComputedStyle(element).height, width: innerWidth })));

                const institute = page.getByRole('button', { name: 'Institute' });
                const academic = page.getByRole('button', { name: 'Academic' });
                await institute.click();
                assert.equal(await institute.getAttribute('aria-expanded'), 'true');
                assert.equal(await page.locator('#nav-submenu-institute').isVisible(), true);
                await academic.click();
                assert.equal(await institute.getAttribute('aria-expanded'), 'false');
                assert.equal(await page.locator('#nav-submenu-institute').isVisible(), false);
                assert.equal(await academic.getAttribute('aria-expanded'), 'true');

                await academic.click();
                assert.equal(await academic.getAttribute('aria-expanded'), 'false', 'submenu tap toggles closed');
                await page.getByRole('button', { name: 'Result' }).click();
                await page.getByRole('link', { name: 'Results' }).click();
                assert.equal(await toggle.getAttribute('aria-expanded'), 'false', 'leaf navigation closes menu');

                const overflow = await page.evaluate(() => document.documentElement.scrollWidth > innerWidth);
                assert.equal(overflow, false, 'navigation creates no horizontal overflow');
            } finally { await context.close(); }
        });
    }
}

test('desktop navigation remains visible and does not force mobile panel styling', async () => {
    const { context, page } = await makePage(1280, false);
    try {
        assert.equal(await page.locator('#primary-navigation').isVisible(), true);
        assert.equal(await page.locator('#primary-navigation').evaluate(element => element.classList.contains('rs-menu-close')), false);
        assert.equal(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth), false);
    } finally { await context.close(); }
});

test('shared navigation renders and opens on all requested live local pages at 390px', async () => {
    const context = await browser.newContext({ viewport: { width: 390, height: 844 }, reducedMotion: 'reduce' });
    const page = await context.newPage();
    page.setDefaultTimeout(10000);
    const paths = ['/', '/about-us', '/head-of-institute-message', '/our-teacher', '/notices', '/image/gallary', '/internal/result'];
    try {
        for (const path of paths) {
            const response = await page.goto('http://localhost/cultivationweb-V2.0/public' + path, { waitUntil: 'domcontentloaded' });
            assert.equal(response?.status(), 200, `${path} renders successfully`);
            const toggle = page.locator('.rs-menu-toggle[aria-controls="primary-navigation"]');
            const nav = page.locator('#primary-navigation');
            assert.equal(await toggle.count(), 1, `${path} uses the shared mobile navigation`);
            await toggle.click();
            assert.equal(await toggle.getAttribute('aria-expanded'), 'true', `${path} opens with correct aria state`);
            assert.equal(await nav.isVisible(), true, `${path} menu is visible after opening`);
            assert.equal(await nav.evaluate(element => element.scrollWidth > element.clientWidth), false, `${path} menu has no horizontal overflow`);
            await page.getByRole('button', { name: 'Institute' }).click();
            assert.equal(await page.locator('#nav-submenu-institute').isVisible(), true, `${path} submenu opens by tap`);
            await toggle.click();
            assert.equal(await toggle.getAttribute('aria-expanded'), 'false', `${path} closes with correct aria state`);
        }
    } finally { await context.close(); }
});
