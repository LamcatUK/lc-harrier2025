<?php
/**
 * Block template for CB Stat Spinner.
 *
 * @package cb-statman2025
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="stat_spinner py-5">
	<div class="container">
		<div class="row justify-content-center">
			<?php
			while ( have_rows( 'stat_spinner' ) ) {
				the_row();
				$stat  = get_sub_field( 'stat' );
				$label = get_sub_field( 'label' );
				?>
				<div class="col-6 col-md-4 col-lg-3 stat_spinner__item">
					<div class="stat_spinner__stat">
						<?php
						if ( get_sub_field( 'prefix' ) ) {
							?>
						<span class="stat_spinner__prefix"><?= esc_html( get_sub_field( 'prefix' ) ); ?></span>
							<?php
						}
						?>
						<span class="stat_spinner__value"><?= esc_html( $stat ); ?></span>
						<?php
						if ( get_sub_field( 'suffix' ) ) {
							?>
						<span class="stat_spinner__suffix"><?= esc_html( get_sub_field( 'suffix' ) ); ?></span>
							<?php
						}
						?>
					</div>
					<span class="stat_spinner__label"><?= esc_html( $label ); ?></span>
				</div>
				<?php
			}
			?>
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
				const statSpinnerSection = document.querySelector('.stat_spinner');
				if (!statSpinnerSection) return;

				let hasAnimated = false;

				function animateStats() {
					if (hasAnimated) return;
					hasAnimated = true;

					document.querySelectorAll('.stat_spinner__value').forEach(function (el) {
						const value = parseInt(el.textContent, 10);
						let current = 0;
						const increment = Math.ceil(value / 100);

						const interval = setInterval(function () {
							current += increment;
							if (current >= value) {
								current = value;
								clearInterval(interval);
							}
							el.textContent = current.toLocaleString();
						}, 20);
					});
				}

				// Create intersection observer
				const observer = new IntersectionObserver(function(entries) {
					entries.forEach(function(entry) {
						if (entry.isIntersecting) {
							animateStats();
							observer.unobserve(entry.target);
						}
					});
				}, {
					threshold: 0.5 // Trigger when 50% of the section is visible
				});

				// Start observing the stat spinner section
				observer.observe(statSpinnerSection);
			});
		</script>
		<?php
	}
);