<?php
require_once('../pz-config.php');

if ( isset($_GET['logout']) && $_GET['logout'] == 'true' )
{
	setcookie(PASS_COOKIE, '', time() - 31536000);
	$messages[] = array('info', 'You have been logged out.');
}

if ( isset($_POST['login']) )
{
	if ( isset($_POST['password']) )
	{
		if ( PASSWORD == $_POST['password'] )
		{
			$expire = ( isset($_POST['remember']) ) ? time() + 31536000 : 0;
			setcookie(PASS_COOKIE, md5(md5($_POST['password'])), $expire);
			
			$location = ( ($_POST['redirect_to'] != '') ? $_POST['redirect_to'] : './index.php' );
			header("Location: $location");
			exit();
		}
	}
}

?>
<html dir="ltr" lang="en">
	<head>
	    <link href='http://fonts.googleapis.com/css?family=Lobster&subset=latin' rel='stylesheet' type='text/css'>
		<title>pez: admin</title>
		<style type="text/css">@import url(admin.css);</style>
		<script type="text/javascript" src="../includes/js/jquery-1.2.3.pack.js"></script>
		<script type="text/javascript" src="../includes/js/functions.js"></script>
<?php if ( isset($html_head) ) echo $html_head; ?>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<h1>pez</h1>
				<ul id="menu">
					<li class="first">Please enter your password to begin.</li>
					</ul>
			</div>
			<div id="main">
				<div id="logout">
					<?php do_messages(); ?>
				</div>
				<div id="login">
					<form name="auth" id="id_auth" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#auth">
						<h3>Password</h3>
						<input type="password" name="password" class="password" /><br>
						<input type="checkbox" name="remember" id="id_remember" class="checkbox" /> <label for="id_remember">Remember me?</label>
						<input type="hidden" name="redirect_to" id="id_redirect_to" value="<?php echo $_GET['redirect_to']; ?>" />
						<input type="submit" name="login" value="Login" class="save" />
					</form>
				</div>	
			</div>
<?php
include_once('admin-footer.php');
?>