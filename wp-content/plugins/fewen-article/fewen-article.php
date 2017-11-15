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

// 加载 css js
add_action( 'admin_enqueue_scripts', 'admin_enqueue_scripts' );



function view( $file, $args=[] ){
    foreach ($args as $k => $v){
        $$k = $v;
    }

    include( $file . '.php' );
}


function main_menu(){
	add_menu_page( 'article', 'article', 'manage_options', 'article', 'show');
	remove_menu_page('article');
}

function show(){
	view('article');
}

function admin_enqueue_scripts(){
    wp_enqueue_style(
        'fewen-article',
        plugins_url( 'fewen-article/assets/css/main.css'  ),
        array(),
        '1.01',
        'screen'
    );
}



