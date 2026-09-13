const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const source = fs.readFileSync('public/assets/js/admin-location-refresh.js', 'utf8');
async function scenario(responses) {
    let handler, updated, calls = [];
    const label = {textContent: 'Perbarui'};
    const status = {hidden: true, textContent: ''};
    const button = {disabled: false, dataset: {csrfToken: 'first', endpoint: '/refresh', dashboard: '/dashboard'},
        querySelector: () => label, setAttribute() {}, removeAttribute() {}, addEventListener(_, cb) {handler = cb;}};
    const document = {getElementById: id => id === 'refresh-locations' ? button : status,
        querySelector: () => ({value: 'week'})};
    vm.runInNewContext(source, {document, URLSearchParams, AbortController, Error, TypeError, SyntaxError,
        setTimeout: (fn, ms) => ms === 20000 ? 0 : setTimeout(fn, 0), clearTimeout,
        updateLocations: rows => updated = rows,
        fetch: async (url, options) => {
            calls.push({url, token: options.body?.get('csrf_token'), cursor: options.body?.get('cursor')});
            const next = responses.shift();
            if (next instanceof Error) throw next;
            return {ok: next.ok !== false, redirected: false, json: async () => next};
        }});
    const running = handler();
    assert.equal(button.disabled, true, 'Immediate disabled state');
    await handler(); // Duplicate invocation while the first request is in flight.
    await running;
    assert.equal(button.disabled, false, 'Button restored');
    assert.equal(label.textContent, 'Perbarui');
    return {calls, updated, status, button};
}
(async () => {
    const success = await scenario([
        {success: true, csrf_token: 'second', cursor: '1.1.1.1', checked: 1, total: 2, unresolved: 0, retry_after: 2, done: false},
        {success: true, csrf_token: 'third', cursor: '8.8.8.8', checked: 1, total: 2, unresolved: 0, retry_after: 0, done: true},
        {locationStats: [{location: 'Makassar', views: 2}]}
    ]);
    assert.equal(success.calls.length, 3, 'Duplicate click did not send another request');
    assert.equal(success.calls[1].token, 'second');
    assert.equal(success.calls[1].cursor, '1.1.1.1');
    assert.equal(success.calls[2].url, '/dashboard?period=week');
    assert.equal(success.updated[0].location, 'Makassar');
    assert.match(success.status.textContent, /berhasil/);
    const failed = await scenario([{ok: false, success: false, csrf_token: 'retry', message: 'Coba lagi'}]);
    assert.equal(failed.calls.length, 1);
    assert.equal(failed.button.dataset.csrfToken, 'retry');
    assert.equal(failed.status.textContent, 'Coba lagi');
    const interrupted = await scenario([new TypeError('Network error')]);
    assert.match(interrupted.status.textContent, /terhenti/);
    console.log('Passed client refresh, duplicate-click, token rotation and failure recovery checks.');
})().catch(error => { console.error(error); process.exitCode = 1; });
