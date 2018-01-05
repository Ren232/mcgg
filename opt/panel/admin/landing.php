<?php
$pageid = "landing";
require_once('header_inc.php');
require_once('includes/header.php');
if(isset($_POST['markdown'])) {
$filename = "markdown.md";
	// Let's make sure the file exists and is writable first.
	if (is_writable($filename)) {

	    // In our example we're opening $filename in append mode.
	    // The file pointer is at the bottom of the file hence
	    // that's where $somecontent will go when we fwrite() it.
	    if (!$handle = fopen($filename, 'w')) {
	         echo "Cannot open file ($filename)";
	         exit;
	    }

	    // Write $somecontent to our opened file.
	    if (fwrite($handle, $_POST['markdown']) === FALSE) {
	        echo "Cannot write to file ($filename)";
	        exit;
	    }

	    //echo "Success, wrote ($somecontent) to file ($filename)";

	    fclose($handle);

	} else {
	    //echo "The file $filename is not writable";
		echo "Uh-oh, something went wrong. Please open a support ticket in the <a href='http://billing.hostiio.com/'>customer portal</a>";
	}
	
}
?>
	<div id="page_wrap">
    <h1>Landing Page Editor</h1>
    <div>
	<p>The landing page is powered by <a href='http://daringfireball.net/projects/markdown/'>Markdown</a>, an easy to use text-to-HTML conversion tool for writing. It's not hard, we promise. Here's the <a href='http://daringfireball.net/projects/markdown/syntax'>syntax</a>. We also are utilizing <a href="http://michelf.com/projects/php-markdown/extra/">PHP Markdown Extra</a> for some extra syntax options. You can view the extra syntax options <a href="http://michelf.com/projects/php-markdown/extra/">here</a>. You can do almost anything you can do in HTML with Markdown.</p>
	<br />
	<form action="landing.php" method="POST">
		<textarea name="markdown" id="markdown"><?php echo file_get_contents("markdown.md"); ?></textarea><br />
		<input type="submit" class="btn">
	</form>
    </div>
	</div>
<?php require_once('includes/footer.php'); ?>