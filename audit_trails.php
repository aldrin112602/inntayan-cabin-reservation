<?php
function logUser($username, $message) {
    date_default_timezone_set('America/New_York');
    $dateAndTime =  date("Y-m-d g:i:s") . (date('A') == 'AM' ? ' PM' : ' AM');
    $logEntry = "[ $dateAndTime ][ $username ] -> $message";
    file_put_contents('audit_log.txt', $logEntry . PHP_EOL, FILE_APPEND);
}
?>
