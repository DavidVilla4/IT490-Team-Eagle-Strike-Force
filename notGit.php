<?php
$output = shell_exec('tar cvzf deploy1.tgz RabbitMQServer.php loggingServer.php');
echo "<pre>$output</pre>"
?>
