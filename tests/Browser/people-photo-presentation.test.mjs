import test,{before,after} from 'node:test';
import assert from 'node:assert/strict';
import {createRequire} from 'node:module';
import {mkdtemp} from 'node:fs/promises';
import {tmpdir} from 'node:os';
import {join} from 'node:path';
const {chromium}=createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE || 'playwright');
const base=process.env.WEBSITE_QA_URL || 'http://localhost/cultivationweb-V2.0';
let browser,directory;
before(async()=>{directory=await mkdtemp(join(tmpdir(),'people-portraits-'));browser=await chromium.launch({headless:true,executablePath:process.env.ASYNC_BROWSER_EXECUTABLE || 'C:/Program Files/Google/Chrome/Application/chrome.exe'});});
after(async()=>{await browser?.close();console.log('Portrait screenshots: '+directory);});
for(const width of [390,768,1280]) test(`shared portraits and mixed-size images at ${width}px`,async()=>{
 const page=await browser.newPage({viewport:{width,height:900}});
 try {
  for(const [path,variant] of [['/our-teacher','directory'],['/student','compact'],['/our-teacher/2','profile'],['/student/1','profile']]) {
   assert.equal((await page.goto(base+path,{waitUntil:'networkidle'})).status(),200);
   // Offline QA blocks existing CDN scripts; suppress only the unrelated loader
   // overlay so screenshots inspect the real portrait component underneath.
   await page.addStyleTag({content:'#loader{display:none!important}'});
   const image=page.locator('[data-people-portrait]').first(),frame=image.locator('..');
   await image.scrollIntoViewIfNeeded();
   await page.waitForFunction(img=>img.complete && img.naturalWidth>0,await image.elementHandle());
   const box=await frame.boundingBox();
   assert.ok(Math.abs(box.width/box.height-.8)<.01,'frame keeps 4:5 ratio');
   assert.ok(box.width>0 && box.height>0,'portrait has visible responsive geometry');
   assert.equal(await image.evaluate(img=>getComputedStyle(img).objectFit),'cover');
   if(!await image.evaluate(img=>img.classList.contains('is-fallback'))) {
    assert.equal(await image.evaluate(img=>getComputedStyle(img).objectPosition),'50% 28%');
   }
   assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
   await frame.screenshot({path:join(directory,path.replaceAll('/','-')+'-'+width+'.png')});
   if(variant==='directory') {
    const sizes=await page.locator('.people-photo-frame--directory').evaluateAll(es=>es.map(e=>e.getBoundingClientRect().height));
    assert.ok(sizes.length>1 && sizes.every(h=>Math.abs(h-box.height)<1),'all directory photo heights match');
   }
   if(variant==='profile') {
    for(const [name,w,h] of [['landscape',900,450],['square',600,600],['tall',500,1600],['small',60,75],['transparent',500,625]]) {
     const svg=`<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}">${name==='transparent'?'':`<rect width="100%" height="100%" fill="#dae7f2"/>`}<circle cx="${w/2}" cy="${h/3}" r="${w/5}" fill="#7896b0"/></svg>`;
     await image.evaluate((img,src)=>{img.removeAttribute('data-portrait-fallback-active');img.src=src;},'data:image/svg+xml,'+encodeURIComponent(svg));
     await page.waitForFunction(img=>img.complete && img.naturalWidth>0,await image.elementHandle());
     assert.equal(await image.evaluate(img=>getComputedStyle(img).objectFit),'cover');
     assert.equal(await image.evaluate(img=>getComputedStyle(img).objectPosition),'50% 28%');
     assert.equal(await image.evaluate(img=>getComputedStyle(img).transform),'none');
     assert.deepEqual(await frame.boundingBox(),box,'source changes do not shift frame geometry');
    }
    await image.evaluate(img=>{img.src='data:image/png;base64,broken';});
    await page.waitForFunction(img=>img.complete && img.naturalWidth>0 && img.classList.contains('is-fallback'),await image.elementHandle());
    assert.equal(await image.evaluate(img=>getComputedStyle(img).objectFit),'cover');
    const fallbackSvg=await image.evaluate(img=>decodeURIComponent(img.src.split(',')[1]));
    assert.match(fallbackSvg,/<ellipse[^>]+fill=/);
    assert.ok(!fallbackSvg.includes('stroke='),'solid silhouette replaces line-art');
    assert.deepEqual(await frame.boundingBox(),box,'fallback keeps identical dimensions');
    await frame.screenshot({path:join(directory,'fallback-'+path.replaceAll('/','-')+'-'+width+'.png')});
   }
  }
 } finally {await page.close();}
});
