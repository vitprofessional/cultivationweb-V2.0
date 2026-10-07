import test, {before, after} from 'node:test';
import assert from 'node:assert/strict';
import {createRequire} from 'node:module';
const {chromium}=createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE || 'playwright');
let browser;
const base = process.env.WEBSITE_QA_URL || 'http://localhost/cultivationweb-V2.0';
before(async()=>{browser=await chromium.launch({headless:true,executablePath:process.env.ASYNC_BROWSER_EXECUTABLE || 'C:/Program Files/Google/Chrome/Application/chrome.exe'});});
after(async()=>{await browser?.close();});
for(const width of [390,768,1280]) test(`real people photos and portal popups at ${width}px`,async()=>{
 const context=await browser.newContext({viewport:{width,height:900}});
 const page=await context.newPage();
 try {
  for(const [path,folder] of [['/student','student'],['/our-teacher','teacher']]) {
   assert.equal((await page.goto(base+path,{waitUntil:'networkidle'})).status(),200);
   assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
   const portrait=page.locator(`img[src*="/cultivation/public/upload/image/${folder}/"]`).first();
   assert.ok(await portrait.count(),'real Admin-hosted portrait exists');
   const src=await portrait.getAttribute('src');
   assert.ok(!src.includes('/public/public/'));
   assert.equal((await page.request.get(src)).status(),200);
   await portrait.scrollIntoViewIfNeeded();
   await page.waitForFunction(img=>img.complete && img.naturalWidth>0,await portrait.elementHandle());
   assert.ok(await portrait.evaluate(img=>img.complete && img.naturalWidth>0));
   const profile=folder==='student'
    ? portrait.locator('xpath=ancestor::tr').locator(`a[href*="${path}/"]`).first()
    : portrait.locator('xpath=ancestor::article').locator('a.teacher-action-btn').first();
   if(await profile.count()) {
    const href=await profile.getAttribute('href');
    assert.equal((await page.goto(new URL(href,base).href,{waitUntil:'networkidle'})).status(),200);
    const profilePortrait=page.locator(`img[src*="/cultivation/public/upload/image/${folder}/"]`).first();
    await profilePortrait.scrollIntoViewIfNeeded();
    await page.waitForFunction(img=>img.complete && img.naturalWidth>0,await profilePortrait.elementHandle());
    assert.ok(await page.locator(`img[src*="/cultivation/public/upload/image/${folder}/"]`).first().evaluate(img=>img.complete && img.naturalWidth>0));
    await page.route(src,route=>route.abort());
    await page.reload({waitUntil:'networkidle'});
    const fallback=page.locator(folder==='student'?'.student-profile-photo':'.ts-photo');
    await fallback.scrollIntoViewIfNeeded();
    await page.waitForFunction(img=>img.complete && img.naturalWidth>0 && img.src===img.dataset.portraitFallback,await fallback.elementHandle());
    await page.unroute(src);
   }
  }
  for(const path of ['/','/about-us']) {
   assert.equal((await page.goto(base+path,{waitUntil:'networkidle'})).status(),200);
   const about=page.locator('img[src*="/upload/image/cultivation/about-"]').first();
   assert.ok(await about.count(),'canonical About image is rendered');
   await about.scrollIntoViewIfNeeded();
   await page.waitForFunction(img=>img.complete && img.naturalWidth>0,await about.elementHandle());
   assert.ok(!(await about.getAttribute('src')).includes('/public/public/'));
  }
  assert.equal((await page.goto(base+'/login',{waitUntil:'networkidle'})).status(),200);
  assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
  for(const role of ['admin','teacher','student','guardian']) {
   const card=page.locator(`[data-portal="${role}"]`);
   const link=card.locator('a.portal-action');
   if(await link.count()) {
    assert.equal(await link.getAttribute('target'),'_blank');
    assert.equal(await link.getAttribute('rel'),'noopener noreferrer');
    const url=await link.getAttribute('href');
    await context.route(url,route=>route.fulfill({status:200,contentType:'text/html',body:'<title>Portal navigation QA</title>'}));
    const popupEvent=page.waitForEvent('popup');
    await link.click();
    const popup=await popupEvent;
    await popup.waitForLoadState('domcontentloaded');
    assert.equal(popup.url(),url);
    assert.equal(await popup.evaluate(()=>window.opener),null);
    assert.equal(page.url(),base+'/login');
    await popup.close();
   } else assert.ok(await card.locator('button').isDisabled());
  }
 } finally {await context.close();}
});
