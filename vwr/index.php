<?
  
  //include_once("../web/xampp/htdocs/meteo/MVIncludes/db_class.php");
  //include_once("../web/xampp/htdocs/meteo/MVIncludes/mvdates.php");
  
  include_once("../MVIncludes/db_class.php");
  include_once("../MVIncludes/mvdates.php");

  include_once('Template.php');  
	
/**/   
  //header informationen für den browser: dateityp festlegen ('.xls') 
header("Content-Type: text/plain; charset='iso-8859-1'"); 
header("Content-Disposition: attachment; filename=vwr.txt"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
 
  
  $tmp = new Template('vwr.tpl');  
  $db_handler = new db_class;
  $iAnzahlSensoren = 7;
  global $oDbReihenTupel0; $oDbAbfragenResultate;
  $sLetztesMesswertDatum;
  
  
  //testflag
  $bTest = false;
  //$bTest = true;

  $heute = Date("Y-m-d");
  
  //für testzwecke
  if($bTest){
  	echo "Testlauf von Globemail!";
  	$heute = "2006-08-14";
  }



//hole letzte verfügbare messzeit für aktuelle werte
$dbResultat0 = $db_handler->db_query("select MAX(Messzeit)from Messwerte");
if ($oDbReihenTupel0 =mysql_fetch_array($dbResultat0)){
	$sLetztesMesswertDatum = $oDbReihenTupel0[0];
}else{
	//Fehlerseite einblenden
	echo "Kein aktueller Datensatz";
	return;
}


//hole alle Werte mit der letzten Messzeit
$query = "select W0,W1,W2,W3,W4,W5,W6 from Messwerte where Messzeit='$sLetztesMesswertDatum'";
$dbResultat = $db_handler->db_query($query) or die($query);
if ($oDbReihenTupel = mysql_fetch_array($dbResultat) ){
	for ($i=0; $i < $iAnzahlSensoren; $i++){
	    //$oDbAbfragenResultate["aktwerte"][$i] = round($oDbReihenTupel[$i],1);
	    $oDbAbfragenResultate["aktwerte"][$i] = number_format($oDbReihenTupel[$i], 1, ",", "");

	    if ($bTest){
	       //echo "-act-".$oDbAbfragenResultate["aktwerte"][$i]." ";
	       //echo"<br></br>";
	    }
	}
}

//minimale und maximale messwerte für die letzte Messzeit ($sLetztesMesswertDatum) berechnen
$oDatumZeit = split(" ",$sLetztesMesswertDatum);
$oDatumTeile = split("-",$oDatumZeit[0]);
$tagesanfang = date("Y-m-d H:i:s", mktime(0,0,0,$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0]));
$tagesende   = date("Y-m-d H:i:s", mktime(23,59,59,$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0] ));


/*
$query = "select MIN(W0), MAX(W0)";
for($i=1; $i < $iAnzahlSensoren; $i++){
	$query = $query.", MIN(W$i), MAX(W$i)";
}
*/


//$query = $query." from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende'";
//$query = "select MIN(W0),Messzeit from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende' GROUP BY Messzeit";

//$query = "SELECT Messzeit,W0 FROM   Messwerte WHERE W0=(select MIN(W0) from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende')";
//$query = "select MIN(W0),Messzeit from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende' Group BY Messzeit";

//echo $query;
/*
SELECT article, dealer, price
FROM   shop s1
WHERE  price=(SELECT MAX(s2.price)
              FROM shop s2
              WHERE s1.article = s2.article);
*/

//echo $query."<br>";
//$dbResultat = $db_handler->db_query($query) or die($query);

/*
if( $oDbReihenTupel = mysql_fetch_array($dbResultat) ){
	for($i=0; $i < $iAnzahlSensoren; $i++){
    	//$oDbAbfragenResultate["minwerte"][$i] = round($oDbReihenTupel[$i*2],1);	 
    	//$oDbAbfragenResultate["maxwerte"][$i] = round($oDbReihenTupel[$i*2+1],1);
    	$oDbAbfragenResultate["minwerte"][$i] = number_format($oDbReihenTupel[$i*2],1, ",", "");	 
    	$oDbAbfragenResultate["maxwerte"][$i] = number_format($oDbReihenTupel[$i*2+1],1, ",", "");
      
	    if ($bTest){
	       echo "-min: ".$oDbAbfragenResultate["minwerte"][$i];
	       echo " -max: ".$oDbAbfragenResultate["maxwerte"][$i];
	       //echo " -min-Datum".$oDbReihenTupel[$i*3];
	       //echo " -max-".$oDbReihenTupel[$i*3+1];
	       //echo"<br></br>";
	       print "\n";
	    }
	}	  
}
*/

//$query7 = "select MAX(W3),Messzeit from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende' Group BY Messzeit";

  for($i=0; $i < $iAnzahlSensoren; $i++){
    
    //$query = "select MIN(W$i),Messzeit from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende' Group BY Messzeit";
    $query = "SELECT Messzeit,W$i FROM   Messwerte WHERE W$i=(select MIN(W$i) from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende')and '$tagesanfang' <= Messzeit ORDER BY Messzeit DESC LIMIT 1";
    //echo $query;
    $dbResultat = $db_handler->db_query($query) or die($query);
    
  	while ($row = mysql_fetch_array($dbResultat)) {
      	//$oDbAbfragenResultate["minwerte"][$i] = round($oDbReihenTupel[$i*2],1);	 
      	//$oDbAbfragenResultate["maxwerte"][$i] = round($oDbReihenTupel[$i*2+1],1);
      	//$oDbAbfragenResultate["minwerte"][$i] = number_format($oDbReihenTupel[$i*2],1, ",", "");	 
      	//$oDbAbfragenResultate["maxwerte"][$i] = number_format($oDbReihenTupel[$i*2+1],1, ",", "");
        $oDbAbfragenResultate["minwerte"][$i] = number_format($row[1],1, ",", "");
        $oDbAbfragenResultate["minwerteDatum"][$i] = $row[0];
  	    if ($bTest){
  	      print "\n";
  	      print "\n";
          printf ("Min-Wert$i: %s  Datum$i: %s fgfg: %s", $row[0], $row[1],$row[2]);
          print "\n";
  	    }
  	}
  	mysql_free_result($dbResultat);
  }	  


  for($i=0; $i < $iAnzahlSensoren; $i++){
    
    //$query = "select MIN(W$i),Messzeit from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende' Group BY Messzeit";
    $query = "SELECT Messzeit,W$i FROM   Messwerte WHERE W$i=(select MAX(W$i) from Messwerte where '$tagesanfang' <= Messzeit and Messzeit <= '$tagesende')and '$tagesanfang' <= Messzeit ORDER BY Messzeit DESC LIMIT 1";
    //echo $query;
    $dbResultat = $db_handler->db_query($query) or die($query);
    
  	if ($row = mysql_fetch_array($dbResultat)) {
      	//$oDbAbfragenResultate["minwerte"][$i] = round($oDbReihenTupel[$i*2],1);	 
      	//$oDbAbfragenResultate["maxwerte"][$i] = round($oDbReihenTupel[$i*2+1],1);
      	//$oDbAbfragenResultate["minwerte"][$i] = number_format($oDbReihenTupel[$i*2],1, ",", "");	 
      	//$oDbAbfragenResultate["maxwerte"][$i] = number_format($oDbReihenTupel[$i*2+1],1, ",", "");
        $oDbAbfragenResultate["maxwerte"][$i] = number_format($row[1],1, ",", "");
        $oDbAbfragenResultate["maxwerteDatum"][$i] = $row[0];
        
  	    if ($bTest){
  	      print "\n";
  	      print "\n";
          printf ("Max-Wert$i: %s  Datum$i: %s fgfg: %s", $row[0], $row[1],$row[2]);
          print "\n";
  	    }
  	}
  	mysql_free_result($dbResultat);
  }	  




/*
*
*
*
*<Temperatur>					{Temperatur}
*<Max. Temperatur>				{Max_Temperatur}
*<Min. Temperatur>				{Min_Temperatur}
*<Tageszeit der Max. Temperatur>			{Time_Max_Temperatur}
*<Tageszeit der Min. Temperatur>			{Time_Min_Temperatur}
*
*
*
*/

/*

function writeMyName()
  {
  echo "Kai Jim Refsnes";
  }

0/360 = N
45 = NO
90= 0
135 =SO
180 = S
225=SW
270=W
315=NW



*/
function mapWindroseGradToText($value){
  //echo "mapWindroseGradToText: ".$value;
  if ($value >= 348.75 AND $value <= 360){
    //echo "value=N";
    return "N";
  }elseif ($value >= 0 and $value < 11.25  ){
    //echo "value=N";
    return "N";
  }elseif ($value >= 11.25 and $value < 33.75  ){
    //echo "value=NNO";
    return "NNO";
  }elseif ($value >= 33.75 and $value < 56.25  ){
    //echo "value=NO";
    return "NO";
  }elseif ($value >= 56.25 and $value < 78.75  ){
    //echo "value=ONO";
    return "ONO";
  }elseif ($value >= 78.75 and $value < 101.25  ){
    //echo "value=O";
    return "O";
  }elseif ($value >= 101.25 and $value < 123.75  ){
    //echo "value=OSO";
    return "OSO";
  }elseif ($value >= 123.75 and $value < 146.25  ){
    //echo "value=SO";
    return "SO";
  }elseif ($value >= 146.25 and $value < 168.75  ){
    //echo "value=SSO";
    return "SSO";
  }elseif ($value >= 168.75 and $value < 191.25  ){
    //echo "value=S";
    return "S";
  }elseif ($value >= 191.25 and $value < 213.75  ){
    //echo "value=SSW";
    return "SSW";
  }elseif ($value >= 213.75 and $value < 236.25  ){
    //echo "value=SW";
    return "SW";
  }elseif ($value >= 236.25 and $value < 258.75  ){
    //echo "value=WSW";
    return "WSW";
  }elseif ($value >= 258.75 and $value < 281.25  ){
    //echo "value=W";
    return "W";
  }elseif ($value >= 281.25 and $value < 303.75  ){
    //echo "value=WNW";
    return "WNW";
  }elseif ($value >= 303.75 and $value < 326.25  ){
    //echo "value=NW";
    return "NW";
  }elseif ($value >= 326.25 and $value < 348.75  ){
    //echo "value=NNW";
    return "NNW";
  }
  
}


  function get24HRegenmenge(){ 
    $heute = Date("Y-m-d H:i:s");
    $db_handler = new db_class;
    //echo $heute ;
    //$heute = "2006-08-13 19:27:00";
    //für testzwecke
    if($bTest){
    	echo "Testlauf von Globemail!";
    	$heute = "2006-08-13 19:27:00";
    }
    
    $oDatumZeit = split(" ",$heute);
    $oZeitTeile = split(":",$oDatumZeit[1]);
    //$tmp->setContent('Time_Min_Temperatur', "$oZeitTeile[0]:$oZeitTeile[1]");

    
    $gestern = Date("Y-m-d",mktime(0,0,0,Date("m"),Date("d")-1,Date("Y")));
    //$gestern = "2006-08-12";
    
    $gesternmittag = $gestern." $oZeitTeile[0]:$oZeitTeile[1]:$oZeitTeile[2]";
    
    
    //hole den maximalen regenwert zwischen gestern nachmittag und gestern mitternacht
    $gesternmitternacht = $gestern." 23:59:59"; 
    $sel = "select Max(W3) from Messwerte where Messzeit>='$gesternmittag' and Messzeit<='$gesternmitternacht'";
    //echo $sel."\n";
    $dbResultat = $db_handler->db_query($sel);
    if ($oDbReihenTupel =mysql_fetch_array($dbResultat)){
    	$regenWert = $oDbReihenTupel[0];
    	if($bGlobalDebug){
    		echo "maximale Niederschlagsmengen zw. $gesternmitternacht und  $gesternmitternacht : ".$regenWert."\n";
    	}
    }else{
    	echo "Kein Niederschlagswert von Gestern $gestern\n";
    }
    

    $dbResultat = $db_handler->db_query("select Min(W3) from Messwerte where Messzeit>='$gesternmittag' and Messzeit<='$gesternmitternacht'");
    if ($oDbReihenTupel =mysql_fetch_array($dbResultat)){
    	/*
      if ($regenWert ==$oDbReihenTupel[0]){
      }else{
      }
      */
      $regenWert -= $oDbReihenTupel[0];
      
    	if($bGlobalDebug){
    		echo "maximale Niederschlagsmengen zw. $gesternmitternacht und  $gesternmitternacht : ".$regenWert."\n";
    	}
    }else{
    	echo "Kein Niederschlagswert von Gestern $gestern\n";
    }
    
    //hole den maximalen regenwert zwischen mitternacht und heute mittag, und addiere den Wert zur gestrigen maximalen Wert dazu
    $dbResultat = $db_handler->db_query("select Max(W3) from Messwerte where Messzeit >'$gesternmitternacht' and Messzeit<='$heute'");
    if ($oDbReihenTupel =mysql_fetch_array($dbResultat)){
    	$regenWert += $oDbReihenTupel[0];
    	if($bGlobalDebug){
    		echo "maximale Niederschlagsmengen zw. $gesternmitternacht und  heutemittag am $aktTempWertDatum : ".$oDbReihenTupel[0]."\n";
    	}
    }else{
    	echo "Kein Niederschlagswert von Gestern $gestern\n";
    }
    
    //echo  "Regenwert 24h ".$regenWert;
    return $regenWert;

  }


  function getRegenrate($aktRegenmenge,$sLetztesMesswertDatum){
    $db_handler = new db_class;
    //$aktRegenmenge = $oDbAbfragenResultate["aktwerte"][3];
    
    $oDatumZeit = split(" ",$sLetztesMesswertDatum);
    $oDatumTeile = split("-",$oDatumZeit[0]);
    $oZeitTeile = split(":",$oDatumZeit[1]);
    $vorletztesDatum = date("Y-m-d H:i:s", mktime($oZeitTeile[0],$oZeitTeile[1]-15,$oZeitTeile[2],$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0]));
    //$tagesende   = date("Y-m-d H:i:s", mktime(23,59,59,$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0] ));
    
    $sel = "select W3,Messzeit from Messwerte where Messzeit>='$vorletztesDatum' and Messzeit<='$sLetztesMesswertDatum' ORDER BY Messzeit DESC LIMIT 2";
    //echo $sel."\n";
    //hole vorletzte Regenmenge
    $dbResultat = $db_handler->db_query($sel);
    if ($oDbReihenTupel =mysql_fetch_array($dbResultat)){
    	$letzteRegenmenge = $oDbReihenTupel[0];
    	if($bGlobalDebug){
    		echo "letzte Regenmenge: ".$oDbReihenTupel[0]."\n";
    		echo "Datum letzte Regenmenge : ".$oDbReihenTupel[1]."\n";
    	}
    }else{
    	echo "Keine letzte Regenmenge in getRegenrate()!\n";
    }
    
    if ($oDbReihenTupel =mysql_fetch_array($dbResultat)){
    	$vorletzteRegenmenge = $oDbReihenTupel[0];
    	if($bGlobalDebug){
    		echo "vorletzte Regenmenge: ".$oDbReihenTupel[0]."\n";
    		echo "Datum vorletzte Regenmenge: ".$oDbReihenTupel[1]."\n";
    	}
    }else{
    	echo "Keine vorletzte Regenmenge in getRegenrate()!\n";
    }
    /*else{
    	echo "Keine vorletzte Regenmenge in getRegenrate()!\n";
    }
    */
	if  ($letzteRegenmenge >= $vorletzteRegenmenge){	
		//echo	"ok";	
		return ($letzteRegenmenge - $vorletzteRegenmenge)*6;
	}else{
		return ($letzteRegenmenge)*6;
	}
    
  }



	$tmp->setContent('Temperatur', $oDbAbfragenResultate["aktwerte"][0]);  
	$tmp->setContent('Max_Temperatur', $oDbAbfragenResultate["maxwerte"][0]);    
	$tmp->setContent('Min_Temperatur', $oDbAbfragenResultate["minwerte"][0]);  
	
 	$tmp->setContent('Luftdruck', $oDbAbfragenResultate["aktwerte"][1]);  
	$tmp->setContent('Max_Luftdruck', $oDbAbfragenResultate["maxwerte"][1]);    
	$tmp->setContent('Min_Luftdruck', $oDbAbfragenResultate["minwerte"][1]);
	
	$tmp->setContent('Luftfeuchtigkeit', $oDbAbfragenResultate["aktwerte"][2]);  
	$tmp->setContent('Max_Luftfeuchtigkeit', $oDbAbfragenResultate["maxwerte"][2]);    
	$tmp->setContent('Min_Luftfeuchtigkeit', $oDbAbfragenResultate["minwerte"][2]);  
	
	$tmp->setContent('Windgeschwindigkeit', $oDbAbfragenResultate["aktwerte"][5]);  
	$tmp->setContent('Max_Windgeschwindigkeit', $oDbAbfragenResultate["maxwerte"][5]);    
  //AVG10_Windgeschwindigkeit --> da In der DB 10. Minutige Werte Abgespeichert werden, ist der Wert der gleiche wie der aktuelle
  $tmp->setContent('AVG10_Windgeschwindigkeit', $oDbAbfragenResultate["aktwerte"][5]);
  	
  $tmp->setContent('Windrichtung', mapWindroseGradToText($oDbAbfragenResultate["aktwerte"][6]));  
	
	$tmp->setContent('Tages_Regenmenge', $oDbAbfragenResultate["aktwerte"][3]);

  // 24H_Regenmenge --> vom Akltuellen Zeitpunkt 24 h zürük
  $tmp->setContent('24H_Regenmenge', number_format(get24HRegenmenge(),1, ",", ""));
  
   // Regenrate --> vom Akltuellen Wert den vorherigen Wert abziehen und die Diffrenz auf eine Stunde hochrechnen
  $tmp->setContent('Regenrate', number_format(getRegenrate($oDbAbfragenResultate["aktwerte"][3],$sLetztesMesswertDatum),1, ",", ""));
  
	//Min und Max Zeitens
	$oDatumZeit = split(" ",$oDbAbfragenResultate["minwerteDatum"][0]);
  $oZeitTeile = split(":",$oDatumZeit[1]);
  $tmp->setContent('Time_Min_Temperatur', "$oZeitTeile[0]:$oZeitTeile[1]");
  
 	$oDatumZeit = split(" ",$oDbAbfragenResultate["maxwerteDatum"][0]);
  $oZeitTeile = split(":",$oDatumZeit[1]);
  $tmp->setContent('Time_Max_Temperatur', "$oZeitTeile[0]:$oZeitTeile[1]");


	$oDatumZeit = split(" ",$oDbAbfragenResultate["minwerteDatum"][1]);
  $oZeitTeile = split(":",$oDatumZeit[1]);
  $tmp->setContent('Time_Min_Luftdruck', "$oZeitTeile[0]:$oZeitTeile[1]");
  
 	$oDatumZeit = split(" ",$oDbAbfragenResultate["maxwerteDatum"][1]);
  $oZeitTeile = split(":",$oDatumZeit[1]);
  $tmp->setContent('Time_Max_Luftdruck', "$oZeitTeile[0]:$oZeitTeile[1]");
  
  
  $oDatumZeit = split(" ",$oDbAbfragenResultate["minwerteDatum"][2]);
  $oZeitTeile = split(":",$oDatumZeit[1]);
  $tmp->setContent('Time_Min_Luftfeuchtigkeit', "$oZeitTeile[0]:$oZeitTeile[1]");
  
 	$oDatumZeit = split(" ",$oDbAbfragenResultate["maxwerteDatum"][2]);
  $oZeitTeile = split(":",$oDatumZeit[1]);
  $tmp->setContent('Time_Max_Luftfeuchtigkeit', "$oZeitTeile[0]:$oZeitTeile[1]");
	
	
 	$oDatumZeit = split(" ",$oDbAbfragenResultate["maxwerteDatum"][5]);
  $oZeitTeile = split(":",$oDatumZeit[1]);
  $tmp->setContent('Time_Max_Windgeschwindigkeit', "$oZeitTeile[0]:$oZeitTeile[1]");
  

	//nicht vorhandene/berechnete Werte mit "---" ersetzen
	  $tmp->setContent('Trend_Luftdruck', "---");
	  $tmp->setContent('Vorhersagen', "---");
	  $tmp->setContent('Sonnenaufgang', "---");
	  $tmp->setContent('Sonnenabgang', "---");
	  $tmp->setContent('Mondfase', "---");
	  $tmp->setContent('AVG5_Windgeschwindigkeit', "---");


/**/
  //mapWindroseGradToText(0);
  //mapWindroseGradToText(10);
  
	print $tmp->vorlage;
?>
