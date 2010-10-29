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

$no_login = true;
include_once('admin-header.php');
?>
		<h2>Login</h2>
		<?php do_messages(); ?>
		
		<form name="auth" id="id_auth" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#auth">
			<fieldset>
				<legend>Please enter your password to login</legend>
				<div>
					<p>We decided against a username/password combination as <?php echo mt_rand(93, 99) . '.' . mt_rand(1, 99); ?>% of the time people just use "admin" as the default username. Since there is only one user required for Pez, there's no point having a username! (So make sure that your password is strong!)</p>
				</div>
				<div>
					<label for="id_password">Password</label>
					<input type="password" name="password" id="id_password" />
				</div>
				<div>
					<label for="id_remember">Remember me?</label>
					<input type="checkbox" name="remember" id="id_remember" class="checkbox" />
				</div>
				<div>
					<input type="hidden" name="redirect_to" id="id_redirect_to" value="<?php echo $_GET['redirect_to']; ?>" />
					<input type="submit" name="login" id="id_login" value="Login" />
				</div>
			</fieldset>
		</form>
<?php
include_once('admin-footer.php');
?>