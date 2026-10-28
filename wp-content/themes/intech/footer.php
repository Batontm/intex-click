<?php
/**
 * Подвал сайта.
 */
$home  = home_url( '/' );
$phone = intech_opt( 'intech_phone', '+7 (931) 605-44-84' );
$socials = intech_messengers();
$company = intech_company();
$address = trim( intech_opt( 'intech_address', '' ) );
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__cols">
		<div class="site-footer__brand">
			<a class="logo logo--footer" href="<?php echo esc_url( $home ); ?>" aria-label="ИНТЕХ — на главную"><?php echo intech_logo_svg( 'full', 'logo__svg' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			<span>Сайты, программы и IT-сопровождение для бизнеса.</span>
			<div class="socials socials--full">
				<?php echo intech_phone_link( 'socials__pill' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php echo intech_email_link( 'socials__pill' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php foreach ( $socials as $s ) : ?>
					<a class="socials__pill" href="<?php echo esc_url( $s[1] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $s[0] ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<nav class="site-footer__col" aria-label="Услуги">
			<strong>Услуги</strong>
			<a href="<?php echo esc_url( intech_landing_url( 'sozdanie-saytov' ) ); ?>">Создание сайтов</a>
			<a href="<?php echo esc_url( $home . '#services' ); ?>">Программные продукты</a>
			<a href="<?php echo esc_url( intech_landing_url( 'prodvizhenie-saytov' ) ); ?>">SEO-продвижение</a>
			<a href="<?php echo esc_url( $home . '#services' ); ?>">Контекстная реклама</a>
			<a href="<?php echo esc_url( intech_landing_url( 'it-autsorsing' ) ); ?>">IT-обслуживание</a>
			<a href="<?php echo esc_url( intech_landing_url( 'servery' ) ); ?>">Серверы и бэкапы</a>
			<a href="<?php echo esc_url( intech_landing_url( 'seti' ) ); ?>">Сети и Wi-Fi</a>
		</nav>
		<nav class="site-footer__col" aria-label="Компания">
			<strong>Компания</strong>
			<a href="<?php echo esc_url( intech_page_url( 'o-kompanii' ) ); ?>">О компании</a>
			<a href="<?php echo esc_url( intech_blog_url() ); ?>">Статьи</a>
			<a href="<?php echo esc_url( intech_page_url( 'kontakty' ) ); ?>">Контакты</a>
			<a href="<?php echo esc_url( intech_privacy_url() ); ?>">Политика конфиденциальности</a>
		</nav>
		<div class="site-footer__col">
			<strong>Контакты</strong>
			<?php echo intech_phone_link(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo intech_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<a href="<?php echo esc_url( intech_page_url( 'kontakty' ) . '#contacts' ); ?>">Написать нам</a>
			<?php if ( $address && 'Город, адрес офиса' !== $address ) : ?>
				<span><?php echo esc_html( $address ); ?></span>
			<?php endif; ?>
		</div>
	</div>
	<div class="container site-footer__bottom">
		<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $company['name'] ); ?> · ИНН <?php echo esc_html( $company['inn'] ); ?> · ОГРН <?php echo esc_html( $company['ogrn'] ); ?></span>
		<a href="<?php echo esc_url( intech_privacy_url() ); ?>">Политика конфиденциальности</a>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
