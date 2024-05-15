<?php
include '../config.php';

$id = $_GET['id'];

// Delete from the payment system
$deletePaymentSql = "DELETE FROM payment WHERE id = $id";
$resultPayment = mysqli_query($conn, $deletePaymentSql);

// Delete from the booking system
$deleteBookingSql = "DELETE FROM roombook WHERE id = $id";
$resultBooking = mysqli_query($conn, $deleteBookingSql);

// Check if both deletions were successful
if ($resultPayment && $resultBooking) {
    header("Location: payment.php");
} else {
    // Handle error if deletion fails
    echo "Error: " . mysqli_error($conn);
}
?>
