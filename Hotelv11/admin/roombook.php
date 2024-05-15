<?php
session_start();
include '../config.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="css/roombook.css">
    <title>Administrator</title>
</head>

<body>
    <!-- guestdetailpanel -->

    <div id="guestdetailpanel">
        <form action="" method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3>RESERVATION</h3>
                <i class="fa-solid fa-circle-xmark" onclick="adduserclose()"></i>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name" required>
                    <input type="email" name="Email" placeholder="Enter Email" required>

                    <?php
                    $countries = array("Philippines", "Armenia", "Azerbaijan", "Bahrain", "Bangladesh", "Bhutan",
                    "Brunei", "Cambodia", "China", "Cyprus", "Georgia", "India", "Indonesia",
                    "Iran", "Iraq", "Israel", "Japan", "Jordan", "Kazakhstan", "Kuwait", "Kyrgyzstan",
                    "Laos", "Lebanon", "Malaysia", "Maldives", "Mongolia", "Myanmar (Burma)", "Nepal",
                    "North Korea", "Oman", "Pakistan", "Palestine", "Afghanistan", "Qatar", "Saudi Arabia",
                    "Singapore", "South Korea", "Sri Lanka", "Syria", "Taiwan", "Tajikistan", "Thailand",
                    "Timor-Leste", "Turkey", "Turkmenistan", "United Arab Emirates", "Uzbekistan", "Vietnam", "Yemen");
                    ?>

                    <select name="Country" class="selectinput" required>
						<option value selected >Select your country</option>
                        <?php
							foreach($countries as $key => $value):
							echo '<option value="'.$value.'">'.$value.'</option>';
							endforeach;
						?>
                    </select>
                    <input type="text" name="Phone" placeholder="Enter Phoneno" required>
                </div>

                <div class="line"></div>

                <div class="reservationinfo">
                    <h4>Reservation information</h4>
                    <select name="RoomType" class="selectinput">
						<option value selected >Type Of Room</option>
                        <option value="Superior Room">SUPERIOR ROOM</option>
                    </select>
                    <select name="Bed" class="selectinput">
						<option value selected >Bedding Type</option>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
						<option value="Triple">Triple</option>
                        <option value="Quad">Quad</option>
                    </select>
                    <div class="datesection">
                        <span>
                            <label for="cin"> Check-In</label>
                            <input name="cin" type ="date">
                        </span>
                        <span>
                            <label for="cin"> Check-Out</label>
                            <input name="cout" type ="date">
                        </span>
                    </div>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailsubmit">Submit</button>
            </div>
        </form>



        <!-- ==== room book php ====-->
        <?php       
            if (isset($_POST['guestdetailsubmit'])) {
                $Name = $_POST['Name'];
                $Email = $_POST['Email'];
                $Country = $_POST['Country'];
                $Phone = $_POST['Phone'];
                $RoomType = $_POST['RoomType'];
                $Bed = $_POST['Bed'];
                $Meal = $_POST['Meal'];
                $cin = $_POST['cin'];
                $cout = $_POST['cout'];

                if($Name == "" || $Email == "" || $Country == ""){
                    echo "<script>swal({
                        title: 'Fill the proper details',
                        icon: 'error',
                    });
                    </script>";
                }
                else{
                    $sta = "NotConfirm";
                    $sql = "INSERT INTO roombook(Name,Email,Country,Phone,RoomType,Bed,cin,cout,stat,nodays) VALUES ('$Name','$Email','$Country','$Phone','$RoomType','$Bed','$cin','$cout','$sta',datediff('$cout','$cin'))";
                    $result = mysqli_query($conn, $sql);

                        if ($result) {
                            echo "<script>swal({
                                title: 'Reservation successful',
                                icon: 'success',
                            });
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
        ?>
    </div>

    
              <!--VIEW-->

<div class="roombooktable" class="table-responsive-xl">
    <?php
    $roombooktablesql = "SELECT * FROM roombook";
    $roombookresult = mysqli_query($conn, $roombooktablesql);
    $nums = mysqli_num_rows($roombookresult);
    ?>
    <table class="table table-bordered" id="table-data">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">UserID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Country</th>
                <th scope="col">Phone</th>
                <th scope="col">Type of Room</th>
                <th scope="col">Type of Bed</th>
                <th scope="col">Check-In</th>
                <th scope="col">Check-Out</th>
                <th scope="col">No of Day</th>
                <th scope="col">Status</th>
                <th scope="col" class="action">Action</th>
            </tr>
        </thead>

        <tbody>
            <?php
            while ($res = mysqli_fetch_array($roombookresult)) {
            ?>
                <tr>
                    <td><?php echo $res['id'] ?></td>
                    <td><?php echo $res['UserID'] ?></td>
                    <td><?php echo $res['Name'] ?></td>
                    <td><?php echo $res['Email'] ?></td>
                    <td><?php echo $res['Country'] ?></td>
                    <td><?php echo $res['Phone'] ?></td>
                    <td><?php echo $res['RoomType'] ?></td>
                    <td><?php echo $res['Bed'] ?></td>
                    <td><?php echo $res['cin'] ?></td>
                    <td><?php echo $res['cout'] ?></td>
                    <td><?php echo $res['nodays'] ?></td>
                    <td><?php echo $res['stat'] ?></td>
                    <td class="action">
                        <?php
                        echo "<button class='btn btn-info' data-toggle='modal' data-target='#viewModal" . $res['id'] . "'>View</button>";

                        if ($res['stat'] != "Confirm") {
                            echo "<a href='javascript:void(0);' onclick='confirmConfirm(" . $res['id'] . ")'><button class='btn btn-success'>Confirm</button></a>";
                            echo "<a href='roombookedit.php?id=" . $res['id'] . "'><button class='btn btn-primary'>Update</button></a>";
                        }
                        ?>
                        <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $res['id'] ?>)"><button class='btn btn-danger'>Delete</button></a>

                    </td>
                </tr>

                <!-- VIEW BUTTON Modal -->
                <div class="modal fade" id="viewModal<?php echo $res['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewModalLabel">Booking Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><strong>UserID:</strong> <?php echo $res['UserID']; ?></p>
                                <p><strong>Name:</strong> <?php echo $res['Name']; ?></p>
                                <p><strong>Email:</strong> <?php echo $res['Email']; ?></p>
                                <p><strong>Country:</strong> <?php echo $res['Country']; ?></p>
                                <p><strong>Phone:</strong> <?php echo $res['Phone']; ?></p>
                                <p><strong>Type of Room:</strong> <?php echo $res['RoomType']; ?></p>
                                <p><strong>Type of Bed:</strong> <?php echo $res['Bed']; ?></p>
                                <p><strong>Check-in:</strong> <?php echo $res['cin']; ?></p>
                                <p><strong>Check-out:</strong> <?php echo $res['cout']; ?></p>
                                <p><strong>Number of Day:</strong> <?php echo $res['nodays']; ?></p>
                                <p><strong>Status:</strong> <?php echo $res['stat']; ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </tbody>
    </table>

<!-- REFRESH BUTTON -->
<button class="btn btn-primary" id="refreshButton">
    <i class="fas fa-sync-alt"></i></button>

    <!-- CONFIRM BUTTON -->
    <script>
        function confirmConfirm(id) {
            Swal.fire({
                title: 'Confirm Reservation?',
                text: 'Marking this reservation as confirmed implies finality. Please note that if you proceed this confirmation, you wont be able to edit it later.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'roomconfirm.php?id=' + id;
                }
            });
        }
    </script>
    
    <script>
    // Refresh the page when the refresh button is clicked
    document.getElementById('refreshButton').addEventListener('click', function () {
        location.reload();
        });
        </script>
        
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Deleting this reservation will permanently remove in room booking',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'roombookdelete.php?id=' + id;
                    }
                });
            }
            
            </script>


</div>


</body>
<script src="./javascript/roombook.js"></script>



</html>
