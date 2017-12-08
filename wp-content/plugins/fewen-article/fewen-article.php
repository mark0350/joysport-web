<?php
/*
Plugin Name: Fewen Article
Plugin URI: http://joysport.club/
Description: Fewen Article is a plugin that can capture articles from url and edit the captured article like a breeze.
Version: 1.0.0
Author: Mark He
Author URI: http://markhe.me/
Text Domain: fewen-article
Domain Path: /lang/

Copyright 2017 Mark He.
*/

final class Fewen_Article{

    private static $instance;

    public static $path;

    public static $dir;

    public static function get_the_only_instance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {


        add_filter( 'media_buttons_context', array( $this, 'insert_form_tinymce_buttons' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script') );

        add_action('wp_ajax_capture_article', array($this, 'ajax_capture_article'));

        self::$path = rtrim(plugin_dir_url( __FILE__ ), '/');

        self::$dir = rtrim(plugin_dir_path(__FILE__), '/');

    }

    public function admin_enqueue_script(){
        wp_enqueue_style('fw-main-style', Fewen_Article::$path . '/assets/css/fw-main.css', array(), '1.00', 'screen');
    }

    public function insert_form_tinymce_buttons($context){
        global $pagenow;

        if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow) {
            return $context;
        }
        $html = '<style>
            span.fw-insert-article {
                color:#888;
                font: 400 18px/1 dashicons;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                display: inline-block;
                width: 18px;
                height: 18px;
                vertical-align: text-top;
                margin: 0 2px 0 0;
            }
        </style>';
        $html .= '<a href="#" class="button-secondary fw-insert-article"><span class="fw-insert-article dashicons dashicons-feedback"></span> ' . __( 'Add Article') . '</a>';
        ?>
        <?php

        wp_enqueue_script('fw-main', Fewen_Article::$path . '/assets/js/fw-main.js', array('jquery'),
            '1.05', true);
        return $context . ' ' . $html;
    }

    public function ajax_capture_article(){
        $url = $_REQUEST['for_url'];
        $article = $this->fetch_article($url);
        wp_send_json(['article'=>$article]);
    }

    public function fetch_article($url){
        include_once (self::$dir . '/simple_html_dom.php');
        return $this->getHTML($url);
    }

	public function getHTML($url)
	{
		$html = $this->get_contents($url);
		$html_copy = str_get_html($html);
		$url_parse = parse_url($url);

		$html_text = "";
		$div_class = '';
		if($url_parse['host'] == "info.51.ca")
		{
			if(strpos($url, "/m/") !== false)
			{
				$div_class = 'div[class=arcbody]';
			}else
			{
				$div_class = 'div[class=article-content clearfix]';
			}
			foreach($html_copy->find($div_class) as $element) {
				foreach($element->find('img') as $img) {
					$img->src = $url_parse['scheme'].'://'.$url_parse['host'].$img->src;
				}
				$html_text = $element->innertext;
			}
		}
        elseif ($url_parse['host'] == "news.yorkbbs.ca")
		{
			foreach($html_copy->find('div[class=article-main]') as $element) {
				$html_text = $element->innertext;
			}
		}
        elseif ($url_parse['host'] == "mp.weixin.qq.com")
		{
			foreach($html_copy->find('div[class=rich_media_content]') as $element) {
				$html_text = $element->innertext;
			}
		}
		else
		{
			$class_name_array = [];
			$number = 0;
			foreach($html_copy->find('p') as $p) {
				$attr = $p->parent()->attr;
				if (array_key_exists("class",$attr))
				{
					if (array_key_exists($attr["class"],$class_name_array))
					{
						$class_name_array[$attr["class"]] ++;
					}else
					{
						$class_name_array[$attr["class"]] = 1;
					}
				}
			}
			foreach($class_name_array as $key => $value) {
				if($value > $number)
				{
					$number = $value;
					$div_class = $key;
				}
			}
			foreach($html_copy->find('div[class='.$div_class.']') as $element) {
				$html_text = $element->innertext;
				break;
			}
		}
		return str_replace("\t","",$html_text);
	}
	/**
	 * 请求数据
	 *
	 * @param $url
	 * @param $timeout
	 * @return string
	 */
	function get_contents($url, $timeout = 20)
	{
		if( function_exists('curl_init') ){
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_HEADER, false );
			curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
			$content = curl_exec( $ch );
			curl_close( $ch );
			$data = $content ? $content : false;
		} else {
			//利用了stream_context_create()设置超时时间:
			$pots = array(
				'http' => array(
					'timeout' => $timeout
				)
			);
			$context = stream_context_create( $pots );
			$content = @file_get_contents( $url, false, $context );
			$data = $content ? $content : false;
			error_log($data);
		}
		return $data ? $this->my_encoding( $content, 'utf-8' ) : false;
	}

	/**
	 * 页面内容并自动转码
	 * my_encoding()自定义函数
	 * $data 为 curl_exec() 或 file_get_contents() 所获得的页面内容
	 * $to 需要转成的编码
	 */
	function my_encoding( $data, $to )
	{
		$encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
		$encoded = mb_detect_encoding($data, $encode_arr);
		$data = mb_convert_encoding($data,$to,$encoded);
		return $data;
	}
}

function Fewen_Article(){
    return Fewen_Article::get_the_only_instance();
}

Fewen_Article();

//add_filter( 'media_buttons_context', array( $this, 'insert_form_tinymce_buttons' ) );
//
//
//add_action( 'init', 'wptuts_buttons' );
//
//add_action('admin_init', 'import_capture_content', 99);
//
//add_filter('default_content', 'filter_post_content', 10, 2);
//add_filter('default_title', 'filter_post_title', 10, 2);
//
//function admin_menu(){
//// MLS 房源
//    add_submenu_page( 'edit.php', __( 'Capture Post' ), __( 'Capture Post' ), 'manage_options', 'post-capture', 'display_post_capture');
//
//}
//
//function display_post_capture(){
//
//
//   view('single-article');
//}
//
//function wptuts_buttons() {
//	add_filter( "mce_external_plugins", "wptuts_add_buttons" );
//	add_filter( 'mce_buttons', 'wptuts_register_buttons' );
//}
//function wptuts_add_buttons( $plugin_array ) {
//	$plugin_array['wptuts'] = plugins_url( 'fewen-article/assets/js/main.js'  );
//	return $plugin_array;
//}
//function wptuts_register_buttons( $buttons ) {
//	array_push( $buttons, 'clear_before', 'clear_after' ); // clearbefore', 'clearafter
//	return $buttons;
//}
//
//
//
//function view( $file, $args=[] ){
//    foreach ($args as $k => $v){
//        $$k = $v;
//    }
//
//    include( $file . '.php' );
//}
//
//
//function main_menu(){
//	add_menu_page( 'article', 'article', 'manage_options', 'article', 'show');
////	remove_menu_page('article');
//}
//
//function show(){
//	view('article');
//}
//
//function admin_enqueue_scripts(){
//
//		wp_enqueue_style(
//			'fewen-article',
//			plugins_url( 'fewen-article/assets/css/main.css'  ),
//			array(),
//			'1.02',
//			'screen'
//		);
//
//	wp_enqueue_script(
//		'fewen-article',
//		plugins_url( 'fewen-article/assets/js/test.js'  ),
//		array('jquery'),
//		'1.00',
//		true
//	);
//
//
//}
//
//function app_views($views){
//
//	global $locked_post_status;
//
//	$post_type = 'post';
//
//	if ( !empty($locked_post_status) )
//		return array();
//
//	$num_posts = wp_count_posts( $post_type, 'readable' );
//	$total_posts = array_sum( (array) $num_posts );
//
//
//	// Subtract post types that are not included in the admin all list.
//	foreach ( get_post_stati( array( 'show_in_admin_all_list' => false ) ) as $state ) {
//		$total_posts -= $num_posts->$state;
//	}
//
//	$all_view_count = get_total_post_view_count(get_current_user_id());
//	$today_view_count = get_today_post_view_count(get_current_user_id());
//	unset($views);
//
//	$views['all'] = "文章总数 <span class='count'>({$total_posts})</span>";
//	$views['all_view_count'] = "阅读总数 <span class='count'>({$all_view_count})</span>";
//	$views['today_view_count'] = "今日点击 <span class='count'>({$today_view_count})</span>";
//
//	return $views;
//}
//
//function change_row_action($actions, $post){
//	unset( $actions['inline hide-if-no-js'] );
//	unset($actions['view']);
//
////	var_dump($post);
////	die();
//
//	$view_count = get_post_view_count($post->ID);
//
//
//	$actions['others'] = "<span class='other'>{$view_count} | {$post->post_date} | {$post->post_status}</span>";
//
////	var_dump($actions);
////	die();
//
//	return $actions;
//
//}
//
//function manage_columns($columns){
//	unset($columns['cb']);
//	return $columns;
//}
//
//
//function set_posts_per_page($per_page, $post_type){
//	return 10;
//
//}
//
//function import_capture_content(){
//    global $pagenow;
//    if(in_array($pagenow, ['post-new.php'])){
//
//        $request_url = 'http://test.51home.ca/api/extract/article';
//        $args = [
//            'method' => 'POST',
//          'headers'  => [
//              'Accept' => 'application/vnd.51Api.v2+json'
//          ],
//          'body' => [
//	          'url' => $_POST['url'],
//          ]
//
//        ];
//        $result = wp_remote_post($request_url, $args);
//        if(wp_remote_retrieve_response_code($result) == 200){
//            $result = wp_remote_retrieve_body($result);
//            $result = json_decode($result, true);
//	        $capture_result = $result['data']['html'];
//	        $capture_title = $result['data']['title'];
//
//        }
//        $_REQUEST['r51_capture_content'] = $capture_result;
//        $_REQUEST['r51_capture_title'] = $capture_title;
//    }
//}
//
//function filter_post_content( $post_content, $post ){
//	if(isset($_REQUEST['r51_capture_content'])){
//		return $_REQUEST['r51_capture_content'];
//	}
//
//	return $post_content;
//}
//
//function filter_post_title( $post_title, $post ){
//	if(isset($_REQUEST['r51_capture_title'])){
//		return $_REQUEST['r51_capture_title'];
//	}
//
//	return $post_title;
//}
