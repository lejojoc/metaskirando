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
	extract($_COOKIE);
	extract($_POST);

	$time = time();
	$tsave = $time + 30*24*3600;
	$tdel = $time - 1000;

// Rajoute une région.
	if (!empty($_POST['new_name']))
	{
		if (!empty($_POST['filtA']))
			$new_filter = implode('|',$filtA);
		if (!empty($new_filter))
		{
			setcookie("region[$new_name]", $new_filter, $tsave);
			$region[$new_name] = $new_filter;
		}
	}

// Met à jour les filtres
	if (isset($_POST['region']))
	{
		foreach ( $_POST['region'] as $name => $filter )
		{
			$region[$name] = $filter;
			setcookie("region[$name]", '', $tdel);
			setcookie("region[$name]", $filter, $tsave);
		}
	}

// Efface une valeur.
	if (isset($_GET['delete']))
	{
		$delete = $_GET['delete'];
		setcookie("region[$delete]", '', $tdel);
		unset($region[$delete]);
	}

	if (isset($_POST['save']))
	{
// Pente raide ?
		if (isset($_POST['raide'])) {
			setcookie('raide', 'on', $tsave);
		} else {
			setcookie('raide', '', $tdel);
		}
// MyRegs only ?
		if (isset($_POST['myregs'])) {
			setcookie('myregs', 'only', $tsave);
		} else {
			setcookie('myregs', '', $tdel);
		}
	}

	require 'sites.inc.php';
	load_All($sorties);
	$regs = make_region_list($sorties);
?>
<html>
<head>
<title>Meta-Skirando : Mes préférences</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="initial-scale=1">
<link href="style.css" rel="stylesheet" type="text/css">
<link rel="canonical" href="http://www.metaskirando.ovh/prefs.php" />
</head>
<body>

<?php include 'menu.inc'; ?>

<form method='post'>
	<h2>Préférences</h2>
	
	<p>
		<input type=checkbox name="raide" id="raide" <?php if (isset($raide)) echo 'checked'; ?>/>
		<label for='raide'>Afficher uniquement la pente raide par défaut (<i>à partir de la difficulté 4.1 ou D-, et volopress.fr</i>).</label>
	</p>
	<p>
		<input type=checkbox name="myregs" id="myregs" <?php if (isset($myregs)) echo 'checked'; ?>/>
		<label for="myregs">Afficher les dernières sorties uniquement dans mes régions (et pas toutes les sorties des Alpes ou du Pakistan :)</label>
	</p>	
	<h2>Mes Régions</h2>
	<p>Vous avez défini les régions suivantes :</p>
	<?php
		if (isset($region))	{
			foreach ( $region as $name => $filter )
			{
				echo "<div class='mr'>";
				echo "<div class='mrd'><a href='prefs.php?delete=$name'>X</a></div>";
				echo "<div class='mrn'>$name</div>";
				$label = str_replace("|", " | ", $filter);
				echo "<div class='mri'>$label</div>";
				echo "</div>\n";
			}
		}
	?>
	<p><br/>Pour définir une nouvelle région, indiquer un nom et spécifier un filtre ci-dessous.</p>
	<div class="mr">
		<div class="mrnx">
			<label for="new_name">Nom</label><br/>
			<input typr=text size=6 name='new_name' id='new_name'/>	
		</div>
		<div class="mrfx">
			<label for="new_filter">Filtre</label>
			<input typr='text' size='10' name='new_filter' id='new_filter' style="width: 100%;"/>
			<br>ou crérer à partir de région existantes :<br>
			<select size="8" name="filtA[]" multiple="multiple" style="width: 100%;">
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
			</select><br>(<i>selection multiple avec la touche ctrl</i>)
		</div>
	</div>
	<p>Le filtre est une expression régulière, pouvant utiliser '|' (opérateur 'ou') et '.' (un caractère quelconque, recommandé à la place de caractères accentués). <u>Exemples</u> : "<i>D.voluy|.crins</i>", "<i>Beaufort</i>" (=tout ce qui contient du Beaufort).<br/>Vous pouvez tester les filtres sur la page d'accueil avant de les enregistrer ici.</p>
	<p style="text-align: center;"><INPUT TYPE=submit NAME="save" VALUE="Oui, c'est ça !"/></p>
</form>


<?php include 'bottom.inc'; ?>

</body></html>
