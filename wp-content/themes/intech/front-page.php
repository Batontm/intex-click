<?php
/**
 * Главная страница — неоновый IT-стиль.
 */
get_header();

$video = intech_hero_video_url();
$phone = intech_opt( 'intech_phone', '+7 (931) 605-44-84' );
$wa    = intech_opt( 'intech_wa', 'https://wa.me/79316054484' );

$ticker = array( 'Разработка сайтов', 'Программы на заказ', 'Готовые решения', 'SEO-продвижение', 'Яндекс Директ', 'Серверы', 'Сети и Wi-Fi', 'Резервное копирование', 'Видеонаблюдение', 'IT-сопровождение' );

$services = array(
	array( 'web', 'Разработка сайтов', 'Корпоративные сайты, лендинги и интернет-магазины с современным дизайном и удобной админкой. Адаптивные и готовые к продвижению.', array( 'WordPress', 'Лендинги', 'Магазины', 'Адаптив' ), 'half' ),
	array( 'code', 'Программные продукты', 'Разрабатываем программы под процессы заказчика или внедряем и настраиваем готовые решения. Интеграции, доработки и сопровождение.', array( 'На заказ', 'Готовые решения', 'Интеграции' ), 'half' ),
	array( 'seo', 'SEO-продвижение', 'Продвижение в Яндексе и Google: техническая оптимизация, структура и контент сайта, локальное SEO, Карты.', array( 'Яндекс', 'Google', 'Карты' ), false ),
	array( 'ads', 'Контекстная реклама', 'Яндекс Директ и таргет: кампании под заявки, а не клики, и еженедельные отчёты.', array( 'Директ', 'VK Реклама' ), false ),
	array( 'smm', 'SMM и контент', 'Ведение соцсетей, статьи и тексты для сайта, визуальный контент для бренда.', array( 'VK', 'Telegram' ), false ),
	array( 'srv', 'Сопровождение IT-инфраструктуры', 'Подбор, монтаж и настройка серверного и сетевого оборудования. Полное сопровождение серверов, сети и рабочих мест по договору.', array( 'Серверы', 'Сети', 'Бэкапы', '1С', 'Рабочие места' ), true ),
	array( 'brand', 'Брендинг', 'Логотип и фирменный стиль, чтобы компанию узнавали в поиске, соцсетях и на вывеске.', array( 'Логотип', 'Стиль' ), false ),
);

$svc_more = array(
	'web'   => array( 'Прототип и дизайн под ваш бренд', 'Вёрстка, адаптив и базовое SEO', 'Формы заявок, Метрика и цели' ),
	'code'  => array( 'Анализ процессов и техническое задание', 'Разработка или настройка готового решения', 'Интеграция с 1С, CRM и сайтом' ),
	'seo'   => array( 'Технический аудит сайта', 'Семантика и структура страниц', 'Карточка компании на Картах' ),
	'ads'   => array( 'Настройка кампаний в Директе', 'Цели и отслеживание обращений', 'Регулярные отчёты' ),
	'smm'   => array( 'Контент-план', 'Оформление сообществ', 'Тексты и визуал для публикаций' ),
	'srv'   => array( 'Аудит текущей инфраструктуры', 'Монтаж и настройка оборудования', 'Резервное копирование и сопровождение' ),
	'brand' => array( 'Логотип', 'Фирменные цвета и шрифты', 'Макеты для печати и соцсетей' ),
);

$solutions = array(
	array( 'web', 'Сайт · Продвижение', 'Корпоративный сайт', 'Сайт с понятной структурой услуг, формой заявки и подключённой аналитикой — готов к продвижению с первого дня.', array( 'Дизайн и вёрстка', 'Форма заявки', 'Метрика и цели' ) ),
	array( 'soft', 'Программный продукт', 'Программа под процессы компании', 'Учёт заказов, клиентов или задач в одной системе — разработанной на заказ или на основе готового решения.', array( 'Разработка или внедрение', 'Интеграция с 1С', 'Обучение сотрудников' ) ),
	array( 'infra', 'IT-инфраструктура', 'Серверная и сеть офиса', 'Сервер, сеть и Wi-Fi под задачи компании, резервное копирование и сопровождение по договору.', array( 'Монтаж и настройка', 'Резервное копирование', 'Сопровождение' ) ),
	array( 'seo', 'Продвижение', 'Поиск, Карты и обращения', 'Оптимизация сайта под поисковые запросы, карточка компании на Картах и отслеживание звонков и заявок.', array( 'SEO-оптимизация', 'Яндекс Карты', 'Учёт обращений' ) ),
);

$infra = array(
	'Подбор и поставка серверов, СХД, ИБП',
	'Монтаж стойки, СКС, питание, охлаждение',
	'ОС, виртуализация, домен, почта, 1С',
	'Локальная сеть, Wi-Fi, VPN для филиалов',
	'Резервное копирование и защита данных',
	'Сопровождение и выезд инженера по договору',
);

$steps = array(
	array( 'Обсуждаем задачу', 'Уточняем, что нужно сделать и какой результат требуется.', 'Созвон или встреча: цели, сроки, ограничения.' ),
	array( 'Согласовываем условия', 'Определяем объём работ, стоимость и сроки.', 'Фиксируем договорённости в документе до начала работ.' ),
	array( 'Выполняем работы', 'Реализуем согласованные задачи и проверяем результат.', 'Показываем промежуточные результаты по ходу работ.' ),
	array( 'Передаём результат', 'Передаём необходимые материалы и доступы, объясняем дальнейшие действия.', 'Инструкции, доступы и рекомендации по дальнейшей работе.' ),
);

$tasks = array(
	array( 'web', 'Нужен сайт с нуля', 'Разработаем сайт под задачи компании и запустим его.', array( 'Прототип', 'Дизайн', 'Запуск' ) ),
	array( 'seo', 'Сайт есть, но нет обращений', 'Проверим структуру, контент и рекламу, найдём, что мешает.', array( 'Аудит', 'План', 'Внедрение' ) ),
	array( 'net', 'Офис без нормальной сети', 'Спроектируем, подключим и настроим сеть и Wi-Fi.', array( 'Обследование', 'Проект', 'Монтаж' ) ),
	array( 'srv', 'Сервер 1С работает медленно', 'Проверим оборудование и настройки сервера.', array( 'Диагностика', 'Настройка', 'Контроль' ) ),
	array( 'support', 'Нет своего IT-специалиста', 'Обслуживание сайтов, серверов и рабочих компьютеров.', array( 'Договор', 'Регламент', 'Выезды' ) ),
	array( 'code', 'Нужна программа под процессы', 'Разработаем решение на заказ или внедрим готовое.', array( 'Анализ', 'Разработка', 'Внедрение' ) ),
);

$faq = array(
	array( 'Сколько стоит сайт?', 'Стоимость зависит от объёма: количества страниц, функций и готовности материалов. После обсуждения задачи называем стоимость и сроки до начала работ.' ),
	array( 'Можно доработать существующий сайт?', 'Да. Смотрим текущий сайт и предлагаем, что исправить или доработать, — без обязательной переделки с нуля.' ),
	array( 'Выезжаете в офис?', 'Да, работы с сетью и оборудованием выполняем на объекте. Зону выезда уточняем при обсуждении задачи.' ),
	array( 'Работаете по договору?', 'Да, работы оформляются договором, по завершении предоставляем закрывающие документы.' ),
	array( 'Обслуживаете после запуска?', 'Можем взять на поддержку сайт, серверы и рабочие компьютеры. Условия обсуждаем отдельно под вашу задачу.' ),
	array( 'С чего начать?', 'Оставьте заявку или позвоните — уточним задачу и предложим следующий шаг.' ),
);
?>

<!-- ===== Первый экран ===== -->
<section class="hero" id="top">
	<div class="hero__bg" aria-hidden="true">
		<?php if ( $video ) : ?>
			<video class="hero__video" autoplay muted loop playsinline preload="metadata">
				<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
			</video>
		<?php endif; ?>
		<div class="hero__glow hero__glow--a"></div>
		<div class="hero__glow hero__glow--b"></div>
		<div class="hero__grid"></div>
		<canvas class="hero__canvas" id="heroNet"></canvas>
		<div class="hero__fade"></div>
	</div>

	<div class="container hero__inner">
		<div class="hero__text">
			<span class="chip"><span class="pulse"></span>IT-компания полного цикла</span>
			<h1 class="hero__title">Сайты, программы и&nbsp;IT&#8209;сопровождение <span class="grad">под&nbsp;ключ</span></h1>
			<p class="hero__lead">Разрабатываем сайты и программные продукты — на заказ или на основе готовых решений. Настраиваем продвижение, помогаем отслеживать обращения и полностью сопровождаем IT-инфраструктуру компании.</p>
			<div class="hero__actions">
				<a class="btn btn--accent" href="#contacts">Обсудить проект <span class="arr" aria-hidden="true">→</span></a>
				<a class="btn btn--ghost" href="#services">Наши услуги</a>
			</div>
			<div class="hero__stats">
				<div class="hero__stat"><b>Под ключ</b><span>от задачи до запуска</span></div>
				<div class="hero__stat"><b>Выезд</b><span>инженера в офис</span></div>
				<div class="hero__stat"><b>Поддержка</b><span>после запуска</span></div>
			</div>
		</div>

		<div class="scene" id="scene" aria-hidden="true">
			<div class="scene__rig">
				<div class="laptop">
					<div class="laptop__screen">
						<div class="ui">
							<div class="ui__nav"><span class="ui__logo"></span><span class="ui__links"><i></i><i></i><i></i><i></i></span></div>
							<div class="ui__hero">
								<div class="ui__copy">
									<span class="ui__h">Ваш сайт —<br>первое впечатление</span>
									<span class="ui__p"></span><span class="ui__p ui__p--s"></span>
									<span class="ui__btn">Оставить заявку</span>
								</div>
								<div class="ui__chart"><i></i><i></i><i></i><i></i><i></i><i></i></div>
							</div>
							<div class="ui__cards"><i></i><i></i><i></i></div>
						</div>
					</div>
					<div class="laptop__base"></div>
				</div>

				<div class="fl fl--a">
					<small>Аналитика</small>
					<b>Заявки</b>
					<svg class="fl__spark" viewBox="0 0 120 34"><path d="M2 30 L20 26 L34 28 L50 18 L64 21 L80 11 L96 13 L118 3"/></svg>
				</div>
				<div class="fl fl--b">
					<small>SEO-аудит</small>
					<div class="fl__rows">
						<div class="fl__row"><em>✓</em>структура</div>
						<div class="fl__row"><em>✓</em>скорость</div>
						<div class="fl__row"><em>✓</em>контент</div>
					</div>
				</div>
				<div class="fl fl--c">
					<small>Мониторинг сервера</small>
					<b>Online</b>
					<div class="leds"><span class="led"></span><span class="led"></span><span class="led"></span><span class="led"></span></div>
				</div>
				<div class="fl fl--d">
					<small>Вёрстка</small>
					<b>Адаптив</b>
				</div>

				<div class="phone">
					<div class="ui">
						<div class="ui__nav"><span class="ui__logo" style="width:30%"></span></div>
						<span class="ui__h">Mobile<br>first</span>
						<span class="ui__p"></span><span class="ui__p ui__p--s"></span>
						<span class="ui__btn">Заказать</span>
						<div class="ui__chart" style="height:28%;flex:none"><i></i><i></i><i></i><i></i></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ===== Бегущая строка ===== -->
<div class="ticker" aria-hidden="true">
	<div class="ticker__track">
		<?php for ( $r = 0; $r < 2; $r++ ) : ?>
			<?php foreach ( $ticker as $t ) : ?>
				<span class="ticker__item"><?php echo esc_html( $t ); ?></span>
			<?php endforeach; ?>
		<?php endfor; ?>
	</div>
</div>

<!-- ===== Проблема ===== -->
<section class="section container">
	<div class="problem glass reveal">
		<div class="problem__text">
			<span class="eyebrow">Почему это важно</span>
			<h2 class="problem__title">Без сильного сайта вы <span class="grad">теряете клиентов</span> каждый день</h2>
			<p class="lead">Клиенты ищут подрядчика в интернете и выбирают того, кто выглядит надёжно. Мы делаем так, чтобы этим подрядчиком были вы.</p>
		</div>
		<div class="problem__metrics">
			<div class="metric"><b>Сайт<span>.</span></b><small>первое, что видит клиент о компании</small></div>
			<div class="metric"><b>Поиск<span>.</span></b><small>клиенты находят вас в Яндексе и на Картах</small></div>
			<div class="metric"><b>Реклама<span>.</span></b><small>кампании и аналитика под цели бизнеса</small></div>
			<div class="metric"><b>IT<span>.</span></b><small>сеть и серверы, на которых держится работа</small></div>
		</div>
	</div>
</section>

<!-- ===== Услуги ===== -->
<section class="section container" id="services">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Наша экспертиза</span>
			<h2 class="h2">Что мы делаем</h2>
		</div>
		<p class="lead" style="max-width:480px">Всё, что нужно организации в цифровой среде: от сайта и клиентов из интернета до серверов и сети в офисе.</p>
	</div>
	<div class="bento">
		<?php foreach ( $services as $s ) : ?>
			<a class="svc glass spot reveal<?php echo 'half' === $s[4] ? ' svc--half' : ( 'full' === $s[4] ? ' svc--full' : ( $s[4] ? ' svc--wide' : '' ) ); ?>" href="<?php $lk = array( 'web' => 'sozdanie-saytov', 'seo' => 'prodvizhenie-saytov', 'srv' => 'it-autsorsing' ); echo isset( $lk[ $s[0] ] ) ? esc_url( intech_landing_url( $lk[ $s[0] ] ) ) : '#contacts'; ?>">
				<span class="svc__icon"><?php echo intech_icon( $s[0] ); // phpcs:ignore ?></span>
				<span class="svc__title"><?php echo esc_html( $s[1] ); ?></span>
				<span class="svc__text"><?php echo esc_html( $s[2] ); ?></span>
				<?php if ( isset( $svc_more[ $s[0] ] ) ) : ?>
					<span class="xp"><span class="xp__in">
						<span class="xp__list">
							<?php foreach ( $svc_more[ $s[0] ] as $m ) : ?><span class="xp__item"><?php echo esc_html( $m ); ?></span><?php endforeach; ?>
						</span>
					</span></span>
				<?php endif; ?>
				<span class="svc__tags">
					<?php foreach ( $s[3] as $tag ) : ?><span><?php echo esc_html( $tag ); ?></span><?php endforeach; ?>
				</span>
				<span class="link-accent">Подробнее <span aria-hidden="true">→</span></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>

<!-- ===== IT-инфраструктура ===== -->
<section class="section container" id="infra">
	<div class="infra glass reveal">
		<div class="infra__intro">
			<span class="eyebrow">IT-инфраструктура</span>
			<h2 class="h2">Полное сопровождение <span class="grad">IT-инфраструктуры</span></h2>
			<p class="lead">Приезжаем на объект, проектируем и собираем инфраструктуру под задачи компании, а дальше сопровождаем серверы, сеть и рабочие места.</p>
			<ul class="infra__list">
				<?php foreach ( $infra as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?>
			</ul>
			<div class="hero__actions">
				<a class="btn btn--accent" href="#contacts">Заказать аудит инфраструктуры <span class="arr" aria-hidden="true">→</span></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( intech_landing_url( 'servery' ) ); ?>">Серверы и бэкапы</a>
				<a class="btn btn--ghost" href="<?php echo esc_url( intech_landing_url( 'seti' ) ); ?>">Сети и Wi-Fi</a>
				<a class="btn btn--ghost" href="<?php echo esc_url( intech_landing_url( 'videonablyudenie' ) ); ?>">Видеонаблюдение</a>
			</div>
		</div>
		<div class="rack" aria-hidden="true">
			<?php
			$units = array( array( 'SW-CORE-01', 1 ), array( 'SRV-APP-01', 2 ), array( 'SRV-1C-02', 2 ), array( 'NAS-BACKUP', 1 ), array( 'FW-GATEWAY', 1 ), array( 'UPS-3KVA', 2 ) );
			foreach ( $units as $u ) :
				?>
				<div class="rack__unit<?php echo 2 === $u[1] ? ' rack__unit--2' : ''; ?>">
					<span class="led"></span><span class="led"></span>
					<span class="bays"><i></i><i></i><i></i><i></i></span>
					<span class="label"><?php echo esc_html( $u[0] ); ?></span>
				</div>
			<?php endforeach; ?>
			<div class="rack__status"><span>● ALL SYSTEMS ONLINE</span><span>status: OK</span></div>
		</div>
	</div>
</section>

<!-- ===== Кейсы / Примеры решений ===== -->
<?php $intech_cases = new WP_Query( array( 'post_type' => 'intech_case', 'posts_per_page' => 6, 'ignore_sticky_posts' => true ) ); ?>
<?php if ( $intech_cases->have_posts() ) : ?>
<section class="section container" id="cases">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Кейсы</span>
			<h2 class="h2">Наши проекты</h2>
		</div>
		<?php $intech_cases_link = get_post_type_archive_link( 'intech_case' ); ?>
		<?php if ( $intech_cases_link ) : ?>
			<a class="link-accent" href="<?php echo esc_url( $intech_cases_link ); ?>">Все кейсы <span aria-hidden="true">→</span></a>
		<?php endif; ?>
	</div>
	<div class="cases">
		<?php while ( $intech_cases->have_posts() ) : $intech_cases->the_post(); ?>
			<a class="case glass spot reveal" href="<?php the_permalink(); ?>">
				<div class="case__media">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'case__img', 'alt' => '' ) ); ?>
					<?php endif; ?>
				</div>
				<div class="case__body">
					<?php $intech_tag = get_post_meta( get_the_ID(), 'case_tag', true ); ?>
					<?php if ( $intech_tag ) : ?><span class="case__tag"><?php echo esc_html( $intech_tag ); ?></span><?php endif; ?>
					<span class="case__title"><?php the_title(); ?></span>
					<span class="case__text"><?php echo esc_html( get_the_excerpt() ); ?></span>
				</div>
			</a>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</section>
<?php else : ?>
<section class="section container" id="cases">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Примеры решений</span>
			<h2 class="h2">Что мы можем <span class="grad">сделать для вас</span></h2>
		</div>
		<p class="lead" style="max-width:460px">Типовые решения, которые мы разрабатываем и внедряем. Состав работ подбираем под вашу задачу.</p>
	</div>
	<div class="solutions">
		<?php foreach ( $solutions as $sol ) : ?>
			<article class="sol glass reveal">
				<div class="sol__art">
					<span class="sol__badge">Пример решения</span>
					<a class="sol__go" href="#contacts">Обсудить такое решение <span aria-hidden="true">→</span></a>
					<?php echo intech_art( $sol[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- SVG из файлов темы ?>
				</div>
				<div class="sol__body">
					<span class="case__tag"><?php echo esc_html( $sol[1] ); ?></span>
					<h3 class="sol__title"><?php echo esc_html( $sol[2] ); ?></h3>
					<p class="sol__text"><?php echo esc_html( $sol[3] ); ?></p>
					<ul class="sol__list">
						<?php foreach ( $sol[4] as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?>
					</ul>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
	<div class="sol__cta reveal">
		<a class="btn btn--accent" href="#contacts">Обсудить свою задачу <span class="arr" aria-hidden="true">→</span></a>
	</div>
</section>
<?php endif; ?>

<!-- ===== Процесс ===== -->
<section class="section container">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Процесс</span>
			<h2 class="h2">Как мы работаем</h2>
		</div>
		<a class="btn btn--ghost" href="#contacts">Начать проект <span class="arr" aria-hidden="true">→</span></a>
	</div>
	<div class="timeline timeline--4">
		<?php foreach ( $steps as $i => $st ) : ?>
			<div class="tl reveal" tabindex="0">
				<span class="tl__n"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<span class="tl__title"><?php echo esc_html( $st[0] ); ?></span>
				<span class="tl__text"><?php echo esc_html( $st[1] ); ?></span>
				<span class="xp"><span class="xp__in"><span class="tl__more"><?php echo esc_html( $st[2] ); ?></span></span></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- ===== О компании и цифры ===== -->
<section class="section container" id="about">
	<div class="about reveal">
		<div style="display:flex;flex-direction:column;gap:16px">
			<span class="eyebrow">О компании</span>
			<h2 class="h2">ИНТЕХ — ваш <span class="grad">технологический партнёр</span></h2>
		</div>
		<p class="lead">Мы соединяем разработку и «железо»: создаём сайты и программы, приводим клиентов, а в офисе сопровождаем серверы и сеть, на которых держится работа компании. Один подрядчик — одна ответственность.</p>
	</div>
	<div class="stats">
		<div class="stat glass spot reveal"><div class="stat__num stat__num--word">Одна команда<span>.</span></div><div class="stat__label">сайты, программы и IT-инфраструктура у одного подрядчика</div><span class="xp"><span class="xp__in"><span class="stat__more">Не нужно координировать нескольких подрядчиков — вопросы по сайту, программам и IT решаются в одном месте.</span></span></span></div>
		<div class="stat glass spot reveal"><div class="stat__num stat__num--word">Прозрачно<span>.</span></div><div class="stat__label">объём, стоимость и сроки согласуем до начала работ</div><span class="xp"><span class="xp__in"><span class="stat__more">Все договорённости фиксируем в документе, изменения обсуждаем заранее.</span></span></span></div>
		<div class="stat glass spot reveal"><div class="stat__num stat__num--word">Рядом<span>.</span></div><div class="stat__label">остаёмся на связи и после передачи результата</div><span class="xp"><span class="xp__in"><span class="stat__more">Отвечаем на вопросы и помогаем с доработками после запуска.</span></span></span></div>
	</div>
</section>

<!-- ===== Решаем задачи бизнеса ===== -->
<section class="section container" id="tasks">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">С чем к нам приходят</span>
			<h2 class="h2">Решаем задачи бизнеса</h2>
		</div>
	</div>
	<ul class="tasks reveal">
		<?php foreach ( $tasks as $t ) : ?>
			<li class="task">
				<span class="task__icon"><?php echo intech_icon( $t[0] ); // phpcs:ignore ?></span>
				<span class="task__title"><?php echo esc_html( $t[1] ); ?></span>
				<span class="task__text"><?php echo esc_html( $t[2] ); ?></span>
				<span class="xp"><span class="xp__in">
					<span class="task__steps">
						<?php foreach ( $t[3] as $k => $stp ) : ?><?php if ( $k ) : ?><span class="task__arr" aria-hidden="true">→</span><?php endif; ?><span><?php echo esc_html( $stp ); ?></span><?php endforeach; ?>
					</span>
				</span></span>
			</li>
		<?php endforeach; ?>
	</ul>
</section>

<!-- ===== Частые вопросы ===== -->
<section class="section container" id="faq">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Вопросы</span>
			<h2 class="h2">Частые вопросы</h2>
		</div>
	</div>
	<div class="faq">
		<?php foreach ( $faq as $q ) : ?>
			<details class="faq__item glass reveal">
				<summary class="faq__q"><?php echo esc_html( $q[0] ); ?><span class="faq__plus" aria-hidden="true"></span></summary>
				<p class="faq__a"><?php echo esc_html( $q[1] ); ?></p>
			</details>
		<?php endforeach; ?>
	</div>
</section>

<!-- ===== Блог ===== -->
<section class="section container" id="blog">
	<div class="section-head reveal">
		<div class="section-head__title">
			<span class="eyebrow">Блог</span>
			<h2 class="h2">Полезное для бизнеса</h2>
		</div>
		<a class="link-accent" href="<?php echo esc_url( intech_blog_url() ); ?>">Все статьи <span aria-hidden="true">→</span></a>
	</div>
	<div class="acards">
		<?php foreach ( array_slice( intech_articles(), 0, 6 ) as $fa ) : ?>
			<?php echo intech_article_card( $fa ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php endforeach; ?>
	</div>
</section>

<?php get_template_part( 'parts/contact' ); ?>

<?php
get_footer();
