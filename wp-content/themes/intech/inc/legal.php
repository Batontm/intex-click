<?php
/**
 * Реквизиты, политика конфиденциальности и общие SEO-настройки сайта.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Реквизиты организации. */
function intech_company() {
	return array(
		'name'  => 'ООО «ИНТЕХ»',
		'full'  => 'Общество с ограниченной ответственностью «ИНТЕХ»',
		'inn'   => '3906959112',
		'ogrn'  => '1153926011876',
		'city'  => 'Калининград',
	);
}

/** Отдельные страницы сайта: адрес => шаблон, заголовок, описание, название в крошках. */
function intech_pages() {
	return array(
		'o-kompanii'                 => array( 'template-about.php', 'О компании ИНТЕХ — IT-компания в Калининграде', 'ИНТЕХ — IT-компания из Калининграда: разработка сайтов и программ, продвижение в интернете, обслуживание серверов, сетей и компьютеров организаций.', 'О компании' ),
		'kontakty'                   => array( 'template-contacts.php', 'Контакты ИНТЕХ — Калининград', 'Контакты ИНТЕХ: телефон, почта, Telegram, MAX, форма заявки и реквизиты. Работаем по Калининграду и Калининградской области.', 'Контакты' ),
		'politika-konfidencialnosti' => array( 'template-privacy.php', 'Политика обработки персональных данных — ИНТЕХ', 'Политика ООО «ИНТЕХ» в отношении обработки персональных данных посетителей сайта.', 'Политика конфиденциальности' ),
	);
}

/** Адрес отдельной страницы. */
function intech_page_url( $slug ) {
	return home_url( '/' . $slug . '/' );
}

/** Адрес страницы политики. */
function intech_privacy_url() {
	return intech_page_url( 'politika-konfidencialnosti' );
}

/** Текущая отдельная страница (адрес) или пустая строка. */
function intech_current_page() {
	$slug = get_query_var( 'intech_legal' );
	return ( $slug && isset( intech_pages()[ $slug ] ) ) ? $slug : '';
}

function intech_is_privacy() {
	return 'politika-konfidencialnosti' === intech_current_page();
}

/* ---------- Адреса отдельных страниц ---------- */
add_action( 'init', function () {
	$slugs = implode( '|', array_map( 'preg_quote', array_keys( intech_pages() ) ) );
	add_rewrite_rule( '^(' . $slugs . ')/?$', 'index.php?intech_legal=$matches[1]', 'top' );
	$ver = md5( $slugs );
	if ( get_option( 'intech_legal_rw' ) !== $ver ) {
		flush_rewrite_rules( false );
		update_option( 'intech_legal_rw', $ver );
	}
} );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'intech_legal';
	return $vars;
} );

add_action( 'parse_query', function ( $q ) {
	if ( $q->is_main_query() && $q->get( 'intech_legal' ) ) {
		$q->is_home = false;
		$q->is_404  = false;
	}
} );

add_filter( 'redirect_canonical', function ( $redirect ) {
	return get_query_var( 'intech_legal' ) ? false : $redirect;
} );

add_filter( 'template_include', function ( $template ) {
	$slug = intech_current_page();
	if ( $slug ) {
		status_header( 200 );
		$t = locate_template( intech_pages()[ $slug ][0] );
		if ( $t ) {
			return $t;
		}
	}
	return $template;
} );

/* ---------- Заголовки страниц в поиске ---------- */
add_filter( 'pre_get_document_title', function ( $title ) {
	$slug = intech_current_page();
	if ( $slug ) {
		return intech_pages()[ $slug ][1];
	}
	if ( is_front_page() ) {
		return 'IT-компания в Калининграде: сайты, SEO и IT-обслуживание — ИНТЕХ';
	}
	return $title;
}, 20 );

/* ---------- Главная: canonical, Open Graph, разметка организации ---------- */
add_action( 'wp_head', function () {
	$c = intech_company();
	if ( is_front_page() ) {
		$url   = home_url( '/' );
		$title = 'IT-компания в Калининграде: сайты, SEO и IT-обслуживание — ИНТЕХ';
		$desc  = 'ИНТЕХ — разработка сайтов и программных продуктов, продвижение в интернете, полное сопровождение IT-инфраструктуры.';
		echo '<link rel="canonical" href="' . esc_url( $url ) . "\">\n";
		echo '<meta property="og:title" content="' . esc_attr( $title ) . "\">\n";
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . "\">\n";
		echo '<meta property="og:url" content="' . esc_url( $url ) . "\">\n";
		echo '<meta property="og:type" content="website">' . "\n";
		echo '<meta property="og:site_name" content="ИНТЕХ">' . "\n";
		echo '<meta property="og:locale" content="ru_RU">' . "\n";

		$services = array();
		foreach ( intech_landings() as $slug => $l ) {
			$services[] = array(
				'@type'       => 'Offer',
				'itemOffered' => array( '@type' => 'Service', 'name' => $l['name'], 'url' => intech_landing_url( $slug ) ),
			);
		}
		$org = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'Organization',
			'name'       => 'ИНТЕХ',
			'legalName'  => $c['full'],
			'taxID'      => $c['inn'],
			'url'        => $url,
			'telephone'  => intech_opt( 'intech_phone', '+7 (931) 605-44-84' ),
			'email'      => intech_email(),
			'areaServed' => array( 'Калининград', 'Калининградская область' ),
			'identifier' => array( '@type' => 'PropertyValue', 'propertyID' => 'ОГРН', 'value' => $c['ogrn'] ),
			'hasOfferCatalog' => array( '@type' => 'OfferCatalog', 'name' => 'Услуги', 'itemListElement' => $services ),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n";
	}
	$slug = intech_current_page();
	if ( $slug ) {
		$pg  = intech_pages()[ $slug ];
		$url = intech_page_url( $slug );
		echo '<meta name="description" content="' . esc_attr( $pg[2] ) . "\">\n";
		echo '<link rel="canonical" href="' . esc_url( $url ) . "\">\n";
		echo '<meta property="og:title" content="' . esc_attr( $pg[1] ) . "\">\n";
		echo '<meta property="og:description" content="' . esc_attr( $pg[2] ) . "\">\n";
		echo '<meta property="og:url" content="' . esc_url( $url ) . "\">\n";
		echo '<meta property="og:type" content="website">' . "\n";
		$ld = array(
			'@context' => 'https://schema.org',
			'@type'    => 'BreadcrumbList',
			'itemListElement' => array(
				array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => home_url( '/' ) ),
				array( '@type' => 'ListItem', 'position' => 2, 'name' => $pg[3], 'item' => $url ),
			),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n";
	}
	// Иконка сайта.
	echo '<link rel="icon" href="' . esc_url( get_template_directory_uri() . '/assets/img/favicon.svg' ) . '" type="image/svg+xml">' . "\n";
}, 2 );

/* ---------- Что не нужно индексировать ---------- */
add_filter( 'wp_robots', function ( $robots ) {
	$empty_cases = is_post_type_archive( 'intech_case' ) && ! have_posts();
	if ( is_search() || is_author() || $empty_cases || is_page( 'sample-page' ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
} );

/* Страницы авторов не нужны: ведут на главную и раскрывают логин. */
add_action( 'template_redirect', function () {
	if ( is_author() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
} );

/* Список пользователей через REST API — только для вошедших. */
add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
} );

/* Пользователей в карте сайта не показываем. */
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}, 10, 2 );

/* Стандартную «Пример страницы» в карту сайта не включаем. */
add_filter( 'wp_sitemaps_posts_query_args', function ( $args, $post_type ) {
	if ( 'page' === $post_type ) {
		$sample = get_page_by_path( 'sample-page' );
		if ( $sample ) {
			$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), array( $sample->ID ) );
		}
	}
	return $args;
}, 10, 2 );
