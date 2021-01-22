<html> 
<body>

<?php
	function updateMF() {
		$token='__Wj7dVSTjV9YGu1guveLyDq0g7S7TfTjaHBTPTpO0kj8__';
		$dta = __DIR__ . "/donnees.txt";
		$fd=@fopen($dta,'w');
		$buf=[];
		for ($i=1; $i<75; $i++)
		{
			$textall=file_get_contents("https://rpcache-aa.meteofrance.com/internet2018client/2.0/snow?massif=$i&token=$token");
			$obj = json_decode($textall, true);
			$massif = $obj ['properties'][massif_name];
			if($massif != '')
			{
				$riskmax = $obj['properties'][massif_avalanche_risk][avalanche_risk_max];
				$nmin_nord = $obj['properties'][total_snow_per_exposition][0][snow_limit];
				$nmin_sud = $obj['properties'][total_snow_per_exposition][1][snow_limit];
				$textall = "$massif|$riskmax|$nmin_nord|$nmin_sud|$i\n";
				@fwrite($fd,$textall);
				$buf[]=$textall;
			}
			if ($i==36)$i=39;// sauter à la corse
			if ($i==41)$i=63;// sauter aux pyrenees
		}	
		@fclose($fd);
		return $buf;
	}

	$fichier = __DIR__ . '/donnees.txt';
	$fi = stat($fichier);
	// si le data existe est a plus d'une heure on fait un update
	if($fi[9] == 0 || time()-$fi[9]>3600) {$lignes = updateMF();echo "<p style='text-align:right;font-style:italic;font-size:0.7em'>Mise à jour du ". date('D j F Y à G:i:s',time()) ."</p>";}
	else {$lignes = file($fichier);echo "<p style='text-align:right;font-style:italic;font-size:0.7em'>Mise à jour du ". date('D j F Y à G:i:s',$fi[9]) ."</p>";}
	echo '<div class="x2x"><div class="tab">';

	echo '<table class="nivo">';
	echo '<tr><th>Alpes</th><th>Risque</th><th>Nord</th><th>Sud</th></tr>';
 
	foreach($lignes as $ligne_num => $ligne) { // on lit le fichier de façon séquentielle
		if($ligne_num == 23){
				echo '</table></div><div class="tab2"><table class="nivo">';
				echo '<tr><th>Corse</th><th>Risque</th><th>Nord</th><th>Sud</th></tr>';
		}
		if($ligne_num == 25){
				echo '</table><table class="nivo">';
				echo '<tr><th>Pyrénées</th><th>Risque</th><th>Nord</th><th>Sud</th></tr>';
		}
		$array = explode('|', $ligne); // retire le séparateur
		switch($array[1]) {
			case '1' : $color='bgcolor="#58FA58"';
				break;
			case '2' : $color='bgcolor="#F7FE2E"';
				break;
			case '3' : $color='bgcolor="#FF8000"';
				break;
			case '4' : $color='bgcolor="#FF0000"';
				break;
			case '5' : $color='bgcolor="#610B0B"';
				break;
			default : $color='';
		}
		$array[1]=str_replace('-1','nc',$array[1]);
		$array[2]=str_replace('-1','Sec',$array[2]);
		$array[3]=str_replace('-1','Sec',$array[3]);
		echo '<tr>';
		$ind = $array[4];
		$nurlbra = "data/aval/showbra.php?bra=" . $ind;
		echo '<td><a target="bra" href="'.$nurlbra.'">'. $array[0] .'</a>';
		echo '</td>';
		echo "<td align=center $color><b>". $array[1] .'</b></td>';
		echo '<td align=center>'.$array[2].'</td>';
		echo '<td align=center>'.$array[3].'</td>';
		echo '</tr>';
	}
	echo '</table></div></div>';
 
?>
</body></html>