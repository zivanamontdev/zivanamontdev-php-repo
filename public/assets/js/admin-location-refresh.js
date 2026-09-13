(() => {
    const button = document.getElementById('refresh-locations');
    const status = document.getElementById('location-refresh-status');
    if (!button || !status) return;
    const label = button.querySelector('[data-refresh-label]');
    let running = false;

    async function request(url, options = {}) {
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 20000);
        try {
            const response = await fetch(url, {...options, signal: controller.signal,
                headers: {'X-Requested-With': 'XMLHttpRequest', ...options.headers}});
            if (response.redirected) throw new Error('Sesi berakhir. Muat ulang halaman untuk masuk kembali.');
            const data = await response.json();
            if (data.csrf_token) button.dataset.csrfToken = data.csrf_token;
            if (!response.ok || data.success === false) throw new Error(data.message || 'Lokasi belum berhasil diperbarui.');
            return data;
        } finally { clearTimeout(timeout); }
    }

    button.addEventListener('click', async () => {
        if (running) return;
        running = true;
        button.disabled = true;
        button.setAttribute('aria-busy', 'true');
        label.textContent = 'Memperbarui…';
        status.hidden = false;
        status.textContent = 'Memulai pembaruan lokasi…';
        let cursor = '';
        let checked = 0;
        let unresolved = 0;
        try {
            while (true) {
                const data = await request(button.dataset.endpoint, {
                    method: 'POST',
                    body: new URLSearchParams({csrf_token: button.dataset.csrfToken, cursor})
                });
                cursor = data.cursor;
                checked += data.checked;
                unresolved += data.unresolved;
                status.textContent = `Memperbarui lokasi: ${checked} dari ${data.total} sumber kunjungan diperiksa. Tetap buka halaman ini.`;
                if (data.done) break;
                if (data.retry_after > 2) status.textContent += ` Melanjutkan dalam ${data.retry_after} detik…`;
                await new Promise(resolve => setTimeout(resolve, Math.max(0.1, data.retry_after) * 1000));
            }
            const period = document.querySelector('#period-filter [data-dropdown-input]')?.value || 'month';
            const dashboard = await request(button.dataset.dashboard + '?period=' + encodeURIComponent(period));
            updateLocations(dashboard.locationStats || []);
            status.textContent = unresolved > 0
                ? `Pembaruan selesai. ${unresolved} sumber kunjungan belum dapat dikenali; klik Perbarui untuk mencoba lagi.`
                : 'Lokasi berhasil diperbarui.';
        } catch (error) {
            status.textContent = error.name === 'AbortError'
                ? 'Koneksi terlalu lama. Klik Perbarui untuk mencoba lagi; hasil sebelumnya tetap tersimpan.'
                : (error instanceof SyntaxError || error instanceof TypeError
                    ? 'Pembaruan terhenti. Muat ulang halaman lalu coba lagi; hasil sebelumnya tetap tersimpan.' : error.message);
        } finally {
            running = false;
            button.disabled = false;
            button.removeAttribute('aria-busy');
            label.textContent = 'Perbarui';
        }
    });
})();
