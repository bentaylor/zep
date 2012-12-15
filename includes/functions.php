<?php
if (!defined('ABSPATH'))
	die('unable to continue...');

include_once(ABSPATH . '/includes/simplepie/simplepie.inc');

function combine_feeds( $feed_list, $max_items = 10, $delimiter = '~', $remove_html = true )
{
	if (empty($feed_list))
		return false;
	
	$combined_feed = array();
	
	foreach($feed_list as $url)
	{
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->set_cache_location(ABSPATH . '/cache');
		//$feed->replace_headers(true);
		if ($remove_html)
			$feed->strip_htmltags(array('img', 'a', 'object', 'embed', 'param', 'iframe', 'p', 'br', 'div', 'span', 'li', 'ul'));
		$feed->set_output_encoding(CHARSET);
		$feed->init();
		
		foreach ($feed->get_items(0, $max_items) as $item)
		{
			$combined_feed[$item->get_date('U') . $delimiter . $feed->get_title() . $delimiter . $url] = $item;
		}
		unset($feed);
	}
	
	krsort($combined_feed);
	
	return array_slice($combined_feed, 0, $max_items);
}

function link_list( $items, $delimiter = '~' )
{
	if (empty($items))
		return false;
	
	$i = 0;
	$html = "<ul>\n";
	$items_count = (sizeof($items) - 1);
	foreach($items as $key => $item)
	{
		$source = explode($delimiter, $key);
		$class = ( ($i == 0) ? 'first ' : ( ($i == $items_count) ? 'last ' : '' ) ) . 'item ' . get_domain($source[2]);
		$html .= '<li class="' . $class . '"><a href="' . $item->get_permalink() . '"><span>&nbsp;</span>' . $item->get_title() . "</a></li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($feeds, $item, $i);
	
	return $html;
}

function trim_excerpt( $content = '', $word_count = 100, $more_link = '[...]' ) {
		
	$content = trim($content);
	
	if ( '' != $content ) {
		$content = strip_tags($content, '<br>');
		$words = explode(' ', $content, $word_count + 1);
		if (count($words) > $word_count) {
			array_pop($words);
			$content = implode(' ', $words);
		}
	}
	
	return "$content $more_link";
}

function flickr_photos( $feed_url, $max_items = 10 )
{
	//$items = combine_feeds( array($feed_url), $max_items, '~', false);
	
	$feed = new SimplePie();
	$feed->set_feed_url($feed_url); 
	$feed->set_cache_location(ABSPATH . '/cache');
	$feed->set_output_encoding('ISO-8859-1');
	$feed->init();

	$html = '';
	
	if ($feed->data)
	{
		foreach ($feed->get_items(0, $max_items) as $item)
		{
			$image = $item->get_description();
			$image = substr($image, strpos($image, 'src=') + 4); // '<img') + 10);
			$image = trim(substr($image, 0, strpos($image, '.jpg') + 4)); // , "\" width")));
			$healthy = array("%3A", "%2F", '"', 'm.jpg');
			$yummy = array(":", "/", '', 's.jpg');
			$image = str_replace($healthy, $yummy, $image);
			//$image = str_replace('m.jpg', 's.jpg', $image);
			
			$html .= '<a href="' . $item->get_permalink() . '">';
			$html .= '<img src="' . $image . '" alt="[flickr photo: ' . $item->get_title() . ']" title="' . $item->get_title() . '" />';
			$html .= "</a>\n";
		}
	}
	
	return $html;
}

function profile_list( $deletable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$profile = new WebDataSources();
		
	if (empty($profile->profiles))
		return false;
	
	asort($profile->profiles);
	
	$i = 0;
	$html = "<ul class=\"profiles\">\n";
	$profile_count = (sizeof($profile->profiles) - 1);
	foreach($profile->profiles as $idx => $profile)
	{
		if ($deletable)
			$delete_me = "<input type=\"image\" name=\"delete_sn\" id=\"id_delete_sn_$idx\" value=\"$idx\" class=\"remove\" src=\"../includes/images/cross.png\" onclick=\"javascript:return confirm('Are you sure you want to delete your social network profile for {$profile[1][0]}?');\" title=\"Click here to delete {$profile[1][0]}\" />";
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $profile_count) ? 'last ' : '' ) ) . 'url item ' . $profile[0];
		$html .= '<li class="' . $class . '"><a rel="me' . ( ($delete_me) ? ' external' : '' ) .'" href="' . sprintf($profile[1][1], $profile[2]) . '"><span>&nbsp;</span>' . stripslashes( $profile[1][0] ) . '</a>' . $delete_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($profile, $profile_count, $i);
	
	return $html;
}

function source_list( $show_ids = false, $deletable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$profile = new WebDataSources();
		
	if (empty($profile->sources))
		return false;
	
	asort($profile->sources);
	
	$i = 0;
	$html = "<ul class=\"sources\">\n";
	$source_count = (sizeof($profile->sources) - 1);
	foreach($profile->sources as $idx => $source)
	{
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-source-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to delete your web data source for ' . $source[0] . '?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_wds_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_wds_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		$id = ( ($show_ids) ? ' id="' . $idx . '"' : '');
		$class = ( ($i == 0) ? 'first ' : ( ($i == $source_count) ? 'last ' : '' ) ) . 'item ' . get_domain($source[1]);
		$html .= '<li' . $id . ' class="' . $class . '"><a rel="me" href="' . $source[1] . '"><span>&nbsp;</span>' . stripslashes( $source[0] ) . '</a>' . $delete_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($source, $source_count, $i);
	
	return $html;
}

function blog_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->blogs)) && (!$removable) )
		return false;
	
	asort($sources->blogs);
	
	$i = 0;
	$html = "<ul class=\"blogs\">\n";
	$blogs_count = (sizeof($sources->blogs) - 1);
	
	foreach($sources->blogs as $idx => $blog)
	{
		$blog_source = $sources->sources[$blog];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-blog-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $blog_source[0] . ' from your blogs module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_blg_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_blg_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="blogs[]" id="blogs-' . array_search($blog_source, $sources->sources) . '" value="' . array_search($blog_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $blogs_count) ? 'last ' : '' ) ) . 'item ' . get_domain($blog_source[1]);
		$html .= '<li class="' . $class . '"><a rel="me" href="' . $blog_source[1] . '"><span>&nbsp;</span>' . stripslashes( $blog_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $blogs_count, $i);
	
	return $html;
}

function bookmark_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->bookmarks)) && (!$removable) )
		return false;
	
	ksort($sources->bookmarks);
	
	$i = 0;
	$html = "<ul class=\"bookmarks\">\n";
	$bookmark_count = (sizeof($sources->bookmarks) - 1);
	
	foreach($sources->bookmarks as $idx => $bookmark)
	{
		$bookmark_source = $sources->sources[$bookmark];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-bookmark-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $bookmark_source[0] . ' from your bookmarks module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_bkm_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_bkm_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="bookmarks[]" id="bookmarks-' . array_search($bookmark_source, $sources->sources) . '" value="' . array_search($bookmark_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $bookmark_count) ? 'last ' : '' ) ) . 'item ' . get_domain($bookmark_source[1]);
		$html .= '<li class="' . $class . '"><a rel="me" href="' . $bookmark_source[1] . '"><span>&nbsp;</span>' . stripslashes( $bookmark_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $bookmark_count, $i);
	
	return $html;
}

function photo_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->photos)) && (!$removable) )
		return false;
	
	asort($sources->photos);
	
	$i = 0;
	$html = "<ul class=\"photos\">\n";
	$photo_count = (sizeof($sources->photos) - 1);
	
	foreach($sources->photos as $idx => $photo)
	{
		$photo_source = $sources->sources[$photo];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-photo-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $bookmark_source[0] . ' from your photos module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_pht_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_pht_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="photos[]" id="photos-' . array_search($photo_source, $sources->sources) . '" value="' . array_search($photo_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $photo_count) ? 'last ' : '' ) ) . 'item ' . get_domain($photo_source[1]);
		$html .= '<li class="' . $class . '"><a rel="me" href="' . $photo_source[1] . '"><span>&nbsp;</span>' . stripslashes( $photo_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $photo_count, $i);
	
	return $html;
}

function music_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->music)) && (!$removable) )
		return false;
	
	asort($sources->music);
	
	$i = 0;
	$html = "<ul class=\"music\">\n";
	$music_count = (sizeof($sources->music) - 1);
	
	foreach($sources->music as $idx => $music)
	{
		$music_source = $sources->sources[$music];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-music-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $bookmark_source[0] . ' from your music module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_msc_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_msc_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="music[]" id="music-' . array_search($music_source, $sources->sources) . '" value="' . array_search($music_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $music_count) ? 'last ' : '' ) ) . 'item ' . get_domain($music_source[1]);
		$html .= '<li class="' . $class . '"><a rel="me" href="' . $music_source[1] . '"><span>&nbsp;</span>' . stripslashes( $music_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $music_count, $i);
	
	return $html;
}

function tag_cloud()
{
	global $tagspace_url;
	
	$data_sources = new WebDataSources();
	
	$tags = array();
	$sources = array();
	foreach ($data_sources->sources as $source)
		$sources[] = $source[1];
	
	foreach (combine_feeds($sources, MAX_ITEMS * 5, '~') as $key => $item)
	{
		$categories = $item->get_categories();
		if (!empty($categories))
		{
			foreach ($categories as $category)
			{
				$tags[] = $category->term;
			}
		}
	}
	unset($key, $item);
	$tags = array_count_values( explode(' ', strtolower( implode(' ', $tags) ) ) );
	arsort($tags);
	$tags = array_slice($tags, 0, 50);
	$low_count = end($tags);
	$high_count = reset($tags);
	$range = ($high_count - $low_count);
	$fontspread = 200 - 75;
	ksort($tags);
	
	foreach ($tags as $key => $val)
	{
		$font_size = 75 + ( $val / ( $range / $fontspread ) );
		$cloud .= '<a href="' . sprintf($tagspace_url, $key) . "\" style=\"font-size:$font_size%;\" title=\"$key ($val)\" rel=\"tag external\">$key</a> ";
	}
	
	return $cloud;
}

function location_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->location)) && (!$removable) )
		return false;
	
	asort($sources->location);
	
	$i = 0;
	$html = "<ul class=\"location\">\n";
	$location_count = (sizeof($sources->location) - 1);
	
	foreach($sources->location as $idx => $location)
	{
		$location_source = $sources->sources[$location];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-location-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $location_source[0] . ' from your location module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_loc_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_loc_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="location[]" id="location-' . array_search($location_source, $sources->sources) . '" value="' . array_search($location_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $location_count) ? 'last ' : '' ) ) . 'item ' . get_domain($location_source[1]);
		$html .= '<li class="' . $class . '"><a rel="me" href="' . $location_source[1] . '"><span>&nbsp;</span>' . stripslashes( $location_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $location_count, $i);
	
	return $html;
}

function tweet_list( $deletable = false, $removable = false )
{
	if (!class_exists('WebDataSources'))
		return false;
	
	$sources = new WebDataSources();
		
	if ( (empty($sources->tweet)) && (!$removable) )
		return false;
	
	asort($sources->tweet);
	
	$i = 0;
	$html = "<ul class=\"tweet\">\n";
	$tweet_count = (sizeof($sources->tweet) - 1);
	
	foreach($sources->tweet as $idx => $tweet)
	{
		$tweet_source = $sources->sources[$tweet];
		if ($deletable)
		{
			$delete_me = '<form method="post" id="delete-tweet-' . $idx . '" onsubmit="javascript:return confirm(\'Are you sure you want to remove ' . $bookmark_source[0] . ' from your tweet module?\');">';
			$delete_me .= '<input type="hidden" name="form_name" value="delete_twt_form" />';
			$delete_me .= '<input type="hidden" name="delete_id" value="' . $idx . '"/>';
			$delete_me .= '<input type="submit" name="save" id="id_save_twt_' . $idx . '" value="X" class="remove" />';
			$delete_me .= '</form>';
		}
		
		if ($removable)
		{
			$remove_me = '<a href="#" class="remove">x</a>';
			$remove_me .= '<input type="hidden" name="tweet[]" id="tweet-' . array_search($tweet_source, $sources->sources) . '" value="' . array_search($tweet_source, $sources->sources) . '" />';
		}
		
		$class = ( ($i == 0) ? 'first ' : ( ($i == $tweet_count) ? 'last ' : '' ) ) . 'item ' . get_domain($tweet_source[1]);
		$html .= '<li class="' . $class . '"><a rel="me" href="' . $tweet_source[1] . '"><span>&nbsp;</span>' . stripslashes( $tweet_source[0] ) . '</a>' . $delete_me . $remove_me . "</li>\n";
		$i++;
	}
	$html .= "</ul>\n";
	
	unset($sources, $source, $tweet_count, $i);
	
	return $html;
}

function get_domain( $url )
{
	$removeables = array('www.', 'ws.', 'api.', 'blog.', 'feeds.', '.com', '.net', '.org', '.gov', '.co', '.uk', '.');
	$host = parse_url($url, PHP_URL_HOST);
	$host = str_replace($removeables, '', $host);
	return $host;
	
	// LK - hmmm... what about links such as *.muxtape, or audioscrobbler = lastfm? (also all international TLDs?)
}

function do_messages( $echo = true )
{
	global $messages;
	
	$html = '';
	
	if ( isset($messages) )
	{
		foreach ($messages as $message)
		{
			$html .= "<div class=\"message-box\"><span class=\"{$message[0]}\">{$message[1]}</span></div>";
		}
	}
	
	if ( empty($html) )
		return;
	
	if ($echo)
	{
		echo $html;
		return;
	}
	
	return $html;
}

function is_auth()
{
	if ( (!defined('PASSWORD')) && (!defined('PASS_COOKIE')) )
		die('The password has not been defined, unable to continue...');
	
	if ( ( (!empty($_COOKIE[PASS_COOKIE])) && ( $_COOKIE[PASS_COOKIE] != md5(md5(PASSWORD)) ) ) || (empty($_COOKIE[PASS_COOKIE])) )
		return false;
	
	if ( isset($_GET['logout']) && $_GET['logout'] == 'true' )
		return false;
	
	return true;
}

function auth_redirect()
{
	// Checks if a user is logged in, if not redirects them to the login page
	if ( !is_auth() )
	{
		$location = './login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']);
		header("Location: $location");
		exit();
	}
}

function get_stylesheet()
{
	if ( is_mobile() )
		return 'styles/mobile.css';
	
	return 'styles/default.css';
}

// Code borrowed from: Alex King's WordPress Mobile Edition 2.0 - http://alexking.org/projects/wordpress
function is_mobile()
{
	if ( !isset($_SERVER['HTTP_USER_AGENT']) )
		return false;
	
	$mobile_browsers = array('2.0 MMP', '240x320', 'AvantGo', 'BlackBerry', 'Blazer', 'Cellphone', 'Danger', 'DoCoMo', 'Elaine/3.0', 'EudoraWeb', 'hiptop', 'iPhone', 'iPod', 'MMEF20', 'MOT-V', 'NetFront', 'Newt', 'Nokia', 'Opera Mini', 'Palm', 'portalmmm', 'Proxinet', 'ProxiNet', 'SHARP-TQ-GX10', 'Small', 'SonyEricsson', 'Symbian OS', 'SymbianOS', 'TS21i-10', 'UP.Browser', 'UP.Link', 'Windows CE', 'WinWAP');
	foreach ($mobile_browsers as $browser)
	{
		if (strstr($_SERVER['HTTP_USER_AGENT'], $browser))
			return true;
	}
	
	return false;
}

?>