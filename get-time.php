<?php 
    date_default_timezone_set('America/New_York');
    echo 'Time: ' .  date("g:i:s") . (date('A') == 'AM' ? ' PM' : ' AM');
?>