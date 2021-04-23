<?php
/*
    Copyright (C) Nathanael Schaeffer
    Copyright (C) Camptocamp Association

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once('./settings.inc.php');

// la date limite des sorties, commune à tous les sites => 1 mois.
	$dlim = date('Y-m-d',time()-31*24*3600);
	$today = date('Y-m-d',time());
	ignore_user_abort(TRUE);
	$canDownload = true;

//////////////
// les sorties sont enregistrées par :
// * date * site * region * iti * lien * participants * cot
//////////////

function make_region_list(&$sorties)
{
	$reg[] = array( 'nom' => '+ Savoies', 'key' => 'Aravis|Bornes|Bauges|Chablais|Fauci|Rouges|Blanc|Bianco|Giffre|Savoie', 'nbr' => 0);
	$reg[] = array( 'nom' => '&nbsp; Aravis - Bornes', 'nbr' => 0, 'key' => 'Aravis|Bornes' );
	$reg[] = array( 'nom' => '&nbsp; Bauges', 'nbr' => 0, 'key' => 'Bauges' );
	$reg[] = array( 'nom' => '&nbsp; Chablais - Faucigny - Aig.Rouges', 'nbr' => 0, 'key' => 'Chablais|Rouges|Fauci|Giffre' );
	$reg[] = array( 'nom' => '&nbsp; Mont-Blanc', 'nbr' => 0, 'key' => 'Blanc|Bianco' );
	$reg[] = array( 'nom' => '+ Maurienne et Tarentaise', 'nbr' => 0, 'key' => 'Beaufort|lauz|Vanoise|Maurienne|Charbonnel|Cerces|Ambin|Thabor|Tarentaise|Alpes Gr' );
	$reg[] = array( 'nom' => '&nbsp; Beaufortain', 'nbr' => 0, 'key' => 'Beaufort' );
	$reg[] = array( 'nom' => '&nbsp; Vanoise-Lauzière', 'nbr' => 0, 'key' => 'Vanoise|lauz' );
	$reg[] = array( 'nom' => '&nbsp; Charbonnel - Grées', 'nbr' => 0, 'key' => 'Charbonnel|Tarentaise|Maurienne|Alpes Gr' );
	$reg[] = array( 'nom' => '&nbsp; Cerces - Thabor - Ambin', 'nbr' => 0, 'key' => 'Cerces|Thabor|Ambin|Maurienne' );
	$reg[] = array( 'nom' => '+ Autour de Grenoble', 'key' => 'Belledonne|Chartreuse|Vercors|Rousses|Taillefer|Mat.+sine|Beaumont|Is.re|Is..re', 'nbr' => 0 );
	$reg[] = array( 'nom' => '&nbsp; Belledonne', 'nbr' => 0, 'key' => 'Belledonne' );
	$reg[] = array( 'nom' => '&nbsp; Chartreuse', 'nbr' => 0, 'key' => 'Chartreuse' );
	$reg[] = array( 'nom' => '&nbsp; Rousses - Arves - Galibier', 'nbr' => 0, 'key' => 'Rousses|Arves|Galibier' );
	$reg[] = array( 'nom' => '&nbsp; Taillefer - Matheysine', 'nbr' => 0, 'key' => 'Taillefer|Beaumont|Mat.+sine' );
	$reg[] = array( 'nom' => '&nbsp; Vercors', 'nbr' => 0, 'key' => 'Vercors' );
	$reg[] = array( 'nom' => '+ Alpes du Sud', 'key' => 'Dign|Queyras|Parpaillon|Ubaye|Brian|Maritime|Mercantour|Pelat|Baron|voluy|crins|valg|oisans|combeyn|champsaur|grasse|provence|du.Sud', 'nbr' => 0);
	$reg[] = array( 'nom' => '&nbsp; Alpes Maritimes', 'nbr' => 0, 'key' => 'Maritime|Mercantour|Pelat' );
	$reg[] = array( 'nom' => '&nbsp; Dévoluy', 'nbr' => 0, 'key' => 'voluy' );
	$reg[] = array( 'nom' => '&nbsp; Ecrins', 'nbr' => 0, 'key' => 'crins|champsaur|valg|combeyn|oisans' );
	$reg[] = array( 'nom' => '&nbsp; Préalpes de Provence', 'key' => 'Dign|Baron|grasse|prov', 'nbr' => 0);
	$reg[] = array( 'nom' => '&nbsp; Queyras - Parpaillon - Ubaye', 'nbr' => 0, 'key' => 'Queyras|Parpaillon|Ubaye|Brian' );
	$reg[] = array( 'nom' => 'Corse', 'nbr' => 0, 'key' => 'Corse|Corsica' );
	$reg[] = array( 'nom' => 'Jura', 'nbr' => 0, 'key' => 'Jura' );
	$reg[] = array( 'nom' => 'Massif Central', 'nbr' => 0, 'key' => 'Massif Central|Cantal|Puy-de-|Ard|Haute-Loire|Lozère|Aveyron|Gard' );
	$reg[] = array( 'nom' => 'Pyrénées', 'nbr' => 0, 'key' => 'Pyr|Pirine|Aigüetortes|Andorr|Ariège|Aure|Basque|Béarn|Bearn|Bigorr|Cadi|Canigou|Cantabri|Capcir|Cardós|Catal|Cerdagne|Conflent|Corbières|Couserans|Encantats|Euskadi|Gavarnie|Garrotxa|Gredos|Luchon|Maladeta|Mont.Perdu|Monte.Perdido|Navarr|N.ouvielle|de.Europa|Posets|Puigmal|Vasco' );
	$reg[] = array( 'nom' => 'Vosges', 'nbr' => 0, 'key' => 'Vosges' );
	$reg[] = array( 'nom' => '+ Suisse', 'nbr' => 0, 'key' => 'Bern|Vaudois|Urner|Uranaises|Glarner|Appenzell|ndner|Oberland|Graub|Valdese|Waadt|Freib|Glaronaises|Grisons|Grigioni|Zentralschweiz|suisse' );
	$reg[] = array( 'nom' => '&nbsp; Alpes Bernoises/Fribourgeoises', 'nbr' => 0, 'key' => 'Bern|Fribourg|Freib' );
	$reg[] = array( 'nom' => '&nbsp; Alpes Vaudoises', 'nbr' => 0, 'key' => 'Vaudois|Valdese|Waadt' );
	$reg[] = array( 'nom' => '&nbsp; Apenzeller', 'nbr' => 0, 'key' => 'Appenzell' );
	$reg[] = array( 'nom' => '&nbsp; Urner-Glarner Alpen', 'nbr' => 0, 'key' => 'Urner|Glarner|Glaronaises|Uranaises' );
	$reg[] = array( 'nom' => '&nbsp; Graubünden', 'nbr' => 0, 'key' => 'Graub|Grisons|Grigioni|ndner|Oberland' );
	$reg[] = array( 'nom' => 'Valais', 'nbr' => 0, 'key' => 'Valais|Pennin' );
	$reg[] = array( 'nom' => 'Alpes Lépontines', 'nbr' => 0, 'key' => 'Lépontine|Lepontine|Tessin' );
	$reg[] = array( 'nom' => '+ Italie', 'nbr' => 0, 'key' => 'Italie|Paradis|Tici|Orobie|Adamello|Dolomiti|Giulie|Ortles|Cozi|Disgrazia|Bergama|Ligur' );
	$reg[] = array( 'nom' => '&nbsp; Adamello', 'nbr' => 0, 'key' => 'Adamello' );
	$reg[] = array( 'nom' => '&nbsp; Cozie', 'nbr' => 0, 'key' => 'Cozi' );
	$reg[] = array( 'nom' => '&nbsp; Dolomites', 'nbr' => 0, 'key' => 'Dolomit' );
	$reg[] = array( 'nom' => '&nbsp; Engadin - Disgrazia', 'nbr' => 0, 'key' => 'Disgrazia' );
	$reg[] = array( 'nom' => '&nbsp; Giulie', 'nbr' => 0, 'key' => 'Giulie' );
	$reg[] = array( 'nom' => '&nbsp; Gran Paradiso', 'nbr' => 0, 'key' => 'Paradis' );
	$reg[] = array( 'nom' => '&nbsp; Alpi Liguri', 'nbr' => 0, 'key' => 'Ligur' );
	$reg[] = array( 'nom' => '&nbsp; Alpi e Prealpi Bergamasche', 'nbr' => 0, 'key' => 'Orobie|Bergama' );
	$reg[] = array( 'nom' => '&nbsp; Ortles', 'nbr' => 0, 'key' => 'Ortles' );
	$reg[] = array( 'nom' => '&nbsp; Tici', 'nbr' => 0, 'key' => 'Tici' );
	
	$r = count($reg);
	$r2 = 0;
	$n = count($sorties);
	$reg2 = array();

	// un premier scan pour obtenir les régions.
	for ($i=0;$i<$n;$i++) {
		$reg_name = $sorties[$i]['reg'];
		$j = 0; $found = FALSE;
		while($j<$r) {
			if (eregi($reg[$j]['key'],$reg_name)) {
				$reg[$j]['nbr'] ++;
				$found = TRUE;
			}
			$j++;
		}
		if (!$found) {
			for($j=0; $j<$r2; $j++)	{
				if (isset($reg2[$j]) && $reg2[$j]['key'] == $reg_name) {
					$reg2[$j]['nbr'] ++;
					$found = TRUE;
					break;
				}
			}
			if (!$found) $reg2[$r2] = array( 'nom' => $reg_name, 'nbr' => 1, 'key' => $reg_name);
			$r2++;
		}
	}

	// tri
	if (count($reg2) != 0) {
		sort($reg2);
		return array_merge( $reg, $reg2 );
	}
	else
		return $reg;
}

function load_cache($base,&$sorties)
{
	global $dlim;
  global $SETTINGS;

	$txt = $SETTINGS['odir'] . "/$base.txt";

	if ($fd = @fopen($txt,'r')) {
	while( !feof($fd) )
		{
			list($site, $id) = fscanf($fd, "%s %s\n");
			list($date, $cot) = fscanf($fd, "%s %s\n");
			$cot = str_replace('_',' ',$cot);
			$nom = ucfirst(html_entity_decode(trim(fgets($fd,400)),ENT_QUOTES));
			$lien = trim(fgets($fd,400));
			$reg = html_entity_decode(trim(fgets($fd,400)),ENT_QUOTES);
			$part = trim(fgets($fd,400));
			
			if ($date >= $dlim)
			{
				$sorties[] = array( 'date' => $date, 'nom' => $nom, 'reg' => $reg, 'site' => $site,
					'part' => $part, 'lien' => $lien, 'cot' => $cot, 'id' => $id );
			}
		}
		fclose($fd);
	}
}

function loadclean_cache($base,&$tmp)
{
	global $dlim;
   global $SETTINGS;

	$old = 0;
	$istart = count($tmp);	// on sauve l'index de depart...
	$txt = $SETTINGS['odir'] . "/$base.txt";
	$ftmp = "$txt.tmp";
	$list_id[] = '0';		// pour supprimer les eventuels doublons...

	// efface le fichier tmp si ca fait trop longtemps...
	if ( (file_exists($ftmp)) && (time() > (@filemtime($ftmp) + 300))) @unlink($ftmp);	

	if ($fd = @fopen($txt,'r')) {
		while( !feof($fd) ) {
			list($site, $id) = fscanf($fd, "%s %s\n");
			list($date, $cot) = fscanf($fd, "%s %s\n");
			$nom = html_entity_decode(trim(fgets($fd,400)),ENT_QUOTES);
			$lien = trim(fgets($fd,400));
			$reg = html_entity_decode(trim(fgets($fd,400)),ENT_QUOTES);
			$part = trim(fgets($fd,400));
			
			if (($site != $base)&&(!feof($fd))) {	// erreur dans le fichier => reset requis !
				fclose($fd);
				@unlink($SETTINGS['odir'] . "/$base.last");
				return;
			}
	
			$res = array_search($id,$list_id);
			if (($date >= $dlim)&&($res === FALSE)) {
				$list_id[] = $id;
				$tmp[] = array( 'date' => $date, 'nom' => $nom, 'reg' => $reg, 'site' => $site,
					'part' => $part, 'lien' => $lien, 'cot' => $cot, 'id' => $id );
			}
			else $old++;
		}
		fclose($fd);

		$n = count($tmp);
		if ( ($old*5) > ($n-$istart) )	// cleanup requis si 1/6 des sorties est p?im?.
		{
			if ( $fd = @fopen($ftmp,'x+') )	// cleanup en cours ?
			{
				for ($i=$istart; $i<$n; $i++)	// on commence ?l'index de d?art.
				{
					$site = $tmp[$i]['site'];	$id = $tmp[$i]['id'];
					$date = $tmp[$i]['date'];	$cot = $tmp[$i]['cot'];
					$nom = $tmp[$i]['nom'];
					$lien = $tmp[$i]['lien'];
					$reg = $tmp[$i]['reg'];
					$part = $tmp[$i]['part'];
					fwrite($fd,"$site $id\n$date $cot\n$nom\n$lien\n$reg\n$part\n");
				}
				fclose($fd);
				unlink($txt);
				rename($ftmp, $txt);
			}
		}
	}
}

function cleanup_cache($base)
{
	global $dlim;
   global $SETTINGS;

	$txt = $SETTINGS['odir'] . "/$base.txt";
	load_cache($txt,$tmp);
	$n = count($tmp);
	$ftmp = "$txt.tmp";
	// la base n'est pas déjç en train d'etre nettoyée ???	
	if ( $fd = @fopen($ftmp,'x+') ) {
		for ($i=0; $i<$n; $i++)	{
			$site = $tmp[$i]['site'];	$id = $tmp[$i]['id'];
			$date = $tmp[$i]['date'];	$cot = $tmp[$i]['cot'];
			$nom = $tmp[$i]['nom'];
			$lien = $tmp[$i]['lien'];
			$reg = $tmp[$i]['reg'];
			$part = $tmp[$i]['part'];
			fwrite($fd,"$site $id\n$date $cot\n$nom\n$lien\n$reg\n$part\n");
		}
		fclose($fd);

		unlink($txt);
		rename($ftmp, $txt);
	}
	return $fd;
}

function load_All( &$sorties ) {
	load_cache('gulliver',$sorties);
	loadclean_cache('volo',$sorties);
	loadclean_cache('skitour',$sorties);
	loadclean_cache('c2c',$sorties);
	loadclean_cache('bivouak',$sorties);
	loadclean_cache('gipfelbuch',$sorties);

}

/// skitour.fr
function update_Skitour($base = 'skitour')
{
  	global $SETTINGS;
	global $canDownload;
	
	$web  = $SETTINGS['odir'] . "/$base.web";
	$txt  = $SETTINGS['odir'] . "/$base.txt";
	$last = $SETTINGS['odir'] . "/$base.last";
	$expire = 40*60;		// en secondes.
	$ftmp = "$txt.tmp";

	if (!file_exists($last)) {
		reset_Skitour();
		return TRUE;
	}
	if ((time() > (@filemtime($web) + $expire)) && $canDownload ) {
		download_Skitour();
		$canDownload = false;
	}

	// mise a jour du fichier $txt ...
	if (@filemtime($web) > @filemtime($last))	// ... si la date est atteinte ...
	{
	  if ( $fd = @fopen($ftmp,'x') )	// update en cours ?
	  {
		fclose($fd);
		$textall = @file_get_contents($web);
		if ($textall !== FALSE) // le site est HS ? on utilise le cache !
		{
			$last_id = (int) trim(@file_get_contents($last));
			$new_id = parse_Skitour($textall,$last_id);
			if ($new_id > $last_id)		// si on a du nouveau ...
			{
				$fd=@fopen($last,'w');	@fwrite($fd,$new_id);	@fclose($fd);
				$fd=@fopen($txt,'a');
				@flock($fd,LOCK_EX);
				@fwrite($fd,$textall);
				@fclose($fd);
			}
			else touch($last);
		}
		else touch($last);
		unlink($ftmp);
	  } elseif (time() > (@filemtime($ftmp) + 3600)) // efface le fichier tmp si ca fait trop longtemps...
		@unlink($ftmp);
	}
	return TRUE;
}

/// www.Gipfelbuch.ch
function update_Gipfelbuch($base = 'gipfelbuch')
{
  global $SETTINGS;
	global $canDownload;

	$web  = $SETTINGS['odir'] . "/$base.web";
	$txt  = $SETTINGS['odir'] . "/$base.txt";
	$last = $SETTINGS['odir'] . "/$base.last";
	$expire = 40*60;		// en secondes.
	$ftmp = "$txt.tmp";

	if (!file_exists($last)) {
		reset_gipfelbuch();
		return TRUE;
	}
	
	if ((time() > (@filemtime($web) + $expire)) && $canDownload)  {
		download_Gipfelbuch();
		$canDownload = false;
	}

	// mise a jour du fichier $txt ...
	if (@filemtime($web) > @filemtime($last))	// ... si la date est atteinte ...
	{
	  if ( $fd = @fopen($ftmp,'x') )	// update en cours ?
	  {
		fclose($fd);
		$textall = @file_get_contents($web);
		if ($textall !== FALSE) // le site est HS ? on utilise le cache !
		{
			$last_id = (int) trim(@file_get_contents($last));
			$new_id = parse_Gipfelbuch($textall,$last_id);
			if ($new_id > $last_id)		// si on a du nouveau ...
			{
				$fd=@fopen($last,'w');	@fwrite($fd,$new_id);	@fclose($fd);
				$fd=@fopen($txt,'a');
				@flock($fd,LOCK_EX);
				@fwrite($fd,$textall);
				@fclose($fd);
			}
			else touch($last);
		}
		else touch($last);
		unlink($ftmp);
	  } elseif (time() > (@filemtime($ftmp) + 3600)) // efface le fichier tmp si ca fait trop longtemps...
		@unlink($ftmp);
	}
	return TRUE;
}
/// www.volopress.net
function update_Volopress($base = 'volo')
{
  global $SETTINGS;
	global $canDownload;

	$web  = $SETTINGS['odir'] . "/$base.web";
	$txt  = $SETTINGS['odir'] . "/$base.txt";
	$last = $SETTINGS['odir'] . "/$base.last";
	$expire = 40*60;		// en secondes.
	$ftmp = "$txt.tmp";

	if (!file_exists($last)) @unlink($txt);
	
	if ((time() > (@filemtime($web) + $expire)) && $canDownload)  {
		download_Volopress();
		$canDownload = false;
	}	

	// mise a jour du fichier $txt ...
	if (@filemtime($web) > @filemtime($last))	// ... si la date est atteinte ...
	{
	  if ( $fd = @fopen($ftmp,'x') )	// update en cours ?
	  {
		fclose($fd);
		$textall = @file_get_contents($web);
		if ($textall !== FALSE)
		{
			$last_id = trim(@file_get_contents($last));
			$new_id = parse_Volopress($textall,$last_id);
			$last_id = trim(@file_get_contents($last));		// relis le last_id, parse_xxx a pris du temps !
			if (strnatcmp($new_id,$last_id) > 0)
			{
				$fd = @fopen($last,'w');	@fwrite($fd,$new_id);	@fclose($fd);
				$fd=@fopen($txt,'a');
				@flock($fd,LOCK_EX);
				@fwrite($fd,$textall);
				@fclose($fd);
			}
			else touch($last);
		}
		else touch($last);
		unlink($ftmp);
	  } elseif (time() > (@filemtime($ftmp) + 3600)) // efface le fichier tmp si ca fait trop longtemps...
		@unlink($ftmp);
	}
	return TRUE;
}

/// www.camptocamp.org
function update_Skirando($base = 'c2c')
{
  global $SETTINGS;
	global $canDownload;

	$web  = $SETTINGS['odir'] . "/$base.web";
	$txt  = $SETTINGS['odir'] . "/$base.txt";
	$last = $SETTINGS['odir'] . "/$base.last";
	$expire = 40*60;		// en secondes.
	$ftmp = "$txt.tmp";

	// reset du fichier $txt si pas de precedent-reset manuel
	if (!file_exists($last)) {
		reset_Skirando();
		return TRUE;
	}
	// reset du fichier $txt si plus d'une heure
	if ((time() > (@filemtime($web) + $expire)) && $canDownload)  {
		download_Skirando();
		$canDownload = false;
	}
	// mise a jour du fichier $txt si le cron est passe
	if (@filemtime($web) > @filemtime($last))
	{
	  if ( $fd = @fopen($ftmp,'x') )	// update en cours ?
	  {
		fclose($fd);
		$textall = @file_get_contents($web);
		if ($textall !== FALSE)
		{
			$last_id = (int) trim(@file_get_contents($last));
			$new_id = parse_Skirando($textall,$last_id);
			if ($new_id > $last_id)
			{
				$fd=@fopen($txt,'a');
				@flock($fd,LOCK_EX);
				@fwrite($fd,$textall);
				@fclose($fd);
				$fd = @fopen($last,'w');	@fwrite($fd,$new_id);	@fclose($fd);
			}
			else touch($last);
		}
		else touch($last);
		unlink($ftmp);
	  } elseif (time() > (@filemtime($ftmp) + 3600)) // efface le fichier tmp si ca fait trop longtemps...
		@unlink($ftmp);
	}
	return TRUE;
}


/// www.bivouak.net
function update_Bivouak($base = 'bivouak')
{
  global $SETTINGS;
	global $canDownload;
	$web  = $SETTINGS['odir'] . "/$base.web";
	$txt  = $SETTINGS['odir'] . "/$base.txt";
	$last = $SETTINGS['odir'] . "/$base.last";
	$expire = 40*60;		// en secondes.
	
	if (!file_exists($last)) @unlink($txt);
	
	if ((time() > (@filemtime($web) + $expire)) && $canDownload)  {
		download_Bivouak();
		$canDownload = false;
		echo "<p style='font-style:italic;font-size:0.7em'>";
		echo "Données mises à jour le ".date('D j F Y à G:i:s',time())."</p>";	
	} else {
		echo "<p style='font-style:italic;font-size:0.7em'>";
		echo "Données mises à jour le ".date('D j F Y à G:i:s',@filemtime($web))."</p>";	
	}
	$textall = @file_get_contents($web);
	if ($textall !== FALSE) // le site est HS, on utilise le cache.
	{
		$last_id = file_get_contents($last);
		if ($last_id == FALSE) {$last_id=0;}
		$new_id = parse_Bivouak($textall,$last_id);
		if ($new_id > $last_id)
		{
			$fd = @fopen($last,'w');	@fwrite($fd,$new_id);	@fclose($fd);
		}
		else touch($last);
	}
	else touch($last);
	return TRUE;
}

///////// RSS FUNCTIONS ////////////



function get_mois($mois)
{
	switch(substr($mois,0,1)) {
		case 'j' :	if (substr($mois,0,2) == 'ja') { $m = '01'; }
					elseif (substr($mois,0,4) == 'juin') { $m = '06'; }
					else { $m = '07'; }
					break;
		case 'm' :	if (substr($mois,0,3) == 'mai') { $m = '05'; }
					else { $m = '03'; }
					break;
		case 'a' :	if (substr($mois,0,2) == 'av') { $m = '04'; }
					else { $m = '08'; }
					break;
		case 'f' :	$m = '02'; break;
		case 's' :	$m = '09'; break;
		case 'o' :	$m = '10'; break;
		case 'n' :	$m = '11'; break;
		case 'd' :	$m = '12'; break;
		default :	$m = 0;
	}
	return $m;
}

function update_Gulliver($base = 'gulliver')
{
  global $SETTINGS;
	global $canDownload;

	$web  = $SETTINGS['odir'] . "/$base.sa.web";
	$web2 = $SETTINGS['odir'] . "/$base.sr.web";
	$txt  = $SETTINGS['odir'] . "/$base.txt";
	$expire = 40*60;	// en secondes.
	$buffer = '';
	$ftmp = "$txt.tmp";
	$ok = FALSE;
	
	if ((time() > (@filemtime($web) + $expire)) && $canDownload)  {
		download_Gulliver();
		$canDownload = false;
	}	
	
	if ( @filemtime($web) > @filemtime($txt)) {
	    if  ( $fd = @fopen($ftmp,'x+') )
	    {
		$textall = @file_get_contents($web2);
		if ($textall !== FALSE)
		{
			parse_Gulliver($textall);
			fwrite($fd,$textall);
			$ok = TRUE;
		}
		$textall = @file_get_contents($web);
		if ($textall !== FALSE)
		{
			parse_Gulliver($textall);
			fwrite($fd,$textall);
			$ok = TRUE;
		}
		
		if ($ok === TRUE)
		{
			fclose($fd);
			@unlink($txt);
			rename($ftmp,$txt);
		}
		else
		{
			touch($txt);
			unlink($ftmp);
		}
	    } elseif (time() > (@filemtime($ftmp) + 3600)) // efface le fichier tmp si ca fait trop longtemps...
		@unlink($ftmp);
	}
	return TRUE;
}


////////// PARSE FUNCTIONS ///////////////


//////////////////////////
// $textall = @file_get_contents('http://www.gulliver.it/scialpinismo/');
// renvoie dans $textall un buffer pret a etre ecrit dans le fichier cache.
function download_Gulliver( $base = 'gulliver' ) {
   global $SETTINGS;
	$web  = $SETTINGS['odir'] . "/$base.sa.web";
	$web2 = $SETTINGS['odir'] . "/$base.sr.web";
	$textall = file_get_contents("https://www.gulliver.it/sci-alpinismo/");
	$fd=@fopen($web,'w');
	@fwrite($fd,$textall);
	@fclose($fd);
	$textall = file_get_contents("https://www.gulliver.it/sci-ripido/");
	$fd=@fopen($web2,'w');
	@fwrite($fd,$textall);
	@fclose($fd);
}
function parse_Gulliver(&$textall)
{
	// on garde que la partie int?essante.
	$p1 = strpos($textall,'<div class="col results');
	$p2 = strpos($textall,'<div class="bg-gray-200"',$p1);
	$textall = substr($textall,$p1,$p2-$p1);
	$entries = explode('<a',$textall);
	$n = count($entries);
	$textall = '';

	for ($i = 1;$i<$n; $i++) {
		$items = explode('<span',$entries[$i]);

		$date = trim(strip_tags('<span'.$items[1]));
		preg_match('/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})/', $date, $regs);
		$date = sprintf("%04d-%02d-%02d", $regs[3], $regs[2], $regs[1]);		
		if ($regs[3]!="")	{
		  	$reg = 'Italie ' . trim(strip_tags('<span'.$items[2]));
	    	
	 		$cot = trim(strip_tags($items[4]));
			if (strlen($cot)>4) {
				ereg('([0-9]{1}.[0-9]{1})',$cot,$regs);
				$cot=$regs[1];
			}
			unset($regs);
	
			preg_match('/href="([^"]*)"/i', $items[0], $regs);
			$id = $regs[1];
			unset($regs);
	
			$noms = explode('h3',$entries[$i]);
			$nom = trim(strip_tags('<h3 '.$noms[1].'</h3>'));
			$textall .= "gulliver $i\n$date $cot\n$nom\n$id\n$reg\n\n";
		}
	}
}


//////////////////////////
// $textall : contenu de "https://api.camptocamp.org/outings?pl=fr&act=skitouring"
// $last_id : ne garde que les sorties dont l'id > $last_id
// renvoie dans $textall un buffer pret a etre ecrit dans le fichier cache.
//    et en return value : $new_id (l'id le plus recent).
function parse_Skirando(&$textall, $last_id)
{
    global $dlim;

    $obj = json_decode($textall, true);
    $outings = $obj['documents'];
    $textall = '';
    $new_id = $last_id;

    foreach ($outings as $outing) {
        $id = $outing['document_id'];
        $date = $outing['date_start'];
        $name = $outing['locales'][0]['title'];
        $link = 'https://www.camptocamp.org/outings/' . $id;
        $area = c2c_area($outing);
		$cotation = "";
        $cotation = $outing['ski_rating'];
			if ($cotation == "") {$cotation = "nc";}
		$author = $outing['author']['name'];
        if ($date < $dlim) break; // not older than one month
        $textall .= "c2c $id\n$date $cotation\n$name\n$link\n$area\n$author\n";
        if ($id > $new_id) $new_id = $id;
    }

    return $new_id;
}

function c2c_area($outing) {
    foreach ($outing["areas"] as $area) {
        $name = $area["locales"][0]["title"];
        if ($area["area_type"] == "range") {
            break;
        }
    }
    return $name;
}


// cherche la cot d'une volo-course si dispo (et ajoute les participants !)
function volo_cot($url,&$parts)
{
	$textall = @file_get_contents($url);
	if ($textall === FALSE)
		return '';
		
	$textall = substr($textall,strpos($textall,'course_cmt'));
	$textall = substr($textall,0,strpos($textall,'</div>'));
	$items = explode('</p>',$textall);

// recupere les participants.
	if ( preg_match_all('/\b[A-Z]{3}\b/',$items[3],$regs) > 0)
	{
		$parts .= ' + ' . implode(' ',$regs[0]);
	}

// recupere la (ou les) cot.
	if ( preg_match_all('/\b[1-4][.][1-3]|\b5[.][1-6]/',$items[1],$regs) > 0 )
	{
		$cotmax = '0.0';
		$cotmin = '9.9';
		foreach($regs[0] as $ccot)
		{
			if ($ccot > $cotmax)
				$cotmax = $ccot;
			if ($ccot < $cotmin)
				$cotmin = $ccot;
		}
		if ($cotmin == $cotmax)
		{
			return $cotmin;
		}
		return "$cotmin-$cotmax";
	}
	return '';
}


//////////////////////////
// $textall = @file_get_contents('http://www.volopress.net/volo/spip.php?rubrique2');
// $last_id : ne garde que les sorties dont l'id > $last_id
// renvoie dans $textall un buffer pret a etre ecrit dans le fichier cache.
//    et en return value : $new_id (l'id le plus recent).
function download_Volopress( $base = 'volo' ) {
   global $SETTINGS;
	$web  = $SETTINGS['odir'] . "/$base.web";
	$textall = file_get_contents("https://www.volopress.net/spip.php?rubrique2");
	$fd=@fopen($web,'w');
	@fwrite($fd,$textall);
	@fclose($fd);
}
function parse_Volopress(&$textall,$last_id)
{
// on garde que la partie intéressante.
	$textall = substr($textall,strpos($textall,'row_first'));
	$textall = substr($textall,0,strpos($textall,'</table>'));
	$volo = explode('</tr>',$textall);
	$textall = '';
	
	$new_id = $last_id;
	for ($i=1; $i<25; $i++)
	{
		$items = explode('</td>',$volo[$i]);
		ereg("sortie([0-9]+)[.]html",$items[1],$regs);
		$id = $regs[1];
		if ($id > $last_id)
		{
			$date = trim(strip_tags($items[0]));
			$lien = "http://www.volopress.net/volo/sortie$id.html";
			
			$pos = strpos($items[1],'</span>');
			$nom = htmlentities(trim(strip_tags(substr($items[1],0,$pos))),ENT_NOQUOTES,'UTF-8');
			$voie = htmlentities(trim(strip_tags(substr($items[1],$pos)),', '),ENT_NOQUOTES,'UTF-8');

			$reg = htmlentities(trim(strip_tags($items[2])),ENT_NOQUOTES,'UTF-8');
			$part = trim(strip_tags($items[4]));
		// recupere la cot (et les participants) :
//			$cot = volo_cot($lien, $part);
			$cot = '';
			$textall .= "volo $id\n$date $cot\n$nom, $voie\n$lien\n$reg\n$part\n";
			if ($id > $new_id) $new_id = $id;
		}
		else break;
	}
	return $new_id;
}


//////////////////////////
// $textall = @file_get_contents('https://skitour.fr/api/sorties?a=2022');
// $last_id : ne garde que les sorties dont l'id > $last_id
// renvoie dans $textall un buffer pret a etre ecrit dans le fichier cache.
//    et en return value : $new_id (l'id le plus recent).
function parse_Skitour(&$textall,$last_id)
{
	global $dlim;
// on garde que la partie int?essante.
	$entries = explode('</tr>',substr($textall,strpos($textall,'<tbody id="tableTopo">')));
	$textall = '';

// extrait l'ID de la derniere sortie.
	ereg('([0-9]+)',$entries[1],$regs);	$new_id = $regs[1];
	if ($new_id > $last_id)		// si on a du nouveau ...
	{
		$n=count($entries)-1;
		for($i=1;$i<$n;$i++)
		{
			$items = explode('</td>',$entries[$i]);
			$lien = substr($items[2],strpos($items[2],'<a href="')+9);
			$lien = substr($lien,0,strpos($lien,'">'));
			ereg('([0-9]+)',$lien,$regs);	$id = $regs[1];
			if ($id > $last_id)
			{
				$nom = trim(strip_tags($items[8]));
				$alt = trim(strip_tags($items[5]));
				$voie = trim(strip_tags($items[2]));
				$reg = trim(strip_tags($items[3]));
				$cot = trim(strip_tags($items[6]));
				$part = trim(strip_tags($items[8]));
				$date = trim(strip_tags($items[1]));
// interprete la date :
				ereg ("([0-9]{2}).([0-9]{2}).([0-9]{2})", $date, $regs);
				$date = "20{$regs[3]}-{$regs[2]}-{$regs[1]}";
// pour etre ecrit plus tard :
				if ($date < $dlim) break;	// pas plus vieux que 1 mois.
				$textall .= "skitour $id\n$date $cot\n$voie\nhttp://skitour.fr$lien\n$reg\n$part\n";
				if ($id > $new_id) $new_id = $id;
			}
		}
	}
	return $new_id;
}
//////////////////////////
// $textall = @file_get_contents('https://www.gipfelbuch.ch/gipfelbuch/verhaeltnisse');
// $last_id : ne garde que les sorties dont l'id > $last_id
// renvoie dans $textall un buffer pret a etre ecrit dans le fichier cache.
//    et en return value : $new_id (l'id le plus recent).

function download_Gipfelbuch( $base = 'gipfelbuch' ) {
   global $SETTINGS;
	$web  = $SETTINGS['odir'] . "/$base.web";
	$textall = file_get_contents("https://www.gipfelbuch.ch/gipfelbuch/verhaeltnisse");
	$fd=@fopen($web,'w');
	@fwrite($fd,$textall);
	@fclose($fd);
}
function parse_Gipfelbuch(&$textall,$last_id)
{
	global $dlim;
// on garde que la partie int?essante.
	$entries = explode('</tr>',substr($textall,strpos($textall,'<table cellpadding="0" cellspacing')));
	$textall = '';
//tableaux de traduction
	$trad_fr = array("Suisse", "Autriche", "Italie", "France", "Allemagne", "Alpes Vaud./Frib./Bern.", "Alpes Glaronaises", "Grisons", "Alpes tessinoises", "Valais", "Alpes Glaronaises/Uranaises", "Jura", "Suisse autres", "Liechtenstein", "Vorarlberg", "Tirol", "Osttirol", "Salzburg", "Kärnten", "Steiermark", "Ober-Österreich", "Autriche autres", "Val d'Aoste", "Piemont", "Alpi Liguri", "Lombardie", "Trentino - Südtirol", "Veneto", "Friuli-Venezia Giulia", "Italie autres", "Haute Savoie", "Savoie", "Isère", "Alpes du Sud", "Pyrénées", "Corse", "France autres", "Allgäu", "Oberbayern West", "Oberbayern Ost", "Schwarzwald", "Allemagne autres", "Autres régions");
	$trad_ch = array("Schweiz", "Österreich", "Italien", "Frankreich", "Deutschland", "CH - Waadt/Freib./Berner Alpen", "CH - Glarus - St. Gallen", "CH - Graubünden", "CH - Tessin", "CH - Walliser Alpen", "CH - Zentralschweiz", "CH - Jura", "CH - Andere Region", "FL - Liechtenstein", "A - Vorarlberg", "A - Tirol", "A - Osttirol", "A - Salzburg", "A - Kärnten", "A - Steiermark", "A - Ober-Österreich", "A - Andere Region", "I - Valle d'Aosta", "I - Piemonte", "I - Liguria", "I - Lombardia", "I - Trentino - Südtirol", "I - Veneto", "I - Friuli-Venezia Giulia", "I - Andere Region", "F - Haute Savoie", "F - Savoie", "F - Isère", "F - Alpes du Sud", "F - Pyrenäen", "F - Korsika", "F - Andere Region", "D - Allgäu", "D - Oberbayern West", "D - Oberbayern Ost", "D - Schwarzwald", "D - Andere Region", "Andere Region");
	$trad_cotfr = array("F", "PD", "AD", "TD", "D",  "ED");
	$trad_cotch = array("L", "WS", "ZS", "SS", "S",  "AS");
// extrait l'ID de la derniere sortie.
	ereg('/([0-9]{6})/',$entries[1],$regs);	$new_id = $regs[1];

	if ($new_id > $last_id)		// si on a du nouveau ...
	{
		$n=count($entries)-2;
		for($i=1;$i<$n;$i++)
		{
			$items = explode('</td>',$entries[$i]);
			$lien = substr($items[0],strpos($items[0],'href="')+6);
			$lien = substr($lien,0,strpos($lien,'">'));
// extrait l'ID de la sortie.
			//$id = strip_tags($items[0]);
			ereg('/([0-9]{6})/',$lien,$regs);	$id = $regs[1];
				$nom = trim(strip_tags($items[0]));
				//$alt = trim(strip_tags($items[2]));
				$voie = trim(strip_tags($items[1]));
				$voie = "$nom, $voie";
				$reg = trim(strip_tags($items[3]));
				$reg2 = str_replace($trad_ch, $trad_fr, $reg);
//récupération et traduction de la cotation et traduction
				$txttoparse = @file_get_contents("https://www.gipfelbuch.ch$lien");
				$cot = substr($txttoparse,strpos($txttoparse,'SAC_Skiskala.pdf">')+18);
				$cot = strstr($cot,'</a>', true);
				$cot = str_replace($trad_cotch, $trad_cotfr, $cot);
				if (strlen ($cot)>2){ $cot='';}
//récupération de l'auteur
				$part = substr($txttoparse,strpos($txttoparse,'<strong>')+8);
				$part = strstr($part,'</strong>', true);
				if (strlen ($part)>20){ $part='';}
				$date = trim(strip_tags($items[8]));
// interprete la date :
				ereg ("([0-9]{2}).([0-9]{2}).([0-9]{4})", $date, $regs);
				$date = "{$regs[3]}-{$regs[2]}-{$regs[1]}";
// pour etre ecrit plus tard :
				if ($date < $dlim) break;	// pas plus vieux que 1 mois.
//Tout ce qui n'est pas ski ne nous intéresse pas
				if (stristr($lien,'Skitour_Snowboardtour')) {
				$textall .= "gipfelbuch $id\n$date $cot\n$voie\nhttps://www.gipfelbuch.ch$lien\n$reg2\n$part\n";
				if ($id > $new_id) $new_id = $id;
				}
		}
	}
	return $new_id;
}

//////////////////////////
// $textall = @file_get_contents('http://www.bivouak.net/index.php?id_sport=1');
// $last_id : ne garde que les sorties dont l'id > $last_id
// renvoie dans $textall un buffer pret a etre ecrit dans le fichier cache.
//    et en return value : $new_id (l'id le plus recent).
function download_Bivouak( $base = 'bivouak' ) {
   global $SETTINGS;
	$web  = $SETTINGS['odir'] . "/$base.web";
	$textall = file_get_contents("https://www.bivouak.net/topos/liste_des_sorties.php?id_sport=1");
	$fd=@fopen($web,'w');
	@fwrite($fd,$textall);
	@fclose($fd);
}
function parse_Bivouak($textall,$last_id)
{
	global $dlim;
	$cur_month = date('m');	$cur_year = date('Y');
// on garde que la partie int?essante.
	$textall = substr($textall,strpos($textall,'Auteur'));
	$textall = substr($textall,0,strpos($textall,'</table>'));
	$entries = explode('</tr>',$textall);
	$textall = '';
// extrait l'ID de la sortie.
	ereg('sortie-([0-9]+)-',$entries[1],$regs);	$new_id = $regs[1];
	if ($new_id > $last_id)
	{
		$n = count($entries)-1;
		for ($i = 1;$i<$n; $i++)
		{
			$items = explode('</td>',$entries[$i]);
			$start = strpos($items[1],'<a href="..') + 11;
			$stop = strpos($items[1],'">',$start);
			$lien = substr($items[1],$start,$stop - $start);
// extrait l'ID de la sortie.
			ereg('sortie-([0-9]+)-',$lien,$regs);		$id = $regs[1];
			//print($id); print('     '); print($last_id); print('\n');
			if ($id > $last_id)
			{
				$date = trim(strip_tags($items[0]));
				$nom = trim(strip_tags($items[1]));
				$reg = trim(strip_tags($items[2]));
				$cot = trim(strip_tags($items[5]));
				$part = trim(strip_tags($items[7]));
// interprete la date :
				ereg ("([0-9]{2}).([0-9]{2})", $date, $regs);
				if (($regs[2]) > $cur_month) {
					$date = ($cur_year-1) . "-{$regs[2]}-{$regs[1]}";
				}	else	{
					$date = "$cur_year-{$regs[2]}-{$regs[1]}";
				}
				if ($date < $dlim) break;	// pas plus vieux que 1 mois.
				$textall .= "bivouak $id\n$date $cot\n$nom\nhttp://www.bivouak.net$lien\n$reg\n$part\n";
				if ($id > $new_id) $new_id = $id;
			}
		}
	}
	$fd = @fopen("./data/bivouak.txt",'a'); @flock($fd,LOCK_EX); @fwrite($fd,$textall); @fclose($fd);
	return $new_id;
}

/////////// RESET FUNCTIONS /////////
function download_Skitour( $base = 'skitour' ) {
   global $SETTINGS;
	$web  = $SETTINGS['odir'] . "/$base.web";
	
	$url = "https://skitour.fr/sorties";
	$options = array(
	  'https'=>array(
		  'method'=>"GET",
		  'header'=>"cle: NL4UeRQausVg4etQD7f21KckjCvVL1Kn"
	 	)
	);
	$context = stream_context_create($options);
	$textall = file_get_contents($url, false, $context);	
	$fd=@fopen($web,'w');
	@fwrite($fd,$textall);
	@fclose($fd);
}
function reset_Skitour($nread = 500, $base = 'skitour' )
{
  global $SETTINGS;

	$txt  = $SETTINGS['odir'] . "/$base.txt";
	$last = $SETTINGS['odir'] . "/$base.last";
	$ftmp = "$txt.tmp";

	echo "<p>Indexing skitour.fr ...";
	if ( $fd = @fopen($ftmp,'x') )		// pas d'autre tentative ?
	{
		fclose($fd);
		$url = "https://skitour.fr/sorties";
		$options = array(
		  'https'=>array(
			  'method'=>"GET",
			  'header'=>"cle: NL4UeRQausVg4etQD7f21KckjCvVL1Kn"
		 	)
		);
		$context = stream_context_create($options);
		$textall = file_get_contents($url, false, $context);	
		if ($textall !== FALSE)
		{
			$new_id = parse_Skitour($textall,0);
			$fd=@fopen($ftmp,'w');
			@fwrite($fd,$textall);
			@fclose($fd);
			$fd=@fopen($last,'w');	@fwrite($fd,$new_id);	@fclose($fd);
			@unlink($txt);
			rename($ftmp,$txt);
			echo ' done.</p>';
		}
		else
		{
			echo ' failed !</p>';
			unlink($ftmp);
		}
//		cleanup_cache($txt);
	}
}
function download_Skirando( $base = 'c2c' ) {
   global $SETTINGS;
	$web  = $SETTINGS['odir'] . "/$base.web";
	$textall = file_get_contents("https://api.camptocamp.org/outings?act=skitouring&pl=fr&limit=500");
	$fd=@fopen($web,'w');
	@fwrite($fd,$textall);
	@fclose($fd);
}
function reset_Skirando($nread = 120, $base = 'c2c' )
{
  global $SETTINGS;

	$txt  = $SETTINGS['odir'] . "/$base.txt";
	$last = $SETTINGS['odir'] . "/$base.last";
	$ftmp = "$txt.tmp";

	if ( $fd = @fopen($ftmp,'x') )		// pas d'autre tentative ?
	{
		echo "<p>Indexing camptocamp.org ...";
		fclose($fd);
		//$textall = file_get_contents($SETTINGS['odir'] . "/$base.web");
		$textall = file_get_contents("https://api.camptocamp.org/outings?act=skitouring&pl=fr&limit=500");
		$new_id = 0;
		$new_id = parse_Skirando($textall,$new_id);
		$fd=@fopen($ftmp,'a');
		@fwrite($fd,$textall);
		@fclose($fd);
		$fd = @fopen($last,'w');	@fwrite($fd,$new_id);	@fclose($fd);
		@unlink($txt);
		rename($ftmp,$txt);
		echo ' done.</p>';
	}
}

function reset_Gipfelbuch($base = 'gipfelbuch' )
{
  global $SETTINGS;

	$txt  = $SETTINGS['odir'] . "/$base.txt";
	$last = $SETTINGS['odir'] . "/$base.last";
	$ftmp = "$txt.tmp";

	if ( $fd = @fopen($ftmp,'x') )		// pas d'autre tentative ?
	{
		echo "<p>Indexing gipfelbuch ...";
		fclose($fd);
		$textall = file_get_contents($SETTINGS['odir'] . "/$base.web");
		$new_id = 0;
		$new_id = parse_Gipfelbuch($textall,$new_id);
		//print ($new_id);
		$fd=@fopen($ftmp,'a');
		@fwrite($fd,$textall);
		@fclose($fd);
		$fd = @fopen($last,'w');	@fwrite($fd,$new_id);	@fclose($fd);
		@unlink($txt);
		rename($ftmp,$txt);
		echo ' done.</p>';
	}
}

// FIND FUNCTIONS ...
function kick_zeurch($keys, $str_data)
{
	foreach($keys as $key)
	{
		if ( !eregi($key,$str_data) )
			return FALSE;
	}
	return TRUE;
}

function sans_accent($chaine)
{
   $accent  ="ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ";
   $noaccent="aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby";
   return strtr(trim($chaine),$accent,$noaccent);
} 

?>
