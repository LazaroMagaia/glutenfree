

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const root = document.documentElement;
const $ = (id) => document.getElementById(id);

/* ---------- Tema (dark / light) ---------- */
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

/* ---------- Menu mobile ---------- */
$('menu')?.addEventListener('click', () => $('mobile').classList.toggle('hidden'));
$('mobile')?.addEventListener('click', (e) => {
    if (e.target.closest('a')) e.currentTarget.classList.add('hidden');
});

/* ---------- Carrinho (contador) + toast ---------- */
let cart = 0;
let timer;
const toast = $('toast');

const say = (text) => {
    if (!toast) return;
    toast.textContent = text;
    toast.classList.remove('translate-y-20', 'opacity-0');
    clearTimeout(timer);
    timer = setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 2000);
};

document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-add]');
    if (!btn) return;
    cart++;
    document.querySelectorAll('.cc').forEach((c) => (c.textContent = cart));
    say(`${btn.dataset.add} adicionado ao carrinho`);
});

/* ---------- Newsletter (validação no front; ligar a uma rota depois) ---------- */
$('sub')?.addEventListener('click', () => {
    const v = $('email').value.trim();
    $('msg').textContent = /^\S+@\S+\.\S+$/.test(v)
        ? 'Obrigado! Subscrição registada.'
        : 'Introduza um email válido.';
});

/* ---------- Reveal ao fazer scroll ---------- */
const io = new IntersectionObserver(
    (entries) => entries.forEach((x) => {
        if (x.isIntersecting) { x.target.classList.add('in'); io.unobserve(x.target); }
    }),
    { threshold: 0.1 }
);
document.querySelectorAll('.reveal').forEach((el) => io.observe(el));

Alpine.start();