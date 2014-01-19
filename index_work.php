<?php
require 'settings.php';

$recent = array();
$dirit = new DirectoryIterator(COMPLETE_PATH);

foreach ($dirit as $d)
{
	if ($d->isFile()) 
		array_push($recent, GetFileInfo($d->getFilename(), $d->getMTime()));
}

usort($recent, "SortByModTime");

function SortByModTime($a, $b)
{
	$a = $a["modtime"];
	$b = $b["modtime"];

    if ($a == $b)
        return 0;

    return ($a > $b) ? -1 : 1;
}

function GetFileInfo($file, $modTime)
{
	$ret = array();

	foreach (explode('.', $file) as $p)
	{
		if (preg_match("/S([0-9][0-9])E([0-9][0-9])/i", $p, $matches))
		{
			$ret["season"] = $matches[1];
			$ret["episode"] = $matches[2];
			break;
		}
		else if (preg_match("/([0-9])x([0-9][0-9])/", $p, $matches))
		{
			$ret["season"] = "0" . $matches[1];
			$ret["episode"] = $matches[2];
			break;
		}
		else
		{
			if (isset($ret["name"]))
				$ret["name"] .= " " . $p;
			else
				$ret["name"] = $p;
		}
	}

	$ret["modtime"] = $modTime;
	$ret["hash"] = md5($file);
	$ret["path"] = COMPLETE_PATH . PATH_SLASH . $file;
	
	return $ret;
}

?>
