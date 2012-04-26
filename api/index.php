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
		array_push($post_times,days_ago($post['article_time']));
	}
	
	while($esites = mysql_fetch_array($ego_sites)){
		array_push($the_sites,$esites['image']);
	}
	
	for($i = 0; $i < count($article_ids); $i++){
		
		$the_article_content="";
		if($article_content[$i] != ""){
			$the_article_content = substr(html_entity_decode($article_content[$i]),0, 750);
			if(strlen($post['article_content']) > 750){
				$the_article_content .= "&hellip;";
			}
		}
		
		//echo $site;
		$article = array('article'=>array('id'=>$post_sites[$i],'image'=>$the_sites[$post_sites[$i]-1],'article_image'=>$article_image[$i],'title'=>$article_title[$i],'content'=>$the_article_content,'url'=>$post_links[$i], 'date'=>$post_times[$i], array('sites'=>$sites)));
			
		
		array_push($the_articles, $article);
	}
	
	echo json_encode($the_articles);
}

function filter($f){
	
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
	
	// GET POSTS FOR SINGLE SITE
	$posts = mysql_query("SELECT p.id, p.article_link, p.article_time, a.article_title, a.article_content, a.article_image, s.image FROM ego_posts as p LEFT JOIN ego_articles as a ON a.id = p.article_id LEFT JOIN ego_sites as s ON p.site_id = s.id  WHERE p.site_id = '" . $f . "' ORDER BY article_time DESC LIMIT 0,20");
	
	while($post = mysql_fetch_array($posts)){
		$the_article_content="";
		if($post['article_content'] != "") {
			$the_article_content = substr(html_entity_decode($post['article_content']),0, 750);
			if(strlen($post['article_content']) > 750){
				$the_article_content .= "&hellip;";
			}
		}
			
		$article = array('article'=>array('id'=>$post['id'],'image'=>$post['image'],'article_image'=>$post['article_image'],'title'=>$post['article_title'],'content'=>$the_article_content,'url'=>$post['article_link'], 'date'=>$post['article_time']));
		array_push($the_articles, $article);
	}
	
	echo json_encode($the_articles);
}

function days_ago($time){
	$then = strtotime($time);
	$diff = time() - $then;
	$days_ago = floor($diff/(60*60*24));
	if($days_ago == 0){
		$days = "today";
	} else if($days_ago == 1){
		$days = "yesterday";
	} else {
		$days = $days_ago . " days ago";
	}
	return $days;
}

?>