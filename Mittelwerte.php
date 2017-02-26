<p><h2>Mittelwerte</h2></p>

<?
$sNavigationsQuelle = "mittelwerte";
$sSchaltflächenBeschriftung = "Aktualisieren";
$sSchaltflächenBeschriftungDownloadJahresWerte = "Download Monatswerte";
$sSchaltflächenBeschriftungDownloadMonatsWerte = "Download Tagesswerte";

//echo "Requets: ".$_REQUEST['Aktualisieren'];

//Setze welcher Lnopf gederückt
if ($_REQUEST['Aktualisieren']  == 'Aktualisieren')
{ 	$bDonwnloadData = 0;
	//echo 'Aktualisiere Page';
}
else
{ 
	if ($_REQUEST['Aktualisieren']  == 'Download Jahreswerte')
	{ 	$bDonwnloadData = 1;
		//echo 'Download_Jahreswerte';
	}
	if ($_REQUEST['Aktualisieren']  == 'Download Monatswerte')
	{ 
		$bDonwnloadData = 2;
		//echo 'NOK Download_Jahreswerte';
	}	
	//echo 'NOK Download_Jahreswerte';
}

if (($bDonwnloadData!=0)){

	
	//header informationen für den browser: dateityp festlegen ('.xls') 
	//header("Content-Type: application/$sDateiTyp"); 
	//header("Content-Disposition: attachment; filename=Messwerteauszug.$sDateiTypEndung"); 
	//header("Pragma: no-cache"); 
	//header("Expires: 0"); 
	
}	



//echo "$bDonwnloadData: ".$bDonwnloadData;
//if (!isset($bDonwnloadData) || ($bDonwnloadData==0)){	
	
	include('./FormularMittelwerte.php');	
	//echo "$bDonwnloadData==0";
//}



include_once("./MVIncludes/db_class.php");
//echo date("h:i:s");
include_once("./MittelwerteDatenSelector.php");


//excel/csv generieren
if ($bDonwnloadData != 0){
	
	//$nowtime = time();
	//echo "bDonwnloadData $nowtime: ".$bDonwnloadData;
	
	if ($bDonwnloadData == 1){		
		//include_once("./ExcelExportMW_Jahr.php");
	}
	
	
}else{

?>



		<h3>Messwerte von <? echo $iJahrIdWert." / ";?><?echo $oDbAbfragenResultateSensor["sensorbez"][0]." ".$oDbAbfragenResultateSensor["sensormass"][0] ?> </h3>
		
		<? 
		
		if($iSensorIdWert==3){ // Regensensor -> nur mit Tagesmaximalwerte arbeiten
		
		}	
		
		
		//&& ($iMonatIdWert < 5)
		if(($iSensorIdWert > $iAnzahlSensorenMeteo-1) && ($iJahrIdWert < 2008) ) {//Boden-Daten erst ab Mai 2008 verfügbar
			
			echo "<div class='warnings1'>Boden-Daten erst ab Mai 2008 verfügbar</div>";
			
		}else{
		
			if(($iSensorIdWert > $iAnzahlSensorenMeteo-1) && ($iJahrIdWert == 2008) && ($iMonatIdWert < 5) ) {//Boden-Daten erst ab Mai 2008 verfügbar
				echo "<div class='warnings1'>Tageswerte für Boden-Daten erst ab Mai 2008 verfügbar</div>";
			}
			
			
		echo '<table  class="mittelWerteTextTM" >';
		echo '<tr>';
		
		echo '<td align="left" BGCOLOR="#ffff00"><b>Wert für</b></td>';
		if($iSensorIdWert!=3){ // nicht Regensensor
		echo '<td align="center" BGCOLOR="#ffff00"><b>Mittelwert</b></td>';
		echo '<td align="center" BGCOLOR="#ffff00"><b>Minimalwert</b></td>';
		echo '<td align="center" BGCOLOR="#ffff00"><b>Maximalwert</b></td>';
		}else{	
			echo '<td align="center" BGCOLOR="#ffff00"><b>Jahres-/Monatssumme</b></td>';
		}
		
		
		echo '</tr>';
		
		
		echo '<tr>';
		echo "<td align='left' BGCOLOR='#00ffff'>".$oDbAbfragenResultateJahr['messzeit'][0]."</td>";
		if($iSensorIdWert!=3){ // nicht Regensensor
			echo "<td align='center' BGCOLOR='#00ffff'>".$oDbAbfragenResultateJahr['avgwerte'][0]."</td>";
			echo "<td align='center' BGCOLOR='#00ffff'>".$oDbAbfragenResultateJahr['minwerte'][0]."</td>";
			echo "<td align='center' BGCOLOR='#00ffff'>".$oDbAbfragenResultateJahr['maxwerte'][0]."</td>";
		}else{
			echo "<td align='center' BGCOLOR='#00ffff'>".$jahres_RS_Sum."</td>";
		}
		
		
		echo "</tr>";
		
		if($iSensorIdWert!=3){
			for ($i = 0; $i < count($oDbAbfragenResultateMonat["messzeit"]); $i++ ) {
			  echo "<tr>";
			    echo "<td align='left' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat["messzeit"][$i]."</td>";
			    echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat["avgwerte"][$i]."</td>";
			    echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat["minwerte"][$i]."</td>";
			    echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat["maxwerte"][$i]."</td>";
			    
			  echo "</tr>";
			}
			
		}else{
			for ($i = 0; $i < count($oDbAbfragenResultateMonat_RS["messzeit"]); $i++ ) {
			  echo "<tr>";
			  	echo "<td align='left' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat_RS["messzeit"][$i]."</td>";
			    echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat_RS["monatsum"][$i]."</td>";
			    //echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat_RS["avgwerte"][$i]."</td>";
			    //echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat_RS["minwerte"][$i]."</td>";
			    //echo "<td align='center' BGCOLOR='#00ff00'>".$oDbAbfragenResultateMonat_RS["maxwerte"][$i]."</td>";
			  echo "</tr>";
			}
			
		}
		
		echo '</table>';
		
		//echo "so nen müll";
		
		if(($iSensorIdWert > $iAnzahlSensorenMeteo-1) && ($iJahrIdWert == 2008) && ($iMonatIdWert < 5) ) {//Boden-Daten erst ab Mai 2008 verfügbar
			
		}else{
		echo '<table class="mittelWerteTextTM" >';
		
		echo '<tr>';
		
		echo '<td align="left" BGCOLOR="#ffff00"><b>Wert für</b></td>';
		if($iSensorIdWert!=3){ // nicht Regensensor
			echo '<td align="center" BGCOLOR="#ffff00"><b>Mittelwert</b></td>';
			echo '<td align="center" BGCOLOR="#ffff00"><b>Minimalwert</b></td>';	
		}
		
		if($iSensorIdWert!=3){ // nicht Regensensor
			echo '<td align="center" BGCOLOR="#ffff00"><b>Maximalwert</b></td>';
		}else{
			echo '<td align="center" BGCOLOR="#ffff00"><b>Tagessumme</b></td>';
		}
			
		
		
		echo '</tr>';
		
		
		  	for ($t = 0; $t <   round(count($oDbAbfragenResultateTag["messzeit"])/2); $t++ ) {
			  echo "<tr>";
			    
			  echo "<td align='left'>".$oDbAbfragenResultateTag["messzeit"][$t]."</td>";
			    
		if($iSensorIdWert!=3){ // nicht Regensensor	    
			    echo "<td align='center'>".$oDbAbfragenResultateTag["avgwerte"][$t]."</td>";
			    echo "<td align='center'>".$oDbAbfragenResultateTag["minwerte"][$t]."</td>";
		}	    
			    echo "<td align='center'>".$oDbAbfragenResultateTag["maxwerte"][$t]."</td>";
			    
			  echo "</tr>";
		  	}  	
		echo '</table>';
		
		echo '<table class="mittelWerteTextTM" >';
		
		echo '<tr>';
		
		echo '<td align="left" BGCOLOR="#ffff00"><b>Wert für</b></td>';
		if($iSensorIdWert!=3){ // nicht Regensensor
			echo '<td align="center" BGCOLOR="#ffff00"><b>Mittelwert</b></td>';
			echo '<td align="center" BGCOLOR="#ffff00"><b>Minimalwert</b></td>';	
		}
		
		if($iSensorIdWert!=3){ // nicht Regensensor
			echo '<td align="center" BGCOLOR="#ffff00"><b>Maximalwert</b></td>';
		}else{
			echo '<td align="center" BGCOLOR="#ffff00"><b>Tagessumme</b></td>';
		}
		
		echo '</tr>';
		
		  	for ($t2 = round(count($oDbAbfragenResultateTag["messzeit"])/2); $t2 < count($oDbAbfragenResultateTag["messzeit"]); $t2++ ) {
			  echo "<tr>";
			    echo "<td align='left'>".$oDbAbfragenResultateTag["messzeit"][$t2]."</td>";
		if($iSensorIdWert!=3){ // nicht Regensensor
			    echo "<td align='center'>".$oDbAbfragenResultateTag["avgwerte"][$t2]."</td>";
			    echo "<td align='center'>".$oDbAbfragenResultateTag["minwerte"][$t2]."</td>";
		}
			    echo "<td align='center'>".$oDbAbfragenResultateTag["maxwerte"][$t2]."</td>";
			  echo "</tr>";
		  	}  	
		echo '</table>';
		
		}//endif Tageswerte-Tabelle bodendaten erst Mai 2008
		
		}//end-if bodendaten erst Mai 2008 
		
}//download oder page neu aufbauen		
		?>  	

















