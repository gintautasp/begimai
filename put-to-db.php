<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	
</head>
<body>
<?php

	$gpx = simplexml_load_file( "20200216_095948.gpx" );
	
	// https://lt.geofumadas.com/kompleksiniai-scenarijai-for-skai%C4%8Diavimai/
	// https://lt.wikipedia.org/wiki/Radianas
	// https://en.wikipedia.org/wiki/Haversine_formula
	
	$r = 6371; // žemes spindulys, km 

	foreach ( $gpx -> trk as $trk ) {
	
		foreach ( $trk -> trkseg as $seg ) {
?>
			<table><tr><th>lat</th><th>lon</th></tr>
<?php

			$pt_prev_x = ( ( array ) $seg -> trkpt [ 0 ] ) [ '@attributes' ];
			// $pt_prev [ 'lat' ] = $seg -> trkpt [ 0 ] [ 'lat' ];
			// $pt_prev [ 'lon' ] = $seg -> trkpt [ 0 ] [ 'lon' ];

			
			// print_r ( $pt_prev [ 'lat' ] [ 0 ]  );
			// print_r ( $pt_prev [ 'lon' ] [ 0 ] ); 
			print_r ( $pt_prev_x /* [ '@attributes' ] */ );
			$pt_prev = array();
			$pt = array();
			$pt_prev [ 'lat' ] = ( float ) $pt_prev_x [ 'lat' ];
			echo ( $pt_prev [ 'lat' ] );
			$pt_prev [ 'lon' ] = ( float ) $pt_prev_x [ 'lon' ];
			echo ( $pt_prev [ 'lon' ] );			
			
			$flag_count = true;
			$sum_dist = 0;
	
			foreach ( $seg -> trkpt as $pt_x ) {
			
				$pt [ 'lat' ] = ( float ) $pt_x [ 'lat' ];
				$pt [ 'lon' ] = ( float ) $pt_x [ 'lon' ];
			
				if ( $flag_count ) {
			
					$dist 
						= 
								acos ( 
								
									sqrt (
											pow ( sin ( ( $pt [ 'lat' ] - $pt_prev [ 'lat' ] ) / 2 ), 2 ) 
										+ 	
											cos ( $pt_prev [ 'lat' ] )
										* 
											cos ( $pt [ 'lat' ] ) 
										*
											pow ( sin ( ( $pt [ 'lon' ] - $pt_prev [ 'lon' ] ) / 2 ), 2 )
									)
								) 
							* 
								( 2 *  $r )
						;
					$sum_dist += $dist;
						
					// $flag_count = false;
				}
?>			
				<tr>
					<td><?= $pt [ 'lat' ] ?></td>
					<td><?= $pt [ 'lon' ] ?></td>
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