<?php
/**
 * Отправка почты сайта через SMTP почтового ящика.
 *
 * Настройки задаются константами в wp-config.php (не в теме и не в репозитории):
 *
 *   define( 'INTECH_SMTP_HOST', 'smtp.beget.com' );
 *   define( 'INTECH_SMTP_PORT', 465 );            // 465 — SSL, 587 — STARTTLS
 *   define( 'INTECH_SMTP_USER', 'info@intex.click' );
 *   define( 'INTECH_SMTP_PASS', 'пароль от ящика' );
 *
 * Если константы не заданы, WordPress отправляет почту как обычно (через mail()).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Включена ли отправка через SMTP. */
function intech_smtp_enabled() {
	return defined( 'INTECH_SMTP_HOST' ) && defined( 'INTECH_SMTP_USER' ) && defined( 'INTECH_SMTP_PASS' )
		&& INTECH_SMTP_HOST && INTECH_SMTP_USER && INTECH_SMTP_PASS;
}

add_action( 'phpmailer_init', function ( $mailer ) {
	if ( ! intech_smtp_enabled() ) {
		return;
	}
	$port = defined( 'INTECH_SMTP_PORT' ) ? (int) INTECH_SMTP_PORT : 465;

	$mailer->isSMTP();
	$mailer->Host       = INTECH_SMTP_HOST;
	$mailer->Port       = $port;
	$mailer->SMTPAuth   = true;
	$mailer->SMTPSecure = 465 === $port ? 'ssl' : 'tls';
	$mailer->Username   = INTECH_SMTP_USER;
	$mailer->Password   = INTECH_SMTP_PASS;
	$mailer->Timeout    = 15;
	$mailer->CharSet    = 'UTF-8';
	// Отправитель должен совпадать с ящиком, иначе почтовый сервер отклонит письмо.
	$mailer->setFrom( INTECH_SMTP_USER, 'Сайт ИНТЕХ', false );
} );

/* Ошибки отправки — в журнал PHP, чтобы их было видно в «Логах» хостинга. */
add_action( 'wp_mail_failed', function ( $error ) {
	if ( is_wp_error( $error ) ) {
		error_log( 'intech mail failed: ' . $error->get_error_message() );
	}
} );
