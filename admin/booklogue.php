<?php
require_once('../pz-config.php');
$bkl_file = '../booklogue/index.html';

// form processing
if ( isset($_POST['save']) )
{
	if ( isset($_POST['editor']) && $_POST['editor'] != '')
	{
		if ($f = @fopen($css_file, 'w'))
		{
			if (@fwrite($f, stripslashes($_POST['editor'])))
			{
				@fclose($f);
			}
			else
			{
				$messages[] = array('error', "Could not write to file '$bkl_file'.");
			}
		}
		else
		{
			$messages[] = array('error', "Could not open file '$bkl_file'.");
		}
	}
	if (empty($messages))
		$messages[] = array('success', 'Your CSS theme styles have been updated.');
}


$html_head = <<<HTML
		<script type="text/javascript" src="../includes/codepress/codepress.js"></script>
		<script type="text/javascript">
			function submitform(frm)
			{
				id_editor.toggleEditor();
				return true;
			}
		</script>

HTML;

include_once('admin-header.php');

$bkl = file_get_contents($bkl_file);
?>
		<?php do_messages(); ?>
		
		<form id="styles" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="javascript:return submitform(this);">
			<fieldset>
				<legend>Booklogue</legend>
				<div>
					<label for="id_editor">Editor</label>
					<textarea name="editor" id="id_editor" class="codepress css editor" wrap="off"><?php echo $bkl; ?></textarea>
				</div>
				<div><input type="submit" name="save" id="id_save_1" value="Save Changes" class="button" /></div>
				<h3>Limitations of using this web-based HTML editor</h3>
				<p>This editor has been tested and works in the latest version of Firefox. It does not work in Chrome or Safari.</p>
				<br>
				<p>Some browsers impose some limits on the amount of data that can be entered in a textarea. <strong>Limits like 32 or 64 kilobytes</strong> (32,768 or 65,536 characters).</p>
				<p>If you are experiencing any problems, we recommend that you edit the HTML stylesheet with your preferred editor, then upload the "booklogue/index.html" via FTP.</p>
			</fieldset>
		</form>
<?php
include_once('admin-footer.php');
?>