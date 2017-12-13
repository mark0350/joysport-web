<?php
/*
Plugin Name: MK Article Crawler
Plugin URI: http://markhe.me/
Description: MK Article Crawler is a plugin that can capture articles from url and edit the captured article like a breeze.
Version: 1.0.0
Author: Mark He
Author URI: http://markhe.me/
Text Domain: mk-article-crawler
Domain Path: /lang/

Copyright 2017 Mark He.
*/

final class MK_Article_Crawler {

	private static $instance;

	public static $path;

	public static $dir;

	public static function get_the_only_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {


		add_action( 'init', [ $this, 'mkac_editor_buttons' ] );

		add_filter( 'media_buttons_context', array( $this, 'insert_form_tinymce_buttons' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );

		add_action( 'wp_ajax_capture_article', array( $this, 'ajax_capture_article' ) );

		self::$path = rtrim( plugin_dir_url( __FILE__ ), '/' );

		self::$dir = rtrim( plugin_dir_path( __FILE__ ), '/' );

	}

	public function admin_enqueue_script() {

		wp_enqueue_style( 'mkac-main-style', MK_Article_Crawler::$path . '/assets/css/mkac-main.css', array(), '1.02', 'screen' );
		wp_enqueue_script( 'mkac-modal', MK_Article_Crawler::$path . '/assets/js/mkac-modal.js', array(), '1.05', true );
	}

    /**
	 * add insert captured article button to post-new and post pages
	 *
     * @param $context
     * @return string
     */
    public function insert_form_tinymce_buttons( $context ) {
		global $pagenow;

		if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow ) {
			return $context;
		}
		$html = '<style>
            span.mkac-insert-article {
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
		$html .= '<a href="#" class="button-secondary mkac-insert-article"><span class="mkac-insert-article dashicons dashicons-feedback"></span> ' . __( 'Add Article' ) . '</a>';
		?>
		<?php

		wp_enqueue_script( 'mkac-main', MK_Article_Crawler::$path . '/assets/js/mkac-main.js', array( 'jquery' ) );


		return $context . ' ' . $html;
	}

    /**
	 * todo wpnonce
     * ajax api to capture article
     */
    public function ajax_capture_article() {

		$this->validata_parameters();
		$article = $this->capture_article( $_REQUEST['mkac_url'] );
		if(is_wp_error($article)){
			wp_send_json_error($article);
		}
		wp_send_json_success( [ 'article' => $article ] );
	}

    /**
	 * validate parameters to article capture
	 *
     * @return bool
     */
    private function validata_parameters(){

		$result = array();
		do{
			if(!isset($_REQUEST['mkac_url'])){
				$result[] = array('message' => 'url is required');
				break;
			}
			if(!wp_http_validate_url(trim($_REQUEST['mkac_url']))){
				$result[] = array('message' => 'Invalid url');
				break;
			}
			return true;
		}while(false);

		wp_send_json_error($result);
	}

    /**
	 * todo 是不是要替换http 请求方法，获取失败 try ?
	 *
     * @param $url
     * @return mixed
     */
    public function capture_article( $url )
    {
        include_once( self::$dir . '/libs/simple_html_dom.php' );
        return $this->getHTML( $url );
	}

    /**
     * customize tinymce editor
     */
    public function mkac_editor_buttons() {
		// 给 tinymce 编辑器增加插件
		add_filter( "mce_external_plugins",
			[ $this, 'mkac_editor_add_buttons' ] );

		// 给 tinymce 编辑器增加两个按钮
		add_filter( 'mce_buttons', [ $this, 'mkac_editor_register_buttons' ] );
	}

	/**
	 * add tinymce editor plugin
	 *
	 * @param $plugin_array
	 *
	 * @return mixed
	 */
	function mkac_editor_add_buttons( $plugin_array ) {
		$plugin_array['mkac_editor']
			= MK_Article_Crawler::$path . '/assets/js/mkac-editor.js';

		return $plugin_array;
	}

	/**
	 * add two buttons to mce editor
	 *
	 * @param $buttons
	 *
	 * @return mixed
	 */
	function mkac_editor_register_buttons( $buttons ) {
		array_push( $buttons, 'clear_before',
			'clear_after' ); // clearbefore', 'clearafter

		return $buttons;
	}

	public function getHTML( $url ) {
		$html      = $this->get_contents( $url );
		$html_copy = str_get_html( $html );
		$url_parse = parse_url( $url );

		$html_text = "";
		$div_class = '';
		if ( $url_parse['host'] == "info.51.ca" ) {
			if ( strpos( $url, "/m/" ) !== false ) {
				$div_class = 'div[class=arcbody]';
			} else {
				$div_class = 'div[class=article-content clearfix]';
			}
			foreach ( $html_copy->find( $div_class ) as $element ) {
				foreach ( $element->find( 'img' ) as $img ) {
					$img->src = $url_parse['scheme'] . '://' . $url_parse['host'] . $img->src;
				}
				$html_text = $element->innertext;
			}
		} elseif ( $url_parse['host'] == "news.yorkbbs.ca" ) {
			foreach ( $html_copy->find( 'div[class=article-main]' ) as $element ) {
				$html_text = $element->innertext;
			}
		} elseif ( $url_parse['host'] == "mp.weixin.qq.com" ) {
			foreach ( $html_copy->find( 'div[class=rich_media_content]' ) as $element ) {
				$html_text = $element->innertext;
			}
		} else {
			$class_name_array = [];
			$number           = 0;
			foreach ( $html_copy->find( 'p' ) as $p ) {
				$attr = $p->parent()->attr;
				if ( array_key_exists( "class", $attr ) ) {
					if ( array_key_exists( $attr["class"], $class_name_array ) ) {
						$class_name_array[ $attr["class"] ] ++;
					} else {
						$class_name_array[ $attr["class"] ] = 1;
					}
				}
			}
			foreach ( $class_name_array as $key => $value ) {
				if ( $value > $number ) {
					$number    = $value;
					$div_class = $key;
				}
			}
			foreach ( $html_copy->find( 'div[class=' . $div_class . ']' ) as $element ) {
				$html_text = $element->innertext;
				break;
			}
		}

		return str_replace( "\t", "", $html_text );
	}

	/**
	 * 请求数据
	 *
	 * @param $url
	 * @param $timeout
	 *
	 * @return string
	 */
	function get_contents( $url, $timeout = 20 ) {
		if ( function_exists( 'curl_init' ) ) {
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
			$pots    = array(
				'http' => array(
					'timeout' => $timeout
				)
			);
			$context = stream_context_create( $pots );
			$content = @file_get_contents( $url, false, $context );
			$data    = $content ? $content : false;
			error_log( $data );
		}

		return $data ? $this->my_encoding( $content, 'utf-8' ) : false;
	}

	/**
	 * 页面内容并自动转码
	 * my_encoding()自定义函数
	 * $data 为 curl_exec() 或 file_get_contents() 所获得的页面内容
	 * $to 需要转成的编码
	 */
	function my_encoding( $data, $to ) {
		$encode_arr = array( 'UTF-8', 'ASCII', 'GBK', 'GB2312', 'BIG5', 'JIS', 'eucjp-win', 'sjis-win', 'EUC-JP' );
		$encoded    = mb_detect_encoding( $data, $encode_arr );
		$data       = mb_convert_encoding( $data, $to, $encoded );

		return $data;
	}
}

function MK_Article_Crawler() {
	return MK_Article_Crawler::get_the_only_instance();
}

MK_Article_Crawler();

