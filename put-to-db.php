<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	
</head>
<body>
<?php

	$gpx = simplexml_load_file( "20200216_095948.gpx" );

	foreach ( $gpx -> trk as $trk ) {
	
		foreach ( $trk -> trkseg as $seg ) {
?>
			<table><tr><th>lat</th><th>lon</th></tr>
<?php
			foreach ( $seg->trkpt as $pt ) {
?>			
				<tr><td><?= $pt ['lat' ] ?></td><td><?= $pt [ 'lon' ] ?></td>
<?php	    
			}
?>
			</table>
<?php
		}
	}
	unset ( $gpx );
?>
</body>
</html>