<html>
<head>

</head>
<body>
Folder: 
<?php
	if (isset($_GET["folder"]))
		echo $_GET["folder"];
	else
		echo "No folder posted";
 ?>
</body>
</html>