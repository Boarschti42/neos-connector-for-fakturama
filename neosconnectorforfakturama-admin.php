<?php
/**
 * Created by IntelliJ IDEA.
 * User: xoxoxo
 * Date: 16.05.16
 * Time: 17:56
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}



class NeosFaktura_Settings_Page {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $optionsp;



    /**
     * Constructor.
     */
    public function __construct() {
        

        #load_plugin_textdomain( 'neosconnectorforfakturama', false, dirname(plugin_basename(__FILE__)).'/lang/' );
        
        add_action( 'admin_menu', array( $this, 'ncff_add_plugin_page' ) );
        //add_action( 'admin_init', array( $this, 'page_init' ) );

        $timezones =
            array (
                '(GMT-12:00) International Date Line West' => 'Pacific/Wake',
                '(GMT-11:00) Midway Island' => 'Pacific/Apia',
                '(GMT-11:00) Samoa' => 'Pacific/Apia',
                '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
                '(GMT-09:00) Alaska' => 'America/Anchorage',
                '(GMT-08:00) Pacific Time (US &amp; Canada); Tijuana' => 'America/Los_Angeles',
                '(GMT-07:00) Arizona' => 'America/Phoenix',
                '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
                '(GMT-07:00) La Paz' => 'America/Chihuahua',
                '(GMT-07:00) Mazatlan' => 'America/Chihuahua',
                '(GMT-07:00) Mountain Time (US &amp; Canada)' => 'America/Denver',
                '(GMT-06:00) Central America' => 'America/Managua',
                '(GMT-06:00) Central Time (US &amp; Canada)' => 'America/Chicago',
                '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
                '(GMT-06:00) Mexico City' => 'America/Mexico_City',
                '(GMT-06:00) Monterrey' => 'America/Mexico_City',
                '(GMT-06:00) Saskatchewan' => 'America/Regina',
                '(GMT-05:00) Bogota' => 'America/Bogota',
                '(GMT-05:00) Eastern Time (US &amp; Canada)' => 'America/New_York',
                '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
                '(GMT-05:00) Lima' => 'America/Bogota',
                '(GMT-05:00) Quito' => 'America/Bogota',
                '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
                '(GMT-04:00) Caracas' => 'America/Caracas',
                '(GMT-04:00) La Paz' => 'America/Caracas',
                '(GMT-04:00) Santiago' => 'America/Santiago',
                '(GMT-03:30) Newfoundland' => 'America/St_Johns',
                '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
                '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
                '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
                '(GMT-03:00) Greenland' => 'America/Godthab',
                '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
                '(GMT-01:00) Azores' => 'Atlantic/Azores',
                '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
                '(GMT) Casablanca' => 'Africa/Casablanca',
                '(GMT) Edinburgh' => 'Europe/London',
                '(GMT) Greenwich Mean Time : Dublin' => 'Europe/London',
                '(GMT) Lisbon' => 'Europe/London',
                '(GMT) London' => 'Europe/London',
                '(GMT) Monrovia' => 'Africa/Casablanca',
                '(GMT+01:00) Amsterdam' => 'Europe/Berlin',
                '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
                '(GMT+01:00) Berlin' => 'Europe/Berlin',
                '(GMT+01:00) Bern' => 'Europe/Berlin',
                '(GMT+01:00) Bratislava' => 'Europe/Belgrade',
                '(GMT+01:00) Brussels' => 'Europe/Paris',
                '(GMT+01:00) Budapest' => 'Europe/Belgrade',
                '(GMT+01:00) Copenhagen' => 'Europe/Paris',
                '(GMT+01:00) Ljubljana' => 'Europe/Belgrade',
                '(GMT+01:00) Madrid' => 'Europe/Paris',
                '(GMT+01:00) Paris' => 'Europe/Paris',
                '(GMT+01:00) Prague' => 'Europe/Belgrade',
                '(GMT+01:00) Rome' => 'Europe/Berlin',
                '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
                '(GMT+01:00) Skopje' => 'Europe/Sarajevo',
                '(GMT+01:00) Stockholm' => 'Europe/Berlin',
                '(GMT+01:00) Vienna' => 'Europe/Berlin',
                '(GMT+01:00) Warsaw' => 'Europe/Sarajevo',
                '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
                '(GMT+01:00) Zagreb' => 'Europe/Sarajevo',
                '(GMT+02:00) Athens' => 'Europe/Istanbul',
                '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
                '(GMT+02:00) Cairo' => 'Africa/Cairo',
                '(GMT+02:00) Harare' => 'Africa/Johannesburg',
                '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
                '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
                '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
                '(GMT+02:00) Kyiv' => 'Europe/Helsinki',
                '(GMT+02:00) Minsk' => 'Europe/Istanbul',
                '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
                '(GMT+02:00) Riga' => 'Europe/Helsinki',
                '(GMT+02:00) Sofia' => 'Europe/Helsinki',
                '(GMT+02:00) Tallinn' => 'Europe/Helsinki',
                '(GMT+02:00) Vilnius' => 'Europe/Helsinki',
                '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
                '(GMT+03:00) Kuwait' => 'Asia/Riyadh',
                '(GMT+03:00) Moscow' => 'Europe/Moscow',
                '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
                '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
                '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
                '(GMT+03:00) Volgograd' => 'Europe/Moscow',
                '(GMT+03:30) Tehran' => 'Asia/Tehran',
                '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
                '(GMT+04:00) Baku' => 'Asia/Tbilisi',
                '(GMT+04:00) Muscat' => 'Asia/Muscat',
                '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
                '(GMT+04:00) Yerevan' => 'Asia/Tbilisi',
                '(GMT+04:30) Kabul' => 'Asia/Kabul',
                '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
                '(GMT+05:00) Islamabad' => 'Asia/Karachi',
                '(GMT+05:00) Karachi' => 'Asia/Karachi',
                '(GMT+05:00) Tashkent' => 'Asia/Karachi',
                '(GMT+05:30) Chennai' => 'Asia/Calcutta',
                '(GMT+05:30) Kolkata' => 'Asia/Calcutta',
                '(GMT+05:30) Mumbai' => 'Asia/Calcutta',
                '(GMT+05:30) New Delhi' => 'Asia/Calcutta',
                '(GMT+05:45) Kathmandu' => 'Asia/Katmandu',
                '(GMT+06:00) Almaty' => 'Asia/Novosibirsk',
                '(GMT+06:00) Astana' => 'Asia/Dhaka',
                '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
                '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
                '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
                '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
                '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
                '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
                '(GMT+07:00) Jakarta' => 'Asia/Bangkok',
                '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
                '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
                '(GMT+08:00) Chongqing' => 'Asia/Hong_Kong',
                '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
                '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
                '(GMT+08:00) Kuala Lumpur' => 'Asia/Singapore',
                '(GMT+08:00) Perth' => 'Australia/Perth',
                '(GMT+08:00) Singapore' => 'Asia/Singapore',
                '(GMT+08:00) Taipei' => 'Asia/Taipei',
                '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
                '(GMT+08:00) Urumqi' => 'Asia/Hong_Kong',
                '(GMT+09:00) Osaka' => 'Asia/Tokyo',
                '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
                '(GMT+09:00) Seoul' => 'Asia/Seoul',
                '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
                '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
                '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
                '(GMT+09:30) Darwin' => 'Australia/Darwin',
                '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
                '(GMT+10:00) Canberra' => 'Australia/Sydney',
                '(GMT+10:00) Guam' => 'Pacific/Guam',
                '(GMT+10:00) Hobart' => 'Australia/Hobart',
                '(GMT+10:00) Melbourne' => 'Australia/Sydney',
                '(GMT+10:00) Port Moresby' => 'Pacific/Guam',
                '(GMT+10:00) Sydney' => 'Australia/Sydney',
                '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
                '(GMT+11:00) Magadan' => 'Asia/Magadan',
                '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
                '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
                '(GMT+12:00) Auckland' => 'Pacific/Auckland',
                '(GMT+12:00) Fiji' => 'Pacific/Fiji',
                '(GMT+12:00) Kamchatka' => 'Pacific/Fiji',
                '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
                '(GMT+12:00) Wellington' => 'Pacific/Auckland',
                '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu',
            );
        
     
        
        

        $this->optionsp = array(
            array("name" => __('Debug', NCFF_TEXTDOMAIN),
                "desc" => __('In debug mode, the object is output on the Ajax page.', NCFF_TEXTDOMAIN),
                "id" => NCFF_TEXTDOMAIN."_debug",
                "type" => "select",
                "options" => array('true'=> __('On', NCFF_TEXTDOMAIN), 'false'=> __('Off', NCFF_TEXTDOMAIN)),
                "std" => "false"
            ),

            array("name" => __('Timezone', NCFF_TEXTDOMAIN),
                "desc" => "",
                "id" => NCFF_TEXTDOMAIN."_timezone",
                "type" => "select",
                "options" => array_flip($timezones),
                "std" => "(GMT+01:00) Berlin"
            ),




        );


    }






    /**
     * Add options page
     */
    public function ncff_add_plugin_page()
    {


        #var_dump($_REQUEST);
        #var_dump(basename(__FILE__));
        if( isset($_REQUEST['formaction']))
        {
            if ( $_GET['tab'] == "settings_options" ) {
                if ('save' == $_REQUEST['formaction']) {
                    foreach ($this->optionsp as $value) {
                        if(isset($value['id']))
                        {
                            if (isset($_REQUEST[$value['id']])) {
                                update_option($value['id'], sanitize_text_field($_REQUEST[$value['id']]));
                            } else {
                                delete_option($value['id']);
                            }
                        }

                    }


                    #header("Location: themes.php?page=options.php&saved=true");
                    #die;
                } else if ('reset_all' == $_REQUEST['formaction']) {
                    foreach ($this->optionsp as $value) {
                        if(isset($value['id'])) {
                            delete_option($value['id']);
                        }
                    }


                    #header("Location: themes.php?page=options.php&" . $_REQUEST['formaction'] . "=true");
                    #die;
                }

            }
        }



        // This page will be under "Settings"
        add_options_page(
            __( 'Neos Connector for Fakturama', NCFF_TEXTDOMAIN ),
            __( 'Fakturama', NCFF_TEXTDOMAIN ),
            'manage_options',
            NCFF_TEXTDOMAIN,
            array( $this, 'ncff_create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function ncff_create_admin_page()
    {
        $active_tab = '';
         if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        } else {
            $active_tab = 'info_options';
        } // end if/else


        
        $this->options = get_option( 'fakturama_setting' );
        #var_dump($plugin_data);
        ?>
        <div class="wrap">
            
            <h2><?php echo esc_html(NCFF_PLUGINDATA['Name'] . " Version " . NCFF_PLUGINDATA['Version']); ?></h2>
            <?php settings_errors();

            

            ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=<?php echo NCFF_TEXTDOMAIN ?>&tab=info_options" class="nav-tab <?php echo $active_tab == 'info_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Information', NCFF_TEXTDOMAIN ); ?></a>
                <a href="?page=<?php echo NCFF_TEXTDOMAIN ?>&tab=settings_options" class="nav-tab <?php echo $active_tab == 'settings_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', NCFF_TEXTDOMAIN ); ?></a>
                <a href="?page=<?php echo NCFF_TEXTDOMAIN ?>&tab=help_options" class="nav-tab <?php echo $active_tab == 'help_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Help', NCFF_TEXTDOMAIN ); ?></a>


            </h2>

                <?php
                // This prints out all hidden setting fields
                if( $active_tab == 'settings_options' ) {

                    echo '<form method="post" action=""  name="form">';

                    $this->ncff_create_form($this->optionsp);


                    ?>

                    <br>
                    <br>
                    <input name="save" type="button" value="<?php _e( 'Save', NCFF_TEXTDOMAIN ); ?>" class="button button-primary" onclick="submit_form(this)" />
                    <input name="reset_all" type="button" value="<?php _e( 'Reset to default values', NCFF_TEXTDOMAIN ); ?>" class="button" onclick="submit_form(this)" />
                    <input type="hidden" name="formaction" value="default" />



                    </form>

                    <script type="application/javascript">
                        function submit_form(element){

                            document.forms['form']['formaction'].value = element.name;
                            document.forms['form'].submit();
                        }
                    </script>

                    <?php



                }else if ($active_tab == 'info_options'){
                    ?>
                    <h2><?php _e('Fakturama Connector set up', NCFF_TEXTDOMAIN);?></h2>
                  
                    <p><b><?php _e('Step 1:', NCFF_TEXTDOMAIN);?></b> <?php _e('Insert your username and password into Fakturama', NCFF_TEXTDOMAIN);?> <br><?php _e('File -> Settings -> Webshop', NCFF_TEXTDOMAIN);?></p>
                    <p><b><?php _e('Step 2:', NCFF_TEXTDOMAIN);?></b> <?php _e('Insert the URL below in Fakturama', NCFF_TEXTDOMAIN);?><br><?php _e('File -> Settings -> Webshop -> Webshop URL', NCFF_TEXTDOMAIN);?></p>

                    <b>URL: "<?php echo get_bloginfo('url'); ?>/wp-admin/admin-ajax.php?do=fakturama" <br></b>
                    <p><?php _e('That\'s it!', NCFF_TEXTDOMAIN);?></p>
                  
                    <?php #echo '<p>Entwickelt von '.esc_html($plugin_data['AuthorName']).'</p>';?>
                    

                    <?php
                }
                else if ($active_tab == 'help_options'){
                    ?>

                    <h2><?php _e('Frequently Asked Questions', NCFF_TEXTDOMAIN);?></h2>
                    <p><?php _e('A Faq has been set up on my website for the Fakturama connector.', NCFF_TEXTDOMAIN);?>
                        <br /><a target="_blank" href="<?php echo esc_url(NCFF_PLUGINDATA['PluginURI'])?>/neos-connector-for-fakturama-faq/"><?php _e('Click here for FAQ', NCFF_TEXTDOMAIN);?></a></p>

                    <h2><?php _e('You could not solve your problem?', NCFF_TEXTDOMAIN);?></h2>
                    <p><?php _e('You can also find help in the official Forum of', NCFF_TEXTDOMAIN);?> <a target="_blank" href="http://forum.fakturama.info/index.php" >Fakturama</a></p>

                    <p><?php _e('More information is available on my Website:', NCFF_TEXTDOMAIN);?> <a target="_blank" href="<?php echo esc_url(NCFF_PLUGINDATA['PluginURI'])?>"><?php echo esc_html(NCFF_PLUGINDATA['PluginURI'])?></a></p>


                    <h2><?php _e('If you could not solve your problem with the above help, write me!', NCFF_TEXTDOMAIN);?></h2>
                    <p><b>fakturama@neosuniverse.de</b>
                        <br><?php _e('Note: 1) Exact problem description 2) With screenshot 3)Webshopimport.log by Fakturama', NCFF_TEXTDOMAIN);?>
                        <br><?php _e('2,3 Please in the E-mail as attachment.', NCFF_TEXTDOMAIN);?></p>

                    <p><?php _e('In the forum I am not active, therefore should not solve the problem please write.', NCFF_TEXTDOMAIN);?></p>
                    <p><u><?php _e('If you want to help with translation then write me.', NCFF_TEXTDOMAIN);?></u></p>
                    <?php
                }




        ?>

        </div><?php
    }


    /* functions to andale the options array  */

    private function ncff_mnt_get_formatted_page_array() {
        global $suffusion_pages_array;
        if (isset($suffusion_pages_array) && $suffusion_pages_array != null) {
            return $suffusion_pages_array;
        }
        $ret = array();
        $pages = get_pages('sort_column=menu_order');
        if ($pages != null) {
            foreach ($pages as $page) {
                if (is_null($suffusion_pages_array)) {
                    $ret[$page->ID] = array ("title" => $page->post_title, "depth" => count(get_ancestors($page->ID, 'page')));
                }
            }
        }
        if ($suffusion_pages_array == null) {
            $suffusion_pages_array = $ret;
            return $ret;
        }
        else {
            return $suffusion_pages_array;
        }
    }

    private function ncff_mnt_get_category_array() {
        global $suffusion_category_array;
        if (isset($suffusion_category_array) && $suffusion_category_array != null) {
            return $suffusion_category_array;
        }
        $ret = array();
        $args = array(
            'orderby' => 'name',
            'parent' => 0
        );
        $categories = get_categories( $args );
        if($categories != null){
            foreach ($categories as $category) {
                if (is_null($suffusion_category_array)) {
                    $ret[$category->cat_ID] = array ("name" => $category->name, "number" => $category->count);
                }
            }
        }

        if ($suffusion_category_array == null) {
            $suffusion_category_array = $ret;
            return $ret;
        }
        else {
            return $suffusion_category_array;
        }
    }

    private function ncff_create_opening_tag($value) {
        $group_class = "";
        if (isset($value['grouping'])) {
            $group_class = "suf-grouping-rhs";
        }
        echo '<div class="suf-section fix">'."\n";
        if ($group_class != "") {
            echo "<div class='$group_class fix'>\n";
        }
        if (isset($value['name'])) {
            echo "<h3>" . esc_html($value['name']) . "</h3>\n";
        }
        if (isset($value['desc']) && !(isset($value['type']) && $value['type'] == 'checkbox')) {
            echo esc_html($value['desc'])."<br />";
        }
        if (isset($value['note'])) {
            echo "<span class=\"note\">".esc_html($value['note'])."</span><br />";
        }
    }

    /**
     * Creates the closing markup for each option.
     *
     * @param $value
     * @return void
     */
    private function ncff_create_closing_tag($value) {
        if (isset($value['grouping'])) {
            echo "</div>\n";
        }
        //echo "</div><!-- suf-section -->\n";
        echo "</div>\n";
    }

    private function ncff_create_suf_header_3($value) { echo '<h3 class="suf-header-3">'.esc_html($value['name'])."</h3>\n"; }

    private function create_section_for_text($value) {
        $this->ncff_create_opening_tag($value);
        $text = "";
        if (get_option($value['id']) === FALSE) {
            $text = $value['std'];
        }
        else {
            $text = get_option($value['id']);
        }

        echo '<input type="text" id="'.esc_attr($value['id']).'" placeholder="enter your title" name="'.esc_attr($value['id']).'" value="'.esc_attr($text).'" />'."\n";
        $this->ncff_create_closing_tag($value);
    }

    private function ncff_create_section_for_textarea($value) {
        $this->ncff_create_opening_tag($value);
        echo '<textarea name="'.esc_attr($value['id']).'" type="textarea" cols="" rows="">'."\n";
        if ( get_option( $value['id'] ) != "") {
            echo esc_textarea(get_option( $value['id'] ));
        }
        else {
            echo esc_textarea($value['std']);
        }
        echo '</textarea>';
        $this->ncff_create_closing_tag($value);
    }


    private function ncff_create_section_for_radio($value) {
        $this->ncff_create_opening_tag($value);
        foreach ($value['options'] as $option_value => $option_text) {
            $checked = ' ';
            if (get_option($value['id']) == $option_value) {
                $checked = ' checked="checked" ';
            }
            else if (get_option($value['id']) === FALSE && $value['std'] == $option_value){
                $checked = ' checked="checked" ';
            }
            else {
                $checked = ' ';
            }
            echo '<div class="mnt-radio"><input type="radio" name="'.esc_attr($value['id']).'" value="'.
                esc_attr($option_value).'" '.$checked."/>".esc_html($option_text)."</div>\n";
        }
        $this->ncff_create_closing_tag($value);
    }



    private function ncff_create_section_for_category_select($page_section,$value) {
        $this->ncff_create_opening_tag($value);
        $all_categoris='';
       
        #echo '<p><strong>'.$page_section.':</strong></p>';
        echo "<select id='".$value['id']."' class='post_form' name='".$value['id']."' value='true'>\n";

        foreach ($value['options'] as $option_value => $option_list) {
            $checked = ' ';
            #echo 'value_id=' . $value['id'] .' value_id=' . get_option($value['id']) . ' options_value=' . $option_value;
            if (get_option($value['id']) == $option_value) {
                $checked = ' selected="selected"" ';
            }
            else if (get_option($value['id']) === FALSE && $value['std'] == $option_value){
                $checked = ' selected="selected"" ';
            }
            else {
                $checked = '';
            }
            
          if($value['id'] == "timezone")
          {
              echo '<option value="'.esc_attr($option_value).'" class="level-0" '.$checked.'  />'.esc_html($option_value)."</option>\n";
          }
          else
          {
              echo '<option value="'.esc_attr($option_value).'" class="level-0" '.$checked.'  />'.esc_html($option_list)."</option>\n";
          }
            
            //$all_categoris .= $option_list['name'] . ',';
        }
        echo "</select>\n ";
        //echo '<script>jQuery("#all").val("'.$all_categoris.'")</\script>';
        $this->ncff_create_closing_tag($value);
    }


    private function ncff_create_form($options) {
        foreach ($options as $value) {
            switch ( $value['type'] ) {
                case "sub-section-3":
                    $this->ncff_create_suf_header_3($value);
                    break;

                case "text";
                    $this->ncff_create_section_for_text($value);
                    break;

                case "textarea":
                    $this->ncff_create_section_for_textarea($value);
                    break;

                case "radio":
                    $this->ncff_create_section_for_radio($value);
                    break;

                case "select":
                    $this->ncff_create_section_for_category_select('first section',$value);
                    break;
                case "select-2":
                    $this->ncff_create_section_for_category_select('second section',$value);
                    break;
            }
        }

        ?>






    <?php }

}

new NeosFaktura_Settings_Page();