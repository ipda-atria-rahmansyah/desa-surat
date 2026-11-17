import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fontsource/inter/400.css';
import '@fontsource/inter/500.css';
import '@fontsource/inter/600.css';
import '@fontsource/inter/700.css';
import 'bootstrap';
import Alpine from 'alpinejs';

const sidebarStorageKey = 'desa-surat.sidebar-collapsed';

document.addEventListener('DOMContentLoaded', () => {
    const appMain = document.querySelector('.app-main');
    const sidebarToggle = document.querySelector('[data-app-sidebar-toggle]');

    if (!appMain || !sidebarToggle) return;

    const setSidebarState = (collapsed) => {
        document.body.classList.toggle('app-sidebar-collapsed', collapsed);
        sidebarToggle.setAttribute('aria-label', collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar');
        sidebarToggle.querySelector('i')?.classList.toggle('bi-layout-sidebar-inset', collapsed);
        sidebarToggle.querySelector('i')?.classList.toggle('bi-list', !collapsed);
    };

    setSidebarState(localStorage.getItem(sidebarStorageKey) === 'true');
    sidebarToggle.addEventListener('click', () => {
        const collapsed = !document.body.classList.contains('app-sidebar-collapsed');
        setSidebarState(collapsed);
        localStorage.setItem(sidebarStorageKey, collapsed);
    });
});

window.letterForm = () => ({
    category: 'Surat Kematian',
    letterType: 'Surat Keterangan Domisili',
    nik: '',
    resident: null,
    searchMessage: '',
    residents: [{ nik: '3201010101010001', name: 'Budi Santoso' }],
    get canContinue() {
        return Boolean(this.category && this.letterType && this.resident);
    },
    searchNik() {
        this.resident = this.residents.find((item) => item.nik === this.nik) ?? null;
        this.searchMessage = this.resident ? '' : 'Data warga tidak ditemukan.';
    },
});
window.Alpine = Alpine;
Alpine.start();
