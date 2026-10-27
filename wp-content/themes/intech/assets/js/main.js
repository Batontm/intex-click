(function () {
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // --- Шапка при прокрутке ---
  var header = document.getElementById('siteHeader');
  function onScroll() { if (header) header.classList.toggle('is-scrolled', window.scrollY > 10); }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // --- Мобильное меню ---
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('siteNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a')) { nav.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); }
    });
  }

  // --- Выпадающее меню «Услуги» ---
  var drops = document.querySelectorAll('.nav__item--drop');
  Array.prototype.forEach.call(drops, function (item) {
    var btn = item.querySelector('.nav__drop-btn');
    if (!btn) return;
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = item.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });
  function closeDrops() {
    Array.prototype.forEach.call(drops, function (item) {
      item.classList.remove('is-open');
      var b = item.querySelector('.nav__drop-btn'); if (b) b.setAttribute('aria-expanded', 'false');
    });
  }
  document.addEventListener('click', function (e) { if (!e.target.closest('.nav__item--drop')) closeDrops(); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDrops(); });

  // --- Телефон: полный номер только при наведении ---
  var dec = function (v) { try { return atob(v).split('').reverse().join(''); } catch (e) { return ''; } };
  var touch = window.matchMedia && window.matchMedia('(hover: none)').matches;
  Array.prototype.forEach.call(document.querySelectorAll('.phone-mask'), function (el) {
    var txt = el.querySelector('.phone-mask__txt');
    if (!txt) return;
    var masked = txt.textContent, href = el.getAttribute('href');
    function show() { txt.textContent = dec(el.getAttribute('data-p')); el.setAttribute('href', 'tel:' + dec(el.getAttribute('data-t'))); el.classList.add('is-shown'); el.setAttribute('aria-label', 'Позвонить'); }
    function hide() { if (touch) return; txt.textContent = masked; el.setAttribute('href', href); el.classList.remove('is-shown'); el.setAttribute('aria-label', 'Показать номер телефона'); }
    el.addEventListener('mouseenter', show);
    el.addEventListener('focus', show);
    el.addEventListener('mouseleave', hide);
    el.addEventListener('blur', hide);
    el.addEventListener('click', function (e) { if (!el.classList.contains('is-shown')) { e.preventDefault(); show(); } });
  });

  // --- Появление блоков ---
  var items = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && !reduce) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var el = entry.target;
          var sib = Array.prototype.indexOf.call(el.parentNode.children, el);
          el.style.transitionDelay = Math.min(sib, 5) * 70 + 'ms';
          el.classList.add('is-visible');
          io.unobserve(el);
        }
      });
    }, { threshold: 0.12 });
    items.forEach(function (el) { io.observe(el); });
  } else {
    items.forEach(function (el) { el.classList.add('is-visible'); });
  }

  // --- Подсветка карточек за курсором ---
  document.querySelectorAll('.spot').forEach(function (card) {
    card.addEventListener('pointermove', function (e) {
      var r = card.getBoundingClientRect();
      card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
      card.style.setProperty('--my', (e.clientY - r.top) + 'px');
    });
  });

  // --- Вопросы: раскрываются при наведении (на устройствах с мышью) ---
  var finePointer = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  if (finePointer) {
    document.querySelectorAll('.faq__item').forEach(function (item) {
      var timer;
      item.addEventListener('mouseenter', function () {
        clearTimeout(timer);
        timer = setTimeout(function () { if (!item.open) { item.open = true; item.dataset.hoverOpen = '1'; } }, 120);
      });
      item.addEventListener('mouseleave', function () {
        clearTimeout(timer);
        if (item.dataset.hoverOpen === '1') { timer = setTimeout(function () { item.open = false; delete item.dataset.hoverOpen; }, 200); }
      });
      // Клик закрепляет вопрос открытым (или закрывает его)
      item.querySelector('summary').addEventListener('click', function (e) {
        if (item.dataset.hoverOpen === '1') { e.preventDefault(); delete item.dataset.hoverOpen; }
      });
    });
  }

  // --- Наклон 3D-сцены за курсором ---
  var scene = document.getElementById('scene');
  if (scene && !reduce) {
    var hero = scene.closest('.hero') || document.body;
    hero.addEventListener('pointermove', function (e) {
      var x = e.clientX / window.innerWidth - 0.5;
      var y = e.clientY / window.innerHeight - 0.5;
      scene.style.setProperty('--ry', (x * 10).toFixed(2) + 'deg');
      scene.style.setProperty('--rx', (-y * 8).toFixed(2) + 'deg');
    });
    hero.addEventListener('pointerleave', function () {
      scene.style.setProperty('--ry', '0deg');
      scene.style.setProperty('--rx', '0deg');
    });
  }

  // --- Сеть узлов на фоне первого экрана ---
  var canvas = document.getElementById('heroNet');
  if (canvas && canvas.getContext) {
    var ctx = canvas.getContext('2d');
    var accent = getComputedStyle(document.body).getPropertyValue('--accent-rgb').trim() || '34, 211, 238';
    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    var W = 0, H = 0, pts = [], mouse = { x: -9999, y: -9999 };

    function resize() {
      var r = canvas.getBoundingClientRect();
      W = r.width; H = r.height;
      canvas.width = W * dpr; canvas.height = H * dpr;
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      var n = Math.round(Math.min(90, (W * H) / 16000));
      pts = [];
      for (var i = 0; i < n; i++) {
        pts.push({ x: Math.random() * W, y: Math.random() * H, vx: (Math.random() - .5) * .3, vy: (Math.random() - .5) * .3, r: Math.random() * 1.6 + .6 });
      }
    }
    function frame() {
      ctx.clearRect(0, 0, W, H);
      for (var i = 0; i < pts.length; i++) {
        var p = pts[i];
        p.x += p.vx; p.y += p.vy;
        if (p.x < 0 || p.x > W) p.vx *= -1;
        if (p.y < 0 || p.y > H) p.vy *= -1;
        for (var j = i + 1; j < pts.length; j++) {
          var q = pts[j], dx = p.x - q.x, dy = p.y - q.y, d = dx * dx + dy * dy;
          if (d < 16000) {
            ctx.strokeStyle = 'rgba(' + accent + ',' + (0.16 * (1 - d / 16000)).toFixed(3) + ')';
            ctx.lineWidth = 1;
            ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(q.x, q.y); ctx.stroke();
          }
        }
        var md = (p.x - mouse.x) * (p.x - mouse.x) + (p.y - mouse.y) * (p.y - mouse.y);
        if (md < 30000) {
          ctx.strokeStyle = 'rgba(' + accent + ',' + (0.35 * (1 - md / 30000)).toFixed(3) + ')';
          ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(mouse.x, mouse.y); ctx.stroke();
        }
        ctx.fillStyle = 'rgba(' + accent + ', .85)';
        ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2); ctx.fill();
      }
      if (!reduce) requestAnimationFrame(frame);
    }
    resize();
    window.addEventListener('resize', resize);
    canvas.parentNode.parentNode.addEventListener('pointermove', function (e) {
      var r = canvas.getBoundingClientRect();
      mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top;
    });
    frame();
  }
})();
