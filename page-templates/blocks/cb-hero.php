<?php
/**
 * Block template for CB Hero.
 *
 * @package cb-statman2025
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="hero">
	<div class="container h-100">
		<div class="row h-100 g-5">
			<div class="col-md-6 my-auto pe-5 hero__content">
				<h1 class="hero__title" data-aos="fade"><?= esc_html( get_field( 'title' ) ); ?></h1>
				<p class="hero__subtitle" data-aos="fade"><?= esc_html( get_field( 'subtitle' ) ); ?></p>
			</div>
			<div class="col-md-6 px-0 hero__image-container">
				<?=
				wp_get_attachment_image(
					get_field( 'image' ),
					'full',
					false,
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