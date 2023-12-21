<?php 
    require_once '../config.php';
    require_once '../global.php';
    require_once './audit_trails.php';

    if(isset($_SESSION['role'])) {
        if($_SESSION['role'] == 'super_admin') {
            header('location: ../super_admin');
        } else {
            // header('location: /admin');
        }
    } else {
        header('location: ../index.php');
    }

    $barangay = $_GET[ 'barangay' ] ?? null;
    logUser($_SESSION['username'], 'Print report generated successfully!');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Print report - PIMS | Population Information Monitoring System</title>
    <link rel="stylesheet" href="../src/bootstrap.min.css" />
    <!-- Favicon -->
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="../img/favicon.ico" type="image/x-icon" />

    <!-- For mobile devices -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png" />
    <meta name="msapplication-TileImage" content="img/logo.png" />
    <meta name="msapplication-TileColor" content="#ffffff" />

    <!-- Poppins font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

    <!-- google icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- custom styles -->
    <style>
    * {
        font-family: "Poppins", sans-serif;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        transition: all 0.5s;
    }

    main.hidden {
        display: none;
    }

    @import url('https://fonts.googleapis.com/css2?family=Lobster+Two&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Spectral&display=swap');

    .dd {
        font-family: 'Lobster Two', sans-serif;
        font-size: 30px;
    }

    .cc {
        font-family: 'Spectral', serif;
        font-weight: bold;
    }


    #img {
        position: absolute;
        left: 100px;
    }
    </style>
</head>
<?php 
$selectedFilter = $_GET['filter_by'] ?? '';
$filterConditions = [
    'January to June' => "MONTH(filter_month) BETWEEN 1 AND 6",
    'July to December' => "MONTH(filter_month) BETWEEN 7 AND 12",
];
$sqlCondition = '';

if (array_key_exists($selectedFilter, $filterConditions)) {
    $sqlCondition = $filterConditions[$selectedFilter];
}




?>

<body>
    <main class="px-5">

        <div class="table-responsive mt-3 px-md-5">
            <img id="img" src="../img/logo.jfif" class="rounded-circle" alt="" width="150px">
            <p class="text-center fs-5">
                Republic of the Philippines <br>
                Province of <?php echo $_SESSION['province'] ?> <br>
                Municipality of <?php echo $_SESSION['municipality'] ?> <br>
                <span class="fw-bold dd">Barangay <?php echo $_SESSION['barangay'] ?></span><br>
                -ooOoo-
            </p>
            <h3 class="text-center fw-bold"><i>OFFICE OF THE PUNONG BARANGAY</i></h3>
            <hr style="padding: 2px; background-color: #000;">
            <h3 class="text-center text-decoration-underline cc">CONSOLIDATION OF FAMILY PROFILE</h3>
            <h5 class="text-center">As of <?php echo date('F, Y', strtotime('2023-12-01')) ?></h5>
            <br>
            <h5>Barangay: <b><?php echo $_SESSION[ 'barangay' ] ?? '' ?></b></h5>
            <h5>No of Purok: <b><?php 
            $query = "
                SELECT COUNT(*) AS purok_count
                FROM (
                    SELECT DISTINCT purok FROM survey_form_records_children WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    UNION
                    SELECT DISTINCT purok FROM survey_form_records_husband WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    UNION
                    SELECT DISTINCT purok FROM survey_form_records_household_member WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    UNION
                    SELECT DISTINCT purok FROM survey_form_records_wife WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                ) AS distinct_purok
            ";

            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $purokCount = $row['purok_count'];

            echo $purokCount;
            ?></b></h5>

            <table border="0">
                <tr>
                    <td width="300">
                        Total # OF <b>FAMILIES</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php echo getCount( 'survey_form_records_husband', $barangay, (!empty($sqlCondition) ? " AND $sqlCondition" : "") ) ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        Total # OF <b>HOUSEHOLD</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php echo getCount( 'survey_form_records_husband', $barangay, (!empty($sqlCondition) ? " AND $sqlCondition" : "") ) ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        Total # OF <b>MALE</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php   
                            $query = "SELECT COUNT(*) AS male_count
                                        FROM (
                                            SELECT sex FROM survey_form_records_children WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Male'
                                            UNION ALL
                                            SELECT sex FROM survey_form_records_husband WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Male'
                                            UNION ALL
                                            SELECT sex FROM survey_form_records_household_member WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Male'
                                        ) AS count_sex";

                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($result);
                            echo $row['male_count'];
                            ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        Total # OF <b>FEMALE</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php   
                            $query = "SELECT COUNT(*) AS female_count
                                FROM (
                                    SELECT sex FROM survey_form_records_children WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Female'
                                    UNION ALL
                                    SELECT sex FROM survey_form_records_wife WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Female'
                                    UNION ALL
                                    SELECT sex FROM survey_form_records_household_member WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Female'
                                ) AS count_sex ";

                            $result = mysqli_query($conn, $query);
                            $row = mysqli_fetch_assoc($result);
                            echo $row['female_count'];
                            ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        Total # OF <b>POPULATION</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php echo getPopulation( $barangay, (!empty($sqlCondition) ? " AND $sqlCondition" : "")) ?>
                        </b>
                    </td>
                </tr>
            </table>

            <h3 class="mt-4 fs-5">&gt; Civil Status of the Couple or Household Owner</h3>
            <table border="0">
                <tr>
                    <td width="300">
                        <b>MARRIED</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            $rows = getRows("unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND status = 'Married'", "survey_form_records_husband");
                            echo count( $rows );
                            ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        <b>SINGLE</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            $rows = getRows("unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND status = 'Single'", "survey_form_records_husband");
                            echo count( $rows );
                            ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        <b>WIDOW</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            $rows = getRows("unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND status = 'Widow'", "survey_form_records_husband");
                            echo count( $rows );
                            ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        <b>LIVE-IN</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            $rows = getRows("unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND status = 'Live-in'", "survey_form_records_husband");
                            echo count( $rows );
                            ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        <b>SEPARATED</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            $rows = getRows("unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND status = 'Separated'", "survey_form_records_husband");
                            echo count( $rows );
                            ?>
                        </b>
                    </td>
                </tr>

            </table>





            <h3 class="mt-4 fs-5">&gt; Educational Attainment</h3>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col fw-bold" style="font-size: 14px;">PUROK</th>
                        <th scope="col fw-bold" style="font-size: 14px;">NO SCHOOLING</th>
                        <th scope="col fw-bold" style="font-size: 14px;">ELEMENTARY SCHOOL</th>
                        <th scope="col fw-bold" style="font-size: 14px;">HIGH SCHOOL</th>
                        <th scope="col fw-bold" style="font-size: 14px;">VOCATIONAL SCHOOL</th>
                        <th scope="col fw-bold" style="font-size: 14px;">SOME COLLEGE</th>
                        <th scope="col fw-bold" style="font-size: 14px;">ASSOCIATE DEGREE</th>
                        <th scope="col fw-bold" style="font-size: 14px;">BACHELOR'S DEGREE</th>
                        <th scope="col fw-bold" style="font-size: 14px;">MASTERS DEGREE</th>
                        <th scope="col fw-bold" style="font-size: 14px;">DOCTORAL DEGREE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $educ_att = ['no-schooling', 'elementary-school', 'high-school', 'vocational-school', 'some-college', 'associate-degree', 'bachelor-degree', 'master-degree', 'doctoral-degree'];

                for ($i = 0; $i < 6; $i++) {
                    $unique_id = $_SESSION['unique_id'];
                    ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <?php
                        $pr = $i + 1;
                        foreach($educ_att as $att) {

                            $sqlQuery = "SELECT
                                        COUNT(*) AS total_count
                                    FROM
                                    (
                                        SELECT unique_id FROM survey_form_records_children WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND purok = $pr AND educationalAttainment = '$att'
                                        UNION ALL
                                        SELECT unique_id FROM survey_form_records_wife WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND purok = $pr AND educationalAttainment = '$att'
                                        UNION ALL
                                        SELECT unique_id FROM survey_form_records_husband WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND purok = $pr AND educationalAttainment = '$att'
                                        UNION ALL
                                        SELECT unique_id FROM survey_form_records_household_member WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND purok = $pr AND educationalAttainment = '$att'
                                    ) AS subquery";

                        $result = mysqli_query($conn, $sqlQuery);
                        $row = mysqli_fetch_assoc($result);
                        echo '<td>' . $row['total_count'] . '</td>';

                        }

                        ?>
                    </tr>
                    <?php
                }
                ?>


                </tbody>

            </table>

            <h3 class="mt-4 fs-5">&gt; Occupation of Couple and Children</h3>
            <?php 
                $sql = "SELECT DISTINCT occupation FROM ( 
                    SELECT DISTINCT occupation FROM survey_form_records_children WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    UNION SELECT DISTINCT occupation FROM survey_form_records_husband WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    UNION SELECT DISTINCT occupation FROM survey_form_records_wife WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " ) AS distinct_occupation";
                
                $result = mysqli_query($conn, $sql);
            ?>

            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col fw-bold">OCCUPATION</th>
                        <th scope="col fw-bold">HUSBAND</th>
                        <th scope="col fw-bold">WIFE</th>
                        <th scope="col fw-bold">CHILDREN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $occupation = $row['occupation'];
                        $unique_id = mysqli_real_escape_string($conn, $_SESSION['unique_id']);

                        $queryHusbandWife = "SELECT source, COUNT(occupation) as count FROM (
                            SELECT 'Husband' as source, occupation FROM survey_form_records_husband WHERE unique_id = '$unique_id' AND occupation = '$occupation'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") ." 
                            UNION ALL
                            SELECT 'Wife' as source, occupation FROM survey_form_records_wife WHERE unique_id = '$unique_id' AND occupation = '$occupation'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") ."
                            UNION ALL
                            SELECT 'Children' as source, occupation FROM survey_form_records_children WHERE unique_id = '$unique_id' AND occupation = '$occupation'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") ."
                        ) AS combined_occupation_counts GROUP BY source";

                        $resultHusbandWife = mysqli_query($conn, $queryHusbandWife);

                        $counts = array();
                        while ($countRow = mysqli_fetch_assoc($resultHusbandWife)) {
                            $counts[$countRow['source']] = $countRow['count'];
                        }

                        ?>
                    <tr>
                        <td>
                            <?php echo $occupation; ?>
                        </td>
                        <td>
                            <?php echo isset($counts['Husband']) ? $counts['Husband'] : 0; ?>
                        </td>
                        <td>
                            <?php echo isset($counts['Wife']) ? $counts['Wife'] : 0; ?>
                        </td>
                        <td>
                            <?php echo isset($counts['Children']) ? $counts['Children'] : 0; ?>
                        </td>
                    </tr>
                    <?php
                    }
                ?>
                </tbody>

            </table>



            <h3 class="mt-4 fs-5">&gt; Place of Work of the Couple and Single <br>
                <span class="text-decoration-underline ml-5 pl-5">WITHIN THE PHILIPPINES</span>
            </h3>

            <table border="0">
                <tr>
                    <td width="300">
                        <b>Husband</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            $rows = getRows("unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND occupation NOT IN ('', 'NA', 'na', 'n/a', 'n/a', 'N/a')", "survey_form_records_husband");
                            echo count( $rows );
                            ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        <b>Wife</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            $rows = getRows("unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND occupation NOT IN ('', 'NA', 'na', 'n/a', 'n/a', 'N/a')", "survey_form_records_wife");
                            echo count( $rows );
                            ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        <b>Children</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php
                            $rows = getRows("unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND occupation NOT IN ('', 'NA', 'na', 'n/a', 'n/a', 'N/a')", "survey_form_records_children");
                            echo count( $rows );
                            ?>
                        </b>
                    </td>
                </tr>
            </table>
            <span class="text-decoration-underline ml-5 pl-5 fs-5">ABROAD</span>
            <table border="0">
                <tr>
                    <td width="300">
                        <b>Husband</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;0
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        <b>Wife</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;0
                        </b>
                    </td>
                </tr>
                <tr>
                    <td width="300">
                        <b>Children</b>
                    </td>
                    <td width="200">
                        <b>
                            - &nbsp;&nbsp;&nbsp;&nbsp;0
                        </b>
                    </td>
                </tr>
            </table>


            <h3 class="mt-4 fs-5">&gt;Religion of the Couple and Single</h3>
            <?php 
                $sql = "SELECT DISTINCT religion FROM ( 
                    SELECT DISTINCT religion FROM survey_form_records_children WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    UNION SELECT DISTINCT religion FROM survey_form_records_husband WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    UNION SELECT DISTINCT religion FROM survey_form_records_wife WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " ) AS distinct_religion";
                
                $result = mysqli_query($conn, $sql);
                
            
            ?>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col fw-bold">RELIGION</th>
                        <th scope="col fw-bold">COUPLE</th>
                        <th scope="col fw-bold">SINGLE CHILDREN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $religion = $row['religion'];
                        $unique_id = mysqli_real_escape_string($conn, $_SESSION['unique_id']);

                    $queryHusbandWife = "SELECT source, COUNT(religion) as count FROM (
                        SELECT 'Husband/Wife' as source, religion FROM survey_form_records_husband WHERE unique_id = '$unique_id' AND religion = '$religion'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                        UNION ALL
                        SELECT 'Husband/Wife' as source, religion FROM survey_form_records_wife WHERE unique_id = '$unique_id' AND religion = '$religion'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                        UNION ALL
                        SELECT 'Children' as source, religion FROM survey_form_records_children WHERE unique_id = '$unique_id' AND religion = '$religion'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    ) AS combined_religion_counts GROUP BY source";

                        $resultHusbandWife = mysqli_query($conn, $queryHusbandWife);

                        $counts = array();
                        while ($countRow = mysqli_fetch_assoc($resultHusbandWife)) {
                            $counts[$countRow['source']] = $countRow['count'];
                        }

                        ?>
                    <tr>
                        <td>
                            <?php echo $religion; ?>
                        </td>
                        <td>
                            <?php echo isset($counts['Husband/Wife']) ? $counts['Husband/Wife'] : 0; ?>
                        </td>
                        <td>
                            <?php echo isset($counts['Children']) ? $counts['Children'] : 0; ?>
                        </td>
                    </tr>
                    <?php
                    }
                ?>
                </tbody>

            </table>



            <h3 class="mt-4 fs-5 fw-bold">&gt; Ethnic Group<br>
                <span class="text-decoration-underline ml-5 pl-5">HUSBAND</span>
            </h3>
            <?php 
                $sql = "SELECT DISTINCT ethnicGroup FROM ( 
                    SELECT DISTINCT ethnicGroup FROM survey_form_records_husband WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . ") AS distinct_ethnicGroup";
                
                $result = mysqli_query($conn, $sql);
                
            
            ?>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $ethnicGroup = $row['ethnicGroup'];
                        $unique_id = mysqli_real_escape_string($conn, $_SESSION['unique_id']);

                        $queryHusbandWife = "SELECT source, COUNT(ethnicGroup) as count FROM (
                        SELECT 'ethnicGroup' as source, ethnicGroup FROM survey_form_records_husband WHERE unique_id = '$unique_id' AND ethnicGroup = '$ethnicGroup'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    ) AS combined_ethnicGroup_counts GROUP BY source";

                        $resultHusbandWife = mysqli_query($conn, $queryHusbandWife);

                        $counts = array();
                        while ($countRow = mysqli_fetch_assoc($resultHusbandWife)) {
                            $counts[$countRow['source']] = $countRow['count'];
                        }

                        ?>
                    <tr>
                        <td>
                            <?php echo $ethnicGroup; ?>
                        </td>
                        <td>
                            <b>
                                - &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo isset($counts['ethnicGroup']) ? $counts['ethnicGroup'] : 0; ?>
                            </b>
                        </td>

                    </tr>
                    <?php
                    }
                ?>
                </tbody>

            </table>


            <h3 class="mt-4 fs-5 fw-bold">
                <span class="text-decoration-underline ml-5 pl-5">WIFE</span>
            </h3>
            <?php 
                $sql = "SELECT DISTINCT ethnicGroup FROM ( 
                    SELECT DISTINCT ethnicGroup FROM survey_form_records_wife WHERE unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . ") AS distinct_ethnicGroup";
                
                $result = mysqli_query($conn, $sql);
                
            
            ?>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $ethnicGroup = $row['ethnicGroup'];
                        $unique_id = mysqli_real_escape_string($conn, $_SESSION['unique_id']);

                        $queryWifeWife = "SELECT source, COUNT(ethnicGroup) as count FROM (
                        SELECT 'ethnicGroup' as source, ethnicGroup FROM survey_form_records_wife WHERE unique_id = '$unique_id' AND ethnicGroup = '$ethnicGroup'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
                    ) AS combined_ethnicGroup_counts GROUP BY source";

                        $resultWifeWife = mysqli_query($conn, $queryWifeWife);

                        $counts = array();
                        while ($countRow = mysqli_fetch_assoc($resultWifeWife)) {
                            $counts[$countRow['source']] = $countRow['count'];
                        }

                        ?>
                    <tr>
                        <td>
                            <?php echo $ethnicGroup; ?>
                        </td>
                        <td>
                            <b>
                                - &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo isset($counts['ethnicGroup']) ? $counts['ethnicGroup'] : 0; ?>
                            </b>
                        </td>

                    </tr>
                    <?php
                    }
                ?>
                </tbody>

            </table>

            <h3 class="mt-4 fs-5 fw-bold">&gt; Family Planning/Method used by the Couple</h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountWithCondition($conn, $unique_id, $field, $condition) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND $field $condition");
                    $stmt->bind_param("s", $unique_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];

                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">Artificial Family Planning Method</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountWithCondition($conn, $unique_id, 'artificialFamilyPlanningMethod', 'NOT IN (NULL, "")'.(!empty($sqlCondition) ? " AND $sqlCondition" : "").''); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Permanent Family Planning Method</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountWithCondition($conn, $unique_id, 'permanentFamilyPlanningMethod', 'NOT IN (NULL, "")'.(!empty($sqlCondition) ? " AND $sqlCondition" : "").''); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Natural Family Planning Method</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountWithCondition($conn, $unique_id, 'naturalFamilyPlanningMethod', 'NOT IN (NULL, "")'.(!empty($sqlCondition) ? " AND $sqlCondition" : "").''); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold"><i>No. of Couple Attending RPM</i></td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountWithCondition($conn, $unique_id, 'attendedResponsibleParentingMovementClass', '= "Yes"'.(!empty($sqlCondition) ? " AND $sqlCondition" : "").''); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>


            <h3 class="mt-4 fs-5 fw-bold">&gt; Household Unit Occupied<br>
                <span class="text-decoration-underline ml-5 pl-4">OWNERSHIP</span>
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountByHousingType($conn, $unique_id, $housingType) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND typeOfHousingUnitOccupied = ?");
                    $stmt->bind_param("ss", $unique_id, $housingType);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">Owner</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByHousingType($conn, $unique_id, 'Owned'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Rented</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByHousingType($conn, $unique_id, 'Rented'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Caretaker</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByHousingType($conn, $unique_id, 'Caretaker'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>



            <h3 class="mt-4 fs-5 fw-bold text-decoration-underline ml-5 pl-4">HOUSE STRUCTURE<br>
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountBySubHousingType($conn, $unique_id, $subHousingType) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND subTypeOfHousingUnitOccupied = ?");
                    $stmt->bind_param("ss", $unique_id, $subHousingType);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">Permanent/Concrete</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountBySubHousingType($conn, $unique_id, 'Permanent - concrete'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Temporary/Wooden</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountBySubHousingType($conn, $unique_id, 'Temporary - wooden'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Makeshift/Cogon/Bamboo, <br>Barong-barong</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountBySubHousingType($conn, $unique_id, 'Makeshift - cogon/bamboo'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>

            <h3 class="mt-4 fs-5 fw-bold text-decoration-underline ml-5 pl-4">HOUSE TYPE<br>
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountSubHousingType($conn, $unique_id, $subHousingType) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND subTypeOfHousingUnitOccupied = ?");
                    $stmt->bind_param("ss", $unique_id, $subHousingType);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">Single</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountSubHousingType($conn, $unique_id, 'Single'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Duplex</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountSubHousingType($conn, $unique_id, 'Duplex'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Commercial/Industrial/Agricultural</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountSubHousingType($conn, $unique_id, 'Commercial/industrial/agricultural'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Apartment</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountSubHousingType($conn, $unique_id, 'Apartment/accessoria/condominium'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>

            <h3 class="mt-4 fs-5 fw-bold ">Type of House Light
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountByHouseLightType($conn, $unique_id, $houseLightType) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND typeOfHouseLightUsed = ?");
                    $stmt->bind_param("ss", $unique_id, $houseLightType);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">Electricity</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByHouseLightType($conn, $unique_id, 'Electricity'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">OTHERS</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByHouseLightType($conn, $unique_id, ''); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>

            <h3 class="mt-4 fs-5 fw-bold ">Type of Water Supply
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountByWaterSupplyType($conn, $unique_id, $waterSupplyType) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND typeOfWaterSupply = ?");
                    $stmt->bind_param("ss", $unique_id, $waterSupplyType);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">TAP</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByWaterSupplyType($conn, $unique_id, 'Tap - (Inside house)'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">SPRING</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByWaterSupplyType($conn, $unique_id, 'Spring'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">DUG WELL</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByWaterSupplyType($conn, $unique_id, 'Dug Well'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">DEEP WELL</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByWaterSupplyType($conn, $unique_id, 'Deep Well'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">PUBLIC WELL</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByWaterSupplyType($conn, $unique_id, 'Public Well'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">PUBLIC FAUCET</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByWaterSupplyType($conn, $unique_id, 'Public Faucet'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">NONE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByWaterSupplyType($conn, $unique_id, 'None'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>


            <h3 class="mt-4 fs-5 fw-bold ">Type of Toilet
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountByToiletType($conn, $unique_id, $toiletType) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND typeOfToilet = ?");
                    $stmt->bind_param("ss", $unique_id, $toiletType);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">WATER-SEALED</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByToiletType($conn, $unique_id, 'Water-sealed'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">WATER-SEALED SHARED W/OTHER HOUSEHOLD</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByToiletType($conn, $unique_id, 'Water-sealed shared with other HH'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">CLOSED PIT</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByToiletType($conn, $unique_id, 'Closed Pit'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">OPEN PIT</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByToiletType($conn, $unique_id, 'Open Pit'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>


            <h3 class="mt-4 fs-5 fw-bold ">Type of Garbage Disposal
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountByGarbageDisposalType($conn, $unique_id, $garbageDisposalType) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND typeOfGarbageDisposal = ?");
                    $stmt->bind_param("ss", $unique_id, $garbageDisposalType);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">PICKED UP BY GARBAGE TRUCK</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByGarbageDisposalType($conn, $unique_id, 'Picked By Garbage Truck'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">WASTE SEGREGATION</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByGarbageDisposalType($conn, $unique_id, 'Waste Segregation'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">COMPOSTING</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByGarbageDisposalType($conn, $unique_id, 'Composting'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">BURNING</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByGarbageDisposalType($conn, $unique_id, 'Burning'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">BURYING</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByGarbageDisposalType($conn, $unique_id, 'Burying'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>



            <h3 class="mt-4 fs-5 fw-bold ">Communication Facility
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountByCommunicationFacility($conn, $unique_id, $communicationFacility) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND communicationFacility = ?");
                    $stmt->bind_param("ss", $unique_id, $communicationFacility);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">CABLE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByCommunicationFacility($conn, $unique_id, 'Cable'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">TELEVISION</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByCommunicationFacility($conn, $unique_id, 'Television'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">RADIO</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByCommunicationFacility($conn, $unique_id, 'Radio'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">TWO-WAY RADIO</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByCommunicationFacility($conn, $unique_id, 'Two-way Radio'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">MOBILE PHONE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByCommunicationFacility($conn, $unique_id, 'Mobile Phone'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">LANDLINE PHONE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByCommunicationFacility($conn, $unique_id, 'Landline Phone'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">NONE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByCommunicationFacility($conn, $unique_id, 'None'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>



            <h3 class="mt-4 fs-5 fw-bold ">Type of Transport Facility
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountByTransportFacility($conn, $unique_id, $transportFacility) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND transportFacility = ?");
                    $stmt->bind_param("ss", $unique_id, $transportFacility);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">BICYCLE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByTransportFacility($conn, $unique_id, 'Bicycle'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">MOTORCYCLE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByTransportFacility($conn, $unique_id, 'Motorcycle'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">TRICYCLE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByTransportFacility($conn, $unique_id, 'Tricycle'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">HANDTRACTOR</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByTransportFacility($conn, $unique_id, 'Handtractor'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">FOUR-WHEEL</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByTransportFacility($conn, $unique_id, 'Jeep') + getCountByTransportFacility($conn, $unique_id, 'Car') + getCountByTransportFacility($conn, $unique_id, 'Van') + getCountByTransportFacility($conn, $unique_id, 'kuliglig'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Others: TRUCK</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByTransportFacility($conn, $unique_id, 'Truck'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>

            <h3 class="mt-4 fs-5 fw-bold ">Agricultural Product
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getAgriculturalProductCount($conn, $unique_id, $product) {
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND agriculturalProduct = ?");
                    $stmt->bind_param("ss", $unique_id, $product);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        return $row['count'];
                    } else {
                        return 0; // Return 0 if no records are found
                    }
                }

                $unique_id = $_SESSION['unique_id'];

                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">RICE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getAgriculturalProductCount($conn, $unique_id, 'Rice'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">CORN</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getAgriculturalProductCount($conn, $unique_id, 'Corn'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">BANANA</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getAgriculturalProductCount($conn, $unique_id, 'Banana'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">TARO/GABI</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getAgriculturalProductCount($conn, $unique_id, 'Taro/Gabi'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">CASSAVA</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getAgriculturalProductCount($conn, $unique_id, 'Cassava'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">others: VEGETABLES</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getAgriculturalProductCount($conn, $unique_id, 'others: VEGETABLES'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>

            <h3 class="mt-4 fs-5 fw-bold ">Poultry
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getTotalPoultryHeads($conn, $unique_id, $poultryType) {
                    $totalHeads = 0;
                    $stmt = $conn->prepare("SELECT SUM(poultryNumberOfHeads$poultryType) AS total FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND poultryNumberOfHeads$poultryType IS NOT NULL");
                    $stmt->bind_param("s", $unique_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $totalHeads = $row['total'];
                    }

                    $stmt->close();

                    return $totalHeads ?? 0;
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">CHICKEN</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalPoultryHeads($conn, $unique_id, 'Chicken'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">DUCK</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalPoultryHeads($conn, $unique_id, 'Duck'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">GEESE</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalPoultryHeads($conn, $unique_id, 'Geese'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">TURKEY</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalPoultryHeads($conn, $unique_id, 'Turkey'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">OTHERS</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalPoultryHeads($conn, $unique_id, 'Others'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>



            <h3 class="mt-4 fs-5 fw-bold ">Livestock
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getTotalLivestockHeads($conn, $unique_id, $livestockType) {
                    $totalHeads = 0;
                    $stmt = $conn->prepare("SELECT SUM(livestockNumber$livestockType) AS total FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND livestockNumber$livestockType IS NOT NULL");
                    $stmt->bind_param("s", $unique_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $totalHeads = $row['total'];
                    }

                    $stmt->close();

                    return $totalHeads ?? 0;
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>
                    <tr>
                        <td class="fw-bold">Pig</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalLivestockHeads($conn, $unique_id, 'Pig'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Goat</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalLivestockHeads($conn, $unique_id, 'Goat'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Sheep</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalLivestockHeads($conn, $unique_id, 'Sheep'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Horse</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalLivestockHeads($conn, $unique_id, 'Horse'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Carabao</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalLivestockHeads($conn, $unique_id, 'Carabao'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Others</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getTotalLivestockHeads($conn, $unique_id, 'Others'); ?></b>
                        </td>
                    </tr>
                </tbody>


            </table>


            <h3 class="mt-4 fs-5 fw-bold ">Other Source of Income/Livelihood
            </h3>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>
                <?php
                function getCountByIncomeSource($conn, $unique_id, $source) {
                    $count = 0;
                    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND otherSourceOfIncome = ?");
                    $stmt->bind_param("ss", $unique_id, $source);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $count = $row['count'];
                    }

                    $stmt->close();

                    return $count;
                }

                $unique_id = $_SESSION['unique_id'];
                ?>

                <tbody>

                    <tr>
                        <td class="fw-bold">Sari-sari store</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByIncomeSource($conn, $unique_id, 'Sari-sari store'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Restaurant</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByIncomeSource($conn, $unique_id, 'Restaurant'); ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Bakeshop</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByIncomeSource($conn, $unique_id, 'Bakeshop'); ?></b>
                        </td>
                    </tr>
                </tbody>



            </table>


            <h3 class="mt-4 fs-5 fw-bold ">Fishpond
            </h3>
            <?php
            function getCountByFishpondOwnership($conn, $unique_id, $fishpondOwnership) {
                $count = 0;
                $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM survey_form_records WHERE unique_id = ?". (!empty($sqlCondition) ? " AND " . $sqlCondition : "") ." AND fishpondOwned = ?");
                $stmt->bind_param("ss", $unique_id, $fishpondOwnership);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $count = $row['count'];
                }

                $stmt->close();

                return $count;
            }

            $unique_id = $_SESSION['unique_id'];
            ?>
            <table border="0">
                <thead>
                    <tr>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                        <th scope="col fw-bold" width="300">&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="fw-bold">Owned</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByFishpondOwnership($conn, $unique_id, 'Owned'); ?>
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">None</td>
                        <td>
                            <b>- &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php echo getCountByFishpondOwnership($conn, $unique_id, 'None'); ?>
                            </b>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php echo "<br><br><h5>Single Age ( " . date('F Y') . ")</h5>"; ?>
            <div class="d-flex align-items-center justify-content-start">
                <?php
$barangay = $_SESSION['barangay'];
$query = "SELECT DISTINCT purok
FROM (
    SELECT purok FROM survey_form_records_household_member WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
    UNION
    SELECT purok FROM survey_form_records_wife WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
    UNION
    SELECT purok FROM survey_form_records_children WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
    UNION
    SELECT purok FROM survey_form_records_husband WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
) AS combined_puroks
ORDER BY CAST(SUBSTRING(purok FROM 7) AS INT) ASC";

$result = $conn->query($query);
$puroks = [];
while ($row = $result->fetch_assoc()) {
    $puroks[] = $row['purok'];
}

$minAge = 1;
$maxAge = 100;

echo "<div>
<h5>Male</h5>
<table class='table table-striped' style='width: 500px'>

<thead><tr><th>Age</th>";
foreach ($puroks as $purok) {
    echo "<th>P$purok</th>";
}

echo "<th>Total</th></tr></thead>";


for ($age = $minAge; $age <= $maxAge; $age++) {
    echo "<tr><td class='bg-info px-2 text-center'>$age</td>";
    $rowTotal = 0;
    
    foreach ($puroks as $purok) {
        $query = "SELECT COUNT(*) AS row_count
            FROM (
                SELECT age FROM survey_form_records_household_member WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND age = $age AND purok = '$purok' AND status = 'Single' AND unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Male'
                UNION ALL
                SELECT age FROM survey_form_records_wife WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND age = $age AND purok = '$purok' AND status = 'Single' AND unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Male'
                UNION ALL
                SELECT age FROM survey_form_records_children WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND age = $age AND purok = '$purok' AND status = 'Single' AND unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Male'
                UNION ALL
                SELECT age FROM survey_form_records_husband WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND age = $age AND purok = '$purok' AND status = 'Single' AND unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Male'
            ) AS combined_rows";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rowCount = $row["row_count"];
        } else {
            $rowCount = 0;
        }

        echo "<td>$rowCount</td>";
        $rowTotal += $rowCount;
    }

    echo "<td>$rowTotal</td></tr>";
}
echo "</tbody></table></div>";


$query = "SELECT DISTINCT purok
FROM (
    SELECT purok FROM survey_form_records_household_member WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
    UNION
    SELECT purok FROM survey_form_records_wife WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
    UNION
    SELECT purok FROM survey_form_records_children WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
    UNION
    SELECT purok FROM survey_form_records_husband WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . "
) AS combined_puroks
ORDER BY CAST(SUBSTRING(purok FROM 7) AS INT) ASC";

$result = $conn->query($query);
$puroks = [];
while ($row = $result->fetch_assoc()) {
    $puroks[] = $row['purok'];
}

$minAge = 1;
$maxAge = 100;

echo "
<div>
<h5>Female</h5>
<table class='table table-striped' style='width: 500px'>
<thead><tr><th>Age</th>";
foreach ($puroks as $purok) {
    echo "<th>P$purok</th>";
}

echo "<th>Total</th></tr></thead>";


for ($age = $minAge; $age <= $maxAge; $age++) {
    echo "<tr><td class='bg-info px-2 text-center'>$age</td>";
    $rowTotal = 0;
    
    foreach ($puroks as $purok) {
        $query = "SELECT COUNT(*) AS row_count
            FROM (
                SELECT age FROM survey_form_records_household_member WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND age = $age AND purok = '$purok' AND status = 'Single' AND unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Female'
                UNION ALL
                SELECT age FROM survey_form_records_wife WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND age = $age AND purok = '$purok' AND status = 'Single' AND unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Female'
                UNION ALL
                SELECT age FROM survey_form_records_children WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND age = $age AND purok = '$purok' AND status = 'Single' AND unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Female'
                UNION ALL
                SELECT age FROM survey_form_records_husband WHERE barangay = '$barangay'" . (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND age = $age AND purok = '$purok' AND status = 'Single' AND unique_id = '{$_SESSION['unique_id']}'". (!empty($sqlCondition) ? " AND $sqlCondition" : "") . " AND sex = 'Female'
            ) AS combined_rows";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rowCount = $row["row_count"];
        } else {
            $rowCount = 0;
        }

        echo "<td>$rowCount</td>";
        $rowTotal += $rowCount;
    }

    echo "<td>$rowTotal</td></tr>";
}
echo "</tbody></table></div>";
?>
            </div>









        </div>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        html2pdf(document.body);
        setInterval(() => {
            location.href = 'records.php'
        }, 1000);
    })
    
    </script>
</body>

</html>