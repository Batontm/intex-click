<?php
/**
 * Тема ИНТЕХ — функции.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'INTECH_VERSION', '2.7.2' );

require_once get_template_directory() . '/inc/landings.php';
require_once get_template_directory() . '/inc/legal.php';
require_once get_template_directory() . '/inc/logo.php';
require_once get_template_directory() . '/inc/contacts.php';
require_once get_template_directory() . '/inc/articles.php';
require_once get_template_directory() . '/inc/metrika.php';

/* ---------- Базовая настройка темы ---------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo' );
	register_nav_menus( array( 'primary' => 'Главное меню' ) );
} );

/* ---------- Стили и скрипты ---------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'intech-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Unbounded:wght@500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'intech-style', get_stylesheet_uri(), array( 'intech-fonts' ), INTECH_VERSION );
	wp_enqueue_script( 'intech-main', get_template_directory_uri() . '/assets/js/main.js', array(), INTECH_VERSION, true );
} );

/* ---------- Палитра: класс на <body> ---------- */
function intech_palettes() {
	return array(
		'cyan'   => 'Неон: циан + синий',
		'blue'   => 'Неон: синий + индиго',
		'violet' => 'Неон: фиолетовый + розовый',
		'green'  => 'Неон: изумрудный',
	);
}

add_filter( 'body_class', function ( $classes ) {
	$palette = get_theme_mod( 'intech_palette', 'cyan' );
	if ( ! array_key_exists( $palette, intech_palettes() ) ) {
		$palette = 'cyan';
	}
	$classes[] = 'palette-' . $palette;
	return $classes;
} );

/* ---------- Настройки в «Внешний вид → Настроить» ---------- */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'intech', array(
		'title'    => 'ИНТЕХ: оформление и контакты',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'intech_palette', array(
		'default'           => 'cyan',
		'sanitize_callback' => function ( $v ) {
			return array_key_exists( $v, intech_palettes() ) ? $v : 'cyan';
		},
	) );
	$wp_customize->add_control( 'intech_palette', array(
		'label'   => 'Палитра',
		'section' => 'intech',
		'type'    => 'select',
		'choices' => intech_palettes(),
	) );

	$wp_customize->add_setting( 'intech_hero_video', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'intech_hero_video', array(
		'label'       => 'Видео на первом экране (MP4)',
		'description' => 'Загрузите ролик или вставьте ссылку. Если пусто — берётся assets/video/hero.mp4 из темы.',
		'section'     => 'intech',
		'mime_type'   => 'video',
	) ) );

	$text_fields = array(
		'intech_phone'   => array( 'Телефон', '+7 (931) 605-44-84' ),
		'intech_site_email' => array( 'Почта компании (показывается на сайте, на неё приходят заявки)', 'info@intex.click' ),
		'intech_address' => array( 'Адрес (если пусто — на сайте не показывается)', '' ),
		'intech_tg'      => array( 'Ссылка на Telegram', 'https://t.me/Alexey_K_KLD' ),
		'intech_max'     => array( 'Ссылка на MAX', 'https://max.ru/u/f9LHodD0cOIwLg04EShW-1I7Plcar58GvXEsrOKXsAnuI3U0PsP2JRZo-L0' ),
		'intech_vk'      => array( 'Ссылка на ВКонтакте', '' ),
	);
	foreach ( $text_fields as $id => $field ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $field[1],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $field[0],
			'section' => 'intech',
			'type'    => 'text',
		) );
	}
} );

/** Значение настройки с запасным текстом. */
function intech_opt( $key, $fallback = '' ) {
	$v = get_theme_mod( $key, $fallback );
	return '' === $v ? $fallback : $v;
}

/** URL видео первого экрана: из настроек или файл в теме. */
function intech_hero_video_url() {
	$url = get_theme_mod( 'intech_hero_video', '' );
	if ( $url ) {
		return $url;
	}
	if ( file_exists( get_template_directory() . '/assets/video/hero.mp4' ) ) {
		return get_template_directory_uri() . '/assets/video/hero.mp4';
	}
	return '';
}

/* ---------- Иконки (линейные SVG) ---------- */
function intech_icon( $name, $class = 'icon' ) {
	$paths = array(
		'web'   => 'M3 5h18v14H3zM3 9h18M7 7h.01M10 7h.01',
		'seo'   => 'M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14zM21 21l-5-5M8 13l2-3 2 2 2-3',
		'ads'   => 'M4 10v4h3l6 4V6L7 10zM17 9a4 4 0 0 1 0 6M19.5 6.5a8 8 0 0 1 0 11',
		'smm'   => 'M4 5h16v11H9l-5 4zM8 10h8M8 13h5',
		'srv'   => 'M3 4h18v6H3zM3 14h18v6H3zM7 7h.01M7 17h.01',
		'net'   => 'M2 9a14 14 0 0 1 20 0M5 12.5a10 10 0 0 1 14 0M8.5 16a5 5 0 0 1 7 0M12 20h.01',
		'brand' => 'M12 3l2.5 5.5L20 9l-4 4 1 6-5-3-5 3 1-6-4-4 5.5-.5z',
		'check' => 'm5 12 5 5 9-10',
		'support' => 'M4 13v-1a8 8 0 0 1 16 0v1M4 13h3v5H5a1 1 0 0 1-1-1zM20 13h-3v5h2a1 1 0 0 0 1-1zM17 18c0 1.7-2 3-5 3',
		'code'  => 'M8 8l-5 4 5 4M16 8l5 4-5 4M14 5l-4 14',
		'cam'   => 'M3 7l13-3 2 6-13 3zM8 12l1 4h4M9 16v4M5 20h8M18 9l3-1',
		'move'  => 'M3 7h11v9H3zM14 10h4l3 3v3h-7zM7 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4z',
	);
	$d = isset( $paths[ $name ] ) ? $paths[ $name ] : '';
	return '<svg class="' . esc_attr( $class ) . '" viewBox="0 0 24 24" aria-hidden="true"><path d="' . esc_attr( $d ) . '"/></svg>';
}

/* ---------- Форма заявки (без плагинов) ---------- */
add_action( 'admin_post_nopriv_intech_lead', 'intech_handle_lead' );
add_action( 'admin_post_intech_lead', 'intech_handle_lead' );

function intech_handle_lead() {
	if ( ! isset( $_POST['intech_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['intech_nonce'] ) ), 'intech_lead' ) ) {
		wp_die( 'Ошибка проверки формы. Обновите страницу и попробуйте снова.' );
	}
	if ( empty( $_POST['consent'] ) ) {
		wp_die( 'Чтобы отправить заявку, отметьте согласие на обработку персональных данных.', 'Нужно согласие', array( 'back_link' => true ) );
	}
	// Ловушка для ботов: скрытое поле должно быть пустым.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( home_url( '/#contacts' ) );
		exit;
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	$to      = intech_email();
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}
	$subject = 'Новая заявка с сайта ИНТЕХ';
	$body    = "Имя: {$name}\nEmail: {$email}\nТелефон: {$phone}\n\nСообщение:\n{$message}\n";
	$headers = array();
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $email;
	}
	wp_mail( $to, $subject, $body, $headers );

	// Копия заявки в базе (на случай, если почта не настроена).
	wp_insert_post( array(
		'post_type'    => 'intech_lead',
		'post_status'  => 'private',
		'post_title'   => $name ? $name . ' — ' . $phone : 'Заявка ' . current_time( 'mysql' ),
		'post_content' => $body,
	) );

	wp_safe_redirect( add_query_arg( 'sent', '1', home_url( '/' ) ) . '#contacts' );
	exit;
}

/* Раздел «Заявки» в админке. */
add_action( 'init', function () {
	register_post_type( 'intech_lead', array(
		'label'         => 'Заявки',
		'public'        => false,
		'show_ui'       => true,
		'menu_icon'     => 'dashicons-email-alt',
		'supports'      => array( 'title', 'editor' ),
		'capability_type' => 'post',
		'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'  => true,
	) );
} );

/* ---------- Иллюстрации решений (встроенные SVG, берут цвета палитры) ---------- */
function intech_art( $name ) {
	$allowed = array( 'web', 'soft', 'infra', 'seo', 'net', 'cctv' );
	if ( ! in_array( $name, $allowed, true ) ) {
		return '';
	}
	$file = get_template_directory() . '/assets/img/sol-' . $name . '.svg';
	return file_exists( $file ) ? file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions
}

/* ---------- Кейсы: раздел в админке ---------- */
add_action( 'init', function () {
	register_post_type( 'intech_case', array(
		'labels'        => array(
			'name'          => 'Кейсы',
			'singular_name' => 'Кейс',
			'add_new'       => 'Добавить кейс',
			'add_new_item'  => 'Новый кейс',
			'edit_item'     => 'Редактировать кейс',
			'all_items'     => 'Все кейсы',
		),
		'public'        => true,
		'has_archive'   => 'cases',
		'rewrite'       => array( 'slug' => 'cases' ),
		'menu_icon'     => 'dashicons-portfolio',
		'menu_position' => 6,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'  => true,
	) );
	register_post_meta( 'intech_case', 'case_tag', array(
		'type'         => 'string',
		'single'       => true,
		'show_in_rest' => true,
		'description'  => 'Метка над названием, например «Сайт · SEO»',
	) );
	// Один раз обновляем ссылки, чтобы заработал адрес /cases/.
	if ( get_option( 'intech_cases_rewrite' ) !== '1' ) {
		flush_rewrite_rules( false );
		update_option( 'intech_cases_rewrite', '1' );
	}
} );
