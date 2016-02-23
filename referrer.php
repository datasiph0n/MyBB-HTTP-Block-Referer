<?php
/*
# HTTP Referer Block
# Originally for siph0n forum.
# Coded by: sn
*/

if(!defined("IN_MYBB"))
{
	die("No direct access allowed.");
}

$plugins->add_hook('global_start', 'referrer_main');


function referrer_info()
{
	return array(
		"name" 			=> "Block HTTP Referrers",
		"description" 	=> "Blocks restricted HTTP Referrers",
		"website"		=> "http://siph0n.in",
		"author"		=> "sn",
		"authorsite"	=> "http://siph0n.in",
		"version"		=> "1.0",
		"guid"			=> "",
		"codename"		=> str_replace('.php', '', basename(__FILE__)),
		"compatibility"	=> "18*"
	);
}

function referrer_main()
{	
	global $mybb;
	if($mybb->settings['http_referrer_enable'] == 1)
	{
		$referrerz = $_SERVER['HTTP_REFERER'];
		
		if(!empty($referrerz))
		{
			$array = $mybb->settings["sites_to_block"];
			if(strpos($array, ",") !== FALSE) {
				$array = explode(",", $array);
			}
			$locka = strposa($referrerz, $array);
			if($locka == 1) {
				header('Location: http://google.com');
			}
		}
	}
}

function strposa($haystack, $needle, $offset=0)
{
        if(!is_array($needle)) $needle = array($needle);
        foreach($needle as $query) {
                if(strpos($haystack, $query, $offset) !== false) return true;
        }
        return false;
}

function referrer_install() // Called when "Install" button is pressed
{
	global $db, $mybb, $templates;
	$settings_group = array(
    	'name' => 'referrer_block',
    	'title' => 'Referrer Block',
    	'description' => 'This is my plugin and it does some things',
    	'disporder' => 5, // The order your setting group will display
    	'isdefault' => 0
	);
	$gid = $db->insert_query("settinggroups", $settings_group);
	$setting_array = array(
    	'http_referrer_enable' => array(
        	'title' => 'HTTP Referrer Block',
        	'description' => 'Do we want to activate this plugin?:',
        	'optionscode' => 'yesno',
        	'value' => '1', // Default
        	'disporder' => 1
    	),
	    'sites_to_block' => array(
	        'title' => 'Sites to block:',
	        'description' => 'Please enter the sites to block followed by a ",":',
	        'optionscode' => "text",
	        'value' => "hackforums.net,",
	        'disporder' => 2
	    ),
	);
	foreach($setting_array as $name => $setting)
	{
    	$setting['name'] = $name;
    	$setting['gid'] = $gid;
	    $db->insert_query('settings', $setting);
	}
	rebuild_settings();
}

function referrer_is_installed()
{
	global $mybb;
	if($mybb->settings["http_referrer_enable"])
	{
		return true;
	}
	return false;
}

function referrer_uninstall()
{
	global $db;

	$db->delete_query('settings', "name IN ('http_referrer_enable','sites_to_block','enable_lasers')");
	$db->delete_query('settinggroups', "name = 'referrer_block'");
	rebuild_settings();
}

function referrer_activate()
{

}

function referrer_deactivate()
{

}

