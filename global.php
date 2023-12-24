    <?php

    function validate_post_data( $post_data ) {
        $sanitized_data = array();
        foreach ( $post_data as $key=>$value ) {
            if ( $key === 'password' ) {
                $sanitized_data[ $key ] = trim( $value );
            } elseif ( is_string( $value ) ) {
                $sanitized_data[ $key ] = $value;
            } else {
                $sanitized_data[ $key ] = $value;
            }
        }
        return $sanitized_data;
    }

    function isDataExists( $table, $select, $condition ) {
        global $conn;
        $query = "SELECT ".$select." FROM ".$table." WHERE ".$condition;
        return( $conn->query( $query )->num_rows>0 );
    }

    function getRows( $condition, $tableName ) {
        global $conn;
        $sql = !empty( $condition ) ? "SELECT * FROM $tableName WHERE $condition" : "SELECT * FROM $tableName";
        $result = $conn->query( $sql );
        $rows = [];
        if ( $result && $result->num_rows>0 ) {
            while( $row = $result->fetch_assoc() ) {
                $rows[] = $row;
            }
        }
        return $rows;
    }


    function filter_and_implode( $input ) {
        if ( is_array( $input ) ) {
            $filtered_array = array_filter( $input, 'trim' );
            $filtered_array = array_map( 'trim', $filtered_array );
            $imploded_string = implode( ', ', $filtered_array );
            return htmlspecialchars( $imploded_string, ENT_QUOTES, 'UTF-8' );
        } elseif ( is_string( $input ) ) {
            return htmlspecialchars( trim( $input ), ENT_QUOTES, 'UTF-8' );
        } else {
            return $input;
        }
    }

function timeAgo($timestamp) {
    date_default_timezone_set('Asia/Manila');
    $currentTimestamp = time();
    $timeDifference = $currentTimestamp - strtotime($timestamp);
    $seconds = abs($timeDifference);
    $minutes = round($seconds / 60);
    $hours   = round($seconds / 3600);
    $days    = round($seconds / 86400);
    $weeks   = round($seconds / 604800);
    $months  = round($seconds / 2629440);
    $years   = round($seconds / 31553280);

    if ($seconds <= 60) {
        return ($seconds == 1) ? "1 second ago" : "$seconds seconds ago";
    } elseif ($minutes <= 60) {
        return ($minutes == 1) ? "1 minute ago" : "$minutes minutes ago";
    } elseif ($hours <= 24) {
        return ($hours == 1) ? "1 hour ago" : "$hours hours ago";
    } elseif ($days <= 7) {
        return ($days == 1) ? "yesterday" : "$days days ago";
    } elseif ($weeks <= 4.3) {
        return ($weeks == 1) ? "1 week ago" : "$weeks weeks ago";
    } elseif ($months <= 12) {
        return ($months == 1) ? "1 month ago" : "$months months ago";
    } else {
        return ($years == 1) ? "1 year ago" : "$years years ago";
    }
}