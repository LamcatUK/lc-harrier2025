<?php
/**
 * Block template for CB Text Table Fancy.
 *
 * @package cb-statman2025
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="text_image_fancy has-secondary-300-background-color py-5">
	<div class="container">
		<div class="row g-5">
			<div class="col-md-6 my-auto d-flex flex-column text_image_fancy__text" data-aos="fade">
				<h2 class="text_image_fancy__title"><?= esc_html( get_field( 'title' ) ); ?></h2>
				<div class="text_image_fancy__content">
					<?= wp_kses_post( get_field( 'content' ) ); ?>
				</div>
				<?php
				if ( get_field( 'link' ) ) {
					$l = get_field( 'link' );
					?>
					<a class="button button-solid align-self-end mt-4 me-0" href="<?= esc_url( $l['url'] ); ?>" target="_blank"
					   rel="noopener noreferrer">
						<?= esc_html( $l['title'] ); ?>
					</a>
					<?php
				}
				?>
			</div>
			<div class="col-md-6">
				<div class="text_image_fancy__table-container mx-5" data-aos="fade-up">
					<div class="table_header">
						<?= esc_html( get_field( 'table_title' ) ); ?>
					</div>
					<?php
					$d = 0;
					while ( have_rows( 'table_content' ) ) {
						the_row();
						?>
					<div class="table_row" data-aos="fade-left" data-aos-delay="<?= esc_attr( $d ); ?>">
						<div class="table_cell my-auto text-green"><i class="fa-solid fa-check fa-2x"></i></div>
						<div class="table_cell">
							<strong><?= esc_html( get_sub_field( 'term' ) ); ?></strong><br>
							<?= esc_html( get_sub_field( 'definition' ) ); ?>
						</div>
					</div>
						<?php
						$d += 100;
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>