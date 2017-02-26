<p><h2>Klimadiagramme (under construction)</h2></p>

<?
$sNavigationsQuelle = "klimadiagramm";
$sSchaltflächenBeschriftung = "Aktualisieren";
include('./FormularKlimadiagramm.php');
include_once("./MVIncludes/db_class.php");

$db_handler = new db_class;
$iAnzahlSensoren = 1;
$iSensorTemp = 0;
$iSensorRegen = 3;
global $oDbReihenTupel0; $oDbAbfragenResultateSensor;$oDbAbfragenResultateJahr;$oDbAbfragenResultateMonat;$oDbAbfragenResultateTag;
global $oDbAbfragenResultateJahr_RS;$oDbAbfragenResultateMonat_RS;$oDbAbfragenResultateTag_RS;//Regensensor


$oTitelMonat = array();
$oTitelMonat[0] = 'Januar';
$oTitelMonat[1] = 'Februar';
$oTitelMonat[2] = 'März';
$oTitelMonat[3] = 'April';
$oTitelMonat[4] = 'Mai';
$oTitelMonat[5] = 'Juni';
$oTitelMonat[6] = 'Juli';
$oTitelMonat[7] = 'August';
$oTitelMonat[8] = 'September';
$oTitelMonat[9] = 'Oktober';
$oTitelMonat[10] = 'November';
$oTitelMonat[11] = 'Dezember';


//hole sensorbezeichnungen und masseinheiten
$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation = '$iSensorTemp'");	
//
while($oDbReihenTupel = mysql_fetch_row($dbResultat)) { 
	$oDbAbfragenResultateSensor["sensorbez"][0] = $oDbReihenTupel[0];	 
	$oDbAbfragenResultateSensor["sensormass"][0] = $oDbReihenTupel[1];
	//echo $oDbReihenTupel[0];
} 

//hole sensorbezeichnungen und masseinheiten
$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation = '$iSensorRegen'");	
//
while($oDbReihenTupel = mysql_fetch_row($dbResultat)) { 
	$oDbAbfragenResultateSensor["sensorbez"][1] = $oDbReihenTupel[0];	 
	$oDbAbfragenResultateSensor["sensormass"][1] = $oDbReihenTupel[1];
	//echo $oDbReihenTupel[0];
} 

$iSensorIdWert_avg_j = "m_j_avg_s".$iSensorTemp;
$iSensorIdWert_min_j = "m_j_min_s".$iSensorTemp;
$iSensorIdWert_max_j = "m_j_max_s".$iSensorTemp;

$iSensorIdWert_avg_m = "m_m_avg_s".$iSensorTemp;
$iSensorIdWert_min_m = "m_m_min_s".$iSensorTemp;
$iSensorIdWert_max_m = "m_m_max_s".$iSensorTemp;

//$iSensorIdWert_avg_t = "m_t_avg_s".$iSensorIdWert;
//$iSensorIdWert_min_t = "m_t_min_s".$iSensorIdWert;
$iSensorIdWert_max_t = "m_t_max_s".$iSensorRegen;


//hole Meteodaten dazu -> Jahreswerte

$query = "select $iSensorIdWert_avg_j,$iSensorIdWert_min_j,$iSensorIdWert_max_j from avg_jahr_meteo_mv where m_j_jahr='$iJahrIdWert'";
$dbResultat = $db_handler->db_query($query) or die($query);
if ($oDbReihenTupel = mysql_fetch_array($dbResultat) ){
	for ($i=0; $i < $iAnzahlSensoren; $i++){
	    $oDbAbfragenResultateJahr["avgwerte"][$i] = number_format(round($oDbReihenTupel[$i],1), 1);
	    $oDbAbfragenResultateJahr["minwerte"][$i] = number_format(round($oDbReihenTupel[$i+1],1), 1);
	    $oDbAbfragenResultateJahr["maxwerte"][$i] = number_format(round($oDbReihenTupel[$i+2],1), 1);
	    $oDbAbfragenResultateJahr["messzeit"][$i] = $iJahrIdWert;
		if ($bGlobalDebug){
	       echo "-act-".$oDbAbfragenResultateJahr["avgwerte"][$i]." ";
	       echo"<br></br>";
	    }
	}
}

//Monatswerte
$query = "select $iSensorIdWert_avg_m,$iSensorIdWert_min_m,$iSensorIdWert_max_m,m_m_monat from avg_monat_meteo_mv where m_m_jahr='$iJahrIdWert' order by m_m_monat asc";
$dbResultat = $db_handler->db_query($query) or die($query);
//if ($oDbReihenTupel = mysql_fetch_array($dbResultat) ){
$m=0;
while($oDbReihenTupel = mysql_fetch_row($dbResultat)){		
	for ($i=0; $i < $iAnzahlSensoren; $i++){
	    $oDbAbfragenResultateMonat["avgwerte"][] = number_format(round($oDbReihenTupel[$i],1), 1);
	    $oDbAbfragenResultateMonat["minwerte"][] = number_format(round($oDbReihenTupel[$i+1],1), 1);
	    $oDbAbfragenResultateMonat["maxwerte"][] = number_format(round($oDbReihenTupel[$i+2],1), 1);
	    //$oDbAbfragenResultateMonat["messzeit"][] = $oTitelMonat[$m];
	    $oDbAbfragenResultateMonat["messzeit"][] = $oTitelMonat[$oDbReihenTupel[$i+3]-1];
	    	
		if ($bGlobalDebug){
	       echo "-act-".$oDbAbfragenResultateMonat["avgwerte"][$m]." ";
	       echo"<br></br>";
	    }
	    $m++;
	}
}


	//select sum(m_t_max_s3),m_t_monat from avg_tag_meteo_mv where m_t_jahr=2011 group by m_t_monat
	//-->Kumuliert nach Monat mit max-Tageswerte --> Kumulierter Monatswerte
	$query = "select sum($iSensorIdWert_max_t),m_t_monat,max($iSensorIdWert_max_t),min($iSensorIdWert_max_t),avg($iSensorIdWert_max_t) from avg_tag_meteo_mv where m_t_jahr='$iJahrIdWert' GROUP BY m_t_monat ORDER BY m_t_monat ASC"; 
	$dbResultat = $db_handler->db_query($query) or die($query);
	$monat=1;
	$jahres_RS_Sum = 0;
	while($oDbReihenTupel = mysql_fetch_row($dbResultat)){
		for ($i=0; $i < $iAnzahlSensoren; $i++){
		    $oDbAbfragenResultateMonat_RS["monatsum"][] = number_format(round($oDbReihenTupel[$i],1), 1);
		    $oDbAbfragenResultateMonat_RS["messzeit"][] = $oTitelMonat[$oDbReihenTupel[$i+1]-1];
		    $jahres_RS_Sum = $jahres_RS_Sum + number_format(round($oDbReihenTupel[$i],1), 1);
		    $oDbAbfragenResultateMonat_RS["monatmax"][] = number_format(round($oDbReihenTupel[$i+2],1), 1);
		    $oDbAbfragenResultateMonat_RS["monatmin"][] = number_format(round($oDbReihenTupel[$i+3],1), 1);
		    $oDbAbfragenResultateMonat_RS["monatavg"][] = number_format(round($oDbReihenTupel[$i+4],1), 1);
			if ($bGlobalDebug){
		       echo "-act-".$oDbAbfragenResultateMonat_RS["monatsum"][$i]." ";
		       echo"<br></br>";
		    }
		    $monat++;
		}
		
	}

?>



<h3>Messwerte von <? echo $iJahrIdWert." / ";?><?echo $oDbAbfragenResultateSensor["sensorbez"][0]." ".$oDbAbfragenResultateSensor["sensormass"][0]." / ".$oDbAbfragenResultateSensor["sensorbez"][1]." ".$oDbAbfragenResultateSensor["sensormass"][1] ?> </h3>

<? 

echo '<table  class="mittelWerteTextTM" >';
echo '<tr>';

echo '<td align="left" BGCOLOR="#ffff00"><b>Wert für</b></td>';
echo '<td align="center" BGCOLOR="#ffff00"><b>Mittelwert Temperatur.</b></td>';
echo '<td align="center" BGCOLOR="#ffff00"><b>Jahres-/Monatssumme Regen</b></td>';
echo '</tr>';


echo '<tr>';
echo "<td align='left' BGCOLOR='#00ffff'>".$oDbAbfragenResultateJahr['messzeit'][0]."</td>";
echo "<td align='center' BGCOLOR='#00ffff'>".$oDbAbfragenResultateJahr['avgwerte'][0]."</td>";
echo "<td align='center' BGCOLOR='#00ffff'>".$jahres_RS_Sum."</td>";
echo "</tr>";

//echo "Anz. Monate Meteo".count($oDbAbfragenResultateMonat["messzeit"]);
//echo "----Anz. Monate Meteo".count($oDbAbfragenResultateMonat_RS["messzeit"]);

	for ($i = 0; $i < count($oDbAbfragenResultateMonat["messzeit"]); $i++ ) {
	  echo "<tr>";
	    echo "<td align='left' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat["messzeit"][$i]."</td>";
	    echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat["avgwerte"][$i]."</td>";	
	    echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat_RS["monatsum"][$i]."</td>";    
	  echo "</tr>";
	}	
//	for ($i = 0; $i < count($oDbAbfragenResultateMonat_RS["messzeit"]); $i++ ) {
//	  echo "<tr>";
//	  	echo "<td align='left' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat_RS["messzeit"][$i]."</td>";
//	    echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat_RS["monatsum"][$i]."</td>";
//	  echo "</tr>";
//	}

echo '</table>';

?>  	





