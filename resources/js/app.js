document.documentElement.classList.add('js');

// Header: transparent over the hero, solid once the page is scrolled.
const header = document.querySelector('header[data-overlay]');

if (header) {
    const update = () => header.toggleAttribute('data-scrolled', window.scrollY > 40);

    update();
    window.addEventListener('scroll', update, { passive: true });
}

// Menu page: highlight the category chip of the section being read.
const chips = document.querySelectorAll('[data-spy] a');

if (chips.length) {
    const byId = new Map([...chips].map((chip) => [chip.getAttribute('href').slice(1), chip]));
    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    chips.forEach((chip) => chip.removeAttribute('aria-current'));
                    byId.get(entry.target.id)?.setAttribute('aria-current', 'true');
                }
            }
        },
        { rootMargin: '-30% 0px -60% 0px' },
    );

    byId.forEach((_, id) => observer.observe(document.getElementById(id)));
}
