import test from 'node:test';
import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { mkdir } from 'node:fs/promises';
import path from 'node:path';
import os from 'node:os';
const { chromium } = createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE || 'playwright');
for (const width of [390, 768, 1280]) test(`Student database ${width}px`, async () => {
    const browser = await chromium.launch({ headless: true, executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
    const page = await browser.newPage({ viewport: { width, height: 1000 } });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    try {
        const response = await page.goto('http://localhost/cultivationweb-V2.0/student', { waitUntil: 'domcontentloaded', timeout: 60000 });
        assert.equal(response.status(), 200);
        await page.locator('#loader').waitFor({ state: 'hidden', timeout: 15000 });
        await page.waitForFunction(() => window.jQuery?.fn.dataTable?.isDataTable('#studentTable'));
        assert.deepEqual(await page.locator('#studentTable thead th').allInnerTexts(), ['STUDENT INFO', 'STUDENT DETAILS', 'VIEW']);
        const total = await page.evaluate(() => jQuery('#studentTable').DataTable().rows().count());
        assert.equal(Number(await page.locator('.student-summary-item strong').first().innerText()), total);
        const name = await page.locator('#studentTable tbody .student-name').first().innerText();
        await page.locator('#student-search').fill(name);
        assert.ok(await page.evaluate(() => jQuery('#studentTable').DataTable().rows({ search: 'applied' }).count()) > 0);
        await page.locator('.student-reset').click();
        await page.waitForFunction(n => jQuery('#studentTable').DataTable().rows({ search: 'applied' }).count() === n, total);
        for (const key of ['class', 'section', 'session', 'department']) {
            const select = page.locator(`[data-student-filter="${key}"]`);
            const choices = await select.locator('option').evaluateAll(options => options.map(o => o.value).filter(Boolean));
            if (choices.length) {
                await select.selectOption(choices[0]);
                assert.ok(await page.evaluate(() => jQuery('#studentTable').DataTable().rows({ search: 'applied' }).count()) > 0);
                assert.ok(await page.locator('#studentTable tbody tr').evaluateAll((rows, { key, value }) => rows.every(row => row.dataset[key] === value), { key, value: choices[0] }));
                await select.selectOption('');
            }
        }
        await page.locator('#student-per-page').selectOption('10');
        assert.equal(await page.evaluate(() => jQuery('#studentTable').DataTable().page.len()), 10);
        if (total > 10) {
            await page.locator('#studentTable_next').click();
            assert.equal(await page.evaluate(() => jQuery('#studentTable').DataTable().page.info().page), 1);
        }
        await page.locator('.student-reset').click();
        await page.waitForFunction(() => jQuery('#studentTable').DataTable().page.len() === 25 && jQuery('#studentTable').DataTable().page.info().page === 0);
        assert.equal((await page.request.get(await page.locator('.student-view-btn').first().getAttribute('href'))).status(), 200);
        assert.ok(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1));
        const output = path.join(os.tmpdir(), 'phase47-students');
        await mkdir(output, { recursive: true });
        await page.evaluate(() => scrollTo({ top: 0, behavior: 'instant' }));
        await page.waitForTimeout(350);
        await page.screenshot({ path: path.join(output, `student-${width}.png`), fullPage: true });
        await page.screenshot({ path: path.join(output, `student-${width}-viewport.png`) });
        assert.deepEqual(errors, []);
    } finally { await browser.close(); }
});
