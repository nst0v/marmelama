const header = document.querySelector('.site-header');
const toggle = document.querySelector('.menu-toggle');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const metrikaId = Number.parseInt(document.body.dataset.metrikaId ?? '', 10);
const analyticsConsentKey = 'marmelama.analyticsConsent';
const analyticsConsentVersion = document.body.dataset.analyticsConsentVersion ?? '';
const configuredConsentDays = Number.parseInt(document.body.dataset.analyticsConsentDays ?? '', 10);
const analyticsConsentDays = Number.isInteger(configuredConsentDays) && configuredConsentDays > 0
  ? configuredConsentDays
  : 365;
const analyticsConsentMaxAge = analyticsConsentDays * 24 * 60 * 60 * 1000;
const analyticsConsent = document.querySelector('[data-analytics-consent]');
let metrikaInitialized = false;
let pendingPageGoalSent = false;

const removeStoredAnalyticsConsent = () => {
  try {
    window.localStorage.removeItem(analyticsConsentKey);
  } catch {
    // Storage can be disabled by the browser.
  }
};

const readAnalyticsConsent = () => {
  try {
    const savedValue = window.localStorage.getItem(analyticsConsentKey);

    if (!savedValue) return null;

    const savedConsent = JSON.parse(savedValue);
    const decidedAt = Date.parse(savedConsent.decidedAt);
    const isCurrent = ['accepted', 'declined'].includes(savedConsent.status)
      && savedConsent.version === analyticsConsentVersion
      && Number.isFinite(decidedAt)
      && Date.now() - decidedAt >= 0
      && Date.now() - decidedAt <= analyticsConsentMaxAge;

    if (isCurrent) return savedConsent.status;
  } catch {
    // Legacy or malformed choices are replaced with a fresh decision.
  }

  removeStoredAnalyticsConsent();

  return null;
};

const writeAnalyticsConsent = (status) => {
  try {
    window.localStorage.setItem(analyticsConsentKey, JSON.stringify({
      status,
      version: analyticsConsentVersion,
      decidedAt: new Date().toISOString(),
    }));
  } catch {
    // The visitor can still use this choice for the current page.
  }
};

const isMetrikaStorageKey = (key) => /^(?:_ym|yandex|metrika|lastHit|lastHitTime|hitParam|counterNum)/i.test(key);

const clearMetrikaWebStorage = (storage) => {
  try {
    Array.from({ length: storage.length }, (_, index) => storage.key(index))
      .filter((key) => key && key !== analyticsConsentKey && isMetrikaStorageKey(key))
      .forEach((key) => storage.removeItem(key));
  } catch {
    // Storage can be disabled by the browser.
  }
};

const clearMetrikaStorage = () => {
  clearMetrikaWebStorage(window.localStorage);
  clearMetrikaWebStorage(window.sessionStorage);

  const hostname = window.location.hostname.replace(/^www\./, '');
  const domains = hostname && hostname !== 'localhost'
    ? ['', `; domain=${hostname}`, `; domain=.${hostname}`]
    : [''];

  document.cookie.split(';').forEach((cookie) => {
    const name = cookie.split('=', 1)[0]?.trim();

    if (!name || (!name.startsWith('_ym') && name !== 'yabs-sid')) return;

    domains.forEach((domain) => {
      document.cookie = `${name}=; Max-Age=0; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/${domain}; SameSite=Lax`;
    });
  });
};

const sendMetrikaGoal = (goal) => {
  if (!metrikaInitialized || typeof window.ym !== 'function' || !goal) return false;

  window.ym(metrikaId, 'reachGoal', goal);

  return true;
};

const initializeMetrika = () => {
  if (!Number.isInteger(metrikaId) || metrikaId <= 0 || metrikaInitialized) return;

  window.ym = window.ym || function () {
    (window.ym.a = window.ym.a || []).push(arguments);
  };
  window.ym.l = Date.now();

  const script = document.createElement('script');
  script.async = true;
  script.src = `https://mc.yandex.ru/metrika/tag.js?id=${metrikaId}`;
  document.head.appendChild(script);

  window.ym(metrikaId, 'init', {
    ssr: true,
    clickmap: true,
    ecommerce: 'dataLayer',
    referrer: document.referrer,
    url: window.location.href,
    accurateTrackBounce: true,
    trackLinks: true,
  });

  metrikaInitialized = true;

  const pendingPageGoal = document.body.dataset.metrikaGoal;

  if (pendingPageGoal && !pendingPageGoalSent) {
    pendingPageGoalSent = sendMetrikaGoal(pendingPageGoal);
  }
};

const showAnalyticsConsent = () => {
  if (!analyticsConsent) return;

  analyticsConsent.hidden = false;
  analyticsConsent.querySelector('button')?.focus({ preventScroll: true });
};

const hideAnalyticsConsent = () => {
  if (analyticsConsent) analyticsConsent.hidden = true;
};

if (Number.isInteger(metrikaId) && metrikaId > 0) {
  const savedConsent = readAnalyticsConsent();

  if (savedConsent === 'accepted') {
    initializeMetrika();
  } else if (savedConsent === 'declined') {
    clearMetrikaStorage();
  } else {
    showAnalyticsConsent();
  }

  analyticsConsent?.querySelector('[data-analytics-accept]')?.addEventListener('click', () => {
    writeAnalyticsConsent('accepted');
    hideAnalyticsConsent();
    initializeMetrika();
  });

  analyticsConsent?.querySelector('[data-analytics-decline]')?.addEventListener('click', () => {
    writeAnalyticsConsent('declined');
    hideAnalyticsConsent();
    clearMetrikaStorage();

    if (metrikaInitialized) window.location.reload();
  });

  document.querySelector('[data-analytics-consent-settings]')?.addEventListener('click', showAnalyticsConsent);

  document.addEventListener('click', (event) => {
    const target = event.target instanceof Element
      ? event.target.closest('[data-analytics-goal]')
      : null;

    if (!target) return;

    sendMetrikaGoal(target.dataset.analyticsGoal);
  });
}

toggle?.addEventListener('click', () => {
  header?.getAnimations?.().forEach((animation) => animation.cancel());
  const previousHeight = header?.getBoundingClientRect().height ?? 0;
  const isOpen = header?.classList.toggle('is-open') ?? false;
  toggle.setAttribute('aria-expanded', String(isOpen));
  toggle.setAttribute('aria-label', isOpen ? 'Закрыть меню' : 'Открыть меню');

  if (header && !reduceMotion && previousHeight && typeof header.animate === 'function') {
    const nextHeight = header.getBoundingClientRect().height;

    header.animate(
      [
        { height: `${previousHeight}px`, overflow: 'hidden' },
        { height: `${nextHeight}px`, overflow: 'hidden' },
      ],
      { duration: 220, easing: 'cubic-bezier(.2, .8, .2, 1)' }
    );
  }
});

document.querySelectorAll('[data-hero-slider]').forEach((slider) => {
  const slides = Array.from(slider.querySelectorAll('[data-hero-slide]'));
  const dots = Array.from(slider.querySelectorAll('[data-hero-dot]'));
  const autoplayDelay = 5000;

  if (slides.length <= 1) return;

  let activeIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));
  let autoplayTimer = null;

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
    if (!autoplayTimer) return;

    window.clearInterval(autoplayTimer);
    autoplayTimer = null;
  };

  const startAutoplay = () => {
    if (reduceMotion || autoplayTimer) return;

    autoplayTimer = window.setInterval(() => showSlide(activeIndex + 1), autoplayDelay);
  };

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      stopAutoplay();
      showSlide(index);
      startAutoplay();
    });
  });

  let touchPointerId = null;
  let touchStartX = 0;
  let touchStartY = 0;

  slider.addEventListener('pointerdown', (event) => {
    if (event.pointerType !== 'touch' || !event.isPrimary) return;

    touchPointerId = event.pointerId;
    touchStartX = event.clientX;
    touchStartY = event.clientY;
    slider.setPointerCapture?.(event.pointerId);
    stopAutoplay();
  });

  const finishTouch = (event, cancelled = false) => {
    if (event.pointerId !== touchPointerId) return;

    const distanceX = event.clientX - touchStartX;
    const distanceY = event.clientY - touchStartY;
    const swipeThreshold = Math.max(36, slider.clientWidth * 0.08);

    if (slider.hasPointerCapture?.(event.pointerId)) {
      slider.releasePointerCapture(event.pointerId);
    }

    touchPointerId = null;

    if (!cancelled && Math.abs(distanceX) >= swipeThreshold && Math.abs(distanceX) > Math.abs(distanceY) * 1.2) {
      showSlide(activeIndex + (distanceX < 0 ? 1 : -1));
    }

    startAutoplay();
  };

  slider.addEventListener('pointerup', (event) => finishTouch(event));
  slider.addEventListener('pointercancel', (event) => finishTouch(event, true));

  showSlide(activeIndex);
  startAutoplay();
});

document.querySelectorAll('[data-review-slider]').forEach((slider) => {
  const slides = Array.from(slider.querySelectorAll('.review-card'));
  const dots = Array.from(slider.parentElement?.querySelectorAll('[data-review-dot]') ?? []);

  if (slides.length <= 1 || dots.length !== slides.length) return;

  const requestedStartIndex = Number.parseInt(slider.dataset.reviewStart ?? '0', 10);
  const initialIndex = Number.isNaN(requestedStartIndex)
    ? 0
    : Math.min(Math.max(requestedStartIndex, 0), slides.length - 1);
  const mobileReviews = window.matchMedia('(max-width: 700px)');
  let scrollFrame = null;

  const setActiveDot = (activeIndex) => {
    dots.forEach((dot, index) => {
      if (index === activeIndex) {
        dot.setAttribute('aria-current', 'true');
      } else {
        dot.removeAttribute('aria-current');
      }
    });
  };

  const slideOffset = (slide) => slide.offsetLeft - slider.offsetLeft;

  const syncDots = () => {
    const activeIndex = slides.reduce((closestIndex, slide, index) => {
      const currentDistance = Math.abs(slideOffset(slide) - slider.scrollLeft);
      const closestDistance = Math.abs(slideOffset(slides[closestIndex]) - slider.scrollLeft);

      return currentDistance < closestDistance ? index : closestIndex;
    }, 0);

    setActiveDot(activeIndex);
    scrollFrame = null;
  };

  slider.addEventListener('scroll', () => {
    if (scrollFrame !== null) return;

    scrollFrame = window.requestAnimationFrame(syncDots);
  }, { passive: true });

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      slider.scrollTo({
        left: slideOffset(slides[index]),
        behavior: reduceMotion ? 'auto' : 'smooth',
      });
      setActiveDot(index);
    });
  });

  const showInitialSlide = () => {
    setActiveDot(initialIndex);

    if (!mobileReviews.matches) return;

    window.requestAnimationFrame(() => {
      slider.scrollTo({ left: slideOffset(slides[initialIndex]), behavior: 'auto' });
    });
  };

  mobileReviews.addEventListener?.('change', (event) => {
    if (event.matches) showInitialSlide();
  });

  showInitialSlide();
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

const revealTargets = Array.from(document.querySelectorAll(
  '.page-hero, .section, .hero-showcase, .card, .kitten-card, .parent-card, .litter-card, .review-card, .trust-card, .delivery-card, [data-soft-reveal]'
)).filter((target) => {
  const softRevealParent = target.closest('[data-soft-reveal]');

  return !softRevealParent || softRevealParent === target;
});

if (!reduceMotion) {
  document.querySelectorAll('[data-soft-reveal] img[loading="lazy"]').forEach((image) => {
    const showImage = () => image.classList.add('is-loaded');

    image.classList.add('soft-load-image');

    if (image.complete) {
      showImage();
      return;
    }

    image.addEventListener('load', showImage, { once: true });
    image.addEventListener('error', showImage, { once: true });
  });
}

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
    target.style.transitionDelay = target.hasAttribute('data-soft-reveal')
      ? '0ms'
      : `${Math.min(index % 6, 4) * 45}ms`;
    observer.observe(target);
  });
} else {
  revealTargets.forEach((target) => target.classList.add('is-visible'));
}
