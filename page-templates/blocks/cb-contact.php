<?php
/**
 * Block template for CB Contact.
 *
 * @package cb-statman2025
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="contact">
	<div class="container py-5">
		<div class="row g-5">
			<div class="col-md-6">
				<p>For any questions or to discuss your property needs, please call, email, or fill in the form.</p>
				<ul class="fa-ul">
					<li><span class="fa-li"><i class="fa-solid fa-phone has-accent-500-color"></i></span> <a href="tel:<?= esc_attr( get_field( 'contact_phone', 'option' ) ); ?>"><?= esc_html( get_field( 'contact_phone', 'option' ) ); ?></a></li>
					<li><span class="fa-li"><i class="fa-solid fa-envelope has-accent-500-color"></i></span> <a href="mailto:<?= esc_attr( antispambot( get_field( 'contact_email', 'option' ) ) ); ?>"><?= esc_html( antispambot( get_field( 'contact_email', 'option' ) ) ); ?></a></li>
				</ul>
			</div>
			<div class="col-md-6">
				<?= do_shortcode( '[contact-form-7 id="' . esc_attr( get_field( 'contact_form_id' ) ) . '"]' ); ?>
			</div>
		</div>
	</div>
</section>