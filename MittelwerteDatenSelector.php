<?php
include_once("./MVIncludes/db_class.php");
$db_handler = new db_class;
$iAnzahlSensoren = 1;
$iAnzahlSensorenMeteo_old = 7;
$iAnzahlSensorenBoden = 4;
$iAnzahlSensorenMeteo = 18;
$iAnzahlSensoren_old = 11;
$iAnzahlSensoren_new = 22;



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




//echo "iSensorIdWert: ".$iSensorIdWert;

//$nowtime = time();
//echo "bDonwnloadDataExport $nowtime: ";

//hole sensorbezeichnungen und masseinheiten
$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation = '$iSensorIdWert'");	
//
while($oDbReihenTupel = mysql_fetch_row($dbResultat)) { 
	$oDbAbfragenResultateSensor["sensorbez"][0] = $oDbReihenTupel[0];	 
	$oDbAbfragenResultateSensor["sensormass"][0] = $oDbReihenTupel[1];
	//echo $oDbReihenTupel[0];
} 


//hole alle Werte mit aktuellem Jahr
if (($iSensorIdWert > $iAnzahlSensorenMeteo_old-1) && (($iSensorIdWert < $iAnzahlSensoren_old))){//boden
	
	$iSensorIdWert_bod = $iSensorIdWert - $iAnzahlSensorenMeteo_old;
	
	echo 'Bodensensor iSensorIdWert_: '.$iSensorIdWert;
	echo 'Bodensensor iSensorIdWert_bod_: '.$iSensorIdWert_bod;
	
	$iSensorIdWert_bod_avg_j = "b_j_avg_s".$iSensorIdWert_bod;
	$iSensorIdWert_bod_min_j = "b_j_min_s".$iSensorIdWert_bod;
	$iSensorIdWert_bod_max_j = "b_j_max_s".$iSensorIdWert_bod;
	
	$iSensorIdWert_bod_avg_m = "b_m_avg_s".$iSensorIdWert_bod;
	$iSensorIdWert_bod_min_m = "b_m_min_s".$iSensorIdWert_bod;
	$iSensorIdWert_bod_max_m = "b_m_max_s".$iSensorIdWert_bod;

	$iSensorIdWert_bod_avg_t = "b_t_avg_s".$iSensorIdWert_bod;
	$iSensorIdWert_bod_min_t = "b_t_min_s".$iSensorIdWert_bod;
	$iSensorIdWert_bod_max_t = "b_t_max_s".$iSensorIdWert_bod;
	
	
	//hole Bodendaten dazu
	$query = "select $iSensorIdWert_bod_avg_j,$iSensorIdWert_bod_min_j,$iSensorIdWert_bod_max_j from avg_jahr_boden_mv where b_j_jahr='$iJahrIdWert'";
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
	$query = "select $iSensorIdWert_bod_avg_m,$iSensorIdWert_bod_min_m,$iSensorIdWert_bod_max_m,b_m_monat from avg_monat_boden_mv where b_m_jahr='$iJahrIdWert' order by b_m_monat asc";
	$dbResultat = $db_handler->db_query($query) or die($query);
	//if ($oDbReihenTupel = mysql_fetch_array($dbResultat) ){
	$m=0;
	while($oDbReihenTupel = mysql_fetch_row($dbResultat)){		
		for ($i=0; $i < $iAnzahlSensoren; $i++){
		    $oDbAbfragenResultateMonat["avgwerte"][] = number_format(round($oDbReihenTupel[$i],1), 1);
		    $oDbAbfragenResultateMonat["minwerte"][] = number_format(round($oDbReihenTupel[$i+1],1), 1);
		    $oDbAbfragenResultateMonat["maxwerte"][] = number_format(round($oDbReihenTupel[$i+2],1), 1);
		    $oDbAbfragenResultateMonat["messzeit"][] = $oTitelMonat[$oDbReihenTupel[$i+3]-1];
		    
			if ($bGlobalDebug){
		       echo "-act-".$oDbAbfragenResultateMonat["avgwerte"][$m]." ";
		       echo"<br></br>";
		    }
		    $m++;
		}
	}
	
	//Tageswerte
	$query = "select $iSensorIdWert_bod_avg_t,$iSensorIdWert_bod_min_t,$iSensorIdWert_bod_max_t,b_t_tag from avg_tag_boden_mv where b_t_jahr='$iJahrIdWert' and b_t_monat='$iMonatIdWert' order by b_t_tag asc";
	$dbResultat = $db_handler->db_query($query) or die($query);
	$tag=1;
	while($oDbReihenTupel = mysql_fetch_row($dbResultat)){
		for ($i=0; $i < $iAnzahlSensoren; $i++){
		    $oDbAbfragenResultateTag["avgwerte"][] = number_format(round($oDbReihenTupel[$i],1), 1);
		    $oDbAbfragenResultateTag["minwerte"][] = number_format(round($oDbReihenTupel[$i+1],1), 1);
		    $oDbAbfragenResultateTag["maxwerte"][] = number_format(round($oDbReihenTupel[$i+2],1), 1);
		    //$oDbAbfragenResultateTag["messzeit"][] = $tag.". ".$oTitelMonat[$iMonatIdWert-1];
		    $oDbAbfragenResultateTag["messzeit"][] = $oDbReihenTupel[$i+3].". ".$oTitelMonat[$iMonatIdWert-1];
			if ($bGlobalDebug){
		       echo "-act-".$oDbAbfragenResultateTag["avgwerte"][$tag-1]." ";
		       echo"<br></br>";
		    }
		    $tag++;
		}
	}
	

	
}else{
	$iSensorIdWert_avg_j = "m_j_avg_s".$iSensorIdWert;
	$iSensorIdWert_min_j = "m_j_min_s".$iSensorIdWert;
	$iSensorIdWert_max_j = "m_j_max_s".$iSensorIdWert;

	$iSensorIdWert_avg_m = "m_m_avg_s".$iSensorIdWert;
	$iSensorIdWert_min_m = "m_m_min_s".$iSensorIdWert;
	$iSensorIdWert_max_m = "m_m_max_s".$iSensorIdWert;

	$iSensorIdWert_avg_t = "m_t_avg_s".$iSensorIdWert;
	$iSensorIdWert_min_t = "m_t_min_s".$iSensorIdWert;
	$iSensorIdWert_max_t = "m_t_max_s".$iSensorIdWert;
	
	
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
	
	//Tageswerte
	$query = "select $iSensorIdWert_avg_t,$iSensorIdWert_min_t,$iSensorIdWert_max_t,m_t_tag from avg_tag_meteo_mv where m_t_jahr='$iJahrIdWert' and m_t_monat='$iMonatIdWert' order by m_t_tag asc";
	$dbResultat = $db_handler->db_query($query) or die($query);
	$tag=1;
	while($oDbReihenTupel = mysql_fetch_row($dbResultat)){
		for ($i=0; $i < $iAnzahlSensoren; $i++){
		    $oDbAbfragenResultateTag["avgwerte"][] = number_format(round($oDbReihenTupel[$i],1), 1);
		    $oDbAbfragenResultateTag["minwerte"][] = number_format(round($oDbReihenTupel[$i+1],1), 1);
		    $oDbAbfragenResultateTag["maxwerte"][] = number_format(round($oDbReihenTupel[$i+2],1), 1);
		    //$oDbAbfragenResultateTag["messzeit"][] = $tag.". ".$oTitelMonat[$iMonatIdWert-1];
		    $oDbAbfragenResultateTag["messzeit"][] = $oDbReihenTupel[$i+3].". ".$oTitelMonat[$iMonatIdWert-1];
			if ($bGlobalDebug){
		       echo "-act-".$oDbAbfragenResultateTag["avgwerte"][$tag-1]." ";
		       echo"<br></br>";
		    }
		    $tag++;
		}
	}

	//select sum(m_t_max_s3),m_t_monat from avg_tag_meteo_mv where m_t_jahr=2011 group by m_t_monat
	//-->Kumuliert nach Monat mit max-Tageswerte --> Kumulierter Monatswerte
if ($iSensorIdWert==3){//Rgensensor
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
	
		
}
	
	
}


?>