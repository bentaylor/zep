<?php
include_once('pz-config.php');

if (!class_exists('Profile'))
	die('The Profile class doesn\'t exist, are you missing some installation files?');
	
$profile = new Profile();

if (!class_exists('WebDataSources'))
	die('The WebDataSources class doesn\'t exist, are you missing some installation files?');

$data_sources = new WebDataSources();
$fullname = $profile->first_name . ' ' . $profile->last_name;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
	<head profile="http://gmpg.org/xfn/11">
		<title>Pez: <?php echo $fullname; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=<?php echo CHARSET; ?>" />
		<meta name="author" content="<?php echo $profile->first_name . ' ' . $profile->last_name; ?>" />
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="generator" content="Pez <?php echo $pz_version . '.' . $pz_svn_revision; ?>" />
		<meta name="robots" content="all" />
		<meta name="dc.title" content="Pez: <?php echo $fullname; ?>" />
		<meta name="dc.publisher" content="<?php echo $fullname; ?>" />
<?php if (is_mobile()) : ?>		<meta name="viewport" content="width=320,user-scalable=false" />
<?php endif; ?>
<?php if (!empty($profile->openid_server)) : ?>		<link rel="openid.server" href="<?php echo $profile->openid_server; ?>" />
<?php endif; ?>
<?php if (!empty($profile->openid_delegate)) : ?>		<link rel="openid.delegate" href="<?php echo $profile->openid_delegate; ?>" />
<?php endif; ?>
		<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet(); ?>" />
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<script type="text/javascript"></script>
	</head>
	<body id="pez">
		<div id="page">
			<ul class="accessibility">
				<li><a href="#skip" title="Skip to content">Skip to content</a></li>
			</ul>
			
			<div id="header" class="vcard">
				<h1><span class="fn"><?php echo $fullname; ?></span></h1>
				<div id="profile-photo"><img src="<?php echo $profile->photo_url; ?>" alt="[Profile photo for <?php echo $fullname; ?>]" title="Profile photo for <?php echo $fullname; ?>" class="photo" /></div>
			</div>
			<div id="main">
				<a name="skip"></a>
				<div id="content">
									
					<div id="about" class="module">
						<h2 class="module-header"><span>What I'm About</span></h2>
						<p class="module-content"><span><?php echo $profile->blurb; ?></span></p>
					</div>
						
<?php if ( ( isset($data_sources->blogs) ) && (count($data_sources->blogs) > 0) ) : ?>					
					<div id="blogs" class="module">
						<h2 class="module-header"><span>What I'm Saying:</span></h2>
						<?php
							$blogs = array();
							foreach ( array_intersect_key( $data_sources->sources, array_flip($data_sources->blogs) ) as $source )
								$blogs[] = $source[1];
						?>
						<div class="module-content items">
<?php foreach (combine_feeds($blogs, MAX_ITEMS, '~') as $key => $item) : $feed_info = explode('~', $key); ?>
							<div class="item">
								<h3><a href="<?php echo $item->get_permalink(); ?>" rel="bookmark"><?php echo $item->get_title(); ?></a>:</h3>
								<blockquote>
									<p>&ldquo;<?php echo trim_excerpt($item->get_description(), 50, '...'); ?>&rdquo; <a href="<?php echo $feed_info[2] ?>"><?php echo $feed_info[1]; ?></a> | <strong> <?php echo gmdate(DATE_FORMAT, $item->get_date('U')); ?></strong></p>
								</blockquote>
							</div>
<?php endforeach; unset($key, $item); ?>
						</div>
					</div>
<?php endif; ?>				
				
<?php if ( ( isset($data_sources->tweet) ) && (count($data_sources->tweet) > 0) ) : ?>
					<div id="tweet" class="module">
						<h2 class="module-header"><span>What My Status Updates Are:</span></h2>
						<div class="module-content links">
							<?php
								$music = array();
								foreach ( array_intersect_key( $data_sources->sources, array_flip($data_sources->tweet) ) as $source )
									$tweet[] = $source[1];
							?>
							<?php echo link_list( combine_feeds($tweet, MAX_ITEMS * 2) ) ?>
						</div>
					</div>
<?php endif; ?>
					
<?php if ( ( isset($data_sources->photos) ) && (count($data_sources->photos) > 0) ) : ?>
					<div id="photos" class="module">
						<h2 class="module-header"><span>What I'm Seeing:</span></h2>
						<div class="module-content photos">
							<?php
								$photos = array();
								foreach ( array_intersect_key( $data_sources->sources, array_flip($data_sources->photos) ) as $source )
									$photos[] = $source[1];
							?>
							<?php echo flickr_photos($photos[0], MAX_ITEMS); ?>
						</div>
					</div>
<?php endif; ?>
					
<?php if ( ( isset($data_sources->bookmarks) ) && (count($data_sources->bookmarks) > 0) ) : ?>
					<div id="bookmarks" class="module">
						<h2 class="module-header"><span>What I'm Reading:</span></h2>
						<div class="module-content links">
							<?php
								$bookmarks = array();
								foreach ( array_intersect_key( $data_sources->sources, array_flip($data_sources->bookmarks) ) as $source )
									$bookmarks[] = $source[1];
							?>
							<?php echo link_list( combine_feeds($bookmarks, MAX_ITEMS * 2) ); ?>
						</div>
					</div>
<?php endif; ?>
					
<?php if ( ( isset($data_sources->music) ) && (count($data_sources->music) > 0) ) : ?>
					<div id="music" class="module">
						<h2 class="module-header"><span>What I'm Hearing:</span></h2>
						<div class="module-content links">
							<?php
								$music = array();
								foreach ( array_intersect_key( $data_sources->sources, array_flip($data_sources->music) ) as $source )
									$music[] = $source[1];
							?>
							<?php echo link_list( combine_feeds($music, MAX_ITEMS * 2) ) ?>
						</div>
					</div>
<?php endif; ?>

<?php if ( ( isset($data_sources->location) ) && (count($data_sources->location) > 0) ) : ?>
					<div id="location" class="module">
						<h2 class="module-header"><span>Where I've Been Checking In:</span></h2>
<?php
							$location = array();
							foreach ( array_intersect_key( $data_sources->sources, array_flip($data_sources->location) ) as $source )
								$location[] = $source[1];
						?>
						<div class="module-content items">
<?php foreach (combine_feeds($location, MAX_ITEMS, '~') as $key => $item) : $feed_info = explode('~', $key); ?>
							<div class="item">
								<p>@ <a href="<?php echo $item->get_permalink(); ?>" rel="bookmark"><?php echo $item->get_title(); ?></a> <strong> <?php echo gmdate('j M Y | h:i  a T', $item->get_date('U')); ?></strong></p>
							</div>
<?php endforeach; unset($key, $item); ?>
							</div>
						</div>
					</div>
<?php endif; ?>

<?php if ( ( isset($data_sources->profiles) ) && (count($data_sources->profiles) > 0) ) : ?>
					<div id="profiles" class="module">
						<h2 class="module-header"><span>Where You Can Find Me:</span></h2>
						<div class="module-content links">
							<?php echo profile_list(); ?>
						</div>
						<div class="clear"></div>
					</div>
<?php endif; ?>
					
				</div>
			</div>
			<div id="footer">
				<p>Powered by <a href="http://pez.bogdind.com/" title="Pez">Pez</a>.</p>
			</div>
		</div>
<?php if (!empty($google_analytics)) : ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("<?php echo $google_analytics; ?>");
pageTracker._initData();
pageTracker._trackPageview();
</script>
<?php endif; ?>
<?php unset($profile, $data_sources); ?>
	</body>
</html>