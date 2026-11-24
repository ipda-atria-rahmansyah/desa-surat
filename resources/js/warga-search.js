const initWargaSearch = () => {
    const form = document.querySelector('[data-warga-search-form]');
    const results = document.querySelector('[data-warga-results]');
    if (!form || !results) return;
    const input = form.querySelector('input[name="q"]');
    let controller;
    let timer;

    const load = async (url) => {
        controller?.abort();
        controller = new AbortController();
        results.classList.add('opacity-50');
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'text/html' }, signal: controller.signal });
            if (response.status === 401 || response.status === 419) { window.location.href = '/login'; return; }
            if (!response.ok) throw new Error();
            results.innerHTML = await response.text();
            history.replaceState({}, '', url);
        } catch (error) {
            if (error.name !== 'AbortError') results.innerHTML = '<div class="p-4 text-center">Gagal memuat data. Coba lagi.</div>';
        } finally { results.classList.remove('opacity-50'); }
    };
    form.addEventListener('submit', (event) => { event.preventDefault(); load(`${form.action}?${new URLSearchParams(new FormData(form))}`); });
    input.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => form.requestSubmit(), 300); });
    results.addEventListener('click', (event) => { const link = event.target.closest('.pagination a'); if (!link) return; event.preventDefault(); load(link.href); });
};

document.addEventListener('DOMContentLoaded', initWargaSearch);

export default initWargaSearch;