<?


	//$root = "E:\MyData\Projects\MeteoVaduz\Archive\jpgraph-1.13\src\Examples";
	$root = "./";

    function doSEARCH()
    {
    	global $root;
        for($i=0; $i<count($root); $i++)
        {
            //unset($this->temp);

            $handle = @opendir($root);
            while ($file = @readdir ($handle))
            {
                if (eregi("^\.{1,2}$",$file))
                {
                    continue;
                }
                //echo $root."/$file";
                echo "<a href='$root/$file'>$root/$file</a>";
                echo "<img border='0' src='$root/$file' width='300' height='200'>";
                echo "<br></br>";
             }
            @closedir($handle);

        }
    }


doSEARCH();


?>