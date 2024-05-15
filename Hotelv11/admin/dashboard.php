<?php
session_start();
include '../config.php';

// Fetch total room bookings
$roombooksql ="SELECT * FROM roombook";
$roombookre = mysqli_query($conn, $roombooksql);
$roombookrow = mysqli_num_rows($roombookre);

// Fetch total staff
$staffsql ="SELECT * FROM staff";
$staffre = mysqli_query($conn, $staffsql);
$staffrow = mysqli_num_rows($staffre);

// Fetch total rooms
$roomsql ="SELECT * FROM room";
$roomre = mysqli_query($conn, $roomsql);
$roomrow = mysqli_num_rows($roomre);

// Fetch total user accounts
$usersql ="SELECT COUNT(*) as totalUsers FROM signup";
$userresult = mysqli_query($conn, $usersql);
$userrow = mysqli_fetch_assoc($userresult);
$totalUsers = $userrow['totalUsers'];

// Fetch total profit from payment table for paid payments
$profitsql = "SELECT SUM(finaltotal) as totalProfit FROM payment WHERE payment_status = 'Paid'";
$profitresult = mysqli_query($conn, $profitsql);
$profitrow = mysqli_fetch_assoc($profitresult);
$totalProfit = $profitrow['totalProfit'];

// Fetch total paid customers from payment table
$paidCustomersSql = "SELECT COUNT(*) as totalPaidCustomers FROM payment WHERE payment_status = 'Paid'";
$paidCustomersResult = mysqli_query($conn, $paidCustomersSql);
$paidCustomersRow = mysqli_fetch_assoc($paidCustomersResult);
$totalPaidCustomers = $paidCustomersRow['totalPaidCustomers'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-...your-integrity-hash-here..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/roombook.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <title>Administrator</title>
</head>
<body>
   <div class="databox">
        <div class="box roombookbox">
        <h2>Total Booked Room</h2>
            <h1><?php echo $totalPaidCustomers ?></h1>
        </div>
        <div class="box guestbox">
            <h2>Total Staff</h2>
            <h1><?php echo $staffrow ?></h1>
        </div>
        <div class="box totalAccountsBox">
            <h2>Total User Accounts</h2>
            <h1><?php echo $totalUsers ?></h1>
        </div>
        <div class="box totalProfitBox">
            <canvas id="profitChart" width="400" height="200"></canvas>
        </div>
        <div class="box clockbox">
            <h2>Current Date and Time</h2>
            <div id="clock"></div>
        </div>
    </div>
    
    <script>
    var totalProfit = <?php echo json_encode($totalProfit); ?>;
    var ctx = document.getElementById('profitChart').getContext('2d');
    var profitChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Profit'],
            datasets: [{
                label: 'Total Profit',
                data: [totalProfit],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
        });
        
        </script>
        
        <script>
        function updateClock() {
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            var seconds = currentTime.getSeconds();
            var day = currentTime.getDate();
            var month = currentTime.getMonth() + 1; 
            var year = currentTime.getFullYear();

            minutes = (minutes < 10 ? "0" : "") + minutes;
            seconds = (seconds < 10 ? "0" : "") + seconds;

            var timeFormat = (hours < 12) ? "AM" : "PM";
            hours = (hours > 12) ? hours - 12 : hours;
            hours = (hours === 0) ? 12 : hours;

            // Display the date and time
            var clockDiv = document.getElementById('clock');
            clockDiv.innerHTML = month + "/" + day + "/" + year + " " + hours + ":" + minutes + ":" + seconds + " " + timeFormat;

            setTimeout(updateClock, 1000);
        }

        updateClock();
        </script>
</body>
</html>
