import test,{before,after} from 'node:test';
import assert from 'node:assert/strict';
import {createRequire} from 'node:module';
import {mkdtemp} from 'node:fs/promises';
import {join} from 'node:path';
import {tmpdir} from 'node:os';
const {chromium}=createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE_PATH||'playwright');
let browser,directory;
before(async()=>{directory=await mkdtemp(join(tmpdir(),'header-portal-qa-'));browser=await chromium.launch({headless:true,executablePath:process.env.ASYNC_BROWSER_EXECUTABLE||'C:/Program Files/Google/Chrome/Application/chrome.exe'})});
after(async()=>{await browser?.close();console.log('Header CTA QA: '+directory)});
for(const width of [390,768,1280])for(const path of ['/','/login'])test(`${path} Portal CTA ${width}px`,async()=>{
 const page=await browser.newPage({viewport:{width,height:950}});
 const response=await page.goto((process.env.WEBSITE_QA_URL||'http://localhost/cultivationweb-V2.0')+path,{waitUntil:'domcontentloaded'});
 assert.equal(response.status(),200);
 // CDN-dependent preloader is unrelated to the locally rendered header under test.
 await page.addStyleTag({content:'#loader{display:none!important}'});
 const cta=page.locator('.header-portal-cta');assert.equal(await cta.count(),1);assert.ok(await cta.isVisible());
 assert.ok((await cta.getAttribute('href')).endsWith('/login'));
 assert.equal(await page.locator('#primary-navigation a').filter({hasText:/^Login$/i}).count(),0);
 const admission=page.locator('#rs-header').getByRole('link',{name:'Admission Information',exact:true});assert.ok(await admission.isVisible());
 const label=width<1101?'.portal-mobile-label':'.portal-desktop-label';assert.ok(await cta.locator(label).isVisible());
 assert.notEqual(await cta.locator(label).evaluate(e=>getComputedStyle(e).color),await cta.evaluate(e=>getComputedStyle(e).backgroundColor),'label contrasts with button background');
 const style=await cta.evaluate(e=>{const s=getComputedStyle(e);return {background:s.backgroundColor,color:s.color,radius:s.borderRadius,border:s.borderTopWidth}});
 assert.deepEqual(style,{background:'rgb(16, 44, 86)',color:'rgb(255, 255, 255)',radius:'5px',border:'0px'});
 assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
 const a=await cta.boundingBox(),b=await admission.boundingBox();
 assert.ok(a.x>=b.x+b.width-1&&a.x+a.width<=width+1,'CTA beside admission without overflow');
 assert.ok(Math.abs(a.height-b.height)<=1&&Math.abs(a.y-b.y)<=1,'CTA height and vertical alignment match admission');
 await cta.focus();await page.keyboard.press('Shift+Tab');await page.keyboard.press('Tab');
 assert.ok(await cta.evaluate(e=>e===document.activeElement&&getComputedStyle(e).outlineStyle==='solid'));
 if(width<1101){const toggle=page.locator('button.rs-menu-toggle');await toggle.click();assert.equal(await toggle.getAttribute('aria-expanded'),'true');await toggle.click();assert.equal(await toggle.getAttribute('aria-expanded'),'false')}
 await page.screenshot({path:join(directory,`${path==='/'?'home':'login'}-${width}.png`)});
 await cta.click();await page.waitForURL('**/login');assert.equal(await page.locator('#portal-heading').innerText(),'Choose Your Portal');await page.close();
});
