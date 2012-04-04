<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include("../includes/db.php");

// Insert a row of information into the table "example"

// Include SimplePie
include_once('simplepie.inc');
include_once('idn/idna_convert.class.php');

// Create a new instance of the SimplePie object
$feed = new SimplePie();

//$feed->force_fsockopen(true);
//$feed1 = 'feed://feeds.feedburner.com/TheDenverEgotist/';

//get all sites
$sites_query = mysql_query("SELECT id, ego_name, feed_url FROM ego_sites") or die(mysql_error());
while($sites = mysql_fetch_array($sites_query)){
$site_url= $sites['feed_url'];

// Make sure that page is getting passed a URL
if (isset($site_url) && $site_url !== '')
{
	// Use the URL that was passed to the page in SimplePie
	$feed->set_feed_url($site_url);
	
	// XML dump
	$feed->enable_xml_dump(isset($_GET['xmldump']) ? true : false);
}

// Initialize the whole SimplePie object.  Read the feed, process it, parse it, cache it, and 
// all that other good stuff.  The feed's information will not be available to SimplePie before 
// this is called.
$success = $feed->init();

// We'll make sure that the right content type and character encoding gets set automatically.
// This function will grab the proper character encoding, as well as set the content type to text/html.
$feed->handle_content_type();

// When we end our PHP block, we want to make sure our DOCTYPE is on the top line to make 
// sure that the browser snaps into Standards Mode.
?>

			<!-- As long as the feed has data to work with... -->
			<?php if ($success): ?>

				<?php
				
                //get articles within the last 7 days
				//$articles_query = mysql_query("SELECT article_content FROM ego_articles WHERE article_time > DATE_SUB(NOW(), INTERVAL 7 DAY)");
				//$articles = mysql_fetch_array($articles_query);
				
                ?>
                
               <?php
               //loop thru rss items
			    foreach($feed->get_items() as $item): 
			   
				/*
				echo $item->get_title();
				echo "<br>";
				echo $item->get_date('Y-m-d H:i:s');
				echo "<br>";
				*/
				
				//$item->get_permalink();
				//$item->get_title();
				//$item->get_date('Y-m-d i:H:s');
				
				$the_content = preg_replace('/National/', '', $item->get_content(), 1);
				$the_content = preg_replace('/Local/', '', $the_content, 1);
				$the_content = strip_tags($the_content);
				$the_content = str_replace('Reddit  Facebook  StumbleUpon  Twitter','',$the_content);
				$the_content = str_replace('Via.','',$the_content);
				
				$the_content = trim($the_content);
				$the_content = htmlentities($the_content);
				$next="0";
				$percent="80";
				$dupe="0";
				
				//if the title doesn't exist
				if($check_title = mysql_query("SELECT id FROM ego_articles where article_title LIKE '%" . htmlentities($item->get_title()) . "%'")){
				if(mysql_num_rows($check_title)>0){
					// if title already exists, insert article in to ego_posts and don't bother comparing
					$dupe="1";
					$row = mysql_fetch_row($check_title);
					//if post doesn't already exist in ego_posts, add it
					if($check_posts = mysql_query("SELECT article_id, site_id, article_link FROM ego_posts where article_id ='" . $row[0] . "' AND site_id = '" . $sites['id'] . "'")){
						if(mysql_num_rows($check_posts)==0){
							mysql_query("INSERT INTO ego_posts(article_id, site_id, article_link, article_time) VALUES('" . $row[0] . "','" . $sites['id'] . "','" . $item->get_permalink() . "','" . $item->get_date('Y-m-d H:i:s') . "')");
							
						}
						
					}
				} else {
					
					//compare articles from the last 7 days with the feed item
					
					/*
					while($articles) {
						
					//compare each of these to each feed item
						similar_text($articles['article_content'], $item->get_content(), $percent_same);
						echo "same" . $percent_same . "<br>";
						if($percent_same >= $percent){
							$next="1";
							echo "<Br />dupe";
							echo $item->get_title() . " " . $percent_same . "<br />";
							break;
						}
					}
					*/
					
					if($next == "0" && $dupe=="0"){
					//add to database
						if($article_insert = mysql_query("INSERT INTO ego_articles(article_title, article_content, article_time, article_image) VALUES('" . htmlentities($item->get_title()) . "','" . mysql_real_escape_string($the_content) . "','" . $item->get_date('Y-m-d H:i:s') . "','" . first_image($item->get_content()) . "')")){
							//for some reason mysql_insert_id isn't working, so i'm doing it manually.
							$insert_id = mysql_insert_id();
							mysql_query("INSERT INTO ego_posts(article_id, site_id, article_link, article_time) VALUES('" . $insert_id . "','" . $sites['id'] . "','" . $item->get_permalink() . "','" . $item->get_date('Y-m-d H:i:s') . "')");
							
						}
					}
				
				}
				}
				?>
                
				<!-- Stop looping through each item once we've gone through all of them. -->
				<?php endforeach; ?>

			<!-- From here on, we're no longer using data from the feed. -->
			<?php endif; ?>
<?php } ?>

<?php
//get first image from feed
function first_image($content) {
	$first_img = '';
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
	$first_img = $matches [1] [0];
	//if it's a twitter logo, disregard since it's their social crap
	$findme = "twitter.png";
	$pos = strpos($first_img, $findme);
	if($pos !== false){
		$first_img='';
	}
	return $first_img;
}
?>