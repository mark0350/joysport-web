<?php
/*
Plugin Name: Fewen Article
Plugin URI: http://joysport.club/
Description: Fewen Article is a plugin that can capture articles.
Version: 1.0.0
Author: fewen
Author URI: http://joysport.club/
Text Domain: fewen-article
Domain Path: /lang/

Copyright 2017 Joysport inc.
*/

add_action( 'admin_menu', 'main_menu');

function main_menu(){
	add_menu_page( 'article', 'article', 'manage_options', 'article', 'show');
	remove_menu_page('article');
}

function show(){
	echo 123;
}
