<?php header("Cache-Control: max-age=1");

function filesizeH($bytes, $decimals = 2) {
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function scan ($directory) {
	$scanned = array_diff(scandir($directory), array('..', '.', 'browsr', 'index.php'));

	foreach($scanned as $entry) {
		$substr = substr($entry, 0, 3);
		if($substr!='i__') {
			if($substr=='s__') {
				echo '<tr class="file">';
				echo '<td><a href="video.php?file='.$entry.'">';
				if(substr($entry, 3, 6)=='p__')
					echo substr($entry, 6).'</a><span class="protected">Protected</span>';
				else
					echo substr($entry, 3).'</a>';
				echo '</td>';
				echo '<td>'.filesizeH(filesize($directory.'/'.$entry)).'</td>
					<td>File</td>
					<td>'.date ('F d Y H:i:s', filemtime($directory.'/'.$entry)).'</td>
				</tr>';
			} elseif(is_file($directory.'/'.$entry)) {
				echo '<tr class="file">';
				echo '<td><a href="get.php?file='.$entry.'">';
				if($substr=='p__')
					echo substr($entry, 3).'</a><span class="protected">Protected</span>';
				else
					echo $entry.'</a>';
				echo '</td>';
				echo '<td>'.filesizeH(filesize($directory.'/'.$entry)).'</td>
					<td>File</td>
					<td>'.date ('F d Y H:i:s', filemtime($directory.'/'.$entry)).'</td>
				</tr>';
			} elseif(is_dir($directory.'/'.$entry))
				echo '<tr class="folder">
					<td><a href="get.php?file='.$entry.'">'.$entry.'</a><span class="forbidden">Forbidden</span></td>
					<td>--</td>
					<td>Folder</td>
					<td>'.date ('F d Y H:i:s', filemtime($directory.'/'.$entry)).'</td>
				</tr>';
		}
	}
	return count($scanned);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>FILES.dumontchapo.net | Browsr</title>
<!-- Inspired from http://codepen.io/znak/pen/ntsdy And greatly improved (I think ;-) -->
<link type="text/css" rel="stylesheet" href="browsr/style/style.css?3" />
</head>
<body>
	<div id="it" draggable="true">
		<section>
			<div class="top-bar">
				<ul class="stop-lights">
					<li class="red-light"><button></button></li>
					<li class="orange-light"><button></button></li>
					<li class="green-light"><button></button></li>
				</ul>
				<h1>Browsr</h1>
				<ul class="tool-bar">
					<li><button></button></li>
					<li><button></button></li>
					<li><button></button></li>
					<li><button></button></li>
				</ul>
			</div>
			<nav>
				<ul>
					<li><a href="http://robin.dumontchapo.net/">r-dc</a></li>
					<li class="active"><a href="http://lab.dumontchapo.net/">lab</a></li>
					<li><a href="http://tmp.dumontchapo.net/">tmp</a></li>
				</ul>
			</nav>
			<article class="content">
				<table>
					<thead>
						<tr>
							<th>Name</th>
							<th>Size</th>
							<th>Type</th>
							<th>Last modified</th>
						</tr>
					</thead>
					<tbody>
						<?php $count = scan('files'); ?>
					</tbody>
				</table>
			</article>
			<aside class="status-bar"><?= $count .' element'.(($count>1)?'s':'') ?></aside>
		</section>
	</div>
</body>
</html>