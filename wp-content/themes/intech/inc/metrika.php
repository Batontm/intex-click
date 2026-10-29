<?php
/**
 * Яндекс Метрика: счётчик и цели.
 *
 * Цели (тип «JavaScript-событие» в Метрике):
 *   lead        — отправлена форма заявки;
 *   phone_click — клик по телефону;
 *   tg_click    — переход в Telegram;
 *   max_click   — переход в MAX;
 *   email_click — клик по почте;
 *   vk_click    — переход во ВКонтакте;
 *   ig_click    — переход в Instagram.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'INTECH_METRIKA_ID', 113813956 );

add_action( 'wp_head', function () {
	$id = (int) INTECH_METRIKA_ID;
	?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=<?php echo $id; ?>', 'ym');

    ym(<?php echo $id; ?>, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/<?php echo $id; ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
(function () {
	var ID = <?php echo $id; ?>;
	function goal(name) { try { if (window.ym) { ym(ID, 'reachGoal', name); } } catch (e) {} }
	// Заявка: после отправки формы сервер возвращает на страницу с ?sent=1.
	try {
		var u = new URL(window.location.href);
		if (u.searchParams.get('sent') === '1') {
			goal('lead');
			u.searchParams.delete('sent');
			history.replaceState(null, '', u.pathname + u.search + u.hash);
		}
	} catch (e) {}
	// Клики по контактам.
	document.addEventListener('click', function (ev) {
		var a = ev.target && ev.target.closest ? ev.target.closest('a[href]') : null;
		if (!a) { return; }
		var h = a.getAttribute('href') || '';
		if (h.indexOf('tel:') === 0) { goal('phone_click'); }
		else if (h.indexOf('mailto:') === 0) { goal('email_click'); }
		else if (/^https?:\/\/(t\.me|telegram\.me)\//i.test(h)) { goal('tg_click'); }
		else if (/^https?:\/\/(www\.)?max\.ru\//i.test(h)) { goal('max_click'); }
		else if (/^https?:\/\/(www\.)?(vk\.ru|vk\.com)\//i.test(h)) { goal('vk_click'); }
		else if (/^https?:\/\/(www\.)?instagram\.com\//i.test(h)) { goal('ig_click'); }
	}, true);
})();
</script>
	<?php
}, 2 );
