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

  // --- Телефон в форме: только цифры, вид +7 (999) 123-45-67 ---
  // Первая 8 или 7 превращается в +7; вставленный номер в любом виде приводится к тому же формату.
  function phoneDigits(v) {
    v = String(v);
    var d = v.replace(/\D/g, '');
    var lead = d.charAt(0) === '7' || d.charAt(0) === '8';
    if (/^\s*\+\s*7/.test(v)) d = d.slice(1);          // уже с +7: это код страны
    else if (lead && d.length !== 10) d = d.slice(1);  // набрали 8… или 7…: тоже код страны
    if (d.length > 10 && (d.charAt(0) === '7' || d.charAt(0) === '8')) d = d.slice(1); // +7 (8 931…) — лишняя восьмёрка
    return d.slice(0, 10);
  }
  function phoneFormat(d) {
    var s = '+7 (' + d.slice(0, 3);
    if (d.length > 3) s += ') ' + d.slice(3, 6);
    if (d.length > 6) s += '-' + d.slice(6, 8);
    if (d.length > 8) s += '-' + d.slice(8, 10);
    return s;
  }
  Array.prototype.forEach.call(document.querySelectorAll('input[type="tel"]'), function (inp) {
    var prev = '';
    function check(d) {
      inp.setCustomValidity(d.length === 10 || (!d.length && !inp.required) ? '' : 'Введите номер полностью: +7 и 10 цифр');
    }
    function apply(e) {
      if (inp.value === '+') return;
      var d = phoneDigits(inp.value);
      var del = e && e.inputType && e.inputType.indexOf('delete') === 0;
      if (del && d === prev && d.length) d = d.slice(0, -1); // стёрли скобку или дефис — убираем цифру перед ним
      inp.value = d.length || !del ? phoneFormat(d) : '';
      prev = d;
      check(d);
    }
    inp.addEventListener('input', apply);
    inp.addEventListener('change', function () { if (inp.value) apply(); });
    inp.addEventListener('blur', function () { if (!phoneDigits(inp.value).length) { inp.value = ''; prev = ''; } });
    if (inp.value) apply(); else check('');
  });

  // --- Защита формы от ботов: отметка, что форму заполняет человек в браузере ---
  var loadedAt = Date.now();
  Array.prototype.forEach.call(document.querySelectorAll('input[name="intech_js"]'), function (g) {
    var form = g.form;
    if (!form) return;
    function mark() { g.value = g.getAttribute('data-guard') + ':' + (Date.now() - loadedAt); }
    ['pointerdown', 'keydown', 'focusin', 'input'].forEach(function (ev) { form.addEventListener(ev, mark); });
    form.addEventListener('submit', mark);
  });

  // --- Видео на фоне первого экрана ---
  // Грузим только на широких экранах и без «экономии трафика» / «уменьшения движения».
  // Пока первый экран не виден, ролик стоит на паузе.
  var video = document.querySelector('.hero__video');
  var conn = navigator.connection || {};
  var wide = window.matchMedia && window.matchMedia('(min-width: 768px)').matches;
  if (video && wide && !reduce && !conn.saveData) {
    if (video.dataset.poster) video.poster = video.dataset.poster;
    Array.prototype.forEach.call(video.querySelectorAll('source[data-src]'), function (s) { s.src = s.dataset.src; });
    video.muted = true;
    video.addEventListener('playing', function () { video.classList.add('is-on'); }, { once: true });
    video.load();

    var inView = true;
    var play = function () {
      if (!inView || document.hidden) return;
      var p = video.play(); if (p && p.catch) p.catch(function () {});
    };
    // Повтор, когда ролик догрузился: первый play() мог не сработать в фоновой вкладке.
    video.addEventListener('canplay', play);
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        inView = entries[0].isIntersecting;
        if (inView) play(); else video.pause();
      }).observe(video.closest('.hero') || video);
    } else {
      play();
    }
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) video.pause(); else play();
    });
  }
})();
