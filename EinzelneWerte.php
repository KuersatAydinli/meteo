<? 
include_once("./MVIncludes/db_class.php");
//echo date("h:i:s");
$db_handler = new db_class;

$iAnzahlSensoren_old = 11;
$iAnzahlSensorenMeteo_old = 7;
$iAnzahlSensoren_new = 22;
$iAnzahlSensorenMeteo = 18;

//0...6 MeteoBank1
//7...10 BodenBank1
//11...21 MeteoBank2

//$iAnzahlSensoren = 11;
//$iAnzahlSensorenMeteo = 7;


$iAnzahlSensorenBoden = 4;
global $oDbReihenTupel0; $oDbAbfragenResultate;
$sLetztesMesswertDatum;

//hole alle sensorbezeichnungen und masseinheiten
$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit,Identifikation from Sensoren");
//
while($oDbReihenTupel = mysql_fetch_row($dbResultat)) { 
	$oDbAbfragenResultate["sensorbez"][] = $oDbReihenTupel[0];	 
	$oDbAbfragenResultate["sensormass"][] = $oDbReihenTupel[1];
	$oDbAbfragenResultate["sensorident"][] = $oDbReihenTupel[2];
	//echo $oDbReihenTupel[0];
} 


//hole letzte verf�gbare messzeit f�r aktuelle werte
$dbResultat0 = $db_handler->db_query("select MAX(Messzeit)from Messwerte");
if ($oDbReihenTupel0 =mysql_fetch_array($dbResultat0)){
	$sLetztesMesswertDatum = $oDbReihenTupel0[0];
	//echo $oDbReihenTupel0[0];
}else{
	//Fehlerseite einblenden
	echo "Kein aktueller Datensatz";
	return;
}


//hole alle Werte mit der letzten Messzeit
$query = "select W0,W1,W2,W3,W4,W5,W6,W11,W12,W13,W14,W15,W16,W17,W18,W19,W20,W21 from Messwerte where Messzeit='$sLetztesMesswertDatum'";
$dbResultat = $db_handler->db_query($query) or die($query);
if ($oDbReihenTupel = mysql_fetch_array($dbResultat) ){
	for ($i=0; $i < $iAnzahlSensorenMeteo; $i++){
		if ($i>6){
			$sid=$i+4;
		}else{
			$sid=$i;
		
		}
		
		$oDbAbfragenResultate["aktwerte"][$sid] = round($oDbReihenTupel[$i],0);
	    if ($bGlobalDebug){
	       echo "-act-".$oDbAbfragenResultate["aktwerte"][$sid]." ";
	       echo"<br></br>";
	    }
	}
}


//hole Bodendaten dazu
$query = "select W0,W1,W2,W3 from Bodenmesswerte where Messzeit='$sLetztesMesswertDatum'";
$dbResultat = $db_handler->db_query($query) or die($query);
if ($oDbReihenTupel = mysql_fetch_array($dbResultat) ){
	for ($i=0; $i < $iAnzahlSensorenBoden; $i++){
	    $oDbAbfragenResultate["aktwerte"][$i+$iAnzahlSensorenMeteo_old] = round($oDbReihenTupel[$i],0);
	    if ($bGlobalDebug){
	       echo "-act-".$oDbAbfragenResultate["aktwerte"][$i+$iAnzahlSensorenMeteo_old]." ";
	       echo"<br></br>";
	    }
	}
}



//minimale und maximale messwerte f�r die letzte Messzeit ($sLetztesMesswertDatum) berechnen
$oDatumZeit = explode(" ",$sLetztesMesswertDatum);
$oDatumTeile = explode("-",$oDatumZeit[0]);
$tagesanfang = date("Y-m-d H:i:s", mktime(0,0,0,$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0]));
$tagesende   = date("Y-m-d H:i:s", mktime(23,59,59,$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0] ));

$query = "select MIN(W0), MAX(W0)";
for($i=1; $i < $iAnzahlSensorenMeteo; $i++){
	
	if ($i>6){
		$sid=$i+4;
	}else{
		$sid=$i;
		
	}
	$query = $query.", MIN(W$sid), MAX(W$sid)";
	
	
	
}
$query = $query." from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende'";

//echo $query."<br>";

$dbResultat = $db_handler->db_query($query) or die($query);
if( $oDbReihenTupel = mysql_fetch_array($dbResultat) ){
	for($i=0; $i < $iAnzahlSensorenMeteo; $i++){
		
		if ($i>6){
			$sid=$i+4;
		}else{
			$sid=$i;
		
		}
		
		
    	$oDbAbfragenResultate["minwerte"][$sid] = round($oDbReihenTupel[$i*2],0);	 
    	$oDbAbfragenResultate["maxwerte"][$sid] = round($oDbReihenTupel[$i*2+1],0);
	    if ($bGlobalDebug){
	       echo "-min-".$oDbAbfragenResultate["minwerte"][$sid];
	       echo " -max-".$oDbAbfragenResultate["maxwerte"][$sid];
	       echo"<br></br>";
	    }
	}	  
}




//hole Bodendaten
$query = "select MIN(W0), MAX(W0)";
for($i=1; $i < $iAnzahlSensorenBoden; $i++){
	$query = $query.", MIN(W$i), MAX(W$i)";
}
$query = $query." from Bodenmesswerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende'";

//echo $query."<br>";
$dbResultat = $db_handler->db_query($query) or die($query);
if( $oDbReihenTupel = mysql_fetch_array($dbResultat) ){
	for($i=0; $i < $iAnzahlSensorenBoden; $i++){
    	$oDbAbfragenResultate["minwerte"][$iAnzahlSensorenMeteo_old+$i] = round($oDbReihenTupel[$i*2],0);	 
    	$oDbAbfragenResultate["maxwerte"][$i+$iAnzahlSensorenMeteo_old] = round($oDbReihenTupel[$i*2+1],0);
	    if ($bGlobalDebug){
	       echo "-min-".$oDbAbfragenResultate["minwerte"][$i+$iAnzahlSensorenMeteo_old];
	       echo " -max-".$oDbAbfragenResultate["maxwerte"][$i+$iAnzahlSensorenMeteo_old];
	       echo"<br></br>";
	    }
	}	  
}



?>

<?
//formatiere datum 2003-11-25 15:23:27 zu 25.11.2003 15:23:27
	$oDatumZeit = explode(" ",$oDbReihenTupel0[0]);
	$oDatumTeile = explode("-",$oDatumZeit[0]);
	$oZeitTeile = explode(":",$oDatumZeit[1]);
	
	//if($bGlobalDebug){
		//echo "Bildschirmbreite von Einzelwerte aus: ".$_GET['iBrowserFensterBreite'];
		//echo "iBrowserFensterHoehe von Einzelwerte aus: ".$_GET['iBrowserFensterHoehe'];
	$iBildBreite=$_GET['iBrowserFensterBreite'];
	$iBildHoehe=$_GET['iBrowserFensterHoehe'];
	//}
	
	
?>


<div class="container">
    <div class="row row-content">
        <div class="well">
            <h2>Messwerte vom <? echo $oDatumTeile[2].".".$oDatumTeile[1].".".$oDatumTeile[0]; echo "&nbsp;"; echo "[". $oZeitTeile[0].":".$oZeitTeile[1].":".$oZeitTeile[2]." ]";?></h2>
        </div>
        <!--<h2>Messwerte vom <?/* echo $oDatumTeile[2].".".$oDatumTeile[1].".".$oDatumTeile[0]; echo "&nbsp;"; echo "[". $oZeitTeile[0].":".$oZeitTeile[1].":".$oZeitTeile[2]." ]";*/?></h2>-->
    </div>
    <table border="1" cellspacing="1" width=<? echo "100%"//echo $iBrowserFensterBreite;?>
    height="190" class="einzelWerteText table table-hover table-bordered table-responsive">
        <thead>
        <tr>
            <td width="25%" height="17" align="left"><b>Sensor</b></td>
            <td width="25%" height="17" align="center"><b>aktuell (<? echo "um ".$oZeitTeile[0].":".$oZeitTeile[1].":".$oZeitTeile[2]?>)</b></td>
            <td width="25%" height="17" align="center"><b>minimal (<? echo "seit  00:00:00"?>)</b></td>
            <td width="25%" height="17" align="center"><b>maximal (<? echo "seit  00:00:00"?>)</b></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Temperatur" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W0&ETitel=Temperatur%20in%20�C&kFarbe=red&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][0]." ".$oDbAbfragenResultate["sensormass"][0] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][0] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][0] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][0] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Temperaturdach" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W11&ETitel=Temperatur Dach%20in%20�C&kFarbe=darkgreen&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][11]." ".$oDbAbfragenResultate["sensormass"][11] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][11] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][11] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][11] ?></td>
        </tr>
        <!-- Taupunkt -->
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Taupunkt" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W19&ETitel=Taupunkt%20in%20�C&kFarbe=yellow&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][19]." ".$oDbAbfragenResultate["sensormass"][19] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][19] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][19] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][19] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Druck" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W18&ETitel=Barometrischer Luftdruck%20in%20hpa&kFarbe=darkgreen&refLinie=1000&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][18]." ".$oDbAbfragenResultate["sensormass"][18] ?></a></td>
            <td width="25%" height="19" align="center"> <?echo $oDbAbfragenResultate["aktwerte"][18] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][18] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][18] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Feuchte" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W2&ETitel=Feuchte%20in%20%&kFarbe=darkblue&refLinie=50&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][2]." ".$oDbAbfragenResultate["sensormass"][2] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][2] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][2] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][2] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Regen" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W3&ETitel=Regen%20in%20mm/m^2&kFarbe=red&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][3]." ".$oDbAbfragenResultate["sensormass"][3] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][3] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][3] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][3] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Regenmomentan" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W16&ETitel=Regenst�rke momentan%20in%20l/m^2&kFarbe=darkgreen&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][16]." ".$oDbAbfragenResultate["sensormass"][16] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][16] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][16] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][16] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Globalstrahlung" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W4&ETitel=Globalstrahlung%20in%20W/m^2&kFarbe=darkorchid4&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][4]." ".$oDbAbfragenResultate["sensormass"][4] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][4] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][4] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][4] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Windgeschwindigkeit" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W5&ETitel=Windgeschwindigkeit%20in%20km/h&kFarbe=red&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][5]." ".$oDbAbfragenResultate["sensormass"][5] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][5] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][5] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][5] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Windspitze" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W14&ETitel=Windspitze%20in%20km/h&kFarbe=darkgreen&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][14]." ".$oDbAbfragenResultate["sensormass"][14] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][14] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][14] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][14] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_Windrichtung" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W6&ETitel=Windrichtung%20in%20(0...360)�&kFarbe=darkred&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][6]." ".$oDbAbfragenResultate["sensormass"][6] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][6] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][6] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][6] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_boden5" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W7&ETitel=Bodentemperatur+-+5+cm+in+%C2%B0C&kFarbe=black&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][7]." ".$oDbAbfragenResultate["sensormass"][7] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][7] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][7] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][7] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_boden10" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W8&ETitel=Bodentemperatur+-+10+cm+in+%C2%B0C&kFarbe=black&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][8]." ".$oDbAbfragenResultate["sensormass"][8] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][8] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][8] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][8] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_boden50" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W9&ETitel=Bodentemperatur+-+50+cm+in+%C2%B0C&kFarbe=black&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][9]." ".$oDbAbfragenResultate["sensormass"][9] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][9] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][9] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][9] ?></td>
        </tr>
        <tr>
            <td width="25%" height="19" align="left">
                <a id="a_boden100" href="./Index.php?sNavigationsSeite=einzelnegrafik&fromIndex=yes&ESensor=W10&ETitel=Bodentemperatur+-+100+cm+in+%C2%B0C&kFarbe=black&refLinie=0&iXBezIntervallWert=6&oZeitIntervall=6">
                    <?echo $oDbAbfragenResultate["sensorbez"][10]." ".$oDbAbfragenResultate["sensormass"][10] ?></a></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["aktwerte"][10] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["minwerte"][10] ?></td>
            <td width="25%" height="19" align="center"><? echo $oDbAbfragenResultate["maxwerte"][10] ?></td>
        </tr>
        </tbody>
    </table>
</div>

