<?php
/**
 * Блог: список статей и разделы.
 */
$cats = intech_blog_cats();
$cur  = intech_blog_cat();
$home = home_url( '/' );
$list = array_filter( intech_articles(), function ( $a ) use ( $cur ) {
	return ! $cur || $cur === $a['cat'];
} );
get_header();
?>

<section class="section container blog-head">
	<nav class="crumbs" aria-label="Хлебные крошки">
		<a href="<?php echo esc_url( $home ); ?>">Главная</a>
		<span aria-hidden="true">/</span>
		<?php if ( $cur ) : ?>
			<a href="<?php echo esc_url( intech_blog_url() ); ?>">Блог</a>
			<span aria-hidden="true">/</span>
			<span aria-current="page"><?php echo esc_html( $cats[ $cur ][0] ); ?></span>
		<?php else : ?>
			<span aria-current="page">Блог</span>
		<?php endif; ?>
	</nav>
	<h1 class="lhero__title"><?php echo esc_html( $cur ? $cats[ $cur ][0] : 'Полезное для бизнеса' ); ?></h1>
	<p class="hero__lead"><?php echo esc_html( $cur ? $cats[ $cur ][1] : 'Простые и практичные статьи для руководителей и владельцев бизнеса: как сделать сайт и получать заявки из Яндекса, как наладить сеть, серверы и резервное копирование в офисе.' ); ?></p>
	<div class="blog-tabs" role="navigation" aria-label="Разделы блога">
		<a class="blog-tab<?php echo $cur ? '' : ' is-active'; ?>" href="<?php echo esc_url( intech_blog_url() ); ?>">Все статьи <span><?php echo count( intech_articles() ); ?></span></a>
		<?php foreach ( $cats as $slug => $c ) : ?>
			<?php $n = count( array_filter( intech_articles(), function ( $a ) use ( $slug ) { return $slug === $a['cat']; } ) ); ?>
			<a class="blog-tab<?php echo $cur === $slug ? ' is-active' : ''; ?>" href="<?php echo esc_url( intech_blog_url( $slug ) ); ?>"><?php echo esc_html( $c[0] ); ?> <span><?php echo (int) $n; ?></span></a>
		<?php endforeach; ?>
	</div>
</section>

<section class="section container blog-list-wrap">
	<div class="acards">
		<?php foreach ( $list as $a ) : ?>
			<?php echo intech_article_card( $a ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php endforeach; ?>
	</div>
</section>

<?php get_template_part( 'parts/contact' ); ?>

<?php
get_footer();
