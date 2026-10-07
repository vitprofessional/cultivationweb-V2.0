import test from 'node:test';
import assert from 'node:assert/strict';
import {createRequire} from 'node:module';
const {chromium}=createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE||'playwright');
for(const width of [390,768,1280]) test(`Global public scroll ${width}px`,async()=>{
 const browser=await chromium.launch({headless:true,executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const page=await browser.newPage({viewport:{width,height:800}});
 const base='http://localhost/cultivationweb-V2.0';
 try{
  await page.goto(base+'/our-teacher',{waitUntil:'networkidle'});
  const profile=await page.locator('.teacher-action-btn').first().getAttribute('href');
  for(const url of [base,base+'/our-teacher',profile,base+'/our-staff',base+'/our-comittee',base+'/about-us',base+'/login']){
   assert.equal((await page.goto(url,{waitUntil:'networkidle'})).status(),200,url);
   await page.addStyleTag({content:'#loader{display:none!important}'});
   await page.evaluate(()=>window.scrollTo({top:0,behavior:'instant'}));
   await page.waitForFunction(()=>document.getElementById('scrollUp').hidden);
   assert.equal(await page.locator('#scrollUp').count(),1);
   const before=await page.locator('.full-width-header').evaluate(el=>el.offsetHeight);
   await page.evaluate(()=>window.scrollTo({top:500,behavior:'instant'}));
   await page.waitForFunction(()=>!document.getElementById('scrollUp').hidden);
   const menu=await page.locator('.menu-sticky').boundingBox();assert.ok(Math.abs(menu.y)<2,`${url}: menu top ${menu.y}`);
   assert.equal(await page.locator('.full-width-header').evaluate(el=>el.offsetHeight),before);
   assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1),url);
   if(width<1101){
    const toggle=page.locator('.rs-menu-toggle');await toggle.click();assert.equal(await toggle.getAttribute('aria-expanded'),'true');
    await page.keyboard.press('Escape');assert.equal(await toggle.getAttribute('aria-expanded'),'false');
    await page.waitForFunction(()=>scrollY>300);
   }
   await page.locator('#scrollUp').focus();await page.keyboard.press('Enter');
   await page.waitForFunction(()=>scrollY<2&&document.getElementById('scrollUp').hidden);
   await page.evaluate(()=>window.scrollTo({top:500,behavior:'instant'}));
   await page.waitForFunction(()=>!document.getElementById('scrollUp').hidden);
   await page.locator('#scrollUp').click();await page.waitForFunction(()=>scrollY<2);
  }
 }finally{await browser.close();}
});
