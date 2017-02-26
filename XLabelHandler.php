<?

/*
$iXBezGroesse = 10;
//max anzahl zu beschriftende xlabels
$anzXLabels = ceil($iBildBreite/$iXBezGroesse);
//alle xLabelIntervall label zeichnen
$xLabelIntervall = ceil($iAnzahlWerte/$anzXLabels);
*/

$iXBezGroesse = 10;
$graphButtonSpace = 0;



function gibXLabelIntervall($breite,$aSP){	
	//max anzahl zu beschriftende xlabels
	global $bGlobalDebug;
	global $iXBezGroesse;
	if($bGlobalDebug){
		echo "Breite in XLabelHandler: ".$breite;
		echo "AnzahlSP in XLabelHandler: ".$aSP;
		echo "xLabelSize in XLabelHandler: ".$iXBezGroesse;
	}
	$anzXLabels = ceil($breite/$iXBezGroesse);
	//alle xLabelIntervall label zeichnen
	$xLabelIntervall = ceil($aSP/$anzXLabels);
	return $xLabelIntervall;
}


//Abstand vom unteren Rand und X-Achse
function gibButtonSpace(){
	global $bGlobalDebug;
	global $graphButtonSpace;
	if($bGlobalDebug){echo "gibButtonSpace bei XLabelHandler: ".$graphButtonSpace;}
	return $graphButtonSpace;
}


function gibXLabelGroesse(){
	global $bGlobalDebug;
	global $iXBezGroesse;
	if($bGlobalDebug){echo "gibXLabelGroesse bei XLabelHandler: ".$iXBezGroesse;}
	return $iXBezGroesse;
}

//in: z.B:
//von:	2003-10-22 10:57:32
//bis:	2003-11-05 22:35:56
function gibXLabelFormat($datenArray,$iBildBreite){
	global $bGlobalDebug;
	global $graphButtonSpace;
	//global $iXBezIntervallWert;
	//global $XGruop;
	
	$retXLabels = array();
	$anzSP = count($datenArray);
	if($anzSP > 0){
		$sDatumVon = $datenArray[0];
		$sDatumBis = end($datenArray)	;
		$vdarray = explode(" ",$sDatumVon);
		$vdar = explode("-",$vdarray[0]);
		$bdarray = explode(" ",$sDatumBis);
		$bdar = explode("-",$bdarray[0]);		
		for ($i = 0; $i < $anzSP; $i++) {
			$dateAr = explode(" ",$datenArray[$i]);
			if($bGlobalDebug){
				echo "vdar[0]:".$vdar[0];
				echo "<br>";
				echo "bdar[0]:".$bdar[0];
				echo "<br>";
				echo "iXBezIntervallWert/iAnzahlWerteProXSchritt".($iXBezIntervallWert/$iAnzahlWerteProXSchritt);
				echo "<br>";
				echo "(i%(iXBezIntervallWert/iAnzahlWerteProXSchritt)".($i%($iXBezIntervallWert/$iAnzahlWerteProXSchritt));
				echo "<br>";
			}
//			if(($i%($iXBezIntervallWert/$iAnzahlWerteProXSchritt))==0){
				
				if($vdar[0]==$bdar[0]){//gleiches Jahr
					if($vdar[1]==$bdar[1]){//gleiches Jahr, gleicher Monat
						if($vdar[2]==$bdar[2]){//gleiches Jahr, gleicher Monat, gleicher Tag 
							//Stunden:Minuten
							$retXLabels[] = substr($dateAr[1],0,5);
						}else{//gleiches Jahr, gleicher Monat, anderer Tag 
							// Tag Stunden:Minuten
							$retXLabels[] = substr($dateAr[0],8,2)."/".substr($dateAr[1],0,5);
						}
					}else{//gleiches Jahr, anderer Monat
						//Monat-Tag Stunden:Minuten
						$retXLabels[] = substr($dateAr[0],5,5)."/".substr($dateAr[1],0,5);
					}
				}else{//unterschiedliches Jahr
					//Jahr-Monat-Tag Stunden:Minuten
					$retXLabels[] = substr($dateAr[0],2,8)."/".substr($dateAr[1],0,5);				
				}
//			}
		}
		//Abstand vom unteren Rand und X-Achse
		$graphButtonSpace = $iXBezGroesse = (8 * strlen($retXLabels[0]))+2;
		
	}else{
		echo "X-Bezeichnugs-Kalenderdaten ist leer!";
	}
	return $retXLabels;

}



?>