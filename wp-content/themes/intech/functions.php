<?php
/**
 * Тема ИНТЕХ — функции.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'INTECH_VERSION', '2.11.1' );

require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/hardening.php';
require_once get_template_directory() . '/inc/landings.php';
require_once get_template_directory() . '/inc/legal.php';
require_once get_template_directory() . '/inc/logo.php';
require_once get_template_directory() . '/inc/contacts.php';
require_once get_template_directory() . '/inc/articles.php';
require_once get_template_directory() . '/inc/metrika.php';
require_once get_template_directory() . '/inc/smtp.php';
require_once get_template_directory() . '/inc/cookies.php';

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
	// Шрифты лежат в теме: без запросов к Google (IP посетителей не уходит за рубеж).
	wp_enqueue_style( 'intech-fonts', get_template_directory_uri() . '/assets/fonts/fonts.css', array(), INTECH_VERSION );
	wp_enqueue_style( 'intech-style', get_stylesheet_uri(), array( 'intech-fonts' ), INTECH_VERSION );
	wp_enqueue_script( 'intech-main', get_template_directory_uri() . '/assets/js/main.js', array(), INTECH_VERSION, true );
} );

/* ---------- Без обращений к чужим серверам ---------- */
// Скрипт эмодзи WordPress грузит картинки с s.w.org — он не нужен.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter( 'emoji_svg_url', '__return_false' );
add_filter( 'wp_resource_hints', function ( $urls, $type ) {
	if ( 'dns-prefetch' === $type ) {
		$urls = array_filter( $urls, function ( $u ) {
			$u = is_array( $u ) ? ( $u['href'] ?? '' ) : $u;
			return false === strpos( $u, 's.w.org' ) && false === strpos( $u, 'fonts.g' );
		} );
	}
	return $urls;
}, 10, 2 );

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
		'description' => 'Загрузите ролик или вставьте ссылку. Если пусто — берутся assets/video/hero.webm и hero.mp4 из темы (и заставка hero.jpg; как подготовить — docs/hero-video.md в репозитории). На телефонах видео не загружается.',
		'section'     => 'intech',
		'mime_type'   => 'video',
	) ) );

	$text_fields = array(
		'intech_phone'   => array( 'Телефон', '+7 (931) 605-44-84' ),
		'intech_site_email' => array( 'Почта компании (показывается на сайте, на неё приходят заявки)', 'info@intex.click' ),
		'intech_address' => array( 'Адрес (если пусто — на сайте не показывается)', '' ),
		'intech_tg'      => array( 'Ссылка на Telegram', 'https://t.me/Alexey_K_KLD' ),
		'intech_max'     => array( 'Ссылка на MAX', 'https://max.ru/u/f9LHodD0cOIwLg04EShW-1I7Plcar58GvXEsrOKXsAnuI3U0PsP2JRZo-L0' ),
		'intech_vk'      => array( 'Ссылка на ВКонтакте', 'https://vk.ru/intexclick' ),
		'intech_instagram' => array( 'Ссылка на Instagram', 'https://www.instagram.com/intex.click/' ),
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

/**
 * Видео первого экрана: источники и заставка.
 *
 * Берёт ролик из настроек темы или файлы assets/video/hero.webm, hero.mp4
 * и заставку hero.jpg. Возвращает null, если видео нет.
 *
 * @return array{sources: array<int, array{src: string, type: string}>, poster: string}|null
 */
function intech_hero_video() {
	$dir     = get_template_directory() . '/assets/video/';
	$uri     = get_template_directory_uri() . '/assets/video/';
	$file    = function ( $name ) use ( $dir, $uri ) {
		return file_exists( $dir . $name ) ? $uri . $name . '?v=' . filemtime( $dir . $name ) : '';
	};
	$sources = array();

	$custom = get_theme_mod( 'intech_hero_video', '' );
	if ( $custom ) {
		$sources[] = array( 'src' => $custom, 'type' => 'video/mp4' );
	} else {
		// WebM обычно легче, поэтому идёт первым; MP4 — для старых iPhone.
		if ( $webm = $file( 'hero.webm' ) ) {
			$sources[] = array( 'src' => $webm, 'type' => 'video/webm' );
		}
		if ( $mp4 = $file( 'hero.mp4' ) ) {
			$sources[] = array( 'src' => $mp4, 'type' => 'video/mp4' );
		}
	}

	if ( ! $sources ) {
		return null;
	}
	return array( 'sources' => $sources, 'poster' => $file( 'hero.jpg' ) );
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
		'phone' => 'M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z',
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

/**
 * Телефон из формы в едином виде: +7 (999) 123-45-67.
 * Принимает номер в любой записи (с 8, с +7, без кода страны); если цифр не 10 — пустая строка.
 */
function intech_normalize_phone( $raw ) {
	$d = preg_replace( '/\D/', '', (string) $raw );
	if ( 11 === strlen( $d ) && in_array( $d[0], array( '7', '8' ), true ) ) {
		$d = substr( $d, 1 );
	}
	if ( 10 !== strlen( $d ) ) {
		return '';
	}
	return sprintf( '+7 (%s) %s-%s-%s', substr( $d, 0, 3 ), substr( $d, 3, 3 ), substr( $d, 6, 2 ), substr( $d, 8, 2 ) );
}

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
	$phone   = intech_normalize_phone( sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) ) );
	if ( ! $phone ) {
		wp_die( 'Проверьте номер телефона: нужны +7 и 10 цифр, например +7 (931) 123-45-67.', 'Неверный номер', array( 'back_link' => true ) );
	}
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
	$sent = wp_mail( $to, $subject, $body, $headers );

	// Копия заявки в базе (на случай, если почта не настроена).
	wp_insert_post( array(
		'post_type'    => 'intech_lead',
		'post_status'  => 'private',
		'post_title'   => $name ? $name . ' — ' . $phone : 'Заявка ' . current_time( 'mysql' ),
		'post_content' => $body . "\n" . ( $sent ? 'Письмо отправлено на ' . $to : 'Письмо НЕ отправлено — проверьте настройки почты' ),
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
