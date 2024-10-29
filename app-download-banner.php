<?php
/*
Plugin Name: Application download banner
Description: Want make your visitor come more often? with this plugin
you can <strong>make your own visitors community</strong>
by few step and they download your apps in IOS or Android!
Version: 1.0.0
Author: www.script.co.il
Author URI: https://www.script.co.il/
License: GPLv2 or later
Text Domain: app-download-banner








/* REGISTERING MENU IN ADMIN DASHBOARD */

if (!function_exists('adb_register_settings_menu')){
    function adb_register_settings_menu() {
        $page_title_tab = __('Application banner Settings','app-download-banner');
        $page_title_menu = __('App banner Settings','app-download-banner');
        add_options_page($page_title_tab, $page_title_menu, 'manage_options', 'app-download-banner', 'adb_settings_page');
    }
    add_action('admin_menu', 'adb_register_settings_menu');    
}

if (!function_exists('adb_settings_page')){
    function adb_settings_page(){
        include_once dirname(__FILE__) . '/admin_form.php';
    }
}

if (!function_exists('adb_dashboard_colorpicker')){
    function adb_dashboard_colorpicker( $hook_suffix ) {
        if (!is_admin()) return;
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'adb-color-picker', plugins_url('assets/adminpanel.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), false, true );
        wp_enqueue_media();
    }
    add_action( 'admin_enqueue_scripts', 'adb_dashboard_colorpicker' );
}
    
    
if (!function_exists('adb_get_background_color')) {
    function adb_get_background_color(){
        $color = get_option('adb_link_background_color');
        if (!$color) $color = '#efefef';
        return $color;
    }
}
if (!function_exists('adb_get_text_color')) {
    function adb_get_text_color(){
        $color = get_option('adb_link_text_color');
        if (!$color) $color = '#333';
        return $color;
    }
}

if (!function_exists('adb_installation_param')){
    function adb_installation_param(){
        $param = array(
            'adb_is_active',
            'adb_link_googleplay',
            'adb_link_appstore',
            'adb_link_logo',
            'adb_link_title',
            'adb_link_description',
            'adb_link_text_color',
            'adb_link_background_color',
            );
            return $param;
    }
if (!function_exists('adb_app_installtion')){
    function adb_app_installtion(){
        foreach (adb_installation_param() as $adb_data){
            if (exist_option($adb_data)==false) add_option($adb_data, '');
        }
    }
    add_action('wp_head', 'adb_app_installtion', 1);
}

    
}


if (!function_exists('exist_option')){
    function exist_option( $arg ) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $db_options = $prefix.'options';
        $sql_query = 'SELECT * FROM ' . $db_options . ' WHERE option_name LIKE "' . $arg . '"';
        $results = $wpdb->get_results( $sql_query, OBJECT );
        if ( count( $results ) === 0 ) {
            return false;
        } else {
            return true;
        }
    }
}


    
    
    
    
/***************************************************************************
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
@@@ START OF FRONT PAGE, IF NOT MOBILE OR MAYBE THIS IS NOT FIRST VISIT @@@@
@@@ THEN LOAD USER DATA FROM SAVE THE PLUGIN WILL RETURN FOR EXITING @@@@@@@
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
***************************************************************************/




global $wp_session;


// ### DO NOT REMOVE COMMENT LINE BELOW ##################
// ### JUST FOR TESTING, THIS REMOVE SESSION IF EXISTS ###
//if (isset($wp_session['is_app'])){ unset($wp_session['is_app']); }
// #######################################################


if (!empty($wp_session['is_app'])) return;

$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");

$is_app = false;
global $is_mobile;
$is_mobile = ($iPhone || $iPad || $iPod) ? 'ios' : false;
$is_mobile = (!$is_mobile && $Android) ? 'android' : $is_mobile;

if (!$is_mobile) return;

switch ($is_mobile) {
    case 'android':
        $android_id = get_option('adb_link_googleplay');
        if (!$android_id) return;
        $android_data = parse_url($android_id);
        if (!empty($android_data['query'])){
            parse_str($android_data['query'], $android_data);
            if (!empty($android_data) && !empty($android_data['id']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == $android_data['id']){
                $is_app = true;
            }
        }
        
        break;
        
    case 'ios':
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'Safari')===false){
            $is_app = true;
        }
        break;
}

if ($is_app){
    $wp_session['is_app'] = true;
}else{
    if ($is_mobile){
        if (!empty($wp_session['is_app'])) return;
        if (!function_exists('adb_add_banner_front')){
            function adb_add_banner_front(){
                global $is_mobile;
                $data = array();
                foreach (adb_installation_param() as $param){
                    $data[$param] = get_option($param);
                }
                $app_url = '';
                if ($is_mobile=='ios'){ $app_url = $data['adb_link_appstore']; }
                if ($is_mobile=='android'){ $app_url = $data['adb_link_googleplay']; }
                if (!$app_url || !$data['adb_is_active']) return;

?>
<div class="adb-top-banner" style="background:<?php echo adb_get_background_color(); ?>;"><a href="<?php echo $app_url; ?>" target="_blank"><div>
    <div class="adb-close-banner"><div></div></div>
    <div class="adb-banner-content" style="color:<?php echo adb_get_text_color(); ?>;">
        <div class="adb-banner-headline"><span><?php echo $data['adb_link_title']; ?></span></span></div>
        <div class="adb-banner-description"><span><?php echo $data['adb_link_description']; ?></span></div>
    </div>
    <?php if (!empty($data['adb_link_logo'])){
?><div class="adb-banner-logo"><div><div class="adb-banner-logo-image"><img src="<?php echo $data['adb_link_logo']; ?>"></div></div></div><?php    
    } ?>
    
</div></a></div>




<?php
            }
        add_action('wp_head', 'adb_add_banner_front');
        }
    }
}

if (!function_exists('adb_register_assets')){
    function adb_register_assets() {
	    wp_enqueue_style( 'adb-stylesheet', plugins_url( 'assets/style.css', __FILE__ ) );
	    
        wp_enqueue_script( 'adb-script',plugins_url( 'assets/script.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
    }
    add_action( 'wp_enqueue_scripts', 'adb_register_assets' );
}
