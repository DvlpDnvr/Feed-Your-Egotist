<?
include('config.php');

// DEFINE REQUEST
$request = $_GET['request'];
$filter = $_GET['filter'];
$page = $_GET['page'];
if($page == ""){
	$page=0;
}
$f="";

switch($request){
	case "all":
		get_all($page);
	break;
	case "posts":
		get_posts($page);
	break;
	case "editorials":
		get_editorials($page);
	break;
}

function get_all($page){
	
	// VARS
	$the_articles = array();
	
	if($_GET['filter'] != ""){
		$f = " WHERE p.site_id = '" . $_GET['filter'] . "'";
	}
	
	// GET POSTS
	$articles = mysql_query("SELECT a.article_title, a.article_content, a.article_time, a.article_image, p.article_link, s.image FROM ego_posts AS p LEFT JOIN ego_articles AS a ON p.article_id = a.id LEFT JOIN ego_sites AS s ON s.id = p.site_id " . $f . " ORDER BY article_time DESC LIMIT " . $page*20 . ",20") or die(mysql_error());
	
	
	while($article = mysql_fetch_array($articles)){
		//truncate article and add ... if needed
		$the_article_content="";
		if($article['article_content'] != ""){
			$the_article_content = substr(html_entity_decode($article['article_content']),0, 750);
			if(strlen($article['article_content']) > 750){
				$the_article_content .= "&hellip;";
			}
		}
		
		//echo $site;
		$article = array('article'=>array('image'=>$article['image'],'article_image'=>$article['article_image'],'title'=>$article['article_title'],'content'=>$the_article_content,'url'=>$article['article_link'], 'date'=>$article['article_time']));
			
		array_push($the_articles, $article);
		
	}
	
	echo json_encode($the_articles);
}

function get_posts($page){
	
	// VARS
	$the_posts = array();
	
	if($_GET['filter'] != ""){
		$f = " AND p.site_id = '" . $_GET['filter'] . "'";
	}
	
	// GET POSTS
	$posts = mysql_query("SELECT a.article_title, a.article_content, a.article_time, a.article_image, p.article_link, s.image FROM ego_posts AS p LEFT JOIN ego_articles AS a ON p.article_id = a.id LEFT JOIN ego_sites AS s ON s.id = p.site_id WHERE p.article_link NOT LIKE '%/editorial/%' " . $f . " ORDER BY article_time DESC LIMIT " . $page*20 . ",20");
	
	while($post = mysql_fetch_array($posts)){
		//truncate article and add ... if needed
		$the_post_content="";
		if($post['article_content'] != ""){
			$the_post_content = substr(html_entity_decode($post['article_content']),0, 750);
			if(strlen($post['article_content']) > 750){
				$the_post_content .= "&hellip;";
			}
		}
		
		//echo $site;
		$post_info = array('article'=>array('image'=>$post['image'],'article_image'=>$post['article_image'],'title'=>$post['article_title'],'content'=>$the_post_content,'url'=>$post['article_link'], 'date'=>$post['article_time']));
			
		array_push($the_posts, $post_info);
		
	}
	
	echo json_encode($the_posts);
}

function get_editorials($page){
	
	// VARS
	$the_editorials = array();
	
	if($_GET['filter']){
		$f = " AND p.site_id = '" . $_GET['filter'] . "'";
	}
	
	// GET POSTS
	$editorials = mysql_query("SELECT a.article_title, a.article_content, a.article_time, a.article_image, p.article_link, s.image FROM ego_posts AS p LEFT JOIN ego_articles AS a ON p.article_id = a.id LEFT JOIN ego_sites AS s ON s.id = p.site_id WHERE p.article_link LIKE '%/editorial/%' " . $f . " ORDER BY article_time DESC LIMIT " . $page*20 . ",20");
	
	while($editorial = mysql_fetch_array($editorials)){
		//truncate article and add ... if needed
		$the_editorial_content="";
		if($editorial['article_content'] != ""){
			$the_editorial_content = substr(html_entity_decode($editorial['article_content']),0, 750);
			if(strlen($editorial['article_content']) > 750){
				$the_editorial_content .= "&hellip;";
			}
		}
		
		//echo $site;
		$editorial_info = array('article'=>array('image'=>$editorial['image'],'article_image'=>$editorial['article_image'],'title'=>$editorial['article_title'],'content'=>$the_editorial_content,'url'=>$editorial['article_link'], 'date'=>$editorial['article_time']));
			
		array_push($the_editorials, $editorial_info);
		
	}
	
	echo json_encode($the_editorials);
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