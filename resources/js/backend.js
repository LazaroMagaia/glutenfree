
import Alpine from 'alpinejs';

window.Alpine = Alpine;

const root = document.documentElement;
const $ = (id) => document.getElementById(id);

/* ---------- Tema ---------- */
const isDark = () =>
    root.dataset.theme === 'dark' ||
    (!root.dataset.theme && matchMedia('(prefers-color-scheme: dark)').matches);

const syncIcons = () => {
    $('sun')?.classList.toggle('hidden', !isDark());
    $('moon')?.classList.toggle('hidden', isDark());
};
syncIcons();

$('theme')?.addEventListener('click', () => {
    root.dataset.theme = isDark() ? 'light' : 'dark';
    try { localStorage.setItem('theme', root.dataset.theme); } catch (e) {}
    syncIcons();
});

/* ---------- Sidebar mobile ---------- */
const side = $('side');
const overlay = $('overlay');
const toggleSide = (open) => {
    side.classList.toggle('-translate-x-full', !open);
    overlay.classList.toggle('hidden', !open);
};
$('openSide')?.addEventListener('click', () => toggleSide(true));
overlay?.addEventListener('click', () => toggleSide(false));
Alpine.start();