<?php
namespace JOYSPORT;
/**
 * Plugin Name: Joysport football
 * Plugin URI: http://joysport.club
 * Description: manage your own football league
 * Author: Joysport INC.
 * Version: 1.0.0
 * Text Domain: joysport-football
 */


//add_action('init', 'register_post_types');
//
//function register_post_types() {
//	register_post_type( 'jsp-match',
//		array(
//			'labels'              => array(
//				'name'               => __( 'Clubs', 'wp-club-manager' ),
//				'singular_name'      => __( 'Club', 'wp-club-manager' ),
//				'add_new'            => __( 'Add New', 'wp-club-manager' ),
//				'all_items'          => __( 'All Clubs', 'wp-club-manager' ),
//				'add_new_item'       => __( 'Add New Club', 'wp-club-manager' ),
//				'edit_item'          => __( 'Edit Club', 'wp-club-manager' ),
//				'new_item'           => __( 'New Club', 'wp-club-manager' ),
//				'view_item'          => __( 'View Club', 'wp-club-manager' ),
//				'search_items'       => __( 'Search Clubs', 'wp-club-manager' ),
//				'not_found'          => __( 'No clubs found', 'wp-club-manager' ),
//				'not_found_in_trash' => __( 'No clubs found in trash' ),
//				'parent_item_colon'  => __( 'Parent Club:', 'wp-club-manager' ),
//				'menu_name'          => __( 'Clubs', 'wp-club-manager' )
//			),
//			'hierarchical'        => false,
//			'supports'            => array( 'title', 'editor', 'thumbnail' ),
//			'public'              => true,
//			'show_ui'             => true,
//			'show_in_menu'        => true,
//			'show_in_nav_menus'   => false,
//			'menu_icon'           => 'dashicons-shield',
//			'publicly_queryable'  => true,
//			'exclude_from_search' => true,
//			'has_archive'         => false,
//			'query_var'           => true,
//			'can_export'          => true,
//			'rewrite'             => false,
////		'capability_type'      => 'wpcm_club',
//			'map_meta_cap'        => true,
//		) );
//}

//	function match_players_meta_box() {
//		add_meta_box();
//	}

//require_once ('Container.php');
//require_once ('Superman.php');
//$container = new Container();
//$container->bind('superman',function ($container, $concrete){
//	return new Superman($container->make($concrete));
//});
//
//$container->bind('xpower', function (){
//	return new XPower;
//});
//
//$container->bind('ultrabomb',function (){
//	return new UltraBomb;
//});
//
//
//$superman1 = $container->make('superman','xpower');
//$superman1->module->activate(['monster', 'catgirl']);



