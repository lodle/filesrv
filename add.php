<html>
<head>

</head>
<body>
Add: 
<?php

if (isset($_POST["file"]))
	echo print_r($_POST["file"]);
else if (isset($_POST["url"]))
	echo $_POST["url"];
else
	echo "No file or url posted";

?>
</body>
</html>