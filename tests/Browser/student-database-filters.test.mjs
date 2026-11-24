import test from 'node:test';
import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
const { chromium } = createRequire(import.meta.url)(process.env.PLAYWRIGHT_MODULE || 'playwright');

// Public Student Database: Class follows Session, Section follows Session and Class, and the
// text search matches only the student's name and ID. Cases are derived from the loaded rows.
async function open() {
    const browser = await chromium.launch({ headless: true, executablePath: 'C:/Program Files/Google/Chrome/Application/chrome.exe' });
    const page = await browser.newPage({ viewport: { width: 1280, height: 1000 } });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const response = await page.goto('http://localhost/cultivationweb-V2.0/student', { waitUntil: 'domcontentloaded', timeout: 60000 });
    assert.equal(response.status(), 200);
    await page.waitForFunction(() => window.jQuery?.fn.dataTable?.isDataTable('#studentTable'));
    const rows = await page.evaluate(() => jQuery('#studentTable').DataTable().rows().nodes().toArray().map(row => ({
        ...row.dataset,
        text: (row.querySelector('.student-name').textContent + ' ' + row.querySelector('.student-id').textContent.replace(/^\s*ID:\s*/, '')).toLowerCase(),
    })));
    const offered = key => page.locator(`[data-student-filter="${key}"] option`).evaluateAll(options => options.map(o => o.value).filter(Boolean));
    const initial = { class: await offered('class'), section: await offered('section'), session: await offered('session'), department: await offered('department') };
    return { browser, page, errors, rows, offered, initial };
}
const value = (page, key) => page.locator(`[data-student-filter="${key}"]`).inputValue();
const choose = (page, key, choice) => page.locator(`[data-student-filter="${key}"]`).selectOption(choice);
const appliedRows = page => page.evaluate(() => jQuery('#studentTable').DataTable().rows({ search: 'applied' }).nodes().toArray().map(row => ({ ...row.dataset })));
/** The options a child should list: values the loaded rows carry under the chosen parents, in the original order. */
const expected = (rows, initial, key, scope) => {
    const present = new Set(rows.filter(row => Object.entries(scope).every(([parent, choice]) => !choice || row[parent] === choice)).map(row => row[key]));
    return initial[key].filter(choice => present.has(choice));
};

test('Class lists only the chosen Session, and Section only the chosen Session and Class', async () => {
    const { browser, page, errors, rows, offered, initial } = await open();
    try {
        for (const session of ['', ...initial.session]) {
            await choose(page, 'session', session);
            assert.deepEqual(await offered('class'), expected(rows, initial, 'class', { session }), `classes for session ${session}`);
            assert.deepEqual(await offered('section'), expected(rows, initial, 'section', { session }), `sections for session ${session}`);
            assert.deepEqual(await offered('department'), initial.department, 'Department does not cascade');
            for (const klass of await offered('class')) {
                await choose(page, 'class', klass);
                assert.deepEqual(await offered('section'), expected(rows, initial, 'section', { session, class: klass }), `sections for ${session}/${klass}`);
                const shown = await appliedRows(page);
                assert.ok(shown.length > 0 && shown.every(row => (!session || row.session === session) && row.class === klass));
            }
            await choose(page, 'class', '');
        }
        assert.deepEqual(errors, []);
    } finally { await browser.close(); }
});

test('a Class or Section that the new parent choice no longer offers resets to All', async (t) => {
    const { browser, page, errors, rows, offered, initial } = await open();
    try {
        const has = (scope) => rows.some(row => Object.entries(scope).every(([key, choice]) => row[key] === choice));
        const staleClass = initial.class.flatMap(klass => initial.session.map(session => ({ klass, session })))
            .find(({ klass, session }) => !has({ class: klass, session }));
        const staleSection = initial.class.flatMap(klass => initial.section.flatMap(section => initial.session.map(session => ({ klass, section, session }))))
            .find(({ klass, section, session }) => has({ class: klass, section }) && has({ class: klass, session }) && !has({ class: klass, section, session }));
        if (!staleClass || !staleSection) t.skip('the loaded rows have no stale Class/Section combination');

        if (staleClass) {
            await choose(page, 'class', staleClass.klass);
            await choose(page, 'session', staleClass.session);
            assert.equal(await value(page, 'class'), '', 'class reset to All');
            assert.ok(!(await offered('class')).includes(staleClass.klass));
            const shown = await appliedRows(page);
            assert.ok(shown.every(row => row.session === staleClass.session), 'the table follows the reset filters');
            assert.equal(shown.length, rows.filter(row => row.session === staleClass.session).length);
            await page.locator('.student-reset').click();
        }
        if (staleSection) {
            await choose(page, 'class', staleSection.klass);
            await choose(page, 'section', staleSection.section);
            await choose(page, 'session', staleSection.session);
            assert.equal(await value(page, 'class'), staleSection.klass, 'a class the session still has is kept');
            assert.equal(await value(page, 'section'), '', 'section reset to All');
            const shown = await appliedRows(page);
            assert.equal(shown.length, rows.filter(row => row.session === staleSection.session && row.class === staleSection.klass).length);
        }
        assert.deepEqual(errors, []);
    } finally { await browser.close(); }
});

test('Reset restores every option, All, the full table and 25 per page', async () => {
    const { browser, page, errors, rows, offered, initial } = await open();
    try {
        await choose(page, 'session', initial.session.at(-1));
        await choose(page, 'class', (await offered('class'))[0]);
        await page.locator('#student-per-page').selectOption('10');
        await page.locator('#student-search').fill('a');
        await page.locator('.student-reset').click();
        await page.waitForFunction(n => jQuery('#studentTable').DataTable().rows({ search: 'applied' }).count() === n
            && jQuery('#studentTable').DataTable().page.len() === 25, rows.length);
        for (const key of ['class', 'section', 'session', 'department']) {
            assert.equal(await value(page, key), '');
            assert.deepEqual(await offered(key), initial[key], `${key} options restored`);
        }
        assert.deepEqual(errors, []);
    } finally { await browser.close(); }
});

test('the text search matches only the student name and ID, never class, section or other details', async () => {
    const { browser, page, errors, rows } = await open();
    try {
        const labels = await page.locator('[data-student-filter] option').evaluateAll(options => options.filter(o => o.value).map(o => o.textContent.trim()));
        const sample = rows[0].text.split(' ');
        for (const term of [...new Set([...labels, 'B', 'Ten', 'ID', 'Session', 'Department', sample[0], sample.at(-1)])]) {
            await page.locator('#student-search').fill(term);
            const words = term.toLowerCase().split(/\s+/).filter(Boolean);
            const matches = rows.filter(row => words.every(word => row.text.includes(word))).length;
            assert.equal(await page.evaluate(() => jQuery('#studentTable').DataTable().rows({ search: 'applied' }).count()), matches, `search "${term}"`);
        }
        assert.ok(rows.length > 0);
        assert.deepEqual(errors, []);
    } finally { await browser.close(); }
});
