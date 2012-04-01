<?
include('config.php');

// DEFINE REQUEST
$request = $_GET['request'];
$filter = $_GET['filter'];

switch($request){
	case "all":
		get_all();
	break;
	default:
		filter($filter);
	break;
}

function get_all(){
	
	// VARS
	$article_title = array();
	$article_content = array();
	$article_ids = array();
	$article_image = array();
	
	$post_ids = array();
	$post_sites = array();
	$post_links = array();
	$post_times = array();
	
	$the_articles = array();
	$sites = array();
	$the_sites = array();
	$currentID = 0;
	
	// GET POSTS
	$articles = mysql_query("SELECT * FROM ego_articles ORDER BY article_time DESC LIMIT 0,20");
	$posts = mysql_query("SELECT * FROM ego_posts ORDER BY article_time DESC LIMIT 0,20");
	$ego_sites = mysql_query("SELECT * FROM ego_sites id");
	
	while($article = mysql_fetch_array($articles)){
		array_push($article_title,$article['article_title']);
		array_push($article_content,$article['article_content']);
		array_push($article_ids,$article['id']);
		array_push($article_image,$article['article_image']);
	}
	while($post = mysql_fetch_array($posts)){
		array_push($post_ids,$post['id']);
		array_push($post_sites,$post['site_id']);
		array_push($post_links,$post['article_link']);
		array_push($post_times,$post['article_time']);	
	}
	
	while($esites = mysql_fetch_array($ego_sites)){
		array_push($the_sites,$esites['image']);	
	}
	
	for($i = 0; $i < count($article_ids); $i++){
		
		//echo $site;
		$article = array('article'=>array('id'=>$post_sites[$i],'title'=>$article_title[$i],'origin'=>$post_sites[$i],'content'=>html_entity_decode($article_content[$i]),'url'=>$post_links[$i],array('sites'=>$sites)));
			
		
		array_push($the_articles, $article);
	}
	
	echo json_encode($the_articles);
}

function filter($f){
	
}
?>