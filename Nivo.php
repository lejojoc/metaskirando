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
*/

require_once('./settings.inc.php');

 ?>
<html>
<head>
<title>Meta-Skirando : Nivoses et Météo dans les Alpes.</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="initial-scale=1">
<link href="style.css" rel="stylesheet" type="text/css">
<meta name="description" content="Le moteur de recherche du Ski de Rando. Les nivoses de météo france et les bulletins d'avalanche. Prévisions météo montagne dans les Alpes" />
<meta name="keywords" content="ski de rando, ski alpinisme, ski extrême, pente raide, alpes, pyrénées, neige, météo" />
<link rel="canonical" href="http://metaskirando.camptocamp.org/Nivo.php" />
</head>
<body>

<?php include 'menu.inc'; ?>

<h1>Nivologie et précautions</h1>

<p><b>En ski de rando, on s'expose à des risques.</b> Une bonne expérience de la montagne, de la neige et un équipement spécial (Arva/Pelle/Sonde) sont nécessaires pour évoluer avec un minimum de sécurité.
Consultez les conseils pratiques et le petit traité de nivologie de l'<a target="_meteo" href="http://www.anena.org/">Anena</a>.</p>
<p>Le site camptocamp.org a regroupé beaucoup d'infos sur la neige et les avalanches, en particulier des <a target="_meteo"  href="http://www.camptocamp.org/articles/107439/fr/neige-et-avalanches-les-ressources-du-net">témoignages d'accidents</a>.<br>
N'hésitez pas à sortir avec des organismes spécialisés (comme le CAF), qui vous formeront à être autonomes.<br>
Consulter aussi la base de donnée des avalanches et observations du manteau sur <a target="dataval" href="http://www.data-avalanche.org/now">le site www.data-avalanche.org</a> ou <a target="_ava" href="data/aval/latestaval.php">le Feed des dernières avalanches</a> déclarées </p>

<h1><a name='meteo'></a>Prévisions et Observations</h1>

<ul>
<li>Bulletins d'avalanches <a target="_meteo"  href="http://www.slf.ch">Ailleurs en europe via slf.ch</a></li>
<li>Prévisions météo : <a target="_meteo"  href="http://mto38.free.fr/">Caplain</a>, <a target="_meteo"  href="http://www.meteo-villes.com/">Meteo-villes</a>, <a target="_meteo"  href="https://chamonix-meteo.com/chamonix-mont-blanc/meteo/prevision/matin/previ_meteo_5_jours.php">Chamonix</a>,
	 et <a target="_meteo"  href="https://meteofrance.com/meteo-montagne">Meteo France</a>. 
<li>Analyse multimodel sur <a target="_meteo"  href="https://www.meteoblue.com/fr/meteo/prevision/multimodel">meteoblue</a>.
</li>
</li>
<li>Observations Météo en temps réel : <a target="_meteo"  href="http://www.meteociel.fr/">Météociel</a> et <a target="_meteo"  href="http://www.infoclimat.fr/accueil/">infoclimat</a>.
</li>
<li><a target="_ava" href="data/aval/latestaval.php">Feed des dernières avalanches</a> déclarées dans data-avalanches.org</li>
</ul>
<h1>Risque &amp; Bera Météo France</h1>
	<p><i>Le tableau ci-dessous est un résumé du risque avalanche publié par Meteo France pour chaque massif. Le résumé est collecté par Metaskirando à intervalle régulier. Visitez <a target="_meteo"  href="https://meteofrance.com/meteo-montagne">le site de Meteo France</a> pour des information plus fraîches.</i></p>
	
<?php include './data/aval/avalanches.php'; ?>

<h1><a name='nivo'></a>Stations automatiques</h1>

Les stations automatiques de Météo France sont disséminées dans les montagnes et donnent de précieuses informations sur les chutes de neige, les températures et le vent, en différents endroits :
<ul>
<li><b>Autour de Grenoble</b> : <a href="#Bel">Belledonne</a>, <a href="#Ecr">Ecrins</a>, <a href="#Ver">Vercors</a>, <a href="#Cha">Chartreuse</a></li>
<li><b>Alpes du Nord</b> : <a href="#Aig">Aiguilles Rouges</a>, <a href="#Bau">Bauges</a>, <a href="#Bea">Beaufortain</a>, <a href="#Van">Vanoise</a>, <a href="#Mau">Haute-Maurienne</a>, <a href="#Tar">Haute-Tarentaise</a>, <a href="#Tha">Thabor / Galibier</a></li>
<li><b>Alpes du Sud</b> : <a href="#Ech">Champsaur</a>, <a href="#Que">Queyras</a>, <a href="#Par">Parpaillon</a>, <a href="#Uba">Ubaye</a>, <a href="#Mer">Mercantour</a></li>
<li><b>Pyrénées</b> : <a href="#PyE">Orientales</a>, <a href="#PyA">Ariège</a>, <a href="#PyC">Centrale</a>, <a href="#PyW">Occidentales</a></li>
<li><b>Corse</b> : <a href="#Cor">Cinto-Rotondo</a></li>
</ul>

<?php
// Parsing mf data.
	$links = file($SETTINGS['odir'] . '/nivo_links.web');
  foreach ($links as $line) {
    if (preg_match('/(ZONE_.*):"(.*)"/', $line, $matches)) {
      ${$matches[1]} = $matches[2];
    }
  }

?>
<div class="nivs">
	<div class="nivb">
		<div class="nivt">
			<b>Belledonne</b>, sur le Plat du Pin, au-dessus du Rivier d'Allemont, en montant vers le Pic de la Belle Etoile
			(<a name='Bel' href="<?php echo $ZONE_AIGLE_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_AIGLE ?>'><img src='<?php echo $ZONE_AIGLE ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Ecrins</b>, sur la morraine du glacier de Bonnepierre, non-loin du dôme des Ecrins
			(<a name='Ecr' href="<?php echo $ZONE_ECRIN_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_ECRIN ?>'><img src='<?php echo $ZONE_ECRIN ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Meije</b>, en rive droite du glacier du Vallon (La Grave)
			(<a name='Meij' href="<?php echo $ZONE_MEIJE_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_MEIJE ?>'><img src='<?php echo $ZONE_MEIJE ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Vercors</b>, commune de Le Gua (à côté du couloir des Sultanes)
			(<a name='Ver' href="<?php echo $ZONE_LEGUA_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_LEGUA ?>'><img src='<?php echo $ZONE_LEGUA ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Chartreuse</b>, commune de St-Hilaire du Touvet (1700m) 
			(<a name='Cha' href="<?php echo $ZONE_STHIL_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_STHIL ?>'><img src='<?php echo $ZONE_STHIL ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Chartreuse</b>, Centre d'Etude de la Neige (Col de Porte 1325m)  
			(<a name='Cha2' href="<?php echo $ZONE_PORTE_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_PORTE ?>'><img src='<?php echo $ZONE_PORTE ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Bauges</b>, plan de la Limace (1630m)
			(<a name='Bau' href="<?php echo $ZONE_ALLAN_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_ALLAN ?>'><img src='<?php echo $ZONE_ALLAN ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Aiguilles Rouges</b>, En face du Mont Blanc
			(<a name='Aig' href="<?php echo $ZONE_AIGRG_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_AIGRG ?>'><img src='<?php echo $ZONE_AIGRG ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Beaufortain</b>, Cote d'Aime, au lieudit de la Portette
			(<a name='Bea' href="<?php echo $ZONE_GRPAR_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_GRPAR ?>'><img src='<?php echo $ZONE_GRPAR ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Vanoise</b>, La Plagne
			(<a name='Van' href="<?php echo $ZONE_BELLE_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_BELLE ?>'><img src='<?php echo $ZONE_BELLE ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Haute-Maurienne</b>, Bonneval-sur-Arc.
			(<a name='Mau' href="<?php echo $ZONE_BONNE_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_BONNE ?>'><img src='<?php echo $ZONE_BONNE ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Haute Tarentaise</b>, lac du Chevril 
			(<a name='Tar' href="<?php echo $ZONE_CHEVR_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_CHEVR ?>'><img src='<?php echo $ZONE_CHEVR ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Thabor</b>, Camp des Rochilles 
			(<a name='Tha' href="<?php echo $ZONE_ROCHI_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_ROCHI ?>'><img src='<?php echo $ZONE_ROCHI ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Galibier</b>, dans le vallon de Roche Noire (Versant Sud des Trois Evêchés)
			(<a name='Gal' href="<?php echo $ZONE_GALIB_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_GALIB ?>'><img src='<?php echo $ZONE_GALIB ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Champsaur</b>, Orcières.
			(<a name='Ech' href="<?php echo $ZONE_ORCIE_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_ORCIE ?>'><img src='<?php echo $ZONE_ORCIE ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Queyras</b>, col Agnel.
			(<a name='Que' href="<?php echo $ZONE_AGNEL_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_AGNEL ?>'><img src='<?php echo $ZONE_AGNEL ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Parpaillon</b>, Lieudit 'Les Montagnettes', près du col de Parpaillon.
			(<a name='Par' href="<?php echo $ZONE_PARPA_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_PARPA ?>'><img src='<?php echo $ZONE_PARPA ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Ubaye</b>, Restefond.
			(<a name='Uba' href="<?php echo $ZONE_RESTE_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_RESTE ?>'><img src='<?php echo $ZONE_RESTE ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Mercantour</b>, Lac des Millefonts
			(<a name='Mer' href="<?php echo $ZONE_MILLE_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_MILLE ?>'><img src='<?php echo $ZONE_MILLE ?>'></a>
		</div>
	</div>
</div>
<div class="nivs">
	<div class="nivb">
		<div class="nivt">
			<b>Pyrénées Orientales</b>, Canigou
			(<a name='PyE' href="<?php echo $ZONE_CANIG_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_CANIG ?>'><img src='<?php echo $ZONE_CANIG ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Pyrénées Orientales</b>, Puigmal
			(<a name='Puig' href="<?php echo $ZONE_PUIGN_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_PUIGN ?>'><img src='<?php echo $ZONE_PUIGN ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Ariège</b>, Hospitalet 
			(<a name='PyA' href="<?php echo $ZONE_HOSPI_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_HOSPI ?>'><img src='<?php echo $ZONE_HOSPI ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Ariège</b>, Couserans (Port d'Aula)
			(<a name='Paul' href="<?php echo $ZONE_PAULA_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_PAULA ?>'><img src='<?php echo $ZONE_PAULA ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Haute Garonne</b>, Luchonnais (Maupas)
			(<a name='PyC' href="<?php echo $ZONE_MAUPA_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_MAUPA ?>'><img src='<?php echo $ZONE_MAUPA ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Haute Pyrénées</b>, Tunnel de Bielsa (Aiguillettes)
			(<a name='Big' href="<?php echo $ZONE_AIGTE_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_AIGTE ?>'><img src='<?php echo $ZONE_AIGTE ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Haute Pyrénées</b>, Lac d'Ardiden
			(<a name='PyW' href="<?php echo $ZONE_LARDI_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_LARDI ?>'><img src='<?php echo $ZONE_LARDI ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Pyrénées Atlantiques</b>, Aspe-Ossau (Soum Couy).
			(<a name='Soum' href="<?php echo $ZONE_SOUMC_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_SOUMC ?>'><img src='<?php echo $ZONE_SOUMC ?>'></a>
		</div>
	</div>
</div>
<div class="nivs">
	<div class="nivb">
		<div class="nivt">
			<b>Corse (Cinto-Rotondo)</b>, Sponde 
			(<a name='Cor' href="<?php echo $ZONE_SPOND_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_SPOND ?>'><img src='<?php echo $ZONE_SPOND ?>'></a>
		</div>
	</div>
	<div class="nivb">
		<div class="nivt">
			<b>Corse (Cinto-Rotondo)</b>, Maniccia 
			(<a name='Mani' href="<?php echo $ZONE_MANIC_SAI ?>">saison</a>)
		</div>
		<div class="nivi">
			<a href='<?php echo $ZONE_MANIC ?>'><img src='<?php echo $ZONE_MANIC ?>'></a>
		</div>
	</div>
</div>

<?php include 'bottom.inc'; ?>

</body></html>
