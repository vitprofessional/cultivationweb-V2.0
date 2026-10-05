import test,{before,after} from 'node:test';
import assert from 'node:assert/strict';
import {createRequire} from 'node:module';
const {chromium}=createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE_PATH||'playwright');
import {mkdtemp,readFile} from 'node:fs/promises';
import {tmpdir} from 'node:os';
import {join} from 'node:path';
import {execFile} from 'node:child_process';
import {promisify} from 'node:util';
let browser,directory;
before(async()=>{
 directory=await mkdtemp(join(tmpdir(),'login-hub-qa-'));
 const result=await promisify(execFile)('php',['vendor/phpunit/phpunit/phpunit','--no-progress','--do-not-cache-result','--filter=LoginHubTest'],{env:{...process.env,APP_ENV:'testing',DB_DATABASE:'cultivation_test',LOGIN_HUB_QA_DIR:directory},timeout:120000});
 assert.match(result.stdout,/Tests: 5/);
 browser=await chromium.launch({headless:true,executablePath:process.env.ASYNC_BROWSER_EXECUTABLE||'C:/Program Files/Google/Chrome/Application/chrome.exe'});
});
after(async()=>{await browser?.close();console.log('Login Hub QA: '+directory)});
for(const width of [390,768,1280])for(const state of ['configured','missing'])test(`${state} portals ${width}px`,async()=>{
 const page=await browser.newPage({viewport:{width,height:950}});
 await page.setContent(await readFile(join(directory,state+'.html'),'utf8'));
 assert.equal(await page.locator('[data-portal]').count(),4);
 assert.equal(await page.locator('.portal-grid').evaluate(e=>getComputedStyle(e).gridTemplateColumns.split(' ').length),width===390?1:width===768?2:4);
 assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
 if(state==='configured'){
  assert.deepEqual(await page.locator('a.portal-action').evaluateAll(es=>es.map(e=>e.getAttribute('href'))),['https://admin.example.test/login','https://admin.example.test/teacher/login','https://admin.example.test/portal/login','https://admin.example.test/portal/login']);
  await page.keyboard.press('Tab');assert.equal(await page.locator('a.portal-action').first().evaluate(e=>e===document.activeElement),true);
  assert.equal(await page.locator('a.portal-action').first().evaluate(e=>getComputedStyle(e).outlineStyle),'solid');
 }else assert.equal(await page.locator('button:disabled').count(),4);
 await page.screenshot({path:join(directory,`${state}-${width}.png`),fullPage:true});await page.close();
});
for(const width of [390,768,1280])test(`real Login Hub ${width}px`,async()=>{
 const page=await browser.newPage({viewport:{width,height:950}});
 const response=await page.goto((process.env.WEBSITE_QA_URL||'http://localhost/cultivationweb-V2.0')+'/login',{waitUntil:'domcontentloaded'});
 assert.equal(response.status(),200);await page.locator('#loader').waitFor({state:'hidden',timeout:20000});
 assert.equal(await page.locator('.edu-page-title').count(),0);
 assert.equal(await page.locator('[data-portal]').count(),4);
 assert.equal(await page.locator('.portal-grid').evaluate(e=>getComputedStyle(e).gridTemplateColumns.split(' ').length),width===390?1:width===768?2:4);
 assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1));
 for(const key of ['admin','teacher','student','guardian']){
  const control=page.locator(`[data-portal="${key}"] .portal-action`);await control.scrollIntoViewIfNeeded();
  const box=await control.boundingBox();assert.ok(box&&box.y>=0&&box.y+box.height<=950,'each portal action is reachable');
 }
 await page.screenshot({path:join(directory,`real-bottom-${width}.png`)});
 await page.evaluate(()=>{document.body.scrollTop=0;document.documentElement.scrollTop=0;window.scrollTo(0,0)});
 await page.screenshot({path:join(directory,`real-${width}.png`),fullPage:true});await page.close();
});
