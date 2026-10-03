document.documentElement.classList.add('js');

// Header: transparent over the hero, solid once the page is scrolled.
const header = document.querySelector('header[data-overlay]');

if (header) {
    const update = () => header.toggleAttribute('data-scrolled', window.scrollY > 40);

    update();
    window.addEventListener('scroll', update, { passive: true });
}

// Menu page: follow the category being read and close the category panel after a choice.
const links = [...document.querySelectorAll('[data-spy] a')];

if (links.length) {
    const label = document.querySelector('[data-spy-label]');
    const count = document.querySelector('[data-spy-count]');
    const byId = new Map(links.map((link) => [link.getAttribute('href').slice(1), link]));

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) {
                    continue;
                }

                const current = byId.get(entry.target.id);

                links.forEach((link) => link.removeAttribute('aria-current'));
                current.setAttribute('aria-current', 'true');
                label.textContent = current.dataset.name;
                count.textContent = `${links.indexOf(current) + 1}/${links.length}`;
            }
        },
        { rootMargin: '-30% 0px -60% 0px' },
    );

    byId.forEach((_, id) => observer.observe(document.getElementById(id)));
    links.forEach((link) => link.addEventListener('click', () => link.closest('[popover]').hidePopover()));
}
