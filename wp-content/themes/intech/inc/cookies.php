<?php
/**
 * Баннер согласия на cookie.
 *
 * Пока посетитель не нажал «Принять», Яндекс Метрика не загружается и её cookie
 * не ставятся (см. inc/metrika.php). Выбор хранится в cookie intech_cookies
 * (all — принял, necessary — только необходимые) один год. Ссылка «Настройки cookie»
 * в подвале снова открывает баннер; при отказе cookie Метрики удаляются.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Ссылка, открывающая настройки cookie. */
function intech_cookie_settings_link( $text = 'Настройки cookie' ) {
	return '<button type="button" class="cookie-settings-link" data-cookie-settings>' . esc_html( $text ) . '</button>';
}

add_action( 'wp_footer', function () {
	?>
<div class="cookie-bar" id="cookieBar" role="dialog" aria-live="polite" aria-label="Согласие на использование cookie" hidden>
	<p class="cookie-bar__text">
		Используем cookie и Яндекс Метрику для статистики посещений. Метрика включится только после «Принять».
		<a href="<?php echo esc_url( intech_privacy_url() ); ?>">Подробнее</a>
	</p>
	<div class="cookie-bar__actions">
		<button type="button" class="btn btn--accent btn--sm" data-cookie="all">Принять</button>
		<button type="button" class="btn btn--ghost btn--sm" data-cookie="necessary">Отклонить</button>
	</div>
</div>
<script>
(function () {
	var bar = document.getElementById('cookieBar');
	if (!bar) { return; }
	var NAME = 'intech_cookies';
	function get() { var m = document.cookie.match(/(?:^|;\s*)intech_cookies=([^;]*)/); return m ? m[1] : ''; }
	function set(v) {
		document.cookie = NAME + '=' + v + '; Max-Age=31536000; Path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
	}
	function dropMetrika() {
		var host = location.hostname, parts = host.split('.'), domains = ['', host, '.' + host];
		if (parts.length > 1) { domains.push('.' + parts.slice(-2).join('.')); }
		document.cookie.split(';').forEach(function (c) {
			var n = c.split('=')[0].trim();
			if (n.indexOf('_ym') === 0 || n === 'mdd') {
				domains.forEach(function (d) { document.cookie = n + '=; Max-Age=0; Path=/' + (d ? '; Domain=' + d : ''); });
			}
		});
		try { Object.keys(localStorage).forEach(function (k) { if (k.indexOf('_ym') === 0) { localStorage.removeItem(k); } }); } catch (e) {}
	}
	function show() { bar.hidden = false; }
	function hide() { bar.hidden = true; }

	bar.addEventListener('click', function (e) {
		var b = e.target.closest('[data-cookie]');
		if (!b) { return; }
		var v = b.getAttribute('data-cookie');
		var wasOn = !!window.intechMetrikaOn;
		set(v);
		hide();
		if (v === 'all') {
			if (window.intechMetrika) { window.intechMetrika(); }
		} else {
			dropMetrika();
			if (wasOn) { location.reload(); }
		}
	});
	document.addEventListener('click', function (e) {
		if (e.target.closest('[data-cookie-settings]')) { e.preventDefault(); show(); }
	});
	if (!get()) { show(); }
})();
</script>
	<?php
}, 20 );
