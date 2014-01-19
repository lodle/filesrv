<?php
require "index_work.php"
?>
<html>
<head>
<title>File Index</title>
<style>
	th
	{
		text-align:center;
		background-color:lightgrey;
		padding:5px;
	}
	.num
	{
		text-align:center;
	}
	
	.date
	{
		font-size:smaller;
		text-align:center;
	}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="ui.achtung-min.css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="ui.achtung-min.js"></script>

<script>

formatDate = function(d) {

  var dd = d.getDate()
  if ( dd < 10 ) dd = '0' + dd

  var mm = d.getMonth()+1
  if ( mm < 10 ) mm = '0' + mm

  var yy = d.getFullYear() % 100
  if ( yy < 10 ) yy = '0' + yy

  return d.getHours() + ":" + d.getMinutes() + " " + mm+'/'+dd+'/'+yy
}

deleteFile = function(hash) {	
	$.ajax({
	  type: "DELETE",
	  url: "/file/" + hash,
	  success: function(data){
		if (data != "File deleted") {
			$.achtung({message: 'Failed to delete file: ' + data, timeout: 0});
		}
		else {
			$("#" + hash).remove();
		}
	  },
	  dataType: "text"
	});
};

markedWatched = function(hash){
	var d = new Date();
	$("#watched-" + hash).text(formatDate(d));
};

$(document).ready(function() {
	$("#tabs").tabs();
	
	$(".del-button").click(function(){
		deleteFile($(this).attr("data-id"));
		return false;
	});
	
	$(".download-link").click(function(){
		markedWatched($(this).attr("data-id"));
		return true;
	});
});
</script>
</head>
<body>
<div id="tabs">
	<ul>
		<li><a href="#recent-files">Recent</a></li>
		<li><a href="#cat-files">Categorised</a></li>
		<li><a href="#add-file">Add File</a></li>
	</ul>

	<div id="recent-files">
		<table width="800px">
			<tr>
				<th width="65%">Show</th>
				<th>DL</th>				
				<th width="5%">Season</th>
				<th width="5%">Episode</th>
				<th width="10%">Added</th>
				<th width="10%">Watched</th>
				<th width="5%"></th>
			</tr>
		<?php
		foreach ($recent as $r)
		{
			echo '
			<tr id="'.$r["hash"].'">
				<td><a href="#'.$r["name"].'">'.$r["name"].'</a></td>
				<td><a href="/file/'.$r["hash"].'" class="download-link" data-id="'.$r["hash"].'"><img src="file.jpg" width="24px"/></a></td>
				<td class="num">'.$r["season"].'</td>
				<td class="num">'.$r["episode"].'</td>
				<td class="date">'.strftime("%H:%M %x", $r["modtime"]).'</td>
				<td class="date"><span id="watched-'.$r["hash"].'"></span></td>
				<td><input type="button" value="del" class="del-button" data-id="'.$r["hash"].'"/>
			</tr>';
		}
		?>
		</table>
	</div>

	<div id="cat-files">
	</div>

	<div id="add-file">
		<table>
			<tr>
				<td>Url:</td>
				<td>
					<form action="/add" method="POST">
						<input type="text" name="url" style="width:350px" /><input type="submit" value="Add Url">
					</form>
				</td>
			</tr>
			<tr>
				<td>File:</td>
				<td>
					<form action="/add" method="POST">
						<input type="file" name="file" accept=".torrent" /><input type="submit" value="Add File">
					</form>
				</td>
			</tr>
		</table>
	</div>
</div>
</body>
</html>