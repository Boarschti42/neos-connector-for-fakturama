<?php
/*
* Plugin Name: Neos Connector for Fakturama
* Plugin URI: https://github.com/Boarschti42/neos-connector-for-fakturama
* Update URI:  https://api.github.com/repos/Boarschti42/neos-connector-for-fakturama/releases/latest
* Description: Neos Connector for Fakturama importiert Produkte und Bestellungen von Woocommerce zu Fakturama. Forked from Kevin Bonner, Changed for current WooCommerce.
* Version: 0.2.0
* Author: Kevin Bonner, Karsten Röhle
* Author URI: https://www.rekn.de
* Min WP Version: 6.2.0
* Max WP Version: 6.3.1
* Text Domain: neosconnectorforfakturama
* Requires Plugins:  woocommerce
*/

/*
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}*/
global $wp_filter;

class NeosFaktura_FakturamaC {

    private $xml;
    /**
     * Constructor. Intentionally left empty and public.
     *
     * */
    public function __construct(){
    }

    private function ncff_includes(){
        include_once('neosconnectorforfakturama-XMLHelper.php');
        include_once ('neosconnectorforfakturama-XML.php');


        if(is_admin())
        {
            require_once('neosconnectorforfakturama-admin.php');
            require_once('neosconnectorforfakturama-notices.php');
            $plugin_admin = new NeosFaktura_Settings_Page();
            add_action( 'admin_menu', array( $plugin_admin, 'ncff_add_plugin_page' ) );

        }
    }

    public function ncff_missing_woocommerce_error_notice()
    {
        $class = 'notice notice-error';
        $message = __( 'Woocommerce Shop Plugin is missing! Please install first Woocommerce!', NCFF_TEXTDOMAIN );

        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
    }

    public function ncff_check_woocommerce_installation(){
        if(is_admin()){
            if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                add_action( 'admin_notices', array( $this, 'ncff_missing_woocommerce_error_notice' ) );

            }
        }
    }

    private function ncff_defines(){
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
        $this->ncff_defines();
        $this->ncff_includes();

        //load_plugin_textdomain( NCFF_TEXTDOMAIN, FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
    }

    public function ncff_admin_init(){
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

        $this->ncff_init();

        /**
         * Load Class
         */

        if (class_exists('NeosFaktura_fakturamaXML')) {
            $this->xml = new NeosFaktura_fakturamaXML();
        }

        add_action( 'rest_api_init', function () {
            register_rest_route( 'neos/v2', '/fakturama', array(
              'methods' => WP_REST_Server::CREATABLE,
              'callback' => array($this, 'ncff_request') ,
              'permission_callback' => '__return_true'
            ) );
          } );
    }

    public function ncff_request( ){ 
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
        $user = wp_signon( $creds);
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
    $paramsreturn = array();
    if(isset($params['username']) )
    {
      $paramsreturn['username'] = sanitize_user($params['username']);
    }
    if(isset($params['password']))
    {
      $paramsreturn['password'] = $params['password'];
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

if (!function_exists('neos_plugin_check_for_updates')) {
    error_log("neos_plugin_check_for_updates called");
    function neos_plugin_check_for_updates($update, $plugin_data, $plugin_file)
    {
        static $response = false;
        error_log("checking for neos updates..." );
       
        // only check for github updates
        if (empty($plugin_data['UpdateURI']) || !str_contains($plugin_data['UpdateURI'], 'api.github.com')) {
            return $update;
        }

        $cache_key = 'neos_update_' . sha1($plugin_file);
        //$response = get_transient($cache_key);
        if (!$response) {
            $response = wp_remote_get($plugin_data['UpdateURI'], [
                'headers' => ['Accept' => 'application/vnd.github.v3+json']
            ]);
            set_transient($cache_key, $response, HOUR_IN_SECONDS * 12);
        }

        if (is_wp_error($response) || empty($response['body'])) {
            return $update;
        }

        $custom_plugins_data = json_decode($response["body"], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON Error: " . json_last_error_msg());
            return $update;
        } 
        if (empty($custom_plugins_data['tag_name'] || $custom_plugins_data['zipball_url'])) {
            return $update;
        }

        $remote_ver = ltrim($custom_plugins_data['tag_name'], 'v');
        $zip_url    = $custom_plugins_data['zipball_url'];  
        $resp = [
            'slug'        => dirname($plugin_file),
            'plugin'      => $plugin_file,
            'version'     => $remote_ver,
            'new_version' => $remote_ver,
            'package'     => $zip_url,
            'tested'      => '6.8.1',
        ];
        return $resp;
        
        

    }

    add_filter('update_plugins_api.github.com', 'neos_plugin_check_for_updates', 10, 3);

}

$plugin = new NeosFaktura_FakturamaC();
$plugin->ncff_rest_init();


//add_action( 'init', array( NeosFaktura_FakturamaC::get_instance(), 'ncff_admin_init' ));
//add_action( 'plugins_loaded', array( NeosFaktura_FakturamaC::get_instance(), 'ncff_rest_init' ));
//register_activation_hook( __FILE__, array( NeosFaktura_FakturamaC::get_instance(), 'ncff_activation' ));