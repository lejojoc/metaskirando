<?php

require_once('./settings.inc.php');
global $SETTINGS;

//token meteofrance pour acceder à l'api

$urlava="http://www.data-avalanche.org/feed";
		
$ndfava="data_aval.xml";
$fi = stat($ndfava);
// si le xml existe est a moins d'une heure on le renvoi
if(time()-$fi[9]<3600) {
   header("Location: $ndfava");
   die();
}
// sinon on va chercher la data, on stocke et on renvoi
$xava=file_get_contents($urlava);
if($xava == '') {
	print "<p>Sorry. Cannot get data from " . $urlava . "</p>";
} else {
	$xava=str_replace("<feed", "<?xml-stylesheet type='text/xsl' href='ava.xslt'?><feed", $xava); 
	$xava=str_replace("<feed", "<ava", $xava); 
	$xava=str_replace("</feed", "</ava", $xava); 
	unlink($ndfava);
	$fbr=@fopen($ndfava,'w');
	@fwrite($fbr,$xava);
	@fclose($fbr);
   header("Location: $ndfava");
   die();
}
?>