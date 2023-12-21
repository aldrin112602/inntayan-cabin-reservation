<?php
require_once '../config.php';
require_once '../global.php';
if ( isset( $_SESSION[ 'role' ] ) ) {
    if ( !$_SESSION[ 'role' ] == 'admin' ) header( 'location: ../super_admin' );
} else header( 'location: ../index.php' );

$barangay = $_GET[ 'barangay' ] ?? null;
$municipality = null;
$province = null;
if ( !isset( $barangay ) ) header( 'location: ./' . '&barangay=' . $barangay );
$csvFilePath = './downloads/file-'. uniqid() .'.csv';
$csvFile = fopen( $csvFilePath, 'w' );

fputcsv( $csvFile, [ 'Province: ', $_SESSION[ 'province' ] ?? $province ] );
fputcsv( $csvFile, [ 'Municipality: ', $_SESSION[ 'municipality' ] ?? $municipality ] );
fputcsv( $csvFile, [ 'Barangay: ', $_SESSION[ 'barangay' ] ?? $barangay ] );
fputcsv( $csvFile, [ '' ] );
fputcsv( $csvFile, [ 'Month: ',  date( 'F' ) ] );
fputcsv( $csvFile, [ 'Year: ',  date( 'Y' ) ] );
fputcsv( $csvFile, [ '' ] );
 
$query = "SELECT DISTINCT purok
FROM (
    SELECT purok FROM survey_form_records_household_member WHERE barangay = '$barangay'
    UNION
    SELECT purok FROM survey_form_records_wife WHERE barangay = '$barangay'
    UNION
    SELECT purok FROM survey_form_records_husband WHERE barangay = '$barangay'
) AS combined_puroks
ORDER BY CAST(SUBSTRING(purok FROM 7) AS INT) ASC";
fputcsv( $csvFile, [ 'Total # of Purok',  mysqli_num_rows( mysqli_query( $conn, $query ) ) ] );
fputcsv( $csvFile, [ 'Total # of Families', getCount( 'survey_form_records_husband', $barangay ) ] );
fputcsv( $csvFile, [ 'Total # of Household', getCount( 'survey_form_records_household_member', $barangay ) ] );
fputcsv( $csvFile, [ 'Total # of Male', getCountRowsMale( $barangay ) ] );
fputcsv( $csvFile, [ 'Total # of Female', getCountRowsFemale( $barangay ) ] );
fputcsv( $csvFile, [ 'Total # of Population', getPopulation( $barangay ) ] );


fputcsv( $csvFile, [ '' ] );
fputcsv( $csvFile, [ 'Family Profile' ] );
$query = "SELECT * FROM survey_form_records_husband WHERE barangay = '$barangay'";
$result = $conn->query( $query );
fputcsv( $csvFile, [ 'id', 'purok', 'barangay', 'municipality', 'province', 'name', 'status', 'type', 'dateOfBirth', 'educationalAttainment', 'age', 'sex', 'birthPlace', 'occupation', 'placeOfWork', 'religion', 'ethnicGroup' ] );
while ( $row = $result->fetch_assoc() ) {
    $rowWithoutUniqueId = array_diff_key($row, array_flip(['unique_id']));
    fputcsv($csvFile, $rowWithoutUniqueId);
}
$query = "SELECT * FROM survey_form_records_wife WHERE barangay = '$barangay'";
$result = $conn->query( $query );
while ( $row = $result->fetch_assoc() ) {
    $rowWithoutUniqueId = array_diff_key($row, array_flip(['unique_id']));
    fputcsv($csvFile, $rowWithoutUniqueId);
}
$query = "SELECT * FROM survey_form_records_children WHERE barangay = '$barangay'";
$result = $conn->query( $query );
while ( $row = $result->fetch_assoc() ) {
    $rowWithoutUniqueId = array_diff_key($row, array_flip(['unique_id']));
    fputcsv($csvFile, $rowWithoutUniqueId);
}
$query = "SELECT * FROM survey_form_records_household_member WHERE barangay = '$barangay'";
$result = $conn->query( $query );
while ( $row = $result->fetch_assoc() ) {
    $rowWithoutUniqueId = array_diff_key($row, array_flip(['unique_id']));
    fputcsv($csvFile, $rowWithoutUniqueId);
}



fputcsv( $csvFile, [ '' ] );
$query = "SELECT * FROM survey_form_records WHERE barangay = '$barangay'";
$result = $conn->query( $query );
fputcsv( $csvFile, [ 'id', 'purok', 'barangay', 'municipality', 'province', 'artificialFamilyPlanningMethod', 'permanentFamilyPlanningMethod', 'naturalFamilyPlanningMethod', 'attendedResponsibleParentingMovementClass', 'typeOfHousingUnitOccupied', 'subTypeOfHousingUnitOccupied', 'typeOfHouseLightUsed', 'typeOfWaterSupply', 'typeOfToilet', 'typeOfGarbageDisposal', 'communicationFacility', 'transportFacility', 'agriculturalProduct', 'poultryNumberOfHeadsChicken', 'poultryNumberOfHeadsDuck', 'poultryNumberOfHeadsGeese', 'poultryNumberOfHeadsTurkey', 'poultryOthers', 'poultryNumberOfHeadsOthers', 'livestockNumberPig', 'livestockNumberGoat', 'livestockNumberSheep', 'livestockNumberCoat', 'livestockNumberCarabao', 'livestockNumberHorse', 'othersLivestock', 'livestockNumberOthers', 'otherSourceOfIncome', 'fishpondOwned', 'fishpondOwnedArea', 'landOwned', 'landOwnedRiceFieldArea', 'landOwnedCornFieldArea', 'land', 'caretakerRiceArea', 'caretakerCornArea', 'caretakerOthersLandOwned', 'monthlyAverageFamilyIncome' ] );
while ( $row = $result->fetch_assoc() ) {
    $rowWithoutUniqueId = array_diff_key($row, array_flip(['unique_id']));
    fputcsv($csvFile, $rowWithoutUniqueId);
}

fputcsv( $csvFile, [ '' ] );
fputcsv( $csvFile, [ '' ] );
fputcsv( $csvFile, [ 'Single Age (' . date( 'F Y' ) . ')' ] );

$query = "SELECT DISTINCT purok
FROM (
    SELECT purok FROM survey_form_records_household_member WHERE barangay = '$barangay'
    UNION
    SELECT purok FROM survey_form_records_wife WHERE barangay = '$barangay'
    UNION
    SELECT purok FROM survey_form_records_children WHERE barangay = '$barangay'
    UNION
    SELECT purok FROM survey_form_records_husband WHERE barangay = '$barangay'
) AS combined_puroks
ORDER BY CAST(SUBSTRING(purok FROM 7) AS INT) ASC";
$result = $conn->query( $query );


fputcsv( $csvFile, [' ', 'Male'] );
$purok = ['Age'];
while ( $row = $result->fetch_assoc() ) $purok[] = $row['purok'];
$purok[] = 'Total';
fputcsv( $csvFile, $purok );

$col_count = ['Total'];

foreach($purok as $value) {
    if($value == 'Age') continue;
    $col_count[$value] = 0;
}

$tableData = [];
for($age = 1; $age <= 100; $age++) {
    $row_count = [$age];
    $row_total = 0;

    foreach ($purok as $value) {
        if(in_array($value, ['Age', 'Total'])) continue;
        $query = "SELECT COUNT(*) AS row_count
                    FROM (
                        SELECT age FROM survey_form_records_household_member WHERE barangay = '$barangay' AND age = $age AND status = 'Single' AND purok = '$value' AND sex = 'Male'
                        UNION ALL
                        SELECT age FROM survey_form_records_wife WHERE barangay = '$barangay' AND age = $age AND status = 'Single' AND purok = '$value' AND sex = 'Male'
                        UNION ALL
                        SELECT age FROM survey_form_records_children WHERE barangay = '$barangay' AND age = $age AND status = 'Single' AND purok = '$value' AND sex = 'Male'
                        UNION ALL
                        SELECT age FROM survey_form_records_husband WHERE barangay = '$barangay' AND age = $age AND status = 'Single' AND purok = '$value' AND sex = 'Male'
                    ) AS combined_rows";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $row_count[] = $row["row_count"];
            $row_total += (int) $row["row_count"];
        } else {
            $row_count[] = 0;
        }
    }

    $row_count[] = $row_total;
    $tableData[] = $row_count;
    
    fputcsv( $csvFile, $row_count );
}
$columnTotals = ['Total'];

for ($colIndex = 1; $colIndex < count($tableData[0]); $colIndex++) {
    $columnTotal = 0;
    for ($rowIndex = 0; $rowIndex < count($tableData); $rowIndex++) {
        $columnTotal += $tableData[$rowIndex][$colIndex];
    }
    $columnTotals[] = $columnTotal; 
}


fputcsv( $csvFile, $columnTotals );

fputcsv( $csvFile, [''] );
fputcsv( $csvFile, [''] );


$query = "SELECT DISTINCT purok
FROM (
    SELECT purok FROM survey_form_records_household_member WHERE barangay = '$barangay'
    UNION
    SELECT purok FROM survey_form_records_wife WHERE barangay = '$barangay'
    UNION
    SELECT purok FROM survey_form_records_children WHERE barangay = '$barangay'
    UNION
    SELECT purok FROM survey_form_records_husband WHERE barangay = '$barangay'
) AS combined_puroks
ORDER BY CAST(SUBSTRING(purok FROM 7) AS INT) ASC";
$result = $conn->query( $query );


fputcsv( $csvFile, [' ', 'Female'] );
$purok = ['Age'];
while ( $row = $result->fetch_assoc() ) $purok[] = $row['purok'];
$purok[] = 'Total';
fputcsv( $csvFile, $purok );

$col_count = ['Total'];

foreach($purok as $value) {
    if($value == 'Age') continue;
    $col_count[$value] = 0;
}

$tableData = [];
for($age = 1; $age <= 100; $age++) {
    $row_count = [$age];
    $row_total = 0;

    foreach ($purok as $value) {
        if(in_array($value, ['Age', 'Total'])) continue;
        $query = "SELECT COUNT(*) AS row_count
                    FROM (
                        SELECT age FROM survey_form_records_household_member WHERE barangay = '$barangay' AND age = $age AND status = 'Single' AND purok = '$value' AND sex = 'Female'
                        UNION ALL
                        SELECT age FROM survey_form_records_wife WHERE barangay = '$barangay' AND age = $age AND status = 'Single' AND purok = '$value' AND sex = 'Female'
                        UNION ALL
                        SELECT age FROM survey_form_records_children WHERE barangay = '$barangay' AND age = $age AND status = 'Single' AND purok = '$value' AND sex = 'Female'
                        UNION ALL
                        SELECT age FROM survey_form_records_husband WHERE barangay = '$barangay' AND age = $age AND status = 'Single' AND purok = '$value' AND sex = 'Female'
                    ) AS combined_rows";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $row_count[] = $row["row_count"];
            $row_total += (int) $row["row_count"];
        } else {
            $row_count[] = 0;
        }
    }

    $row_count[] = $row_total;
    $tableData[] = $row_count;
    
    fputcsv( $csvFile, $row_count );
}
$columnTotals = ['Total'];

for ($colIndex = 1; $colIndex < count($tableData[0]); $colIndex++) {
    $columnTotal = 0;
    for ($rowIndex = 0; $rowIndex < count($tableData); $rowIndex++) {
        $columnTotal += $tableData[$rowIndex][$colIndex];
    }
    $columnTotals[] = $columnTotal; 
}


fputcsv( $csvFile, $columnTotals );




fclose( $csvFile );
header( 'location: ./?download=' . base64_encode( $csvFilePath ) . '&barangay=' . $barangay );
?>
