<?php
/**
 * Общие SEO-помощники: длина title и description, картинка для соцсетей.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Поиск обрезает title примерно после 65 знаков. */
const INTECH_TITLE_MAX = 65;
/** …а description — примерно после 170. */
const INTECH_DESC_MAX = 170;

/** Короткие title для статей, у которых заголовок сам длиннее лимита (в H1 остаётся полный). */
function intech_short_titles() {
	return array(
		'sayt-na-wordpress-plyusy-i-minusy'     => 'Сайт на WordPress для бизнеса: плюсы, минусы и риски',
		'domen-hosting-ssl'                     => 'Домен, хостинг и SSL: как не потерять доступ к сайту',
		'yandeks-metrika-tseli'                 => 'Яндекс Метрика для сайта: какие цели настроить первыми',
		'it-autsorsing-ili-shtatnyy-sisadmin'   => 'IT-аутсорсинг или штатный сисадмин: что выбрать компании',
		'vpn-dlya-filialov-i-sotrudnikov'       => 'Корпоративный VPN для филиалов и удалённых сотрудников',
		'abonentskoe-obsluzhivanie-kompyuterov' => 'Абонентское обслуживание компьютеров: что входит в услугу',
	);
}

/**
 * Title с самым длинным из суффиксов, который ещё помещается в лимит.
 *
 * @param string   $title    Основная часть.
 * @param string[] $suffixes Варианты окончания, от длинного к короткому.
 */
function intech_seo_title( $title, $suffixes = array( ' — блог ИНТЕХ', ' — ИНТЕХ' ) ) {
	foreach ( $suffixes as $s ) {
		if ( mb_strlen( $title . $s ) <= INTECH_TITLE_MAX ) {
			return $title . $s;
		}
	}
	return $title;
}

/** Description не длиннее лимита: обрезается по концу предложения, иначе по слову. */
function intech_seo_desc( $desc ) {
	$desc = trim( $desc );
	if ( mb_strlen( $desc ) <= INTECH_DESC_MAX ) {
		return $desc;
	}
	$cut = mb_substr( $desc, 0, INTECH_DESC_MAX );
	$dot = max( (int) mb_strrpos( $cut, '. ' ), (int) mb_strrpos( $cut, '? ' ), (int) mb_strrpos( $cut, '! ' ) );
	if ( $dot >= 100 ) {
		return mb_substr( $cut, 0, $dot + 1 );
	}
	$space = (int) mb_strrpos( mb_substr( $cut, 0, INTECH_DESC_MAX - 1 ), ' ' );
	return rtrim( mb_substr( $cut, 0, $space ), ' ,;:—-' ) . '…';
}

/* ---------- Картинка для ссылок в соцсетях и мессенджерах (на всех страницах) ---------- */
add_action( 'wp_head', function () {
	// Страницы услуг, блога и «О компании» тема отдаёт сама: для WordPress они формально «не найдены»,
	// поэтому настоящую 404 отличаем по тому, что это не одна из страниц темы.
	$own = intech_current_landing() || intech_current_page() || intech_is_blog();
	if ( is_404() && ! $own ) {
		return;
	}
	$file = get_template_directory() . '/assets/img/og.jpg';
	if ( ! file_exists( $file ) ) {
		return;
	}
	$img = get_template_directory_uri() . '/assets/img/og.jpg?v=' . filemtime( $file );
	echo '<meta property="og:image" content="' . esc_url( $img ) . "\">\n";
	echo '<meta property="og:image:width" content="1200">' . "\n";
	echo '<meta property="og:image:height" content="630">' . "\n";
	echo '<meta property="og:image:alt" content="ИНТЕХ — сайты, SEO и IT для бизнеса в Калининграде">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="theme-color" content="#04060C">' . "\n";
}, 3 );
