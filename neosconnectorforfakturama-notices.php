<?php
/**
 * Created by IntelliJ IDEA.
 * User: kevin
 * Date: 21.07.2017
 * Time: 13:37
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class NeosFaktura_Notices
{
    /**
     * Constructor.
     */
    public function __construct() {
        {
            add_action( 'admin_notices', array( $this, 'ncff_review_notice' ) );
            add_action('admin_init', array($this, 'ncff_disable_review_notice'));
        }
    }



    public function ncff_review_notice(){
       
    }

    public function ncff_reset_notice_after_update( $upgrader_object, $options ) {


       
    }

    public function ncff_disable_review_notice(){

       
    }
}



