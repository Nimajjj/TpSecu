<?php ob_start(); ?>

<h1>Signed up</h1>
<br><br>

<?php
$content = ob_get_clean();
require('base.php')
?>
