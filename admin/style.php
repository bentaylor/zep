<?php
require_once('../pz-config.php');
$css_file = '../styles/default.css';

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
				$messages[] = array('error', "Could not write to file '$css_file'.");
			}
		}
		else
		{
			$messages[] = array('error', "Could not open file '$css_file'.");
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

$css = file_get_contents($css_file);
?>
		<h2>CSS Style</h2>
		<?php do_messages(); ?>
		
		<form id="styles" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="javascript:return submitform(this);">
			<fieldset>
				<legend>Pez Theme Editor</legend>
				<div>
					<label for="id_editor">CSS Editor</label>
					<textarea name="editor" id="id_editor" class="codepress css editor" wrap="off"><?php echo $css; ?></textarea>
				</div>
				<div><input type="submit" name="save" id="id_save_1" value="Save Changes" class="button" /></div>
				<h3>Limitations of using this web-based CSS editor</h3>
				<p>Some browsers impose some limits on the amount of data that can be entered in a textarea. <strong>Limits like 32 or 64 kilobytes</strong> (32,768 or 65,536 characters).</p>
				<p>If you are experiencing any problems, we recommend that you edit the CSS stylesheet with your preferred editor, then upload the "style.css" via FTP.</p>
			</fieldset>
		</form>
<?php
include_once('admin-footer.php');
?>