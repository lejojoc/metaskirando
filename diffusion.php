<?php /*
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
*/ ?>
<html>
<head>
<title>Meta-Skirando : diffusion</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1">
<meta name="description" content="Le moteur de recherche du Ski de Rando. Les conditions de neige pour le ski de randonnée en France et ailleurs !" />
<meta name="keywords" content="ski de rando, ski alpinisme, ski extrême, pente raide, alpes, pyrénées, neige, météo, skitour, skirando, blms, nimp crew, volopress, ohm chamonix, sngm, bivouak" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="canonical" href="http://metaskirando.camptocamp.org/diffusion.php" />
<?php

extract($_GET);

require "sites.inc.php";

if (isset($_GET['go']))
{
	if (count($site) >= 8) {
		$sites = 'all';
	} elseif (!empty($site)) {
		$sites = implode('+',$site);
	}
	if ((empty($_GET['zon']))&&(!empty($zonA)))
		$zon = implode('|',$zonA);
	$lien = "ski_rss.php?days=$days&nbr=$nbr";
	if (!empty($sites))
		$lien .= "&site=$sites";
	if (!empty($zon))
		$lien .= "&zon=$zon";
	if (!empty($part))
		$lien .= "&aut=$part";
	if (!empty($cotmin))
		$lien .= "&cotmin=$cotmin";
}
else
	$lien = 'ski_rss.php';

echo "<link href=\"$lien\" rel=\"alternate\" type=\"application/rss+xml\" title=\"Les dernieres sorties de ski de rando\" />\n";
echo '</head><body>';
?>

<?php include 'menu.inc'; ?>

<h1>Diffusez Meta-Skirando !</h1>
<p>
Pour cela, plusieurs possibilités pour votre site :
<ul>
	<li>Mettre un lien vers Méta-Skirando (facile !)</li>
	<li>Afficher des sorties via RSS (enrichissez votre site !)</li>
	<li>Mettre en place une <a href="#box">boite de recherche</a> (pratique !)</li>
</ul>
</p>

<h2>Le flux RSS du Ski de
Rando :</h2>
<p>Affichez les sorties de ski de rando qui vous intéressent sur votre site mais aussi sur l'acceuil personalisé de google. ! 
<u>Exemples</u> :
<ul>
<li>Il est possible de récupérer les sorties dont vous êtes l'auteur sur le site où vous contribuez.</li>
<li>Si vous êtes fan de LTA chez volopress et de David Zijp chez skitour, récupérez juste leurs sorties en sélectionnant les sites "volopress" et "skitour" et auteurs "LTA|David Zijp"</li>
<li>Vous avez un site web régional : affichez toutes les dernieres sorties sur cette région</li>
</ul>
</p>

<?php
	load_All($sorties);
	$regs = make_region_list($sorties);
?>

<form method="get">
	<div class="rss">
		<div class="rssb" style="min-width: 8em;">
				<h2>Sites</h2>
				<select size="8" name="site[]" multiple="multiple" style="width: 95%">
					<option value="all">* tous *</option>
					<option value="skrd">camptocamp.org</option>
					<option value="sktr">skitour.fr</option>
					<option value="bivk">bivouak.net</option>
					<option value="volo">volopress.fr</option>
					<option value="gull">Guilliver.it</option>
					<option value="gipf">gipfelbuch.ch</option>
				</select>
		</div>
		<div class="rssb" style="min-width: 8em;">
				<h2>Auteurs</h2>
				<input size="6" name="part" type="text" style="width: 95%"/>
				<p>(<i>expression r&eacute;guli&egrave;re : plusieurs auteurs sont &agrave; s&eacute;parer par</i> | )</p>
		</div>
		<div class="rssb">
				<h2>Difficult&eacute;</h2>
				<label for="cotmin">&agrave; partir de</label>
				<select name="cotmin" id="cotmin">
					<option value="">1.1 (ou F)</option>
					<option value="2">2.1 (ou PD-)</option>
					<option value="3">3.1 (ou AD-)</option>
					<option value="4">4.1 (ou D-)</option>
					<option value="5">5.1 (ou TD-)</option>
				</select>
		</div>
		<div class="rssb">
				<h2>Volume</h2>
				<p><label for="nbr">Afficher au plus </label>
				<select name="nbr" id="nbr">
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select> sorties <label for="days">sur les </label>
				<select name="days">
					<option value="3">3</option>
					<option value="7">7</option>
					<option value="15">15</option>
					<option value="21">21</option>
					<option value="31">31</option>
				</select> derniers jours</p>
		</div>
		<div class="rssb" style="min-width: 15em;">
				<h2>R&eacute;gions</h2>
				<p><label for="zonA">S&eacute;lectionner dans la liste (<i>selection multiple avec  [Ctrl]</i></label> :<br/>		
				<select size="8" name="zonA[]" id="zonA" multiple="multiple" style="width: 95%">
					<?php
						if (isset($_COOKIE['region']))
						{
							foreach ( $_COOKIE['region'] as $nom => $key )
								echo "<option value=\"$key\">* $nom </option>\n";
						}
						$r = count($regs);
						for ($i=0;$i<$r;$i++)
						{
							$nom = $regs[$i]['nom'];
							$key = $regs[$i]['key'];
							echo "<option value=\"$key\">$nom </option>\n";
						}
					?>
				</select></p>
				<p>ou sp&eacute;cifier un filtre :</p>
				<input size="10" name="zon" style="width: 95%">
				<p>(<i>expression r&eacute;guli&egrave;re (séparer les r&eacute;gions par</i> '|') :</p>
			</div>
		</div>
	</div>
	<div style="text-align: center;">
		<input name="go" value="Donne moi l'URL" type="submit">
	</div>
</form>
<?php

if (isset($_GET['go']))
{
	echo "<p><b>Voici le fil RSS correspondant à cette requête :</b></p><p class='code'><a href=\"$lien\">$lien</a><br/></p>";
}
?>
<hr>

<h2  id="box">Les boites de recherche</h2>
<div class="rss">
	<div class="brs" style="min-width: 20em;">
		<p><b>Le 'Kick Zeurch'</b> effectue une recherche sur tous les champs (site, auteur, itin&eacute;raire, r&eacute;gion) sur tout ou une partie d'un mot.</p>
		<form method="get" action="http://www.metaskirando.ovh/index.php">
			<p class="bx"><b><label for="str">Kick Zeurch: </label></b>
			<input title="rechercher une sortie avec MetaSkirando" size="10" name="str" id="str" type="text" style="width: 60%">
			<input name="kz" value=" Go " type="submit"></p>
		</form>
		<p>Le code <i>Kick Zeurch</i> &agrave; copier dans votre page web :</p>
		<p class='code'><br/>&lt;form method="get" action="http://www.metaskirando.ovh/index.php"&gt;<br>&lt;i&gt;Kick Zeurch&lt;/i&gt;<br>&lt;input name="str" type="text"&gt;<br>&lt;input name="kz" value="Go" type="submit"&gt;<br>&lt;/form&gt;</p>	
	</div>
	<div class="brs" style="min-width: 20em;">
		<p><b>La boite R&eacute;gion</b> effectue une recherche par régions. Vous pouvez y mettre les régions qui vous plaisent !</p>
		<form method="get" name="msr-regs" action="http://www.metaskirando.ovh/index.php">
			<p class="bx"><b><label for="msr-regs">Massif: </label></b>
			<select name="zon" onchange="document.forms['msr-regs'].submit()" style="width: 60%">
				<option value=""></option>
				<option value="Aravis|Bornes">Aravis-Bornes</option>
				<option value="Belledonne">Belledonne</option>
				<option value="Beaufort">Beaufortain</option>
				<option value="voluy">D&eacute;voluy</option>
				<option value="Pyr">Pyr&eacute;n&eacute;es</option>
			</select></p>
		</form>
		<p>Le code <i>R&eacute;gions</i> à copier dans votre page web, et à personnaliser avec vos régions :</p>
		<p class='code'><br/>&lt;form method="get" name="msr-regs" action="http://www.metaskirando.ovh/index.php"&gt;
			<br>&lt;b&gt;Massif&lt;/b&gt; :&lt;br&gt;<br>&lt;select name="zon" onchange="document.forms['msr-regs'].submit()"&gt;
			<br> &lt;option value=""&gt;&lt;/option&gt;<br> &lt;option value="Aravis|Bornes"&gt;Aravis-Bornes&lt;/option&gt;
			<br> &lt;option value="Belledonne"&gt;Belledonne&lt;/option&gt;<br> &lt;option value="Beaufort"&gt;Beaufortain&lt;/option&gt;
			<br> &lt;option value="voluy"&gt;D&eacute;voluy&lt;/option&gt;<br> &lt;option value="Pyr"&gt;Pyr&eacute;n&eacute;es&lt;/option&gt;
			<br>&lt;/select&gt;&lt;/form&gt;<br>
		</p>
	</div>
</div>

<?php include 'bottom.inc'; ?>

</body></html>
