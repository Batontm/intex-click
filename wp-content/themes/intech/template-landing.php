<?php
/**
 * Шаблон посадочной страницы услуги. Данные — в inc/landings.php.
 */
$l = intech_current_landing();
if ( ! $l ) {
	status_header( 404 );
	get_template_part( 'index' );
	return;
}
$all   = intech_landings();
$home  = home_url( '/' );
$steps = array(
	array( 'Обсуждаем задачу', 'Уточняем, что нужно сделать и какой результат требуется.' ),
	array( 'Согласовываем условия', 'Определяем объём работ, стоимость и сроки.' ),
	array( 'Выполняем работы', 'Реализуем согласованные задачи и проверяем результат.' ),
	array( 'Передаём результат', 'Передаём материалы и доступы, объясняем дальнейшие действия.' ),
);
get_header();
?>

<!-- ===== Первый экран услуги ===== -->
<section class="lhero">
	<div class="hero__bg" aria-hidden="true">
		<div class="hero__glow hero__glow--a"></div>
		<div class="hero__glow hero__glow--b"></div>
		<div class="hero__grid"></div>
		<div class="hero__fade"></div>
	</div>
	<div class="container lhero__inner">
		<div class="lhero__text">
			<nav class="crumbs" aria-label="Хлебные крошки">
				<a href="<?php echo esc_url( $home ); ?>">Главная</a>
				<span aria-hidden="true">/</span>
				<span aria-current="page"><?php echo esc_html( $l['name'] ); ?></span>
			</nav>
			<span class="chip"><span class="pulse"></span><?php echo esc_html( $l['eyebrow'] ); ?></span>
			<h1 class="lhero__title"><?php echo wp_kses( $l['h1'], array() ); ?></h1>
			<p class="hero__lead"><?php echo esc_html( $l['lead'] ); ?></p>
			<div class="hero__actions">
				<a class="btn btn--accent" href="#contacts">Обсудить задачу <span class="arr" aria-hidden="true">→</span></a>
				<a class="btn btn--ghost" href="#services">Что входит</a>
			</div>
			<div class="hero__stats">
				<?php foreach ( $l['badges'] as $b ) : ?>
					<div class="hero__stat"><b><?php echo esc_html( $b[0] ); ?></b><span><?php echo esc_html( $b[1] ); ?></span></div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="lhero__art glass" aria-hidden="true">
			<?php echo intech_art( $l['art'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- SVG из файлов темы ?>
		</div>
	</div>
</section>

<!-- ===== Услуги ===== -->
<section class="section container" id="services">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow"><?php echo esc_html( $l['eyebrow'] ); ?></span>
			<h2 class="h2"><?php echo esc_html( $l['services_title'] ); ?></h2>
		</div>
	</div>
	<div class="bento">
		<?php foreach ( $l['services'] as $s ) : ?>
			<div class="svc glass spot reveal" tabindex="0">
				<span class="svc__icon"><?php echo intech_icon( $s[0] ); // phpcs:ignore ?></span>
				<h3 class="svc__title"><?php echo esc_html( $s[1] ); ?></h3>
				<span class="svc__text"><?php echo esc_html( $s[2] ); ?></span>
				<span class="xp"><span class="xp__in">
					<span class="xp__list">
						<?php foreach ( $s[3] as $m ) : ?><span class="xp__item"><?php echo esc_html( $m ); ?></span><?php endforeach; ?>
					</span>
				</span></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- ===== Дополнительный блок ===== -->
<?php if ( ! empty( $l['extra'] ) ) : ?>
<section class="section container">
	<div class="infra glass reveal">
		<div class="infra__intro">
			<span class="eyebrow">Подробнее</span>
			<h2 class="h2"><?php echo esc_html( $l['extra']['title'] ); ?></h2>
			<p class="lead"><?php echo esc_html( $l['extra']['text'] ); ?></p>
			<a class="btn btn--accent" href="#contacts">Получить консультацию <span class="arr" aria-hidden="true">→</span></a>
		</div>
		<ul class="infra__list lextra__list">
			<?php foreach ( $l['extra']['bullets'] as $b ) : ?><li><?php echo esc_html( $b ); ?></li><?php endforeach; ?>
		</ul>
	</div>
</section>
<?php endif; ?>

<!-- ===== Форматы работы и стоимость ===== -->
<section class="section container" id="price">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Стоимость</span>
			<h2 class="h2">Форматы работы</h2>
		</div>
		<p class="lead" style="max-width:520px"><?php echo esc_html( $l['formats_note'] ); ?></p>
	</div>
	<div class="formats">
		<?php foreach ( $l['formats'] as $i => $f ) : ?>
			<div class="fmt glass spot reveal<?php echo 1 === $i ? ' fmt--accent' : ''; ?>">
				<h3 class="fmt__title"><?php echo esc_html( $f[0] ); ?></h3>
				<p class="fmt__text"><?php echo esc_html( $f[1] ); ?></p>
				<ul class="checks">
					<?php foreach ( $f[2] as $it ) : ?><li><?php echo intech_icon( 'check' ); // phpcs:ignore ?><?php echo esc_html( $it ); ?></li><?php endforeach; ?>
				</ul>
				<span class="fmt__price">Стоимость — после обсуждения</span>
				<a class="btn <?php echo 1 === $i ? 'btn--accent' : 'btn--ghost'; ?>" href="#contacts">Рассчитать стоимость</a>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- ===== Этапы ===== -->
<section class="section container">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Процесс</span>
			<h2 class="h2">Как мы работаем</h2>
		</div>
	</div>
	<div class="timeline timeline--4">
		<?php foreach ( $steps as $i => $st ) : ?>
			<div class="tl reveal">
				<span class="tl__n"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<span class="tl__title"><?php echo esc_html( $st[0] ); ?></span>
				<span class="tl__text"><?php echo esc_html( $st[1] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- ===== Вопросы ===== -->
<section class="section container" id="faq">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Вопросы</span>
			<h2 class="h2">Частые вопросы</h2>
		</div>
	</div>
	<div class="faq">
		<?php foreach ( $l['faq'] as $q ) : ?>
			<details class="faq__item glass reveal">
				<summary class="faq__q"><?php echo esc_html( $q[0] ); ?><span class="faq__plus" aria-hidden="true"></span></summary>
				<p class="faq__a"><?php echo esc_html( $q[1] ); ?></p>
			</details>
		<?php endforeach; ?>
	</div>
</section>

<!-- ===== Статьи по теме ===== -->
<?php $l_articles = intech_articles_for_service( $l['slug'], 3 ); ?>
<?php if ( $l_articles ) : ?>
<section class="section container">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Блог</span>
			<h2 class="h2">Полезные статьи</h2>
		</div>
		<a class="link-accent" href="<?php echo esc_url( intech_blog_url() ); ?>">Все статьи <span aria-hidden="true">→</span></a>
	</div>
	<div class="acards">
		<?php foreach ( $l_articles as $la ) : ?>
			<?php echo intech_article_card( $la ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>

<!-- ===== Другие услуги ===== -->
<section class="section container">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Ещё по теме</span>
			<h2 class="h2">Другие услуги</h2>
		</div>
		<a class="link-accent" href="<?php echo esc_url( $home . '#services' ); ?>">Все услуги <span aria-hidden="true">→</span></a>
	</div>
	<div class="related">
		<?php foreach ( $l['related'] as $rs ) : ?>
			<?php if ( empty( $all[ $rs ] ) ) { continue; } ?>
			<a class="rel glass spot reveal" href="<?php echo esc_url( intech_landing_url( $rs ) ); ?>">
				<span class="rel__name"><?php echo esc_html( $all[ $rs ]['name'] ); ?></span>
				<span class="rel__text"><?php echo esc_html( $all[ $rs ]['desc'] ); ?></span>
				<span class="link-accent">Подробнее <span aria-hidden="true">→</span></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>

<?php get_template_part( 'parts/contact' ); ?>

<?php
get_footer();
