<?php if ( !isset($no_login) ) auth_redirect(); ?>
<html dir="ltr" lang="en">
	<head>
		<title>Pez: Admin</title>
		<style type="text/css">@import url(admin.css);</style>
		<script type="text/javascript" src="../includes/js/jquery-1.2.3.pack.js"></script>
		<script type="text/javascript" src="../includes/js/functions.js"></script>
<?php if ( isset($html_head) ) echo $html_head; ?>
	</head>
	<body>
		<div id="page">
			<div id="header">
				<h1>Pez: Your online personal profile (<a href="../" rel="external">View Site</a>)</h1>
				<ul id="menu">
					<li class="first<?php if ($_SERVER['SCRIPT_NAME'] == '/pez/admin/profile.php') echo ' current'; ?>"><a href="profile.php">Profile</a></li>
					<li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/pez/admin/web-sources.php') echo 'current'; ?>"><a href="web-sources.php">Web Data Sources</a></li>
					<li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/pez/admin/settings.php') echo ' current'; ?>"><a href="settings.php">Settings</a></li>
					<li class="<?php if ($_SERVER['SCRIPT_NAME'] == '/pez/admin/style.php') echo ' current'; ?>"><a href="style.php">CSS Style</a></li>
					<li class="last"><?php echo ( is_auth() ) ?  '<a href="login.php?logout=true">Logout</a>' : '<a href="login.php">Login</a>'; ?></li>
				</ul>
			</div>
			<div id="main">