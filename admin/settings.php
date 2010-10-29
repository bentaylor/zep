<?php
require_once('../pz-config.php');
require_once('social-networks.php');

// form processing
$settings = new Settings();

if ( isset($_POST['save']) )
{
	if ( isset($_POST['charset']) && $_POST['charset'] != '')
		$settings->charset = $_POST['charset'];
	
	if ( isset($_POST['max_items']) )
		$settings->max_items = $_POST['max_items'];
	
	if ( isset($_POST['date_format']) && $_POST['date_format'] != '')
		$settings->date_format = $_POST['date_format'];
	
	if ( isset($_POST['google_analytics']) )
		$settings->google_analytics = $_POST['google_analytics'];
	
	if ( isset($_POST['tagspace_url']) && $_POST['tagspace_url'] != '-1')
		$settings->tagspace_url = $_POST['tagspace_url'];
	
	$settings->save();
	$messages[] = array('success', 'Your settings have been updated.');
}

include_once('admin-header.php');
?>
		<h2>Settings</h2>
		<?php do_messages(); ?>
		
		<form name="settings" id="settings" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>#settings">
			<fieldset>
				<legend>Pez Settings</legend>
				<div>
					<label for="id_charset">Charset Encoding</label>
					<input type="text" name="charset" id="id_charset" value="<?php echo $settings->charset; ?>" maxlength="32" />
					<p>Enter your charset here. (i.e. UTF-8)</p>
				</div>
				<div>
					<label for="id_max_items">Max Items</label>
					<select name="max_items" id="id_max_items">
						<?php for ($i = 0; $i <= 25; $i++) echo "<option value=\"$i\"" . (($i == $settings->max_items) ? ' selected="true"' : '') . ">$i</option>"; ?>
					</select>
					<p>Set the number of blog articles you want to appear here.</p>
				</div>
				<div>
					<label for="id_date_format">Date Format</label>
					<input type="text" name="date_format" id="id_date_format" value="<?php echo $settings->date_format; ?>" />
					<p><a href="http://uk2.php.net/date" rel="external">Documentation on date formatting.</a></p>
				</div>
				<div>
					<label for="id_google_analytics">Google Analytics</label>
					<input type="text" name="google_analytics" id="id_google_analytics" value="<?php echo $settings->google_analytics; ?>" />
					<p>Set your Google Analytics ID here.</p>
				</div>
				<div>
					<label for="id_tagspace_url">Tagspace</label>
					<select name="tagspace_url" id="id_tagspace_url">
						<option class="select" value="-1">Pick one...</option>
						<?php
							foreach ($tagspaces as $name => $url)
							{
								echo "<option value=\"$url\"" . (($url == $settings->tagspace_url) ? ' selected="true"' : '') . ">$name</option>";
							}
						?>
					</select>
					<p>Select a destination tagspace for the tag links in your tag cloud.</p>
				</div>
				<div><input type="submit" name="save" id="id_save_1" value="Save Changes" class="button" /></div>
			</fieldset>
		</form>
<?php
include_once('admin-footer.php');
?>