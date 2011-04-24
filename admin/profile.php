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
			if ( isset($_POST['first_name']) && $_POST['first_name'] != '')
				$profile->first_name = $_POST['first_name'];
			
			if ( isset($_POST['middle_name']) )
				$profile->middle_name = $_POST['middle_name'];
			
			if ( isset($_POST['last_name']) && $_POST['last_name'] != '')
				$profile->last_name = $_POST['last_name'];
						
			if ( isset($_POST['blurb']) && $_POST['blurb'] != '')
				$profile->blurb = stripslashes($_POST['blurb']);
			
			if ( isset($_POST['adjectives']) && $_POST['adjectives'] != '')
				$profile->adjectives = $_POST['adjectives'];

			if ( isset($_POST['location']) && $_POST['location'] != '')
				$profile->location = $_POST['location'];
			
			if ( isset($_POST['country']) && $_POST['country'] != '')
				$profile->country = $_POST['country'];
			
			if ( isset($_POST['email']) && $_POST['email'] != '')
				$profile->email = $_POST['email'];
			
			if ( isset($_POST['dob_day']) && isset($_POST['dob_month']) && isset($_POST['dob_year']))
				$profile->dob = array('day' => $_POST['dob_day'], 'month' => $_POST['dob_month'], 'year' => $_POST['dob_year']);
			
			if ( isset($_POST['gender']) && $_POST['gender'] != '')
				$profile->gender = $_POST['gender'];
					
			if ( isset($_POST['openid_server']) && isset($_POST['openid_server']) )
				$profile->openid_server = $_POST['openid_server'];
			
			if ( isset($_POST['openid_delegate']) && isset($_POST['openid_delegate']) )
				$profile->openid_delegate = $_POST['openid_delegate'];
			
			$profile->save();
			
			$messages[] = array('success', 'Your profile has been updated.');
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
		<li class="active"><a href="profile.php"><span class="add">+</span> Personal Details</a></li>
		<li><a href="avatar.php"><span class="add">+</span> Avatar</a></li>
		<li><a href="social.php"><span class="add">+</span> Social Networks</a></li>
		</fieldset>
		</ul>
		<div class="column">
			<form id="profile" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<fieldset>
					<input type="hidden" name="form_name" id="id_form_name" value="profile_form" />
					<div>
						<label for="id_first_name">First name</label>
						<input type="text" class="text" name="first_name" id="id_first_name" value="<?php echo $profile->first_name; ?>" maxlength="32" />
					</div>
					<div>
						<label for="id_last_name">Middle name</label>
						<input type="text" class="text" name="middle_name" id="id_middle_name" value="<?php echo $profile->middle_name; ?>" maxlength="32" />
					</div>
					<div>
						<label for="id_last_name">Last name</label>
						<input type="text" class="text" name="last_name" id="id_last_name" value="<?php echo $profile->last_name; ?>" maxlength="32" />
					</div>
					<div>
						<label for="id_blurb">Short blurb about me</label>
						<textarea name="blurb" id="id_blurb" rows="3" cols="40"><?php echo stripslashes($profile->blurb); ?></textarea>
						<p>This is a paragraph sized description of you that appears at the top of the page. Remember to use paragraph and break tags.</p>
					</div>
					<div>
						<label for="id_last_name">Adjectives</label>
						<input type="text" class="text" name="adjectives" id="adjectives" value="<?php echo $profile->adjectives; ?>" maxlength="140" />
					</div>
					<div>
						<label for="id_location">Location</label>
						<input type="text" class="text" name="location" id="id_location" value="<?php echo $profile->location; ?>" maxlength="32" />
					</div>
					<div>
						<label for="id_country">Country</label>
						<select name="country" id="id_country" class="select">
							<option class="select" value="-1">Pick one...</option>
						<?php foreach ($countries as $code => $country) : ?>
							<option value="<?php echo $country; ?>"<?php if ($profile->country == $country) echo ' selected="true"'; ?>><?php echo $country; ?></option>
						<?php endforeach; ?>
						</select>
					</div>
					<div>
						<label for="id_email">Email address</label>
						<input type="text" class="text" name="email" id="id_email" value="<?php echo $profile->email; ?>" />
						<p>Leave this blank if you don't want to publish your email address.</p>
					</div>
					<div>
						<label>Date of Birth</label>
						<select name="dob_day" id="id_dob_day">
							<option value="-1">Day</option>
							<?php for ($i = 1; $i <= 31; $i++) echo "<option value=\"$i\"" . (($i == $profile->dob['day']) ? ' selected="true"' : '') . ">$i</option>"; ?>
						</select>
						<select name="dob_month" id="id_dob_month">
							<option value="-1">Month</option>
							<?php for ($i = 1; $i <= 12; $i++) echo "<option value=\"$i\"" . (($i == $profile->dob['month']) ? ' selected="true"' : '') . ">" . date('F', mktime(0, 0, 0, $i)) . "</option>"; ?>
						</select>
						<select name="dob_year" id="id_dob_year">
							<option value="-1">Year</option>
							<?php for ($i = date('Y'); $i >= 1900; $i--) echo "<option value=\"$i\"" . (($i == $profile->dob['year']) ? ' selected="true"' : '') . ">$i</option>"; ?>
						</select>
					</div>
					<div>
						<label for="id_gender">Gender</label>
						<select name="gender" id="id_gender">
							<option value="-1">Select One</option>
							<option value="m"<?php if ($profile->gender == 'm') echo ' selected="true"'; ?>>Male</option>
							<option value="f"<?php if ($profile->gender == 'f') echo ' selected="true"'; ?>>Female</option>
						</select>
					</div>
					<div><input type="submit" name="save" id="id_save_1" value="Save Changes" class="button" /></div>
				</fieldset>
			</form>
		</div>
		
					</div>
		</form>
<?php
include_once('admin-footer.php');
?>