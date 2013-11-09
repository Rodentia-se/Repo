<?php
//cf_array.php

function creamOrder($ordning, $creamfiles) {

	// $ordning16 = array(1,16,9,8,5,12,13,4,3,14,11,6,7,10,15,2);

	$antal = count($ordning);

	for ($i = 0; $i < $creamfiles ; $i ++ ) {

		for ($j = (($antal/pow(2,$i))-1); $j > 0 ; $j--) {	

			$nyplats = ($antal/pow(2, $i))-1;

			if ($ordning[$j] > ($antal/(pow(2,($i+1))))) {

				$value = $ordning[$j];
				unset ($ordning[$j]);
				array_splice($ordning, $nyplats, 0, $value);
				$ordning = array_merge($ordning);

			}
		} 
			
	}

	
	// Invert the elements that hasn't been moved. First, get their values to a temp-array
	for ($i = 0; $i < $antal/(pow(2, $creamfiles)); $i ++ ) {
	
		$swap[] = $ordning[$i];
	
	}

	$swapcount = count($swap)-1;

	// ...then, put 'em back.
	for ($i = 0; $i < $antal/(pow(2, $creamfiles)); $i ++ ) {
	
		$ordning[$i] = $swap[$swapcount-$i];
	
	}

	// And finally, invert the whole array so it's ready for reading.
	$ordning = array_reverse($ordning);

	return $ordning;

}
?>
