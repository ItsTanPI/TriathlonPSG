<?php
$FH = fopen("../Saves/SaveData.json", 'w');
$JSON = $_POST['JSONText'];
fwrite($FH, $JSON);
fclose($FH);

$FH = fopen("../Saves/SaveData.json", 'r');
$OP = fread($FH );

$jsonResponse = json_encode($OP);

echo $jsonResponse;
?>
