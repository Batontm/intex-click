<?php
/**
 * Страница «О компании».
 */
$c    = intech_company();
$home = home_url( '/' );
$dirs = array(
	array( 'web', 'Разработка', 'Сайты и программные продукты — на заказ или на основе готовых решений. Адаптивные, с удобной админкой, готовые к продвижению.', array( array( 'Создание сайтов', 'sozdanie-saytov' ) ) ),
	array( 'seo', 'Продвижение', 'Поисковое продвижение в Яндексе и Google, карточки на Картах и аналитика: чтобы клиенты находили компанию и обращались.', array( array( 'SEO-продвижение', 'prodvizhenie-saytov' ) ) ),
	array( 'srv', 'IT-инфраструктура', 'Компьютеры, сеть и серверы в офисе: подбираем, устанавливаем, настраиваем и сопровождаем по договору.', array( array( 'IT-обслуживание', 'it-autsorsing' ), array( 'Серверы и бэкапы', 'servery' ), array( 'Сети и Wi-Fi', 'seti' ) ) ),
);
$principles = array(
	array( 'Одна команда', 'Сайт, программы и IT-инфраструктура у одного подрядчика — не нужно координировать нескольких исполнителей.' ),
	array( 'Прозрачно', 'Объём, стоимость и сроки согласуем до начала работ и фиксируем в документе.' ),
	array( 'По договору', 'Работаем с организациями официально: договор и закрывающие документы.' ),
	array( 'Рядом', 'Находимся в Калининграде, выезжаем по городу и области, остаёмся на связи после сдачи работ.' ),
);
$steps = array(
	array( 'Обсуждаем задачу', 'Уточняем, что нужно сделать и какой результат требуется.' ),
	array( 'Согласовываем условия', 'Определяем объём работ, стоимость и сроки.' ),
	array( 'Выполняем работы', 'Реализуем согласованные задачи и проверяем результат.' ),
	array( 'Передаём результат', 'Передаём материалы и доступы, объясняем дальнейшие действия.' ),
);
get_header();
?>

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
				<span aria-current="page">О компании</span>
			</nav>
			<span class="chip"><span class="pulse"></span>IT-компания из Калининграда</span>
			<h1 class="lhero__title">ИНТЕХ — ваш технологический партнёр</h1>
			<p class="hero__lead">Мы соединяем разработку и «железо»: создаём сайты и программы, помогаем компаниям получать клиентов из интернета и сопровождаем серверы, сеть и рабочие места, на которых держится работа офиса. Один подрядчик — одна ответственность.</p>
			<div class="hero__actions">
				<a class="btn btn--accent" href="#contacts">Обсудить задачу <span class="arr" aria-hidden="true">→</span></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( intech_page_url( 'kontakty' ) ); ?>">Контакты</a>
			</div>
		</div>
		<div class="lhero__art glass" aria-hidden="true">
			<?php echo intech_art( 'soft' ); // phpcs:ignore WordPress.Security.EscapeOutput -- SVG из файлов темы ?>
		</div>
	</div>
</section>

<section class="section container">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Направления</span>
			<h2 class="h2">Чем мы занимаемся</h2>
		</div>
	</div>
	<div class="dirs">
		<?php foreach ( $dirs as $d ) : ?>
			<div class="dir glass spot reveal">
				<span class="svc__icon"><?php echo intech_icon( $d[0] ); // phpcs:ignore ?></span>
				<h3 class="dir__title"><?php echo esc_html( $d[1] ); ?></h3>
				<p class="dir__text"><?php echo esc_html( $d[2] ); ?></p>
				<div class="dir__links">
					<?php foreach ( $d[3] as $lnk ) : ?>
						<a class="link-accent" href="<?php echo esc_url( intech_landing_url( $lnk[1] ) ); ?>"><?php echo esc_html( $lnk[0] ); ?> <span aria-hidden="true">→</span></a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<section class="section container">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Принципы</span>
			<h2 class="h2">Как мы работаем с клиентами</h2>
		</div>
	</div>
	<div class="principles">
		<?php foreach ( $principles as $p ) : ?>
			<div class="principle glass reveal">
				<div class="stat__num stat__num--word"><?php echo esc_html( $p[0] ); ?><span>.</span></div>
				<p class="principle__text"><?php echo esc_html( $p[1] ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<section class="section container">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Процесс</span>
			<h2 class="h2">Этапы работы</h2>
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

<section class="section container">
	<div class="reqs glass reveal">
		<div>
			<span class="eyebrow">Реквизиты</span>
			<h2 class="h2">Работаем официально</h2>
		</div>
		<dl class="reqs__list">
			<div><dt>Организация</dt><dd><?php echo esc_html( $c['full'] ); ?></dd></div>
			<div><dt>ИНН</dt><dd><?php echo esc_html( $c['inn'] ); ?></dd></div>
			<div><dt>ОГРН</dt><dd><?php echo esc_html( $c['ogrn'] ); ?></dd></div>
			<div><dt>Город</dt><dd><?php echo esc_html( $c['city'] ); ?></dd></div>
		</dl>
	</div>
</section>

<?php get_template_part( 'parts/contact' ); ?>

<?php
get_footer();
