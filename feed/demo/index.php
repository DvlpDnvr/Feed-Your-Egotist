<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


include("../includes/db.php");


// Insert a row of information into the table "example"
//mysql_query("INSERT INTO ego_sites (feed_url) VALUES('Testing') ") or die(mysql_error());  

// Include SimplePie
// Located in the parent directory
include_once('simplepie.inc');
include_once('idn/idna_convert.class.php');

// Create a new instance of the SimplePie object
$feed = new SimplePie();

//$feed->force_fsockopen(true);
$feed1 = 'http://feeds.feedburner.com/TheDenverEgotist/';

// Make sure that page is getting passed a URL
if (isset($feed1) && $feed1 !== '')
{
	// Use the URL that was passed to the page in SimplePie
	$feed->set_feed_url($feed1);
	
	// XML dump
	$feed->enable_xml_dump(isset($_GET['xmldump']) ? true : false);
}

// Allow us to change the input encoding from the URL string if we want to. (optional)
if (!empty($_GET['input']))
{
	$feed->set_input_encoding($_GET['input']);
}

// Allow us to choose to not re-order the items by date. (optional)
if (!empty($_GET['orderbydate']) && $_GET['orderbydate'] == 'false')
{
	$feed->enable_order_by_date(false);
}

// Allow us to cache images in feeds.  This will also bypass any hotlink blocking put in place by the website.
if (!empty($_GET['image']) && $_GET['image'] == 'true')
{
	$feed->set_image_handler('./handler_image.php');
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

				<!-- Let's begin looping through each individual news item in the feed. -->
				<?php foreach($feed->get_items() as $item): ?>
					
						<!-- If the item has a permalink back to the original post (which 99% of them do), link the item's title to it. -->
						<h2><?php if ($item->get_permalink()) echo '<a href="' . $item->get_permalink() . '">'; echo $item->get_title(); if ($item->get_permalink()) echo '</a>'; ?>&nbsp;<span class="footnote"><?php echo $item->get_date('j M Y, g:i a'); ?></span></h2>

						<!-- Display the item's primary content. -->
                        <?php
                        	//remove first occurence of National and Local from feed
							$the_content = preg_replace('/National/', '', $item->get_content(), 1);
							$the_content = preg_replace('/Local/', '', $the_content, 1);
                        ?>
						<?php echo $the_content; ?>

					</div>

				<!-- Stop looping through each item once we've gone through all of them. -->
				<?php endforeach; ?>

			<!-- From here on, we're no longer using data from the feed. -->
			<?php endif; ?>
