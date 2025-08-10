<?php
/**
 * Block template for CB Service Cards.
 *
 * @package cb-statman2025
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="service_cards py-5">
	<div class="container">
		<h2 class="fancy mb-4" data-aos="fade"><?= esc_html( get_field( 'title' ) ); ?></h2>
		<div class="row g-4 mb-4">
			<?php
			while ( have_rows( 'services' ) ) {
				the_row();
				$service_slug = sanitize_title( get_sub_field( 'service_name' ) );
				?>
				<div class="col-md-6 col-lg-4" data-aos="fade-up">
					<a class="service_cards__item" href="<?= esc_url( '/services/#' . $service_slug ); ?>">
						<span class="service_cards__icon fa-stack fa-4x has-accent-500-color">
							<i class="fa-solid fa-circle fa-stack-2x"></i>
							<i class="fa-stack-1x fa-inverse <?= esc_attr( get_sub_field( 'glyph' ) ); ?>"></i>
						</span>
						<h3 class="service_cards__title"><?= esc_html( get_sub_field( 'service_name' ) ); ?></h3>
						<p class="service_cards__description"><?= esc_html( get_sub_field( 'service_description' ) ); ?></p>
						<div class="service_cards__link">Read More <i class="fa-solid fa-angle-right"></i></div>
					</a>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</section>