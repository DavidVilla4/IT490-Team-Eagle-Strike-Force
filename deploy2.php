<?php
$output = shell_exec('tar cvzf deploy2.tgz recipe.php recipe.html recipeConnect.php recipe.txt');
echo "<pre>$output</pre>"
?>

