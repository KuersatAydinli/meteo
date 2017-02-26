<?
//gibt ein mysqldatumsformat zurück und überprüft es ob es ein richtiges Datum ist, In: 10.23.2003 09:50 --> Out: 2003-10-23 09:50 
function gibMysqlDatum ($date) {
	//in: 20.10.2003 15:00
	//out: 2003-10-20 15:00
	$oDatumZeit = explode(" ",$date);
	$oDatumTeile = explode(".",$oDatumZeit[0]);
	$oZeitTeile = explode(":",$oDatumZeit[1]);
	$rdate = date ("Y-m-d H:i:s", mktime ($oZeitTeile[0],$oZeitTeile[1],0,$oDatumTeile[1],$oDatumTeile[0],$oDatumTeile[2]));
	if(isset($bGlobalDebug)){
		echo "".$oDatumZeit[0]."<br>";
		echo "".$oDatumZeit[1]."<br>";
		echo "".$oZeitTeile[0]."<br>";
		echo "".$oZeitTeile[1]."<br>";
		echo "".$oDatumTeile[0]."<br>";
		echo "".$oDatumTeile[1]."<br>";
		echo "".$oDatumTeile[2]."<br>";
		echo "".$rdate."<br>";		
	}
	
	if(checkdate ($oDatumTeile[1],$oDatumTeile[0],$oDatumTeile[2])){
		return $rdate;	
	}else{
		echo "Datumseingabe nicht korrekt! z.B.: 20.10.2003 15:00";
		return null;
	
	}
	
}

//überprüft ob $von kleiner als $bis is, die eingaben müssen vom typ timestamp sein
function vergleicheVonBis ($von,$bis) {
	if($von < $bis){
		return 1;
	}else{
		return null;
	}

}

//gibt ein datum in form 20.10.2003 15:00 als timestamp zurück
function gibZeitStempel ($date) {
	//error_reporting(E_ALL);	
	//echo "gibZeitStempel:".$date;
	$oDatumZeit = explode(" ",$date);
	$oDatumTeile = explode(".",$oDatumZeit[0]);
	$oZeitTeile = explode(":",$oDatumZeit[1]);
	$rdate = mktime ((int)$oZeitTeile[0],(int)$oZeitTeile[1],0,(int)$oDatumTeile[1],(int)$oDatumTeile[0],(int)$oDatumTeile[2]);
	if(checkdate ((int)$oDatumTeile[1],(int)$oDatumTeile[0],(int)$oDatumTeile[2])){
		return $rdate;			
	}else{
		echo "Datumseingabe nicht korrekt! z.B.: 20.10.2003 15:00";
		return null;	
	}

}

//gibt ein datum in form 2013-03-10 00:30:41 als timestamp zurück
function gibZeitStempelSql ($mezDatum) {
	//error_reporting(E_ALL);	
	//echo "gibZeitStempel:".$date;
	//in:2003-11-03 08:17:52
	
	$oDatumZeit = explode(" ",$mezDatum);
	$oDatumTeile = explode("-",$oDatumZeit[0]);
	$oZeitTeile = explode(":",$oDatumZeit[1]);
	if($bGlobalDebug){
		echo "datearray[0]".$oDatumZeit[0]."<br>";
		echo "datearray[1]".$oDatumZeit[1]."<br>";
		echo "tar[0]".$oZeitTeile[0]."<br>";
		echo "tar[1]".$oZeitTeile[1]."<br>";
		echo "tar[2]".$oZeitTeile[2]."<br>";
		echo "dar[0]".$oDatumTeile[0]."<br>";
		echo "dar[1]".$oDatumTeile[1]."<br>";
		echo "dar[2]".$oDatumTeile[2]."<br>";		
	}
	
	//mktime(int hour, int minute, int second, int month, int day, int year )
	//dar[0]2003	dar[1]10	dar[2]25
	$rdate = mktime($oZeitTeile[0],$oZeitTeile[1],0,$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0]);
	return $rdate;			

}


/*
Universal Time (UT): Astronomische Daten werden häufig in UT angegeben. UT ist die Ortszeit für den 0. Längengrad (Greenwich, bei London). Da so eine einheitliche Zeitangabe verwendet wird, können Ereignisse international ausgetauscht werden, ohne daß man groß die Zeitzonen berücksichtigen muß. 
Die Universal Time erhält man, indem man 
im Winter von der mitteleuropäischen Zeit (MEZ) eine Stunde abzieht, 
im Sommer von der Sommerzeit (MESZ) zwei Stunden abzieht. 
Also entspricht 23:00 Uhr MEZ 22:00 Uhr UT. 
23:00 MESZ entspricht dagegen 21:00 Uhr UT. 

Für Auswertungen innerhalb Deutschlands wird häufig die mitteleuropäische Zeit (MEZ) benutzt. 
*/

function getUTTime($mezDatum){
	//in:2003-11-03 08:17:52
	//out:200311030717
	//oder je nach Jahrezeit
	//out:200311030617
	$oDatumZeit = explode(" ",$mezDatum);
	$oDatumTeile = explode("-",$oDatumZeit[0]);
	$oZeitTeile = explode(":",$oDatumZeit[1]);
	if($bGlobalDebug){
		echo "datearray[0]".$oDatumZeit[0]."<br>";
		echo "datearray[1]".$oDatumZeit[1]."<br>";
		echo "tar[0]".$oZeitTeile[0]."<br>";
		echo "tar[1]".$oZeitTeile[1]."<br>";
		echo "tar[2]".$oZeitTeile[2]."<br>";
		echo "dar[0]".$oDatumTeile[0]."<br>";
		echo "dar[1]".$oDatumTeile[1]."<br>";
		echo "dar[2]".$oDatumTeile[2]."<br>";		
	}
	
	//mktime(int hour, int minute, int second, int month, int day, int year )
	//dar[0]2003	dar[1]10	dar[2]25
	$rdate = gmdate("YmdHi", mktime($oZeitTeile[0],$oZeitTeile[1],0,$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0]));
	return	$rdate;	
}

?>