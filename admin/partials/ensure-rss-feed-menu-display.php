
<?php settings_errors(); ?>

<form method="post" action="options.php">

	<?php settings_fields( 'ers-settings-group' ); ?>
	<?php do_settings_sections( 'ensure-rss-feed' ); ?>

	<?php submit_button(); ?>

</form>
