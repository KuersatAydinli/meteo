<h1>Allgemeine Informationen</h1>


<h2>Messung der Sensorwerte</h2>
<p class="sensInfo">
�	Die Sensoren werden alle zwei Minuten einmal ausgelesen.<br/>
�	Alle 10 Minuten (dies entspricht 5 Messungen) wird f�r jeden Sensor das
	arithmetische Mittel berechnet und mit einem Zeitstempel in einer Datenbank gespeichert.
</p>






<h2>Sensordaten</h2>
<?

include_once("./MVIncludes/db_class.php");
$db_handler = new db_class;
$table = "sensoren";


$dbResultat = $db_handler->db_query("SELECT * FROM $table");
?>
<table  border="1" class="sensInfo">
 <tr>
 <?
 $k = 0;
 while($field=mysql_fetch_field($dbResultat)) {
    if($k==1){
    	echo  "<TD><b>".ucwords($field->name)."</b></TD>";
    }else{
    	echo  "<TH>".ucwords($field->name)."</TH>";
    }
    
    
    $k++;
 }
 echo  "</tr>";
 while($oDbReihenTupel  =  mysql_fetch_row($dbResultat))  {
    echo  "<tr>";
    for($i=0; $i < mysql_num_fields($dbResultat);  $i++)  {
       if($i==1 || $i==6){
			echo  "<td align=left>$oDbReihenTupel[$i]&nbsp;</td>";
       }else{
       		echo  "<td align=center>$oDbReihenTupel[$i]&nbsp;</td>";
       }
       
    }
    echo  "</tr>\n";
}
?>
</table>
<br>

<!--                                                                               
<h3>Der Temperatursensor</h3>
Die Temperaturmessung basiert
auf einem Pr�zisions-Halbleitersensor
mit integrierter Linearisierung.
Der resultierende Me�wert
wird zus�tzlich durch die Software
linearisiert. Der Temperatursensor
ist standardm��ig auf der Unterseite
der Wetterstation montiert
und ist zwangsbel�ftet, was eine
sehr schnelle Reaktion auf Temperatur-
�nderungen bewirkt und einen
W�rmestau verhindert.
Bereich: von -40 �C bis + 50 �C,
Me�genauigkeit � 0,3 �C, (Anzeige
auch in �Fahrenheit oder Kelvin).

<h3>Der Feuchtesensor</h3>
Der Feuchtesensor ist ein schnell ansprechender kapazitiver Sensor (monolithisch), der auf einem
feuchtever�nderbaren Dielektrikum (Kapazit�t) basiert. Proportional zur Luftfeuchte
wird �ber die Elektronik ein Signal mit ver�nderlicher Frequenz erzeugt, die vom
Mikroprozessor ausgewertet wird. Dieser Sensor ist ebenfalls auf der Unterseite der
Wetterstation angebracht. Er sitzt unter einer Schutzkappe aus Gore-Tex, damit
Verschmutzung oder Zerst�rung durch Staub, Insekten, etc. vermieden wird.
Der Feuchtesensor ist in einem Temperaturbereich von von -40 �C bis + 50 �C
einsetzbar und auf eine Genauigkeit von 2 % linearisiert. Der Feuchtesensor ist voll
betaubar.
Bereich: von 12 bis 100 %, Me�genauigkeit �2 %, Anzeige auch als Taupunktmessung
in �C oder �F.
Achtung: Dieser Sensor ist sehr empfindlich gegen�ber statischer Aufladung
und Luftverschmutzung (Staub, aggressive Gase, aber auch Salz, etc)
Beachten Sie, da� dieser Sensor bei ung�nstigen Bedingungen (h�ufige
Betauung, mikrobiologischer Belastung durch Schimmelsporen, Bakterien,
etc.) eine schnellere Alterung aufweist, als unter Normalbedingungen.

<h3>Der Drucksensor</h3>
Der Drucksensor besteht aus einem monolithischen, lasergetrimmten Absolutdrucksensor (Dickfilmkeramik),
der bereits eine Linearisierung von 5 hPa �ber den gesamten Temperaturbereich
besitzt, d.h., das Barometer ist temperaturkompensiert. Eine weitere,
softwarem��ige Temperaturlinearisierung reduziert den temperaturabh�ngigen
Druck-Fehler auf kleiner als 2 hPa �ber den ganzen Temperaturbereich. Das
Me�signal wird durch einen Instrumentenverst�rker aufbereitet. Sie k�nnen den
Sensor in einem Temperaturbereich von -40 �C bis + 50 �C einsetzen.
Anzeigebereich: von 950 hPa bis 1050 hPa mit � 1 hPa Me�genauigkeit, Anzeige
reduziert auf 0 m Meeresh�he, Ortsh�heneingabe in m, Anzeige auch in mm
Quecksilbers�ule oder Inch Quecksilbers�ule

<h3>Der Globalstrahlungssensor</h3>
Der Globalstrahlungssensor ist ein Pyranometer, das die Strahlung zwischen 305 und 2800 nm aufnimmt. Die
Temperaturdifferenz zwischen einem geschw�rzten und einem reflektierenden
Element wird ermittelt. Die Me�sensoren sind
zwei Halbleitersensoren. Die Werte werden in W/m2 ausgegeben.
Der Me�wert ist immer ein Durchschnittswert, der in 10 Sekunden-Abst�nden
aktualisiert wird. 
Bereich: von 0 bis 1500 W/m2 mit einer Genauigkeit von �2.5 % vom Endwert.

<h3>Der Windgeschwindigkeitssensor</h3>
Der Windgeschwindigkeitssensor ist ein Dreischalenanemometer mit magnetischer Abtastung. Die Windgeschwindigkeit
wird ber�hrungslos �ber einen Reed-Kontakt gemessen. Ein Spitzenwertdetektor
erfa�t jede Windspitze und stellt sie dann der Me�software zur Verf�gung.
Bereich: in km/h von 0 bis 150 km/h mit � 2km/h Me�genauigkeit.

<h3>Der Windrichtungssensor</h3>
Der Windrichtungssensor besteht aus einer Windfahne mit einem Pr�zisions-Endlospotentiometer von 10 k?
und einem Drehwinkel von 360 � zur Aufl�sung der Windrichtungsposition. Die
Windrichtung wird in � angegeben, wobei 90� f�r Osten steht, 180� f�r S�den, 270�
f�r Westen und 0 � f�r Norden.
Bereich: in 360 �, Me�genauigkeit 5 � mit einem toten Winkel von 20� im Norden,Anlaufgeschwindigkeit 1,4 km/h

<h3>Der Regenmengensensor</h3>
Eine selbstentleerende Wippe wird durch
den aufgefangenen Regen gekippt. Der
Wassereintritt auf der genormten Fl�che
von 200 cm2 wird gesammelt und �ber
einen Trichter der Wippe zugef�hrt. Die
Wippe kippt immer dann, wenn eine bestimmte
Menge Wasser aufgesammelt
wurde. Das Wippen erzeugt Impulse, die
gez�hlt werden. 
Bereich: von 0 bis 50 mm/m2 mit einer
Me�genauigkeit von � 0,1 mm/m2



<h3>Genauigkeiten</h3>
Temperatur: � 0,5 �C<br>
Feuchte: � 2.0 % (bei 10�C..35�C)<br>
Sonne: � 2.5 % vom Endwert<br>
Druck: � 1 hPa (bei 0�C..50�C), �2 hPa unter 0�C<br>
Regen: � 0,2 mm<br>
Windrichtung: � 5?, im Norden toter Winkel von ca. 10?<br>
Windgeschwindigkeit: � 2 km/h (bei -10�C..50�C)<br>


<h3>Me�bereiche</h3>
Temperatur: von -40 � bis + 50 �, Aufl�sung 0,1 �<br>
relative Feuchte: von 0 bis 100 % Aufl�sung 0,1 %<br
absoluter Druck: von 800 hPa bis 1100hPa in 0.1 hPa Aufl�sung<br>
Sonnenenergie: von 0 bis 1500 W/m2 mit einer Aufl�sung von 1 W/m2<br>
(Spektralbereich 305..2800nm)<br>
Regenmenge: von 0 bis 50 mm mit 0,1mm Aufl�sung<br>
Windrichtung: 0 bis 350 �, Aufl�sung 1 �<br>
Windgeschwindigkeit: in km/h von 0 bis 150 km/h mit 0,1 km/h Aufl�sung<br>
-->      

<MAP NAME="MWS9-Messarea">
<AREA SHAPE=POLY COORDS="198,5,216,9,234,50,236,59,269,67,283,62,295,69,298,72,325,77,330,69,356,75,372,86,351,90,327,87,325,81,294,77,293,104,302,117,303,159,264,163,262,119,273,103,273,74,233,65,234,75,217,108,198,105,198,5" HREF="#mws9" ALT="Windrichtung">
<AREA SHAPE=POLY COORDS="53,145,53,127,57,126,58,127,61,118,66,113,74,114,82,117,89,125,87,152,77,151,76,167,80,176,119,186,121,195,131,202,59,214,57,206,55,177,69,171,70,150,58,155,53,147,53,145" HREF="#mws9" ALT="Globalstrahlung">
<AREA SHAPE=POLY COORDS="148,124,148,88,139,77,140,65,144,55,154,69,167,69,176,57,172,45,160,31,153,24,139,18,134,32,118,38,116,44,106,43,95,29,75,34,68,47,76,63,93,64,103,56,105,48,117,47,120,76,109,90,110,122,148,124" HREF="#mws9" ALT="Windgeschwindigkeit">
<AREA SHAPE=POLY COORDS="183,279,183,231,189,209,189,188,235,178,234,167,263,158,261,140,237,128,198,122,148,123,92,128,89,148,79,153,81,169,84,174,125,188,124,230,140,234,141,282,147,282,149,274,172,273,178,279,183,279" HREF="#mws9" ALT="Regenmenge">
<AREA SHAPE=POLY COORDS="275,310,291,303,291,227,283,220,271,221,270,173,236,167,236,178,191,187,191,209,204,213,202,221,188,226,186,302,200,310,202,294,233,302,269,297,275,310" HREF="#mws9" ALT="Temperatur-Luftdruck-Luftfeuchtigkeit">
</MAP>


<h2><a name="mws9"></a>Wetterstation MWS10</h2>
<p class="sensInfo">
Professionelle Wetterstation mit Mikroprozessor und Datenlogger, 7 Sensoren
fuer Temperatur, relative Luftfeuchtigkeit (auch als Taupunkt),
barometrischer Druck, Windgeschwindigkeit, Windrichtung, Windspitze, Winddurchschnitt Globalstrahlung
(Sonnenenergie) und Regenmenge. Die gesamte Sensorik und Auswertelektronik
ist in einem Edelstahlgehaeuse untergebracht. Die Daten werden ueber
digitale, serielle Schnittstellen (RS485) zum Rechner uebertragen.<br>
<img src="./MW9.jpg"> </img>


<img src="./M9-BF-o.jpg" USEMAP="#MWS9-Messarea" border="red"> </img>
</p>

<!--
<img src="./SensorDach2.jpg" width="100%"> </img>
<img src="./Temperaturfuehler1.jpg" width="100%"> </img>
<img src="./Temperaturfuehler2.jpg" width="100%"> </img>

-->

<h2>Bodentemperaturen</h2>
<p class="sensInfo">
Die Bodentemperaturen werden seit dem 18. Mai 2008 mit vier Temperaturf�hlern (Campbell Scientific 105T) 
erfasst. 
</p>

<h2>The GLOBE Program</h2>
<p class="sensInfo">
Die Anlage wurde im Januar 2004 im Zusammenhang mit der Teilnahme des Liechtensteinischen Gymnasiums am GLOBE-Projekt errichtet. 
T�glich um 14.00 Uhr werden automatisch Daten an den GLOBE-Server in Colorado geschickt.
<a href="http://www.globe.gov/">http://www.globe.gov/</a>
</p>

<h2>Browsereinstellungen</h2>
<p class="sensInfo">
Um alle Funktionalit�ten der Webseite zu benutzen, schalten Sie bitte "Javascript" in Ihrem Browser ein.
</p>
<h2>Kontakt</h2>
<p class="sensInfo">
<a href="mailto:dietmar.possner@powersurf.li">Mail</a>
</p>

<!--
<h2>Wartung und Pflege</h2>


Die MWS9 ist durch die ausgekl�gelte Sensorik nahezu wartungsfrei. Lediglich die
Tropf�ffnung im Regenauffangbeh�lter kann durch Insekten, Bl�tter o.�. verstopfen.
Sie erkennen diesen Fehler daran, da� sich bei Regen die Regenanzeige nicht
ver�ndert. In diesem Fall s�ubern Sie vorsichtig den Regentrichter.
Ein anderer Grund, weshalb eine Regenanzeige bei Niederschlag ausbleibt, kann
ein Klemmen der Regenwippe durch Insekten unterhalb des Regentrichters sein.
L�sen Sie in diesem Fall die drei Silikontropfen, die den Trichter auf der Station
fixieren und nehmen diesen vorsichtig ab. Befreien Sie dann bei Bedarf die
Regenwippe von Spinnennetzen, etc. Pr�fen Sie dann die Beweglichkeit der Wippe
und fixieren den Trichter wieder mit drei Tropfen Silikon.
�ffnen Sie auf keinen Fall das Geh�use. Es k�nnten sonst empfindliche Bauteile
Inneren der Wetterstation zerst�rt werden.
Bei jedem Eingriff in die Hard- und Software erlischt die Gew�hrleistung.
Die MWS9-Wetterstation wurde f�r den station�ren Gebrauch unter normalen
klimatischen Bedingungen (gem��igtes Klima) konzipiert. Eine Benutzung unter
extremen Bedingungen, wie z.B. auf Schiffen, mobiler Betrieb auf einem Me�fahrzeug,
etc., ist nicht angezeigt. Ebenso ist es nicht ratsam, die MWS9
Standorten aufzustellen, an denen die Wetterstation Salzwasser, etc ausgesetzt
( z.B. an Stra�en, die im Winter gesalzen werden, direkt an der K�ste, etc.).
-->