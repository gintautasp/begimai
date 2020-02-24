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
	$koef_grad_to_rad = (  $pi / 180 );

	function mk_point_x ( $pt_gpx ) {
		
		global $pi, $koef_grad_to_rad;
	
		$pt_in_rad  = new stdClass;

		$pt_in_rad -> lat_grad = $pt_gpx [ 'lat' ];
		$pt_in_rad -> lon_grad = $pt_gpx [ 'lon' ];
		$pt_in_rad -> lat = ( float ) $pt_gpx [ 'lat' ] * $koef_grad_to_rad;
		$pt_in_rad -> lon = ( float ) $pt_gpx [ 'lon' ] * $koef_grad_to_rad;
		$pt_in_rad -> time = $pt_gpx -> time;
		
		return $pt_in_rad;
	}
	
	function mk_point_z ( $pt_from, $pt_to ) {
	
		
		
	}

	$gpx = simplexml_load_file( "20200216_095948.gpx" );
	
	// https://lt.geofumadas.com/kompleksiniai-scenarijai-for-skai%C4%8Diavimai/
	// https://lt.wikipedia.org/wiki/Radianas
	// https://en.wikipedia.org/wiki/Haversine_formula
	
	$r = 6371; // žemės spindulys, km 

	foreach ( $gpx -> trk as $trk ) {
	
		foreach ( $trk -> trkseg as $seg ) {
?>
			<table><tr><th>lat</th><th>lon</th><th>dist</th><th>time</th></tr>
<?php

			$pt_prev_x = ( ( array ) $seg ->   trkpt [ 0 ] ) [ '@attributes' ];

			print_r ( $pt_prev_x  );

			$pt_prev = mk_point_x ( $pt_prev_x );
			
			$flag_count = true;
			$sum_dist = 0;
	
			foreach ( $seg -> trkpt as $pt_x ) {

				$pt =  mk_point_x ( $pt_x );
				
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
								( 2 *  $r * 1000 )
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