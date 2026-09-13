(() => {
    const desktop = window.matchMedia('(min-width: 768px)');
    let testimonialTrigger = null;

    function updateReveal(section, reset = false) {
        const items = [...section.querySelectorAll('[data-reveal-item]')];
        const button = section.querySelector('[data-reveal-more]');
        const configured = desktop.matches ? (section.dataset.desktopInitial || section.dataset.initial) : section.dataset.initial;
        const initial = configured === 'all' ? items.length : Number(configured);
        const visible = reset ? initial : Math.max(initial, Number(section.dataset.visible || initial));
        section.dataset.visible = String(visible);
        items.forEach((item, index) => {
            item.classList.remove('reveal-desktop-only');
            item.hidden = index >= visible;
        });
        if (button) {
            button.classList.remove('reveal-mobile-only');
            button.hidden = visible >= items.length;
        }
    }

    function initialize() {
        document.querySelectorAll('[data-reveal]').forEach(section => updateReveal(section, true));
    }

    function closeTestimonial(restoreFocus = true) {
        document.getElementById('modal-testimoni-detail')?.classList.add('hidden');
        document.body.classList.remove('testimoni-modal-open');
        if (restoreFocus && testimonialTrigger?.isConnected) testimonialTrigger.focus();
        testimonialTrigger = null;
    }

    // Delegation always resolves the current DOM, including content replaced by AJAX.
    document.addEventListener('click', event => {
        const more = event.target.closest('[data-reveal-more]');
        if (more) {
            const section = more.closest('[data-reveal]');
            if (!section) return;
            section.dataset.visible = String(Number(section.dataset.visible) + Number(section.dataset.step || 3));
            updateReveal(section);
            return;
        }
        const trigger = event.target.closest('.testimoni-read-more');
        const modal = document.getElementById('modal-testimoni-detail');
        if (trigger && modal) {
            const data = JSON.parse(trigger.dataset.testimoni || '{}');
            modal.querySelector('#modal-testimoni-text').textContent = data.testimonial_text || '';
            modal.querySelector('#modal-testimoni-parent').textContent = data.parent_name || '';
            modal.querySelector('#modal-testimoni-child').textContent = data.child_name ? 'Orang Tua dari ' + data.child_name : '';
            const image = modal.querySelector('#modal-testimoni-image');
            const fallback = new URL('images/image_testi.jpg', window.location.origin + '/').href;
            image.onerror = () => { image.onerror = null; image.src = fallback; };
            image.src = trigger.dataset.image || fallback;
            image.alt = data.parent_name || 'Testimoni';
            testimonialTrigger = trigger;
            modal.classList.remove('hidden');
            document.body.classList.add('testimoni-modal-open');
            modal.querySelector('#close-modal-testimoni-detail').focus();
        } else if (event.target.closest('#close-modal-testimoni-detail') || (modal && event.target === modal)) {
            closeTestimonial();
        }
        // Article lists also survive AJAX navigation without stale page scripts.
        const articleMore = event.target.closest('#loadMoreBtn, #desktopLoadMoreBtn');
        if (articleMore) {
            if (articleMore.id === 'loadMoreBtn') {
                document.querySelectorAll('.mobile-article-item').forEach(item => item.classList.add('show'));
            } else {
                document.getElementById('desktop-hidden-articles')?.classList.remove('hidden');
            }
            articleMore.hidden = true;
        }
    });
    document.addEventListener('keydown', event => {
        const modal = document.getElementById('modal-testimoni-detail');
        if (!modal || modal.classList.contains('hidden')) return;
        if (event.key === 'Escape') closeTestimonial();
        if (event.key === 'Tab') {
            event.preventDefault();
            modal.querySelector('#close-modal-testimoni-detail').focus();
        }
    });
    document.addEventListener('page:before-replace', () => closeTestimonial(false));
    document.addEventListener('page:loaded', initialize);
    desktop.addEventListener('change', initialize);
    initialize();
})();
