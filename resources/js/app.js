import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fontsource/inter/400.css';
import '@fontsource/inter/500.css';
import '@fontsource/inter/600.css';
import '@fontsource/inter/700.css';
import 'bootstrap';
import Alpine from 'alpinejs';

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
