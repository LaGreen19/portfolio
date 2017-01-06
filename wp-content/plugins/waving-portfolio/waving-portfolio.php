<?php
/**
 * Plugin Name: Waving Portfolio
 * Plugin URI:  http://www.itechflare.com/
 * Description: Free plugin with very slick design to professionally promote & present your job portfolio.
 * Version:     1.2.4.5
 * Author:      Abdulrhman Elbuni
 * Author URI:  http://www.itechflare.com/
 * Text Domain: wavingportfolio
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define("WAVING_PORTFOLIO_VERSION", "1.2.4.5");
define("WAVING_PORTFOLIO_VERSION_SLUG", "waving_portfolio_version");
define("WAVING_PORTFOLIO_TEXT_DOMAIN", "waving-portfolio");
define('WAVING_PORTFOLIO_PLGUINURI',plugin_dir_path( __FILE__ ));
define('WAVING_PORTFOLIO_PLUGINURL',plugins_url( '',__FILE__));

// Required files for registering the post type and taxonomies.
require_once WAVING_PORTFOLIO_PLGUINURI . 'includes/waving-portfolio-type.php';
require_once WAVING_PORTFOLIO_PLGUINURI . 'includes/waving-portfolio-post-type-registrations.php';
require_once WAVING_PORTFOLIO_PLGUINURI . 'includes/cmb2/waving-meta.php';
require_once WAVING_PORTFOLIO_PLGUINURI . 'includes/waving-register-mce-buttons.php';

// Instantiate registration class, so we can add it as a dependency to main plugin class.
$portfolio_post_type_registrations = new Portfolio_Post_Type_Registrations;

// Instantiate main plugin file, so activation callback does not need to be static.
$waving_portfolio = new Waving_Portfolio_Type($portfolio_post_type_registrations);
// Register callback that is fired when the plugin is activated.
register_activation_hook( __FILE__, array( $waving_portfolio, 'activate' ) );
// Initialise registrations for post-activation requests.
$portfolio_post_type_registrations->init();


class Waving_Portfolio_Plugin
{
  private $meta_prefix = "_waving_portfolio_";
  private $post_type = "itech_portfolio";
  
  public function __construct() {
    // Init
    add_action( 'init', array( $this, 'waving_portfolio_init' ) );
  }
 
  function waving_portfolio_init()
  {
    add_shortcode( 'waving', array( $this, 'waving_func'));
    add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_libraries') );
    add_filter( 'shortcode_atts_gallery', array( $this, 'waving_gallery_atts') , 10, 3 );
    add_action( 'admin_enqueue_scripts', array($this,'waving_admin_enqueue') );
  }
  
  function upgrade_to_1_2()
  {
    global $wpdb;
    // First update fix
    $wpdb->update( 
        'wp_posts', 
        array( 
            'post_type' => 'itech_portfolio',	// string
        ), 
        array( 'post_type' => 'portfolio' ), 
        array( '%s' ), // Formatting column
        array( '%s' ) 
    );
    // Second update fix
    $wpdb->update( 
        'wp_term_taxonomy', 
        array( 
            'taxonomy' => 'waving_portfolio_category',	// string
        ), 
        array( 'taxonomy' => 'portfolio_category' ), 
        array( '%s' ), // Formatting column
        array( '%s' ) 
    );
    // third update fix
    $wpdb->update( 
        'wp_term_taxonomy', 
        array( 
            'taxonomy' => 'waving_portfolio_tag',	// string
        ), 
        array( 'taxonomy' => 'portfolio_tag' ), 
        array( '%s' ), // Formatting column
        array( '%s' ) 
    );
  }
  
  // [waving effect="value"]
  function waving_func( $atts ) {
    extract( shortcode_atts( array(
            'effect' => 12,
            'width' => 0,
            'height' => 250,
            'theme'=> 'dark', 
            'all' => 'false',
            'clicking' => 'true',
            'showcat' => 'true',
            'tag' => ''),
            $atts ) );

    return $this->Building_Portfolio_List($width, $height, $effect,$theme,$tag, $showcat, $all, $clicking);
  }
 
  // Enqueue scripts
  function load_frontend_libraries() {

    $params = array(
        'loadingImageSrc' => plugins_url( 'assets/images/loading.gif' , __FILE__ ),
        'closeImageSrc' => plugins_url( 'assets/images/closelabel.gif' , __FILE__ ),
        );

      wp_enqueue_style( 'waving-portfolio-default-style',
              plugins_url( 'assets/css/style.css' , __FILE__ ));
      wp_enqueue_style( 'waving-portfolio-lightbox',
              plugins_url( 'assets/css/lightbox.css' , __FILE__ ));
      wp_enqueue_style( 'waving-portfolio-modal',
              plugins_url( 'assets/css/modal.css' , __FILE__ ));
          
    // Loading waving hover effect scripts
      wp_enqueue_script(
          'waving-portfolio-hover-script',
          plugins_url( 'assets/js/jquery.hoverdir.js' , __FILE__ ),
          array( 'jquery' ), WAVING_PORTFOLIO_VERSION, true
      );
      wp_enqueue_script(
          'waving-portfolio-modernizer-script',
          'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', '', WAVING_PORTFOLIO_VERSION, true
      );

          // Loading lightbox effect script
      wp_enqueue_script(
          'waving-portfolio-lightbox-script',
          plugins_url( 'assets/js/lightbox.min.js' , __FILE__ ),
                  array( 'jquery' ), WAVING_PORTFOLIO_VERSION, true
      );

      wp_register_script('waving-portfolio-custom-script', 
                  plugins_url( 'assets/js/custom.js' , __FILE__ ),array('jquery', 'waving-portfolio-lightbox-script' , 'waving-portfolio-hover-script'),WAVING_PORTFOLIO_VERSION, true);


      wp_enqueue_script(
          'waving-portfolio-classie-script',
          plugins_url( 'assets/js/classie.js' , __FILE__ ),'',WAVING_PORTFOLIO_VERSION, true
      );
      wp_enqueue_script(
          'waving-portfolio-modal-script',
          plugins_url( 'assets/js/modalEffects.js' , __FILE__ ),'',WAVING_PORTFOLIO_VERSION, true
      );

      wp_localize_script( 'waving-portfolio-lightbox-script', 'pluginSetting', $params );
      wp_enqueue_script('waving-portfolio-custom-script');


  }
  
  /**
  * Loads the admin menu css
  */
  function waving_admin_enqueue() {
      wp_enqueue_style( 'waving_meta_box_styles', plugin_dir_url( __FILE__ ) . 'assets/css/waving-admin.css' );
  }
  
  function sortByOrder($a, $b) {
    return strcmp($a->slug, $b->slug);
  }
  
  function Building_Portfolio_List($width, $height, $fx, $theme, $tag, $showCategory,$all, $click)
  {
      if($click == "true") $disableClicking2 = 'class="md-trigger"';
      else $disableClicking2 = '';

      $disableClicking = "";
      $rand_index = rand(); 
      
      $waving_meta = array(); 
      $lists = array();
      $modals = array();
      $paramCustom = array();
      $categoryMenu = array();
      // Filtered category list
      $cat_lists = array();

      // Check whether tag is empty or not
      $tagCondition = ($tag=='')?null:$tag;
      
      // Get portfolio posts
      $type = 'itech_portfolio';

      $args=array(
        'post_type' => $type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'waving_portfolio_tag' => $tagCondition,
        'cache_results' => false);
      
      // If it is a comma seperate list, reformat the string
      if(strpos($tagCondition, ',')){
        $tagCondition = str_replace(' ', '', $tagCondition);
        $tagCondition = str_replace(',','-',$tagCondition);
      }
      
      $listHeader = '<section style="text-align:center"><ul id="da-thumbs" class="waving-thumb-'.$rand_index.' da-thumbs-'.$tagCondition.'">';
      $listFooter = '</ul></section>';

      $i = 1;
      
      if($theme == 'light')
      {
          wp_enqueue_style( 'waving-portfolio-modal-light-theme',
                  plugins_url( 'assets/css/light.css' , __FILE__ ));
      }

      // print out categories
      $taxonomy = 'waving_portfolio_category';
      $image_code = '';

      $my_query = null;
      $my_query = new WP_Query($args);
      if( $my_query->have_posts() ) {
          while ($my_query->have_posts()) : $my_query->the_post(); 

              $i = rand(); 

              // Initialize categories to be used for categories menu rendering
            $temp_cat_cmp_array[] = get_the_terms( get_the_ID(),$taxonomy);

            // ========= Start: Ensure that cat_list variable is unique ============

            if(count($temp_cat_cmp_array) != 0 && $temp_cat_cmp_array[0] != null){
              foreach($temp_cat_cmp_array[0] as $cat_cmp)
              {
                $cmp_result = false;
                if(count($cat_lists) != 0){
                  foreach($cat_lists as $cat_val)
                  {
                    if($cat_val == $cat_cmp)
                    {
                      $cmp_result = true;
                    }
                  }
                }
                if(!$cmp_result)
                {
                  array_push($cat_lists, $cat_cmp);
                }
              }
            }
			
			// Sort categories that will be printed out as buttons
            usort($cat_lists, array($this,'sortByOrder'));
			
            unset($temp_cat_cmp_array);
            $temp_cat_cmp_array = array();

              // ========= End: Ensure that cat_list variable is unique ============
              //Extract meta information
              $waving_meta = get_post_meta( get_the_ID() );
              
              // Title and description for sliding panel
              $description = false;
              // Hyperlink or image on click event over panel
              $hyperlinkSelection = false;
              $blankPage = false;
              $hyperlink = "";
              $panel = "";
              $fontsize = "15";
              $excerptLength = "20";
              $imageList = array();
              
              if(isset($waving_meta[$this->meta_prefix.'hori']))
              {
                $hyperlinkSelection = ($waving_meta[$this->meta_prefix.'hori'][0]=='on')?true:false;
              }
              
              if(isset($waving_meta[$this->meta_prefix.'blank']))
              {
                $blankPage = ($waving_meta[$this->meta_prefix.'blank'][0]=='on')?true:false;
              }
              
              if(isset($waving_meta[$this->meta_prefix.'fontsize']))
              {
                $fontsize = ($waving_meta[$this->meta_prefix.'fontsize'][0]);
              }
              
              if(isset($waving_meta[$this->meta_prefix.'excerpt']))
              {
                $description = ($waving_meta[$this->meta_prefix.'excerpt'][0]=='on')?true:false;
              }
              
              if(isset($waving_meta[$this->meta_prefix.'excerpt_length']))
              {
                $excerptLength = ($waving_meta[$this->meta_prefix.'excerpt_length'][0]);
              }
              
              if(isset($waving_meta[$this->meta_prefix.'hyperlink']))
              {
                $hyperlink = $waving_meta[$this->meta_prefix.'hyperlink'][0];
              }
              
              if(isset($waving_meta[$this->meta_prefix.'file_list']))
              {
                $imageList = unserialize($waving_meta[$this->meta_prefix.'file_list'][0]);
              }
      
              $post_thumbnail_id = get_post_thumbnail_id(get_the_ID()); 
              
              // Select optimal portfolio item image size and store it in $image
              if($width < 310)
                $image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID() ), 'medium' );    
              else
                $image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID() ), 'full' );    
              
              $title = get_the_title( $post_thumbnail_id );
              $alt = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true);
              
              // IF there is no image don't render a list
              if($image!=""){
                  $gImages = $imageList;
                  $gallary = "";

                  // Building the gallary
                  if(!empty($gImages) ){
                      $j=1;
                      foreach($gImages as $key=>$img){
                        $img_thumbnail = wp_get_attachment_image_src( $key, array(150,150), false ); 
                        $internal_img_title = get_the_title( $key );
                        $internal_img_alt = get_post_meta($key, '_wp_attachment_image_alt', true);
                        $gallary = $gallary.'<a href="'.$img.'" data-lightbox="image-list-'.$i.'" rel="portfolios'.$i.'"><img class="multiple-borders" src="'.$img_thumbnail[0].'" width="100"  title="'.$internal_img_title.'" alt="'.$internal_img_alt.'" /></a>';
                      }
                  }

                  if($hyperlinkSelection)
                  {
                    if($blankPage)
                      $disableClicking = "target='_blank' href='".$hyperlink."'";
                    else
                      $disableClicking = "href='".$hyperlink."'";
                  }else{
                    $disableClicking = $disableClicking2;
                  }
                  
                  $modal = '<div class="md-modal md-effect-12" id="modal-'.$i.'">
                    <div id="md-close">
                      <img class="waving-close" src="'.WAVING_PORTFOLIO_PLUGINURL.'/assets/images/close-button.png">
                    </div>
                    <div class="md-content waving-content">
                      <h3>'.  get_the_title().'</h3>
                      <div class="modal-waving-content">
                      <div class="modal-gallary">
                          '.$gallary.'<br/><br/>
                        </div>'.
                          apply_filters('the_content', get_the_content()).'</div>
                    </div>
                    </div>';

                  $categories = get_the_terms( get_the_ID(),$taxonomy);
                  $cat_class = "";
                  if( $categories ){
                    foreach($categories as $cat)
                    {
                      $cat_class = $cat_class. " waving-" . $cat->term_id;
                    }
                  }

                  if($width > 0){
                    $image_code = '<img src="'.$image[0].'" style="width:'.$width.'px" title="'.$title.'" alt="'.$alt.'"/>';
                  }else
                  {
                    $image_code = '<img src="'.$image[0].'" style="height:'.$height.'px" title="'.$title.'" alt="'.$alt.'" >';
                  }

                  if($description)
                  {
                    $content = get_the_excerpt();
                    $content = substr($content, 0, $excerptLength);
                    
                    $list = '<li class="waving-item-class waving-item '.$cat_class.'">
                      <a '.$disableClicking.' data-modal="modal-'.$i.'">
                        '.$image_code.'
                        <div style="display: block; left: 100%; top: 0px; overflow: hidden; -webkit-transition: all 300ms ease; transition: all 300ms ease;">
                          <span style="margin: 20px 15px 0px !important;padding:3px 0 !important;font-size:'.$fontsize.'px !important">'.get_the_title().'</span>
                          <p style="font-size:'.$fontsize.'px !important;color: white !important;width: 80% !important;margin: 5px 13px 0px !important;">
                            '.$content.'...
                          </p>
                        </div>
                      </a>
                    </li>';
                  }else{
                    $list = '<li class="waving-item '.$cat_class.'">
                      <a '.$disableClicking.' data-modal="modal-'.$i.'">
                        '.$image_code.'
                        <div style="display: block; left: 100%; top: 0px; overflow: hidden; -webkit-transition: all 300ms ease; transition: all 300ms ease;">
                          <span style="font-size:'.$fontsize.'px">'.get_the_title().'</span>
                        </div>
                      </a>
                    </li>';
                  }
                  
                  array_push($modals, $modal);
                  array_push($lists, $list);
                  $i++;
              }
          endwhile;
      }
      wp_reset_query();

      // ========= Start: Print out categories ============
      if(count($cat_lists)!=0){
        $paramCustom = array("all" => $all,
          "initialClass"=>$cat_lists[0]->term_id);
      }else{
        $paramCustom = array("all" => "1",
          "initialClass"=>"0");
      }

      if($all == "true" && count($cat_lists)!=0)
      {
        $categoryMenu[] = '<button class="waving-button waving-button-'.$rand_index.' action-button shadow animate blue" onClick="ShowLists(\'all\',\''.$tagCondition.'\', this, '.$rand_index.')">All</button>';
      }

      if(count($cat_lists)!=0){
        foreach($cat_lists as $term_cat)
        {
          $categoryMenu[] = '<button class="waving-button waving-button-'.$rand_index.' action-button shadow animate blue" onClick="ShowLists(\''.$term_cat->term_id.'\',\''.$tagCondition.'\', this, '.$rand_index.')">'.ucfirst($term_cat->name).'</button>';
        }
      }

      // ========= End: Print out categories ============

      $static = '<div class="md-overlay"></div><div id="waving-dim"></div>';

      wp_localize_script( 'waving-portfolio-custom-script', 'pluginSetting', $paramCustom );

      $catMenu = ($showCategory=="true")?implode("",$categoryMenu):'';

      return '<div class="waving-wrap">'.$catMenu.'</div>'.implode("",$modals).''.$listHeader.''.implode("",$lists).''.$listFooter.''.$static;
  }

  // Setting gallary attributes to full size by Default
  function waving_gallery_atts( $out, $pairs, $atts ) {

      $atts = shortcode_atts( array(
          'columns' => '2',
          'size' => 'full',
          ), $atts );

      $out['columns'] = $atts['columns'];
      $out['size'] = $atts['size'];

      return $out;

  }

}

$waving_portfolio_plugin = new Waving_Portfolio_Plugin();