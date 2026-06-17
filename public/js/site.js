const header = document.querySelector('.site-header');
const toggle = document.querySelector('.menu-toggle');

toggle?.addEventListener('click', () => {
  const isOpen = header?.classList.toggle('is-open') ?? false;
  toggle.setAttribute('aria-expanded', String(isOpen));
});

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

document.querySelectorAll('[data-hero-slider]').forEach((slider) => {
  const slides = Array.from(slider.querySelectorAll('[data-hero-slide]'));
  const dots = Array.from(slider.querySelectorAll('[data-hero-dot]'));
  const previousButton = slider.querySelector('[data-hero-prev]');
  const nextButton = slider.querySelector('[data-hero-next]');

  if (slides.length <= 1) return;

  let activeIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));
  let timer = null;

  if (activeIndex < 0) activeIndex = 0;

  const showSlide = (nextIndex) => {
    activeIndex = (nextIndex + slides.length) % slides.length;

    slides.forEach((slide, index) => {
      const isActive = index === activeIndex;
      slide.classList.toggle('is-active', isActive);
      slide.setAttribute('aria-hidden', String(!isActive));
    });

    dots.forEach((dot, index) => {
      const isActive = index === activeIndex;
      dot.classList.toggle('is-active', isActive);
      dot.setAttribute('aria-current', String(isActive));
    });
  };

  const stopAutoplay = () => {
    if (!timer) return;
    window.clearInterval(timer);
    timer = null;
  };

  const startAutoplay = () => {
    if (reduceMotion || timer) return;
    timer = window.setInterval(() => showSlide(activeIndex + 1), 6500);
  };

  previousButton?.addEventListener('click', () => {
    stopAutoplay();
    showSlide(activeIndex - 1);
    startAutoplay();
  });

  nextButton?.addEventListener('click', () => {
    stopAutoplay();
    showSlide(activeIndex + 1);
    startAutoplay();
  });

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      stopAutoplay();
      showSlide(index);
      startAutoplay();
    });
  });

  slider.addEventListener('pointerenter', stopAutoplay);
  slider.addEventListener('pointerleave', startAutoplay);
  slider.addEventListener('focusin', stopAutoplay);
  slider.addEventListener('focusout', startAutoplay);

  showSlide(activeIndex);
  startAutoplay();
});

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
  '.page-hero, .section, .hero-showcase, .card, .kitten-card, .parent-card, .litter-card, .review-card, .trust-card, .step-card, .delivery-card'
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
