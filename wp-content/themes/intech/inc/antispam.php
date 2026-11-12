<?php
/**
 * Защита формы заявки от ботов — без капчи и сторонних сервисов.
 *
 * 1. Скрытое поле-ловушка (в самой форме): люди его не видят, боты заполняют.
 * 2. Метка времени с подписью: форму нельзя отправить быстрее чем через 3 секунды
 *    после открытия страницы и нельзя отправить «заготовкой» без захода на сайт.
 * 3. Отметка от браузера: main.js ставит её, когда человек начал заполнять форму.
 *    Программы, которые шлют запрос напрямую, её не ставят.
 * 4. Ограничение частоты: не больше 3 заявок за 10 минут и 15 за сутки с одного адреса.
 * 5. Содержимое: ссылки в имени и больше двух ссылок в сообщении — признак рассылки.
 * 6. Повтор той же заявки в течение 2 минут второй раз не записывается.
 *
 * Ботам не сообщаем, что их отсеяли: они получают обычный переход на главную.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const INTECH_FORM_MIN_SECONDS = 3;
const INTECH_FORM_MAX_AGE     = DAY_IN_SECONDS;

/** Подпись метки времени: подделать её без секретного ключа сайта нельзя. */
function intech_form_sign( $ts ) {
	return substr( hash_hmac( 'sha256', 'intech_lead|' . $ts, wp_salt( 'nonce' ) ), 0, 20 );
}

/** Скрытые поля защиты — выводятся внутри формы. */
function intech_form_guard_fields() {
	$ts = time();
	printf(
		'<input type="hidden" name="intech_ts" value="%1$s"><input type="hidden" name="intech_js" value="" data-guard="%2$s">',
		esc_attr( $ts . '.' . intech_form_sign( $ts ) ),
		esc_attr( $ts )
	);
}

/** Адрес отправителя для ограничения частоты. */
function intech_form_ip_key( $suffix ) {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	return 'intech_lead_' . $suffix . '_' . md5( $ip );
}

/**
 * Проверка заявки.
 *
 * @return string '' — заявка в порядке; 'bot' — отсеять молча; 'limit' — слишком часто; 'dup' — повтор.
 */
function intech_form_guard_check( $name, $phone, $message ) {
	// phpcs:disable WordPress.Security.NonceVerification -- nonce проверен в intech_handle_lead().
	$raw = isset( $_POST['intech_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['intech_ts'] ) ) : '';
	$js  = isset( $_POST['intech_js'] ) ? sanitize_text_field( wp_unslash( $_POST['intech_js'] ) ) : '';
	// phpcs:enable

	$parts = explode( '.', $raw );
	if ( 2 !== count( $parts ) || ! ctype_digit( $parts[0] ) || ! hash_equals( intech_form_sign( $parts[0] ), $parts[1] ) ) {
		return 'bot';
	}
	$age = time() - (int) $parts[0];
	if ( $age < INTECH_FORM_MIN_SECONDS || $age > INTECH_FORM_MAX_AGE ) {
		return 'bot';
	}
	// Отметка браузера: «метка времени:сколько миллисекунд человек был на странице».
	if ( ! preg_match( '/^' . preg_quote( $parts[0], '/' ) . ':(\d+)$/', $js, $m ) || (int) $m[1] < 1000 ) {
		return 'bot';
	}

	$links = '~(https?://|www\.|\[url|<a\s)~iu';
	if ( preg_match( $links, $name ) || preg_match_all( $links, $message ) > 2 ) {
		return 'bot';
	}

	$dup = 'intech_lead_dup_' . md5( $phone . '|' . $message );
	if ( get_transient( $dup ) ) {
		return 'dup';
	}

	$short = intech_form_ip_key( '10m' );
	$long  = intech_form_ip_key( 'day' );
	if ( (int) get_transient( $short ) >= 3 || (int) get_transient( $long ) >= 15 ) {
		return 'limit';
	}
	set_transient( $short, (int) get_transient( $short ) + 1, 10 * MINUTE_IN_SECONDS );
	set_transient( $long, (int) get_transient( $long ) + 1, DAY_IN_SECONDS );
	set_transient( $dup, 1, 2 * MINUTE_IN_SECONDS );
	return '';
}
