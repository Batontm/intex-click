<?php
/**
 * Телефон (скрытый до наведения) и мессенджеры.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Почта компании: показывается на сайте, на неё приходят заявки из формы. */
function intech_email() {
	$e = intech_opt( 'intech_site_email', 'info@intex.click' );
	return is_email( $e ) ? $e : 'info@intex.click';
}

/** Ссылка на почту компании. */
function intech_email_link( $class = '' ) {
	$e = intech_email();
	return '<a class="' . esc_attr( trim( 'email-link ' . $class ) ) . '" href="mailto:' . esc_attr( $e ) . '">' . esc_html( $e ) . '</a>';
}

/** Мессенджеры: [название, ссылка, короткая подпись]. Пустые ссылки не выводятся. */
function intech_messengers() {
	$list = array(
		array( 'Telegram', intech_opt( 'intech_tg', 'https://t.me/Alexey_K_KLD' ), 'TG' ),
		array( 'MAX', intech_opt( 'intech_max', 'https://max.ru/u/f9LHodD0cOIwLg04EShW-1I7Plcar58GvXEsrOKXsAnuI3U0PsP2JRZo-L0' ), 'MAX' ),
		array( 'ВКонтакте', intech_opt( 'intech_vk', '' ), 'VK' ),
	);
	return array_values( array_filter( $list, function ( $m ) {
		return $m[1] && '#' !== $m[1];
	} ) );
}

/**
 * Телефон, у которого последние цифры скрыты. Полный номер показывается
 * при наведении (на телефоне — по первому нажатию), второе нажатие звонит.
 * Полного номера нет в тексте страницы — он собирается скриптом.
 */
function intech_phone_link( $class = '' ) {
	$phone  = intech_opt( 'intech_phone', '+7 (931) 605-44-84' );
	$tel    = preg_replace( '/[^0-9+]/', '', $phone );
	$masked = $phone;
	$left   = 4;
	for ( $i = strlen( $masked ) - 1; $i >= 0 && $left > 0; $i-- ) {
		if ( ctype_digit( $masked[ $i ] ) ) {
			$masked = substr_replace( $masked, '•', $i, 1 );
			$left--;
		}
	}
	return sprintf(
		'<a class="phone-mask %1$s" href="%2$s" data-p="%3$s" data-t="%4$s" aria-label="Показать номер телефона"><span class="phone-mask__txt">%5$s</span></a>',
		esc_attr( $class ),
		esc_url( intech_page_url( 'kontakty' ) ),
		esc_attr( base64_encode( strrev( $phone ) ) ),
		esc_attr( base64_encode( strrev( $tel ) ) ),
		esc_html( $masked )
	);
}
