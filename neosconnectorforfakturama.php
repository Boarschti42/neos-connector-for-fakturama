<?php
/*
Plugin Name: Neos Connector for Fakturama
Plugin URI: https://www.rekn.de
Description: Neos Connector for Fakturama importiert Produkte und Bestellungen von Woocommerce zu Fakturama. Forked from Kevin Bonner, Changed for current WooCommerce.
Version: 0.1.2
Author: Kevin Bonner, Karsten Röhle
Author URI: https://www.rekn.de
Min WP Version: 6.2.0
Max WP Version: 6.3.1
Text Domain: neosconnectorforfakturama
*/
/*
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}*/

class NeosFaktura_FakturamaC {

    private $xml;
    /**
     * Constructor. Intentionally left empty and public.
     *
     * */
    public function __construct(){
        $this->ncff_rest_init();
    }

    private function ncff_includes(){
        error_log("ncff_includes()");

        include_once('neosconnectorforfakturama-XMLHelper.php');
        include_once ('neosconnectorforfakturama-XML.php');

        if(is_admin())
        {
            include_once('neosconnectorforfakturama-admin.php');
            include_once ('neosconnectorforfakturama-notices.php');
        }
    }

    public function ncff_missing_woocommerce_error_notice()
    {
        error_log("ncff_missing_woocommerce_error_notice()");

        $class = 'notice notice-error';
        $message = __( 'Woocommerce Shop Plugin is missing! Please install first Woocommerce!', NCFF_TEXTDOMAIN );

        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }

    public function ncff_check_woocommerce_installation(){
        error_log("ncff_check_woocommerce_installation()");

        if(is_admin()){
            if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                add_action( 'admin_notices', array( $this, 'ncff_missing_woocommerce_error_notice' ) );

            }
        }
    }

    private function ncff_defines(){
        error_log("ncff_defines()");

        defined('NCFF_TEXTDOMAIN') or define('NCFF_TEXTDOMAIN', 'neosconnectorforfakturama');
        defined('NCFF_PLUGIN_PATH') or define('NCFF_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
        defined('NCFF_PLUGIN_URL') or define('NCFF_PLUGIN_URL', plugins_url( '/', __FILE__ ));
        defined('NCFF_PLUGINBASENAME') or define('NCFF_PLUGINBASENAME', plugin_basename( __FILE__ ));
        defined('NCFF_PLUGINDATA') or define('NCFF_PLUGINDATA', array('Version' => '0.1.0', 'Name' => 'Neos Connector for Fakturama', 'PluginURI'=> 'https://www.rekn.de/'));
        defined('NCFF_PLUGIN_PAGE_URL') or define('NCFF_PLUGIN_PAGE_URL', get_bloginfo('url')."/wp-admin/options-general.php?page=".NCFF_TEXTDOMAIN);
        defined('NCFF_DEBUG') or define('NCFF_DEBUG', get_option(NCFF_TEXTDOMAIN.'_debug') === 'true'? true: false);
        defined('NCFF_XMLNS') or define('NCFF_XMLNS', "http://www.fakturama.org");
    }

    private function ncff_init(){
        error_log("ncff_init()");

        $this->ncff_defines();
        $this->ncff_includes();

        load_plugin_textdomain( NCFF_TEXTDOMAIN, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
    }

    public function ncff_admin_init(){
        error_log("ncff_admin_init()");

        $this->ncff_init();

        /**
         * Load Class
         */
        if (class_exists('NeosFaktura_Notices')) {
            new NeosFaktura_Notices();
        }

        add_action('admin_init', array($this, 'ncff_check_woocommerce_installation'));
    }

    function prefix_get_endpoint_phrase() {
        // rest_ensure_response() wraps the data we want to return into a WP_REST_Response, and ensures it will be properly returned.
        return rest_ensure_response( 'Hello World, this is the WordPress REST API' );
    }

    public function ncff_rest_init(){
        error_log("ncff_rest_init()");

        $this->ncff_init();

        /**
         * Load Class
         */

        if (class_exists('NeosFaktura_fakturamaXML')) {
            $this->xml = new NeosFaktura_fakturamaXML();
        }

        add_action( 'rest_api_init', function () {
            error_log("rest_api_init()");

            register_rest_route( 'neos/v2', '/fakturama', array(
              'methods' => WP_REST_Server::CREATABLE,
              'callback' => array($this, 'ncff_request') ,
              'permission_callback' => '__return_true'
            ) );
          } );


    }

    public function ncff_request( ){ 
        error_log("ncff_request()");

        $params = "";
        if(isset($_POST['action']))
        {
            $params = $this->ncff_ValidatingSantizingParams($_POST);
        }

        if(!isset($params['username']) || !isset($params['password']))
        {
            wp_die("Please Login! " . var_export($_POST, true));
        }

        $creds = array();
        $creds['user_login'] = $params['username'];
        $creds['user_password'] = $params['password'];
        $creds['remember'] = false;
        $user = wp_signon( $creds, false );
        if ( is_wp_error($user) ){
            wp_die($user->get_error_message());
        }

        if(NCFF_DEBUG == False) {
            header("Content-type: text/xml");
        }

        if(isset($params['action'])){
            switch ($params['action']) {
                case "get_products":

                    echo $this->xml->ncff_get_products();

                    break;
                case "get_orders":

                    echo $this->xml->ncff_get_orders();

                    break;
                case "get_products_orders":

                    echo $this->xml->ncff_get_products_orders();

                    break;
                case "get_status":
                    echo $this->xml->ncff_get_order_stati();
                    break;

            }
        }
        else{
            wp_die("Missing action!");
        }

        if(isset($params['setstate'])){
            $this->xml->ncff_setstate($params['setstate']);
        }
    }
  
  private function ncff_ValidatingSantizingParams($params){
    error_log("ncff_ValidatingSantizingParams(): " . var_export($params, true));

    $paramsreturn = array();
    if(isset($params['username']) && validate_username($params['username']))
    {
      $paramsreturn['username'] = sanitize_user($params['username']);
    }
    if(isset($params['password']))
    {
      $paramsreturn['password'] = sanitize_text_field($params['password']);
    }
    if(isset($params['action']) && !preg_match('/[^a-z_]+/', $params['action']))
    {
      $paramsreturn['action'] = sanitize_text_field($params['action']); //input like this action=get_products_orders or action=get_orders or action=get_products
    }
    if(isset($params['setstate']))
    {
      $paramsreturn['setstate'] = sanitize_text_field($params['setstate']); //input like this setstate={0=3*Hallo%0ATest}
    }
    
    return $paramsreturn;
  }

}
error_log("running functions");

$plugin = new NeosFaktura_FakturamaC();

//add_action( 'init', array( NeosFaktura_FakturamaC::get_instance(), 'ncff_admin_init' ));
//add_action( 'plugins_loaded', array( NeosFaktura_FakturamaC::get_instance(), 'ncff_rest_init' ));
//register_activation_hook( __FILE__, array( NeosFaktura_FakturamaC::get_instance(), 'ncff_activation' ));