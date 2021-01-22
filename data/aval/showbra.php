<?php

require_once('./settings.inc.php');
global $SETTINGS;

//token meteofrance pour acceder à l'api
$token='__Wj7dVSTjV9YGu1guveLyDq0g7S7TfTjaHBTPTpO0kj8__';
$braid=$_GET['bra'];
$braid= substr('0' . $braid, -2);

$urlbra="https://rpcache-aa.meteofrance.com/internet2018client/2.0/report?domain=$braid&report_type=Forecast&report_subtype=BRA&token=$token";
		
$ndfbra="BRA_" . $braid . ".xml";
$fi = stat($ndfbra);
// si le xml existe est a moins d'une heure on le renvoi
if(time()-$fi[9]<3600) {
   header("Location: $ndfbra");
   die();
}
// sinon on va chercher chez MF, on stocke et on renvoi
$xbra=file_get_contents($urlbra);
if($xbra == '') {
	print "<p>Sorry. Cannot get BERA for id = " . $braid . "</p>";
} else {
	$xbra=str_replace("../web/bra.xslt", "bra.xslt", $xbra); 
	unlink($ndfbra);
	$fbr=@fopen($ndfbra,'w');
	@fwrite($fbr,$xbra);
	@fclose($fbr);
   header("Location: $ndfbra");
   die();
}
?>