<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/davesuy
 * @since      1.0.0
 *
 * @package    Ensure_Rss_Feed
 * @subpackage Ensure_Rss_Feed/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ensure_Rss_Feed
 * @subpackage Ensure_Rss_Feed/public
 * @author     Dave Ramirez <davesuywebmaster@gmail.com>
 */
class Ensure_Rss_Feed_Public {

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



	private $check_success;
	private $cpt;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->check_success = true;
		$this->cpt = "";

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ensure-rss-feed-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ensure-rss-feed-public.js', array( 'jquery' ), $this->version, false );

	}



	public function ensureRss() {


		$this->cpt = get_option('ers_cpt');

		if(!empty($this->cpt)) { 

			if(function_exists('fetch_feed')) {

				include_once(ABSPATH . WPINC . '/feed.php');
				include_once(ABSPATH . WPINC . '/class-simplepie.php'); 	

				$feed_url = get_option('ers_feed_url');	
				$ers_author = get_option('ers_author');

				// Includes the necessary files
				//$feed = fetch_feed($feed_url); 	// URL to the feed you want to show

				//echo '<pre>'.print_r($feed_url , true).'</pre>';

				//$urls = array('https://www.rzim.org/just-thinking/feed/','https://www.feedforall.com/blog-feed.xml');

				$urls = $feed_url;


					 
				$feed = new SimplePie();
				$feed->set_feed_url($urls);
				$ers_set_cache_location = ABSPATH . WPINC . '/SimplePie/Cache/cache';

				if (!file_exists($ers_set_cache_location)) {
				   mkdir($ers_set_cache_location, 0777, true);
				}

				$feed->set_cache_location($ers_set_cache_location);
				$feed->set_timeout(30);
				$feed->init();
				$feed->handle_content_type();
				//echo $feed->get_title();



				//$rss = fetch_feed($urls); 	// URL to the feed you want to show

		
				$maxitems = 0;

				if ( ! is_wp_error( $feed ) ) : // Checks that the object is created correctly

				    // Figure out how many total items there are, but limit it to 5. 
				    $maxitems = $feed->get_item_quantity( 100 ); 

				    // Build an array of all the items, starting with element 0 (first element).
				    $rss_items = $feed->get_items( 0, $maxitems );

				endif;



				//$limit = $feed->get_item_quantity(3); 								// How many items you wish to display
				//$items = $feed->get_items(0, $limit); 								// 0 is start and limit is noted above
			}

			if ( $maxitems == 0) {

				echo '<div>The feed is either empty or unavailable.</div>';

			} else {

				//$cc = get_post_meta(1055, 'publish_date', true);

				//echo '<pre>'.print_r($cc, true).'</pre>';

				//echo $this->get_cpt_by_cf("Wed, 26 Dec 2018 07:00:00 -0500"); 


				foreach ( $rss_items  as $item) {


					$title = $item->get_title();
					$content = $item->get_content();
					$post_author_id = $ers_author;
			
					$thumbnail = $item->get_item_tags('', 'image');
					$pubDate = $item->get_item_tags('', 'pubDate');

					$thumb_out = $thumbnail[0]["child"]['']['url'][0]['data'];
					$pubDate_out = $pubDate[0]['data'];

					// && $this->get_cpt_by_cf($pubDate_out) == false

					if (!get_page_by_title($title, OBJECT, $this->cpt) ) {

						$args_rss_insert_post = array(

							'post_title'=> $title, 
							'post_type'=> $this->cpt, 
							'post_content'=> $content,
							'post_status' => 'publish',
							'post_author' => $post_author_id,
							'meta_input'   => array(
	    						'publish_date' => $pubDate_out,
							),

						);

						$rss_insert_post = wp_insert_post($args_rss_insert_post);


						if(!empty($thumb_out)) {

							$this->Generate_Featured_Image( $thumb_out, $rss_insert_post);

						}

						if(!is_wp_error($rss_insert_post)) {
					  		
					  		$this->check_success = true;
						
						} else {
					  		
					  		$this->check_success = false;
									
							//there was an error in the post insertion, 

							$error_msg = $rss_insert_post->get_error_message();
								 
						}

					}

				}



				if($this->check_success == true) {
		  
					$this->custom_logs_success("Success");
				
				} 

				if($this->check_success == false) {
							
					//there was an error in the post insertion, 

					$this->custom_logs_failed($error_msg);
						 
				}

		


			}

		}
	}


	public function Generate_Featured_Image( $image_url, $post_id  ) {

		if(!empty($this->cpt)) { 

		    $upload_dir = wp_upload_dir();

		    $image_data = file_get_contents($image_url);
		    $filename = basename($image_url);
		    if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
		    else                                    $file = $upload_dir['basedir'] . '/' . $filename;
		    file_put_contents($file, $image_data);

		    $wp_filetype = wp_check_filetype($filename, null );
		    $attachment = array(
		        'post_mime_type' => $wp_filetype['type'],
		        'post_title' => sanitize_file_name($filename),
		        'post_content' => '',
		        'post_status' => 'inherit'
		    );
		    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
		    require_once(ABSPATH . 'wp-admin/includes/image.php');
		    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
		    $res2= set_post_thumbnail( $post_id, $attach_id );

		}

	}


	public function custom_logs_success($message) { 

		$upload = wp_upload_dir();
	    $upload_dir = $upload['basedir'];
	    $upload_dir = $upload_dir . '/just_thinking_log/success';


	    if (! file_exists($upload_dir)) {
	       mkdir( $upload_dir, 0700 );
	    }


	    if(is_array($message)) { 
	        $message = json_encode($message); 
	    } 
	    $file = fopen($upload_dir."/success_logs.log","a"); 
	    return fwrite($file,  date('Y-m-d h:i:s') . " :: " . $message."\r\n"); 
	    fclose($file); 

	}

	public function custom_logs_failed($message) { 

		$upload = wp_upload_dir();
	    $upload_dir = $upload['basedir'];
	    $upload_dir = $upload_dir . '/just_thinking_log/failed';


	    if (! file_exists($upload_dir)) {
	       mkdir( $upload_dir, 0700 );
	    }


	    if(is_array($message)) { 
	        $message = json_encode($message); 
	    } 
	    
	    $file = fopen($upload_dir."/failed_logs.log","a"); 
	    return fwrite($file, date('Y-m-d h:i:s') . " :: " . $message."\r\n"); 
	    fclose($file);  

	}

	public function get_cpt_by_cf($pubOut) {

		$args = array('post_type'=> $this->cpt, "meta_key" => "publish_date", "meta_value" => $pubOut, 'posts_per_page'   => -1);
		$pubDateposts = get_posts($args);


		if(count($pubDateposts) > 0) {
			return true;

		} else {

			return false;

		}


	}


}
