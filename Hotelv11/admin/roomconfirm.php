<?php
include '../config.php';

$id = $_GET['id'];

$sql = "SELECT * FROM roombook WHERE id = '$id'";
$re = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_array($re)) {
    $Name = $row['Name'];
    $Email = $row['Email'];
    $Country = $row['Country'];
    $Phone = $row['Phone'];
    $RoomType = $row['RoomType'];
    $Bed = $row['Bed'];
    $cin = $row['cin'];
    $cout = $row['cout'];
    $noofday = $row['nodays'];
    $stat = $row['stat'];

    if ($stat == "NotConfirm") {
        $st = "Confirm";

        $update_sql = "UPDATE roombook SET stat = '$st' WHERE id = '$id'";
        $update_result = mysqli_query($conn, $update_sql);

        if ($update_result) {
            // Fetch the price from the room table based on RoomType and Bed
            $price_query = "SELECT price FROM room WHERE type = '$RoomType' AND bedding = '$Bed'";
            $price_result = mysqli_query($conn, $price_query);

            if ($price_result && mysqli_num_rows($price_result) > 0) {
                $price_row = mysqli_fetch_assoc($price_result);
                $type_of_room = $price_row['price'];

                // Calculate final total
                $fintot = $type_of_room * $noofday;

                // Insert into payment table
                $psql = "INSERT INTO payment(id, Name, Email, RoomType, Bed, cin, cout, noofdays, finaltotal) 
                         VALUES ('$id', '$Name', '$Email', '$RoomType', '$Bed', '$cin', '$cout', '$noofday', '$fintot')";

                mysqli_query($conn, $psql);
                // Redirect to roombook.php after processing each row
                header("Location:roombook.php");
            } else {
                echo "Error fetching price from the room table";
            }
        }
    }
}
?>
