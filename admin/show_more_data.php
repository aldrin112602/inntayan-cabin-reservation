<?php
require_once( '../config.php' );
require_once( '../global.php' );
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && $_POST[ 'id' ] && $_POST[ 'table_name' ] ) {
    $jsonResponse = json_encode( getRows( "id = {$_POST['id']}", $_POST[ 'table_name' ] ) );
    header( 'Content-Type: application/json' );
    echo $jsonResponse;
}

?>