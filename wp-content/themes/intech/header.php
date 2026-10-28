<?php
/**
 * Шапка сайта.
 */
$home = home_url( '/' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( ! intech_is_landing() && ! intech_current_page() && ! intech_is_blog() ) : ?>
	<meta name="description" content="ИНТЕХ — разработка сайтов и программных продуктов, продвижение в интернете, полное сопровождение IT-инфраструктуры.">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="page-glow" aria-hidden="true"></div>
<a class="screen-reader-text" href="#main">Перейти к содержимому</a>

<header class="site-header" id="siteHeader">
	<div class="container site-header__inner">
		<a class="logo" href="<?php echo esc_url( $home ); ?>" aria-label="ИНТЕХ — на главную">
			<?php echo intech_logo_svg( 'full', 'logo__svg' ); // phpcs:ignore WordPress.Security.EscapeOutput -- SVG темы ?>
		</a>

		<button class="nav-toggle" type="button" aria-label="Открыть меню" aria-expanded="false" aria-controls="siteNav">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
		</button>

		<?php
		$cur_landing = intech_is_landing() ? get_query_var( 'intech_landing' ) : '';
		$cur_page    = intech_current_page();
		$nav_icons   = array( 'sozdanie-saytov' => 'web', 'prodvizhenie-saytov' => 'seo', 'it-autsorsing' => 'support', 'servery' => 'srv', 'seti' => 'net' );
		$has_form    = is_front_page() || $cur_landing || intech_is_blog() || in_array( $cur_page, array( 'o-kompanii', 'kontakty' ), true );
		?>
		<nav class="nav" id="siteNav" aria-label="Основное меню">
			<div class="nav__item nav__item--drop<?php echo $cur_landing ? ' is-current' : ''; ?>">
				<button class="nav__drop-btn" type="button" aria-expanded="false" aria-controls="navServices">Услуги <svg class="nav__chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></button>
				<div class="nav__drop" id="navServices">
					<div class="nav__drop-in">
						<?php foreach ( intech_landings() as $slug => $l ) : ?>
							<a class="nav__drop-link<?php echo $slug === $cur_landing ? ' is-active' : ''; ?>" href="<?php echo esc_url( intech_landing_url( $slug ) ); ?>"<?php echo $slug === $cur_landing ? ' aria-current="page"' : ''; ?>>
								<span class="nav__drop-ico"><?php echo intech_icon( isset( $nav_icons[ $slug ] ) ? $nav_icons[ $slug ] : 'web' ); // phpcs:ignore ?></span>
								<span class="nav__drop-name"><?php echo esc_html( $l['name'] ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<a class="nav__link<?php echo intech_is_blog() ? ' is-active' : ''; ?>" href="<?php echo esc_url( intech_blog_url() ); ?>">Статьи</a>
			<a class="nav__link<?php echo 'o-kompanii' === $cur_page ? ' is-active' : ''; ?>" href="<?php echo esc_url( intech_page_url( 'o-kompanii' ) ); ?>"<?php echo 'o-kompanii' === $cur_page ? ' aria-current="page"' : ''; ?>>О компании</a>
			<a class="nav__link<?php echo 'kontakty' === $cur_page ? ' is-active' : ''; ?>" href="<?php echo esc_url( intech_page_url( 'kontakty' ) ); ?>"<?php echo 'kontakty' === $cur_page ? ' aria-current="page"' : ''; ?>>Контакты</a>
			<?php echo intech_phone_link( 'nav__phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<a class="btn btn--accent btn--sm" href="<?php echo esc_url( $has_form ? '#contacts' : intech_page_url( 'kontakty' ) . '#contacts' ); ?>">Связаться</a>
		</nav>
	</div>
</header>

<main id="main">
