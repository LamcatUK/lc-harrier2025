<?php
/**
 * Template for displaying the blog index page.
 *
 * @package cb-statman2025
 */

defined( 'ABSPATH' ) || exit;

$page_for_posts = get_option( 'page_for_posts' );

get_header();
?>
<main id="main">
	<section class="hero">
		<div class="container h-100">
			<div class="row h-100 g-5">
				<div class="col-md-6 my-auto pe-5 hero__content">
					<h1 class="hero__title" data-aos="fade">News &amp; Insights</h1>
					<p class="hero__subtitle" data-aos="fade">Insightful updates and straight-talking advice on property, deals, and strategy</p>
				</div>
				<div class="col-md-6 px-0 hero__image-container">
					<?=
					get_the_post_thumbnail(
						$page_for_posts,
						'full',
						array(
							'class'    => 'hero__image',
							'data-aos' => 'zoom-out',
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>
    <section class="latest_posts mt-5">
        <div class="container pb-5">
            <?php
            // Get all categories for filter buttons.
            $all_categories = get_categories(
				array(
					'hide_empty' => true,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			);

            if ( ! empty( $all_categories ) ) {
                ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="filter-buttons text-center">
                            <button class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
                            <?php
							foreach ( $all_categories as $category ) {
								?>
                                <button class="btn btn-outline-primary filter-btn" data-filter="<?= esc_attr( $category->slug ); ?>"><?= esc_html( $category->name ); ?></button>
                            	<?php
							}
							?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="row g-4 w-100">
            <?php
            // Custom query to include both published and scheduled posts.
            $args = array(
                'post_type'      => 'post',
                'post_status'    => array( 'publish', 'future' ), // Include published and scheduled posts.
                'orderby'        => 'date',
                'order'          => 'DESC', // Descending order.
                'posts_per_page' => -1,    // Get all posts.
            );

            $q = new WP_Query( $args );

            if ( $q->have_posts() ) {
				while ( $q->have_posts() ) {
					$q->the_post();
					// get categories.
					$categories = get_the_category();
					if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
						// get space separated list of category slugs.
						$first_category = $categories[0];
						// If there are multiple categories, use the first one.
						if ( count( $categories ) > 1 ) {
							// Get the first category slug.
							$categories = array_slice( $categories, 0, 1 );
						}
						// Convert to space separated list.
						$categories = implode( ' ', wp_list_pluck( $categories, 'slug' ) );
					}
					?>
					<div class="col-md-6 col-lg-4" data-aos="fade" data-aos-delay="<?= esc_attr( $d ); ?>" data-category="<?= esc_attr( $categories ); ?>">
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
            } else {
                echo '<p>No posts found.</p>';
            }

            // Reset post data.
            wp_reset_postdata();
            ?>
            </div>
        </div>
    </section>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const posts = document.querySelectorAll('[data-category]');

    // Add post-item class to all posts
    posts.forEach(post => {
        post.classList.add('post-item');
    });

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterValue = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter posts
            posts.forEach(post => {
                const postCategories = post.getAttribute('data-category');
                const shouldShow = filterValue === 'all' || (postCategories && postCategories.includes(filterValue));
                
                if (shouldShow) {
                    post.style.display = 'block';
                } else {
                    post.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php

get_footer();
?>