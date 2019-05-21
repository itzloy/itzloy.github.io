<?php
/*
 */
$upload_dir = "excels/";
if (isset($_FILES["excelFile"])) { // it is recommended to check file type and size here
    if ($_FILES["excelFile"]["error"] > 0) {
        echo "Error: " . $_FILES["file"]["error"] . "<br>";
    } else {
        move_uploaded_file($_FILES["excelFile"]["tmp_name"], $upload_dir . $_FILES["excelFile"]["name"]);
        echo $_FILES["excelFile"]["name"];
    }
}
?>