<?php
/*
Plugin Name: Neos Connector for Fakturama
Plugin URI: https://www.neosuniverse.de
Description: Neos Connector for Fakturama importiert Produkte und Bestellungen von Woocommerce zu Fakturama
Version: 0.0.10
Author: Kevin Bonner
Author URI: https://www.neosuniverse.de
Min WP Version: 4.0
Max WP Version: 4.7
Text Domain: fakturamac
*/




defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

#error_reporting(E_ALL);
#ini_set('display_errors', 1);

include_once('admin_settings.php');
include_once('fakturamaXMLHelper.php');
include_once ('fakturamaXML.php');


class NeosFaktura_FakturamaC {

    private $xml;
    private $debug;

    public function __construct(){
        // Don't run outside of WP
        if ( ! defined('ABSPATH') ) {
            return;
        }

        $this->debug = get_option('NeosFaktura_debug') === 'true'? true: false;;
        load_plugin_textdomain( 'fakturamac', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
        add_action( 'init', array($this, 'core_add_ajax_hook'));
        add_action('wp_ajax_nopriv_fakturama', array($this, 'request'));
        add_action('wp_ajax_fakturama', array($this, 'request'));



        $this->xml = new NeosFaktura_fakturamaXML($this->debug);



        // Hook in
        add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

        function custom_override_checkout_fields( $fields ) {
            $fields['order']['order_comments'] = array(
                'type' => 'textarea',
                'class' => array('notes'),
                'label' => __('Order Notes', 'woocommerce'),
                'placeholder' => _x('Notes about your order, e.g. special notes for delivery.', 'placeholder', 'woocommerce')
            );

            return $fields;
        }


    }

    public function core_add_ajax_hook() {
        if ( isset( $_REQUEST['do'] ) ) {
          if (strpos($_REQUEST['do'], 'fakturama') !== false) {
            do_action( 'wp_ajax_' . sanitize_text_field($_REQUEST['do']) );
          }
        }
    }



    public function request(){



        $params = "";
        if(isset($_POST['action']))
        {
            $params = $this->ValidatingSantizingParams($_POST);
        }
        else{
            $params = $this->ValidatingSantizingParams($_GET);
        }


        $creds = array();
        $creds['user_login'] = $params['username'];
        $creds['user_password'] = $params['password'];
        $creds['remember'] = false;
        $user = wp_signon( $creds, false );
        if ( is_wp_error($user) ){
            die($user->get_error_message());
        }



        if($this->debug == False) {


            header("Content-type: text/xml");

        }

        if(isset($params['action'])){
            switch ($params['action']) {
                case "get_products":

                    echo $this->xml->get_products();

                    break;
                case "get_orders":

                    echo $this->xml->get_orders();

                    break;
                case "get_products_orders":

                    echo $this->xml->get_products_orders();

                    break;

            }
        }




        if(isset($params['setstate'])){
            $this->xml->setstate($params['setstate']);
        }


        wp_die();
        //}

    }
  
  private function ValidatingSantizingParams($params){
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

new NeosFaktura_FakturamaC();
