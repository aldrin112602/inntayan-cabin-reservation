<?php
// Check if the required parameters exist in the URL
$table = null;
if ( isset( $_GET[ 'id' ] ) && isset( $_GET[ 'type' ] ) && isset( $_GET[ 'action' ] ) ) {
    $id = sanitize( $_GET[ 'id' ] );
    $type = sanitize( $_GET[ 'type' ] );
    $action = sanitize( $_GET[ 'action' ] );

    if ( $type === 'husband' || $type === 'children' || $type === 'household' || $type === 'wife' || $type === 'survey_form_records') {
        switch( $type ) {
            case 'husband':
            case 'children':
            case 'wife':
                $table = 'survey_form_records_' . $type;
            break;
            case 'survey_form_records':
                $table = 'survey_form_records';
            break;
            default:
                $table = 'survey_form_records_household_member';
            break;
        }
        if ($action === 'remove') {
            try {
                $checkExistenceQuery = "SELECT COUNT(*) FROM $table WHERE id = ?";
                $statement = $conn->prepare($checkExistenceQuery);
                $statement->bind_param('i', $id);
                $statement->execute();
                $statement->bind_result($count);
                $statement->fetch();
                $statement->close();

                if ($count > 0) {
                    $removeQuery = "DELETE FROM $table WHERE id = ?";
                    $removeStatement = $conn->prepare($removeQuery);
                    $removeStatement->bind_param('i', $id);
                    $removeStatement->execute();
                    $removeStatement->close();

                    echo '
                        <script>
                            $(document).ready(function() {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Record removed successfully",
                                    icon: "success",
                                    onClose: function() {
                                        window.open("./records.php", "_self");
                                    }
                                });
                            })
                        </script>
                    ';
                } else {

                    echo '
                        <script>
                            $(document).ready(function() {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Data not found",
                                    icon: "error",
                                    onClose: function() {
                                        window.open("./records.php", "_self");
                                    }
                                });
                            })
                        </script>
                    ';
                    
                }
            } catch (Exception $e) {
                echo '
                    <script>
                        $(document).ready(function() {
                            Swal.fire({
                                title: "Error!",
                                text: "'. $e->getMessage() .'",
                                icon: "error",
                                onClose: function() {
                                    window.open("./records.php", "_self");
                                }
                            });
                        })
                    </script>
                ';
            }
        } elseif ( $action === 'update' ) {
            // find first to table if the data exist before showing the update modal
            

        } else {
            echo '
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        title: "Error!",
                        text: "Invalid action specified",
                        icon: "error",
                        onClose: function() {
                            window.open("./records.php", "_self");
                        }
                    });
                })
            </script>
            ';
        }
    } else {
        echo '
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        title: "Error!",
                        text: "Invalid type specified",
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