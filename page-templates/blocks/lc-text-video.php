<?php
/**
 * Block template for LC Text Video.
 *
 * @package lc-harrier2025
 */

defined( 'ABSPATH' ) || exit;

$bg      = get_field( 'bg_colour' );
$fg      = get_field( 'fg_colour' );
$classes = array();
$style   = '';

if ( $bg ) {
	$classes[] = 'has-' . sanitize_html_class( $bg ) . '-background-color';
}
if ( $fg ) {
	$classes[] = 'has-' . sanitize_html_class( $fg ) . '-color';
}
?>
<section class="text-video <?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<div class="container py-5">
		<div class="row g-4">
			<div class="col-lg-4">
				<h2 class="mt-0"><?= esc_html( get_field( 'title' ) ); ?></h2>
				<div>
					<?= wp_kses_post( get_field( 'content' ) ); ?>
				</div>
				<?php
				if ( get_field( 'link' ) ) {
					$button_link = get_field( 'link' );
					?>
					<div class="text-end">
						<a class="btn btn--primary mt-3 me-0" href="<?= esc_url( $button_link['url'] ); ?>" target="<?= esc_attr( $button_link['target'] ); ?>">
							<?= esc_html( $button_link['title'] ); ?>
						</a>
					</div>
					<?php
				}
				?>
			</div>
			<div class="col-lg-8">
				<div class="text-video__wrapper">
					<div class="text-video__poster" alt="">
						<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/harrier-gates--wo.webp' ); ?>">
					</div>
					<div class="text-video__vimeo ratio ratio-16x9">
						<?= wp_oembed_get( 'https://player.vimeo.com/video/' . get_field( 'vimeo_id' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var wrapper = document.querySelector('.text-video__wrapper');
	var poster = wrapper.querySelector('.text-video__poster');
	var iframe = wrapper.querySelector('iframe');
	if (iframe) {
		iframe.addEventListener('load', function() {
		poster.style.display = 'none';
		});
	}
});
</script>
		<?php
	}
);