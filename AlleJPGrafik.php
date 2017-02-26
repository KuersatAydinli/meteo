<?php
include ("./MVIncludes/jpgraph.php");
include ("./MVIncludes/jpgraph_line.php");
include_once("./MVIncludes/db_class.php");
include("./MVIncludes/mvdates.php");

// wenn $bTest==1, dann kann diese datei ohne URL-Parameter aufgerufen werden
$bTest = false;
$myDebug=false;

//$bTest = true;
//$myDebug=true;

//$iAnzahlSensorenMeteo = 7;
$iAnzahlSensorenBoden = 4;


$iAnzahlSensoren_old = 11;
$iAnzahlSensorenMeteo_old = 7;
$iAnzahlSensoren_new = 22;
$iAnzahlSensorenMeteo = 18;


/////////////////////////
//Unterscheidung Meteo oder Boden
/////////////////////////



    //if (!isset($_GET['sURLSensor'])) {
    //    $_GET['sURLSensor'] = '';
    //} 

//erzeuge Datenbankverbindung
$db_handler = new db_class;

//erforderliche variablenzuweisungen, wenn $bTest==1
if($bTest){
	$iBildBreite=300;
	$iBildHoehe=350;
	$sDatumVon="23.10.2003 15:00";
	$sDatumBis="23.10.2003 22:00";
}

if($myDebug){
	echo 	"iBildBreite-2017: ".$iBildBreite;
	echo	"iBildHoehe-2017: ".$iBildHoehe;
}

// Array für die Grafiktitel (aus Datenbank)
$oTitel = array();
$oTitel[0] = '';
$oTitel[1] = '';
$oTitel[2] = '';
$oTitel[3] = '';
$oTitel[4] = '';
$oTitel[5] = '';
$oTitel[6] = '';

//boden
$oTitel[7] = '';
$oTitel[8] = '';
$oTitel[9] = '';
$oTitel[10] = '';



//echo "------------".$sURLSensor';
//hole alle sensorbezeichnungen und masseinheiten für grafiküberschrift
if($sURLSensor=="*"){
	//$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren");
	$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation in (0,1,2,3,4,5,6,11,14,16,18,19)");
	
	
	//echo "URLSensor_*";
}else{
	
	//if ($sURLSensor == "W10"){
	if (strlen($sURLSensor) > 2){
		$sUrlSensorID = (int)substr($sURLSensor,1,2);
		//echo "W10: ".$sUrlSensorID;
	}else{
		$sUrlSensorID = (int)substr($sURLSensor,1,1);
	}
	
	//echo "SensorId: ".$sUrlSensorID;
	//echo $iAnzahlSensorenMeteo;
	
	if (((int)$sUrlSensorID > (int)$iAnzahlSensorenMeteo_old) && ((int)$sUrlSensorID < (int)$iAnzahlSensoren_old)   ){
		//echo 'Bodensensor 2_: '.$sUrlSensorID;
		$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation = '$sUrlSensorID'");
	}else{
		$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation = '$sUrlSensorID'");	
	}
	
}
//dann schreibe alle sensorbezeichnungen und masseinheiten in das Array für die Grafiktitel
$k=0;
while($oDbReihenTupel = mysql_fetch_row($dbResultat)) { 
	$oTitel[$k]=$oDbReihenTupel[0]." ".$oDbReihenTupel[1];
	//echo $k."---".$oDbReihenTupel[0]."---",$oDbReihenTupel[1]."---".$oTitel[$k]; 
	$k++;
} 

$oKurvenfarben = array("blue","darkgreen","darkblue","black","darkorchid4","deeppink4","darkred","blue","darkgreen","darkblue","black","yellow");



//von und bis prüfen ob ein richtiges Datum ist
if (vergleicheVonBis(gibZeitStempel($sDatumVon),gibZeitStempel($sDatumBis))) {
	//echo "Daten ok";		
} else {
	echo " Startdatum muss vor dem Enddatum liegen! ";		
}


//$sDatumVon und bis auf mysqlformat stellen, für select
$sDatumVon = gibMysqlDatum($sDatumVon);
$sDatumBis = gibMysqlDatum($sDatumBis);

if($myDebug){
	echo "Von vor select: ".$sDatumVon;
	echo "Bis vor select: ".$sDatumBis;
}




//hole alle werte für den gegebenen zeitraum aus datenbank 
if($sURLSensor=="*"){
	$dbResultat = $db_handler->db_query("select * from Messwerte where Messzeit between '$sDatumVon' and '$sDatumBis' order by Messzeit");
}else{
	//echo $sUrlSensorID;
	//echo $iAnzahlSensorenMeteo;
	//echo $iAnzahlSensoren;
	
	if (((int)$sUrlSensorID > (int)$iAnzahlSensorenMeteo_old-1) && ((int)$sUrlSensorID < (int)$iAnzahlSensoren_old)){
		//echo 'Bodensensor: '.$sUrlSensorID;
		
		$sURLSensor = "W".($sUrlSensorID-$iAnzahlSensorenMeteo_old);
		
		//echo 'sURLSensor: '.$sURLSensor;
		$dbResultat = $db_handler->db_query("select Messzeit,$sURLSensor from Bodenmesswerte where Messzeit between '$sDatumVon' and '$sDatumBis' order by Messzeit");	
	}else{
		//echo 'Meteosensor: '.$sUrlSensorID;
		$dbResultat = $db_handler->db_query("select Messzeit,$sURLSensor from Messwerte where Messzeit between '$sDatumVon' and '$sDatumBis' order by Messzeit");		
	}
	
}
//hole werte für y-achse aus db
//$dbResultat = $db_handler->db_query("select W0,W1,W2,W3,W4,W5,W6,Messzeit from Messwerte where Messzeit>'$sDatumVon' and Messzeit<'$sDatumBis'");





//hole anzahl stützpunkte anhand hit rows
$iAnzahlWerte = mysql_num_rows($dbResultat);

//echo "iAnzahlWerte : ".$iAnzahlWerte; 
//echo "iBildBreite : ".$iBildBreite;

if($iAnzahlWerte<2){
	echo " Es sind keine Datensätze für diesen Zeitraum vorhanden! ";
	exit;
}

//Xstep berechnen
$iXSchrittPixelAnzahl = 1;
if($iAnzahlWerte<$iBildBreite){
	$iXSchrittPixelAnzahl = $iBildBreite / $iAnzahlWerte;
}
//iAnzahlWerteProXSchritt berechnen, ceil() rundet auf nächste ganze zahl
$iAnzahlWerteProXSchritt = 1;
if($iAnzahlWerte>$iBildBreite){
	$iAnzahlWerteProXSchritt = ceil($iAnzahlWerte / $iBildBreite);
}

if($myDebug){
	echo "Breite: ".$iBildBreite;
	echo "Hoehe: ".$iBildHoehe;
	echo "Sensor: ".$sURLSensor;
	echo "Von: ".$sDatumVon;
	echo "Bis: ".$sDatumBis;
	echo "XStep: ".$iXSchrittPixelAnzahl;
	echo "iAnzahlWerteProXSchritt: ".$iAnzahlWerteProXSchritt;
	echo "Anzahl Datensätze".$iAnzahlWerte;
}


if($sURLSensor!="*"){
	$sBildMapURL=array();
	$sBildMapBeschriftung=array();
}

//zu abspeichernder aktueller ywert
for ($i=0; $i < $iAnzahlSensoren; $i++) {
	$oYDaten[$i] = array();
	$oXDaten[$i] = array();
	
}

$iAnzWerteXSchritte = 0;
$anzahl=0;
$oSensorYWerte = array();
$oSensorYWerte[0] = 0;
$oSensorYWerte[1] = 0;
$oSensorYWerte[2] = 0;
$oSensorYWerte[3] = 0;
$oSensorYWerte[4] = 0;
$oSensorYWerte[5] = 0;
$oSensorYWerte[6] = 0;

//Boden
$oSensorYWerte[7] = 0; //Temperatur dach 11
$oSensorYWerte[8] = 0; //windspitze		14
$oSensorYWerte[9] = 0; //regenstärke	16
$oSensorYWerte[10] = 0; //barometrischer Druck 18
$oSensorYWerte[11] = 0; //Taupunkt 19



	for ($iAnzahlWerteIndex = 0; $iAnzahlWerteIndex < $iAnzahlWerte; $iAnzahlWerteIndex++){//$i=0; $i < $iAnzahlSensoren; $i++
		if ( $oDbReihenTupel = mysql_fetch_array($dbResultat) ){
			for ($iAnzahlSensorenIndex=0; $iAnzahlSensorenIndex < ($iAnzahlSensoren + $iAnzahlSensoren_additional); $iAnzahlSensorenIndex++) {//$iAnzahlWerteIndex = 0; $iAnzahlWerteIndex < $iAnzahlWerte; $iAnzahlWerteIndex++		   
				//$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$iAnzahlSensorenIndex + 1];
				
				
				//$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$iAnzahlSensorenIndex + 1];
				if ($iAnzahlSensorenIndex == 7){
					$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$isensor_id_add1];
				}elseif ($iAnzahlSensorenIndex == 8){
					$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$isensor_id_add2];
				}elseif ($iAnzahlSensorenIndex == 9){
					$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$isensor_id_add3];
				}elseif ($iAnzahlSensorenIndex == 10){
					$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$isensor_id_add4];
				}elseif ($iAnzahlSensorenIndex == 11){
					$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$isensor_id_add5];
				}else{
					$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$iAnzahlSensorenIndex + 1];
				}
			
				
				
				//$oSensorYWerte[] = $oSensorYWerte[$iAnzahlSensorenIndex];
		        if ($myDebug){
		           echo "-ywert-".$oSensorYWerte[$iAnzahlSensorenIndex];
		           echo "-ywertDB-".$oDbReihenTupel[$iAnzahlSensorenIndex];
		           echo "-ywertDB+1-".$oDbReihenTupel[$iAnzahlSensorenIndex + 1];
		           echo "-sensorIndex-".$iAnzahlSensorenIndex;
		           echo "anzahl: ".$anzahl++;
		           echo"<br></br>";
		        }
		    }
		}	    
		
		
		
		
		$iAnzWerteXSchritte++;
		if($iAnzWerteXSchritte == $iAnzahlWerteProXSchritt){
			for ($iAnzahlSensorenIndex1=0; $iAnzahlSensorenIndex1 < ($iAnzahlSensoren + $iAnzahlSensoren_additional); $iAnzahlSensorenIndex1++){
				$oYDaten[$iAnzahlSensorenIndex1][] = $oSensorYWerte[$iAnzahlSensorenIndex1] / $iAnzahlWerteProXSchritt;
				//$x += $iXSchrittPixelAnzahl; 
				$oXDaten[$iAnzahlSensorenIndex1][] = $oDbReihenTupel[0];
				$oSensorYWerte[$iAnzahlSensorenIndex1] = 0 ;
				if($sURLSensor!="*"){
					// Client side image map targets (map)
					//$sBildMapURL[]=$sMapURL;	
					//$sBildMapURL[] = $REQUEST_URI;
					$sBildMapURL[] = "#";
					// Strings to put as "alts" (and "title" value) (map)
					$sBildMapBeschriftung[]=$oDbReihenTupel[0]." : Wert=%0.2f";								
				}

		       	if ($myDebug){
		       	   echo "-anz-".$iAnzWerteXSchritte;
		       	   echo "-iAnzahlWerteProXSchritt-".$iAnzahlWerteProXSchritt;
		           echo "-anzahl gleich XGRUP".$oSensorYWerte[$iAnzahlSensorenIndex1] / $iAnzahlWerteProXSchritt;
		           echo"<br></br>";
		        }

			}

		$iAnzWerteXSchritte = 0 ;
		}	
	}

//Bereite X-Bezeichnungen vor
include ('./XLabelHandler.php');

			if(isset($_POST['iXBezIntervallWert'])){
				$iXBezIntervall = ceil($_POST['iXBezIntervallWert'] / $iAnzahlWerteProXSchritt); 
			}
			elseif(isset($_GET['iXBezIntervallWert'])){
				$iXBezIntervall = ceil($_GET['iXBezIntervallWert'] / $iAnzahlWerteProXSchritt);
		    }
		    

//$iXBezIntervall = ceil($iXBezIntervallWert / $iAnzahlWerteProXSchritt);

//XBezeichungen
$oXBezeichnungen = gibXLabelFormat($oXDaten[0],$iBildBreite);

$iBildHoehe += gibButtonSpace();

for ($iAnzahlSensorenIndex=0; $iAnzahlSensorenIndex < $iAnzahlSensoren; $iAnzahlSensorenIndex++) {
	// Grafik generieren und Grafiktyp festlegen
	$oGraph = new Graph($iBildBreite, $iBildHoehe ,"auto");    
	$oGraph->SetScale("textlin");	
	// Die Liniengrafik generieren 
	
	
	// Show the gridlines
	$oGraph->ygrid->Show(true,false);
	$oGraph->xgrid->Show(true,false);	
	
	//echo "LinePlot iAnzahlSensorenIndex: ".$iAnzahlSensorenIndex;
	//echo "LinePlot add ydata: ".$oYDaten[0];
	$oLinienKurve=new LinePlot($oYDaten[$iAnzahlSensorenIndex]);
	// Die Linien zu der Grafik hinzufügen
	//$oLinienKurve->SetWeight(12);
	$oGraph->Add($oLinienKurve);


if($sURLSensor=="*"){
	if($iAnzahlSensorenIndex==0){ //temperaturen + dach + taupunkt
		//echo "LinePlot add ydata".$oYDaten[0];
		$oLinienKurve_Add=new LinePlot($oYDaten[$iAnzahlSensorenIndex+7]);
		$oLinienKurve_Add_Tau=new LinePlot($oYDaten[$iAnzahlSensorenIndex+11]);
		// Die Linien zu der Grafik hinzufügen
		//$oLinienKurve->SetWeight(12);

		//$oGraph->SetY2Scale("lin");
		//$oGraph->AddY2($oLinienKurve_Add);
		$oGraph->Add($oLinienKurve_Add);
		$oGraph->Add($oLinienKurve_Add_Tau);
		
		$oLinienKurve_Add->SetWeight(12);
		$oLinienKurve_Add->SetColor("darkgreen");
		
		$oLinienKurve_Add_Tau->SetWeight(12);
		$oLinienKurve_Add_Tau->SetColor("yellow");
		
		//$oGraph->title->Set($oTitel[$iAnzahlSensorenIndex]."---".$oTitel[$iAnzahlSensorenIndex+7]);
		//$oLinienKurve->SetLegend($oTitel[$iAnzahlSensorenIndex]);
		//$oLinienKurve_Add->SetLegend($oTitel[$iAnzahlSensorenIndex+7]);
		$oLinienKurve->SetLegend('Temperatur Schatten');
		$oLinienKurve_Add->SetLegend('Dach');
		$oLinienKurve_Add_Tau->SetLegend($oTitel[$iAnzahlSensorenIndex+11]);
		
		$oGraph->img->SetMargin(60,50,20,gibButtonSpace());
		
		//$oGraph->y2axis->SetColor("darkgreen");
		//$oGraph->y2axis->SetWeight(2);
		
		$oGraph->legend->Pos(0.5,0.0,"center","top");
		$oLinienKurve->SetColor("red");
		
		//$oSensorYWerte[7] = 0; //Temperatur dach 11
		//$oSensorYWerte[8] = 0; //windspitze		14
		//$oSensorYWerte[9] = 0; //regenstärke	16
		//$oSensorYWerte[10] = 0; //barometrischer Druck 18	
	
	
	}elseif($iAnzahlSensorenIndex==1){ //druck + barometrischer
			//echo "LinePlot add ydata".$oYDaten[0];
		$oLinienKurve_Add=new LinePlot($oYDaten[$iAnzahlSensorenIndex+9]);
			// Die Linien zu der Grafik hinzufügen
			//$oLinienKurve->SetWeight(12);
		
		//$oGraph->SetY2Scale("lin");
		//$oGraph->AddY2($oLinienKurve_Add);
		$oGraph->Add($oLinienKurve_Add);
		
		$oLinienKurve_Add->SetWeight(22);
		$oLinienKurve_Add->SetColor("darkgreen");
		//$oGraph->title->Set($oTitel[$iAnzahlSensorenIndex]."---".$oTitel[$iAnzahlSensorenIndex+9]);
			
		$oLinienKurve->SetLegend($oTitel[$iAnzahlSensorenIndex]);
		$oLinienKurve_Add->SetLegend($oTitel[$iAnzahlSensorenIndex+9]);
		$oGraph->img->SetMargin(60,50,20,gibButtonSpace());
		
		//$oGraph->y2axis->SetColor("darkgreen");
		//$oGraph->y2axis->SetWeight(2);
		
		$oGraph->legend->Pos(0.5,0.0,"center","top");
		$oLinienKurve->SetColor("red");
				
	}elseif($iAnzahlSensorenIndex==3){ //regen + srärke
		//echo "LinePlot add ydata".$oYDaten[0];
		$oLinienKurve_Add=new LinePlot($oYDaten[$iAnzahlSensorenIndex+6]);
		// Die Linien zu der Grafik hinzufügen
		//$oLinienKurve->SetWeight(12);
		
		//$oGraph->SetY2Scale("lin");
		//$oGraph->AddY2($oLinienKurve_Add);
		$oGraph->Add($oLinienKurve_Add);

		$oLinienKurve_Add->SetWeight(12);
		$oLinienKurve_Add->SetColor("darkgreen");
		//$oGraph->title->Set($oTitel[$iAnzahlSensorenIndex]."---".$oTitel[$iAnzahlSensorenIndex+6]);
		$oLinienKurve->SetLegend($oTitel[$iAnzahlSensorenIndex]);
		$oLinienKurve_Add->SetLegend($oTitel[$iAnzahlSensorenIndex+6]);
		$oGraph->img->SetMargin(60,50,20,gibButtonSpace());
		
		//$oGraph->y2axis->SetColor("darkgreen");
		//$oGraph->y2axis->SetWeight(2);
		
		$oGraph->legend->Pos(0.5,0.0,"center","top");
		$oLinienKurve->SetColor("red");
		
	}elseif($iAnzahlSensorenIndex==5){ //wind + spitze
		//echo "LinePlot add ydata".$oYDaten[0];
		$oLinienKurve_Add=new LinePlot($oYDaten[$iAnzahlSensorenIndex+3]);
		// Die Linien zu der Grafik hinzufügen
		//$oLinienKurve->SetWeight(12);
		
		//$oGraph->SetY2Scale("lin");
		//$oGraph->AddY2($oLinienKurve_Add);
		$oGraph->Add($oLinienKurve_Add);
		
		$oLinienKurve_Add->SetWeight(12);
		$oLinienKurve_Add->SetColor("darkgreen");
		//$oGraph->title->Set($oTitel[$iAnzahlSensorenIndex]."---".$oTitel[$iAnzahlSensorenIndex+3] );
		$oLinienKurve->SetLegend($oTitel[$iAnzahlSensorenIndex]);
		$oLinienKurve_Add->SetLegend($oTitel[$iAnzahlSensorenIndex+3]);
		$oGraph->img->SetMargin(60,50,20,gibButtonSpace());
		
		//$oGraph->y2axis->SetColor("darkgreen");
		//$oGraph->y2axis->SetWeight(2);
		
		$oGraph->legend->Pos(0.5,0.0,"center","top");
		$oLinienKurve->SetColor("red");
		
	}else{
		$oGraph->title->Set($oTitel[$iAnzahlSensorenIndex]);
		$oGraph->img->SetMargin(60,20,20,gibButtonSpace());
		$oLinienKurve->SetColor($oKurvenfarben[$iAnzahlSensorenIndex]);
		
		
	}
}else{
	$oGraph->title->Set($oTitel[$iAnzahlSensorenIndex]);
	$oGraph->img->SetMargin(60,20,20,gibButtonSpace());
	//$oLinienKurve->SetColor($oKurvenfarben[$iAnzahlSensorenIndex]);
	
	$oLinienKurve->SetColor($kFarbe);
	
	
	
}//Einzelsensor

	
	
	
	
	// Grafik Formatieren
//	$oGraph->xaxis->SetTickLabels(gibXLabelFormat($oXDaten[$iAnzahlSensorenIndex],$iBildBreite)); 
	$oGraph->xaxis->SetTickLabels($oXBezeichnungen); 
	$oGraph->xaxis->SetTextLabelInterval($iXBezIntervall);
	$oGraph->xaxis->SetFont(FF_FONT1,gibXLabelGroesse()); 
	$oGraph->xaxis->SetLabelAngle(90);
	$oGraph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$oGraph->xaxis->HideTicks();
	//erzeuge x-tiks label
	$oGraph->xaxis->SetFont(FF_FONT1,$iXBezGroesse); 

	

	// Hide the tick marks
	
	//27.12.2016
	//$oGraph->title->Set($oTitel[$iAnzahlSensorenIndex]);
	
	$oGraph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);	
	//$oLinienKurve->SetLineWeight(100);
		
	

	if($sURLSensor!="*"){
		// Use diamonds as markerss (map)
		$oLinienKurve->mark->SetType(MARK_DIAMOND);
		$oLinienKurve->mark->SetWidth(5);	
		$oLinienKurve->mark->SetFillColor($kFarbe);			
		// Set the scatter plot image map targets
		$oLinienKurve->SetCSIMTargets($sBildMapURL,$sBildMapBeschriftung);								
	}

	$oGraph->yaxis->SetColor("red");
	$oGraph->yaxis->SetWeight(2);
	$oGraph->SetShadow();	
	$oLinienKurve->SetWeight(12);
	
	
	
	
	
	//create reference line
	//$oGraph->AddLine(new PlotLine(HORIZONTAL, $refLinie, "blue",2)); 
	//$oGraph->AddLine(new PlotLine(HORIZONTAL, $refLinie, "blue",2));
	$oGraph->xaxis->SetPos('min'); // X-Achsenbeschriftungen immer unten anzeigen 
	
	
	//echo "iAktuelleZeit: ".$iAktuelleZeit;
	$fileName = "./kurven/kurve".$iAnzahlSensorenIndex."_".$iAktuelleZeit.".png";
	//echo "FileName:".$fileName;
	//Grafik abspeichern
	//@unlink($fileName);

	
	
	//@unlink("./kurven/kurve$iAnzahlSensorenIndex.png");
	//$oGraph->Stroke("./kurven/kurve$iAnzahlSensorenIndex.png");
	
	$oGraph->Stroke($fileName);
	
	//@unlink("./kurven/kurve$iAnzahlSensorenIndex.png");
	if($sURLSensor!="*"){
		echo $oGraph->GetHTMLImageMap("mvgraphmap");		
	}
}
?>
