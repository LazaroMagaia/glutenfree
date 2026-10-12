
const openedModals = [];
const previousFocus = new WeakMap();
const DURATION = 200;

function getModal(target) {
    return typeof target === 'string'
        ? document.getElementById(target)
        : target;
}

function isOpen(modal) {
    return modal?.dataset.open === 'true';
}

function openModal(target, trigger = null) {
    const modal = getModal(target);
    console.log("Clicked to open modal:", modal, "Triggered by:", trigger);
    if (!modal || isOpen(modal)) return;

    const panel = modal.querySelector('[data-modal-panel]');
    const backdrop = modal.querySelector('[data-modal-backdrop]');

    if (!panel || !backdrop) return;

    previousFocus.set(modal, document.activeElement);

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.dataset.open = 'true';
    modal.setAttribute('aria-hidden', 'false');

    openedModals.push(modal);
    document.body.classList.add('overflow-hidden');

    modal.dispatchEvent(new CustomEvent('modal:open', {
        bubbles: true,
        detail: { trigger }
    }));

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            if (!isOpen(modal)) return;

            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');

            panel.classList.remove('scale-95', 'opacity-0');
            panel.classList.add('scale-100', 'opacity-100');

            const focusTarget =
                modal.querySelector('[data-modal-focus]') || panel;

            if (!focusTarget.hasAttribute('tabindex')) {
                focusTarget.setAttribute('tabindex', '-1');
            }

            focusTarget.focus();

            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    });
}

function closeModal(target) {
    const modal = getModal(target);

    if (!modal || !isOpen(modal)) return;

    const panel = modal.querySelector('[data-modal-panel]');
    const backdrop = modal.querySelector('[data-modal-backdrop]');

    modal.dataset.open = 'false';
    modal.setAttribute('aria-hidden', 'true');

    backdrop?.classList.remove('opacity-100');
    backdrop?.classList.add('opacity-0');

    panel?.classList.remove('scale-100', 'opacity-100');
    panel?.classList.add('scale-95', 'opacity-0');

    const index = openedModals.lastIndexOf(modal);

    if (index !== -1) {
        openedModals.splice(index, 1);
    }

    if (openedModals.length === 0) {
        document.body.classList.remove('overflow-hidden');
    }

    window.setTimeout(() => {
        if (isOpen(modal)) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        const focus = previousFocus.get(modal);

        if (focus && document.contains(focus)) {
            focus.focus();
        }
    }, DURATION);

    modal.dispatchEvent(new CustomEvent('modal:close', {
        bubbles: true
    }));
}

document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-modal-open]');

    if (opener) {
        event.preventDefault();
        openModal(opener.dataset.modalOpen, opener);
        return;
    }

    const closer = event.target.closest('[data-modal-close]');

    if (closer) {
        const modal = closer.closest('[data-modal]');
        closeModal(closer.dataset.modalClose || modal);
        return;
    }

    const backdrop = event.target.closest('[data-modal-backdrop]');

    if (backdrop) {
        const modal = backdrop.closest('[data-modal]');

        if (modal && !modal.hasAttribute('data-modal-persistent')) {
            closeModal(modal);
        }
    }
});

document.addEventListener('keydown', (event) => {
    const modal = openedModals[openedModals.length - 1];

    if (!modal) return;

    if (
        event.key === 'Escape' &&
        !modal.hasAttribute('data-modal-persistent')
    ) {
        closeModal(modal);
    }
});

window.Modal = {
    open: openModal,
    close: closeModal,
};
