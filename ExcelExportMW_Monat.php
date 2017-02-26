<?

//include_once("./MVIncludes/db_class.php");
//include("./MVIncludes/mvdates.php");

ini_set('max_execution_time', 0);
//oder in php.ini : max_execution_time = 240

//$db_handler = new db_class;
//$sDbTabellenName = "Messwerte";
//$sDbName  = "MeteoVaduz";


//wenn $bTitelAusgabe=true dann wird in der Exceldatei in erste Zelle ein Titel ausgegeben
$bTitelAusgabe = false; 
//Titeltext definieren
$sTitelAusgabeText = "Messwerte von".$iJahrIdWert." / ".$oDbAbfragenResultateSensor['sensorbez'][0]." ".$oDbAbfragenResultateSensor["sensormass"][0]; 
 
//Datei wird in Excelformat erzeugt('.xls') 

if (isset($_GET['bUrlCvsAuswahl']) && ($_GET['bUrlCvsAuswahl']==1)){
	$sDateiTyp = "text/plain"; 
	$sDateiTypEndung = "csv"; 
	$FeldTrennzeichen = ";";
}else{
	$sDateiTyp = "vnd.ms-excel"; 
	$sDateiTypEndung = "xls";
	$FeldTrennzeichen = "\t";
}


    if (!isset($_GET['sUrlSensor'])) {
        $_GET['sUrlSensor'] = '';
    } 


//header informationen für den browser: dateityp festlegen ('.xls') 
/**/
header("Content-Type: application/$sDateiTyp"); 
header("Content-Disposition: attachment; filename=Tageswerte.$sDateiTypEndung"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

//echo "iSensorIdWer0t: ".$iSensorIdWert; 

//	echo "bDonwnloadData != 0";
	$iSensorIdWert = $_GET['iSensorIdWert'];
	$iJahrIdWert = $_GET['iJahrIdWert'];
	$iMonatIdWert = $_GET['iMonatIdWert'];

include("./MittelwerteDatenSelector.php");

//echo "iSensorIdWert: ".$iSensorIdWert; 

//wenn $bTitelAusgabe auf = true dann $sTitelAusgabeText ausgeben 
if ($bTitelAusgabe){ 
	echo("$sTitelAusgabeText\n"); 
} 


//schreibe Messzeit als überschrift in erste zeile
//if (isset($_GET['bUrlCvsAuswahl']) && ($_GET['bUrlCvsAuswahl']==1)){
if (isset($_GET['bUrlCvsAuswahl'])){
		
		$sZellenEintrag = "";
	
		$sZellenEintrag .= "Wert für". $FeldTrennzeichen; 

		
		if($iSensorIdWert!=3){ // nicht Regensensor
			$sZellenEintrag .= "Mittelwert". $FeldTrennzeichen;
			$sZellenEintrag .= "Minimalwert". $FeldTrennzeichen;
			$sZellenEintrag .= "Maximalwert". $FeldTrennzeichen;
			
		}else{	
			$sZellenEintrag .= "Tagessumme". $FeldTrennzeichen;
		}
		echo $sZellenEintrag;
		
		
				
		//print("\n");
		

		for ($t2 = 0; $t2 <   round(count($oDbAbfragenResultateTag["messzeit"])); $t2++ ) {
			print("\n");
			$sZellenEintrag = "";
			
			echo $oDbAbfragenResultateTag["messzeit"][$t2].$FeldTrennzeichen; //erste Spalte "z.B. 01. Jan" punkt nicht ersetzen 
			if($iSensorIdWert!=3){ // nicht Regensensor
				$sZellenEintrag .= $oDbAbfragenResultateTag["avgwerte"][$t2].$FeldTrennzeichen;
				$sZellenEintrag .= $oDbAbfragenResultateTag["minwerte"][$t2].$FeldTrennzeichen;
			}
			$sZellenEintrag .= $oDbAbfragenResultateTag["maxwerte"][$t2].$FeldTrennzeichen;
			
			if ($_GET['bUrlCvsAuswahl']==0){
			    $sZellenEintrag = str_replace($FeldTrennzeichen."$", "", $sZellenEintrag); 
				//c.s:03.04.2006 ersetze gleitkomma von punkt zu komma wegen formatierungsproblem im Excel (11.11 wird als Datum interpretiert)
				$sZellenEintrag = str_replace(".", ",", $sZellenEintrag);
			    $sZellenEintrag = preg_replace("/\r\n|\n\r|\n|\r/", " ", $sZellenEintrag); 
			    $sZellenEintrag .= "\t"; 
			    print(trim($sZellenEintrag)); 
			}else{
				echo $sZellenEintrag;
			}
		}//for  	

		print "\n"; 
	
}else{
	//echo "Messzeit". "\t";
}





 
?> 
