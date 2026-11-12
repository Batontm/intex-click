<?php
/**
 * Защита и облегчение страниц: заголовки безопасности, XML-RPC, версия WordPress,
 * ограничение попыток входа, лишние стили редактора блоков.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- XML-RPC не используется: закрываем целиком ---------- */
if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
	status_header( 403 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	exit( 'XML-RPC disabled.' );
}
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_headers', function ( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
} );

/* ---------- Заголовки безопасности ---------- */
add_filter( 'wp_headers', function ( $headers ) {
	if ( is_ssl() ) {
		$headers['Strict-Transport-Security'] = 'max-age=31536000';
	}
	$headers['X-Content-Type-Options'] = 'nosniff';
	$headers['Referrer-Policy']        = 'strict-origin-when-cross-origin';
	$headers['Permissions-Policy']     = 'camera=(), microphone=(), geolocation=(), payment=()';
	// Встраивать сайт во фрейм можно только самому сайту и Вебвизору Метрики (иначе записи не откроются).
	$headers['Content-Security-Policy'] = "frame-ancestors 'self' https://metrika.yandex.ru https://metrika.yandex.com https://webvisor.com https://*.webvisor.com";
	return $headers;
} );

/* ---------- Не показываем версию WordPress и служебные ссылки ---------- */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
add_filter( 'the_generator', '__return_empty_string' );

/* readme.html и license.txt раскрывают версию; после обновлений ядра появляются снова. */
add_action( 'init', function () {
	if ( get_transient( 'intech_core_files_checked' ) ) {
		return;
	}
	foreach ( array( 'readme.html', 'license.txt' ) as $f ) {
		$path = ABSPATH . $f;
		if ( is_file( $path ) && is_writable( $path ) ) {
			@unlink( $path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		}
	}
	set_transient( 'intech_core_files_checked', 1, DAY_IN_SECONDS );
} );

/* ---------- Вход в админку: не больше 10 неудачных попыток с одного адреса за 15 минут ---------- */
function intech_login_key() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	return 'intech_login_fail_' . md5( $ip );
}
add_action( 'wp_login_failed', function () {
	$key = intech_login_key();
	set_transient( $key, (int) get_transient( $key ) + 1, 15 * MINUTE_IN_SECONDS );
} );
add_filter( 'authenticate', function ( $user ) {
	if ( (int) get_transient( intech_login_key() ) >= 10 ) {
		return new WP_Error( 'intech_too_many', 'Слишком много неудачных попыток входа. Попробуйте через 15 минут.' );
	}
	return $user;
}, 30 );
add_action( 'wp_login', function () {
	delete_transient( intech_login_key() );
} );
/* Не подсказываем, что именно неверно — логин или пароль. */
add_filter( 'login_errors', function ( $error ) {
	return false !== strpos( $error, 'Слишком много' ) ? $error : 'Неверный логин или пароль.';
} );

/* ---------- Тема не использует редактор блоков: его стили на сайте не нужны ---------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}, 100 );
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

/* ---------- Шрифты первого экрана загружаем заранее, чтобы текст не «мигал» ---------- */
add_action( 'wp_head', function () {
	$dir = get_template_directory_uri() . '/assets/fonts/';
	foreach ( array( 'unbounded-cyrillic.woff2', 'manrope-cyrillic.woff2' ) as $f ) {
		echo '<link rel="preload" href="' . esc_url( $dir . $f ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	}
}, 1 );
