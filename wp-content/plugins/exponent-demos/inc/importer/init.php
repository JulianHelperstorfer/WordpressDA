<?php
	/**
 	* Plugin Name: BE one click import
 	* Plugin URI: http://www.brandexponents.com/
 	* Description: One click import
 	* Version: 3.0.1
 	* Author: BrandExponents
 	* Author URI: http://www.brandexponents.com/
 	* License: GPL2
 	*/
global $core;
//load admin theme data importer
if( !class_exists( 'ExponentDemoImporter' ) ) {
	class ExponentDemoImporter extends ExponentImporter {
		/**
		 * Holds a copy of the object for easy reference.
		 *
		 * @since 0.0.1
		 *
		 * @var object
		 */
		private static $instance;
		
		/**
		 * Set the key to be used to store theme options
		 *
		 * @since 0.0.2
		 *
		 * @var object
		 */

		public $colorhub_options_file_name = 'colorhub-data.json';

		public $typehub_options_file_name = 'typehub.json';
		
		public $widgets_file_name 		=  'widgets.json';
		
		public $content_demo_file_name  =  'content.xml';

		public $customizer_data_name = 'customizer.dat';

		public $demo_settings_name = 'demo_settings.json';

		public $tatsu_header_file_name = 'tatsu-header.json';

		public $tatsu_global_sections_file_name = 'tatsu-global-sections.json';

		public $wpforms_data_file_name = 'wpforms-data.json';

		/**
		 * Store the selected options from the dashboard
		 *
		 * @since 0.0.1
		 * @var object
		 */
		public $selected_demo_folder;



		/**
		 * Holds a copy of the widget settings 
		 *
		 * @since 0.0.2
		 *
		 * @var object
		 */
		public $widget_import_results;
		
		/**
		 * Constructor. Hooks all interactions to initialize the class.
		 *
		 * @since 0.0.1
		 */
		public function __construct() {
			$this->demo_files_path = EXP_DEMOS_PATH . '/inc/importer/demo-files/';
			self::$instance = $this;
			
			parent::__construct();
		}

		public function run() {
			add_action( 'wp_ajax_exponent_import_form', array($this, 'ajax_import'), 10, 1 );
			add_action( 'wp_ajax_exponent_import_theme_options', array($this, 'ajax_import_theme_options'), 10, 1 );
			add_action( 'wp_ajax_exponent_import_theme_widgets', array($this, 'ajax_import_theme_widgets'), 10, 1 );
			add_action( 'wp_ajax_exponent_set_home_page', array($this, 'ajax_set_home_page'), 10, 1 );
			add_action('wp_ajax_exponent_import_slider', array($this, 'import_slider'));
			add_action('wp_ajax_exponent_set_demo_content', array($this, 'ajax_set_demo_content'));
			add_action('wp_ajax_exponent_require_plugins', array($this, 'ajax_get_require_plugins'));
			add_action( 'wp_ajax_exponent_import_typehub_options', array($this, 'ajax_import_typehub_options'), 10, 1 );
			add_action( 'wp_ajax_exponent_import_tatsu_global_sections', array($this, 'ajax_import_tatsu_global_sections'), 10 );
		}

		public function ajax_import_theme_options(){
			$demo = $_POST['exp-demo'];
			$this->selected_demo_folder = $demo;
			$this->set_demo_theme_options();
			wp_die();
		}
		
		public function ajax_import_typehub_options(){
			$demo = $_POST['exp-demo'];
			$this->selected_demo_folder = $demo;
			$this->set_typehub_options();
			wp_die();
		}

		public function ajax_import_tatsu_global_sections() {
			$demo = $_POST['exp-demo'];
			$this->selected_demo_folder = $demo;
			$this->set_global_section_options();
			wp_die();
		}

		public function import_revslider($data = ''){
			$demo = $_POST['exp-demo'];
			if(class_exists('RevSliderSlider')) {
				$slider = new RevSlider();
				return $slider->importSliderFromPost(true, true, $data);
			} else {
				echo 'Failed to import slider data, Please make sure to install and activate Slider Revolution plugin first';
			}
			wp_die();
		}

		public function import_slider() {
			$demo = $_POST['exp-demo'];
	
			$sliders = self::get_settings($demo)['sliders'];
			foreach ($sliders as $type => $file) {
				$data = $this->demo_files_path.'/'.$file;
				if ($type == 'revslider' && file_exists($data)) {
					echo $this->import_revslider($data);
				}
			}
		}

		public function ajax_import_theme_widgets(){
			$demo = $_POST['exp-demo'];
			$this->selected_demo_folder = $demo;
			$this->process_widget_import_file();
		}

		public function ajax_set_demo_content() {
			$demo = $_POST['exp-demo'];
			if( isset( $_POST['data'] ) ) {
				$data = $_POST['data'];
			}
		    remove_image_size('exponent-blog-image');
		    remove_image_size('exponent-blog-image-with-aspect-ratio');
		    remove_image_size('2col-portfolio');
		    remove_image_size('2col-portfolio-masonry');
		    remove_image_size('3col-portfolio-wide-width-height');
		    remove_image_size('3col-portfolio-wide-width');
		    remove_image_size('2col-gallery');
		    remove_image_size('2col-gallery-masonry');
		    remove_image_size('3col-gallery-wide-width-height');
		    remove_image_size('3col-gallery-wide-width');
		    remove_image_size('portfolio');
		    remove_image_size('portfolio-masonry');
		    remove_image_size('gallery');
		    remove_image_size('gallery-masonry');
		    remove_image_size('tatsu_lazyload_thumb');
		    remove_image_size('exponent-carousel-thumb');
			parent::set_demo_data();
			$this->set_demo_menus($demo);
			wp_die();
		}

		public function ajax_import() {

		$demo = $_POST['exp-demo'];
		$data = $_POST['data'];
		$this->selected_demo_folder = $demo;
		$customize_data = $this->demo_files_path.$this->customizer_data_name;

		if(isset($data)) {
			
			foreach ($data as $key) {
				if(method_exists($this, $key)) {
					call_user_func(array($this, $key));
				}
			}
			$this->import_master_slider();
			$this->set_demo_menus();
			
		}
		parent::_import_customizer($customize_data);
		
		}
		/**
		 * Add menus
		 *
		 * @since 0.0.1
		 */
		public function set_demo_menus($demo) {
			// Menus to Import and assign - you can remove or add as many as you want
			$locations = array();
			$menus = self::get_settings($demo)['menus'];
			foreach ($menus as $location => $name) {
				$menu = wp_get_nav_menu_object($name);
				$locations[$location] = $menu->term_id; 
			}
			var_dump( $locations );
			set_theme_mod( 'nav_menu_locations', $locations);
			
		}

		public function check_settings($demo = '') {
			
			$available = [];
			$path = $this->demo_files_path.'/'.$demo.'/';
			$content = @file_get_contents($path.$this->demo_settings_name);
			$settings_file = json_decode($content, true);
			if(isset($settings_file['home_page_title']) ){
				$available['home_page'] = 1;
			} else {
				$available['home_page'] = 0;
			}
			
			if(isset($settings_file['sliders'])) {
				$available['slider_data'] = 1;
			} else {
				$available['slider_data'] = 0;
			}

			if(file_exists($path.$this->widgets_file_name)) {
				$available['widgets'] = 1;
			} else {
				$available['widgets'] = 0;
			}
			if( file_exists($path.$this->customizer_data_name) && class_exists('Colorhub') ) {
				$available['theme_option'] = 1;
			} else {
				$available['theme_option'] = 0;
			}
			if(file_exists($path.$this->typehub_options_file_name) && class_exists('Typehub') ) {
				$available['typehub_option'] = 1;
			} else {
				$available['typehub_option'] = 0;
			}
			if(file_exists($path.$this->tatsu_global_sections_file_name) && function_exists('tatsu_register_global_module') ) {
				$available['tatsu_global_section_option'] = 1;
			} else {
				$available['tatsu_global_section_option'] = 0;
			}
			if(file_exists($path.$this->content_demo_file_name)) {
				$available['content'] = 1;
			} else {
				$available['content'] = 0;
			}
			return "data-settings='".json_encode($available)."'";

		}

		public function get_settings($selected_demo = '') {
			if($selected_demo == '') {
				return;
			}
			$path = $this->demo_files_path.$this->demo_settings_name;
			
			$content = @file_get_contents($path);

			return json_decode($content, true);
		}

		public function ajax_set_home_page() {
			require( ABSPATH . '/wp-load.php' );
			$demo = $_POST['exp-demo'];
			$page_title = self::get_settings($demo)['home_page_title'];
			$blog_page_title = self::get_settings($demo)['blog_page_title'];
			$page = get_page_by_title(esc_html( $page_title ));
			$blog_page = get_page_by_title( $blog_page_title );
			if($page->ID) {
				update_option( 'show_on_front', 'page', true);
				$is_home_page_updated = update_option( 'page_on_front', $page->ID );
			} 
			if( $blog_page->ID ) {
				update_option( 'show_on_front', 'page', true);
				$is_blog_page_updated = update_option( 'page_for_posts', $blog_page->ID );
			}
			if( !$is_home_page_updated && !$is_blog_page_updated ) {
				printf('Faild to set %s as home page & %s as blog page please make sure to import the content first', $page_title, $blog_page_title );
			} elseif ( $is_home_page_update && !$is_blog_page_updated ) {
				printf('%s has been set as home page however failed to set %s as blog page', $page_title, $blog_page_title );
			} elseif ( !$is_home_page_update && $is_blog_page_updated ) {
				printf('Failed to set %s as home page however %s has been set as blog page', $page_title, $blog_page_title );
			} else {
				printf('%s page has been set as front page & %s has been set as blog page', $page_title, $blog_page_title);
			}

			//WPFORMS IMPORT
			$content_plugins = self::get_settings($demo)['content_plugins'];
			if(!empty($content_plugins) && isset($content_plugins['WPForms'])){
				$this->process_wpforms_import_file();
			}
			//SET HEADER AND FOOTER MENU IF NOT SET
			$demo_nav_menus = self::get_settings($demo)['nav_menus'];
			if(!empty($demo_nav_menus)){
				$current_nav_menus = tatsu_header_get_menu_list()[0];
				$diff_nav = array_diff_assoc($demo_nav_menus,$current_nav_menus);
				if(!empty($current_nav_menus) && !empty($diff_nav)){
					$replace_ids = array();
					$header_id = tatsu_get_active_header_id();
					$footer_id = tatsu_get_active_footer_id();
					$header_content = '';
					$footer_content = '';
					if(!empty($header_id)){
						$header_page = get_post( $header_id );
						if(!empty($header_page)){
							$header_content = $header_page->post_content;
						}
					}
					if(!empty($footer_id)){
						$footer_page = get_post( $footer_id );
						if(!empty($footer_page)){
							$footer_content = $footer_page->post_content;
						}
					}
				   if(!empty($header_content) || !empty($footer_content)){
					foreach ($demo_nav_menus as $key_id => $nav_menu) {
						$key_replace = array_search($nav_menu,$current_nav_menus);
						if($key_replace !== false && $key_id != $key_replace){
							$replace_ids[$key_id]=$key_replace;
							$demo_menu_name = 'menu_name= "'.$key_id.'"';
							$current_menu_name = 'menu_name="'.$key_replace.'"';
							$header_content = str_ireplace($demo_menu_name,$current_menu_name,$header_content);
							$footer_content = str_ireplace($demo_menu_name,$current_menu_name,$footer_content);
						}
					}
					if(!empty($header_id) && !empty($header_content)){
						$header_post = array(
							'ID'           => $header_id,
							'post_content' => $header_content,
						);
						wp_update_post( $header_post );
						$title = 'Header';
						printf('%s menu has been set', $title);
					}
					if(!empty($footer_id) && !empty($footer_content)){
						$footer_post = array(
							'ID'           => $footer_id,
							'post_content' => $footer_content,
						);
						wp_update_post( $footer_post );
						$title = 'Footer';
						printf('%s menu has been set', $title);
					}
				
				   }
				}
			}
			
			//Set woocommerce shop page
			$shop_page_title = self::get_settings($demo)['shop_page_title']; 
			if(!empty($shop_page_title)){ 
				$shop_page = get_page_by_title(esc_html( $shop_page_title ));
				if(!empty($shop_page->ID)){
					if(update_option( 'woocommerce_shop_page_id', $shop_page->ID )){
						printf('%s page has been set as shop page', $shop_page_title);
						$my_account_page_title = self::get_settings($demo)['my_account_page_title']; 
						$cart_page_title = self::get_settings($demo)['cart_page_title']; 
						$checkout_page_title = self::get_settings($demo)['checkout_page_title'];
						//My account page
						if(!empty($my_account_page_title)){
							$myaccount_page = get_page_by_title(esc_html( $my_account_page_title ));
							if(!empty($myaccount_page->ID)){
								if(update_option( 'woocommerce_myaccount_page_id', $myaccount_page->ID )){
									printf('%s page has been set as my account page', $my_account_page_title);
								}else{
									printf('Faild to set %s as my account page', $my_account_page_title);
								}
							}
						}
						
						///Cart page
						if(!empty($cart_page_title)){
							$cart_page = get_page_by_title(esc_html( $cart_page_title ));
							if(!empty($cart_page->ID)){
								if(update_option( 'woocommerce_cart_page_id', $cart_page->ID )){
									printf('%s page has been set as cart page', $cart_page_title);
								}else{
									printf('Faild to set %s as cart page', $cart_page_title);
								}
							}
					    }
						//checkout page
						if(!empty($checkout_page_title)){
							$checkout_page = get_page_by_title(esc_html( $checkout_page_title ));
							if(!empty($checkout_page->ID)){
								if(update_option( 'woocommerce_checkout_page_id', $checkout_page->ID )){
									printf('%s page has been set as checkout page', $checkout_page_title);
								}else{
									printf('Faild to set %s as checkout page', $checkout_page_title);
								}
							}
						}
					
						//SET woocommerce_settings options if provided
						$woocommerce_settings = self::get_settings($demo)['woocommerce_settings']; 
						if(!empty($woocommerce_settings)){ 
							foreach ($woocommerce_settings as $option_name => $option_value) {
								update_option( $option_name, $option_value );
							}
						}

					}else{
						printf('Faild to set %s as shop page', $shop_page_title);
					}
				}
			}
			wp_die();
		}
		public function ajax_get_require_plugins(){
			$demo = $_POST['exp-demo'];
			$ret = '';
			$require_plugins = self::get_settings($demo)['content_plugins'];
			$plugins = [];
			if(is_array($require_plugins) && sizeof($require_plugins) >= 1) {
				foreach ($require_plugins as $plugin => $pluginName) {
					if(!is_plugin_active( $plugin.'/'.$plugin.'.php' ) && !class_exists($plugin)) {
						$plugins[] = $pluginName;
					}
				}
				if(sizeof($plugins) >= 1) {
					$ret = '{"stat":"0", "plugins":'.json_encode(array_values($plugins)).'}';
				} else {
					$ret = '{"stat":"1"}';
				}
				
			} else {
				$ret = '{"stat":"1"}';
			}
			wp_send_json( $ret, null );		
			wp_die();
		}
	}
}

function exponent_demos_importer_tpl() {
	$radium = new ExponentDemoImporter();
	echo $radium->demo_installer();
}
add_action( 'exp_import_tpl', 'exponent_demos_importer_tpl', 30, 1 );
?>