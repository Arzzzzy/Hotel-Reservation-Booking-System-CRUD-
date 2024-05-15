<?php

include 'config.php';
session_start();

$usermail = "";
$usermail = $_SESSION['usermail'];

if (!$usermail) {
    header("location: index.php");
    exit; 
}

$sql_user = "SELECT UserID, Username FROM signup WHERE Email = '$usermail'";
$result_user = mysqli_query($conn, $sql_user);

if ($result_user) {
    $row_user = mysqli_fetch_assoc($result_user);

    if ($row_user !== null && array_key_exists('UserID', $row_user)) {
        $userID = $row_user['UserID'];
        $username = $row_user['Username'];
    } else {
        echo "Session Expired";
        exit;
    }
} else {
    echo "Query failed";
    exit;
}

// Fetch distinct Room Types
$sql_room_types = "SELECT DISTINCT type FROM room";
$result_room_types = mysqli_query($conn, $sql_room_types);

// Fetch distinct Bedding Types
$sql_bedding_types = "SELECT DISTINCT bedding FROM room";
$result_bedding_types = mysqli_query($conn, $sql_bedding_types);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>Basta Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./admin/css/roombook.css">
    <style>
      #guestdetailpanel{
        display: none;
      }
      #guestdetailpanel .middle{
        height: 450px;
      }
    </style>
</head>

<body>
  <nav>
    <div class="logo">
      <p>Basta Hotel</p>
    </div>
    <ul>
      <li><a href="#firstsection">Home</a></li>
      <button class="btn btn-primary bookbtn" onclick="openbookbox()"><a href="#secondsection" style="text-decoration: none; color: inherit;">Book Now!</a></button>
      <li onclick="openHistory()">History</li>
      <li><a href="#" onclick="viewRoomPrices()">View Rate</a></li>
      <a href="./logout.php"><button class="btn btn-danger">Logout</button></a>
    </ul>
  </nav>

  <section id="firstsection">
    <center>
    <div class="welcomeline">
    <h1 class="welcometag">Welcome, <span class="highlight"><?php echo $username; ?></span> Experience The Luxury Comfort!</h1>
      <br>
    </div>
  </center>

    <!-- bookbox -->
    <div id="guestdetailpanel">
      <form action="" method="POST" class="guestdetailpanelform">
        <div class="head">
          <h3>Booking System</h3>
          <i class="fa-solid fa-circle-xmark" onclick="closebox()"></i>
        </div>
        <div class="middle">
          <div class="guestinfo">
            <h4>Guest information</h4>
            <input type="text" name="Name" placeholder="Enter Full name">
            <input type="text" name="Email" placeholder="Enter Email" >

            <?php
            $countries = array("Philippines", "Armenia", "Azerbaijan", "Bahrain", "Bangladesh", "Bhutan",
            "Brunei", "Cambodia", "China", "Cyprus", "Georgia", "India", "Indonesia",
            "Iran", "Iraq", "Israel", "Japan", "Jordan", "Kazakhstan", "Kuwait", "Kyrgyzstan",
            "Laos", "Lebanon", "Malaysia", "Maldives", "Mongolia", "Myanmar (Burma)", "Nepal",
            "North Korea", "Oman", "Pakistan", "Palestine", "Afghanistan", "Qatar", "Saudi Arabia",
            "Singapore", "South Korea", "Sri Lanka", "Syria", "Taiwan", "Tajikistan", "Thailand",
            "Timor-Leste", "Turkey", "Turkmenistan", "United Arab Emirates", "Uzbekistan", "Vietnam", "Yemen");
            ?>

            <select name="Country" class="selectinput">
              <option value selected>Select your country</option>
              <?php
              foreach ($countries as $key => $value):
                echo '<option value="' . $value . '">' . $value . '</option>';
              endforeach;
              ?>
            </select>
            <input type="text" name="Phone" placeholder="Enter Phone">
          </div>

          <div class="line"></div>

          <div class="reservationinfo">
            <h4>Reservation information</h4>
            <select name="RoomType" class="selectinput">
              <option value="" selected disabled>Select Room Type</option>
              <?php
              while ($row = mysqli_fetch_assoc($result_room_types)) {
                echo '<option value="' . $row['type'] . '">' . $row['type'] . '</option>';
              }
              ?>
              </select>
              <select name="Bed" class="selectinput">
                <option value="" selected disabled>Select Bedding Type</option>
                <?php
                while ($row = mysqli_fetch_assoc($result_bedding_types)) {
                  echo '<option value="' . $row['bedding'] . '">' . $row['bedding'] . '</option>';
                }
                ?>
                </select>
            <div class="datesection">
              <span>
                <label for="cin"> Check-In</label>
                <input name="cin" type="date">
              </span>
              <span>
                <label for="cin"> Check-Out</label>
                <input name="cout" type="date">
              </span>
            </div>
          </div>
        </div>
        <div class="footer">
          <button class="btn btn-success" name="guestdetailsubmit">Submit</button>
        </div>
      </form>

      <?php
      if (isset($_POST['guestdetailsubmit'])) {
        $Name = $_POST['Name'];
        $Email = $_POST['Email'];
        $Country = $_POST['Country'];
        $Phone = $_POST['Phone'];
        $RoomType = isset($_POST['RoomType']) ? $_POST['RoomType'] : '';
        $Bed = isset($_POST['Bed']) ? $_POST['Bed'] : '';
        $cin = $_POST['cin'];
        $cout = $_POST['cout'];

        if ($Name == "" || $Email == "" || $Country == "" || $RoomType == "" || $Bed == "" ||  $cin == "" || $cout == "") {
          echo "<script>swal({
                    title: 'Fill out all Details',
                    icon: 'error',
                });
                </script>";
        } else {
          $nodays = date_diff(date_create($cin), date_create($cout))->format('%R%a');
          if (strtotime($cin) < strtotime(date('Y-m-d'))) {
            echo "<script>swal({
                        title: 'Invalid Check-In Date',
                        text: 'Check-in date cannot be before today.',
                        icon: 'error',
                    });
                    </script>";
          } elseif ($nodays < 0) {
            echo "<script>swal({
                        title: 'Invalid Dates',
                        text: 'Check-out date cannot be before check-in date.',
                        icon: 'error',
                    });
                    </script>";
          } else {
            $sta = "NotConfirm";
            $sql = "INSERT INTO roombook(UserID, Name, Email, Country, Phone, RoomType, Bed,  cin, cout, stat, nodays) VALUES ('$userID', '$Name', '$Email', '$Country', '$Phone', '$RoomType', '$Bed', '$cin', '$cout', '$sta', '$nodays')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
              echo "<script>
                                window.location.href = 'success.php'; // Redirect after successful reservation
                                </script>";
            } else {
              echo "<script>swal({
                                title: 'Something went wrong',
                                icon: 'error',
                            });
                            </script>";
            }
          }
        }
      }
      ?>

    </div>
  </section>

  <section id="contactus">
    <div class="social">
    <a href="https://www.instagram.com/arzz_chris/" target="_blank">
        <i class="fab fa-instagram"></i>
    </a>
    <a href="https://www.facebook.com/christianandrei.tolentinoarzadon" target="_blank">
        <i class="fab fa-facebook"></i>
    </a>
    <a href="mailto:arzzpaypal2gmail.com">
        <i class="fas fa-envelope"></i>
    </a>
    </div>
    <div class="createdby">
    <p>arzadon_christian</p>
      <h6>BSCS 2-B</h6>
    </div>
  </section>

  <script>

    var bookbox = document.getElementById("guestdetailpanel");

    openbookbox = () => {
      bookbox.style.display = "flex";
    }
    closebox = () => {
      bookbox.style.display = "none";
    }
  </script>

  <!--HISTORY FUNCTION-->
  <script>
    function openHistory() {
      <?php
      $sql = "SELECT * FROM roombook WHERE UserID = '$userID'";
      $result = mysqli_query($conn, $sql);
      ?>

      var historyContent = "<div style='max-height: 300px; overflow-y: auto;'><h2>Booking History</h2><table class='table table-bordered'><thead><tr><th>Name</th><th>Email</th><th>Country</th><th>Phone</th><th>RoomType</th><th>Bed</th><th>Check-In</th><th>Check-Out</th><th>Status</th></tr></thead><tbody>";
      <?php
      while ($row = mysqli_fetch_assoc($result)) {
        echo "historyContent += \"<tr><td>{$row['Name']}</td><td>{$row['Email']}</td><td>{$row['Country']}</td><td>{$row['Phone']}</td><td>{$row['RoomType']}</td><td>{$row['Bed']}</td><td>{$row['cin']}</td><td>{$row['cout']}</td><td>{$row['stat']}</td></tr>\";";
      }
      ?>
      historyContent += "</tbody></table></div>";
      var modal = new bootstrap.Modal(document.getElementById('historyModal'));
      document.getElementById('historyModalContent').innerHTML = historyContent;
      modal.show();
    }
  </script>

  <!-- history Bootstrap modal -->
  <div class="modal" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="historyModalContent">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>


  <!--VIEW PRICES-->
<script>
  function viewRoomPrices() {
    <?php
    $sql_prices = "SELECT * FROM room";
    $result_prices = mysqli_query($conn, $sql_prices);
    ?>

    var pricesContent = "<div style='max-height: 300px; overflow-y: auto;'><h2>Room Prices</h2><table class='table table-bordered'><thead><tr><th>Room Type</th><th>Bedding Type</th><th>Price per day/s</th></tr></thead><tbody>";
    <?php
    while ($row_prices = mysqli_fetch_assoc($result_prices)) {
      echo "pricesContent += \"<tr><td>{$row_prices['type']}</td><td>{$row_prices['bedding']}</td><td>{$row_prices['price']}</td></tr>\";";
    }
    ?>
    pricesContent += "</tbody></table></div>";

    var modal = new bootstrap.Modal(document.getElementById('pricesModal'));
    document.getElementById('pricesModalContent').innerHTML = pricesContent;
    modal.show();
  }
</script>

<!-- Prices Bootstrap modal -->
<div class="modal" id="pricesModal" tabindex="-1" aria-labelledby="pricesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="pricesModalContent">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</script>


</body>
</html>
