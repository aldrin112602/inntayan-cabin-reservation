<?php

require_once( '../global.php' );
require_once './audit_trails.php';

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST') {

    $purok = filter_and_implode( $_POST[ 'purok' ] ?? '' );
    $barangay = $_SESSION[ 'barangay' ];
    $municipality = $_SESSION[ 'municipality' ];
    $province = $_SESSION[ 'province' ];

    $unique_id = $_SESSION[ 'unique_id' ];

    
    $household_id = $_POST['household_id'] ?? NULL;
    $belongs_to = $_POST['belongs_to'] ?? NULL;
    $household = $_POST['house$household'] ?? NULL;

    $name = $_POST[ 'name' ] ?? [];
    $name = explode( ', ', filter_and_implode( $name ) );

    $life_status = $_POST[ 'option' ] ?? [];
    $life_status = explode( ', ', filter_and_implode( $life_status ) );

    $status = $_POST[ 'status' ] ?? [];
    $status = explode( ', ', filter_and_implode( $status ) );

    $type = $_POST[ 'type' ] ?? [];
    $type = explode( ', ', filter_and_implode( $type ) );

    $dateOfBirth = $_POST[ 'dateOfBirth' ] ?? [];
    $dateOfBirth = explode( ',', filter_and_implode( $dateOfBirth ) );

    $age = $_POST[ 'age' ] ?? [];
    $age = explode( ', ', filter_and_implode( $age ) );

    $educationalAttainment = $_POST[ 'educationalAttainment' ] ?? [];
    $educationalAttainment = explode( ',', filter_and_implode( $educationalAttainment ) );

    $sex = $_POST[ 'sex' ] ?? [];
    $sex = explode( ', ', filter_and_implode( $sex ) );

    $birthPlace = $_POST[ 'birthPlace' ] ?? [];
    $birthPlace = explode( ', ', filter_and_implode( $birthPlace ) );

    $occupation = $_POST[ 'occupation' ] ?? [];
    $occupation = explode( ', ', filter_and_implode( $occupation ) );

    $placeOfWork = $_POST[ 'placeOfWork' ] ?? [];
    $placeOfWork = explode( ', ', filter_and_implode( $placeOfWork ) );

    $religion = $_POST[ 'religion' ] ?? [];
    $religion = explode( ',', filter_and_implode( $religion ) );

    $ethnicGroup = $_POST[ 'ethnicGroup' ] ?? [];
    $ethnicGroup = explode( ',', filter_and_implode( $ethnicGroup ) );

    $artificialFamilyPlanningMethod = $_POST[ 'artificialFamilyPlanningMethod' ] ?? [];
    $artificialFamilyPlanningMethod = filter_and_implode( $artificialFamilyPlanningMethod );

    $permanentFamilyPlanningMethod = $_POST[ 'permanentFamilyPlanningMethod' ] ?? [];
    $permanentFamilyPlanningMethod = filter_and_implode( $permanentFamilyPlanningMethod );

    $naturalFamilyPlanningMethod = $_POST[ 'naturalFamilyPlanningMethod' ] ?? [];
    $naturalFamilyPlanningMethod = filter_and_implode( $naturalFamilyPlanningMethod );

    $attendedResponsibleParentingMovementClass = filter_and_implode( $_POST[ 'attendedResponsibleParentingMovementClass' ] ?? '' );

    $typeOfHousingUnitOccupied = filter_and_implode( $_POST[ 'typeOfHousingUnitOccupied' ] ?? '' );

    $subTypeOfHousingUnitOccupied = $_POST[ 'subTypeOfHousingUnitOccupied' ] ?? [];
    $subTypeOfHousingUnitOccupied = filter_and_implode( $subTypeOfHousingUnitOccupied );

    $typeOfHouseLightUsed = filter_and_implode( $_POST[ 'typeOfHouseLightUsed' ] ?? '' );

    $typeOfWaterSupply = $_POST[ 'typeOfWaterSupply' ] ?? [];
    $typeOfWaterSupply = filter_and_implode( $typeOfWaterSupply );

    $typeOfToilet = filter_and_implode( $_POST[ 'typeOfToilet' ] ?? '' );

    $typeOfGarbageDisposal = $_POST[ 'typeOfGarbageDisposal' ] ?? [];
    $typeOfGarbageDisposal = filter_and_implode( $typeOfGarbageDisposal );

    $communicationFacility = $_POST[ 'communicationFacility' ] ?? [];
    $communicationFacility = filter_and_implode( $communicationFacility );

    $transportFacility = $_POST[ 'transportFacility' ] ?? [];
    $transportFacility = filter_and_implode( $transportFacility );

    $agriculturalProduct = $_POST[ 'agriculturalProduct' ] ?? [];
    $agriculturalProduct = filter_and_implode( $agriculturalProduct );

    $poultryNumberOfHeadsChicken = filter_and_implode( $_POST[ 'poultryNumberOfHeadsChicken' ] ?? '' );
    $poultryNumberOfHeadsDuck = filter_and_implode( $_POST[ 'poultryNumberOfHeadsDuck' ] ?? '' );
    $poultryNumberOfHeadsGeese = filter_and_implode( $_POST[ 'poultryNumberOfHeadsGeese' ] ?? '' );
    $poultryNumberOfHeadsTurkey = filter_and_implode( $_POST[ 'poultryNumberOfHeadsTurkey' ] ?? '' );
    $poultryOthers = filter_and_implode( $_POST[ 'poultryOthers' ] ?? '' );
    $poultryNumberOfHeadsOthers = filter_and_implode( $_POST[ 'poultryNumberOfHeadsOthers' ] ?? '' );

    $livestockNumberPig = filter_and_implode( $_POST[ 'livestockNumberPig' ] ?? '' );
    $livestockNumberGoat = filter_and_implode( $_POST[ 'livestockNumberGoat' ] ?? '' );
    $livestockNumberSheep = filter_and_implode( $_POST[ 'livestockNumberSheep' ] ?? '' );
    $livestockNumberCoat = filter_and_implode( $_POST[ 'livestockNumberCoat' ] ?? '' );
    $livestockNumberCarabao = filter_and_implode( $_POST[ 'livestockNumberCarabao' ] ?? '' );
    $livestockNumberHorse = filter_and_implode( $_POST[ 'livestockNumberHorse' ] ?? '' );
    $othersLivestock = filter_and_implode( $_POST[ 'othersLivestock' ] ?? '' );
    $livestockNumberOthers = filter_and_implode( $_POST[ 'livestockNumberOthers' ] ?? '' );

    $otherSourceOfIncome = filter_and_implode( $_POST[ 'otherSourceOfIncome' ] ?? '' );
    $fishpondOwned = filter_and_implode( $_POST[ 'fishpondOwned' ] ?? '' );
    $fishpondOwnedArea = filter_and_implode( $_POST[ 'fishpondOwnedArea' ] ?? '' );

    $landOwned = $_POST[ 'landOwned' ] ?? [];
    $landOwned = filter_and_implode( $landOwned );

    $landOwnedRiceFieldArea = filter_and_implode( $_POST[ 'landOwnedRiceFieldArea' ] ?? '' );

    $landOwnedCornFieldArea = filter_and_implode( $_POST[ 'landOwnedCornFieldArea' ] ?? '' );

    $land = $_POST[ 'land' ] ?? [];
    $land = filter_and_implode( $land );

    $caretakerRiceArea = filter_and_implode( $_POST[ 'caretakerRiceArea' ] ?? '' );
    $caretakerCornArea = filter_and_implode( $_POST[ 'caretakerCornArea' ] ?? '' );
    $caretakerOthersLandOwned = filter_and_implode( $_POST[ 'caretakerOthersLandOwned' ] ?? '' );
    $monthlyAverageFamilyIncome = filter_and_implode( $_POST[ 'monthlyAverageFamilyIncome' ] ?? '' );

    $success = true;

    // husband, wife, children, household
    for ( $i = 0; $i < count( $type ); $i++ ) {
        $stat = $life_status[$i] ?? NULL;

        switch( trim( strtolower( $type[ $i ] ) ) ) {
            case 'husband':
            $sql = "INSERT INTO survey_form_records_husband (household_id, household, unique_id, belongs_to, life_status, purok, barangay, municipality, province, name, status, type, dateOfBirth, educationalAttainment, age, sex, birthPlace, occupation, placeOfWork, religion, ethnicGroup)
                        VALUES ('$household_id', '$household', '$unique_id', '$belongs_to', '$stat', '$purok', '$barangay', '$municipality', '$province', '{$name[$i]}', '{$status[$i]}', '{$type[$i]}', '{$dateOfBirth[$i]}', '{$educationalAttainment[$i]}', '{$age[$i]}', '{$sex[$i]}', '{$birthPlace[$i]}', '{$occupation[$i]}', '{$placeOfWork[$i]}', '{$religion[$i]}', '{$ethnicGroup[$i]}')";
            if ( !mysqli_query( $conn, $sql ) ) $success = false;
            break;
            case 'wife':
            $sql = "INSERT INTO survey_form_records_wife (household_id, household, unique_id, belongs_to, life_status, purok, barangay, municipality, province, name, status, type, dateOfBirth, educationalAttainment, age, sex, birthPlace, occupation, placeOfWork, religion, ethnicGroup)
                        VALUES ('$household_id', '$household',  '$unique_id', '$belongs_to', '$stat', '$purok', '$barangay', '$municipality', '$province', '{$name[$i]}', '{$status[$i]}', '{$type[$i]}', '{$dateOfBirth[$i]}', '{$educationalAttainment[$i]}', '{$age[$i]}', '{$sex[$i]}', '{$birthPlace[$i]}', '{$occupation[$i]}', '{$placeOfWork[$i]}', '{$religion[$i]}', '{$ethnicGroup[$i]}')";
            if ( !mysqli_query( $conn, $sql ) ) $success = false;
            break;
            case 'children':
            $sql = "INSERT INTO survey_form_records_children (household_id, household, unique_id, belongs_to, life_status, purok, barangay, municipality, province, name, status, type, dateOfBirth, educationalAttainment, age, sex, birthPlace, occupation, placeOfWork, religion, ethnicGroup)
                        VALUES ('$household_id', '$household',  '$unique_id', '$belongs_to', '$stat', '$purok', '$barangay', '$municipality', '$province', '{$name[$i]}', '{$status[$i]}', '{$type[$i]}', '{$dateOfBirth[$i]}', '{$educationalAttainment[$i]}', '{$age[$i]}', '{$sex[$i]}', '{$birthPlace[$i]}', '{$occupation[$i]}', '{$placeOfWork[$i]}', '{$religion[$i]}', '{$ethnicGroup[$i]}')";
            if ( !mysqli_query( $conn, $sql ) ) $success = false;
            break;
            default:
            // household
            $sql = "INSERT INTO survey_form_records_household_member (household_id, household, unique_id, belongs_to, life_status, purok, barangay, municipality, province, name, status, type, dateOfBirth, educationalAttainment, age, sex, birthPlace, occupation, placeOfWork, religion, ethnicGroup)
                        VALUES ('$household_id', '$household',  '$unique_id', '$belongs_to', '$stat', '$purok', '$barangay', '$municipality', '$province', '{$name[$i]}', '{$status[$i]}', '{$type[$i]}', '{$dateOfBirth[$i]}', '{$educationalAttainment[$i]}', '{$age[$i]}', '{$sex[$i]}', '{$birthPlace[$i]}', '{$occupation[$i]}', '{$placeOfWork[$i]}', '{$religion[$i]}', '{$ethnicGroup[$i]}')";
            if ( !mysqli_query( $conn, $sql ) ) $success = false;

            break;
        }
    }

    $sql = "INSERT INTO survey_form_records (household_id, household, unique_id, belongs_to, purok, barangay, municipality, province, artificialFamilyPlanningMethod, permanentFamilyPlanningMethod, naturalFamilyPlanningMethod, attendedResponsibleParentingMovementClass, typeOfHousingUnitOccupied, subTypeOfHousingUnitOccupied, typeOfHouseLightUsed, typeOfWaterSupply, typeOfToilet, typeOfGarbageDisposal, communicationFacility, transportFacility, agriculturalProduct, poultryNumberOfHeadsChicken, poultryNumberOfHeadsDuck, poultryNumberOfHeadsGeese, poultryNumberOfHeadsTurkey, poultryOthers, poultryNumberOfHeadsOthers, livestockNumberPig, livestockNumberGoat, livestockNumberSheep, livestockNumberCoat, livestockNumberCarabao, livestockNumberHorse, othersLivestock, livestockNumberOthers, otherSourceOfIncome, fishpondOwned, fishpondOwnedArea, landOwned, landOwnedRiceFieldArea, landOwnedCornFieldArea, land, caretakerRiceArea, caretakerCornArea, caretakerOthersLandOwned, monthlyAverageFamilyIncome)
            VALUES ('$household_id', '$household',  '$unique_id', '$belongs_to', '$purok', '$barangay', '$municipality', '$province', '$artificialFamilyPlanningMethod', '$permanentFamilyPlanningMethod', '$naturalFamilyPlanningMethod', '$attendedResponsibleParentingMovementClass', '$typeOfHousingUnitOccupied', '$subTypeOfHousingUnitOccupied', '$typeOfHouseLightUsed', '$typeOfWaterSupply', '$typeOfToilet', '$typeOfGarbageDisposal', '$communicationFacility', '$transportFacility', '$agriculturalProduct', '$poultryNumberOfHeadsChicken', '$poultryNumberOfHeadsDuck', '$poultryNumberOfHeadsGeese', '$poultryNumberOfHeadsTurkey', '$poultryOthers', '$poultryNumberOfHeadsOthers', '$livestockNumberPig', '$livestockNumberGoat', '$livestockNumberSheep', '$livestockNumberCoat', '$livestockNumberCarabao', '$livestockNumberHorse', '$othersLivestock', '$livestockNumberOthers', '$otherSourceOfIncome', '$fishpondOwned', '$fishpondOwnedArea', '$landOwned', '$landOwnedRiceFieldArea', '$landOwnedCornFieldArea', '$land', '$caretakerRiceArea', '$caretakerCornArea', '$caretakerOthersLandOwned', '$monthlyAverageFamilyIncome')";
    if ( !mysqli_query( $conn, $sql ) ) $success = false;

    if ( $success ) {
        logUser($_SESSION[ 'username' ], 'Form record saved successfully!');
        echo '
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        title: "Success!",
                        text: "Form saved successfully",
                        icon: "success",
                        onClose: function() {
                            window.open("./records.php", "_self");
                        }
                    });
                })
            </script>
        ';

    } else {
        logUser($_SESSION[ 'username' ], 'Error saving form record!');
        echo '
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        title: "Error!",
                        text: "Something went wrong",
                        icon: "error",
                        onClose: function() {
                            window.open("./records.php", "_self");
                        }
                    });
                })
            </script>
        ';
    }

}

?>

