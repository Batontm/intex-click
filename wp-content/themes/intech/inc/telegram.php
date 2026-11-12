<?php
/**
 * Уведомления о заявках в Telegram.
 *
 * Настройка — в админке: Заявки → Telegram. Там вставляется токен бота (от @BotFather)
 * и привязываются получатели: человек пишет боту /start, в админке нажимается «Привязать».
 *
 * По умолчанию в сообщении нет персональных данных — только «пришла заявка» и ссылка
 * на неё в админке: Telegram — иностранный сервис, и отправка туда имени и телефона
 * клиента считается трансграничной передачей. Имя и телефон можно включить галочкой.
 *
 * Токен можно задать и константой в wp-config.php: define( 'INTECH_TG_TOKEN', '…' );
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Токен бота: константа из wp-config.php или настройка из админки. */
function intech_tg_token() {
	if ( defined( 'INTECH_TG_TOKEN' ) && INTECH_TG_TOKEN ) {
		return (string) INTECH_TG_TOKEN;
	}
	return (string) get_option( 'intech_tg_token', '' );
}

/** Получатели: массив id чата => имя. */
function intech_tg_chats() {
	$chats = get_option( 'intech_tg_chats', array() );
	return is_array( $chats ) ? $chats : array();
}

/** Вызов метода Telegram Bot API. Возвращает массив result или WP_Error. */
function intech_tg_api( $method, $args = array(), $token = null ) {
	$token = null === $token ? intech_tg_token() : $token;
	if ( ! preg_match( '/^\d{5,}:[\w-]{30,}$/', $token ) ) {
		return new WP_Error( 'intech_tg_token', 'Токен бота не задан или записан с ошибкой.' );
	}
	$res = wp_remote_post( 'https://api.telegram.org/bot' . $token . '/' . $method, array(
		'timeout' => 8,
		'body'    => $args,
	) );
	if ( is_wp_error( $res ) ) {
		return new WP_Error( 'intech_tg_net', 'Нет связи с Telegram: ' . $res->get_error_message() );
	}
	$data = json_decode( wp_remote_retrieve_body( $res ), true );
	if ( empty( $data['ok'] ) ) {
		return new WP_Error( 'intech_tg_api', 'Telegram ответил: ' . ( isset( $data['description'] ) ? $data['description'] : 'ошибка ' . wp_remote_retrieve_response_code( $res ) ) );
	}
	return $data['result'];
}

/**
 * Отправить текст всем получателям.
 *
 * @return int Скольким получателям сообщение доставлено.
 */
function intech_tg_send( $text ) {
	$ok = 0;
	foreach ( array_keys( intech_tg_chats() ) as $chat_id ) {
		$r = intech_tg_api( 'sendMessage', array(
			'chat_id'                  => $chat_id,
			'text'                     => $text,
			'disable_web_page_preview' => 'true',
		) );
		if ( is_wp_error( $r ) ) {
			error_log( 'intech telegram failed: ' . $r->get_error_message() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
		} else {
			$ok++;
		}
	}
	return $ok;
}

/**
 * Уведомление о новой заявке.
 *
 * @return string Строка для записи в заявку: что произошло с уведомлением ('' — Telegram не настроен).
 */
function intech_tg_notify_lead( $lead_id, $name, $phone, $message ) {
	if ( ! intech_tg_token() || ! intech_tg_chats() ) {
		return '';
	}
	$text = "Новая заявка с сайта intex.click\n" . wp_date( 'd.m.Y H:i' );
	if ( get_option( 'intech_tg_details' ) ) {
		$text .= "\n\nИмя: {$name}\nТелефон: {$phone}";
		if ( $message ) {
			$text .= "\n\n" . wp_html_excerpt( $message, 600, '…' );
		}
	}
	if ( $lead_id ) {
		$text .= "\n\nОткрыть: " . admin_url( 'post.php?post=' . (int) $lead_id . '&action=edit' );
	}
	$n = intech_tg_send( $text );
	return $n ? 'Уведомление в Telegram отправлено (' . $n . ')' : 'Уведомление в Telegram НЕ отправлено';
}

/* ---------- Страница настройки: Заявки → Telegram ---------- */
add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=intech_lead', 'Уведомления в Telegram', 'Telegram', 'manage_options', 'intech-telegram', 'intech_tg_page' );
} );

function intech_tg_page() {
	$token  = intech_tg_token();
	$chats  = intech_tg_chats();
	$fixed  = defined( 'INTECH_TG_TOKEN' ) && INTECH_TG_TOKEN;
	$notice = get_transient( 'intech_tg_notice_' . get_current_user_id() );
	delete_transient( 'intech_tg_notice_' . get_current_user_id() );
	$bot = $token ? intech_tg_api( 'getMe' ) : null;
	$act = admin_url( 'admin-post.php' );
	?>
	<div class="wrap">
		<h1>Уведомления о заявках в Telegram</h1>
		<?php if ( $notice ) : ?>
			<div class="notice notice-<?php echo esc_attr( $notice[0] ); ?>"><p><?php echo esc_html( $notice[1] ); ?></p></div>
		<?php endif; ?>

		<h2>1. Бот</h2>
		<?php if ( $bot && ! is_wp_error( $bot ) ) : ?>
			<p>Подключён бот <strong>@<?php echo esc_html( $bot['username'] ); ?></strong>.</p>
		<?php elseif ( is_wp_error( $bot ) ) : ?>
			<p style="color:#b32d2e"><?php echo esc_html( $bot->get_error_message() ); ?></p>
		<?php else : ?>
			<p>В Telegram откройте <strong>@BotFather</strong>, отправьте ему <code>/newbot</code>, придумайте имя. В ответ придёт токен — длинная строка вида <code>123456789:AA…</code>. Вставьте её сюда.</p>
		<?php endif; ?>
		<?php if ( $fixed ) : ?>
			<p>Токен задан в файле wp-config.php.</p>
		<?php else : ?>
			<form method="post" action="<?php echo esc_url( $act ); ?>">
				<input type="hidden" name="action" value="intech_tg">
				<input type="hidden" name="do" value="token">
				<?php wp_nonce_field( 'intech_tg' ); ?>
				<input type="password" name="token" class="regular-text" autocomplete="off" placeholder="<?php echo $token ? 'токен сохранён — вставьте новый, чтобы заменить' : 'токен от @BotFather'; ?>">
				<?php submit_button( $token ? 'Заменить токен' : 'Сохранить токен', 'primary', 'submit', false ); ?>
			</form>
		<?php endif; ?>

		<h2>2. Кому присылать</h2>
		<?php if ( $chats ) : ?>
			<ul>
				<?php foreach ( $chats as $id => $name ) : ?>
					<li>
						<form method="post" action="<?php echo esc_url( $act ); ?>" style="display:inline">
							<input type="hidden" name="action" value="intech_tg">
							<input type="hidden" name="do" value="remove">
							<input type="hidden" name="chat" value="<?php echo esc_attr( $id ); ?>">
							<?php wp_nonce_field( 'intech_tg' ); ?>
							<strong><?php echo esc_html( $name ); ?></strong>
							<button class="button-link" style="color:#b32d2e">убрать</button>
						</form>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p>Получателей пока нет.</p>
		<?php endif; ?>
		<?php if ( $bot && ! is_wp_error( $bot ) ) : ?>
			<p>Чтобы добавить получателя: он открывает бота <strong>@<?php echo esc_html( $bot['username'] ); ?></strong> в Telegram и нажимает «Запустить» (или пишет <code>/start</code>). После этого нажмите кнопку.</p>
			<form method="post" action="<?php echo esc_url( $act ); ?>">
				<input type="hidden" name="action" value="intech_tg">
				<input type="hidden" name="do" value="bind">
				<?php wp_nonce_field( 'intech_tg' ); ?>
				<?php submit_button( 'Привязать написавших боту', 'secondary', 'submit', false ); ?>
			</form>
		<?php endif; ?>

		<h2>3. Что присылать</h2>
		<form method="post" action="<?php echo esc_url( $act ); ?>">
			<input type="hidden" name="action" value="intech_tg">
			<input type="hidden" name="do" value="details">
			<?php wp_nonce_field( 'intech_tg' ); ?>
			<label><input type="checkbox" name="details" value="1" <?php checked( (bool) get_option( 'intech_tg_details' ) ); ?>> Присылать имя, телефон и текст заявки прямо в сообщении</label>
			<p class="description">Без галочки приходит только «Новая заявка» и ссылка на неё в админке. Telegram — иностранный сервис: если включить галочку, данные клиента уходят за рубеж, и это нужно отразить в политике обработки персональных данных и в уведомлении Роскомнадзора.</p>
			<?php submit_button( 'Сохранить', 'secondary', 'submit', false ); ?>
		</form>

		<?php if ( $chats && $token ) : ?>
			<h2>4. Проверка</h2>
			<form method="post" action="<?php echo esc_url( $act ); ?>">
				<input type="hidden" name="action" value="intech_tg">
				<input type="hidden" name="do" value="test">
				<?php wp_nonce_field( 'intech_tg' ); ?>
				<?php submit_button( 'Отправить тестовое сообщение', 'secondary', 'submit', false ); ?>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

add_action( 'admin_post_intech_tg', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Недостаточно прав.' );
	}
	check_admin_referer( 'intech_tg' );
	$do     = isset( $_POST['do'] ) ? sanitize_key( wp_unslash( $_POST['do'] ) ) : '';
	$notice = array( 'success', 'Сохранено.' );

	if ( 'token' === $do ) {
		$token = isset( $_POST['token'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['token'] ) ) ) : '';
		$me    = intech_tg_api( 'getMe', array(), $token );
		if ( is_wp_error( $me ) ) {
			$notice = array( 'error', 'Токен не сохранён. ' . $me->get_error_message() );
		} else {
			update_option( 'intech_tg_token', $token, false );
			$notice = array( 'success', 'Бот @' . $me['username'] . ' подключён.' );
		}
	} elseif ( 'bind' === $do ) {
		$updates = intech_tg_api( 'getUpdates', array( 'timeout' => 0 ) );
		if ( is_wp_error( $updates ) ) {
			$notice = array( 'error', $updates->get_error_message() );
		} else {
			$chats = intech_tg_chats();
			$added = array();
			foreach ( $updates as $u ) {
				$chat = isset( $u['message']['chat'] ) ? $u['message']['chat'] : null;
				if ( ! $chat || isset( $chats[ $chat['id'] ] ) ) {
					continue;
				}
				$name = trim( ( isset( $chat['first_name'] ) ? $chat['first_name'] : '' ) . ' ' . ( isset( $chat['last_name'] ) ? $chat['last_name'] : '' ) );
				if ( ! $name ) {
					$name = isset( $chat['title'] ) ? $chat['title'] : 'Чат ' . $chat['id'];
				}
				$chats[ $chat['id'] ] = sanitize_text_field( $name );
				$added[]              = $name;
			}
			update_option( 'intech_tg_chats', $chats, false );
			$notice = $added
				? array( 'success', 'Добавлены получатели: ' . implode( ', ', $added ) . '.' )
				: array( 'warning', 'Новых сообщений боту не найдено. Откройте бота в Telegram, нажмите «Запустить» и повторите.' );
		}
	} elseif ( 'remove' === $do ) {
		$chats = intech_tg_chats();
		$id    = isset( $_POST['chat'] ) ? sanitize_text_field( wp_unslash( $_POST['chat'] ) ) : '';
		unset( $chats[ $id ] );
		update_option( 'intech_tg_chats', $chats, false );
		$notice = array( 'success', 'Получатель убран.' );
	} elseif ( 'details' === $do ) {
		update_option( 'intech_tg_details', empty( $_POST['details'] ) ? 0 : 1, false );
	} elseif ( 'test' === $do ) {
		$n      = intech_tg_send( "Проверка: уведомления о заявках с сайта intex.click работают.\n" . wp_date( 'd.m.Y H:i' ) );
		$notice = $n ? array( 'success', 'Тестовое сообщение отправлено.' ) : array( 'error', 'Сообщение не отправлено. Проверьте, что получатель не заблокировал бота.' );
	}

	set_transient( 'intech_tg_notice_' . get_current_user_id(), $notice, 60 );
	wp_safe_redirect( admin_url( 'edit.php?post_type=intech_lead&page=intech-telegram' ) );
	exit;
} );
