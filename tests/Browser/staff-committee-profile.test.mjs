import test from 'node:test';
import assert from 'node:assert/strict';
import {createRequire} from 'node:module';
const {chromium}=createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE||'playwright');
for(const width of [390,768,1280]) for(const path of ['our-staff','our-comittee']) test(`${path} profile ${width}px`,async()=>{
 const browser=await chromium.launch({headless:true,executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const page=await browser.newPage({viewport:{width,height:1000},reducedMotion:'reduce'});
 try{
  await page.goto('http://localhost/cultivationweb-V2.0/'+path,{waitUntil:'networkidle'});
  const link=page.locator('.team-profile-link').first();const href=await link.getAttribute('href');assert.ok(href.includes('/'+path+'/'));
  assert.equal((await page.goto(href,{waitUntil:'networkidle'})).status(),200);
  await page.addStyleTag({content:'#loader{display:none!important}html{scroll-behavior:auto!important}'});
  assert.equal(await page.locator('.tp-hero h1').count(),1);
  assert.equal(await page.locator('.tp-identity h1,.tp-identity .tp-role').count(),0);
  const frame=await page.locator('.people-photo-frame--profile').boundingBox();assert.ok(Math.abs(frame.width/frame.height-.8)<.01);
  assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
  await page.screenshot({path:process.env.TEMP+'/'+path+'-profile-'+width+'.png',fullPage:true});
  await page.locator('.ts-name').evaluate(el=>el.textContent='Long Professional Name '.repeat(7));
  if(await page.locator('.tp-wide dd').count()) await page.locator('.tp-wide dd').evaluate(el=>el.textContent='Long address '.repeat(30));
  const img=page.locator('.people-portrait');await img.evaluate(el=>el.dispatchEvent(new Event('error')));assert.ok((await img.getAttribute('src')).startsWith('data:image/svg+xml'));
  assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
  assert.equal((await page.goto('http://localhost/cultivationweb-V2.0/'+path+'/999999999',{waitUntil:'domcontentloaded'})).status(),404);
 }finally{await browser.close();}
});
