<?php
/**
 * Блок «Заявка» — общий для главной и посадочных страниц.
 */
$phone = intech_opt( 'intech_phone', '+7 (931) 605-44-84' );
?>
<!-- ===== Заявка ===== -->
<section class="section container" id="contacts">
	<div class="cta reveal">
		<span class="eyebrow">Старт проекта</span>
		<h2 class="cta__title">Готовы вывести бизнес <span class="grad">на новый уровень?</span></h2>
		<p class="lead" style="max-width:600px">Оставьте заявку — проведём бесплатную консультацию и предложим решение под вашу задачу.</p>
	</div>
	<div class="contact glass reveal">
		<div class="contact__info">
			<h3>Обсудим ваш проект</h3>
			<ul class="checks">
				<li><?php echo intech_icon( 'check' ); // phpcs:ignore ?>Бесплатная консультация и аудит</li>
				<li><?php echo intech_icon( 'check' ); // phpcs:ignore ?>Ответ в течение рабочего дня</li>
				<li><?php echo intech_icon( 'check' ); // phpcs:ignore ?>Прозрачная смета и сроки</li>
				<li><?php echo intech_icon( 'check' ); // phpcs:ignore ?>Договор и закрывающие документы</li>
			</ul>
			<div class="contact__phones">
				<?php echo intech_phone_link( 'contact__phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php echo intech_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php foreach ( intech_messengers() as $m ) : ?>
					<a href="<?php echo esc_url( $m[1] ); ?>" target="_blank" rel="noopener">Написать нам в <?php echo esc_html( $m[0] ); ?> →</a>
				<?php endforeach; ?>
			</div>
		</div>
		<form class="form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php if ( isset( $_GET['sent'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
				<p class="form__ok" role="status">Спасибо! Заявка отправлена — мы свяжемся с вами в ближайшее время.</p>
			<?php endif; ?>
			<input type="hidden" name="action" value="intech_lead">
			<?php wp_nonce_field( 'intech_lead', 'intech_nonce' ); ?>
			<label class="screen-reader-text" aria-hidden="true">Сайт <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
			<label>Имя <input type="text" name="name" placeholder="Ваше имя" required></label>
			<label>Email <input type="email" name="email" placeholder="you@company.ru"></label>
			<label class="full">Телефон <input type="tel" name="phone" placeholder="+7" required></label>
			<label class="full">Сообщение <textarea name="message" rows="4" placeholder="Расскажите о задаче: сайт, программа, продвижение, IT"></textarea></label>
			<label class="form__consent full"><input type="checkbox" name="consent" value="1" required> <span>Даю согласие на обработку персональных данных в соответствии с <a href="<?php echo esc_url( intech_privacy_url() ); ?>" target="_blank">политикой конфиденциальности</a></span></label>
			<button class="btn btn--accent" type="submit">Отправить заявку <span class="arr" aria-hidden="true">→</span></button>
		</form>
	</div>
</section>

