<?php

use Elementor\Plugin;
use Elementor\Beta_Testers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$user = wp_get_current_user();

$ajax = Plugin::$instance->common->get_component( 'ajax' );

$email = $user->user_email;
?>
<script type="text/template" id="tmpl-elementor-beta-tester">
	<form id="elementor-beta-tester-form" method="post">
		<input type="hidden" name="_nonce" value="<?php echo $ajax->create_nonce(); ?>">
		<input type="hidden" name="action" value="elementor_beta_tester_signup" />
		<div id="elementor-beta-tester-form__caption"><?php echo __( 'Get Developer Updates', 'elementor-beta' ); ?></div>
		<div id="elementor-beta-tester-form__description"><?php echo __( 'You’ll receive an update that includes a developer version of Elementor, and it’s content directly to your Email', 'elementor-beta' ); ?></div>
		<div id="elementor-beta-tester-form__input-wrapper">
			<input id="elementor-beta-tester-form__email" name="beta_tester_email" type="email" placeholder="<?php echo __( 'Your Email', 'elementor-beta' ); ?>" required value="<?php echo $email; ?>" />
			<button id="elementor-beta-tester-form__submit" class="elementor-button elementor-button-success">
				<span class="elementor-state-icon">
					<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
				</span>
				<?php echo __( 'Sign Up', 'elementor-beta' ); ?>
			</button>
		</div>
		<div id="elementor-beta-tester-form__terms">
			<?php echo sprintf( __( 'By clicking Sign Up, you agree to Elementor\'s <a href="%1$s">Terms of Service</a> and <a href="%2$s">Privacy Policy</a>', 'elementor-beta' ), Beta_Testers::NEWSLETTER_TERMS_URL, Beta_Testers::NEWSLETTER_PRIVACY_URL ); ?>
		</div>
	</form>
</script>

