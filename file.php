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

if (!isset($file) || !file_exists($file))
{
	echo "No file found! (" . $file . ")";
}
else if ($_SERVER['REQUEST_METHOD'] == "DELETE")
{
	unlink($file);
	
	if (file_exists($file))
		echo "Failed to delete file";
	else
		echo "File deleted";
}
else
{
	header(SEND_FILE_HEADER . ': ' . $file);
	header('Content-type: ' . mime_content_type($file));
	header('Content-Disposition: inline; filename="' . basename($file) . '"');
}
 ?>
