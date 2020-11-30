<?php
$output = shell_exec('tar czvf clientServerZip.tgz ClientServer.php dataPull.ini');
$output2 = shell_exec('tar czvf logFileZip.tgz loggingServer.php logging.ini logFile.txt loggingClient.php');
$output3 = shell_exec('tar czvf serverZip.tgz testRabbitMQServer.php testRabbitMQClient.php testRabbitMQ.ini');
echo $output . "" . $output2 . "" . $output3;
?>
