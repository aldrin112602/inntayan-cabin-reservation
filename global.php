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

    function getCurrentPage() {
        $protocol = isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] === 'on'? 'https://' : 'http://';
        $host = $_SERVER[ 'HTTP_HOST' ];
        $request_uri = $_SERVER[ 'REQUEST_URI' ];
        if ( empty( $request_uri ) ) {
            $request_uri = '/';
        }
        $current_page_url = $protocol.$host.$request_uri;
        return $current_page_url;
    }
    ;

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

    function sanitize($input) {
        return filter_and_implode($input);
    }

    function hideUniqueID( $uniqueID ) {
        $hiddenPart = '*****' . substr( $uniqueID, -8 );
        return $hiddenPart;
    }

    function hideKeys($uniqueID) {
        $length = strlen($uniqueID);
        $numCharsToHide = (int) ceil($length / 2);
        if ($numCharsToHide >= $length) {
            return str_repeat('*', $length);
        } else {
            $hiddenPart = str_repeat('*', $numCharsToHide) . substr($uniqueID, -$numCharsToHide);
            return $hiddenPart;
        }
    }
    function getCount($table, $brgy = null, $filter_by = null) {
        $unique_id = $_SESSION[ 'unique_id' ];
        global $conn;
        if(empty($brgy)) {
            $sql = "SELECT COUNT(*) as count FROM ". $table ." WHERE unique_id = '$unique_id'" . $filter_by;
        } else {
            $sql = "SELECT COUNT(*) as count FROM ". $table ." WHERE barangay = '$brgy'" . $filter_by;
        }
        $result = $conn->query($sql);
        $count = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row["count"];
        }
        return $count;
    }

function getCountRowsFemale($brgy = null, $filter_by = null) {
    global $conn;
    $unique_id = $_SESSION['unique_id'];
    
    if(!empty($brgy)) {
        $sql = "SELECT COUNT(*) AS row_count
    FROM survey_form_records_wife AS wife
    JOIN survey_form_records_household_member AS household_member
        ON wife.barangay = household_member.barangay
    JOIN survey_form_records_children AS children
        ON wife.barangay = children.barangay
    WHERE wife.barangay = '$brgy'
        AND wife.sex = 'Female'
        AND household_member.sex = 'Female'
        AND children.sex = 'Female'";
    } else {
        $sql = "SELECT COUNT(*) AS row_count
                FROM survey_form_records_wife AS wife
                JOIN survey_form_records_household_member AS household_member
                    ON wife.unique_id = household_member.unique_id
                JOIN survey_form_records_children AS children
                    ON wife.unique_id = children.unique_id
                WHERE wife.unique_id = '$unique_id'
                    AND wife.sex = 'Female'
                    AND household_member.sex = 'Female'
                    AND children.sex = 'Female'";
    }

    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }

    $count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row["row_count"] - 1;
    }
    return $count < 0 ? 0 : $count;
}

function getCountRowsMale($barangay = null, $filter_by = null) {
    global $conn;
    $unique_id = $_SESSION['unique_id'];
    if(!empty($barangay)) {
        $sql = "SELECT COUNT(*) AS row_count
                FROM survey_form_records_husband AS husband
                JOIN survey_form_records_household_member AS household_member
                    ON husband.barangay = household_member.barangay
                JOIN survey_form_records_children AS children
                    ON husband.barangay = children.barangay
                WHERE husband.barangay = '$barangay'
                    AND husband.sex = 'Male'
                    AND household_member.sex = 'Male'
                    AND children.sex = 'Male'";
    } else {
        $sql = "SELECT COUNT(*) AS row_count
                FROM survey_form_records_husband AS husband
                JOIN survey_form_records_household_member AS household_member
                    ON husband.unique_id = household_member.unique_id
                JOIN survey_form_records_children AS children
                    ON husband.unique_id = children.unique_id
                WHERE husband.unique_id = '$unique_id'
                    AND husband.sex = 'Male'
                    AND household_member.sex = 'Male'
                    AND children.sex = 'Male'";
    }
     

    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }

    $count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row["row_count"];
    }
    return $count;
}


function getPopulation($barangay = null, $filter_by = null) {
    global $conn;
    $tables = array('survey_form_records_husband', 'survey_form_records_wife', 'survey_form_records_children', 'survey_form_records_household_member');
    // global $barangay;
    function getRowCountt($conn, $table, $barangay, $f) {
        if(!empty($barangay)) {
            $sql = "SELECT COUNT(*) AS count FROM $table WHERE barangay = '$barangay'" . $f;
        } else {
            $sql = "SELECT COUNT(*) AS count FROM $table";
        }
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
    $totalPopulation = 0;
    foreach($tables as $table) $totalPopulation += (int) getRowCountt($conn, $table, $barangay, $filter_by);
    return $totalPopulation;
}


function getEthnicGroupPercentages($tableName, $filter_by = null) {
    global $conn;
    $sessionId = $_SESSION['unique_id'] ?? '';
    if (!empty($sessionId)) {
        $ethnicGroupsQuery = "
            SELECT ethnicGroup, COUNT(*) AS count
            FROM $tableName
            WHERE unique_id = '$sessionId'
            GROUP BY ethnicGroup";
        $result = $conn->query($ethnicGroupsQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ethnicGroup = $row['ethnicGroup'];
                $count = $row['count'];
                $data[$ethnicGroup] = $count;
            }
        }
        $totalCount = array_sum($data);
        $percentages = array();
        foreach ($data as $ethnicGroup => $count) {
            $percentage = number_format(($count / $totalCount) * 100, 2);
            $percentages[$ethnicGroup] = $percentage;
            
        }
        return $percentages;
    } else {
        return array();
    }
}

function getEthnicGroupCounts($tableName, $filter_by = null) {
    global $conn;
    $sessionId = $_SESSION['unique_id'] ?? '';
    if (!empty($sessionId)) {
        $ethnicGroupsQuery = "
            SELECT ethnicGroup, COUNT(*) AS count
            FROM $tableName
            WHERE unique_id = '$sessionId'
            GROUP BY ethnicGroup";
        $result = $conn->query($ethnicGroupsQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ethnicGroup = $row['ethnicGroup'];
                $count = $row['count'];
                $data[$ethnicGroup] = $count;
            }
        }
        return $data;
    } else {
        return array();
    }
}


function getCivilStatusPercentages($tableName, $filter_by = null) {
    global $conn;
    $sessionId = $_SESSION['unique_id'] ?? '';
    if (!empty($sessionId)) {
        $statusQuery = "
            SELECT status, COUNT(*) AS count
            FROM $tableName
            WHERE unique_id = '$sessionId'
            GROUP BY status";
        $result = $conn->query($statusQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['status'];
                $count = $row['count'];
                $data[$status] = $count;
            }
        }
        $totalCount = array_sum($data);
        $percentages = array();
        foreach ($data as $status => $count) {
            $percentage = number_format(($count / $totalCount) * 100, 2);
            $percentages[$status] = $percentage;
        }
        return $percentages;
    } else {
        return array();
    }
}


function getCivilStatusCounts($tableName, $filter_by = null) {
    global $conn;
    $sessionId = $_SESSION['unique_id'] ?? '';
    if (!empty($sessionId)) {
        $statusQuery = "
            SELECT status, COUNT(*) AS count
            FROM $tableName
            WHERE unique_id = '$sessionId'
            GROUP BY status";
        $result = $conn->query($statusQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['status'];
                $count = $row['count'];
                $data[$status] = $count;
            }
        }
        return $data; // This returns the counts of civil statuses.
    } else {
        return array();
    }
}




function getReligionPercentages($tableName, $filter_by = null) {
    global $conn;
    $sessionId = $_SESSION['unique_id'] ?? '';
    if (!empty($sessionId)) {
        $religionQuery = "
            SELECT religion, COUNT(*) AS count
            FROM $tableName
            WHERE unique_id = '$sessionId'
            GROUP BY religion";
        $result = $conn->query($religionQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $religion = $row['religion'];
                $count = $row['count'];
                $data[$religion] = $count;
            }
        }
        $totalCount = array_sum($data);
        $percentages = array();
        foreach ($data as $religion => $count) {
            $percentage = number_format(($count / $totalCount) * 100, 2);
            $percentages[$religion] = $percentage;
        }
        return $percentages;
    } else {
        return array();
    }
}

function getReligionCounts($tableName, $filter_by = null) {
    global $conn;
    $sessionId = $_SESSION['unique_id'] ?? '';
    if (!empty($sessionId)) {
        $religionQuery = "
            SELECT religion, COUNT(*) AS count
            FROM $tableName
            WHERE unique_id = '$sessionId'
            GROUP BY religion";
        $result = $conn->query($religionQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $religion = $row['religion'];
                $count = $row['count'];
                $data[$religion] = $count;
            }
        }
        return $data; // This returns the counts of religions.
    } else {
        return array();
    }
}




// for superAdmin
function getPopulationS($brgy) {
    global $conn;

    $tables = array('survey_form_records_husband', 'survey_form_records_wife', 'survey_form_records_children', 'survey_form_records_household_member');
    function getRowCountt($conn, $table, $brgy) {
        $sql = "SELECT COUNT(*) AS count FROM $table WHERE barangay = '$brgy'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }
    $totalPopulation = 0;
    foreach($tables as $table) $totalPopulation += (int) getRowCountt($conn, $table, $brgy);
    return $totalPopulation;
}

function getCountRowsFemaleS($barangay) {
    global $conn;
    $sql = "SELECT COUNT(*) AS row_count
    FROM survey_form_records_wife AS wife
    JOIN survey_form_records_household_member AS household_member
        ON wife.barangay = household_member.barangay
    JOIN survey_form_records_children AS children
        ON wife.barangay = children.barangay
    WHERE wife.barangay = '$barangay'
        AND wife.sex = 'Female'
        AND household_member.sex = 'Female'
        AND children.sex = 'Female'";

    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }

    $count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row["row_count"];
    }
    return $count;
}

function getCountRowsMaleS($barangay) {
    global $conn;
    
     $sql = "SELECT COUNT(*) AS row_count
    FROM survey_form_records_husband AS husband
    JOIN survey_form_records_household_member AS household_member
        ON husband.barangay = household_member.barangay
    JOIN survey_form_records_children AS children
        ON husband.barangay = children.barangay
    WHERE husband.barangay = '$barangay'
        AND husband.sex = 'Male'
        AND household_member.sex = 'Male'
        AND children.sex = 'Male'";

    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }

    $count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row["row_count"];
    }
    return $count;
}


function getEthnicGroupPercentagesS($tableName, $barangay) {
    global $conn;
    if (!empty($barangay)) {
        $ethnicGroupsQuery = "
            SELECT ethnicGroup, COUNT(*) AS count
            FROM $tableName
            WHERE barangay = '$barangay'
            GROUP BY ethnicGroup";
        $result = $conn->query($ethnicGroupsQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ethnicGroup = $row['ethnicGroup'];
                $count = $row['count'];
                $data[$ethnicGroup] = $count;
            }
        }
        $totalCount = array_sum($data);
        $percentages = array();
        foreach ($data as $ethnicGroup => $count) {
            $percentage = number_format(($count / $totalCount) * 100, 2);
            $percentages[$ethnicGroup] = $percentage;
        }
        return $percentages;
    } else {
        return array();
    }
}

function getEthnicGroupCountsS($tableName, $barangay) {
    global $conn;
    if (!empty($barangay)) {
        $ethnicGroupsQuery = "
            SELECT ethnicGroup, COUNT(*) AS count
            FROM $tableName
            WHERE barangay = '$barangay'
            GROUP BY ethnicGroup";
        $result = $conn->query($ethnicGroupsQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ethnicGroup = $row['ethnicGroup'];
                $count = $row['count'];
                $data[$ethnicGroup] = $count;
            }
        }
        return $data; // This returns the counts of ethnic groups for a specific barangay.
    } else {
        return array();
    }
}



function getCivilStatusPercentagesS($tableName, $barangay) {
    global $conn;
    if (!empty($barangay)) {
        $statusGroupsQuery = "
            SELECT status, COUNT(*) AS count
            FROM $tableName
            WHERE barangay = '$barangay'
            GROUP BY status";
        $result = $conn->query($statusGroupsQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['status'];
                $count = $row['count'];
                $data[$status] = $count;
            }
        }
        $totalCount = array_sum($data);
        $percentages = array();
        foreach ($data as $statusGroup => $count) {
            $percentage = number_format(($count / $totalCount) * 100, 2);
            $percentages[$statusGroup] = $percentage;
        }
        return $percentages;
    } else {
        return array();
    }
}

function getCivilStatusCountsS($tableName, $barangay) {
    global $conn;
    if (!empty($barangay)) {
        $statusGroupsQuery = "
            SELECT status, COUNT(*) AS count
            FROM $tableName
            WHERE barangay = '$barangay'
            GROUP BY status";
        $result = $conn->query($statusGroupsQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['status'];
                $count = $row['count'];
                $data[$status] = $count;
            }
        }
        return $data; // This returns the counts of civil statuses for a specific barangay.
    } else {
        return array();
    }
}




function getReligionPercentagesS($tableName, $barangay) {
    global $conn;
    if (!empty($barangay)) {
        $religionGroupsQuery = "
            SELECT religion, COUNT(*) AS count
            FROM $tableName
            WHERE barangay = '$barangay'
            GROUP BY religion";
        $result = $conn->query($religionGroupsQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $religion = $row['religion'];
                $count = $row['count'];
                $data[$religion] = $count;
            }
        }
        $totalCount = array_sum($data);
        $percentages = array();
        foreach ($data as $religionGroup => $count) {
            $percentage = number_format(($count / $totalCount) * 100, 2);
            $percentages[$religionGroup] = $percentage;
        }
        return $percentages;
    } else {
        return array();
    }
}

function getReligionCountsS($tableName, $barangay) {
    global $conn;
    if (!empty($barangay)) {
        $religionGroupsQuery = "
            SELECT religion, COUNT(*) AS count
            FROM $tableName
            WHERE barangay = '$barangay'
            GROUP BY religion";
        $result = $conn->query($religionGroupsQuery);
        $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $religion = $row['religion'];
                $count = $row['count'];
                $data[$religion] = $count;
            }
        }
        return $data; // This returns the counts of religions for a specific barangay.
    } else {
        return array();
    }
}



