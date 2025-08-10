<?php
/**
 * Footer template for the CB TXP theme.
 *
 * This file contains the footer section of the theme, including navigation menus,
 * office addresses, and colophon information.
 *
 * @package cb-statman2025
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_page( 'contact' ) ) {
	// Include the contact CTA block if not on the contact page.
	get_template_part( 'page-templates/blocks/cb-contact-cta' );
}
?>
<div id="footer-top"></div>
<div class="pre-footer">
	<div class="container text-center py-5">
		<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/nick-statman-sig.svg' ); ?>" width="400" height="85">
	</div>
</div>
<footer class="footer py-4">
    <div class="container">
        <div class="row pb-4 g-3">
            <div class="col-sm-2">
				<div class="footer-title">Links</div>
                <?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu1',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
            </div>
            <div class="col-sm-5">
				<div class="footer-title">Services</div>
                <?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu2',
						'menu_class'     => 'footer__menu cols-lg-2',
					)
				);
				?>
            </div>
            <div class="col-sm-2 footer__contact">
                <div class="footer-title">Contact</div>
				<?= do_shortcode( '[contact_phone]' ); ?><br>
				<?= do_shortcode( '[contact_email]' ); ?><br>
            </div>
			<div class="col-sm-3 text-center">
				<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/nick-statman-sig--wo.svg' ); ?>" class="mb-4" width="250" height="53">
				<?= do_shortcode( '[social_icons class="d-flex justify-content-center gap-3 fs-500 has-accent-500-color"]' ); ?>
            </div>
        </div>

        <div class="colophon d-flex justify-content-between align-items-center flex-wrap">
            <div>
                &copy; <?= esc_html( gmdate( 'Y' ) ); ?> Nick Statman.
            </div>
            <div>
                <a href="https://www.chillibyte.co.uk/" rel="nofollow noopener" target="_blank" class="cb"
                title="Digital Marketing by Chillibyte"></a>
            </div>
        </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>