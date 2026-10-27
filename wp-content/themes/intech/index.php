<?php
/**
 * Блог, записи, страницы и всё остальное.
 */
get_header();
?>

<section class="container page-hero">
	<?php if ( is_singular() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if ( is_single() ) : ?>
				<p class="eyebrow" style="margin-bottom:16px"><?php echo esc_html( get_the_date() ); ?></p>
			<?php endif; ?>
			<h1><?php the_title(); ?></h1>
		<?php endwhile; rewind_posts(); ?>
	<?php else : ?>
		<p class="eyebrow" style="margin-bottom:16px">Блог</p>
		<h1><?php echo is_home() ? 'Полезное для бизнеса' : wp_kses_post( get_the_archive_title() ); ?></h1>
	<?php endif; ?>
</section>

<section class="container">
	<?php if ( is_singular() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', array( 'class' => 'post-card__img', 'style' => 'margin-bottom:40px' ) ); ?>
			<?php endif; ?>
			<article class="entry"><?php the_content(); ?></article>
		<?php endwhile; ?>
	<?php elseif ( have_posts() ) : ?>
		<div class="cards">
			<?php while ( have_posts() ) : the_post(); ?>
				<a class="post-card" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'post-card__img', 'alt' => '' ) ); ?>
					<?php else : ?>
						<div class="post-card__img placeholder">[Обложка статьи]</div>
					<?php endif; ?>
					<span class="post-card__meta"><?php echo esc_html( get_the_date() ); ?></span>
					<span class="post-card__title"><?php the_title(); ?></span>
				</a>
			<?php endwhile; ?>
		</div>
		<div class="pagination"><?php echo paginate_links(); // phpcs:ignore ?></div>
	<?php else : ?>
		<p class="lead">Пока здесь пусто.</p>
	<?php endif; ?>
</section>

<?php
get_footer();
