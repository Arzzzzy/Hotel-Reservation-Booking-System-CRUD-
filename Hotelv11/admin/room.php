<?php
session_start();
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="css/room.css">
</head>


<body>
    <!--Add and Delete Buttons-->
    <div class="addroomsection">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addRoomModal">Add Room</button>
        <button type="button" class="btn btn-danger ml-2" data-toggle="modal" data-target="#deleteRoomModal">Delete Room</button>
        
        <!-- Add Room Modal -->
        <div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">Add Room</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" onsubmit="return validateForm();">
                    <label for="troom">Room Type</label>
                    <input type="text" name="troom" class="form-control" placeholder="Enter Room Type" required>
                    <br>
                    <label for="price_single">Price for Single Room</label>
                    <input type="text" name="price_single" class="form-control" placeholder="Enter Price for Single Room" onkeypress="return isNumberKey(event)" required>
                    <br>

                    <label for="price_double">Price for Double Room</label>
                    <input type="text" name="price_double" class="form-control" placeholder="Enter Price for Double Room" onkeypress="return isNumberKey(event)" required>
                    <br>

                    <label for="price_triple">Price for Triple Room</label>
                    <input type="text" name="price_triple" class="form-control" placeholder="Enter Price for Triple Room" onkeypress="return isNumberKey(event)" required>
                    <br>

                    <label for="price_quad">Price for Quad Room</label>
                    <input type="text" name="price_quad" class="form-control" placeholder="Enter Price for Quad Room" onkeypress="return isNumberKey(event)" required>
                    <br>
                    
                    <button type="submit" class="btn btn-success" name="addroom">Add Room</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<!-- SHOW ALL AVAILABLE ROOM FROM THE DATABASE-->
<div class="room">
    <?php
    $sql = "SELECT * FROM room";
    $re = mysqli_query($conn, $sql)
    ?>
    <?php
    while ($row = mysqli_fetch_array($re)) {
        $id = $row['type'];
        {
            echo "<div class='roombox roomboxsuperior'>
                    <div class='text-center no-boder'>
                        <i class='fa-solid fa-bed fa-4x mb-2'></i>
                        <h3>" . $row['type'] . "</h3>
                        <div class='mb-1'>" . $row['bedding'] . "</div>
                    </div>
                </div>";
        }
    }
    ?>
</div>

<!--js for only numbers is accepted-->
<script>
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
         Swal.fire({

            icon: 'error',
                title: 'Invalid Input',
                text: 'Please enter only numeric values.',
            });
            return false;
        }
        return true;
    }

    function validateForm() {
        if (document.forms[0]["troom"].value === "" ||
            document.forms[0]["price_single"].value === "" ||
            document.forms[0]["price_double"].value === "" ||
            document.forms[0]["price_triple"].value === "" ||
            document.forms[0]["price_quad"].value === "") {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all fields',
            });

            return false;
        }

        return true;
    }
</script>

<!--insert into room table-->
<?php
if (isset($_POST['addroom'])) {
    $typeofroom = $_POST['troom'];

    // Retrieve prices for each room type
    $price_single = $_POST['price_single'];
    $price_double = $_POST['price_double'];
    $price_triple = $_POST['price_triple'];
    $price_quad = $_POST['price_quad'];

    // Insert each room type into the database
    $sql_single = "INSERT INTO room(type, bedding, price) VALUES ('$typeofroom', 'Single', '$price_single')";
    $sql_double = "INSERT INTO room(type, bedding, price) VALUES ('$typeofroom', 'Double', '$price_double')";
    $sql_triple = "INSERT INTO room(type, bedding, price) VALUES ('$typeofroom', 'Triple', '$price_triple')";
    $sql_quad = "INSERT INTO room(type, bedding, price) VALUES ('$typeofroom', 'Quad', '$price_quad')";

    // Execute each query
    $result_single = mysqli_query($conn, $sql_single);
    $result_double = mysqli_query($conn, $sql_double);
    $result_triple = mysqli_query($conn, $sql_triple);
    $result_quad = mysqli_query($conn, $sql_quad);

    if ($result_single && $result_double && $result_triple && $result_quad) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Room Added Successfully',
                    text: 'The room list has been updated.',
                }).then(() => {
                    // Reload the page or update the room list
                    window.location.href = 'room.php'; 
                });
              </script>";
    }
}
?>

</div>

<!-- Delete Room Modal -->
<div class="modal fade" id="deleteRoomModal" tabindex="-1" role="dialog" aria-labelledby="deleteRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRoomModalLabel">Delete Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" onsubmit="return validateDeleteForm();">
                    <label for="deleteRoomType">Select Room Type to Delete</label>
                    <select class="form-control" id="deleteRoomType" name="deleteRoomType" required>
                        <?php
                        $sql = "SELECT DISTINCT type FROM room";
                        $re = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_array($re)) {
                            echo "<option value='{$row['type']}'>{$row['type']}</option>";
                        }
                        ?>
                    </select>

                    <br>

                    <button type="submit" class="btn btn-danger" name="deleteRoom">Delete Room</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function validateDeleteForm() {
        var selectedRoomType = $("#deleteRoomType").val();

        if (!selectedRoomType) {e
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a room type to delete',
            });
            return false;
        }

        return true;
    }
</script>

<!--delete into room table based on the selected room type-->
<?php
if (isset($_POST['deleteRoom'])) {
    $selectedRoomType = $_POST['deleteRoomType'];

    
    $deleteRoomsQuery = "DELETE FROM room WHERE type = '$selectedRoomType'";
    $result = mysqli_query($conn, $deleteRoomsQuery);

    if ($result) {
        // Display success message using SweetAlert
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'The room type and its rooms have been deleted.',
                }).then(() => {
                    // Reload the page or update the room list
                    window.location.href = 'room.php'; 
                });
              </script>";
        exit(); 
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete the room type. Please try again.',
                });
              </script>";
    }
}
?>


    
</body>

</html>