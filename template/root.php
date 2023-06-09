<?php ob_start(); ?>

<h1>Root</h1>
<br><br>

<?php
$content = ob_get_clean();
require('base.php')
?>

