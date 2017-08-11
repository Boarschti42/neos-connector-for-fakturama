<?php
/**
 * Created by IntelliJ IDEA.
 * User: xoxoxo
 * Date: 16.05.16
 * Time: 17:56
 */

class NeosFaktura_Settings_Page {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $optionsp;

    private $plugin = 'fakturamac';


    /**
     * Constructor.
     */
    public function __construct() {
        
        if ( ! defined('ABSPATH') ) {
            return;
        }
        
        load_plugin_textdomain( 'fakturamac', false, dirname(plugin_basename(__FILE__)).'/lang/' );
        
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
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
            array("name" => "Debug",
                "desc" => "Im Debug Modus wird auf der Ajax-Seite das Object ausgegeben.",
                "id" => "NeosFaktura_debug",
                "type" => "select",
                "options" => array('true'=>'An', 'false'=>'Aus'),
                "std" => "false"
            ),

            array("name" => __('Timezone', 'fakturamac'),
                "desc" => "",
                "id" => "NeosFaktura_timezone",
                "type" => "select",
                "options" => $timezones,
                "std" => "(GMT+01:00) Berlin"
            ),




        );


    }




    /**
     * Add options page
     */
    public function add_plugin_page()
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
            __( 'Neos Connector for Fakturama', 'fakturamac' ),
            __( 'Fakturama', 'fakturamac' ),
            'manage_options',
            $this->plugin,
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        $active_tab = '';
         if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        } else {
            $active_tab = 'info_options';
        } // end if/else


        
        $this->options = get_option( 'fakturama_setting' );
        $plugin_data = get_plugin_data(__DIR__.'/neosconnectorforfakturama.php');
        #var_dump($plugin_data);
        ?>
        <div class="wrap">
            
            <h2><?php echo esc_html($plugin_data['Name'] . " Version " . $plugin_data['Version']); ?></h2>
            <?php settings_errors();

            

            ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=<?php echo $this->plugin ?>&tab=info_options" class="nav-tab <?php echo $active_tab == 'info_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Information', 'fakturamac' ); ?></a>
                <a href="?page=<?php echo $this->plugin ?>&tab=settings_options" class="nav-tab <?php echo $active_tab == 'settings_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', 'fakturamac' ); ?></a>
                <a href="?page=<?php echo $this->plugin ?>&tab=help_options" class="nav-tab <?php echo $active_tab == 'help_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Help', 'fakturamac' ); ?></a>


            </h2>

                <?php
                // This prints out all hidden setting fields
                if( $active_tab == 'settings_options' ) {

                    echo '<form method="post" action=""  name="form">';

                    $this->create_form($this->optionsp);


                    ?>

                    <br>
                    <br>
                    <input name="save" type="button" value="Save" class="button button-primary" onclick="submit_form(this)" />
                    <input name="reset_all" type="button" value="Reset to default values" class="button" onclick="submit_form(this)" />
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
                    echo '<h2>Fakturama Connector einrichten</h2>';
                  
                    echo '<p><b>Schritt 1:</b> WP Benutzername und Passwort in Fakturama einf&uuml;gen <br>Datei -> Einstellungen -> Webshop</p>';
                    echo '<p><b>Schritt 2:</b> Die unten stehende URL in Fakturama einf&uuml;gen <br>Datei -> Einstellungen -> Webshop -> Webshop URL</p>';

                    echo '<b>URL: '.get_bloginfo('url')."/wp-admin/admin-ajax.php?do=fakturama <br></b>";                  
                    echo '<p>Das wars!</p>';
                  
                    echo '<p>Entwickelt von '.esc_html($plugin_data['AuthorName']).'</p>';
                    





                    echo '<h2>Falls du meine Arbeit unterstützen möchtest.</h2>';
                    echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="hosted_button_id" value="7B7XZ227HN5GC">
                            <input type="image" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">
                            <img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
                            </form>
                            ';

                }
                else if ($active_tab == 'help_options'){

                    echo '<h2>H&auml;fige Fragen?</h2>';
                    echo '<p>Auf meiner Webseite wurde f&uuml;r den Fakturama-Connector eine Faq eingerichtet. Bitte zuerst dort nach lesen evtl. kann das Problem schon vorher gel&ouml;&szlig;t
                            werden. <br /><a target="_blank" href="'. esc_url($plugin_data['PluginURI']).'/neos-connector-for-fakturama-faq/">Hier geht es zur FAQ</a></p>';

                    echo '<h2>Fehler, Probleme oder du möchtest das Plugin &Uuml;bersetzen</h2>';
                    echo '<p>Weitere Informationen gibt es auf meiner Webseite <a target="_blank" href="'. esc_url($plugin_data['PluginURI']).'">'.esc_html($plugin_data['PluginURI']).'</a></p>';
                    echo '<p>Schreibe mir an <b>fakturama@neosuniverse.de</b><br>Am besten mit möglichst genauer Fehlerbeschreibung und evtl. mit Screenshots.</p>';
                    echo '<p>Hilfe findest du auch im Offizillen Forum von <a target="_blank" href="http://forum.fakturama.info/index.php" >Fakturama</a></p>';

                    echo '<p>Im Forum bin ich nicht aktiv, deshalb sollte sich das Problem nicht l&ouml;sen bitte schreiben.</p>';

                }




        ?></div><?php
    }


    /* functions to andale the options array  */

    private function mnt_get_formatted_page_array() {
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

    private function mnt_get_category_array() {
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

    private function create_opening_tag($value) {
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
    private function create_closing_tag($value) {
        if (isset($value['grouping'])) {
            echo "</div>\n";
        }
        //echo "</div><!-- suf-section -->\n";
        echo "</div>\n";
    }

    private function create_suf_header_3($value) { echo '<h3 class="suf-header-3">'.esc_html($value['name'])."</h3>\n"; }

    private function create_section_for_text($value) {
        $this->create_opening_tag($value);
        $text = "";
        if (get_option($value['id']) === FALSE) {
            $text = $value['std'];
        }
        else {
            $text = get_option($value['id']);
        }

        echo '<input type="text" id="'.esc_attr($value['id']).'" placeholder="enter your title" name="'.esc_attr($value['id']).'" value="'.esc_attr($text).'" />'."\n";
        $this->create_closing_tag($value);
    }

    private function create_section_for_textarea($value) {
        $this->create_opening_tag($value);
        echo '<textarea name="'.esc_attr($value['id']).'" type="textarea" cols="" rows="">'."\n";
        if ( get_option( $value['id'] ) != "") {
            echo esc_textarea(get_option( $value['id'] ));
        }
        else {
            echo esc_textarea($value['std']);
        }
        echo '</textarea>';
        $this->create_closing_tag($value);
    }


    private function create_section_for_radio($value) {
        $this->create_opening_tag($value);
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
        $this->create_closing_tag($value);
    }



    private function create_section_for_category_select($page_section,$value) {
        $this->create_opening_tag($value);
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
        $this->create_closing_tag($value);
    }


    private function create_form($options) {
        foreach ($options as $value) {
            switch ( $value['type'] ) {
                case "sub-section-3":
                    $this->create_suf_header_3($value);
                    break;

                case "text";
                    $this->create_section_for_text($value);
                    break;

                case "textarea":
                    $this->create_section_for_textarea($value);
                    break;

                case "radio":
                    $this->create_section_for_radio($value);
                    break;

                case "select":
                    $this->create_section_for_category_select('first section',$value);
                    break;
                case "select-2":
                    $this->create_section_for_category_select('second section',$value);
                    break;
            }
        }

        ?>






    <?php }

}

new NeosFaktura_Settings_Page();