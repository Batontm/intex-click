<?php
/**
 * Страница «Контакты».
 */
$c     = intech_company();
$home  = home_url( '/' );
$phone = intech_opt( 'intech_phone', '+7 (931) 605-44-84' );
get_header();
?>

<section class="section container cpage">
	<nav class="crumbs" aria-label="Хлебные крошки">
		<a href="<?php echo esc_url( $home ); ?>">Главная</a>
		<span aria-hidden="true">/</span>
		<span aria-current="page">Контакты</span>
	</nav>
	<h1 class="lhero__title">Контакты</h1>
	<p class="hero__lead">Позвоните, напишите на почту, в Telegram или MAX либо оставьте заявку — ответим в течение рабочего дня. Работаем по Калининграду и Калининградской области.</p>

	<div class="cards4">
		<div class="ccard glass spot reveal">
			<span class="svc__icon"><?php echo intech_icon( 'support' ); // phpcs:ignore ?></span>
			<span class="ccard__label">Телефон</span>
			<span class="ccard__value"><?php echo intech_phone_link(); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		</div>
		<a class="ccard glass spot reveal" href="mailto:<?php echo esc_attr( intech_email() ); ?>">
			<span class="svc__icon"><?php echo intech_icon( 'smm' ); // phpcs:ignore ?></span>
			<span class="ccard__label">Почта</span>
			<span class="ccard__value"><?php echo esc_html( intech_email() ); ?></span>
		</a>
		<?php foreach ( intech_messengers() as $m ) : ?>
			<a class="ccard glass spot reveal" href="<?php echo esc_url( $m[1] ); ?>" target="_blank" rel="noopener">
				<span class="svc__icon"><?php echo intech_icon( 'smm' ); // phpcs:ignore ?></span>
				<span class="ccard__label"><?php echo esc_html( $m[0] ); ?></span>
				<span class="ccard__value">Написать в <?php echo esc_html( $m[0] ); ?> →</span>
			</a>
		<?php endforeach; ?>
		<a class="ccard glass spot reveal" href="#contacts">
			<span class="svc__icon"><?php echo intech_icon( 'code' ); // phpcs:ignore ?></span>
			<span class="ccard__label">Заявка</span>
			<span class="ccard__value">Заполнить форму →</span>
		</a>
		<div class="ccard glass reveal">
			<span class="svc__icon"><?php echo intech_icon( 'move' ); // phpcs:ignore ?></span>
			<span class="ccard__label">География</span>
			<span class="ccard__value">Калининград и область, выезд инженера</span>
		</div>
	</div>

	<div class="reqs glass reveal">
		<div>
			<span class="eyebrow">Реквизиты</span>
			<h2 class="h2"><?php echo esc_html( $c['name'] ); ?></h2>
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
