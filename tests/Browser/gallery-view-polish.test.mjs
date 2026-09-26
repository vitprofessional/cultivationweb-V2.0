// Rendered synthetic Blade fixtures, real application CSS and pinned Bootstrap assets.
// All browser network traffic is intercepted; no application server or operational data is used.
import test, { before, after } from 'node:test';
import assert from 'node:assert/strict';
import { readFile, mkdir } from 'node:fs/promises';
import { createHash } from 'node:crypto';
import { createRequire } from 'node:module';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
const { chromium } = createRequire(import.meta.url)('playwright');
const root = fileURLToPath(new URL('../../', import.meta.url));
const fixtures = process.env.GALLERY_WEB_QA_DIR;
const output = process.env.GALLERY_WEB_SCREENSHOTS;
for (const directory of [fixtures, output]) {
    if (!directory || path.resolve(directory).toLowerCase().startsWith(path.resolve(root).toLowerCase())) throw new Error('Use external synthetic QA directories.');
}
const urls = {
    css: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
    js: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
};
const integrity = {
    css: 'T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN',
    js: 'C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL',
};
const assets = {};
let browser;
before(async () => {
    for (const type of ['css', 'js']) {
        const response = await fetch(urls[type]);
        assert.equal(response.status, 200);
        assets[type] = Buffer.from(await response.arrayBuffer());
        assert.equal(createHash('sha384').update(assets[type]).digest('base64'), integrity[type]);
    }
    await mkdir(output, { recursive: true });
    browser = await chromium.launch({headless:true, ...(process.env.ASYNC_BROWSER_EXECUTABLE ? {executablePath:process.env.ASYNC_BROWSER_EXECUTABLE} : {})});
});
after(async () => { await browser?.close(); });

function illustration(label, color) {
    return `<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800"><rect width="1200" height="800" fill="${color}"/><path d="M0 620 340 180 680 620 900 330 1200 650v150H0z" fill="#ffffff" opacity=".14"/><text x="60" y="110" fill="white" font-family="sans-serif" font-size="44">${label}</text><text x="60" y="164" fill="white" font-family="sans-serif" font-size="26">Synthetic Gallery QA image</text></svg>`;
}

async function openFixture(name, width, colorScheme, broken = false) {
    const raw = await readFile(path.join(fixtures, name), 'utf8');
    const script = [...raw.matchAll(/<script\b[^>]*>([\s\S]*?)<\/script>/gi)].map(m => m[1]).find(s => s.includes("const modalElement = document.getElementById("));
    assert.ok(script, 'Real Gallery interaction script must be present');
    const html = raw.replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, '');
    const context = await browser.newContext({viewport:{width,height:900},colorScheme,reducedMotion:'reduce'});
    const page = await context.newPage(), errors = [], requests = [];
    page.setDefaultTimeout(6000);
    page.on('pageerror', e => errors.push(e.message));
    await page.route('**/*', async route => {
        const request = route.request(), url = new URL(request.url());
        requests.push({url:url.href,method:request.method()});
        assert.equal(request.method(), 'GET');
        if (url.href === urls.css) return route.fulfill({contentType:'text/css',body:assets.css});
        if (request.resourceType() === 'document') {
            return route.fulfill({contentType:'text/html',body:url.hostname === 'www.youtube-nocookie.com' ? '<html style="background:#101c2e;color:white"><body style="font:18px sans-serif;padding:24px">Synthetic YouTube player · embed URL verified</body></html>' : html});
        }
        if (request.resourceType() === 'image') {
            if (broken && url.pathname.endsWith('/science.jpg')) return route.fulfill({status:404,body:''});
            const first = url.pathname.endsWith('/science.jpg'), second = url.pathname.endsWith('/assembly.png');
            return route.fulfill({contentType:'image/svg+xml',body:illustration(first ? 'Science exhibition' : second ? 'School assembly' : 'Gallery preview', first ? '#185c80' : second ? '#785722' : '#234657')});
        }
        if (url.hostname === 'admin.example.test' && url.pathname.endsWith('/assembly.mp4')) {
            return route.fulfill({contentType:'video/mp4',body:Buffer.from('000000206674797069736f6d0000020069736f6d69736f32617663316d703431','hex')});
        }
        if (url.hostname === 'web.example.test' && ['stylesheet','font'].includes(request.resourceType())) {
            const relative = decodeURIComponent(url.pathname).replace(/^\/public\//, '/');
            const target = path.resolve(root, 'public', '.' + relative);
            if (target.startsWith(path.resolve(root, 'public') + path.sep)) {
                try { return await route.fulfill({body:await readFile(target),contentType:request.resourceType() === 'stylesheet' ? 'text/css' : 'font/woff2'}); } catch { /* optional theme asset absent */ }
            }
        }
        return route.fulfill({status:204,body:''});
    });
    await page.goto('https://web.example.test/gallery/qa');
    await page.addScriptTag({content:await readFile(path.join(root,'public/cultivation/assets/js/jquery.min.js'),'utf8')});
    await page.addScriptTag({content:await readFile(path.join(root,'public/cultivation/assets/js/bootstrap.min.js'),'utf8')});
    await page.addScriptTag({content:assets.js.toString()});
    await page.addScriptTag({content:script});
    await page.evaluate(() => document.getElementById('loader')?.remove());
    return {page,context,errors,requests};
}

async function visibleContrast(locator) {
    assert.ok(await locator.isVisible());
    const value = await locator.evaluate(el => {
        const style = getComputedStyle(el), box = el.getBoundingClientRect();
        const rgb = value => value.match(/[\d.]+/g).slice(0,3).map(Number);
        const luminance = color => rgb(color).map(n=>n/255).map(n=>n<=.04045?n/12.92:((n+.055)/1.055)**2.4).reduce((sum,n,i)=>sum+n*[.2126,.7152,.0722][i],0);
        let background = style.backgroundColor, parent = el;
        while (background === 'rgba(0, 0, 0, 0)' && parent.parentElement) {parent=parent.parentElement;background=getComputedStyle(parent).backgroundColor;}
        const a=luminance(style.color),b=luminance(background);
        return {contrast:(Math.max(a,b)+.05)/(Math.min(a,b)+.05),width:box.width,height:box.height,text:el.textContent.trim(),opacity:style.opacity};
    });
    assert.ok(value.text.length > 0);
    assert.equal(value.opacity, '1');
    assert.ok(value.width > 30 && value.height >= 40, JSON.stringify(value));
    assert.ok(value.contrast >= 4.5, JSON.stringify(value));
}

for (const width of [1280,768,390]) for (const theme of ['light','dark']) {
    test(`${width}px ${theme}: separate photo images and selected modal`, async () => {
        const {page,context,errors} = await openFixture('photos.html',width,theme);
        try {
            const cards = page.locator('.gallery-feature-card');
            const expected = [
                'https://admin.example.test/tenant/public/upload/image/PhotoGallery/science.jpg',
                'https://admin.example.test/tenant/public/upload/image/PhotoGallery/assembly.png',
                'https://web.example.test/public/img/campus.jpeg',
            ];
            for (let i=0;i<3;i++) {
                assert.equal(await cards.nth(i).getAttribute('data-image'),expected[i]);
                assert.equal(await cards.nth(i).locator('img').getAttribute('src'),expected[i]);
                await cards.nth(i).click();
                await page.locator('#imageModal.show').waitFor();
                assert.equal(await page.locator('#galleryModalImage').getAttribute('src'),expected[i]);
                assert.ok(await page.locator('#galleryModalImage').isVisible());
                assert.equal(await page.locator('#galleryModalImage').evaluate(el=>getComputedStyle(el).objectFit),'contain');
                if (i===1 && theme==='light') await page.screenshot({path:path.join(output,`photo-modal-${width}.png`)});
                await page.keyboard.press('Escape');
                await page.locator('#imageModal').waitFor({state:'hidden'});
            }
            if (theme==='light') await page.locator('.gallery-featured-grid').screenshot({path:path.join(output,`photos-${width}.png`)});
            assert.deepEqual(errors,[]);
        } finally {await context.close();}
    });
    for (const type of ['embed','file']) test(`${width}px ${theme} ${type}: video CTA contrast, modal fit and source unchanged`, async () => {
        const {page,context,errors,requests} = await openFixture('both-modes.html',width,theme);
        try {
            const trigger = page.locator('.video-feature-grid .video-trigger[data-type="'+type+'"]').first();
            await visibleContrast(trigger.locator('.video-card-cta'));
            assert.ok(await trigger.locator('.video-card-cta svg').isVisible());
            const expected = type === 'embed' ? 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ' : 'https://admin.example.test/tenant/public/upload/image/VideoGallery/assembly.mp4';
            if (type==='embed') await page.locator('.video-feature-grid').screenshot({path:path.join(output,`video-cards-${width}-${theme}.png`)});
            await trigger.click();
            await page.locator('#videoPreviewModal.show').waitFor();
            const player = page.locator(type==='embed'?'#videoPreviewFrame':'#videoPreviewFile');
            assert.equal(await player.getAttribute('src'),expected);
            assert.ok(await player.isVisible());
            assert.equal(await page.locator('#videoPreviewLink').getAttribute('href'),expected);
            const actions = page.locator('#videoPreviewModal .video-modal-actions');
            await actions.scrollIntoViewIfNeeded();
            await visibleContrast(actions.locator('button'));
            await visibleContrast(actions.locator('a'));
            const fit = await page.locator('#videoPreviewModal .modal-content').evaluate(el=>({x:el.getBoundingClientRect().x,right:el.getBoundingClientRect().right,bottom:el.getBoundingClientRect().bottom,top:el.getBoundingClientRect().top,scroll:el.scrollWidth,client:el.clientWidth}));
            assert.ok(fit.x>=0 && fit.right<=width+1 && fit.top>=0 && fit.bottom<=901 && fit.scroll<=fit.client+1,JSON.stringify(fit));
            assert.ok(await page.locator('#videoPreviewDescription').isVisible());
            await page.screenshot({path:path.join(output,`video-modal-${width}-${theme}-${type}.png`)});
            await actions.locator('button').click();
            await page.locator('#videoPreviewModal').waitFor({state:'hidden'});
            assert.deepEqual(errors,[]);
            assert.ok(requests.every(r=>!r.url.includes('/public/public/')));
        } finally {await context.close();}
    });
}

test('a broken photo alone falls back without replacing another card', async () => {
    const {page,context} = await openFixture('photos.html',390,'light',true);
    try {
        await page.waitForFunction(()=>document.querySelector('.gallery-feature-card img').src.endsWith('/public/img/campus.jpeg'));
        assert.equal(await page.locator('.gallery-feature-card').nth(1).locator('img').getAttribute('src'),'https://admin.example.test/tenant/public/upload/image/PhotoGallery/assembly.png');
        await page.locator('.gallery-feature-card').first().click();
        assert.equal(await page.locator('#galleryModalImage').getAttribute('src'),'https://web.example.test/public/img/campus.jpeg');
    } finally {await context.close();}
});
test('unset media origin shows unavailable, not repeated fake photo cards', async () => {
    const {page,context} = await openFixture('photos-unconfigured.html',390,'light');
    try {
        for (let i=0;i<2;i++) {
            const card=page.locator('.gallery-feature-card').nth(i);
            assert.equal(await card.locator('img').count(),0);
            await card.click();
            await page.locator('#imageModal.show').waitFor();
            assert.ok(await page.locator('#galleryModalUnavailable').isVisible());
            assert.ok(!await page.locator('#galleryModalImage').isVisible());
            assert.ok(!await page.locator('#galleryModalDownload').isVisible());
            await page.keyboard.press('Escape');
            await page.locator('#imageModal').waitFor({state:'hidden'});
        }
        assert.ok((await page.locator('.gallery-feature-card').nth(2).locator('img').getAttribute('src')).endsWith('/public/img/campus.jpeg'));
    } finally {await context.close();}
});
