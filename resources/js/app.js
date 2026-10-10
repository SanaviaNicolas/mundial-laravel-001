const root = document.documentElement;
const fine = matchMedia('(hover: hover) and (pointer: fine)').matches;
const calm = matchMedia('(prefers-reduced-motion: reduce)').matches;

root.classList.add('js');

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

// Pinned storytelling: progress through the tall track decides which fact is shown.
const track = document.querySelector('.pin-track');

if (track && !calm) {
    const facts = [...track.querySelectorAll('.pin-fact')];
    const update = () => {
        const box = track.getBoundingClientRect();
        const progress = Math.min(1, Math.max(0, -box.top / (box.height - innerHeight)));
        const active = Math.min(facts.length - 1, Math.floor(progress * facts.length));

        track.style.setProperty('--p', progress.toFixed(3));
        facts.forEach((fact, i) => {
            fact.toggleAttribute('data-active', i === active);
            fact.toggleAttribute('data-past', i < active);
        });
    };

    update();
    addEventListener('scroll', update, { passive: true });
}

// Pointer effects: desktop only, never with reduced motion.
if (fine && !calm) {
    // Floating photo that follows the pointer over the menu index.
    const list = document.querySelector('[data-preview]');

    if (list) {
        const preview = document.body.appendChild(Object.assign(document.createElement('div'), { className: 'preview', ariaHidden: 'true' }));
        const photo = preview.appendChild(document.createElement('img'));
        const mouse = { x: innerWidth / 2, y: innerHeight / 2 };
        const pos = { ...mouse };
        const lerp = (a, b, t) => a + (b - a) * t;

        addEventListener('pointermove', (event) => {
            mouse.x = event.clientX;
            mouse.y = event.clientY;
        });
        list.addEventListener('pointerover', (event) => {
            const row = event.target.closest('[data-img]');

            preview.toggleAttribute('data-on', Boolean(row));

            if (row) {
                photo.src = row.dataset.img;
            }
        });
        list.addEventListener('pointerleave', () => preview.removeAttribute('data-on'));

        const frame = () => {
            pos.x = lerp(pos.x, mouse.x + 28, 0.14);
            pos.y = lerp(pos.y, mouse.y - 110, 0.14);
            preview.style.setProperty('--px', `${pos.x}px`);
            preview.style.setProperty('--py', `${pos.y}px`);
            preview.style.setProperty('--pr', `${(mouse.x - pos.x) * 0.05}deg`);
            requestAnimationFrame(frame);
        };

        frame();
    }

    // Hero photo drifts slightly against the pointer.
    const hero = document.querySelector('[data-mouse]');

    hero?.addEventListener('pointermove', (event) => {
        hero.style.setProperty('--mx', ((0.5 - event.clientX / innerWidth) * 28).toFixed(1));
        hero.style.setProperty('--my', ((0.5 - event.clientY / innerHeight) * 18).toFixed(1));
    });
}
