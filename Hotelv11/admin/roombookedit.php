<?php

include '../config.php';

// EDIT OR UPDATE
$id = $_GET['id'];

$sql ="Select * from roombook where id = '$id'";
$re = mysqli_query($conn,$sql);
while($row=mysqli_fetch_array($re))
{
    $Name = $row['Name'];
    $Email = $row['Email'];
    $Country = $row['Country'];
    $Phone = $row['Phone'];
    $cin = $row['cin'];
    $cout = $row['cout'];
    $noofday = $row['nodays'];
    $stat = $row['stat'];
}

if (isset($_POST['guestdetailedit'])) {
    $EditName = $_POST['Name'];
    $EditEmail = $_POST['Email'];
    $EditCountry = $_POST['Country'];
    $EditPhone = $_POST['Phone'];
    $EditRoomType = isset($_POST['RoomType']) ? $_POST['RoomType'] : '';
    $EditBed = isset($_POST['Bed']) ? $_POST['Bed'] : '';
    $Editcin = $_POST['cin'];
    $Editcout = $_POST['cout'];

    if (empty($EditName) || empty($EditEmail) || empty($EditCountry) || empty($EditPhone) || empty($EditRoomType) || empty($EditBed) ||  empty($Editcin) || empty($Editcout)) {
        echo "<script>
                alert('Error: All fields are required.');
                window.history.go(-1); // Go back to the previous page
              </script>";
        exit;
    }

    // Calculate the number of days between check-in and check-out
    $nodays = date_diff(date_create($Editcin), date_create($Editcout))->format('%R%a');

    // Check if the check-in date is not before the current date
    if (strtotime($Editcin) < strtotime(date('Y-m-d'))) {
        echo "<script>
                alert('Error: Check-in date cannot be before today.');
                window.history.go(-1); // Go back to the previous page
              </script>";
        exit;
    } elseif ($nodays < 0) {
        echo "<script>
                alert('Error: Check-out date cannot be before check-in date.');
                window.history.go(-1); // Go back to the previous page
              </script>";
        exit;
    }

    $sql = "UPDATE roombook SET Name = '$EditName',Email = '$EditEmail',Country='$EditCountry',Phone='$EditPhone',RoomType='$EditRoomType',Bed='$EditBed',cin='$Editcin',cout='$Editcout',nodays = datediff('$Editcout','$Editcin') WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    
    // noofday update
    $psql ="Select * from roombook where id = '$id'";
    $presult = mysqli_query($conn,$psql);
    $prow=mysqli_fetch_array($presult);
    $Editnoofday = $prow['nodays'];

    $editttot = $type_of_room*$Editnoofday;
    $editbtot = $type_of_bed*$Editnoofday;

    $editfintot = $editttot + $editbtot;

    $psql = "UPDATE payment SET Name = '$EditName',Email = '$EditEmail',RoomType='$EditRoomType',Bed='$EditBed',cin='$Editcin',cout='$Editcout',noofdays = '$Editnoofday',finaltotal = '$editfintot' WHERE id = '$id'";

    $paymentresult = mysqli_query($conn,$psql);

    if ($paymentresult) {
            header("Location:roombook.php");
    }

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="css/roombook.css">
    <style>
        
        #editpanel{
            position : fixed;
            z-index: 1000;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            /* align-items: center; */
            background-color: rgba(255, 255, 255, 0.752);
        }
        #editpanel .guestdetailpanelform{
            height: 620px;
            width: 1170px;
            background-color: rgba(255, 255, 255, 0.752);
            border-radius: 10px;  
            /* temp */
            position: relative;
            top: 20px;
            animation: guestinfoform .3s ease;
        }

    </style>
    <title>Document</title>
</head>
<body>

<?php
session_start();
$emailValue = isset($_SESSION['usermail']) ? $_SESSION['usermail'] : ''; 
?>

    <div id="editpanel">
        <form method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3>EDIT RESERVATION</h3>
                <a href="./roombook.php"><i class="fa-solid fa-circle-xmark"></i></a>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name" value="<?php echo $Name ?>">
                    <!--<input type="email" name="Email" placeholder="Enter Email" value="<?php echo $Email ?>">-->
                    <input type="email" name="Email" placeholder="Enter Email" value="<?php echo isset($_SESSION['usermail']) ? $_SESSION['usermail'] : ''; ?>" readonly>
                    

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
						<option value selected >Select your country</option>
                        <?php
							foreach($countries as $key => $value):
							echo '<option value="'.$value.'">'.$value.'</option>';
							endforeach;
						?>
                    </select>
                    <input type="text" name="Phone" placeholder="Enter Phone"  value="<?php echo $Phone ?>">
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
                            <input name="cin" type ="date" value="<?php echo $cin ?>">
                        </span>
                        <span>
                            <label for="cin"> Check-Out</label>
                            <input name="cout" type ="date" value="<?php echo $cout ?>">
                        </span>
                    </div>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailedit">Edit</button>
            </div>
        </form>
    </div>
</body>
</html>