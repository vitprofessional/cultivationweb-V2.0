import test from 'node:test';
import assert from 'node:assert/strict';
import {createRequire} from 'node:module';
const {chromium}=createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE||'playwright');
for(const width of [390,768,1280]) for(const path of ['our-teacher','our-staff','our-comittee']) test(`${path} CTA contrast ${width}px`,async()=>{
 const browser=await chromium.launch({headless:true,executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const page=await browser.newPage({viewport:{width,height:1000}});
 try{
  await page.goto('http://localhost/cultivationweb-V2.0/'+path,{waitUntil:'networkidle'});
  const href=await page.locator('.teacher-action-btn,.team-profile-link').first().getAttribute('href');
  assert.equal((await page.goto(href,{waitUntil:'networkidle'})).status(),200);
  await page.addStyleTag({content:'#loader{display:none!important}html{scroll-behavior:auto!important}'});
  const buttons=page.locator('.tp-actions a');assert.ok(await buttons.count());
  for(const button of await buttons.all()){
   await page.mouse.move(0,0);await button.evaluate(el=>el.blur());
   const normal=await button.evaluate(el=>({color:getComputedStyle(el).color,background:getComputedStyle(el).backgroundImage}));
   await button.hover();
   const check=async()=>{await page.waitForFunction(el=>getComputedStyle(el).color==='rgb(255, 255, 255)'&&getComputedStyle(el).backgroundColor==='rgb(7, 81, 138)'&&[...el.querySelectorAll('*')].every(child=>getComputedStyle(child).color==='rgb(255, 255, 255)'),await button.elementHandle(),{timeout:3000});};
   await check();await page.mouse.move(0,0);await page.keyboard.press('Tab');await button.focus();await check();
   assert.equal(await button.evaluate(el=>getComputedStyle(el).outlineWidth),'3px');
   await button.evaluate(el=>el.blur());
   await page.waitForFunction(({el,color})=>getComputedStyle(el).color===color,{el:await button.elementHandle(),color:normal.color});
   assert.deepEqual(await button.evaluate(el=>({color:getComputedStyle(el).color,background:getComputedStyle(el).backgroundImage})),normal);
  }
  assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
 }finally{await browser.close();}
});
