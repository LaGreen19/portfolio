<?php
/**
 * Plugin Name: Portfolio Manager Lite
 * Plugin URI: http://OTWthemes.com 
 * Description: Portfolio Manager for WordPress adds tons of portfolio functionality in terms of layout variation, styling options, content re-arrangement, etc. to your WordPress based website. Upload portfolio items in a custom post type. Create as many portfolio lists as you like. This plugin comes with 8 templates to choose from. Select list content, modify layout and style your list to get the content and the look you want. Use the list’s shortcode or a widget to place your lists anywhere in your site – WYSIWYG editor of your page/post, any sidebar, template files.
 * Author: OTWthemes.com
 * Version: 1.12
 * Author URI: http://themeforest.net/user/OTWthemes
 */
	// Directory Separator
	if( !defined( 'DS' ) ){
		define( 'DS', '/' );
	}
	
	if( !defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER_LITE' ) ){
		define( 'OTW_PLUGIN_PORTFOLIO_MANAGER_LITE', 1 );
	}
	
	// Full map
	define( 'OTW_PML_SERVER_PATH', dirname(__FILE__) );
	// Namespace for translation
	define( 'OTW_PML_TRANSLATION', 'otw-portfolio-manager-lite' );
	
	// Plugin Folder Name
	if( function_exists( 'plugin_basename' ) ){
		define( 'OTW_PML_PATH', preg_replace( "/\/otw\_portfolio\_manager\_lite\.php$/", '', plugin_basename( __FILE__ ) ) );
	}else{
		define( 'OTW_PML_PATH', 'otw-portfolio-manager-lite' );
	}
	
	$otw_pml_plugin_url = plugin_dir_url( __FILE__);
	$otw_pml_js_version = '0.2';
	$otw_pml_css_version = '0.2';
	
	load_plugin_textdomain( OTW_PML_TRANSLATION ,false,dirname(plugin_basename(__FILE__)) . '/languages/');
	
	$upload_dir = wp_upload_dir();
	
	if( isset( $upload_dir['basedir'] ) ){
		define( 'SKIN_PML_URL', set_url_scheme( $upload_dir['baseurl'] ).DS.'otwpm'.DS.'skins'.DS );
		define( 'SKIN_PML_PATH', $upload_dir['basedir'].DS.'otwpm'.DS.'skins'.DS );
		define( 'UPLOAD_PML_PATH', $upload_dir['basedir'].DS );
	}else{
		define( 'SKIN_PML_URL',  plugins_url(). DS . 'wp-content' . DS . 'uploads'. DS .'otwpm'. DS .'skins' . DS );
		define( 'SKIN_PML_PATH', $_SERVER['DOCUMENT_ROOT'] . DS . 'wp-content'. DS .'uploads' . DS .'otwpm'. DS . 'skins' .DS );
		define( 'UPLOAD_PML_PATH', $_SERVER['DOCUMENT_ROOT'] . DS . 'wp-content'. DS .'uploads' . DS );
	}
	
	//components
	$otw_pml_image_component = false;
	$otw_pml_image_object = false;
	$otw_pml_image_profile = false;
	$otw_pml_page_shortcode_component = false;
	$otw_pml_page_shortcode_object = false;
	$otw_pml_form_component = false;
	
	//load core component functions
	@include_once( 'include/otw_components/otw_functions/otw_functions.php' );
	
	if( !function_exists( 'otw_register_component' ) ){
		wp_die( 'Please include otw components' );
	}
	
	//register image component
	otw_register_component( 'otw_image', dirname( __FILE__ ).'/include/otw_components/otw_image/', '/include/otw_components/otw_image/' );
	
	//register form component
	otw_register_component( 'otw_form', dirname( __FILE__ ).'/include/otw_components/otw_form/', $otw_pml_plugin_url.'include/otw_components/otw_form/' );
	
	//register shortcode component
	otw_register_component( 'otw_shortcode', dirname( __FILE__ ).'/include/otw_components/otw_shortcode/', $otw_pml_plugin_url.'include/otw_components/otw_shortcode/' );
	
if( !class_exists('OTWPortfolioManagerLite') ){
	
	class OTWPortfolioManagerLite{
		
		// Query Class Instance
		public $otwPMQuery = null;
		
		// CSS Class Instance
		public $otwCSS = null;
		
		// Tempalte Dispatcher
		public $otwDispatcher = null;
		
		public $fontsArray = null;
		
		// Validation errors array
		public $errors = null;
		
		// Form data on error
		public $errorData = null;
		
		public $portfolio_post_type = 'otw_pm_portfolio';
		
		public $portfolio_category = 'otw_pm_portfolio_category';
		
		public $portfolio_tag = 'otw_pm_portfolio_tag';
		
		public $menu_parent = 'edit.php?post_type=otw_pm_portfolio';
		
		public $otw_pm_plugin_options = array();
		
		public $portfolio_meta_details = array(
			'1' => 'Skills Needed',
			'2' => 'Date',
			'3' => 'Project URL',
			'4' => 'Copyright',
			'5' => 'Client',
			'6' => 'Client URL',
		);
		
		/**
		  * Initialize plugin
		  */
		public function __construct(){
		
			// Create an instance of the OTWPMQuery Class
			$this->otwPMQuery = new OTWPMLQuery();
			
			$this->otwPMQuery->portfolio_category  = $this->portfolio_category;
			$this->otwPMQuery->portfolio_tag  = $this->portfolio_tag;
			$this->otwPMQuery->portfolio_post_type  = $this->portfolio_post_type;
			
			$this->otwCSS = new OTWPMLCss();
			$this->otwDispatcher = new OTWPMLDispatcher();
			$this->otwDispatcher->portfolio_category  = $this->portfolio_category;
			$this->otwDispatcher->portfolio_tag  = $this->portfolio_tag;
			$this->otwDispatcher->portfolio_post_type  = $this->portfolio_post_type;
			$this->otwDispatcher->portfolio_templates = array(
				'single-portfolio-media-left' => __( 'Media Left (default)', OTW_PML_TRANSLATION )
			);
			
			$this->otw_pm_plugin_options = get_option( 'otw_pm_plugin_options' );
			$this->otwDispatcher->otw_pm_plugin_options = $this->otw_pm_plugin_options;
			
			require_once( 'include' . DS . 'fonts.php' );
			$this->fontsArray = json_decode($allFonts);
			
			// Add Admin Menu only if role is Admin
			if( is_admin() ) {
				
				// Save and redirect are done before any headers are loaded
				$this->saveAction();
				
				// Add Admin Assets
				add_action( 'admin_init', array($this, 'register_resources') );
				
				// Add Admin menu
				add_action( 'admin_menu', array($this, 'register_menu') );
				
				// Add Meta Box 
				add_action( 'add_meta_boxes', array($this, 'pm_meta_boxes'), 10, 2 );
				
				// Save Meta Box Data
				add_action( 'save_post', array($this, 'pm_save_meta_box') );
				
				//return select 2 options
				add_action( 'wp_ajax_otw_pml_select2_options', array($this, 'get_select2_options') );
			}
			
			add_action('init', array($this, 'load_resources') );
			
			register_activation_hook( __FILE__, array( $this, 'flush_rewrites' ) );
			
			// Load Short Code
			add_shortcode( 'otw-pm-list', array($this, 'pm_list_shortcode') );
			
			// Include Widgets Functionality
			add_action( 'widgets_init', array($this, 'pm_register_widgets') );
			
			// Enque template JS and CSS files
			add_action( 'wp_enqueue_scripts', array($this, 'register_fe_resources') );
			
			// Ajax FE Actions - Load More Pagination
			add_action( 'wp_ajax_get_pm_posts', array($this, 'otw_pm_get_posts') );
			add_action( 'wp_ajax_nopriv_get_pm_posts', array($this, 'otw_pm_get_posts') );
			
			// Ajax FE Social Share
			add_action( 'wp_ajax_pm_social_share', array($this, 'otw_pm_social_share') );
			add_action( 'wp_ajax_nopriv_pm_social_share', array($this, 'otw_pm_social_share') );
			
			//Ajax actions fro videos
			add_action( 'wp_ajax_otw_pm_get_video', array($this, 'otw_pm_get_video') );
			add_action( 'wp_ajax_nopriv_otw_pm_get_video', array($this, 'otw_pm_get_video') );
			
			add_action( 'template_redirect', array( $this, 'otw_pm_portfolio_template' ) );
			
			add_filter( 'get_post_metadata', array( $this, 'otw_pm_portfolio_thumbnail_metadata' ), 99, 5 );
			add_filter( 'post_thumbnail_html', array( $this, 'otw_pm_portfolio_thumbnail' ), 99, 5 );
			
			if( is_admin() ){
				add_filter('manage_'.$this->portfolio_post_type.'_posts_columns', array( $this, 'portfolio_table_head' ) );
				add_action( 'admin_notices', array( $this, 'portofolio_lite_message' ) );
			}
		}//end of construct
		
		/**
		 * Lite plugin message
		 */
		public function portofolio_lite_message(){
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			if( function_exists( 'get_current_screen' ) ){
				
				$screen = get_current_screen();
				
				if( isset( $screen->id ) && strlen( $screen->id ) ){
					$requested_page = $screen->id;
				}
				
				if( preg_match( "/otw\-pml\-details$/", $requested_page ) || preg_match( "/otw\-pml\-add$/", $requested_page ) || preg_match( "/otw\-pml\-custom\-templates$/", $requested_page ) || preg_match( "/otw\-pml$/", $requested_page ) || preg_match( "/otw\-pml\-settings$/", $requested_page ) || ( $this->portfolio_post_type == $requested_page ) || ( 'edit-'.$this->portfolio_post_type == $requested_page ) || ( 'edit-'.$this->portfolio_category == $requested_page ) || ( 'edit-'.$this->portfolio_tag == $requested_page ) ){
					include_once( 'views/otw_portfolio_manager_lite_help.php' );
				}
			}
		}
		
		/**
		 * saveAction - Validate form and save + redirect
		 * @return void
		*/
		public function saveAction(){
		
			if( !empty( $_POST ) && isset($_POST['submit-otw-pml-copy']) ){
				$this->errors = null;
				
				// Get Current Items in the DB
				$otw_pm_list = $this->otwPMQuery->getLists();
				
				if( empty( $_POST['id'] ) || !isset( $otw_pm_list['otw-pm-list'] ) || !isset( $otw_pm_list['otw-pm-list']['otw-pm-list-'.$_POST['id'] ] )){
					$this->errors['source_list_name'] = __('Source Portfolio List Not found', OTW_PML_TRANSLATION);
				}
				
				if( empty( $_POST['list_name'] ) ){
					$this->errors['list_name'] = __('New Portfolio List Name is Required', OTW_PML_TRANSLATION);
				}
				
				// Errors have been detected persist data
				if( !empty( $this->errors ) ){
					$this->errorData = $_POST;
					return null;
				}
				
				if( isset( $otw_pm_list['otw-pm-list']['next_id'] ) ){
					
					$otw_pm_list['otw-pm-list'][ 'otw-pm-list-'.$otw_pm_list['otw-pm-list']['next_id'] ] = $otw_pm_list['otw-pm-list']['otw-pm-list-'.$_POST['id'] ];
					$otw_pm_list['otw-pm-list'][ 'otw-pm-list-'.$otw_pm_list['otw-pm-list']['next_id'] ]['id'] = $otw_pm_list['otw-pm-list']['next_id'];
					$otw_pm_list['otw-pm-list'][ 'otw-pm-list-'.$otw_pm_list['otw-pm-list']['next_id'] ]['list_name'] = $_POST['list_name'];
					$otw_pm_list['otw-pm-list'][ 'otw-pm-list-'.$otw_pm_list['otw-pm-list']['next_id'] ]['edit'] = false;
					$otw_pm_list['otw-pm-list'][ 'otw-pm-list-'.$otw_pm_list['otw-pm-list']['next_id'] ]['date_created'] = date('Y/m/d');
					$otw_pm_list['otw-pm-list'][ 'otw-pm-list-'.$otw_pm_list['otw-pm-list']['next_id'] ]['user_id'] = get_current_user_id();
					
					$source_customCssFile = SKIN_PML_PATH . DS .'otw-pm-list-'.$_POST['id'].'-custom.css';
					$customCssFile = SKIN_PML_PATH . DS .'otw-pm-list-'.$otw_pm_list['otw-pm-list']['next_id'].'-custom.css';
					
					if( file_exists( $source_customCssFile ) ){
						
						$css_file_content = file_get_contents( $source_customCssFile );
						
						$css_file_content = str_replace( 'otw-pm-list-'.$_POST['id'], 'otw-pm-list-'.$otw_pm_list['otw-pm-list']['next_id'], $css_file_content );
						
						file_put_contents( $customCssFile, $css_file_content );
					}
					$otw_pm_list['otw-pm-list']['next_id']++;
					update_option( 'otw_pm_lists', $otw_pm_list );
					
					$this->redirect('admin.php?page=otw-pml&success=true');
					exit;
				}
			}else if( !empty( $_POST ) && isset($_POST['submit-otw-pml']) ){
				
				$this->errors = null;
				
				// Check if Portfolio List Name is present
				if( empty( $_POST['list_name'] ) ) {
					$this->errors['list_name'] = __('Portfolio List Name is Required', OTW_PML_TRANSLATION);
				}
				// Check if Protfolio List Template is present
				if( empty( $_POST['template'] ) || $_POST['template'] === 0 ) {
					$this->errors['template'] = __('Please select a Portfolio List Template', OTW_PML_TRANSLATION);
				}
				
				//Check Selection of content: Category OR Tag OR Author
				if( 
					( empty( $_POST['categories'] ) && empty( $_POST['tags'] ) && empty( $_POST['users'] ) ) &&
					( empty( $_POST['all_categories'] ) && empty( $_POST['all_tags'] ) && empty( $_POST['all_users'] ) )
				) {
					$this->errors['content'] = __('Please select a Category or Tag or Author.', OTW_PML_TRANSLATION);
				}
            
                              // Add dates ( created / modified ) to current post
                              if( empty( $_POST['date_created'] ) && empty( $this->errors ) ) {
                                $_POST['date_created'] = $_POST['date_modified'] = date('Y/m/d');
                              }
            
                              // Update modified if post is edited
                              if( !empty( $_POST['id'] ) ) {
                                // Inject Date Modified into $_POST
                                $_POST['date_modified'] = date('Y/m/d');
                              }
            
                              /** 
                               * If select All functionality is used, adjust the POST
                               */
                              if( !empty( $_POST['all_categories'] ) ) {
                                $_POST['categories'] = $_POST['all_categories'];
                              }
                              if( !empty( $_POST['all_tags'] ) ) {
                                $_POST['tags'] = $_POST['all_tags'];
                              }
                              if( !empty( $_POST['all_users'] ) ) {
                                $_POST['users'] = $_POST['all_users'];
                              }
            
                              // Errors have been detected persist data
                              if( !empty( $this->errors ) ) {
                                $this->errorData = $_POST;
                                return null;
                              }
            
                              // This is a new list get the ID
                              if( empty( $_POST['edit'] ) &&  empty( $this->errors ) ) {
                                $otw_pm_lists = $this->otwPMQuery->getLists();
            
                                // This is the first list generated
                                if( empty( $otw_pm_lists ) ) {
                                  $_POST['id'] = 1;
                                } else {
                                  $_POST['id'] = $otw_pm_lists['otw-pm-list']['next_id'];
                                }
                              }
            
                              // Assign $_POST to variable in order to fill form on error / edit
                              include( 'include' . DS . 'content.php' );
                              
                              foreach( $_POST as $key => $value ){
                            		$content[ $key ] = $value;
                              }
                              
            
                              /**
                              * Create Custom CSS file for inline styles such as: Title, Meta Items, Excpert, Continue Reading
                              */
                              $customCssFile = SKIN_PML_PATH . DS .'otw-pm-list-'.$_POST['id'].'-custom.css';
            
                              // Make sure all the older CSS rules are deleted in order for a fresh save
                              if( file_exists( $customCssFile ) ) {
                                file_put_contents( $customCssFile, '');
                              }
            
                              // Write Custom CSS
                              $this->otwCSS->writeCSS( str_replace('\\', '', $_POST['custom_css']),  $customCssFile );
                              
              
                              $borderStyles = array(
                            	    'border-style' => (!empty($_POST['border-style']))? $_POST['border-style'] : '',
                            	    'border-size' => (!empty($_POST['border-size']))? $_POST['border-size'] : '',
                            	    'border-color' => (!empty($_POST['border-color']))? $_POST['border-color'] : '',
                            	    'container'   => '#otw-pm-list-'.$_POST['id'].' .with-border'
                              );
                              
                              $this->otwCSS->buildCSS( $borderStyles, $customCssFile );
                              
                             $backgroundStyles = array(
                            	    'background-color' => (!empty($_POST['background-color']))? $_POST['background-color'] : '',
                            	    'container'   => '#otw-pm-list-'.$_POST['id'].' .with-bg'
                              );
                              
                              $this->otwCSS->buildCSS( $backgroundStyles, $customCssFile );
                              
                              // Get Current Items in the DB
                              $otw_pm_list = $this->otwPMQuery->getLists();
            
                              // Create new entry 
                              $otw_pm_list_data['otw-pm-list'][ 'otw-pm-list-' . $_POST['id'] ] = $_POST;
                              
							$default_empty_fields = array( 'show-social-icons-facebook', 'show-social-icons-twitter', 'show-social-icons-googleplus', 'show-social-icons-linkedin', 'show-social-icons-pinterest' );
							
							foreach( $default_empty_fields as $f_key ){
								
								if( !array_key_exists( $f_key, $otw_pm_list_data['otw-pm-list'][ 'otw-pm-list-' . $_POST['id'] ] ) ){
									$otw_pm_list_data['otw-pm-list'][ 'otw-pm-list-' . $_POST['id'] ][ $f_key ] = 0;
								}
							}
            
            
                              // We setup the next_id value. This will apply to the first save only
                              if( empty($otw_pm_list['otw-pm-list']['next_id']) && empty( $_POST['edit'] ) ) {
                                // We assume this is the first save with ID = 1, next ID has to be 2. Count starts from 1 because of short-code
                                $otw_pm_list_data['otw-pm-list']['next_id'] = 2;      
                              } elseif ( empty( $_POST['edit'] ) ) {
                                $otw_pm_list['otw-pm-list']['next_id'] = $otw_pm_list['otw-pm-list']['next_id'] + 1;
                                $otw_pm_list_data['otw-pm-list']['next_id'] =  $otw_pm_list['otw-pm-list']['next_id'];
                              }
            
                              // Merge the 2 arrays
                              if ( $otw_pm_list === false || empty( $otw_pm_list ) ) {
                                $listData = $otw_pm_list_data;
                              } elseif ( !empty($otw_pm_list) ) {
                                // Do not remove the ['otw-pm-list'] from they array_merge. There is a strange behavior related to this
                                $listData['otw-pm-list'] = array_merge( $otw_pm_list['otw-pm-list'], $otw_pm_list_data['otw-pm-list'] );
                              }
            
                              // Update
                              if( empty($this->errors) ) {
                                
                                // Get $widget from included file
                                include( 'include' . DS . 'content.php' );
            
                                if( in_array( $_POST['template'], $widgets) ) {
                                  // It's a widget
                                  $listData['otw-pm-list'][ 'otw-pm-list-' . $_POST['id'] ]['widget'] = 1;
                                } else {
                                  // It's NOT a Widget
                                  $listData['otw-pm-list'][ 'otw-pm-list-' . $_POST['id'] ]['widget'] = 0;
                                }
            
                                update_option( 'otw_pm_lists', $listData );
                                
                                $this->redirect('admin.php?page=otw-pml-add&action=edit&otw-pm-list-id='.$_POST['id'].'&success=true');
                                exit;
                                
                              } // End update
            
                            } // End if (!empty($_POST))
                            
		}//end of save action
		
		/**
		  * Load Widget Class
		  * Init Widget Class
		  */
		public function pm_register_widgets () {
			register_widget( 'OTWPML_Widget' );
		}
		
		/**
		  * Add components
		  */
		public function load_resources(){
			
			global $otw_pml_image_component, $otw_pml_image_profile, $otw_pml_image_object, $otw_pml_plugin_url, $otw_pml_js_version, $otw_pml_css_version, $otw_pml_page_shortcode_component, $otw_pml_page_shortcode_object;
			
			$this->register_portfolio_custom_types();
			
			//form component
			$otw_pml_form_component = otw_load_component( 'otw_form' );
			$otw_pml_form_object = otw_get_component( $otw_pml_form_component );
			$otw_pml_form_object->js_version = $otw_pml_js_version;
			$otw_pml_form_object->css_version = $otw_pml_css_version;
			
			include_once( plugin_dir_path( __FILE__ ).'include/otw_labels/otw_pml_form_object.labels.php' );
			$otw_pml_form_object->init();
			
			//shortcode component
			$otw_pml_page_shortcode_component = otw_load_component( 'otw_shortcode' );
			$otw_pml_page_shortcode_object = otw_get_component( $otw_pml_page_shortcode_component );
			$otw_pml_page_shortcode_object->js_version = $otw_pml_js_version;
			$otw_pml_page_shortcode_object->css_version = $otw_pml_css_version;
			
			$otw_pml_page_shortcode_object->add_default_external_lib( 'css', 'style', get_stylesheet_directory_uri().'/style.css', 'live_preview', 10 );
			
			if( class_exists( 'OTWPMLQuery' ) ){
				$otw_pml_page_shortcode_object->shortcodes['portfolio_manager'] = array('title' => __('Portfolio Manager', OTW_PML_TRANSLATION ), 'enabled' => true, 'children' => false, 'order' => 134, 'parent' => false, 'path' => dirname(__FILE__) . '/include/otw_components/otw_shortcode/', 'url' => $otw_pml_plugin_url . 'include/otw_components/otw_shortcode/');
			}
			
			include_once( plugin_dir_path( __FILE__ ).'include/otw_labels/otw_pml_page_shortcode_object.labels.php' );
			$otw_pml_page_shortcode_object->init();
			
			
			$otw_pml_image_component = otw_load_component( 'otw_image' );
			
			$otw_pml_image_object = otw_get_component( $otw_pml_image_component );
			
			$otw_pml_image_object->init();
			
			$img_location = wp_upload_dir();
			
			$otw_pml_image_profile = $otw_pml_image_object->add_profile( $img_location['basedir'].'/', $img_location['baseurl'].'/', 'otwpm' );
			
			$this->otw_rewrite_rules();
			
			$this->process_actions();
		}//end load resourses
		
		/**
		 * Activation hook
		*/
		public function flush_rewrites(){
			$this->register_portfolio_custom_types();
			flush_rewrite_rules();
		}
		
		/**
		 * Register custom post type and taxonomies
		 */
		public function register_portfolio_custom_types(){
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			if( !taxonomy_exists( $this->portfolio_category ) ){
				
				$labels = array(
					'name' => _x( 'Portfolio Categories', '',OTW_PML_TRANSLATION ),
					'singular_name' => _x( 'Portfolio Category', '', OTW_PML_TRANSLATION ),
					'search_items' =>  __( 'Search Portfolio Category', OTW_PML_TRANSLATION ),
					'all_items' => __( 'All Portfolio Category', OTW_PML_TRANSLATION ),
					'parent_item' => __( 'Parent Portfolio Category', OTW_PML_TRANSLATION ),
					'parent_item_colon' => __( 'Parent Portfolio Category:', OTW_PML_TRANSLATION ),
					'edit_item' => __( 'Edit Portfolio Category', OTW_PML_TRANSLATION ),
					'update_item' => __( 'Update Portfolio Category', OTW_PML_TRANSLATION ),
					'add_new_item' => __( 'Add New Portfolio Category', OTW_PML_TRANSLATION ),
					'new_item_name' => __( 'New Portfolio Category Name', OTW_PML_TRANSLATION ),
					'menu_name' => __( 'Portfolio Categories', OTW_PML_TRANSLATION ),
					'separate_items_with_commas' => __( 'Separate categories with commas', OTW_PML_TRANSLATION ),
					'add_or_remove_items' => __( 'Add or remove categories', OTW_PML_TRANSLATION ),
					'choose_from_most_used' => __( 'Choose from the most used categories', OTW_PML_TRANSLATION ),
					'popular_items' => __( 'Popular Categories', OTW_PML_TRANSLATION )
				);
				
				$args = array(
					'hierarchical' => true,
					'labels' => $labels,
					'poblic' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'exclude_from_search' => false,
					'query_var' => true,
					'has_archive'       =>  true,
					'rewrite' => 1,
					'show_admin_column' => true
				);
				
				$otw_pm_plugin_options = get_option( 'otw_pm_plugin_options' );
				
				if( isset( $otw_pm_plugin_options['otw_pm_portfolio_category_slug'] ) && strlen( trim( $otw_pm_plugin_options['otw_pm_portfolio_category_slug'] ) ) ){
					$args['rewrite'] = array( 'slug' => $otw_pm_plugin_options['otw_pm_portfolio_category_slug'], 'with_front' => true );
				}
				register_taxonomy( $this->portfolio_category , array( $this->portfolio_post_type ), $args );
			}
			
			if( !taxonomy_exists( $this->portfolio_tag ) ){
				
				$labels = array(
					'name' => _x( 'Portfolio Tags', '',OTW_PML_TRANSLATION ),
					'singular_name' => _x( 'Portfolio Tag', '', OTW_PML_TRANSLATION ),
					'search_items' =>  __( 'Search Portfolio Tag', OTW_PML_TRANSLATION ),
					'all_items' => __( 'All Portfolio Tag', OTW_PML_TRANSLATION ),
					'parent_item' => __( 'Parent Portfolio Tag', OTW_PML_TRANSLATION ),
					'parent_item_colon' => __( 'Parent Portfolio Tag:', OTW_PML_TRANSLATION ),
					'edit_item' => __( 'Edit Portfolio Tag', OTW_PML_TRANSLATION ),
					'update_item' => __( 'Update Portfolio Tag', OTW_PML_TRANSLATION ),
					'add_new_item' => __( 'Add New Portfolio Tag', OTW_PML_TRANSLATION ),
					'new_item_name' => __( 'New Portfolio Tag Name', OTW_PML_TRANSLATION ),
					'menu_name' => __( 'Portfolio Tags', OTW_PML_TRANSLATION ),
					'separate_items_with_commas' => __( 'Separate tags with commas', OTW_PML_TRANSLATION ),
					'add_or_remove_items' => __( 'Add or remove tags', OTW_PML_TRANSLATION ),
					'choose_from_most_used' => __( 'Choose from the most used tags', OTW_PML_TRANSLATION ),
					'popular_items' => __( 'Popular Tags', OTW_PML_TRANSLATION )
				);
				
				$args = array(
					'hierarchical' => false,
					'labels' => $labels,
					'poblic' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'exclude_from_search' => false,
					'query_var' => true,
					'has_archive'       =>  true,
					'rewrite' => 1,
					'show_admin_column' => true
				);
				
				$otw_pm_plugin_options = get_option( 'otw_pm_plugin_options' );
				
				if( isset( $otw_pm_plugin_options['otw_pm_portfolio_tag_slug'] ) && strlen( trim( $otw_pm_plugin_options['otw_pm_portfolio_tag_slug'] ) ) ){
					$args['rewrite'] = array( 'slug' => $otw_pm_plugin_options['otw_pm_portfolio_tag_slug'] );
				}
				
				register_taxonomy( $this->portfolio_tag , array( $this->portfolio_post_type ), $args );
			}
			
			if( !post_type_exists( $this->portfolio_post_type ) ){
				
				$labels = array(
					'name' => _x( 'Portfolio Items', 'post type general name', OTW_PML_TRANSLATION ),
					'singular_name' => _x( 'PM Portfolio Item', 'post type singular name', OTW_PML_TRANSLATION ),
					'add_new' => _x( 'Add New Item', 'slide', OTW_PML_TRANSLATION ),
					'add_new_item' => __( 'Add New Item', OTW_PML_TRANSLATION ),
					'edit_item' => __( 'Edit Portfolio Item', OTW_PML_TRANSLATION ),
					'new_item' => __( 'New Portfolio Item', OTW_PML_TRANSLATION ),
					'view_item' => __( 'View Portfolio Item', OTW_PML_TRANSLATION ),
					'search_items' => __( 'Search Portfolio Items', OTW_PML_TRANSLATION ),
					'menu_name' => __( 'Portfolio Manager Lite', OTW_PML_TRANSLATION ),
					'all_items' => __( 'Portfolio Items', OTW_PML_TRANSLATION ),
					'not_found' =>  __( 'No portfolio items found', OTW_PML_TRANSLATION ),
					'not_found_in_trash' => __( 'No portfolio items found in Trash', OTW_PML_TRANSLATION ),
					'parent_item_colon' => ''
				);
				
				$args = array(
					'labels' => $labels,
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'query_var' => true,
					'rewrite' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_icon' => plugins_url() . DS . OTW_PML_PATH . DS .'assets'. DS .'img'. DS .'menu_icon.png',
					'menu_position' => null, 
					'has_archive' => true, 
					'taxonomies' => array( $this->portfolio_category, $this->portfolio_tag ), 
					'supports' => array( 'title','editor','excerpt' )
				);
				
				$otw_pm_plugin_options = get_option( 'otw_pm_plugin_options' );
				
				if( isset( $otw_pm_plugin_options['otw_pm_portfolio_slug'] ) && strlen( trim( $otw_pm_plugin_options['otw_pm_portfolio_slug'] ) ) ){
					
					$args['rewrite'] = array( 'slug' => $otw_pm_plugin_options['otw_pm_portfolio_slug'] );
				}
				
				register_post_type( $this->portfolio_post_type, $args );
			}
			
			$details = get_option( 'otw_pm_portfolio_details' );
			
			if( !is_array( $details ) ){
				
				$details = array();
				
				$order = 0;
				foreach( $this->portfolio_meta_details as $id => $title ){
					
					$details[ $id ] = array( 'id' => $id, 'title' => $title, 'order' => $order );
					$order++;
				}
				update_option( 'otw_pm_portfolio_details', $details );
			}
		}//end register custom post types
		
		function otw_rewrite_rules(){
			
			$last_update = get_option( 'otw_pml_rewrite_rules' );
			
			if( !$last_update || ( $last_update < strtotime( 'now -24 hours' ) ) ){
				flush_rewrite_rules();
				$last_update = time();
				update_option( 'otw_pml_rewrite_rules', $last_update );
			}
		}
		
		public function otw_pm_social_share () {
			
			include( 'social-shares.php' );
			
			if(isset($_POST['url']) && $_POST['url'] != '' && filter_var($_POST['url'], FILTER_VALIDATE_URL)){
				$url = $_POST['url'];
				$otw_social_shares = new otw_social_shares($url);
				
				echo $otw_social_shares->otw_get_shares();
			} else {
				echo json_encode(array('info' => 'error', 'msg' => 'URL is not valid!'));
			}
			exit;
		}
		
		private function process_actions(){
			
			global $otw_pml_validate_messages, $wpdb;
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			if( isset( $_POST['otw_pml_action'] ) ){
				
				switch( $_POST['otw_pml_action'] ){
					
					case 'import_from_light':
							
							if( isset( $_POST['cancel'] ) ){
								
								wp_redirect( 'admin.php?page=otw-pml-settings' );
								
							}elseif( isset( $_POST['submit'] ) ){
								
								//first import categories
								$light_categories = get_terms( 'otw-portfolio-category', array( 'number' => '', 'hide_empty' => false  )  );
								$exists_categories = get_terms( $this->portfolio_category, array( 'number' => '', 'hide_empty' => false  ) );
								
								$exists_categories_assoc = array();
								
								if( is_array( $exists_categories ) && count( $exists_categories ) ){
									
									foreach( $exists_categories as $exists_cat ){
										$exists_categories_assoc[ $exists_cat->name ] = $exists_cat;
									}
								}
								
								$cat_to_cat = array();
								
								$cat_to_cat = $this->import_light_categories( $light_categories, $exists_categories_assoc, $cat_to_cat, 0 );
								
								
								$args = array(
									'post_type' => 'otw-portfolio'
								);
								$light_items = new WP_Query( $args );
								
								if( isset( $light_items->posts ) && count( $light_items->posts ) ){
									
									foreach( $light_items->posts as $light_post ){
										
										//first check if we already imported this post by id in meta data
										$new_post_args = array();
										
										$exists_args = array(
											'post_type' => $this->portfolio_post_type,
											'meta_query' => array(
												array(
													'key' => 'otw_pm_light_id',
													'value' => $light_post->ID
												)
											)
										);
										$existsPosts = new WP_Query( $exists_args );
										
										if( isset( $existsPosts->posts ) && ( count( $existsPosts->posts ) == 1 ) ){
											foreach( $existsPosts->posts as $exists_post ){
												$new_post_args['ID'] = $exists_post->ID;
												break;
											}
										}
										
										$new_post_args['post_content'] = $light_post->post_content;
										$new_post_args['post_title'] = $light_post->post_title;
										$new_post_args['post_name'] = $light_post->post_name;
										$new_post_args['post_excerpt'] = $light_post->post_excerpt;
										$new_post_args['post_status'] = $light_post->post_status;
										$new_post_args['post_author'] = $light_post->post_author;
										$new_post_args['post_date'] = $light_post->post_date;
										$new_post_args['post_date_gmt'] = $light_post->post_date_gmt;
										$new_post_args['post_modified'] = $light_post->post_modified;
										$new_post_args['post_modified_gmt'] = $light_post->post_modified_gmt;
										$new_post_args['post_type'] = $this->portfolio_post_type;
										
										$new_post_id = wp_insert_post( $new_post_args );
										
										if( $new_post_id ){ //insert meta for reference
											add_post_meta( $new_post_id, 'otw_pm_light_id', $light_post->ID, true );
											
											$light_meta_data = get_post_meta( $light_post->ID );
											
											if( isset( $light_meta_data['custom_otw-portfolio-repeatable-image'] ) ){
											
												$custom_repeatable = unserialize( $light_meta_data['custom_otw-portfolio-repeatable-image'][0] );
												
												$otw_new_meta_data = array(
													'media_type'      => '',
													'youtube_url'     => '',
													'vimeo_url'       => '',
													'soundcloud_url'  => '',
													'img_url'         => '',
													'slider_url'      => ''
												);
												
												if( is_array( $custom_repeatable ) && $crSize = count( $custom_repeatable ) ){
												
													$image_string = '';
													foreach ( $custom_repeatable as $custom_image ){
														
														$image_url = wp_get_attachment_image_src($custom_image, 'otw-porfolio-large');
														$image_string .= $image_url[0].',';
													}
													$image_string = substr( $image_string, 0, -1 );
													
													if( $crSize == 1 ){
														$otw_new_meta_data['media_type'] = 'img';
														$otw_new_meta_data['img_url'] = $image_string;
														
													}else{
														$otw_new_meta_data['media_type'] = 'slider';
														$otw_new_meta_data['slider_url'] = $image_string;
													}
													
													add_post_meta( $new_post_id, 'otw_pm_meta_data', $otw_new_meta_data, true);
													// If POST is in the DB update it
													update_post_meta( $new_post_id, 'otw_pm_meta_data', $otw_new_meta_data);
												}else{
													delete_post_meta( $new_post_id, 'otw_pm_meta_data'  );
												}
												
												
											}else{
												delete_post_meta( $new_post_id, 'otw_pm_meta_data'  );
											}
											
											//save details
											$portfolio_meta_details = $this->get_details();
											
											$key_name = '';
											foreach( $portfolio_meta_details as $detail_id => $detail_data ){
											
												if( $detail_data['id'] == '6' ){
													$key_name = 'otw_pm_portfolio_detail_'.$detail_id;
												}
											}
											
											if( $key_name ){
												
												if( isset( $light_meta_data['custom_otw-portfolio-url'] ) && !empty( $light_meta_data['custom_otw-portfolio-url'][0] ) ){
													add_post_meta( $new_post_id, $key_name, $light_meta_data['custom_otw-portfolio-url'][0], true);
													update_post_meta( $new_post_id, $key_name, $light_meta_data['custom_otw-portfolio-url'][0] );
												}else{
													delete_post_meta( $new_post_id, $key_name );
												}
											}
											
											//save categories
											$new_categories = wp_get_post_terms( $new_post_id, $this->portfolio_category );
											$light_categories = wp_get_post_terms( $light_post->ID, 'otw-portfolio-category' );
											
											$cats_to_add = array();
											
											if( is_array( $light_categories ) && count( $light_categories ) ){
											
												foreach( $light_categories as $light_cat ){
												
													if( isset( $cat_to_cat[ $light_cat->term_id ] ) ){
														$cats_to_add[] = $cat_to_cat[ $light_cat->term_id ];
													}
												}
											}
											
											
											wp_set_post_terms( $new_post_id, $cats_to_add, $this->portfolio_category, false );
										}
									}
								}
								wp_redirect( 'admin.php?page=otw-pml-settings&message=3' );
							}
						break;
				}
			}
		}//end process actions
		
		/**
		  * Add Styles and Scripts needed by the Admin interface
		*/
		public function register_resources () {
			
			global $otw_pml_plugin_url, $otw_pml_js_version, $otw_pml_css_version, $otw_pml_shortcode_component, $otw_pml_shortcode_object;
			
			if( !function_exists( 'wp_enqueue_media' ) ) {
				wp_enqueue_media(); //WP 3.5 media uploader
			}
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			//check the skin folder
			$upload_dir = wp_upload_dir();
			
			if( isset( $upload_dir['basedir'] ) && is_writable( $upload_dir['basedir'] ) && !is_dir( SKIN_PML_PATH ) ){
				
				if( !is_dir( $upload_dir['basedir'].DS.'otwpm' ) ){
					mkdir( $upload_dir['basedir'].DS.'otwpm' );
				}
				if( is_dir( $upload_dir['basedir'].DS.'otwpm' ) && !is_dir( SKIN_PML_PATH ) ){
					mkdir( SKIN_PML_PATH );
				}
			}
			
			add_action('admin_print_styles', array( $this, 'enqueue_admin_styles' ) );
			add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		
		}//end register resourse
		
		/**
		 * Add Menu To WP Backend
		 * This menu will be available only for Admin users
		 */
		public function register_menu(){
		
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			add_submenu_page( $this->menu_parent, __('Portfolio Manager Lists', OTW_PML_TRANSLATION), __('Portfolio Lists', OTW_PML_TRANSLATION), 'manage_options', 'otw-pml', array( $this , 'pm_list' ) );
			$hook_suffix_0 = add_submenu_page( $this->menu_parent, __('Portfolio Manager | Add List', OTW_PML_TRANSLATION), __('Add New List', OTW_PML_TRANSLATION), 'manage_options', 'otw-pml-add', array( $this , 'pm_add' ) );
			$hook_suffix_1 = add_submenu_page( __FILE__, __('Portfolio Manager | Duplicate List', OTW_PML_TRANSLATION), __('Duplicated List', OTW_PML_TRANSLATION), 'manage_options', 'otw-pml-copy', array( $this , 'pm_copy' ) );
			add_submenu_page( $this->menu_parent, __('Portfolio Manager | Options', OTW_PML_TRANSLATION), __('Options', OTW_PML_TRANSLATION), 'manage_options', 'otw-pml-settings', array( $this , 'pm_settings' ) );
			
			add_action( 'load-' . $hook_suffix_0 , array( $this, 'open_pm_menu' ) );
			add_action( 'load-' . $hook_suffix_1 , array( $this, 'open_pm_menu' ) );
		}//end register menu
		
		public function open_pm_menu(){
			
			global $menu, $submenu;
			
			foreach( $menu as $key => $item ){
				
				if( $item[2] == $this->menu_parent ){
					$menu[ $key ][4] = $menu[ $key ][4].' wp-has-submenu wp-has-current-submenu wp-menu-open menu-top otw-pm-menu-open current';
					
					if( function_exists( 'get_current_screen' ) ){
					
						$screen = get_current_screen();
						
						if( preg_match( "/otw\-pm\-add$/", $screen->base ) && isset( $submenu[ $this->menu_parent ] ) && isset( $_GET['action'] ) && ( $_GET['action'] == 'edit' ) ){
							foreach( $submenu[ $this->menu_parent ] as $s_key => $s_data ){
								
								if( $s_data[2] == 'otw-pm' ){
									$submenu[ $this->menu_parent ][ $s_key ][4] = 'current';
								}
							}
						}
					}
				}
			}
		}//end open menu
		
		public function enqueue_admin_styles( $requested_page ){
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			if( function_exists( 'get_current_screen' ) ){
				
				$screen = get_current_screen();
				
				if( isset( $screen->id ) && strlen( $screen->id ) ){
					$requested_page = $screen->id;
				}
			}
			
			if( preg_match( "/otw\-pml\-add$/", $requested_page ) || preg_match( "/otw\-pml$/", $requested_page ) || preg_match( "/otw\-pml\-settings$/", $requested_page ) || ( $this->portfolio_post_type == $requested_page ) ){
				wp_register_style( 'otw-pm-admin-color-picker', plugins_url() . DS . OTW_PML_PATH . DS . 'assets'.DS.'css'.DS.'colorpicker.css' );
				wp_register_style( 'otw-pm-admin-pm-default', plugins_url() . DS . OTW_PML_PATH . DS . 'assets'.DS.'css'.DS.'otw-portfolio-list-default.css' );
				wp_register_style( 'otw-pm-admin-pm-select2', plugins_url() . DS . OTW_PML_PATH . DS . 'assets'.DS.'css'.DS.'select2.css' );
				wp_enqueue_style( 'otw-pm-admin-color-picker' );
				wp_enqueue_style( 'otw-pm-admin-pm-default' );
				wp_enqueue_style( 'otw-pm-admin-pm-select2' );
			}
			
			if( preg_match( "/otw\-pml/", $requested_page ) || preg_match( "/otw\-pml\-/", $requested_page ) || preg_match( "/otw\-pml\-settings$/", $requested_page ) || ( $this->portfolio_post_type == $requested_page ) || ( 'edit-'.$this->portfolio_post_type == $requested_page ) || ( 'edit-'.$this->portfolio_category == $requested_page ) || ( 'edit-'.$this->portfolio_tag == $requested_page ) ){
				wp_register_style( 'otw-pml-admin', plugins_url() . DS . OTW_PML_PATH . DS . 'assets'.DS.'css'.DS.'otw-portfolio-manager-lite.css' );
				wp_enqueue_style( 'otw-pml-admin' );
			}
			
		}//end admin styles
		
		public function enqueue_admin_scripts( $requested_page ){
			
			global $otw_pml_plugin_url, $otw_pml_js_version;
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			if( function_exists( 'get_current_screen' ) ){
				
				$screen = get_current_screen();
				
				if( isset( $screen->id ) && strlen( $screen->id ) ){
					$requested_page = $screen->id;
				}
			}
			
			if( preg_match( "/otw\-pml\-add$/", $requested_page ) || preg_match( "/otw\-pml$/", $requested_page ) || preg_match( "/otw\-pml\-settings$/", $requested_page ) || ( $this->portfolio_post_type == $requested_page ) ){
				
				// Get ALL categories to be used in SELECT 2
				$categoriesData     = array();
				$catCount = 0;
				
				// Get ALL tags to be used in SELECT 2
				$tagsData           = array();
				$tagCount           = 0;
				
				// Get ALL users (Authors)
				$usersData          = array();
				$userCount          = 0;
				
				$pagesData          = array();
				$pageCount          = 0;
				
				$messages = array(
					'delete_confirm'  => __('Are you sure you want to delete ', OTW_PML_TRANSLATION),
					'modal_title'     => __('Select Images', OTW_PML_TRANSLATION),
					'modal_btn'       => __('Add Image', OTW_PML_TRANSLATION)
				);
				
				wp_register_script( 'otw-pm-admin-colorpicker', plugins_url() . DS . OTW_PML_PATH . DS . 'assets'.DS.'js'.DS.'plugins'.DS.'colorpicker.js', array('jquery') );
				wp_register_script( 'otw-pm-admin-select2', plugins_url() . DS . OTW_PML_PATH . DS . 'assets'.DS.'js'.DS.'plugins'.DS.'select2.js', array('jquery') );
				wp_register_script( 'otw-pm-admin-variables', plugins_url() . DS . OTW_PML_PATH . DS . 'assets'.DS.'js'.DS.'otw-admin-pm-variables.js' );
				wp_register_script( 'otw-pm-admin-functions', plugins_url() . DS . OTW_PML_PATH . DS . 'assets'.DS.'js'.DS.'otw-admin-pm-functions.js' );
				wp_register_script( 'otw-pm-admin-fonts', plugins_url() . DS . OTW_PML_PATH . DS . 'assets'.DS.'js'.DS.'fonts.js' );
				
				// Custom Scripts + Plugins
				wp_enqueue_script( 'otw-pm-admin-colorpicker' );
				wp_enqueue_script( 'otw-pm-admin-select2' );
				wp_enqueue_script( 'otw-pm-admin-otwpmpreview' );
				wp_enqueue_script( 'otw-pm-admin-fonts');
				wp_enqueue_script( 'otw-pm-admin-functions');
				wp_enqueue_script( 'otw-pm-admin-variables');
				
				// Core Scripts
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-draggable' );
				wp_enqueue_script( 'jquery-ui-droppable' );
				wp_enqueue_script( 'jquery-ui-accordion' );
				wp_enqueue_script( 'jquery-ui-tabs' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				
				wp_localize_script( 'otw-pm-admin-functions', 'categories', json_encode( $categoriesData ) );
				wp_localize_script( 'otw-pm-admin-functions', 'tags', json_encode( $tagsData ) );
				wp_localize_script( 'otw-pm-admin-functions', 'users', json_encode( $usersData ) );
				wp_localize_script( 'otw-pm-admin-functions', 'pages', json_encode( $pagesData ) );
				wp_localize_script( 'otw-pm-admin-functions', 'messages', json_encode( $messages ) );
				
				wp_localize_script( 'otw-pm-admin-functions', 'frontendURL', plugins_url() . DS . OTW_PML_PATH . DS . 'frontend/' );
			}
			
		}//end enque scripts
		
		function otw_pm_portfolio_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ){
		
			if( get_post_type( $post_id ) == $this->portfolio_post_type ){
				$html = $this->otwDispatcher->getArchiveMedia( $post_id );
			
			}
			return $html;
		}
		
		/**
		 * Modify portfolio listing table header
		*/
		public function portfolio_table_head( $columns ){
		
			$old_columns = $columns;
			
			$columns = array();
			
			foreach( $old_columns as $c_key => $c_value ){
				
				$columns[ $c_key ] = $c_value;
				
				if( $c_key == 'title' ){
					$columns['author'] = __( 'Author', OTW_PML_TRANSLATION );
				}
			}
			return $columns;
		}
		
		/**
		  * Save Meta Box Data
		  * @param $post_id - int - Current POST ID beeing edited
		*/
		function pm_save_meta_box( $post_id ){
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
				return;
			}
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			if( !empty( $_POST ) && !empty( $_POST['otw-pm-list-media_type']) ){
				$otw_meta_data = array(
					'media_type'      => $_POST['otw-pm-list-media_type'],
					'youtube_url'     => $_POST['otw-pm-list-youtube_url'],
					'vimeo_url'       => $_POST['otw-pm-list-vimeo_url'],
					'soundcloud_url'  => $_POST['otw-pm-list-soundcloud_url'],
					'img_url'         => $_POST['otw-pm-list-img_url'],
					'slider_url'      => $_POST['otw-pm-list-slider_url']
				);
				
				/**
				 * Add Custom POST Meta Data
				 * If POST is found in the DB it will just be ignored and return FALSE
				*/
				add_post_meta($post_id, 'otw_pm_meta_data', $otw_meta_data, true);
				
				// If POST is in the DB update it
				update_post_meta($post_id, 'otw_pm_meta_data', $otw_meta_data);
			}elseif( !empty( $_POST ) && isset( $_POST['otw-pm-list-media_type']) ){
				delete_post_meta($post_id, 'otw_pm_meta_data');
			}
			
			if( !empty( $_POST ) && !empty( $_POST['otw_pm_meta_details']) ){
			
				$otw_details_meta = array();
				
				$portfolio_meta_details = $this->get_details();
				
				foreach( $portfolio_meta_details as $detail_id => $detail_data ){
					
					$key_name = 'otw_pm_portfolio_detail_'.$detail_id;
					
					if( isset( $_POST[ $key_name ] ) && strlen( trim( $_POST[ $key_name ] ) ) ){
						
						add_post_meta($post_id, $key_name, $_POST[ $key_name ], true);
						update_post_meta($post_id, $key_name, $_POST[ $key_name ] );
					}else{
						delete_post_meta($post_id, $key_name );
					}
				}
			}
			
		}//end save meta box
		
		/**
		 * Add Meta Boxes
		*/
		public function pm_meta_boxes(){
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			// Add Support for POSTS
			add_meta_box(
				'otw-pm-meta-box',
				__('OTW Portfolio Manager Media Item', OTW_PML_TRANSLATION),
				array($this, 'otw_portfolio_manager_media_meta_box'),
				$this->portfolio_post_type,
				'normal',
				'default'
			);
			
			add_meta_box(
				'otw-pm-details-meta-box',
				__('OTW Portfolio Details', OTW_PML_TRANSLATION),
				array($this, 'otw_portfolio_manager_details_meta_box'),
				$this->portfolio_post_type,
				'normal',
				'default'
			);
			
		}//end meta boxes
		
		/**
		  * Add Custom HTML Meta Box on portfolio items
		  */
		public function otw_portfolio_manager_media_meta_box( $post ){
			$otw_pm_meta_data = get_post_meta( $post->ID, 'otw_pm_meta_data', true );
			require_once( 'views'. DS .'otw_portfolio_manager_lite_meta_box.php' );
		}
		
		public function otw_pm_portfolio_thumbnail_metadata( $value, $post_id, $meta_key, $single ){
			
			if( ( get_post_type( $post_id ) == $this->portfolio_post_type ) && ( $meta_key == '_thumbnail_id' ) ){
				
				if( is_archive() ){
					
					$otw_portfolio_meta_data = get_post_meta( $post_id, 'otw_pm_meta_data', true );
					
					if( count( $otw_portfolio_meta_data ) ){
						return 1;
					}
				}
			}
			return $value;
		}
		
		/**
		  * Add Custom HTML Meta Box on portfolio items
		  */
		public function otw_portfolio_manager_details_meta_box( $post ){
			
			$portfolio_meta_details = $this->get_details();
			
			require_once( 'views'. DS .'otw_portfolio_manager_lite_details_meta_box.php' );
		}
		
		/**
		  * Get details and prepare the result
		  */
		public function get_details(){
		
			$otw_details = get_option( 'otw_pm_portfolio_details' );
			
			if( is_array( $otw_details ) && count( $otw_details ) ){
				
				foreach( $otw_details as $otw_detail_key => $otw_detail_key_data ){
				
					if( !array_key_exists( $otw_detail_key, $this->portfolio_meta_details ) ){
						unset( $otw_details[ $otw_detail_key ] );
					}
				}
			}
			
			if( is_array( $otw_details ) && count( $otw_details ) ){
				
				uasort( $otw_details, array( $this, 'sort_details' ) );
				
			}else{
				$otw_details = array();
			}
			
			return $otw_details;
		}//end get details
		
		/**
		 * Sort details
		 */
		public function sort_details( $a, $b ){
			
			if( $a['order'] > $b['order'] ){
				return 1;
			}elseif( $a['order'] < $b['order'] ){
				return -1;
			}elseif( $a['id'] > $b['id'] ){
				return 1;
			}elseif( $a['id'] < $b['id'] ){
				return -1;
			}
			return 0;
			
		}
		
		/*
		* Single portfolio template
		*/
		public function otw_pm_portfolio_template(){
			
			
			global $post;;
			
			if( is_single() && !is_archive() && isset( $post->post_type ) && ( $post->post_type == $this->portfolio_post_type ) ){	
				
				$this->otwDispatcher->otw_custom_templates = $this->get_custom_templates();
				
				$this->otwDispatcher->buildPortfolioTemplate( $post, $this->get_details() );
			}
		}
		
		/**
		 * Load Resources for FE - CSS and JS
		 */
		public function register_fe_resources () {
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			$uniqueHash = wp_create_nonce("otw_pm_social_share"); 
			$socialShareLink = admin_url( 'admin-ajax.php?action=pm_social_share&nonce='. $uniqueHash );
			
			wp_register_script( 'otw-pm-flexslider', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'js'. DS .'jquery.flexslider.min.js', array( 'jquery' ) );
			wp_register_script( 'otw-pm-infinitescroll', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'js'. DS .'jquery.infinitescroll.min.js', array( 'jquery' ) );
			wp_register_script( 'otw-pm-isotope', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'js'. DS .'isotope.pkgd.min.js', array( 'jquery' ) );
			wp_register_script( 'otw-pm-pixastic', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'js'. DS .'pixastic.custom.min.js', array( 'jquery' ) );
			wp_register_script( 'otw-pm-fitvid', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'js'. DS .'jquery.fitvids.js', array( 'jquery' ) );
			wp_register_script( 'otw-pm-main-script', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'js'. DS .'script.js', array( 'jquery', 'jquery-ui-accordion', 'jquery-ui-tabs' ), '', true );
			
			// Custom Scripts + Plugins
			wp_enqueue_script( 'otw-pm-flexslider' );
			wp_enqueue_script( 'otw-pm-infinitescroll' );
			wp_enqueue_script( 'otw-pm-isotope' );
			wp_enqueue_script( 'otw-pm-pixastic' );
			wp_enqueue_script( 'otw-pm-fitvid' );
			wp_enqueue_script( 'otw-pm-main-script' );
			
			$otw_js_labels = array( 'otw_pm_loading_text' =>__( 'Loading items...', OTW_PML_TRANSLATION ),
			    'otw_pm_no_more_posts_text' => __( 'No More Items Found', OTW_PML_TRANSLATION )
			);
			wp_localize_script( 'otw-pm-main-script', 'otw_pm_js_labels', $otw_js_labels ); 
			wp_localize_script( 'otw-pm-main-script', 'socialShareURL', $socialShareLink ); 
			
			wp_register_style( 'otw-pm-default', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'css'. DS .'default.css' );
			wp_register_style( 'otw-pm-font-awesome', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'css'. DS .'font-awesome.min.css' );
			wp_register_style( 'otw-pm-pm', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'css'. DS .'otw-portfolio-manager.css' );
			wp_register_style( 'otw-pm-grid', plugins_url() . DS . OTW_PML_PATH . DS .'frontend'. DS .'css'. DS .'otw-grid.css' );
			
			if( is_file( SKIN_PML_PATH . DS . 'custom.css' ) ){
				wp_register_style( 'otw-pm-custom', SKIN_PML_URL .'custom.css'  );
			}
			
			wp_enqueue_style( 'otw-pm-grid' );
			wp_enqueue_style( 'otw-pm-pm' );
			wp_enqueue_style( 'otw-pm-font-awesome' );
			wp_enqueue_style( 'otw-pm-default' );
			
			if( is_file( SKIN_PML_PATH . DS . 'custom.css' ) ){
				wp_enqueue_style( 'otw-pm-custom' );
			}
		}//end register fe resourses
		
		/**
		  * Get custom templates
		*/
		public function get_custom_templates(){
			
			$otw_custom_templates = array();
			
			return $otw_custom_templates;
		}
		
		/**
		 * OTW Portfolio Manager List Page
		 */
		public function pm_list () {
			$action = $_GET;
			
			// Check if writing permissions
			$writableCssError = $this->check_writing( SKIN_PML_PATH );
			$writableError    = $this->check_writing( UPLOAD_PML_PATH );
			
			$otw_pm_lists = get_option( 'otw_pm_lists' );
			
			if( !empty( $action['action'] ) && $action['action'] === 'delete' ) {
				$list_id = $_GET['otw-pm-list-id'];
				$item = 'otw-pm-list-'.$list_id;
				
				unset( $otw_pm_lists['otw-pm-list'][ $item ] );
				
				update_option( 'otw_pm_lists', $otw_pm_lists );
				
			}
			require_once('views' . DS . 'otw_portfolio_manager_lite_list.php');
		}
		
		/**
		 * Check Writing Permissions
		*/
		public function check_writing( $path ) {
		
			$writableCssError = false;
			if( !is_writable( $path ) ) {
				$writableCssError = true;
			}
			
			return $writableCssError;
		}
		
		/**
		 * OTW Portfolio Manager Settings Page
		*/
		public function pm_settings () {
			$customCss = '';
			$cssPath = SKIN_PML_PATH . DS . 'custom.css';
			
			// Check if writing permissions
			$writableCssError = $this->check_writing( SKIN_PML_PATH );
			
			// Open File for edit
			if( empty( $_POST ) && !$writableCssError  ) {
				
				if( file_exists( $cssPath ) ){
					$customCss = file_get_contents( $cssPath );
				}else{
					$customCss = '';
				}
			}
			
			// Save File on disk and redirect.
			if( !empty( $_POST ) && isset( $_POST['otw_pm_save_settings'] ) && ( $_POST['otw_pm_save_settings'] == 1 ) )
			{
				$otw_pm_plugin_options = get_option( 'otw_pm_plugin_options' );
				
				$portfolio_settings = array( 'otw_pm_template', 'otw_pm_social_icons', 'otw_pm_prev_next_nav', 'otw_pm_related_posts', 'otw_pm_portfolio_slug', 'otw_pm_portfolio_category_slug', 'otw_pm_portfolio_tag_slug', 'otw_pm_description_title_text', 'otw_pm_details_title_text', 'otw_pm_related_title_text', 'otw_pm_archive_media_width', 'otw_pm_archive_media_height', 'otw_pm_archive_media_format','otw_pm_item_media_width', 'otw_pm_item_media_height', 'otw_pm_item_media_format', 'otw_pm_related_media_width', 'otw_pm_related_media_height', 'otw_pm_related_media_format' ,'otw_pm_related_posts_number', 'otw_pm_item_title', 'otw_pm_moreinfo_title_text', 'otw_pm_media_lightbox', 'otw_pm_item_media_lightbox_width', 'otw_pm_item_media_lightbox_height', 'otw_pm_item_media_lightbox_format', 'otw_pm_social_title_text', 'otw_pm_related_posts_criteria', 'otw_pm_grid_pages' );
				
				foreach( $portfolio_settings as $setting_key )
				{
					$otw_pm_plugin_options[ $setting_key ] = '';
					
					if( isset( $_POST[ $setting_key ] ) ){
						$otw_pm_plugin_options[ $setting_key ] = $_POST[ $setting_key ];
					}
				}
				
				update_option( 'otw_pm_plugin_options', $otw_pm_plugin_options );
				
				$customCSS = str_replace('\\', '', $_POST['otw_css']);
				file_put_contents( $cssPath, $customCSS );
				echo "<script>window.location = 'admin.php?page=otw-pml-settings&success_css=true';</script>";
				die;
			}
			
			$import_from_light = post_type_exists( 'otw-portfolio' );
			
			require_once('views' . DS . 'otw_portfolio_manager_lite_settings.php');
		}
		
		/**
		 * OTW Portfolio Manager Add / Edit Page
		*/
		public function pm_add () {
		
			// Default Values 
			// $content and $widgets
			include( 'include' . DS . 'content.php' );
			
			$default_content = $content;
			
			// Edit field - used to determin if we are on an edit or add action
			$edit = false;
			
			// Reload $_POST data on error
			if( !empty( $this->errors ) ) {
				$content = $this->errorData;
			}
			
			// Edit - Load Values for current list
			if( !empty($_GET['otw-pm-list-id']) ) {
				$listID = (int) $_GET['otw-pm-list-id'];
				$nextID = $listID;
				
				$edit = true;
				$content = $this->otwPMQuery->getItemById( $listID );
				
				foreach( $default_content as $content_key => $content_value ){
					
					if( !array_key_exists( $content_key, $content ) ){
						$content[ $content_key ] = $content_value;
					}
				}
			}
			
			// Make manipulations to the $content in order to be used in the UI
			if( !empty( $content ) ) {
				// Replace escaping \ in order to display in textarea
				
				$content['custom_css'] = str_replace('\\', '', $content['custom_css']);
				
				// Select All functionality, remove all items from the list if Select All is used
				// We use this approach in order not to show any items in the text field if select all is used
				if( !empty( $content['all_categories'] ) ) { $content['categories'] = ''; }
				if( !empty( $content['all_tags'] ) ) { $content['tags'] = ''; }
				if( !empty( $content['all_users'] ) ) { $content['users'] = ''; }
				
				if( !array_key_exists('select_categories' , $content ) ) { $content['select_categories'] = ''; }
				if( !array_key_exists('select_tags' , $content ) ) { $content['select_tags'] = ''; }
				if( !array_key_exists('select_users' , $content ) ) { $content['select_users'] = ''; }
				
			}
			
			require_once('views' . DS . 'otw_portfolio_manager_lite_add_list.php');
		}
		
		public function redirect( $location ){
			
			header("Location: $location" );
			
			return true;
		}
		
		/**
		 * Load Lists on the Front End using short code
		 * @param $attr - array
		 */
		public function pm_list_shortcode( $attr ) {
			
			if( defined( 'OTW_PLUGIN_PORTFOLIO_MANAGER' ) ){
				return;
			}
			
			$listID = $attr['id'];
			
			// Get Current Items in the DB
			$otw_pm_options = $this->otwPMQuery->getItemById( $listID );
			
			if( !empty( $otw_pm_options ) ) {
			
				// Enqueue Custom Styles CSS
				
				if( file_exists(SKIN_PML_PATH . DS . 'otw-pm-list-'.$listID.'-custom.css') ) {
					
					wp_register_style( 'otw-pm-custom-css-'.$listID, SKIN_PML_URL .'otw-pm-list-'.$listID.'-custom.css' );
					wp_enqueue_style( 'otw-pm-custom-css-'.$listID );
					
				}
				
				// Load $templateOptions - array
				include('include' . DS . 'content.php');
				
				$currentPage = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				
				if( is_array( $content ) ){
					
					foreach( $content as $opt_name => $opt_value ){
						
						if( !isset( $otw_pm_options[ $opt_name ] ) ){
							$otw_pm_options[ $opt_name ] = $opt_value;
						}
					}
				}
				
				$otw_posts_result = $this->otwPMQuery->getPosts( $otw_pm_options, $currentPage );
				
				return $this->otwDispatcher->generateTemplate( $otw_pm_options, $otw_posts_result, $templateOptions );
				
			} else {
				
				$errorMsg = '<p>';
				$errorMsg .= __('Woops, we have encountered an error. The List you are trying to use can not be found: ', OTW_PML_TRANSLATION);
				$errorMsg .= 'otw-pm-list-'.$attr['id'].'<br/>';
				$errorMsg .= '</p>';
				 return $errorMsg;
			}
		}
		
		public function otw_pm_get_posts () {
			// Load $templateOptions - array
			include('include' . DS . 'content.php');
			
			$otw_pm_options = $this->otwPMQuery->getItemById( $_GET['post_id'] );
			$otw_pm_results = $this->otwPMQuery->getPosts( $otw_pm_options, $_GET['page'] );
			
			$paginationPageNo = (int) $_GET['page'] + 1;
			
			if( !empty($otw_pm_results->posts) ) {
				echo $this->otwDispatcher->generateTemplate( $otw_pm_options, $otw_pm_results, $templateOptions, true, $paginationPageNo );
			} else {
				echo ' ';  
			}
			exit;
		}
		
		public function otw_pm_get_video(){
		
			$post_id = 0;
			
			if( isset( $_GET['post_id'] ) && intval( $_GET['post_id'] ) ){
				$post_id = $_GET['post_id'];
				$list_id = 0;
				
				$view_type = '';
				
				if( isset( $_GET['vr'] ) && (  $_GET['vr'] == 'item_media' ) ){
					$view_type =  $_GET['vr'];
				}
				elseif( isset( $_GET['vr'] ) && (  $_GET['vr'] == 'list_media' ) ){
				
					if( isset( $_GET['list_id'] ) && intval( $_GET['list_id'] ) ){
						$list_id = $_GET['list_id'];
						$view_type =  $_GET['vr'];
					}
				}
				
				if( $post_id ){
					$post = get_post( $post_id );
					
					if( isset( $post->ID ) && ( $post->post_type == $this->portfolio_post_type ) ){
						
						$postMetaData = get_post_meta( $post->ID, 'otw_pm_meta_data', true );
						
						if( $view_type == 'item_media' ){
							
							$this->otwDispatcher->otwImageCrop = new OTWPMImageCropLite();
							
							$videoWidth = '1024';
							$videoHeight = '640';
							
							$item_options = get_post_meta( $post->ID, 'otw_pm_options_meta_data', true );
							
							if( isset( $item_options['otw_pm_options_type'] ) && ( $item_options['otw_pm_options_type'] == 'custom' ) ){
								
								if( isset( $item_options['options'] ) ){
								
									if( isset( $item_options['options']['otw_pm_item_media_lightbox_width'] ) && strlen( $item_options['options']['otw_pm_item_media_lightbox_width'] ) ){
										$videoWidth = $item_options['options']['otw_pm_item_media_lightbox_width'];
									}
									
									if( isset( $item_options['options']['otw_pm_item_media_lightbox_height'] ) && strlen( $item_options['options']['otw_pm_item_media_lightbox_height'] ) ){
										$videoHeight = $item_options['options']['otw_pm_item_media_lightbox_height'];
									}
								}
							}else{
								if( isset( $this->otw_pm_plugin_options['otw_pm_item_media_lightbox_width'] ) && strlen( $this->otw_pm_plugin_options['otw_pm_item_media_lightbox_width'] ) ){
									$videoWidth = $this->otw_pm_plugin_options['otw_pm_item_media_lightbox_width'];
								}
								
								if( isset( $this->otw_pm_plugin_options['otw_pm_item_media_lightbox_height'] ) && strlen( $this->otw_pm_plugin_options['otw_pm_item_media_lightbox_height'] ) ){
									$videoHeight = $this->otw_pm_plugin_options['otw_pm_item_media_lightbox_height'];
								}
							}
						}elseif( $view_type == 'list_media' ){
							
							$this->otwDispatcher->otwImageCrop = new OTWPMImageCropLite();
							
							$videoWidth = '1024';
							$videoHeight = '640';
							
							$listOptions = $this->otwPMQuery->getItemById( $list_id );
							
							if( isset( $listOptions['lightbox_thumb_width'] ) && strlen( $listOptions['lightbox_thumb_width'] ) ){
								$videoWidth = $listOptions['lightbox_thumb_width'];
							}
							if( isset( $listOptions['lightbox_thumb_height'] ) && strlen( $listOptions['lightbox_thumb_height'] ) ){
								$videoHeight = $listOptions['lightbox_thumb_height'];
							}
							
						}
						
						if( isset( $postMetaData['media_type'] ) ){
						
							switch( $postMetaData['media_type'] ){
							
								case 'youtube':
										if( !empty( $postMetaData['youtube_url'] ) ){
											
											if( in_array( $view_type, array( 'item_media', 'list_media' ) ) ){
												echo $this->otwDispatcher->otwImageCrop->embed_resize( wp_oembed_get($postMetaData['youtube_url'], array('width' => $videoWidth)), $videoWidth, $videoHeight, 'center_center' );
											}else{
												echo wp_oembed_get( $postMetaData['youtube_url'] );
											}
											die;
										}
									break;
								case 'vimeo':
										if( !empty( $postMetaData['vimeo_url'] ) ){
											
											if( in_array( $view_type, array( 'item_media', 'list_media' ) ) ){
												echo $this->otwDispatcher->otwImageCrop->embed_resize( wp_oembed_get($postMetaData['vimeo_url'], array('width' => $videoWidth)), $videoWidth, $videoHeight, 'center_center' );
											}else{
												echo wp_oembed_get( $postMetaData['vimeo_url'] );
											}
											die;
										}
									break;
								case 'soundcloud':
										if( !empty( $postMetaData['soundcloud_url'] ) ){
											
											if( in_array( $view_type, array( 'item_media', 'list_media' ) ) ){
												echo $this->otwDispatcher->otwImageCrop->embed_resize( wp_oembed_get($postMetaData['soundcloud_url'], array('width' => $videoWidth)), $videoWidth, $videoHeight, 'center_center' );
											}else{
												echo wp_oembed_get( $postMetaData['soundcloud_url'] );
											}
											die;
										}
									break;
							}
						}
					}
				}
			}
			_e( 'Video not found', OTW_PML_TRANSLATION );
			die;
		}//end get video
		
		private function init_portfolio_item_values( $otw_pm_plugin_options ){
			
			if( !isset( $otw_pm_plugin_options['otw_pm_template'] ) ){
				
				$otw_pm_plugin_options['otw_pm_template'] = 'single-portfolio-media-left';
			}
			
			if( !isset( $otw_pm_plugin_options['otw_pm_portfolio_slug'] ) ){
				$otw_pm_plugin_options['otw_pm_portfolio_slug'] = $this->portfolio_post_type;
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_portfolio_category_slug'] ) ){
				$otw_pm_plugin_options['otw_pm_portfolio_category_slug'] = $this->portfolio_category;
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_portfolio_tag_slug'] ) ){
				$otw_pm_plugin_options['otw_pm_portfolio_tag_slug'] = $this->portfolio_tag;
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_description_title_text'] ) ){
				$otw_pm_plugin_options['otw_pm_description_title_text'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_details_title_text'] ) ){
				$otw_pm_plugin_options['otw_pm_details_title_text'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_social_title_text'] ) ){
				$otw_pm_plugin_options['otw_pm_social_title_text'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_moreinfo_title_text'] ) ){
				$otw_pm_plugin_options['otw_pm_moreinfo_title_text'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_related_title_text'] ) ){
				$otw_pm_plugin_options['otw_pm_related_title_text'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_related_posts_criteria'] ) ){
				$otw_pm_plugin_options['otw_pm_related_posts_criteria'] = $this->portfolio_category;
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_archive_media_width'] ) ){
				$otw_pm_plugin_options['otw_pm_archive_media_width'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_archive_media_height'] ) ){
				$otw_pm_plugin_options['otw_pm_archive_media_height'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_archive_media_format'] ) ){
				$otw_pm_plugin_options['otw_pm_archive_media_format'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_item_media_width'] ) ){
				$otw_pm_plugin_options['otw_pm_item_media_width'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_item_media_height'] ) ){
				$otw_pm_plugin_options['otw_pm_item_media_height'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_item_media_format'] ) ){
				$otw_pm_plugin_options['otw_pm_item_media_format'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_related_media_format'] ) ){
				$otw_pm_plugin_options['otw_pm_related_media_format'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_item_media_lightbox_width'] ) ){
				$otw_pm_plugin_options['otw_pm_item_media_lightbox_width'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_item_media_lightbox_height'] ) ){
				$otw_pm_plugin_options['otw_pm_item_media_lightbox_height'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_item_media_lightbox_format'] ) ){
				$otw_pm_plugin_options['otw_pm_item_media_lightbox_format'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_item_title'] ) ){
				$otw_pm_plugin_options['otw_pm_item_title'] = 'yes';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_related_media_width'] ) ){
				$otw_pm_plugin_options['otw_pm_related_media_width'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_media_lightbox'] ) ){
				$otw_pm_plugin_options['otw_pm_media_lightbox'] = 'no';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_related_media_height'] ) ){
				$otw_pm_plugin_options['otw_pm_related_media_height'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_related_posts_number'] ) ){
				$otw_pm_plugin_options['otw_pm_related_posts_number'] = '';
			}
			if( !isset( $otw_pm_plugin_options['otw_pm_grid_pages'] ) ){
				$otw_pm_plugin_options['otw_pm_grid_pages'] = 'yes';
			}
			return $otw_pm_plugin_options;
		}
		
		public function get_select2_options(){
			
			$options = array();
			$options['results'] = array();
			
			$options_type = '';
			$options_limit = 100;
			
			if( isset( $_POST['otw_options_type'] ) ){
				$options_type = $_POST['otw_options_type'];
			}
			
			if( isset( $_POST['otw_options_limit'] ) ){
				$options_limit = $_POST['otw_options_limit'];
			}
			
			switch( $options_type ){
				
				case 'otw_pm_category':
						$args = array();
						$args['hide_empty']      = 0;
						$args['number']          = $options_limit;
						
						if( isset( $_POST['otw_options_ids'] ) && strlen( $_POST['otw_options_ids'] ) ){
							
							$args['include'] = array();
							$include_items = explode( ',', $_POST['otw_options_ids'] );
							
							foreach( $include_items as $i_item ){
								
								if( intval( $i_item ) ){
									$args['include'][] = $i_item;
								}
							}
						}
						
						if( isset( $_POST['otw_options_search'] ) && strlen( $_POST['otw_options_search'] ) ){
							$args['search'] = urldecode( $_POST['otw_options_search'] );
						}
						
						$all_items = get_terms( $this->portfolio_category, $args );
						
						if( is_array( $all_items ) && count( $all_items ) ){
							foreach( $all_items as $item ){
								$o_key = count( $options['results'] );
								$options['results'][ $o_key ] = array();
								$options['results'][ $o_key ]['id'] = $item->term_id;
								$options['results'][ $o_key ]['text'] = $item->name;
							}
						}
					break;
				case 'otw_pm_tag':
						$args = array();
						$args['hide_empty']      = 0;
						$args['number']          = $options_limit;
						
						if( isset( $_POST['otw_options_ids'] ) && strlen( $_POST['otw_options_ids'] ) ){
							
							$args['include'] = array();
							$include_items = explode( ',', $_POST['otw_options_ids'] );
							
							foreach( $include_items as $i_item ){
								
								if( intval( $i_item ) ){
									$args['include'][] = $i_item;
								}
							}
						}
						
						if( isset( $_POST['otw_options_search'] ) && strlen( $_POST['otw_options_search'] ) ){
							$args['search'] = urldecode( $_POST['otw_options_search'] );
						}
						
						$all_items = get_terms( $this->portfolio_tag, $args );
						
						if( is_array( $all_items ) && count( $all_items ) ){
							foreach( $all_items as $item ){
								$o_key = count( $options['results'] );
								$options['results'][ $o_key ] = array();
								$options['results'][ $o_key ]['id'] = $item->term_id;
								$options['results'][ $o_key ]['text'] = $item->name;
							}
						}
					break;
				case 'user':
						$args = array();
						
						if( isset( $_POST['otw_options_ids'] ) && strlen( $_POST['otw_options_ids'] ) ){
							
							$args['include'] = array();
							$include_items = explode( ',', $_POST['otw_options_ids'] );
							
							foreach( $include_items as $i_item ){
								
								if( intval( $i_item ) ){
									$args['include'][] = $i_item;
								}
							}
						}
						
						if( isset( $_POST['otw_options_search'] ) && strlen( $_POST['otw_options_search'] ) ){
							$args['search'] = '*'.urldecode( $_POST['otw_options_search'] ).'*';
						}
						
						$all_items = get_users( $args );
						
						if( is_array( $all_items ) && count( $all_items ) ){
							foreach( $all_items as $item ){
								$o_key = count( $options['results'] );
								$options['results'][ $o_key ] = array();
								$options['results'][ $o_key ]['id'] = $item->ID;
								$options['results'][ $o_key ]['text'] = $item->user_login;
							}
						}
					break;
				case 'page':
						$args = array();
						$args['post_type'] = 'page';
						$args['number']          = $options_limit;
						
						if( isset( $_POST['otw_options_ids'] ) && strlen( $_POST['otw_options_ids'] ) ){
							
							$args['post__in'] = array();
							$include_items = explode( ',', $_POST['otw_options_ids'] );
							
							foreach( $include_items as $i_item ){
								
								if( intval( $i_item ) ){
									$args['post__in'][] = $i_item;
								}
							}
						}
						
						if( isset( $_POST['otw_options_search'] ) && strlen( $_POST['otw_options_search'] ) ){
							$args['s'] = urldecode( $_POST['otw_options_search'] );
						}
						
						$query = new WP_Query( $args );
						$all_items = $query->posts;
						
						if( is_array( $all_items ) && count( $all_items ) ){
							foreach( $all_items as $item ){
								$o_key = count( $options['results'] );
								$options['results'][ $o_key ] = array();
								$options['results'][ $o_key ]['id'] = $item->ID;
								$options['results'][ $o_key ]['text'] = $item->post_title;
							}
						}
					break;
			}
			
			echo json_encode( $options );
			die;
		}
		
	}//end of class
}

// DB Query
require_once( 'classes' . DS . 'otw_pm_query.php' );

// Template Dispatcher
require_once( 'classes' . DS . 'otw_pm_dispatcher.php' );

// Custom CSS
require_once( 'classes' . DS . 'otw_pm_css.php' );

// Add Image Crop Functionality
require_once( 'classes' . DS . 'otw_pm_image_crop.php' );

// Register Widgets
require_once( 'classes' . DS . 'otw_portfolio_manager_widgets.php' );

// Register VC add on
require_once( 'classes' . DS . 'otw_portfolio_manager_vc_addon.php' );

$otwPortfolioMangerLitePlugin = new OTWPortfolioManagerLite();
?>