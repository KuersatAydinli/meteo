<?php
// Dateiname: db_klasse.php
class db_class
{
	
//mysql auf localhost nbinf2
//var $host = "localhost";
//var $user = "semiz";
//var $pass = "mysql";

//mysql auf localhost globe0300
var $host = "localhost";
var $user = "root";
var $pass = "root";


//mysql auf localhost globe0300
//var $host = "146.136.28.168";
//var $user = "root";
//var $pass = "";

//mysql auf pcinf58
//var $host = "146.136.4.106";
//var $user = "root";
//var $pass = "";


var $dbname = "MeteoVaduz";
var $db_link = false;

   function db_class(){
      //echo $username;
      $this->db_connect($this->host,$this->user,$this->pass,$this->dbname);
   }

   function db_connect($host,$user,$pass,$dbname){
      $this->db_link = @mysql_pconnect($host,$user,$pass)       or die ("Datenbankverbindung nicht möglich!");
      $this->db_choose($dbname);
   }

   function db_choose($dbname){
      //echo $dbname;
      @mysql_select_db($dbname)       or die ("Datenbank konnt nicht ausgewählt werden!");
    }

   function db_query($query){
      $dbResultat = @mysql_query($query, $this->db_link)       or die ("Abfrage war ungültig!".mysql_error());
      return $dbResultat;
   }
}
?>