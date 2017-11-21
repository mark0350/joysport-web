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

add_action( 'init', 'wptuts_buttons' );

add_action( 'views_edit-post', 'app_views' );

add_action('post_row_actions', 'change_row_action', 10 ,2);

add_filter( 'bulk_actions-' . 'edit-post', '__return_empty_array' );

add_filter( 'manage_posts_columns' , 'manage_columns' );

add_filter( 'edit_posts_per_page' , 'set_posts_per_page', 99, 2 );


function wptuts_buttons() {
	add_filter( "mce_external_plugins", "wptuts_add_buttons" );
	add_filter( 'mce_buttons', 'wptuts_register_buttons' );
}
function wptuts_add_buttons( $plugin_array ) {
	$plugin_array['wptuts'] = plugins_url( 'fewen-article/assets/js/main.js'  );
	return $plugin_array;
}
function wptuts_register_buttons( $buttons ) {
	array_push( $buttons, 'clear_before', 'clear_after' ); // clearbefore', 'clearafter
	return $buttons;
}



function view( $file, $args=[] ){
    foreach ($args as $k => $v){
        $$k = $v;
    }

    include( $file . '.php' );
}


function main_menu(){
	add_menu_page( 'article', 'article', 'manage_options', 'article', 'show');
//	remove_menu_page('article');
}

function show(){
	view('article');
}

function admin_enqueue_scripts(){

		wp_enqueue_style(
			'fewen-article',
			plugins_url( 'fewen-article/assets/css/main.css'  ),
			array(),
			'1.02',
			'screen'
		);


}

function app_views($views){

	global $locked_post_status;

	$post_type = 'post';

	if ( !empty($locked_post_status) )
		return array();

	$num_posts = wp_count_posts( $post_type, 'readable' );
	$total_posts = array_sum( (array) $num_posts );


	// Subtract post types that are not included in the admin all list.
	foreach ( get_post_stati( array( 'show_in_admin_all_list' => false ) ) as $state ) {
		$total_posts -= $num_posts->$state;
	}

	$all_view_count = get_total_post_view_count(get_current_user_id());
	$today_view_count = get_today_post_view_count(get_current_user_id());
	unset($views);

	$views['all'] = "文章总数 <span class='count'>({$total_posts})</span>";
	$views['all_view_count'] = "阅读总数 <span class='count'>({$all_view_count})</span>";
	$views['today_view_count'] = "今日点击 <span class='count'>({$today_view_count})</span>";

	return $views;
}

function change_row_action($actions, $post){
	unset( $actions['inline hide-if-no-js'] );
	unset($actions['view']);

//	var_dump($post);
//	die();

	$view_count = get_post_view_count($post->ID);


	$actions['others'] = "<span class='other'>{$view_count} | {$post->post_date} | {$post->post_status}</span>";

//	var_dump($actions);
//	die();

	return $actions;

}

function manage_columns($columns){
	unset($columns['cb']);
	return $columns;
}


function set_posts_per_page($per_page, $post_type){
	return 10;

}
