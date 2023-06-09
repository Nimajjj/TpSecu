<?php ob_start(); ?>

<h1>You are not allowed to access this service.</h1>
<br><br>


<?php
$content = ob_get_clean();
require('base.php')
?>


