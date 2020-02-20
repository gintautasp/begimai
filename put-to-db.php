<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	
</head>
<body>
<?php

	$pi = 4 * atan ( 1.0 );
	echo "pi: " . $pi . "<br>";

	function point_in_rad ( $pt_in_grad ) {
		
		global $pi;
	
		$pt_in_rad  = new stdClass;
		$koef = (  $pi / 180 );
		
		$pt_in_rad -> lat = ( float ) $pt_in_grad [ 'lat' ] * $koef;
		$pt_in_rad -> lon = ( float ) $pt_in_grad [ 'lon' ] * $koef;
		$pt_in_rad -> time = $pt_in_grad -> time;
		
		return $pt_in_rad;
	}

	$gpx = simplexml_load_file( "20200216_095948.gpx" );
	
	// https://lt.geofumadas.com/kompleksiniai-scenarijai-for-skai%C4%8Diavimai/
	// https://lt.wikipedia.org/wiki/Radianas
	// https://en.wikipedia.org/wiki/Haversine_formula
	
	$r = 6371; // žemės spindulys, km 

	foreach ( $gpx -> trk as $trk ) {
	
		foreach ( $trk -> trkseg as $seg ) {
?>
			<table><tr><th>lat</th><th>lon</th></tr>
<?php

			$pt_prev_x = ( ( array ) $seg -> trkpt [ 0 ] ) [ '@attributes' ];

			print_r ( $pt_prev_x  );

			$pt_prev = point_in_rad ( $pt_prev_x );
			
			$flag_count = true;
			$sum_dist = 0;
	
			foreach ( $seg -> trkpt as $pt_x ) {

				$pt =  point_in_rad ( $pt_x );
				
				if ( $flag_count ) {
			
					$dist 
						= 
								asin ( 
								
									sqrt (
											pow ( sin ( ( $pt -> lat - $pt_prev -> lat ) / 2 ), 2 ) 
										+ 	
											cos ( $pt_prev -> lat )
										* 
											cos ( $pt -> lat ) 
										*
											pow ( sin ( ( $pt -> lon - $pt_prev -> lon ) / 2 ), 2 )
									)
								) 
							* 
								( 2 *  $r * 1000)
						;
					$sum_dist += $dist;
				}
?>			
				<tr>
					<td><?= $pt -> lat ?></td>
					<td><?= $pt -> lon ?></td>
					<td><?= $dist  ?></td>
					<td><?= $pt_x -> time  ?></td>					
				</tr>
<?php	    
				$pt_prev = $pt;
			}
?>
			<tr>
				<td></td>
				<td></td>
				<td><?= $sum_dist  ?></td>
				<td><?= $pt_x -> time  ?></td>			
			</tr>
			</table>
<?php
		}
	}
	unset ( $gpx );
?>
</body>
</html>