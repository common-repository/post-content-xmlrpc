<?php       
/*    
Plugin Name: Post Content Using XMLRPC
Description: This plugin is useful for adding wordpress post to remote wordpress sites using XMLRPC.
Author: Kartik Shete (Stark Infotech).
Author URI: http://starkinfotech.com
Version: 1.0
*/

set_time_limit(0);

function curl_enable_notice(){
    echo '<div class="error"><p>It seem that cURL is disabled on your server. Please contact your server administrator to install / enable cURL.</p></div>'; 
}
if(!function_exists('curl_init')) {
    add_action('admin_notices', 'curl_enable_notice');
}

function jal_install_pcx() {

   global $wpdb;
   $table_name = $wpdb->prefix . "pcx";
      
	$sql = "CREATE TABLE " . $table_name . " (
		id int(10) not null auto_increment,
		sitetitle varchar(255) DEFAULT NULL,
		siteurl varchar(255) not null,
		username varchar(255) not null,
		password varchar(255) not null,
		PRIMARY KEY (id)
	);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

}

register_activation_hook(__FILE__,'jal_install_pcx');

add_action('admin_init', 'editor_admin_init');
add_action('admin_head', 'editor_admin_head');

function editor_admin_init() {
  wp_enqueue_script('word-count');
  wp_enqueue_script('post');
  wp_enqueue_script('editor');
  wp_enqueue_script('media-upload');
  wp_enqueue_style('thickbox');
}

function editor_admin_head() {
  wp_tiny_mce();
}

add_action('admin_menu','pcx');	

	function pcx()
   	{  
   		add_menu_page("Post Content", "Post Content", 10, "pcx_add_sites", "pcx_add_sites");
		add_submenu_page("pcx_add_sites", 'Add Post', 'Add Post', 10, "pcx_content", 'pcx_add_content');
	}

	function pcx_add_sites()
	{
		global $wpdb;
		include('list_sites.php');
		
	}

	function pcx_add_content()
	{
		global $wpdb;
		include('add_content.php');
	}
	
	function pcs_add_post($title,$body,$rpcurl,$username,$password,$category,$keywords='',$encoding='UTF-8')
	{
		$title2 = htmlentities(stripslashes($title),ENT_NOQUOTES,$encoding);
		$keywords2 = htmlentities($keywords,ENT_NOQUOTES,$encoding);
		
		$content = array(
			'title' => $title2,
			'description' => stripslashes($body),
			'mt_allow_comments' => 1,  // 1 to allow comments
			'post_type' => 'post',
			'mt_keywords' => $keywords2,
			'categories' => array($category)
		);
		
		$rpcurl2 = $rpcurl."/xmlrpc.php";
		
		$params = array(0,$username,$password,$content,true);
		$request = xmlrpc_encode_request('metaWeblog.newPost',$params);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $rpcurl2);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		
		$results = curl_exec($ch);
		$res = xmlrpc_decode($results);
		curl_close($ch);
		return $res;
	}
?>