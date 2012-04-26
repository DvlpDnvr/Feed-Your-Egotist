<?
class RSS
{
	public function RSS()
	{
		require_once ('../includes/db.php');
	}
	public function GetFeed()
	{
		return $this->getDetails() . $this->getItems();
	}
	private function getDetails()
	{
		$details = '<?xml version="1.0" encoding="ISO-8859-1" ?>
				<rss version="2.0">
					<channel>
						<title>Feed Your Egotist</title>
						<link>http://www.feedyouregotist.com</link>
						<description>Aggregated Egotist Feeds</description>
						';
		
		return $details;
	}
	private function getItems()
	{
		$result = mysql_query("SELECT article_id, article_link FROM ego_posts ORDER BY article_time DESC LIMIT 10");
		
		$items = '';
		while($row_post = mysql_fetch_array($result))
		{
			$query = mysql_query("SELECT article_title, article_content FROM ego_articles WHERE id = '" . $row_post['article_id'] . "' LIMIT 1");
			$row = mysql_fetch_row($query);
			$items .= '<item>
							<title>'. $row[0] .'</title>
							<link>'. $row_post["article_link"] .'</link>
							<description><![CDATA['. htmlspecialchars_decode($row[1]) .']]></description>
					   </item>';
		}
		$items .= '</channel>
				</rss>';
		return $items;
	}
}
?>