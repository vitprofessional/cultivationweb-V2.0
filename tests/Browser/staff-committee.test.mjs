import test from 'node:test';
import assert from 'node:assert/strict';
import {createRequire} from 'node:module';
const {chromium}=createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE||'playwright');
for(const width of [390,768,1280]) for(const path of ['our-staff','our-comittee']) test(`${path} ${width}px`,async()=>{
 const browser=await chromium.launch({headless:true,executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe'});
 const page=await browser.newPage({viewport:{width,height:1000},reducedMotion:'reduce'});
 try{
  assert.equal((await page.goto('http://localhost/cultivationweb-V2.0/'+path,{waitUntil:'networkidle'})).status(),200);
  await page.addStyleTag({content:'#loader{display:none!important}'});
  const cards=page.locator('.team-card');assert.ok(await cards.count()>0);
  const first=cards.first();await first.scrollIntoViewIfNeeded();
  const frame=await first.locator('.people-photo-frame').boundingBox();assert.ok(Math.abs(frame.width/frame.height-.8)<.01);
  assert.equal(await first.locator('.team-profile-link').count(),1);
  assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
  await page.screenshot({path:process.env.TEMP+'/team-'+path+'-'+width+'.png',fullPage:true});
  // DOM fixture: exercise multiple members and long labels without writing operational data.
  await page.evaluate(()=>{const grid=document.querySelector('.team-grid');const original=grid.firstElementChild;grid.replaceChildren(...Array.from({length:5},(_,i)=>{const card=original.cloneNode(true);card.querySelector('.team-name').textContent='Long Member Name for Responsive Directory '+i;card.querySelector('.team-role').textContent='Senior Institutional Support and Governance Member';return card;}));});
  const heights=await cards.evaluateAll(es=>es.map(e=>e.getBoundingClientRect().height));assert.ok(heights.every(h=>Math.abs(h-heights[0])<1));
  assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
 }finally{await browser.close();}
});
