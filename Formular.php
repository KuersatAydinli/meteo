<?

include_once("./MVIncludes/db_class.php");	
$db_handler = new db_class;
$dbResultat0 = $db_handler->db_query("select MAX(Messzeit)from Messwerte");
if ($oDbReihenTupel0 =mysql_fetch_array($dbResultat0)){
	$sLetztesMesswertDatum = $oDbReihenTupel0[0];
}else{
	//Fehlerseite einblenden
	echo "Kein aktueller Datensatz";
	return;
}
$oDatumZeit = explode(" ",$sLetztesMesswertDatum);
$oDatumTeile = explode("-",$oDatumZeit[0]);
$oZeitTeile = explode(":",$oDatumZeit[1]);
$oDatum = mktime($oZeitTeile[0],$oZeitTeile[1],$oZeitTeile[2],$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0]);

if($bGlobalDebug){
	echo "checked oZeitIntervall bei Formular: ".$_GET['oZeitIntervall'];
	echo "selected option bei Formular: ".$_GET['iXBezIntervallWert'];
}


/*
    if (!isset($_GET['oZeitIntervall'])) {
        $_GET['oZeitIntervall'] = '';
    } else {
        $_GET['oZeitIntervall'] = urldecode($_GET['oZeitIntervall']);
    }
*/
    		if(isset($_POST['oZeitIntervall'])){
				$oZeitIntervall = $_POST['oZeitIntervall']; 
			}
			elseif(isset($_GET['oZeitIntervall'])){
				$oZeitIntervall = $_GET['oZeitIntervall'];
		    }
    
    		if(isset($_POST['iXBezIntervallWert'])){
				$iXBezIntervallWert = $_POST['iXBezIntervallWert']; 
			}
			elseif(isset($_GET['iXBezIntervallWert'])){
				$iXBezIntervallWert = $_GET['iXBezIntervallWert'];
		    }
		    
		    
    
    
    		if(isset($_GET['bSelbstAufruf'])){
			if($bGlobalDebug){echo "bSelbstAufruf";}
			if(isset($_POST['sFrmVonDatum'])){
				$sKonVonDatum = $_POST['sFrmVonDatum']; 
				$sKonBisDatum = $_POST['sFrmBisDatum'];
				
			}
			elseif(isset($_GET['sFrmVonDatum'])){
				$sKonVonDatum = $_GET['sFrmVonDatum']; 
				$sKonBisDatum = $_GET['sFrmBisDatum'];
		    }
			
		}else{
			if($bGlobalDebug){echo "fromIndex";}
			$sKonVonDatum = Date('d.m.Y H:i',$oDatum - 21600);	
			$sKonBisDatum = 	Date('d.m.Y H:i',$oDatum);
			
		}
		$sDatumVon=$sKonVonDatum;
		$sDatumBis=$sKonBisDatum;
    

	//echo urldecode("http://meteovaduz.schulen.li/meteo/%3Cbr%20/%3E%3Cb%3ENotice%3C/b%3E:%20%20Undefined%20variable:%20sFrmVonDatum_loc%20in%20%3Cb%3EC:/MeteoLGVaduz/Programme/Web/xampp/htdocs/meteo/Formular.php%3C/b%3E%20on%20line%20%3Cb%3E37%3C/b%3E%3Cbr%20/%3E%3Cbr%20/%3E%3Cb%3ENotice%3C/b%3E:%20%20Undefined%20variable:%20sFrmBisDatum_loc%20in%20%3Cb%3EC:/MeteoLGVaduz/Programme/Web/xampp/htdocs/meteo/Formular.php%3C/b%3E%20on%20line%20%3Cb%3E37%3C/b%3E%3Cbr%20/%3E./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&formFrom=&formTo=&ESensor=W0&ETitel=Temperatur in °C&kFarbe=red&iXBezIntervallWert=6&oZeitIntervall=6&iBrowserFensterBreite=1149&iBrowserFensterHoehe=621");
	

?>



<form name="idFormular" id="idFormular" method="POST" action="<?echo "./index.php?sNavigationsSeite=$sNavigationsQuelle&bSelbstAufruf=yes&formFrom=".$_GET['sFrmVonDatum']."&formTo=".$_GET['sFrmBisDatum']."&ESensor=".$_GET['ESensor']."&ETitel=".$_GET['ETitel']."&kFarbe=".$_GET['kFarbe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall."&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe'];?>">


<table border="0" cellspacing="0" width="100%" class="formtabletext">

   <tr class="FormInfoText2">Geben Sie die gewünschte Ansichtsdauer ein, z.B. von 01.02.2010 03:32 bis 12.02.2010 10:15 </tr>
   <!--
   <tr class="FormInfoText2">oder wählen Sie anhand der Auswahlmöglichkeiten einen vordefinierten Zeitraum (z.B. 12 Stunden) und klicken Sie anschliessend auf ‚Aktualisieren‘.</tr>
   -->
<?
		if($_GET['sNavigationsSeite']=="datenexport"){
   			echo "<tr class='FormInfoText2'>oder wählen Sie anhand der Auswahlmöglichkeiten einen vordefinierten Zeitraum (z.B. 12 Stunden) und klicken Sie anschliessend auf ‚Download‘.</tr>";			
		}else{			
   			echo "<tr class='FormInfoText2'>oder wählen Sie anhand der Auswahlmöglichkeiten einen vordefinierten Zeitraum (z.B. 12 Stunden) und klicken Sie anschliessend auf ‚Aktualisieren‘.</tr>";			
		}



   if($_GET['sNavigationsSeite']=="einzelnegrafik"){
				echo "<tr align='left' class='FormInfoText2'>Mit 'Download' können Sie die Werte in tabellarischer Form herunterladen.</tr>";		
		
   }
?>
  <tr class="FormInfoText2">
    
    
    <? 
    $seite = $_GET['sNavigationsSeite'];
    echo "<td width='' class='FormInfoText2'>von <input type='text' id='sFrmVonDatum' name='sFrmVonDatum'  value='$sDatumVon' onchange='chkMinDate(\"$seite\")'></td>";?>
    <td width="">bis <input type="text" name="sFrmBisDatum" id="sFrmBisDatum" size="20" value="<?echo $sDatumBis?>"></td>

	<td width="" >
	<?
		if($_GET['sNavigationsSeite']=="datenexport"){
			echo "<input type='button' name='download' value='$sSchaltflächenBeschriftung' onClick='erstelleDownloadLink(\"alle\")'>";
			echo "<input type='radio' name='Radiodateityp' checked value='excel' onClick='wechsleDownloadBild()'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl' onClick='wechsleDownloadBild()'> Exceldatei<br>";
			echo "<input type='radio' name='RadiodateitypTable' checked value='luft'> Datenexport Luftwerte<input type='radio' name='RadiodateitypTable' value='boden'> Datenexport Bodenwerte<br></td>";
		}else{			
			echo "<input type='submit' value='$sSchaltflächenBeschriftung' name='Aktualisieren' ></td>";
		}
	//bei einzelner grafik könnne die werte auch in tabellarischer Form heruntergeladen werden
	if($_GET['sNavigationsSeite']=="einzelnegrafik"){
		$esen = $_GET['ESensor'];
		echo "<td ><input type='button' name='download' value = 'Download' onClick='erstelleDownloadLink(\"$esen\")'>";
		echo "<input type='radio' name='Radiodateityp' checked value='excel'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl'> Excel-Datei<br></td>";
	}
	?>
	
  <?
	if($_GET['sNavigationsSeite']=="datenexport"){
	/*
		echo "<td width=''>";
  		echo"<input type='radio' name='Radiodateityp' checked value='excel'> CSV-Datei<br></td>";
		echo"<td><input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl'> Exceldatei<br></td>";
	*/
	}
 
  ?>	
   </tr>
	<tr>
   		<td class="padding0"><input TYPE="radio" VALUE="6" NAME="oZeitIntervall"  <?if(($oZeitIntervall==6) ||(!isset($oZeitIntervall))){echo "checked";}?> onClick="anpassenVonUndBis('xgrid',<? echo "'".$seite."'" ?>)" > 6 Stunden<br></td>
   		<td class="padding0"><input TYPE="radio" VALUE="12" NAME="oZeitIntervall" <?if($oZeitIntervall==12){echo "checked";}?> onClick="anpassenVonUndBis('xgrid',<? echo "'".$seite."'" ?>)">   12 Stunden<br></td>
   		<td class="padding0"><input TYPE="radio" VALUE="24" NAME="oZeitIntervall" <?if($oZeitIntervall==24){echo "checked";}?> onClick="anpassenVonUndBis('xgrid',<? echo "'".$seite."'" ?>)">   24 Stunden<br></td>
<?if($_GET['sNavigationsSeite']!="datenexport"){echo "<td class='FormInfoText2'>Wählen Sie das Beschriftungsintervall der X-Achse aus und klicken Sie anschliessend auf ‚Aktualisieren‘
.</td>";}?>
	</tr>
	<tr>   		
  		<td class="padding0"><input TYPE="radio" VALUE="168" NAME="oZeitIntervall" <?if($oZeitIntervall==168){echo "checked";}?> onClick="anpassenVonUndBis('xgrid',<? echo "'".$seite."'" ?>)"> 1 Woche<br></td>
   		<td class="padding0"><input TYPE="radio" VALUE="720" NAME="oZeitIntervall" <?if($oZeitIntervall==720){echo "checked";}?> onClick="anpassenVonUndBis('xgrid',<? echo "'".$seite."'" ?>)">   1 Monat<br></td>
  		
  		<td class="padding0"><input TYPE="radio" VALUE="8760" NAME="oZeitIntervall" <?if($oZeitIntervall==8760){echo "checked";}?> onClick="anpassenVonUndBis('xgrid',<? echo "'".$seite."'" ?>)">   1 Jahr<br></td>
<?

//echo $iXBezIntervallWert;

if($_GET['sNavigationsSeite']!="datenexport"){
echo "<td class='padding0'>";
 		echo "<SELECT NAME='iXBezIntervallWert'>";
 			echo "<OPTION "; if($iXBezIntervallWert==1){echo "selected ";} echo " VALUE='1'>10 Minuten";
			echo "<OPTION "; if($iXBezIntervallWert==3){echo "selected ";} echo " VALUE='3'>30 Minuten";
			echo "<OPTION "; if(($iXBezIntervallWert==6) ||(!isset($iXBezIntervallWert))){echo "selected ";} echo "VALUE='6' >1 Stunde";
			echo "<OPTION "; if($iXBezIntervallWert==72){echo "selected ";} echo " VALUE='72'>12 Stunden";
			echo "<OPTION "; if($iXBezIntervallWert==144){echo "selected ";} echo " VALUE='144'>1 Tag";
			echo "<OPTION "; if($iXBezIntervallWert==1008){echo "selected ";} echo " VALUE='1008'>1 Woche";
			echo "<OPTION "; if($iXBezIntervallWert==4320){echo "selected ";} echo " VALUE='4320'>1 Monat";
			echo "<OPTION "; if($iXBezIntervallWert==52560){echo "selected ";} echo " VALUE='52560'>1 Jahr";

		echo "</SELECT>";
echo "</td>";
}else{
	echo "<td>";
	echo "<input type='hidden' NAME='iXBezIntervallWert'>";
	echo "</SELECT>";
	echo "</td>";
}

?>


 	</tr>	
</table>
</form>