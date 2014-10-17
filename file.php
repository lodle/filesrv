<?php
if (!isset($_GET["file"]))
{
	echo "No file id posted";
	exit;
}

require 'index_work.php';

$hash = $_GET["file"];

//Do db stuff here, make sure to set watched time stamp
foreach ($recent as $r)
{
	if ($r["hash"] == $hash)
	{
		$file = $r["path"];
		break;
	}
}

$isdir = (strpos($hash, "folder:") == 0);

if (!isset($file) || !file_exists($file))
{
	echo "No file found! (" . $file . ")";
}
else if ($_SERVER['REQUEST_METHOD'] == "DELETE")
{
	if ($isdir)
		system("rm -rf ".escapeshellarg($file));
	else
		unlink($file);
	
	if (file_exists($file))
		echo "Failed to delete file";
	else
		echo "File deleted";
}
else if ($isdir)
{
    // we deliver a zip file
    header("Content-Type: archive/zip");
 
    // filename for the browser to save the zip file
    header("Content-Disposition: attachment; filename=" . basename($file) . ".zip");
 
    // get a tmp name for the .zip
    $tmp_zip = tempnam("tmp", "tempname") . ".zip";
 
    //change directory so the zip file doesnt have a tree structure in it.
    chdir($file);
   
    // zip the stuff (dir and all in there) into the tmp_zip file
    exec('zip '.$tmp_zip.' *');
   
    // calc the length of the zip. it is needed for the progress bar of the browser
    $filesize = filesize($tmp_zip);
    header("Content-Length: $filesize");
 
    // deliver the zip file
    $fp = fopen("$tmp_zip","r");
    echo fpassthru($fp);
 
    // clean up the tmp zip file
    unlink($tmp_zip);
}
else
{
	header(SEND_FILE_HEADER . ': ' . $file);
	header('Content-type: ' . mime_content_type($file));
	header('Content-Disposition: inline; filename="' . basename($file) . '"');
}
 ?>
