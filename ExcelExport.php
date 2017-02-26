<?
include_once("./MVIncludes/db_class.php");
include("./MVIncludes/mvdates.php");

ini_set('max_execution_time', 0);
//oder in php.ini : max_execution_time = 240

$db_handler = new db_class;
$sDbTabellenName = "Messwerte";
$sDbName  = "MeteoVaduz";

//echo "iSensorIdWer0t: ".$iSensorIdWert; 

//wenn $bTitelAusgabe=true dann wird in der Exceldatei in erste Zelle ein Titel ausgegeben
$bTitelAusgabe = false; 
//Titeltext definieren
$sTitelAusgabeText = "Datenauszug für Tabelle $sDbTabellenName aus Datenbank $sDbName von ".$_GET['sDatumVon']." bis ".$_GET['sDatumBis']; 
 
//Datei wird in Excelformat erzeugt('.xls') 

if (isset($_GET['bUrlCvsAuswahl']) && ($_GET['bUrlCvsAuswahl']==1)){
	$sDateiTyp = "text/plain"; 
	$sDateiTypEndung = "csv"; 
}else{
	$sDateiTyp = "vnd.ms-excel"; 
	$sDateiTypEndung = "xls"; 
}


    if (!isset($_GET['sUrlSensor'])) {
        $_GET['sUrlSensor'] = '';
    } 


//header informationen für den browser: dateityp festlegen ('.xls') 
header("Content-Type: application/$sDateiTyp"); 
header("Content-Disposition: attachment; filename=Messwerteauszug.$sDateiTypEndung"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

//Datum checken und ändern in dbformat
//von und bis prüfen ob ein richtiges Datum ist
if (vergleicheVonBis(gibZeitStempel($_GET['sDatumVon']),gibZeitStempel($_GET['sDatumBis']))) {
	//echo "Daten ok";		
	//$sDatumVon und $sDatumBis auf mysqlformat stellen, für select
	$_GET['sDatumVon'] = gibMysqlDatum($_GET['sDatumVon']);
	$_GET['sDatumBis'] = gibMysqlDatum($_GET['sDatumBis']);
} else {
	echo " Startdatum muss vor dem Enddatum liegen! ";		
}


//wenn $bTitelAusgabe auf = true dann $sTitelAusgabeText ausgeben 
if ($bTitelAusgabe){ 
	echo("$sTitelAusgabeText\n"); 
} 

//Spaltentrennzeichen definieren
$cSpaltenTrennzeichen = "\t";  

//hole alle sensorbezeichnungen und masseinheiten für spaltenüberschrift
if($_GET['sURLSensor']=="*"){
	$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation in (0,1,2,3,4,5,6) ");
}else{
	$sUrlSensorID = substr($_GET['sURLSensor'],1,1);
	$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation = '".$sUrlSensorID."'");
}


//schreibe Messzeit als überschrift in erste zeile
if (isset($_GET['bUrlCvsAuswahl']) && ($_GET['bUrlCvsAuswahl']==1)){
	echo "Messzeit". ";"; 
}else{
	echo "Messzeit". "\t"; 
}


//dann schreibe alle sensorbezeichnungen und masseinheiten für spaltenüberschrift
while($oDbReihenTupel = mysql_fetch_row($dbResultat)) { 
	if (isset($_GET['bUrlCvsAuswahl']) && ($_GET['bUrlCvsAuswahl']==1)){
		echo $oDbReihenTupel[0]." in ".$oDbReihenTupel[1]. ";";
	}else{
		echo $oDbReihenTupel[0]." in ".$oDbReihenTupel[1]. "\t";
	}
	 
} 
//gehe in die nächste reihe in der exceltabelle
print("\n"); 


//hole alle werte für den gegebenen zeitraum aus datenbank 
if($_GET['sURLSensor']=="*"){
	//echo "db_query: "."select * from $sDbTabellenName where Messzeit > '".$_GET['sDatumVon']."' and Messzeit <= '".$_GET['sDatumBis']."'";
	$dbResultat = $db_handler->db_query("select * from $sDbTabellenName where Messzeit between '".$_GET['sDatumVon']."' and '".$_GET['sDatumBis']."' order by Messzeit");
	
}else{
	//echo "db_query: "."select Messzeit,".$_GET['sURLSensor']." from $sDbTabellenName where Messzeit > '".$_GET['sDatumVon']."' and Messzeit <= '".$_GET['sDatumBis']."'";
	
	//19.8.2012
	$dbResultat = $db_handler->db_query("select Messzeit,".$_GET['sURLSensor']." from $sDbTabellenName where Messzeit between '".$_GET['sDatumVon']."' and '".$_GET['sDatumBis']."' order by Messzeit");
	//Datumsformat fixieren:	
	//$dbResultat = $db_handler->db_query("select DATEFORMAT(Messzeit, '%y.%m.%d'),".$_GET['sURLSensor']." from $sDbTabellenName where Messzeit between '".$_GET['sDatumVon']."' and '".$_GET['sDatumBis']."' order by Messzeit");
	
}

//holle alle daten reihenweise und schreibe sie einzeln in die spalten ein

    while($oDbReihenTupel = mysql_fetch_row($dbResultat)){ 
        //set_time_limit(60); // legt die zeit in sekunden fest, die ein script laufen darf 
        $sZellenEintrag = ""; 
        for($iSpaltenIndex=0; $iSpaltenIndex<mysql_num_fields($dbResultat);$iSpaltenIndex++){ 
		 	if (isset($_GET['bUrlCvsAuswahl']) && ($_GET['bUrlCvsAuswahl']==1)){	
				
		 		//Deaktivieren für: 19.8.2012 Erste Spalte Zeitstempel -> Formatierung auf YYYY/mm/dd
		 		//echo $oDbReihenTupel[$iSpaltenIndex].";";	
				
				//START:19.8.2012 Erste Spalte Zeitstempel -> Formatierung auf YYYY/mm/dd
				if($iSpaltenIndex==0){
							$oDate = new DateTime($oDbReihenTupel[$iSpaltenIndex]);
							echo $oDate->format("Y/m/d H:i").";";
				}else{
					echo $oDbReihenTupel[$iSpaltenIndex].";";
				}			
				//END:19.8.2012 Erste Spalte Zeitstempel -> Formatierung auf YYYY/mm/dd
				
			}else{
	            if(!isset($oDbReihenTupel[$iSpaltenIndex])) 
	                $sZellenEintrag .= "NULL".$cSpaltenTrennzeichen; 
	            elseif ($oDbReihenTupel[$iSpaltenIndex] != "") 
	                $sZellenEintrag .= "$oDbReihenTupel[$iSpaltenIndex]".$cSpaltenTrennzeichen; 

//$sZellenEintrag .= "'"."$oDbReihenTupel[$iSpaltenIndex]".$cSpaltenTrennzeichen;

	            else 
	                $sZellenEintrag .= "".$cSpaltenTrennzeichen; 
 			}
 			
       } 
	   if (!isset($_GET['bUrlCvsAuswahl'])){
	        $sZellenEintrag = str_replace($cSpaltenTrennzeichen."$", "", $sZellenEintrag); 
//c.s:03.04.2006 ersetze gleitkomma von punkt zu komma wegen formatierungsproblem im Excel (11.11 wird als Datum interpretiert)
$sZellenEintrag = str_replace(".", ",", $sZellenEintrag);
	        $sZellenEintrag = preg_replace("/\r\n|\n\r|\n|\r/", " ", $sZellenEintrag); 
	        $sZellenEintrag .= "\t"; 
	        print(trim($sZellenEintrag)); 
		}
        print "\n"; 
    } 
?> 
