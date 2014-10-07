<?php
function srytk2014_dequeue_fonts(){
	//twenty fourteen縺瑚ｪｭ縺ｿ霎ｼ繧lato font繧呈ｶ医☆
	wp_dequeue_style( 'twentyfourteen-lato' );
}

add_action( 'wp_enqueue_scripts', 'srytk2014_dequeue_fonts' ,11);

//フィードurlを非表示
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );


//必要なし
remove_action('wp_head', 'wp_generator');

//FeedBurnerの<link>をheadに表示
function echo_feedburner_url(){
	echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr(get_bloginfo('name')) . 'のフィード" href="http://feeds.feedburner.com/SorryForTeamKilling">'."\n";
}

add_action( 'wp_head' , 'echo_feedburner_url' );


//ベースcssを追加
/*
function add_base_css2014(){
	wp_enqueue_style('base_twentyfourteen', get_theme_root_uri()."/twentyfourteen/style.css");
}

add_action(	'wp_enqueue_scripts', 'add_base_css2014' , 8 );
 */

function check_update_css(){
	$ori_path = get_stylesheet_directory()."/style.css";

	$paths = array(get_stylesheet_directory()."/style_meta.css", get_template_directory()."/style.css", get_stylesheet_directory()."/myStyle.css", get_stylesheet_directory()."/genericons_.css");

	$max_time = 0;

	foreach($paths as $p){
		$temp = filemtime($p);
		if($temp && $max_time < $temp){
			$max_time = $temp;
		}
	}

	if(filemtime($ori_path) < $max_time){
		$str = '@charset "UTF-8";';

		foreach($paths as $p){
			$str .= file_get_contents($p);
		}

		file_put_contents($ori_path, $str);
	}
}

add_action( 'wp_enqueue_scripts', 'check_update_css');

/*
function remove_genericons(){
	wp_dequeue_style( 'genericons' );
//	wp_deregister_style('genericons');
}

add_action( 'wp_enqueue_scripts', 'remove_genericons', 10 );
 */

function dequeue_genericons() {
	wp_dequeue_style( 'genericons' );
	wp_deregister_style('genericons');
}
add_action( 'wp_enqueue_scripts', 'dequeue_genericons', 11 );



//genericons
/*
function cdn_genericons(){
	//wp_dequeue_style( 'genericons' );
	wp_enqueue_style( 'genericons', 'https://cdn.jsdelivr.net/genericons/3.0.3/genericons.css', array(), Null );
}

add_action( 'wp_enqueue_scripts', 'cdn_genericons' ,9);

 */

//jqueryをjsdelivrから読んで、migateは無視する

function register_jquery() {
	
	//file update check
	$original_file_time = filemtime(get_stylesheet_directory()."/js/sitescript.js");
	$max_time = filemtime(get_stylesheet_directory()."/js/jquery/1.11.1/jquery.min.js");

	$files_path = array(get_stylesheet_directory()."/js/srytk-common.js", get_template_directory()."/js/functions.js");

	$c = count($files_path);

	for($i=0;$i<$c;$i++){
		$temp = filemtime($files_path[$i]);

		if($temp > $max_time && $temp){
			$max_time = $temp;
		}
	}

	if($original_file_time < $max_time){
		$str = file_get_contents(get_stylesheet_directory()."/js/jquery/1.11.1/jquery.min.js");

		foreach($files_path as $path){
			$str .= file_get_contents($path);
		}

		file_put_contents(get_stylesheet_directory()."/js/sitescript.js", $str);
	}



    wp_deregister_script( 'jquery' );
    //wp_register_script( 'jquery', get_stylesheet_directory_uri()."/js/sitescript.js", false, null, true );
    //wp_enqueue_script( 'jquery' );
}

add_action( 'wp_enqueue_scripts', 'register_jquery' );


//Feedをキャッシュさせない( for cloudflare )
function feed_no_cache($headers){
	if(is_feed()){
		//$headers["Cache-Control"] = "no-cache, must-revalidate, max-age=0";
		header("Cache-Control: no-cache, must-revalidate, max-age=0");
	}
}

//add_action('send_headers', 'feed_no_cache');
add_action('wp','feed_no_cache');
		

//Javascriptでゴニョゴニョする
/*
function srytk_common_js(){
	//wp_register_script("srytk_commonjs", get_stylesheet_directory_uri()."/js/srytk-common.js", array("jquery"), null, true);
	wp_enqueue_script( 'srytk_commonjs', get_stylesheet_directory_uri()."/js/srytk-common.js", array("jquery"), Null, true );
}

add_action("wp_enqueue_scripts", "srytk_common_js");
 */


//twentyfourteen_scriptsをインラインにする

function deQ_2014script(){
	wp_dequeue_script('twentyfourteen-script');
}
add_action( 'wp_enqueue_scripts', 'deQ_2014script', 100);


/*
function twentyfourteen_scripts_inliner(){
	echo '<script>'.file_get_contents(get_template_directory()."/js/functions.js").'</script>';
}

add_action( 'wp_footer', 'twentyfourteen_scripts_inliner', 100 );
 */



//<body>に「itemscope itemtype="http://schema.org/WebPage"」を追加する
/*
function add_item_scope_body($content){
	if(is_single()){
		//個別投稿のページ（または添付ファイルページ・カスタム投稿タイプの個別ページ）が表示されている場合。固定ページには適用されない。
		preg_match('/<body([^>]*?)>/i',$content,$match);
		$temp = $match[1];
		if(strpos($temp,'itemscope') === false){
			$content = preg_replace('/<body[^>]*?>/i','<body'.$temp." itemscope itemtype='http://schema.org/WebPage'>",$content,1);
		}else{
			$content = preg_replace('/<body[^>]*?>/i','<body'.$temp." tes>",$content,1);
		}
	}
	return $content; //必ず返す
}

add_filter('the_content', 'add_item_scope_body', 99);
 */

/*
//構造化データ JSON-LD
function my_json_ld(){
	return;
	$json = array();
	$json["@context"] = "http://schema.org";
	$json["@type"] = "WebPage";


	$htm = bcn_display(true);
	$ar = explode("> &gt; ",$htm);
	if(!$ac = count($ar)){
		return;
	}else{
		$bc = array();
		for($i=0;$i+1<$ac;++$i){
			preg_match('/>([^<]*?)</i',$ar[$i],$match);
			$name = $match[1];

			preg_match('/href="([^"]*?)"/i',$ar[$i], $match);
			$url = $match[1];

			$bc[] = array("name" => $name, "url" => $url);
		}

		$json["breadcrumb"]["@list"] = $bc;

	}


	echo "\n".'<script type="application/ld+json">'.json_encode($json,JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ).'</script>';

}

add_action('wp_footer', 'my_json_ld', 30);
 */


function add_my_cache_control(){
	if(is_user_logged_in() || is_preview() || is_admin()){
		return;
	}else{
		$maxAge = 0;
		$status = "private";
		if(is_front_page()){
			//トップページ
			$maxAge = 1*60*60;
			$status = "public";
		}else if(is_singular()){
			//is_single()、is_page() 、is_attachment() のいずれかが真である場合
			$maxAge = 12*60*60;
			$status = "public";
		}else if(is_archive() || is_search()){
			//各アーカイブページが表示されている場合。アーカイブページには、カテゴリー、タグ、作成者、日付別のものがあります。 
			//検索結果のページが表示されている場合。
			
			$maxAge = 24*60*60;
			$status = "public";
		}

		header("Cache-Control: ".$status.", max-age=".$maxAge);
		//header("X-test: test");
	}
}
			
//add_action( 'send_headers', 'add_my_cache_control' , 11 );
add_action( 'wp', 'add_my_cache_control' , 11 );

//ad dns-prefetch
function add_ad_dns_prefetch_code(){
	$domains = array(
		'www.gstatic.com',
		'googleads.g.doubleclick.net',
		'tpc.googlesyndication.com',
		'www.google-analytics.com',
		'ajax.cloudflare.com',
		'i.ytimg.com',
		'images-na.ssl-images-amazon.com',
		'www.google.com',
		't0.gstatic.com',
		't1.gstatic.com',
		't2.gstatic.com'
	);

	/*
	for($i=1;$i<10;++$i){
		$domains[] = "farm".$i.".staticflickr.com";
	}
	 */

	foreach($domains as $domain){
		echo '<link rel="dns-prefetch" href="//'.$domain.'">';
	}
}

add_action('wp_head', 'add_ad_dns_prefetch_code');


function add_favicon(){
	echo '<link rel="shortcut icon" href="//srytk.com/wp-content/uploads/2014/08/favcon16x16.ico">';
}

add_action('wp_head', 'add_favicon');

function add_ad_script(){
	echo '<script async defer src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
	//echo '<script async src="//www.gstatic.com/pub-config/ca-pub-6931805962182644.js"></script>'; //firefoxでなぜか２回リクエストが走るのでコメントアウト
}

add_action('wp_footer', 'add_ad_script');

//jpegの圧縮品質
//default = 90
add_filter('jpeg_quality', function($arg){return 50;});


//CloudFlareのUniversal SSLを利用し、Flexible SSLに対応するため、$_SERVER['HTTPS']に強制的に'on'を設定する
function override_server_https_env(){
	$_SERVER['HTTPS'] = 'on';
}

add_action('after_setup_theme', 'override_server_https_env');

function add_hsts_header(){
	//とりえず3日
	$age = 60*60*24*3;

	header("Strict-Transport-Security: max-age=".$age);
}

add_action('wp', 'add_hsts_header');


function ga_tracker(){
	//google analytics tracker
	//inline scriptはcssより早く実行されるべき
	echo '<script>!function(a,b,c){a.GoogleAnalyticsObject=c,a[c]=a[c]||function(){(a[c].q=a[c].q||[]).push(arguments)},a[c].l=1*new Date}(window,document,"ga"),ga("create","UA-51729378-1","auto"),ga("send","pageview");</script>';
}

add_action('wp_head', 'ga_tracker', 1); //引数をとらず、またデフォルト(10)より早く実行

//async deferにするためマニュアルで挿入
function set_google_analytics_script(){
	echo '<script async defer src="//www.google-analytics.com/analytics.js"></script>';
}
add_action('wp_footer', 'set_google_analytics_script');


//async deferにするためマニュアルで挿入
function set_site_script(){
	$stamp = filemtime(get_stylesheet_directory()."/js/sitescript.js");
	echo '<script async defer src="'.get_stylesheet_directory_uri().'/js/sitescript.js?ver='.$stamp.'"></script>';
}

add_action('wp_footer', 'set_site_script');
