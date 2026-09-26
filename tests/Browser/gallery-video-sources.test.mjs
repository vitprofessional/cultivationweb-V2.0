// Actual rendered Web Blade fixture; all media/network requests are intercepted.
import test, { before, after } from 'node:test';
import assert from 'node:assert/strict';
import { readFile } from 'node:fs/promises';
import { createRequire } from 'node:module';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
const { chromium } = createRequire(import.meta.url)('playwright');
const root = fileURLToPath(new URL('../../', import.meta.url));
const fixtures = process.env.GALLERY_WEB_QA_DIR;
if (!fixtures || path.resolve(fixtures).toLowerCase().startsWith(path.resolve(root).toLowerCase())) throw new Error('Use external synthetic fixtures only.');
const raw = await readFile(path.join(fixtures, 'both-modes.html'), 'utf8');
const script = [...raw.matchAll(/<script\b[^>]*>([\s\S]*?)<\/script>/gi)].map(m=>m[1]).find(s=>s.includes("const modalElement = document.getElementById('videoPreviewModal')"));
assert.ok(script);
const html = raw.replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, '').replace(/<link\b[^>]*>/gi, '');
const jquery = await readFile(path.join(root,'public/cultivation/assets/js/jquery.min.js'),'utf8');
const bootstrap = await readFile(path.join(root,'public/cultivation/assets/js/bootstrap.min.js'),'utf8');
const css = await readFile(path.join(root,'public/back-office/css/bootstrap.min.css'),'utf8');
let browser;
before(async()=>{browser=await chromium.launch({headless:true,...(process.env.ASYNC_BROWSER_EXECUTABLE?{executablePath:process.env.ASYNC_BROWSER_EXECUTABLE}:{})});});
after(async()=>{await browser?.close();});
for (const width of [1280,768,390]) for (const type of ['embed','file']) test(width+'px public '+type+': existing player receives the correct authority',async()=>{
    const context=await browser.newContext({viewport:{width,height:900},reducedMotion:'reduce'});
    try {
        const page=await context.newPage(),errors=[],unexpected=[];
        page.setDefaultTimeout(5000);
        page.on('pageerror',e=>errors.push(e.message));
        await page.route('**/*',route=>{
            const r=route.request(),url=new URL(r.url());
            if (r.method()!=='GET') {unexpected.push(r.method());return route.abort();}
            if (url.origin==='https://web.example.test' && r.isNavigationRequest()) return route.fulfill({contentType:'text/html',body:html});
            if (url.origin==='https://www.youtube-nocookie.com' && url.pathname==='/embed/dQw4w9WgXcQ') return route.fulfill({contentType:'text/html',body:'Synthetic player'});
            if (url.origin==='https://admin.example.test' && url.pathname==='/tenant/public/upload/image/VideoGallery/assembly.mp4') return route.fulfill({contentType:'video/mp4',body:Buffer.from('000000206674797069736f6d0000020069736f6d69736f32617663316d703431','hex')});
            if (['image','font','stylesheet'].includes(r.resourceType())) return route.fulfill({status:204,body:''});
            unexpected.push(url.href);return route.abort();
        });
        await page.goto('https://web.example.test/gallery/video');
        await page.addStyleTag({content:css});
        await page.addScriptTag({content:jquery}); await page.addScriptTag({content:bootstrap}); await page.addScriptTag({content:script});
        await page.evaluate(()=>document.getElementById('loader')?.remove());
        const trigger=page.locator('.video-trigger[data-type="'+type+'"]').first();
        const expected=type==='embed'?'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ':'https://admin.example.test/tenant/public/upload/image/VideoGallery/assembly.mp4';
        await trigger.click();
        await page.locator('#videoPreviewModal').waitFor({state:'visible'});
        const player=page.locator(type==='embed'?'#videoPreviewFrame':'#videoPreviewFile');
        assert.equal(await player.getAttribute('src'),expected);assert.ok(await player.isVisible());
        assert.equal(await page.locator('#videoPreviewLink').getAttribute('href'),expected);
        assert.ok(!expected.includes('/public/public/'));
        // This test covers source selection/rendering. The unrelated modal framework
        // lifecycle is unchanged; production also loads its existing Bootstrap 5 CDN.
        assert.deepEqual(errors,[]);assert.deepEqual(unexpected,[]);
    } finally {await context.close();}
});
