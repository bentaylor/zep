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
		case 'sn_form' :
			$sources = new WebDataSources();
			if ($_POST['network_id'] != '-1')
			{
				$network_id = $_POST['network_id'];
				$username = $_POST['username'];
				$sources->profiles[] = array($network_id, $social_networks[$network_id], $username);
				$messages[] = array('success', 'Successfully added ' . $social_networks[$network_id][0] . ' to your profile list.');
			}
			if ($_POST['delete_sn'] != '')
			{
				$delete_id = $_POST['delete_sn'];
				$messages[] = array('success', $sources->profiles[$delete_id][1][0] . ' has been removed from your profile list.');
				unset($sources->profiles[$delete_id]);
			}
			$sources->save();
			unset($sources);
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
		<li><a href="avatar.php"><span class="add">+</span> Avatar</a></li>
		<li class="active"><a href="social.php"><span class="add">+</span> Social Networks</a></li>
		<li><a href="openid.php"><span class="add">+</span> Open ID</a></li>
		</fieldset>
		</ul>


<div class="column">
			<form name="add-network" id="add-network" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#add-network">
				<fieldset>
					<p>List your social network profiles here.</p>
					<input type="hidden" name="form_name" id="id_form_name" value="sn_form" />
					<div>
						<label for="id_network_id">Select a social network</label>
						<select name="network_id" id="id_network_id">
							<option class="select" value="-1">Pick one...</option>
						<?php foreach ($social_networks as $name => $site) : ?>
							<option class="<?php echo $name; ?>" value="<?php echo $name; ?>"><?php echo $site[0]; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
					<div>
						<label for="id_username">Username / User ID</label>
						<input id="id_username" type="text" class="text" name="username" maxlength="32" value="<?php echo (isset($_POST['username'])) ? $_POST['username'] : ''; ?>" />
						<p>Bebo, Facebook? Use the number in the URL of your 'Profile' page (e.g. <?php $rand = rand(100000000, 999999999); echo $rand; ?>)</p>
					</div>
					<div><input type="submit" name="save" id="id_save_2" value="Add Social Network" class="button" /></div>
					<div class="networks">
						<?php echo profile_list(true); ?>
					</div>
				</fieldset>
			</form>
			<?php
include_once('admin-footer.php');
?>