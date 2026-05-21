const header = document.querySelector('.site-header');
const toggle = document.querySelector('.menu-toggle');

toggle?.addEventListener('click', () => {
  const isOpen = header?.classList.toggle('is-open') ?? false;
  toggle.setAttribute('aria-expanded', String(isOpen));
});

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.addEventListener('click', (event) => {
  const link = event.target instanceof Element ? event.target.closest('a[href^="#"]') : null;
  if (!link) return;

  const hash = link.getAttribute('href');
  if (!hash || hash === '#') return;

  const target = document.querySelector(hash);
  if (!target) return;

  event.preventDefault();
  target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
  history.pushState(null, '', hash);
});

const revealTargets = document.querySelectorAll(
  '.page-hero, .section, .card, .kitten-card, .parent-card, .litter-card, .review-card, .trust-card, .step-card, .delivery-card'
);

if ('IntersectionObserver' in window && !reduceMotion) {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    },
    { rootMargin: '0px 0px -8% 0px', threshold: 0.12 }
  );

  revealTargets.forEach((target, index) => {
    target.classList.add('reveal-item');
    target.setAttribute('style', `transition-delay: ${Math.min(index % 6, 4) * 45}ms`);
    observer.observe(target);
  });
} else {
  revealTargets.forEach((target) => target.classList.add('is-visible'));
}
