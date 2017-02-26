<?

$sBilderPfad = "./Publikationen";

// Open a known directory, and proceed to read its contents
if (is_dir($sBilderPfad)) {
	if ($oPfadHandler = opendir($sBilderPfad)) {
		echo("<table><tr>");
		$iReihenBilderAnzahl = -1;
		$iAnzahlBilderProReihe = 1;
		$iBildBreite = ceil($_GET['iBrowserFensterBreite'] / $iAnzahlBilderProReihe);
    	while (($oDatei = readdir($oPfadHandler)) !== false) {
       		if (is_file($sBilderPfad . "/" . $oDatei)) {
	    		$iReihenBilderAnzahl = ($iReihenBilderAnzahl+1) % $iAnzahlBilderProReihe;
	    		if ($iReihenBilderAnzahl == 0) echo("<tr>");
				
	    		//$dispFileName = substr($oDatei,0,strlen($oDatei)-4);
	    		$dispFileName = $oDatei;
				
       			//echo("<th class='padding0' width=\"". 100/$iAnzahlBilderProReihe . "%\" height=\"". 100/$iAnzahlBilderProReihe . "%\">$dispFileName <br> <a href=\"$sBilderPfad/$oDatei\"><img align=\"center\" border=1 width=\"$iBildBreite\" height=\"$iBildBreite\" src=\"$sBilderPfad/$oDatei\" alt=\"$oDatei\"></a><br>&nbsp;</th>\n");       			
       			//echo("<th align='left' \"><a href=\"$sBilderPfad/$oDatei\">$dispFileName</a><br>&nbsp;</th>\n");
       			echo("<th align='left' ><a href=\"$sBilderPfad/$oDatei\">$dispFileName</a><br>&nbsp;</th>\n");

       			if ($iReihenBilderAnzahl == $iAnzahlBilderProReihe-1) echo("</tr>");
       			
       		}

       	}
		echo("</table>");
       	closedir($oPfadHandler);
   	}
}


?>