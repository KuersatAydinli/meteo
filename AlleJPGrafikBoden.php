<?php
include ("./MVIncludes/jpgraph.php");
include ("./MVIncludes/jpgraph_line.php");
include ("./MVIncludes/jpgraph_scatter.php");
include_once("./MVIncludes/db_class.php");
include("./MVIncludes/mvdates.php");

// wenn $bTest==1, dann kann diese datei ohne URL-Parameter aufgerufen werden
$bTest = false;
$myDebug=false;

$iAnzahlSensorenMeteo = 7;
$iAnzahlSensorenBoden = 4;
$iAnzahlSensorenBodenTemp = 5;


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


// Array für die Grafiktitel (aus Datenbank)
$oTitel = array();
$oTitel[0] = '';//Air
$oTitel[1] = '';//Boden 5
$oTitel[2] = '';//Boden 10
$oTitel[3] = '';//Boden 50
$oTitel[4] = '';//Boden 100


//echo "------------".$sURLSensor';
//hole alle sensorbezeichnungen und masseinheiten für grafiküberschrift
if($sURLSensor=="*"){
	$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit from Sensoren where Identifikation in (0,7,8,9,10)order by Identifikation");
	//echo "URLSensor_*";
}
//dann schreibe alle sensorbezeichnungen und masseinheiten in das Array für die Grafiktitel
$k=0;
$lv=0;
while($oDbReihenTupel = mysql_fetch_row($dbResultat)) { 
	$oTitel[$k]=$oDbReihenTupel[0]." ".$oDbReihenTupel[1];
	//echo $k."---".$oDbReihenTupel[0]."---",$oDbReihenTupel[1]."---".$oTitel[$k]; 
	$k++;
} 

//$oKurvenfarben = array("blue","darkgreen","darkblue","black","darkorchid4","deeppink4","darkred","blue","darkgreen","darkblue","black");
$oKurvenfarben = array("darkblue","red","#FF00CC","yellow","darkgreen");
$oMarkTypes = array(MARK_SQUARE,MARK_UTRIANGLE,MARK_DTRIANGLE,MARK_DIAMOND,MARK_CROSS,MARK_CIRCLE,MARK_CIRCLE,MARK_STAR,MARK_X);



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
	$dbResultat = $db_handler->db_query("select Messzeit,W0,W1,W2,W3 from Bodenmesswerte where Messzeit between '$sDatumVon' and '$sDatumBis' order by Messzeit");
}

//hole anzahl stützpunkte anhand hit rows
$iAnzahlWerte = mysql_num_rows($dbResultat);
if($iAnzahlWerte==0){
	echo " Es sind keine Datensätze für diesen Zeitraum vorhanden !";
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
for ($i=0; $i < $iAnzahlSensorenBodenTemp; $i++) {
	$oYDaten[$i] = array();
	$oXDaten[$i] = array();
	
}

$iAnzWerteXSchritte = 0;
$anzahl=0;
$anzahl2=0;
$oSensorYWerte = array();
$oSensorYWerte[0] = 0;
$oSensorYWerte[1] = 0;
$oSensorYWerte[2] = 0;
$oSensorYWerte[3] = 0;
$oSensorYWerte[4] = 0;

$iAnzahlYWerte=0;


///////////////////////////////////////////////////////////////////////////
//hole alle Temperaturwerte für den gegebenen zeitraum aus datenbank 
if($sURLSensor=="*"){
	$dbResultatTemp = $db_handler->db_query("select Messzeit,W0 from Messwerte where Messzeit between '$sDatumVon' and '$sDatumBis' order by Messzeit");
}

//Bodenprofilswerte
	$oSensorYWerteProfOrg = array();
	//$myDebug = true;	

	for ($iAnzahlWerteIndex = 0; $iAnzahlWerteIndex < $iAnzahlWerte; $iAnzahlWerteIndex++){//$i=0; $i < $iAnzahlSensoren; $i++

		//Air-Temperatur
		if ( $oDbReihenTupelTemp = mysql_fetch_array($dbResultatTemp) ){
			for ($iAnzahlSensorenIndex=0; $iAnzahlSensorenIndex < 1; $iAnzahlSensorenIndex++) {//$iAnzahlWerteIndex = 0; $iAnzahlWerteIndex < $iAnzahlWerte; $iAnzahlWerteIndex++		   
				//$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$iAnzahlSensorenIndex + 1];
				//$oSensorYWerte[$iAnzahlSensorenBoden + $iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenBoden + $iAnzahlSensorenIndex] + $oDbReihenTupelTemp[$iAnzahlSensorenIndex + 1];
				$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupelTemp[$iAnzahlSensorenIndex + 1];
				$oSensorYWerte[] = $oSensorYWerte[$iAnzahlSensorenIndex];
				
				//Bodenprofilswerte abfüllen
				if (($iAnzahlWerteIndex+1) == $iAnzahlWerte){
					$oSensorYLuftTempWert = round($oDbReihenTupelTemp[1],0);					
				}
				
		        if ($myDebug){
		           echo "-ywert-".$oSensorYWerte[$iAnzahlSensorenIndex];
		           echo "-ywertDB-".$oDbReihenTupelTemp[$iAnzahlSensorenIndex];
		           echo "-ywertDB+1-".$oDbReihenTupelTemp[$iAnzahlSensorenIndex + 1];
		           echo "-sensorIndex-".$iAnzahlSensorenIndex;
		           echo "anzahl: ".$anzahl++;
		           echo"<br></br>";
		        }
		    }
		}	    
				
		if ( $oDbReihenTupel = mysql_fetch_array($dbResultat) ){
			for ($iAnzahlSensorenIndex=0; $iAnzahlSensorenIndex < $iAnzahlSensorenBoden; $iAnzahlSensorenIndex++) {//$iAnzahlWerteIndex = 0; $iAnzahlWerteIndex < $iAnzahlWerte; $iAnzahlWerteIndex++		   
				//$oSensorYWerte[$iAnzahlSensorenIndex] = $oSensorYWerte[$iAnzahlSensorenIndex] + $oDbReihenTupel[$iAnzahlSensorenIndex + 1];
				$oSensorYWerte[1 + $iAnzahlSensorenIndex] = $oSensorYWerte[1 + $iAnzahlSensorenIndex] + $oDbReihenTupel[$iAnzahlSensorenIndex + 1];
				//$oSensorYWerte[] = $oSensorYWerte[$iAnzahlSensorenIndex];
				
				//Bodenprofilswerte abfüllen
				if ((($iAnzahlWerteIndex+1) == $iAnzahlWerte) && ($iAnzahlSensorenIndex==0)){
					$oSensorYWerteProfOrg[0] = $oDbReihenTupel[1];
					$oSensorYWerteProfOrg[1] = $oDbReihenTupel[2];
					$oSensorYWerteProfOrg[2] = $oDbReihenTupel[3];
					$oSensorYWerteProfOrg[3] = $oDbReihenTupel[4];					
				}
				
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
			for ($iAnzahlSensorenIndex1=0; $iAnzahlSensorenIndex1 < $iAnzahlSensorenBodenTemp; $iAnzahlSensorenIndex1++){
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
		$iAnzahlYWerte++;
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

	$oGraph = new Graph($iBildBreite, $iBildHoehe ,"auto");
	$oGraph->SetScale("textlin");	
	// Die Liniengrafik generieren 
	// Show the gridlines
	$oGraph->ygrid->Show(true,false);
	$oGraph->xgrid->Show(true,false);	
for ($iAnzahlSensorenIndex=0; $iAnzahlSensorenIndex < $iAnzahlSensorenBodenTemp; $iAnzahlSensorenIndex++) {
	// Grafik generieren und Grafiktyp festlegen
	//echo "LinePlot add ydata".$oYDaten[0];
	$oLinienKurve=new LinePlot($oYDaten[$iAnzahlSensorenIndex]);
	// Die Linien zu der Grafik hinzufügen
	//$oLinienKurve->SetWeight(12);
	$oGraph->Add($oLinienKurve);
	
	////-------------------------------------------------
	$oLinienKurve->mark->SetType($oMarkTypes[$iAnzahlSensorenIndex]);
	//$oLinienKurve->mark->SetType(MARK_DIAMOND,true);
	
	/*
	 * 	MARK_SQUARE, A filled square 
		MARK_UTRIANGLE, A triangle pointed upwards 
		MARK_DTRIANGLE, A triangle pointed downwards 
		MARK_DIAMOND, A diamond 
		MARK_CIRCLE, A circle 
		MARK_FILLEDCIRCLE, A filled circle 
		MARK_CROSS, A cross 
		MARK_STAR, A star 
		MARK_X, An 'X'
	 * */
	
	
	$oLinienKurve->mark->SetFillColor($oKurvenfarben[$iAnzahlSensorenIndex]);
	$oLinienKurve->mark->SetWidth(2);
	$oLinienKurve->mark->SetSize(8);
	$oLinienKurve->mark->Show(false);
	////-------------------------------------------------

	
	$oLinienKurve->SetWeight(2);
	$oLinienKurve->SetColor($oKurvenfarben[$iAnzahlSensorenIndex]);
	$oLinienKurve->SetLegend($oTitel[$iAnzahlSensorenIndex]);
	
	
	
}//end for
	
	//$oGraph->SetTickDensity(TICKD_VERYSPARSE,TICKD_VERYSPARSE);
	/*
	 * 	TICKD_DENSE, Small distance between ticks 
		TICKD_NORMAL, Default value 
		TICKD_SPARSE, Longer distance between ticks 
		TICKD_VERYSPARSE, Very few ticks 

	 * */
	
	//$oGraph->xaxis->scale->ticks->SupressMinorTickMarks(); 
	//$oGraph->xaxis->SetTextLabelInterval(10); 

	//$graph->xaxis->SetTickPositions($tickPositions,$minTickPositions);
	


	// Grafik Formatieren
//	$oGraph->xaxis->SetTickLabels(gibXLabelFormat($oXDaten[$iAnzahlSensorenIndex],$iBildBreite)); 
	$oGraph->xaxis->SetTickLabels($oXBezeichnungen); 
	$oGraph->xaxis->SetTextLabelInterval($iXBezIntervall);
	// Display every 6:th tickmark
	//$oGraph->xaxis->SetTextTickInterval(5);
	
	$oGraph->xaxis->SetFont(FF_FONT1,gibXLabelGroesse()); 
	$oGraph->xaxis->SetLabelAngle(90);
	$oGraph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$oGraph->xaxis->HideTicks();
	//erzeuge x-tiks label
	$oGraph->xaxis->SetFont(FF_FONT1,$iXBezGroesse); 
	//echo "gibButtonSpace(): ".gibButtonSpace();
	$oGraph->img->SetMargin(60,20,50,gibButtonSpace());

	// Hide the tick marks
	//$oGraph->title->Set($oTitel[$iAnzahlSensorenIndex]);
	$oGraph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);	
	//$oLinienKurve->SetLineWeight(100);
	$oGraph->yaxis->SetColor("red");
	$oGraph->yaxis->SetWeight(2);
	$oGraph->yaxis->SetTitle('Temperatur [°C]','middle');
	$oGraph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD); 
	$oGraph->yaxis->SetTitlemargin(40); 
	
	
	$oGraph->SetShadow();	
	
	
	$oGraph->xaxis->SetPos('min'); // X-Achsenbeschriftungen immer unten anzeigen 
	$oGraph->legend->SetFrameWeight(5);
	$oGraph->legend->SetAbsPos(0.00,0.20,"right","top"); 
	//$oGraph->legend->SetLayout(LEGEND_VERT);  
	$oGraph->legend->SetColumns(3);
	$oGraph->legend->SetFont(FF_VERDANA,FS_NORMAL,7); 
	$oGraph->legend->SetLineSpacing(0); 
	$oGraph->legend->SetHColMargin(10);
	//$oGraph->legend->SetLineWeight(-5);
	
	//$oGraph->legend->SetMarkAbsSize(6); 

	
	$oGraph->legend->SetShadow(); 
	$oGraph->legend->SetColor('blue'); 
	$oGraph->legend->SetFillColor('#F2F2F2'); 
	//$oGraph->legend->Hide(); 
	//echo "iAktuelleZeit: ".$iAktuelleZeit;
	//$fileName = "./kurven/kurve".$iAnzahlSensorenIndex."_".$iAktuelleZeit.".png";
	//echo "FileName:".$fileName;
	//Grafik abspeichern
	//@unlink($fileName);

	$fileName = "./kurven/kurve0"."_".$iAktuelleZeit.".png";
	
	//@unlink("./kurven/kurve$iAnzahlSensorenIndex.png");
	//$oGraph->Stroke("./kurven/kurve$iAnzahlSensorenIndex.png");
	$oGraph->Stroke($fileName);
	//@unlink("./kurven/kurve$iAnzahlSensorenIndex.png");

	
	
/////////////////////////////////////////////////
//TEMPPROFIL
////////////////////////////////////////////////

// Callback for markers
// Must return array(width,border_color,fill_color,filename,imgscale)
// If any of the returned values are '' then the
// default value for that parameter will be used (possible empty)
function FCallback($aYVal,$aXVal) {
    //echo "callback";
	//global $format;
    //return array($format[strval($oSensorXWerteProf)][strval($oSensorYWerteProf)][0],'',
         //$format[strval($oSensorXWerteProf)][strval($oSensorYWerteProf)][1],'','');
}
	
	
///////////////////////////////////////////////	
	
	$oGraphProf = new Graph($iBildHoeheProf, $iBildBreiteProf ,"auto");
	$oGraphProf->SetScale("intlin");	
	// Die Liniengrafik generieren 
	// Show the gridlines
	$oGraphProf->Set90AndMargin(50,25,40,15);
	//$oGraphProf->ygrid->Show(true,false);
	$oGraphProf->xgrid->Show(true,false);	
	
	//$oSensorYWerteProfOrg = array(15.6,13.7,18.9,19.7);
/*
	$oSensorYWerteProfOrg = array();
	
	$oSensorYWerteProfOrg[0] = $oYDaten[1][$iAnzahlYWerte-1];
	$oSensorYWerteProfOrg[1] = $oYDaten[2][$iAnzahlYWerte-1];
	$oSensorYWerteProfOrg[2] = $oYDaten[3][$iAnzahlYWerte-1];
	$oSensorYWerteProfOrg[3] = $oYDaten[4][$iAnzahlYWerte-1];
	$oSensorYLuftTempWert = round($oYDaten[0][$iAnzahlYWerte-1],0);
*/	
	$YWerteProfLv=0;
	$oSensorYWerteProf = array();
	$oSensorXWerteProf = array();
	$oYBezProf = array();
	$sBildMapURLProf=array();
	$sBildMapBeschriftungProf=array();
	
	$spezPos = array(5, 10,50, 100);

	
	//
	//30.10.2011 12:10 - 30.10.2011 18:15
	for ($iAnzahlXProf=0; $iAnzahlXProf < 21; $iAnzahlXProf++) {
		$oSensorXWerteProf[$iAnzahlXProf] = ($iAnzahlXProf * 5)*1;
		if (in_array(5*$iAnzahlXProf, $spezPos)) {
		    $oSensorYWerteProf[$iAnzahlXProf] = $oSensorYWerteProfOrg[$YWerteProfLv];
		    //echo "spezPos enthalten, Pos: ".$YWerteProfLv." , Wert: ".$oSensorYWerteProf[$iAnzahlXProf];
		    $oYBezProf[]=$oSensorYWerteProf[$iAnzahlXProf];
		    $sBildMapURLProf[] = "#";
			// Strings to put as "alts" (and "title" value) (map)
			$sBildMapBeschriftungProf[]=$oSensorYWerteProf[$iAnzahlXProf]." : Wert=%0.2f";								
		    
		    $YWerteProfLv++;
		}else {
			$oSensorYWerteProf[$iAnzahlXProf] = "-";
			//$YWerteProfLv++;
		}
	}	
	
	$oLinienKurveProf=new LinePlot($oSensorYWerteProf,$oSensorXWerteProf);
	$oGraphProf->Add($oLinienKurveProf);
	$oLinienKurveProf->SetWeight(12);
	$oLinienKurveProf->SetColor("red");
	$oLinienKurveProf->SetCSIMTargets($sBildMapURLProf,$sBildMapBeschriftungProf);									
	
	
/*	
	echo "<pre>";
	print_r($oSensorXWerteProf);
	echo "</pre>";
	
	echo "<pre>";
	print_r($oSensorYWerteProf);
	echo "</pre>";
*/	
	
	$sp1 = new ScatterPlot($oSensorYWerteProf,$oSensorXWerteProf); 
	$sp1->mark->SetType(MARK_FILLEDCIRCLE); 
	$sp1->mark->SetFillColor("red"); 
	$sp1->mark->SetWidth(3);
	
	 
	//$sp1->value->SetFormat('%d');
	// Enable display of each slice value
	$sp1->value->Show();
	$sp1->value->SetColor("black","darkred");
	$sp1->value->SetAlign('left'); 
	$sp1->value->SetFormat('%02.1f'); 
	$sp1->value->SetMargin(10); 
	//$sp1->SetCSIMTargets($sBildMapURLProf,$sBildMapBeschriftungProf);
	
	// Specify the callback
	//$sp1->mark->SetCallbackYX("FCallback");
	
	$oGraphProf->Add($sp1); 
		
	
	//$oLinienKurveProf->SetLegend($oTitel[$iAnzahlSensorenIndex]);

	// Grafik Formatieren
//	$oGraphProf->xaxis->SetTickLabels(gibXLabelFormat($oXDaten[$iAnzahlSensorenIndex],$iBildBreite)); 
	//$oGraphProf->xaxis->SetTickLabels($oSensorXWerteProf); 
	//$oGraphProf->xaxis->SetTextLabelInterval(4);
	//$oGraphProf->xaxis->SetFont(FF_FONT1,gibXLabelGroesse()); 
	
	//$oGraphProf->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$oGraphProf->xaxis->SetTitle('Bodentiefe [cm]      ','low');
	$oGraphProf->xaxis->SetTitleSide(SIDE_LEFT);
	$oGraphProf->xaxis->title->SetAngle(90);
	//$oGraphProf->xaxis->title->SetPos(90);  
	
	$oGraphProf->xaxis->SetTitlemargin(20); 
	
	$oGraphProf->xaxis->HideTicks();
	
	//erzeuge x-tiks label
	//$oGraphProf->xaxis->SetFont(FF_FONT1,$iXBezGroesse); 

	//$oGraphProf->img->SetMargin(60,20,20,0);

	// Hide the tick marks
	//$oGraphProf->title->Set($oTitel[$iAnzahlSensorenIndex]);
	//$oGraphProf->yaxis->title->SetFont(FF_VERDANA,FS_BOLD);	
	//$oLinienKurve->SetLineWeight(100);
	$oGraphProf->yaxis->SetColor("red");
	$oGraphProf->yaxis->SetWeight(2);
	$oGraphProf->yaxis->SetTitle('Temperatur [°C]        ','high');
	//$oGraphProf->yaxis->title->SetFont(FF_FONT1,FS_BOLD); 
	$oGraphProf->yaxis->SetTitlemargin(35); 
	$oGraphProf->yaxis->title->SetAngle(0);
	//$oGraphProf->yaxis->SetLabelFormat('%d');
	$oGraphProf->yaxis->SetLabelFormat('%0.1f');  
	
	//$oGraphProf->yaxis->SetTickLabels($oYBezProf); 
	$oGraphProf->yaxis->SetTextLabelInterval(2);
	//$oGraphProf->xaxis->SetTextTickInterval(1,2); 

	
	
	
	
	
	//$oGraphProf->SetShadow();	
	//$oGraphProf->xaxis->SetPos('min'); // X-Achsenbeschriftungen immer unten anzeigen 
	//$oGraphProf->legend->SetFrameWeight(1);
	//$oGraphProf->legend->Pos(0.00,0.00,"right","top"); 
	//$oGraphProf->legend->SetLayout(LEGEND_VERT);  
	//$oGraphProf->legend->SetFont(FF_VERDANA,FS_NORMAL,7); 
	//$oGraphProf->legend->SetLineWeight(2);
	//$oGraphProf->legend->SetShadow(); 
	//$oGraphProf->legend->SetColor('blue'); 
	//$oGraphProf->legend->SetFillColor('lightblue'); 
	
	// Adjust the label align for X-axis so they look good rotated 
	$oGraphProf->xaxis->SetLabelAlign('right','center','right'); 

	// Adjust the label align for Y-axis so they look good rotated 
	$oGraphProf->yaxis->SetLabelAlign('center','bottom');
	
	//$oGraphProf->legend->Hide(); 
	//echo "iAktuelleZeit: ".$iAktuelleZeit;
	//$fileName = "./kurven/kurve".$iAnzahlSensorenIndex."_".$iAktuelleZeit.".png";
	//echo "FileName:".$fileName;
	//Grafik abspeichern
	//@unlink($fileName);
	$fileNameProf = "./kurven/kurve1"."_".$iAktuelleZeit.".png";
	
	//@unlink("./kurven/kurve$iAnzahlSensorenIndex.png");
	//$oGraphProf->Stroke("./kurven/kurve$iAnzahlSensorenIndex.png");
	//$oGraphProf->StrokeCSIM($fileNameProf);
	$oGraphProf->Stroke($fileNameProf);
	//$oGraphProf -> StrokeCSIM (basename(__FILE__)) 
	;
	//@unlink("./kurven/kurve$iAnzahlSensorenIndex.png");
	
	$oGraphProf->GetHTMLImageMap("mapProf");
	
	
	
/////////////////////////////////////////////////
//TEMPPROFIL Grob
////////////////////////////////////////////////

	
	$oGraphProfGrob = new Graph($iBildHoeheProf, $iBildBreiteProf ,"auto");
	$oGraphProfGrob->SetScale("intlin",-5,30);	
	// Die Liniengrafik generieren 
	// Show the gridlines
	$oGraphProfGrob->Set90AndMargin(50,25,40,15);
	//$oGraphProf->ygrid->Show(true,false);
	$oGraphProfGrob->xgrid->Show(true,false);	
	
	
	$oLinienKurveProfGrob=new LinePlot($oSensorYWerteProf,$oSensorXWerteProf);
	$oGraphProfGrob->Add($oLinienKurveProfGrob);
	$oLinienKurveProfGrob->SetWeight(12);
	$oLinienKurveProfGrob->SetColor("red");

	
	$sp1Grob = new ScatterPlot($oSensorYWerteProf,$oSensorXWerteProf); 
	$sp1Grob->mark->SetType(MARK_FILLEDCIRCLE); 
	$sp1Grob->mark->SetFillColor("red"); 
	$sp1Grob->mark->SetWidth(3);
	 
	 
	//$sp1->value->SetFormat('%d');
	// Enable display of each slice value
	//$sp1Grob->value->Show();
	$sp1Grob->value->SetColor("black","darkred");
	$sp1Grob->value->SetAlign('left'); 
	$sp1Grob->value->SetFormat('%02.1f'); 
	$sp1Grob->value->SetMargin(10); 

	
	// Specify the callback
	//$sp1->mark->SetCallbackYX("FCallback");
	
	$oGraphProfGrob->Add($sp1Grob); 
	// Grafik Formatieren
	$oGraphProfGrob->xaxis->SetTitle('Bodentiefe [cm]      ','low');
	$oGraphProfGrob->xaxis->SetTitleSide(SIDE_LEFT);
	$oGraphProfGrob->xaxis->title->SetAngle(90);
	//$oGraphProf->xaxis->title->SetPos(90);  
	$oGraphProfGrob->xaxis->SetPos('min'); // X-Achsenbeschriftungen immer unten anzeigen 
	$oGraphProfGrob->xaxis->SetTitlemargin(20); 
	
	$oGraphProfGrob->xaxis->HideTicks();
	
	$oGraphProfGrob->yaxis->SetColor("red");
	$oGraphProfGrob->yaxis->SetWeight(2);
	$oGraphProfGrob->yaxis->SetTitle('Temperatur [°C]        ','high');
	//$oGraphProf->yaxis->title->SetFont(FF_FONT1,FS_BOLD); 
	$oGraphProfGrob->yaxis->SetTitlemargin(35); 
	$oGraphProfGrob->yaxis->title->SetAngle(0);
	//$oGraphProf->yaxis->SetLabelFormat('%d');
	$oGraphProfGrob->yaxis->SetLabelFormat('%0.0f');  
	
	//$oGraphProfGrob->yaxis->SetTickLabels($oYBezProf); 
	$oGraphProfGrob->yaxis->SetTextLabelInterval(1);
	$oGraphProfGrob->xaxis->SetTextTickInterval(1,2); 

	
	// Adjust the label align for X-axis so they look good rotated 
	$oGraphProfGrob->xaxis->SetLabelAlign('right','center','right'); 

	// Adjust the label align for Y-axis so they look good rotated 
	$oGraphProfGrob->yaxis->SetLabelAlign('center','bottom');
	
	//Grafik abspeichern
	//@unlink($fileName);
	$fileNameProf = "./kurven/kurve2"."_".$iAktuelleZeit.".png";
	
	//@unlink("./kurven/kurve$iAnzahlSensorenIndex.png");
	//$oGraphProf->Stroke("./kurven/kurve$iAnzahlSensorenIndex.png");
	$oGraphProfGrob->Stroke($fileNameProf);
	
	
	?>
