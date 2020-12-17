<?php
$output = shell_exec('tar cvzf deploy3.tgz edit.php editor.php ingredient.php cookbook.html');
echo "<pre>$output</pre>"
?>

