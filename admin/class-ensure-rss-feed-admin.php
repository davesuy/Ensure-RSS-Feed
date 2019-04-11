<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/davesuy
 * @since      1.0.0
 *
 * @package    Ensure_Rss_Feed
 * @subpackage Ensure_Rss_Feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ensure_Rss_Feed
 * @subpackage Ensure_Rss_Feed/admin
 * @author     Dave Ramirez <davesuywebmaster@gmail.com>
 */
class Ensure_Rss_Feed_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ensure_Rss_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ensure_Rss_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ensure-rss-feed-admin.css', array(), $this->version, 'all' );

		//wp_enqueue_style( "bootstrap.min.css", plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ensure_Rss_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ensure_Rss_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		$screen = get_current_screen();

		if ( $screen->id == "toplevel_page_ensure-rss-feed" ) {
	
			wp_enqueue_script( 'jquery.form-repeater', plugin_dir_url( __FILE__ ) . 'js/jquery.form-repeater.js', array( 'jquery' ), $this->version, false );
		

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ensure-rss-feed-admin.js', array( 'jquery' ), $this->version, false );

		}

	}



	public function ers_menus_sections() {
		global $wpdb;

		add_menu_page("Ensure RSS Feed", "Ensure RSS Feed", "manage_options", "ensure-rss-feed", array($this, "ers_menus_callback_function"), "dashicons-admin-plugins", 7);

		add_submenu_page("ensure-rss-feed", "Settings", "General", "manage_options", "ensure-rss-feed", array($this, "ers_menus_callback_function"));

		//add_submenu_page("ensure-rss-feed", "ERS CSS Options", "Custom CSS", "manage_options", "ensure-rss-feed-css", array($this, "ers_menus_callback_function_css"));

		// Activate Settings
		add_action('admin_init', array($this, 'ers_custom_settings'));

	}


	public function ers_custom_settings() {
		register_setting('ers-settings-group', 'ers_feed_url');
		register_setting('ers-settings-group', 'ers_author');
		register_setting('ers-settings-group', 'ers_cpt');

		add_settings_section('ers-settings-options', 'Settings Options', array($this, 'ers_settings_options'), 'ensure-rss-feed');

		add_settings_field('ers-feed-url', 'Feed URL', array($this, 'ers_feed_url'), 'ensure-rss-feed', 'ers-settings-options');
		add_settings_field('ers-author', 'Author ID', array($this, 'ers_author'), 'ensure-rss-feed', 'ers-settings-options');
		add_settings_field('ers-cpt', 'Custom Post type key', array($this, 'ers_cpt'), 'ensure-rss-feed', 'ers-settings-options');
	}

	public function ers_feed_url() {

		$feed_url = get_option('ers_feed_url');

		$feed_url = array_filter($feed_url);  
		$feed_url_count = count($feed_url) - 1;

		//echo '<pre>'.print_r($feed_url, true).'</pre>';
		?>

		<div id="feedUrlCon">
			<button type="button" class="r-btnAdd button button-primary ">Add +</button>
			<?php
			for ($x = 0; $x <= $feed_url_count; $x++) {

				echo '<p><input type="text" name="ers_feed_url[]" value="'.$feed_url[$x].'" class="regular-text loop-feed-input" /> <button type="button" class="r-btnRemove button button-secondary loop-feed-input-remove" style="display: inline-block;">Remove -</button></p>';

			}
			?>
			
	      	

	      		<div class="r-group">
	      			<p>
					<?php

						echo '<input type="text" name="ers_feed_url[]" value="" class="regular-text" />';

					?> <button type="button" class="r-btnRemove button button-secondary">Remove -</button>
					</p>
				

				
				 </div>
			</div>

		<?php

	}

	public function ers_author() {

		$ers_author = esc_attr(get_option('ers_author'));

		echo '<input type="number" name="ers_author" value="'.$ers_author.'"  />';
	
	}

	public function ers_cpt() {

		$ers_cpt = esc_attr(get_option('ers_cpt'));

		echo "<input type='text' name='ers_cpt' value='".$ers_cpt."'  />";
	}


	public function ers_settings_options() {
		echo 'Customize your Settings';
	}

	public function ers_menus_callback_function() {
		include_once ENSURE_RSS_FEED_PLUGIN_DIR.'/admin/partials/ensure-rss-feed-menu-display.php';
	}

	public function ers_menus_callback_function_css() {
		echo 'Custom CSS display';
	}


}
