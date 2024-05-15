<?php
include '../config.php';

$id = $_GET['id'];

// Check if the ID exists in the roombook table
$checkSql = "SELECT * FROM roombook WHERE id = $id";
$result = mysqli_query($conn, $checkSql);

if (mysqli_num_rows($result) > 0) {
    // Delete from the payment table first 
    // $deletePaymentSql = "DELETE FROM payment WHERE id = $id";
    // mysqli_query($conn, $deletePaymentSql);

    // Now, delete from the roombook table
    $deleteRoombookSql = "DELETE FROM roombook WHERE id = $id";
    mysqli_query($conn, $deleteRoombookSql);

    header("Location: roombook.php");
} else {
    // Handle the case where the ID doesn't exist in roombook table
    echo "Invalid ID";
}
?>
