<?php
/*
    Copyright (C) Nathanael Schaeffer

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

	$myregs = $_COOKIE['myregs'];
	$raide = $_COOKIE['raide'];
	extract($_GET);		// php 5

	$time = time();
	unset( $last_sktr, $last_volo, $last_bivk, $last_skrd, $last_gipf );
// les dernieres sorties sont stockees dans 'last' pour 2 jours.
	$last_sktr = trim(@file_get_contents($SETTINGS['odir'] . '/skitour.last'));
	$last_volo = trim(@file_get_contents($SETTINGS['odir'] . '/volo.last'));
	$last_bivk = trim(@file_get_contents($SETTINGS['odir'] . '/bivouak.last'));
	$last_skrd = trim(@file_get_contents($SETTINGS['odir'] . '/c2c.last'));
	$last_gipf = trim(@file_get_contents($SETTINGS['odir'] . '/gipfelbuch.last'));
// on efface le cookie 'last' pour le mettre a jour.
	setcookie('last','',$time-1000);
	setcookie('last',"sktr=$last_sktr&volo=$last_volo&bivk=$last_bivk&skrd=$last_skrd&gipf=$last_gipf",$time+48*3600);
// les dernieres sorties vues sont stockees dans 'current' pour 5 min.
	if (isset($_COOKIE['current']))
	{
		$items = explode('&',$_COOKIE['current']);
		$last_sktr = substr($items[0],strpos($items[0],'=')+1);
		$last_volo = substr($items[1],strpos($items[1],'=')+1);
		$last_bivk = substr($items[2],strpos($items[2],'=')+1);
		$last_skrd = substr($items[3],strpos($items[3],'=')+1);
		$last_gipf = substr($items[4],strpos($items[4],'=')+1);
	}
	elseif (isset($_COOKIE['last']))
	{
		$items = explode('&',$_COOKIE['last']);
		$last_sktr = substr($items[0],strpos($items[0],'=')+1);
		$last_volo = substr($items[1],strpos($items[1],'=')+1);
		$last_bivk = substr($items[2],strpos($items[2],'=')+1);
		$last_skrd = substr($items[3],strpos($items[3],'=')+1);
		$last_gipf = substr($items[4],strpos($items[4],'=')+1);
		setcookie('current',$_COOKIE['last'],$time+15*60);
	}

// raide : cookie must be overridden by form input.
	if (isset($_COOKIE['raide']) && (!empty($_GET)) && (!isset($_GET['raide'])))
		unset($raide);
// only my region : cookie must be overridden by form input.
	if (isset($_COOKIE['myregs']) && (!empty($_GET)) && (!isset($_GET['myregs'])))
		unset($myregs);
?>
<html>
<head>
<title>Meta-Skirando</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1">
<meta name="description" content="Le moteur de recherche du Ski de Rando. Les conditions de neige pour le ski de randonnée en France et ailleurs !" />
<meta name="keywords" content="ski de rando, ski alpinisme, ski extrême, pente raide, alpes, pyrénées, neige, météo, skitour, skirando, volopress, bivouak" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="Les dernieres sorties de ski de rando" href="ski_rss.php" />
<link rel="search" type="application/opensearchdescription+xml" title="rechercher avec metaskirando" href="metaskirando.xml">
<link rel="shortcut icon" href="favicon.ico" />
<link rel="canonical" href="http://metaskirando.camptocamp.org" />
</head>
<body>

<?php include 'menu.inc'; ?>

<h1 style="text-align: center;">Le moteur de recherche du Ski de Rando</h1>
<h2 style="text-align: center;">Passer plus de temps &agrave; surfer sur la neige que sur le net.</span></h2>

<?php
	require "sites.inc.php";
	
	update_Bivouak();
	update_Skitour();
	update_Gulliver();
	update_Skirando();
	update_Gipfelbuch();
	update_Volopress();
	
	load_All($sorties);
	
	$regs = make_region_list($sorties);
	$nsorties = count($sorties);

?>

<div>
	<FORM method='get' name='search' class="srch">
		<div class="srchb">
			<p><SELECT NAME="zon" onchange="document.forms['search'].submit()">
					<option value=''>----- Tous les massifs ----</option>
					<?php
							$zn = $_GET['zon'];
							if( strlen($zn) == 0) $zn="---- Tous les massifs ----";
							if (isset($_COOKIE['region']))
							{
								foreach ( $_COOKIE['region'] as $nom => $key )
									echo "<option value=\"$key\"";
									if($key == $zn) { echo "selected='true'";}
									echo ">* $nom </option>\n";
							}
						
							$r = count($regs);
							for ($i=0;$i<$r;$i++)
							{
								$nom = $regs[$i]['nom'];
								$nbr = $regs[$i]['nbr'];
								$key = $regs[$i]['key'];
								if ($nbr != 0) {
							  		echo "<option value=\"$key\"";
									if($key == $zn) { echo "selected='true'";}
									echo ">$nom ($nbr) </option>\n";		
								}					
							}
					?>
				</SELECT>
			</p>
		</div>
		<div class="srchb">
			<p><span style="white-space: nowrap;"><input style="vertical-align: middle;" title="Seulement les pentes raides (a partir du niveau 4.1 ou D-)" type=checkbox name="raide" id="raide" <?php if (isset($raide)) echo 'checked'; ?> onchange="document.forms['search'].submit()">
			   <label for="raide">Pente raide</label></span>
				<?php	if (isset($_COOKIE['region'])){	?>
						<span style="white-space: nowrap;"><input style="vertical-align: middle;" title="Seulement les sorties de mes régions." type=checkbox name="myregs" id="myregs" <?php if (isset($myregs)) echo 'checked'; ?> onchange="document.forms['search'].submit()">
					   <label for="myregs">Mes régions </label> <a href="prefs.php">(définir)</a></span>
				<?php }	?>
			</p>
		</div>
		<div class="srchb">
			<p><span style="white-space: nowrap;">
				<INPUT title="Recherche sur tous les champs (massif, itinéraire, auteur, date ...)" TYPE=text SIZE=20 NAME=str>
				<INPUT TYPE=submit NAME="kz" VALUE="Filtrer"></span>
			</p>
		</div>
	</FORM>
</div>

<?php

if (isset($raide))
{
	unset($found);
	$nf = 0;
	$n = count($sorties);
	for($i = 0; $i < $n; $i++)
	{
		if ($sorties[$i]['site'] == 'volo')
		{
			$found[$nf] = $sorties[$i];
			$nf++;
		}
		else
		{
			$cot = substr($sorties[$i]['cot'],0,1);
			switch($cot) {
				case '4' : case '5' : case 'D' : case 'T' : case 'E' :
				$found[$nf] = $sorties[$i];
				$nf++;
			}
		}
	}
	$sorties = $found;
}

if ( isset($myregs) && empty($zon) && isset($_COOKIE['region']) )
{
	unset($found);
	
	$zon = implode('|',$_COOKIE['region']);
	
	$nf = 0;
	$n = count($sorties);
	for($i = 0; $i < $n; $i++)
	{
		if (eregi($zon,$sorties[$i]['reg']))
		{
			$found[$nf] = $sorties[$i];
			$nf++;
		}
	}
	$sorties = $found;
}

$kz = 0;
if (!empty($zon))
{
//	$zon = implode('|',$zonA);

	unset($found);
	$nf = 0;
	for($j=0;$j<$r;$j++)
	{
		if ($zon == $regs[$j]['key'])
		{
			$reg_name = $regs[$j]['nom'];
			break;
		}
	}
	$label = str_replace("&nbsp; ", "", $reg_name);
	$label = str_replace("+ ", "", $label);
	echo "\n<h1>$label</h1>\n";
	
	$n = count($sorties);
	for($i = 0; $i < $n; $i++)
	{
		if (eregi($zon,$sorties[$i]['reg']))
		{
			$found[$nf] = $sorties[$i];
			$nf++;
		}
	}
	$sorties = $found;
	$kz = 1;
}

if (!empty($_GET['str']))
{
	unset($found);
	$nf = 0;
	$n = count($sorties);
	$req_str = sans_accent(eregi_replace('([1-5])[.]([1-6])',"\\1[.]\\2",$_GET['str']));
	$keys = explode(' ',$req_str);
	for($i = 0; $i < $n; $i++)
	{
		if (kick_zeurch($keys,sans_accent(implode(' ',$sorties[$i]))))
		{
			$req_str = implode('|',$keys);
			$found[$nf]['date'] = eregi_replace($req_str,"<span class='yt'>\\0</span>",$sorties[$i]['date']);
			$found[$nf]['nom'] = eregi_replace($req_str,"<span class='yt'>\\0</span>",$sorties[$i]['nom']);
			$found[$nf]['reg'] = eregi_replace($req_str,"<span class='yt'>\\0</span>",$sorties[$i]['reg']);
			$found[$nf]['part'] = eregi_replace($req_str,"<span class='yt'>\\0</span>",$sorties[$i]['part']);
			$found[$nf]['cot'] = eregi_replace($req_str,"<span class='yt'>\\0</span>",$sorties[$i]['cot']);
			$found[$nf]['site'] = $sorties[$i]['site'];
			$found[$nf]['lien'] = $sorties[$i]['lien'];
			$found[$nf]['id'] = $sorties[$i]['id'];
			$nf++;
		}
	}
	$sorties = $found;
	$kz = 1;
}

if ($kz == 0)
{
	$dmax = date('Y-m-d',time()-240*3600);
	echo "\n<h2>Quoi de neuf depuis 10 jours ?</h2>\n";

	unset($found);
	$nf = 0;
	// affiche les dernieres
	$n = count($sorties);
	for($i = 0; $i < $n; $i++)
	{
		$site = $sorties[$i]['site'];
		$id = $sorties[$i]['id'];
		$date = $sorties[$i]['date'];
		
		if ($date >= $dmax)
		{
			$found[$nf] = $sorties[$i];
			$nf++;
		}

	}

	if ($nf == 0)
	{
		rsort($sorties);
		$found = array_slice($sorties,0,30);
	}
	$sorties = &$found;
}

$n = count($sorties);
?>

<p>Les <b><?php echo $n; ?></b> derni&egrave;res sorties des principaux sites web de ski de randonn&eacute;e.<br>
</p>

<?php

if (isset($found))
{
	rsort($sorties);

	echo "<table class='main'>\n";

	$prevdate = 0;
	for ($i=0;$i<$n;$i++)
	{
		$date = $sorties[$i]['date'];
		$nom = mb_strimwidth($sorties[$i]['nom'],0,80,"...");
		$site = $sorties[$i]['site'];
		$reg = $sorties[$i]['reg'];
		$part = $sorties[$i]['part'];
		$lien = $sorties[$i]['lien'];
		$cot = $sorties[$i]['cot'];
		$id = $sorties[$i]['id'];

		$trtag = '<tr class="new">';
		switch($site) {
			case 'volo' : if ( strnatcmp($id,$last_volo) <= 0 ) { $trtag = '<tr>'; }
				 break;
			case 'bivouak' : if ($id <= $last_bivk) { $trtag = '<tr>'; }
				 break;
			case 'c2c' : if ($id <= $last_skrd) { $trtag = '<tr>'; }
				 break;
			case 'skitour' : if ($id <= $last_sktr) { $trtag = '<tr>'; }
				 break;
			case 'gipfelbuch' : if ($id <= $last_gipf) { $trtag = '<tr>'; }
				 break;
			default: $trtag = '<tr>';
		}

		if ($date <> $prevdate) {
			echo "</table><p><br/><b>$date</b></p><table class='main'>";
			$prevdate = $date;
		} else {
			if ($i >= 1000) break;	// trop de sorties ? on arête là !
			$dtxt = $date;
		}
		
		echo "$trtag<td><a href=\"$lien\">$nom</a></td><td><b>$reg</b></td><td>$cot</td><td><i> $part</i> [$site]</td></tr>\n";
	}

	echo '</table></center>';
}

include 'bottom.inc';

?>

</body></html>
