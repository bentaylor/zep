<?php
// check if an email address for the gravatar image is available
if ( isset($_GET['gravatar_email']) && $_GET['gravatar_email'] != '' )
{
	$gravatar_url = md5( $_GET['gravatar_email'] );
	exit($gravatar_url);
}

require_once('../pz-config.php');
require_once('social-networks.php');

$profile = new Profile();

// choose the form process
if ( isset($_POST['form_name']) && $_POST['form_name'] != '' )
{
	switch ($_POST['form_name'])
	{
		case 'profile_form' :

			if ( isset($_POST['photo_url']) && isset($_POST['photo_url']) )
				$profile->photo_url = $_POST['photo_url'];

			if ( isset($_POST['gravatar_check']) && isset($_POST['gravatar_url']) )
			{
				$profile->gravatar = true;
				$profile->photo_url = $_POST['gravatar_url'];
			}
			
			$profile->save();
			
			$messages[] = array('success', 'Your avatar has been updated.');
			break;
					
		default :
			break;
	}
}

include_once('admin-header.php');
?>
		<?php do_messages(); ?>
		<ul id="subnav">
		<fieldset>
		<li><a href="profile.php"><span class="add">+</span> Personal Details</a></li>
		<li class="active"><a href="avatar.php"><span class="add">+</span> Avatar</a></li>
		<li><a href="social.php"><span class="add">+</span> Social Networks</a></li>
		<li><a href="openid.php"><span class="add">+</span> Open ID</a></li>
		</fieldset>
		</ul>
		<div class="column">
			<form id="profile2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#profile2">
				<fieldset>
					<input type="hidden" name="form_name" id="id_form_name" value="profile_form" />
					<div>
						<label for="id_photo_url">Profile Photo URL</label>
						<input type="text" class="text" name="photo_url" id="id_photo_url" value="<?php echo $profile->photo_url; ?>" />
						<p>Enter the URL of your profile image. Its size depends on the current theme that you have installed. Please refer to the theme's readme for guidance.</p>
						<?php
							if ( $profile->photo_url != '' )
								echo '<div class="photo"><img src="' . $profile->photo_url . '" alt="[profile photo]" title="Profile Photo" /></div>';
						?>
					</div>
<!--
					<div>
						<label for="id_gravatar_check">Got Gravatar?</label>
						<?php $has_gravatar = ($profile->gravatar != '') ? ' checked="true"' : ''; ?>
						<input type="checkbox" name="gravatar_check" id="id_gravatar_check" <?php echo $has_gravatar; ?>onchange="javascript:get_gravatar();" />
						<div id="gravatar-options" style="display:<?php echo ($profile->gravatar != '') ? 'block' : 'none'; ?>;">
							<div>
								<label for="id_gravatar_email">Email</label>
								<input type="text" name="gravatar_email" id="id_gravatar_email" value="<?php echo $profile->email; ?>" />
							</div>
							<div>
								<label for="id_gravatar_url">URL</label>
								<input type="text" name="gravatar_url" id="id_gravatar_url" value="<?php echo $profile->photo_url; ?>" />
							</div>
							<div>
								<img id="gravatar_image" src="<?php echo $profile->gravatar; ?>" alt="[gravatar]" title="Gravatar" />
							</div>
						</div>
					</div>
-->
					<div><input type="submit" name="save" id="id_save_3" value="Save Changes" class="button" /></div>
				</fieldset>
				</form>
<?php
include_once('admin-footer.php');
?>