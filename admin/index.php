<?php
require_once('../pz-config.php');
include_once('admin-header.php');
$profile = new Profile();

$fullname = $profile->first_name . ' ' . $profile->last_name;
?>
<div class="content-module">
               <h2><img src="<?php echo $profile->photo_url; ?>" width="40px" height="40px"> Hola, <?php echo $profile->first_name . ' ' . $profile->last_name; ?>!</h2>
                <p><h3>Welcome to your dashboard. What would you like to do today?</h3></p>
                
                <h3><a href="profile.php">Edit Your Profile</a></h3>
                <p>Set-up your profile data.  Add your name, avatar, etc.</p>
                
                <h3><a href="web-sources.php">Add a Web Data Source or three</a></h3>
                <p>Your data feeds; Facebook, flickr, etc.</p>
                
                <h3><a href="settings.php">Settings</a></h3>
                <p>Global site settings for Pez.</p>
</div>
           
<?php
include_once('admin-footer.php');
?>