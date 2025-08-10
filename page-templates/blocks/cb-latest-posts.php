<?php
/**
 * Block template for CB Latest Posts.
 *
 * @package cb-statman2025
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="latest_posts has-secondary-300-background-color py-5">
	<div class="container">
		<h2 class="fancy" data-aos="fade">News &amp; Insights</h2>
		<?php
		$q = new WP_Query(
			array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'order'          => 'DESC',
				'orderby'        => 'date',
			)
		);
		if ( $q->have_posts() ) {
			?>
			<div class="row g-4 mt-4 mb-4">
				<?php
				$d = 0;
				while ( $q->have_posts() ) {
					$q->the_post();
					// get first category.
					$first_category = get_the_category();
					if ( ! empty( $first_category ) && ! is_wp_error( $first_category ) ) {
						$first_category = $first_category[0];
					} else {
						$first_category = null;
					}
					?>
					<div class="col-md-6 col-lg-4" data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>">
						<a class="latest_posts__item" href="<?= esc_url( get_permalink() ); ?>">
							<div class="latest_posts__image">
								<?php
								if ( $first_category ) {
									?>
									<span class="badge"><?= esc_html( $first_category->name ); ?></span>
									<?php
								} else {
									?>
									<span class="badge">News</span>
									<?php
								}
								?>
								<?= get_the_post_thumbnail( get_the_ID(), 'medium', array( 'class' => 'img-fluid' ) ); ?>
							</div>
							<div class="post_meta ps-3">
								<span><i class="fa-regular fa-calendar"></i> <?= esc_html( get_the_date( 'jS F Y' ) ); ?></span>
								<span><i class="fa-regular fa-clock"></i> <?= esc_html( estimate_reading_time_in_minutes( get_the_content() ) ); ?> minute read</span>
							</div>
							<h3 class="latest_posts__title"><?= esc_html( get_the_title() ); ?></h3>
						</a>
					</div>
					<?php
					$d += 100;
				}
				?>
			</div>
			<?php
		}
		wp_reset_postdata();
		?>
		<div class="text-center">
			<a class="button button-solid" href="<?= esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">All News &amp; Insights</a>
		</div>
	</div>
</section>