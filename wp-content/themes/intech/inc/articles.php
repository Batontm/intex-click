<?php
/**
 * Блог: полезные статьи. Тексты лежат в inc/articles/*.php,
 * страницы создаются темой — в админке ничего делать не нужно.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Разделы блога: адрес => [название, описание]. */
function intech_blog_cats() {
	return array(
		'it'    => array( 'IT для бизнеса', 'Статьи о серверах, сетях, резервном копировании, видеонаблюдении и IT-обслуживании офиса.' ),
		'sayty' => array( 'Сайты и продвижение', 'Статьи о создании сайтов, SEO, Яндекс Картах, аналитике и рекламе для компаний.' ),
	);
}

/** Все статьи, новые сверху. */
function intech_articles() {
	static $list = null;
	if ( null !== $list ) {
		return $list;
	}
	$by_cat = array();
	foreach ( array( 'it', 'sayty' ) as $cat ) {
		$by_cat[ $cat ] = array();
		$file = get_template_directory() . '/inc/articles/' . $cat . '.php';
		if ( file_exists( $file ) ) {
			$items = include $file;
			foreach ( (array) $items as $slug => $a ) {
				$a['slug'] = $slug;
				$a['cat']  = $cat;
				$words     = count( preg_split( '/\s+/u', trim( wp_strip_all_tags( $a['body'] ) ) ) );
				$a['mins'] = max( 2, (int) round( $words / 180 ) );
				$by_cat[ $cat ][] = $a;
			}
		}
	}
	// Новые сверху; статьи разделов чередуются.
	$list = array();
	$max  = max( array_map( 'count', $by_cat ) );
	for ( $i = 0; $i < $max; $i++ ) {
		foreach ( array( 'sayty', 'it' ) as $cat ) {
			if ( isset( $by_cat[ $cat ][ $i ] ) ) {
				$list[ $by_cat[ $cat ][ $i ]['slug'] ] = $by_cat[ $cat ][ $i ];
			}
		}
	}
	uasort( $list, function ( $x, $y ) {
		return strcmp( $y['date'], $x['date'] );
	} );
	return $list;
}

function intech_blog_url( $cat = '' ) {
	return home_url( '/blog/' . ( $cat ? $cat . '/' : '' ) );
}

function intech_article_url( $slug ) {
	return home_url( '/blog/' . $slug . '/' );
}

/** Статьи, которые ведут на страницу услуги. */
function intech_articles_for_service( $service, $limit = 3 ) {
	$out = array();
	foreach ( intech_articles() as $a ) {
		if ( $service === $a['service'] ) {
			$out[] = $a;
		}
	}
	return array_slice( $out, 0, $limit );
}

/** Текущая статья, раздел или пустое значение. */
function intech_current_article() {
	$slug = get_query_var( 'intech_article' );
	$all  = intech_articles();
	return ( $slug && isset( $all[ $slug ] ) ) ? $all[ $slug ] : null;
}

function intech_is_blog() {
	return '' !== (string) get_query_var( 'intech_blog' ) || null !== intech_current_article();
}

function intech_blog_cat() {
	$c = (string) get_query_var( 'intech_blog' );
	return ( $c && isset( intech_blog_cats()[ $c ] ) ) ? $c : '';
}

/** Дата по-русски. */
function intech_ru_date( $ymd ) {
	$m = array( '', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря' );
	$t = strtotime( $ymd );
	return (int) gmdate( 'j', $t ) . ' ' . $m[ (int) gmdate( 'n', $t ) ] . ' ' . gmdate( 'Y', $t );
}

/** Карточка статьи для списков. */
function intech_article_card( $a ) {
	$cats = intech_blog_cats();
	ob_start();
	?>
	<a class="acard glass spot reveal" href="<?php echo esc_url( intech_article_url( $a['slug'] ) ); ?>">
		<span class="acard__meta"><span class="acard__cat"><?php echo esc_html( $cats[ $a['cat'] ][0] ); ?></span><span><?php echo esc_html( $a['mins'] ); ?> мин чтения</span></span>
		<span class="acard__title"><?php echo esc_html( $a['title'] ); ?></span>
		<span class="acard__text"><?php echo esc_html( $a['desc'] ); ?></span>
		<span class="link-accent">Читать <span aria-hidden="true">→</span></span>
	</a>
	<?php
	return ob_get_clean();
}

/* ---------- Адреса ---------- */
add_action( 'init', function () {
	$cats  = implode( '|', array_keys( intech_blog_cats() ) );
	$slugs = implode( '|', array_map( 'preg_quote', array_keys( intech_articles() ) ) );
	add_rewrite_rule( '^blog/?$', 'index.php?intech_blog=all', 'top' );
	add_rewrite_rule( '^blog/(' . $cats . ')/?$', 'index.php?intech_blog=$matches[1]', 'top' );
	if ( $slugs ) {
		add_rewrite_rule( '^blog/(' . $slugs . ')/?$', 'index.php?intech_article=$matches[1]', 'top' );
	}
	$ver = md5( $cats . '|' . $slugs );
	if ( get_option( 'intech_blog_rw' ) !== $ver ) {
		flush_rewrite_rules( false );
		update_option( 'intech_blog_rw', $ver );
	}
} );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'intech_blog';
	$vars[] = 'intech_article';
	return $vars;
} );

add_action( 'parse_query', function ( $q ) {
	if ( $q->is_main_query() && ( $q->get( 'intech_blog' ) || $q->get( 'intech_article' ) ) ) {
		$q->is_home = false;
		$q->is_404  = false;
	}
} );

add_filter( 'redirect_canonical', function ( $redirect ) {
	return ( get_query_var( 'intech_blog' ) || get_query_var( 'intech_article' ) ) ? false : $redirect;
} );

add_filter( 'template_include', function ( $template ) {
	if ( intech_current_article() ) {
		status_header( 200 );
		return locate_template( 'template-article.php' ) ?: $template;
	}
	if ( get_query_var( 'intech_blog' ) ) {
		status_header( 200 );
		return locate_template( 'template-blog.php' ) ?: $template;
	}
	return $template;
} );

/* ---------- SEO ---------- */
add_filter( 'pre_get_document_title', function ( $title ) {
	$a = intech_current_article();
	if ( $a ) {
		return $a['title'] . ' — блог ИНТЕХ';
	}
	if ( get_query_var( 'intech_blog' ) ) {
		$c = intech_blog_cat();
		return $c ? intech_blog_cats()[ $c ][0] . ' — статьи блога ИНТЕХ' : 'Блог ИНТЕХ — полезное о сайтах, продвижении и IT для бизнеса';
	}
	return $title;
}, 20 );

add_action( 'wp_head', function () {
	$a = intech_current_article();
	if ( $a ) {
		$url = intech_article_url( $a['slug'] );
		echo '<meta name="description" content="' . esc_attr( $a['desc'] ) . "\">\n";
		echo '<link rel="canonical" href="' . esc_url( $url ) . "\">\n";
		echo '<meta property="og:title" content="' . esc_attr( $a['title'] ) . "\">\n";
		echo '<meta property="og:description" content="' . esc_attr( $a['desc'] ) . "\">\n";
		echo '<meta property="og:url" content="' . esc_url( $url ) . "\">\n";
		echo '<meta property="og:type" content="article">' . "\n";
		$graph = array(
			array(
				'@type'            => 'Article',
				'headline'         => $a['title'],
				'description'      => $a['desc'],
				'datePublished'    => $a['date'],
				'dateModified'     => $a['date'],
				'mainEntityOfPage' => $url,
				'inLanguage'       => 'ru',
				'author'           => array( '@type' => 'Organization', 'name' => 'ИНТЕХ', 'url' => home_url( '/' ) ),
				'publisher'        => array( '@type' => 'Organization', 'name' => 'ИНТЕХ', 'url' => home_url( '/' ) ),
			),
			array(
				'@type'           => 'BreadcrumbList',
				'itemListElement' => array(
					array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => home_url( '/' ) ),
					array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Блог', 'item' => intech_blog_url() ),
					array( '@type' => 'ListItem', 'position' => 3, 'name' => $a['title'], 'item' => $url ),
				),
			),
		);
		if ( ! empty( $a['faq'] ) ) {
			$faq = array();
			foreach ( $a['faq'] as $q ) {
				$faq[] = array( '@type' => 'Question', 'name' => $q[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $q[1] ) );
			}
			$graph[] = array( '@type' => 'FAQPage', 'mainEntity' => $faq );
		}
		echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . "</script>\n";
		return;
	}
	if ( get_query_var( 'intech_blog' ) ) {
		$c    = intech_blog_cat();
		$url  = intech_blog_url( $c );
		$desc = $c ? intech_blog_cats()[ $c ][1] : 'Полезные статьи ИНТЕХ для руководителей и владельцев бизнеса: как сделать сайт, продвигать компанию в Яндексе, наладить сеть, серверы и резервное копирование.';
		echo '<meta name="description" content="' . esc_attr( $desc ) . "\">\n";
		echo '<link rel="canonical" href="' . esc_url( $url ) . "\">\n";
	}
}, 1 );

/* ---------- Карта сайта ---------- */
add_action( 'init', function () {
	if ( ! function_exists( 'wp_register_sitemap_provider' ) || ! class_exists( 'WP_Sitemaps_Provider' ) ) {
		return;
	}
	if ( ! class_exists( 'Intech_Blog_Sitemap' ) ) {
		class Intech_Blog_Sitemap extends WP_Sitemaps_Provider {
			public function __construct() {
				$this->name        = 'blog';
				$this->object_type = 'blog';
			}
			public function get_url_list( $page_num, $object_subtype = '' ) {
				$list = array( array( 'loc' => intech_blog_url() ) );
				foreach ( array_keys( intech_blog_cats() ) as $c ) {
					$list[] = array( 'loc' => intech_blog_url( $c ) );
				}
				foreach ( intech_articles() as $a ) {
					$list[] = array( 'loc' => intech_article_url( $a['slug'] ), 'lastmod' => $a['date'] . 'T10:00:00+02:00' );
				}
				return $list;
			}
			public function get_max_num_pages( $object_subtype = '' ) {
				return 1;
			}
		}
	}
	wp_register_sitemap_provider( 'blog', new Intech_Blog_Sitemap() );
}, 21 );
