<?
include_once("./MVIncludes/db_class.php");
include("./MVIncludes/mvdates.php");

$db_handler = new db_class;
$sDbTabellenName = "Messwerte";
$sDbName  = "MeteoVaduz";


//wenn $bTitelAusgabe=true dann wird in der Exceldatei in erste Zelle ein Titel ausgegeben
$bTitelAusgabe = false; 
//Titeltext definieren
$sTitelAusgabeText = "Datenauszug f�r Tabelle $sDbTabellenName aus Datenbank $sDbName von $sDatumVon bis $sDatumBis"; 
 
//Datei wird in Excelformat erzeugt('.xls') 

if (isset($bUrlCvsAuswahl) && ($bUrlCvsAuswahl==1)){
	$sDateiTyp = "text/plain"; 
	$sDateiTypEndung = "csv"; 
}else{
	$sDateiTyp = "vnd.ms-excel"; 
	$sDateiTypEndung = "xls"; 
}

 
//header informationen f�r den browser: dateityp festlegen ('.xls') 
header("Content-Type: application/$sDateiTyp"); 
header("Content-Disposition: attachment; filename=Messwerteauszug.$sDateiTypEndung"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

//Datum checken und �ndern in dbformat
//von und bis pr�fen ob ein richtiges Datum ist
if (vergleicheVonBis(gibZeitStempel($sDatumVon),gibZeitStempel($sDatumBis))) {
	//echo "Daten ok";		
	//$sDatumVon und $sDatumBis auf mysqlformat stellen, f�r select
	$sDatumVon = gibMysqlDatum($sDatumVon);
	$sDatumBis = gibMysqlDatum($sDatumBis);
} else {
	echo "Startdatum umd Enddatum nicht korrekt!";		
}


//wenn $bTitelAusgabe auf = true dann $sTitelAusgabeText ausgeben 
if ($bTitelAusgabe){ 
	echo("$sTitelAusgabeText\n"); 
} 

//Spaltentrennzeichen definieren
$cSpaltenTrennzeichen = "\t";  

//hole alle sensorbezeichnungen und masseinheiten f�r spalten�berschrift
if($sURLSensor=="*"){
	$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren");
}else{
	$sUrlSensorID = substr($sURLSensor,1,1);
	$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation = '$sUrlSensorID'");
}


//schreibe Messzeit als �berschrift in erste zeile
if (isset($bUrlCvsAuswahl) && ($bUrlCvsAuswahl==1)){
	echo "Messzeit". ";"; 
}else{
	echo "Messzeit". "\t"; 
}


//dann schreibe alle sensorbezeichnungen und masseinheiten f�r spalten�berschrift
while($oDbReihenTupel = mysql_fetch_row($dbResultat)) { 
	if (isset($bUrlCvsAuswahl) && ($bUrlCvsAuswahl==1)){
		echo $oDbReihenTupel[0]." in ".$oDbReihenTupel[1]. ";";
	}else{
		echo $oDbReihenTupel[0]." in ".$oDbReihenTupel[1]. "\t";
	}
	 
} 
//gehe in die n�chste reihe in der exceltabelle
print("\n"); 


//hole alle werte f�r den gegebenen zeitraum aus datenbank 
if($sURLSensor=="*"){
	$dbResultat = $db_handler->db_query("select * from $sDbTabellenName where Messzeit > '$sDatumVon' and Messzeit <= '$sDatumBis'");
}else{
	$dbResultat = $db_handler->db_query("select Messzeit,$sURLSensor from $sDbTabellenName where Messzeit > '$sDatumVon' and Messzeit <= '$sDatumBis'");
}

//holle alle daten reihenweise und schreibe sie einzeln in die spalten ein

    while($oDbReihenTupel = mysql_fetch_row($dbResultat)){ 
        //set_time_limit(60); // legt die zeit in sekunden fest, die ein script laufen darf 
        $sZellenEintrag = ""; 
        for($iSpaltenIndex=0; $iSpaltenIndex<mysql_num_fields($dbResultat);$iSpaltenIndex++){ 
		 	if (isset($bUrlCvsAuswahl) && ($bUrlCvsAuswahl==1)){	
				echo $oDbReihenTupel[$iSpaltenIndex].";";
			}else{
	            if(!isset($oDbReihenTupel[$iSpaltenIndex])) 
	                $sZellenEintrag .= "NULL".$cSpaltenTrennzeichen; 
	            elseif ($oDbReihenTupel[$iSpaltenIndex] != "") 
	                $sZellenEintrag .= "$oDbReihenTupel[$iSpaltenIndex]".$cSpaltenTrennzeichen; 
	            else 
	                $sZellenEintrag .= "".$cSpaltenTrennzeichen; 
 			}
 			
       } 
	   if (!isset($bUrlCvsAuswahl)){
	        $sZellenEintrag = str_replace($cSpaltenTrennzeichen."$", "", $sZellenEintrag); 
	        $sZellenEintrag = preg_replace("/\r\n|\n\r|\n|\r/", " ", $sZellenEintrag); 
	        $sZellenEintrag .= "\t"; 
	        print(trim($sZellenEintrag)); 
		}
        print "\n"; 
    } 
?> 
