<?php
/**
 * Страница статьи.
 */
$a    = intech_current_article();
$cats = intech_blog_cats();
$home = home_url( '/' );
$land = intech_landings();
$svc  = isset( $land[ $a['service'] ] ) ? $land[ $a['service'] ] : null;

// Оглавление по заголовкам h2: добавляем им якоря.
$toc  = array();
$n    = 0;
$body = preg_replace_callback( '#<h2>(.*?)</h2>#u', function ( $m ) use ( &$toc, &$n ) {
	$n++;
	$id    = 'part-' . $n;
	$toc[] = array( $id, wp_strip_all_tags( $m[1] ) );
	return '<h2 id="' . $id . '">' . $m[1] . '</h2>';
}, $a['body'] );

// Похожие статьи: сначала из того же раздела и с той же услугой.
$related = array();
foreach ( intech_articles() as $r ) {
	if ( $r['slug'] !== $a['slug'] && $r['service'] === $a['service'] ) {
		$related[] = $r;
	}
}
foreach ( intech_articles() as $r ) {
	if ( count( $related ) >= 3 ) {
		break;
	}
	if ( $r['slug'] !== $a['slug'] && $r['cat'] === $a['cat'] && ! in_array( $r, $related, true ) ) {
		$related[] = $r;
	}
}
$related = array_slice( $related, 0, 3 );
get_header();
?>

<article class="section container article">
	<header class="article__head">
		<nav class="crumbs" aria-label="Хлебные крошки">
			<a href="<?php echo esc_url( $home ); ?>">Главная</a>
			<span aria-hidden="true">/</span>
			<a href="<?php echo esc_url( intech_blog_url() ); ?>">Блог</a>
			<span aria-hidden="true">/</span>
			<a href="<?php echo esc_url( intech_blog_url( $a['cat'] ) ); ?>"><?php echo esc_html( $cats[ $a['cat'] ][0] ); ?></a>
		</nav>
		<h1 class="article__title"><?php echo esc_html( $a['title'] ); ?></h1>
		<p class="article__lead"><?php echo esc_html( $a['lead'] ); ?></p>
		<div class="article__meta">
			<span><?php echo esc_html( intech_ru_date( $a['date'] ) ); ?></span>
			<span><?php echo esc_html( $a['mins'] ); ?> мин чтения</span>
			<span>Автор: команда ИНТЕХ</span>
		</div>
	</header>

	<div class="article__grid">
		<div class="article__body prose">
			<?php if ( count( $toc ) > 2 ) : ?>
				<nav class="toc glass" aria-label="Содержание">
					<strong>Содержание</strong>
					<ol>
						<?php foreach ( $toc as $t ) : ?>
							<li><a href="#<?php echo esc_attr( $t[0] ); ?>"><?php echo esc_html( $t[1] ); ?></a></li>
						<?php endforeach; ?>
					</ol>
				</nav>
			<?php endif; ?>

			<?php echo wp_kses_post( $body ); ?>

			<?php if ( ! empty( $a['faq'] ) ) : ?>
				<h2>Частые вопросы</h2>
				<div class="faq faq--one">
					<?php foreach ( $a['faq'] as $q ) : ?>
						<details class="faq__item glass">
							<summary class="faq__q"><?php echo esc_html( $q[0] ); ?><span class="faq__plus" aria-hidden="true"></span></summary>
							<p class="faq__a"><?php echo esc_html( $q[1] ); ?></p>
						</details>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<aside class="article__side">
			<?php if ( $svc ) : ?>
				<div class="side-cta glass">
					<span class="eyebrow">Поможем</span>
					<strong class="side-cta__title"><?php echo esc_html( $svc['name'] ); ?></strong>
					<p><?php echo esc_html( $svc['desc'] ); ?></p>
					<a class="btn btn--accent btn--sm" href="<?php echo esc_url( intech_landing_url( $a['service'] ) ); ?>">Подробнее об услуге</a>
					<a class="link-accent" href="#contacts">Задать вопрос <span aria-hidden="true">→</span></a>
				</div>
			<?php endif; ?>
		</aside>
	</div>
</article>

<?php if ( $related ) : ?>
<section class="section container">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Читайте также</span>
			<h2 class="h2">Похожие статьи</h2>
		</div>
		<a class="link-accent" href="<?php echo esc_url( intech_blog_url() ); ?>">Все статьи <span aria-hidden="true">→</span></a>
	</div>
	<div class="acards">
		<?php foreach ( $related as $r ) : ?>
			<?php echo intech_article_card( $r ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<?php get_template_part( 'parts/contact' ); ?>

<?php
get_footer();
