<?php
$FH = fopen("../Saves/SaveData.json", 'w');
$JSON = $_POST['JSONText'];
fwrite($FH, $JSON);
fclose($FH);

$FH = fopen("../Saves/SaveData.json", 'r');
$OP = fread($FH, filesize("..//Saves//SaveData.json"));
$jsonResponse = json_encode($OP);

echo $jsonResponse;
?>
