// Offline QA of actual Blade responses exported by PublicNoticeViewerTest.
// No server, live data, authentication, external scripts, or network mutations.
// Local product CSS is retained; third-party CDN styles/fonts are omitted.
import test, { before, after } from 'node:test';
import assert from 'node:assert/strict';
import { readFile, mkdir } from 'node:fs/promises';
import { createRequire } from 'node:module';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const { chromium } = createRequire(import.meta.url)('playwright');
const root = fileURLToPath(new URL('../../', import.meta.url));
const fixtures = process.env.NOTICE_VIEWER_QA_DIR;
if (!fixtures || path.resolve(fixtures).toLowerCase().startsWith(path.resolve(root).toLowerCase())) {
    throw new Error('NOTICE_VIEWER_QA_DIR must be an external synthetic fixture directory.');
}
const titles = { text: 'School reopening announcement', image: 'Term calendar · notice image', pdf: 'Examination schedule', long: 'Detailed school guidelines', xss: 'Safety & <script>window.noticeXSS=1</script>' };
const mediaOrigin = 'https://admin.example.test';
const picture = '<svg xmlns="http://www.w3.org/2000/svg" width="1000" height="600" viewBox="0 0 1000 600"><rect width="1000" height="600" fill="#edf3f7"/><rect x="35" y="35" width="930" height="530" rx="8" fill="white" stroke="#cbd9e3"/><text x="75" y="105" font-family="sans-serif" font-size="28" fill="#234562">ACADEMIC CALENDAR</text><text x="75" y="150" font-family="sans-serif" font-size="18" fill="#526577">Synthetic notice image · QA only</text><path d="M75 200H925M75 280H925M75 360H925M75 440H925M280 200V520M500 200V520M720 200V520" stroke="#cbd9e3" stroke-width="2"/></svg>';
let browser;
before(async () => { browser = await chromium.launch({ headless: true, ...(process.env.ASYNC_BROWSER_EXECUTABLE ? { executablePath: process.env.ASYNC_BROWSER_EXECUTABLE } : {}) }); });
after(async () => { await browser?.close(); });

async function open(name = 'list', width = 1280, options = {}) {
    const raw = await readFile(path.join(fixtures, name + '.html'), 'utf8');
    const html = raw.replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, script => script.startsWith('<script data-public-notice-script>') ? script : '')
        .replace(/<link\b[^>]*>/gi, link => link.includes('rel="stylesheet"') && link.includes('https://public.example.test/') ? link : '');
    const context = await browser.newContext({ viewport: { width, height: width === 390 ? 844 : 1000 }, reducedMotion: 'reduce', javaScriptEnabled: options.js !== false });
    if (options.unsupported) await context.addInitScript(() => { HTMLDialogElement.prototype.showModal = undefined; });
    const page = await context.newPage(); const errors = [], mutations = [], mediaRequests = [], unknown = [];
    page.setDefaultTimeout(6000);
    page.on('pageerror', error => errors.push(error.message));
    await page.route('**/*', async route => {
        const request = route.request(), url = new URL(request.url());
        if (request.method() !== 'GET') { mutations.push(request.method() + ' ' + url.href); return route.abort(); }
        if (request.isNavigationRequest() && url.origin === 'https://public.example.test') return route.fulfill({ contentType: 'text/html', body: html });
        if (url.origin === mediaOrigin) {
            mediaRequests.push(url.href);
            if (!url.pathname.startsWith('/tenant/public/upload/notice/')) { unknown.push(url.href); return route.abort(); }
            if (options.imageFailure) return route.fulfill({ status: 404, body: '' });
            return route.fulfill({ contentType: 'image/svg+xml', body: picture });
        }
        if (url.origin === 'https://public.example.test' && url.pathname.startsWith('/public/')) {
            const filename = path.resolve(root, decodeURIComponent(url.pathname.slice(1)));
            if (!filename.startsWith(path.resolve(root, 'public') + path.sep)) return route.abort();
            try {
                const body = await readFile(filename);
                const contentType = filename.endsWith('.css') ? 'text/css' : filename.endsWith('.woff2') ? 'font/woff2' : undefined;
                return route.fulfill({ body, ...(contentType ? { contentType } : {}) });
            } catch { return route.fulfill({ status: 404, body: '' }); }
        }
        // Unrelated third-party homepage decoration is intentionally not requested live.
        if (['image', 'stylesheet', 'font'].includes(request.resourceType())) return route.fulfill({ status: 204, body: '' });
        unknown.push(url.href); return route.abort();
    });
    await page.goto('https://public.example.test/' + (name === 'home' ? '' : 'notices'));
    // The unrelated template's main.js normally dismisses its page-load overlay.
    // That script is excluded from this isolated viewer harness.
    await page.evaluate(() => document.getElementById('loader')?.remove());
    return { page, context, errors, mutations, mediaRequests, unknown };
}

async function select(page, type) {
    const title = titles[type];
    const trigger = page.locator('[data-public-notice-open]').filter({ hasText: title });
    const button = await trigger.count() ? trigger : page.getByRole('link', { name: 'View notice: ' + title, exact: true });
    const original = page.url();
    await button.click();
    await page.locator('#public-notice-dialog').waitFor({ state: 'visible' });
    assert.equal(page.url(), original, 'View must not navigate');
    assert.equal(await page.locator('#public-notice-title').textContent(), title);
    return button;
}

async function fits(page) {
    const dimensions = await page.locator('#public-notice-dialog').evaluate(dialog => {
        const rect = dialog.getBoundingClientRect();
        return { left: rect.left, right: rect.right, top: rect.top, bottom: rect.bottom, width: innerWidth, height: innerHeight,
            scroll: dialog.scrollWidth, client: dialog.clientWidth, contentScroll: dialog.querySelector('.pn-scroll').scrollWidth, contentClient: dialog.querySelector('.pn-scroll').clientWidth };
    });
    assert.ok(dimensions.left >= 0 && dimensions.right <= dimensions.width && dimensions.top >= 0 && dimensions.bottom <= dimensions.height, JSON.stringify(dimensions));
    assert.ok(dimensions.scroll <= dimensions.client + 1 && dimensions.contentScroll <= dimensions.contentClient + 1, 'Modal has no horizontal overflow');
    assert.ok(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1), 'No page horizontal overflow');
    const close = await page.locator('.pn-footer .pn-close').boundingBox();
    assert.ok(close.height >= 44 && close.y + close.height <= dimensions.height);
}

async function screenshot(page, name) {
    if (!process.env.NOTICE_VIEWER_SCREENSHOTS) return;
    await mkdir(process.env.NOTICE_VIEWER_SCREENSHOTS, { recursive: true });
    await page.screenshot({ path: path.join(process.env.NOTICE_VIEWER_SCREENSHOTS, name + '.png') });
}

for (const width of [1280, 768, 390]) for (const type of ['text', 'image', 'pdf', 'long']) {
    test(`${width}px ${type}: selected notice, internal layout and usable actions`, async () => {
        const qa = await open('list', width); const { page, context } = qa;
        try {
            await select(page, type);
            assert.equal(await page.locator('#public-notice-dialog').getAttribute('aria-labelledby'), 'public-notice-title');
            assert.equal(await page.locator('#public-notice-dialog time').textContent(), '20 Sep 2026');
            assert.equal(await page.locator('#public-notice-title').evaluate(e => e === document.activeElement), true);
            if (type === 'text') {
                assert.ok((await page.locator('.pn-message').textContent()).includes('guardians,\n\nClasses resume'));
                assert.equal(await page.locator('.pn-message').evaluate(e => getComputedStyle(e).whiteSpace), 'pre-wrap');
                assert.equal(await page.locator('.pn-attachment').count(), 0);
            }
            if (type === 'image') {
                await page.waitForFunction(() => document.querySelector('[data-public-notice-image]')?.naturalWidth > 0);
                const size = await page.locator('[data-public-notice-image]').evaluate(e => ({ rendered: e.clientWidth / e.clientHeight, original: e.naturalWidth / e.naturalHeight }));
                assert.ok(Math.abs(size.rendered - size.original) < 0.02, 'Image is not distorted');
            }
            if (type === 'pdf' || type === 'image') {
                const link = page.locator('.pn-file-action');
                assert.match(await link.getAttribute('href'), /^https:\/\/admin\.example\.test\/tenant\/public\/upload\/notice\//);
                assert.ok(!(await link.getAttribute('href')).includes('/public/public/'));
                assert.equal(await link.getAttribute('target'), '_blank');
                assert.equal(await link.getAttribute('rel'), 'noopener noreferrer');
                await link.scrollIntoViewIfNeeded();
                assert.ok((await link.boundingBox()).height >= 44);
                await page.locator('.pn-scroll').evaluate(e => e.scrollTop = 0);
            }
            if (type === 'long') {
                assert.ok(await page.locator('.pn-scroll').evaluate(e => e.scrollHeight > e.clientHeight));
                await page.locator('.pn-scroll').evaluate(e => e.scrollTop = e.scrollHeight);
                assert.ok(await page.locator('.pn-scroll').evaluate(e => e.scrollTop > 0));
                await fits(page);
                await page.locator('.pn-scroll').evaluate(e => e.scrollTop = 0);
            }
            await fits(page);
            await screenshot(page, `${type}-${width}`);
            assert.deepEqual(qa.errors, []); assert.deepEqual(qa.mutations, []); assert.deepEqual(qa.unknown, []);
        } finally { await context.close(); }
    });
}

test('both Close actions and Escape restore focus and body scrolling; selections never become stale', async () => {
    const { page, context } = await open();
    try {
        await page.evaluate(() => { document.body.style.overflow = 'auto'; document.body.style.paddingRight = '7px'; });
        for (const [type, action] of [['text', 'top'], ['pdf', 'footer'], ['long', 'escape']]) {
            const trigger = await select(page, type);
            assert.equal(await page.evaluate(() => document.body.style.overflow), 'hidden');
            if (action === 'escape') await page.keyboard.press('Escape');
            else await page.locator(action === 'top' ? '.pn-icon-close' : '.pn-close').click();
            await page.locator('#public-notice-dialog').waitFor({ state: 'hidden' });
            await page.waitForFunction(() => !document.querySelector('.pn-scroll').childElementCount);
            assert.equal(await trigger.evaluate(e => e === document.activeElement), true);
            assert.deepEqual(await page.evaluate(() => [document.body.style.overflow, document.documentElement.style.overflow, document.body.style.paddingRight]), ['auto', '', '7px']);
        }
    } finally { await context.close(); }
});

test('backdrop closes; inside clicks and dragging outward do not', async () => {
    const { page, context } = await open();
    try {
        await select(page, 'text');
        await page.locator('.pn-message').click();
        assert.ok(await page.locator('#public-notice-dialog').isVisible());
        const box = await page.locator('#public-notice-dialog').boundingBox();
        await page.mouse.move(box.x + 50, box.y + 50); await page.mouse.down();
        await page.mouse.move(2, 2); await page.mouse.up();
        assert.ok(await page.locator('#public-notice-dialog').isVisible());
        await page.mouse.click(2, 2);
        await page.locator('#public-notice-dialog').waitFor({ state: 'hidden' });
    } finally { await context.close(); }
});

test('keyboard open and Tab/Shift+Tab remain inside the dialog', async () => {
    const { page, context } = await open();
    try {
        const trigger = page.locator('[data-public-notice-open]').filter({ hasText: titles.pdf });
        await trigger.focus(); await page.keyboard.press('Enter');
        await page.locator('#public-notice-dialog').waitFor({ state: 'visible' });
        await page.keyboard.press('Shift+Tab');
        assert.ok(await page.locator('.pn-close').evaluate(e => e === document.activeElement));
        await page.keyboard.press('Tab');
        assert.ok(await page.locator('.pn-icon-close').evaluate(e => e === document.activeElement));
        for (let i = 0; i < 7; i++) {
            await page.keyboard.press('Tab');
            assert.ok(await page.locator('#public-notice-dialog').evaluate(e => e.contains(document.activeElement)));
        }
    } finally { await context.close(); }
});

test('opening and closing does not lose the reader’s page position', async () => {
    const { page, context } = await open('home', 390);
    try {
        const trigger = page.getByRole('link', { name: 'View notice: ' + titles.text, exact: true });
        await trigger.scrollIntoViewIfNeeded();
        const before = await page.evaluate(() => window.scrollY);
        await trigger.click();
        await page.locator('#public-notice-dialog').waitFor({ state: 'visible' });
        await page.keyboard.press('Escape');
        await page.waitForFunction(() => !document.querySelector('.pn-scroll').childElementCount);
        assert.ok(Math.abs(await page.evaluate(() => window.scrollY) - before) < 2);
        assert.ok(await trigger.evaluate(e => e === document.activeElement));
    } finally { await context.close(); }
});

test('plain text attack stays inert when cloned from the template', async () => {
    const { page, context, unknown } = await open();
    try {
        await select(page, 'xss');
        assert.equal(await page.evaluate(() => window.noticeXSS), undefined);
        assert.equal(await page.locator('.pn-scroll script, .pn-scroll img').count(), 0);
        assert.ok((await page.locator('.pn-message').textContent()).includes('<img src=x onerror='));
        assert.deepEqual(unknown, []);
    } finally { await context.close(); }
});

for (const width of [1280, 390]) test(`homepage View opens the shared modal at ${width}px without navigation`, async () => {
    const { page, context, errors } = await open('home', width);
    try {
        await select(page, 'pdf'); await fits(page);
        await screenshot(page, `homepage-pdf-${width}`);
        assert.deepEqual(errors, []);
    } finally { await context.close(); }
});

test('missing media authority retains safe unavailable state with readable text', async () => {
    const { page, context, mediaRequests } = await open('missing-media', 390);
    try {
        await select(page, 'text');
        assert.ok(await page.getByText('The attachment is currently unavailable. Please check again later.', { exact: true }).isVisible());
        assert.equal(await page.locator('.pn-file-action, .pn-image').count(), 0);
        assert.deepEqual(mediaRequests, []); await fits(page);
    } finally { await context.close(); }
});

test('broken image offers an honest fallback and original canonical link', async () => {
    const { page, context } = await open('list', 390, { imageFailure: true });
    try {
        await select(page, 'image');
        await page.locator('[data-public-notice-image-error]').waitFor({ state: 'visible' });
        assert.equal(await page.locator('[data-public-notice-image]').isVisible(), false);
        assert.ok(await page.locator('.pn-file-action').isVisible()); await fits(page);
    } finally { await context.close(); }
});

for (const options of [{ js: false }, { unsupported: true }]) test(`direct route fallback: ${options.js === false ? 'JavaScript disabled' : 'dialog unsupported'}`, async () => {
    const { page, context } = await open('list', 390, options);
    try {
        const trigger = page.locator('[data-public-notice-open]').filter({ hasText: titles.text });
        const href = await trigger.getAttribute('href');
        await trigger.click(); await page.waitForURL(href);
        assert.match(page.url(), /\/notices\/\d+$/);
        assert.equal(await page.locator('#public-notice-dialog').isVisible(), false);
    } finally { await context.close(); }
});
