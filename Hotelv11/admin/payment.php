<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    // Assuming that the 'pay' button sends the payment ID as a POST parameter
    $paymentId = $_POST['pay'];

    // Update the payment status to 'Paid'
    $updatePaymentStatusSql = "UPDATE payment SET payment_status = 'Paid' WHERE id = $paymentId";
    mysqli_query($conn, $updatePaymentStatusSql);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    // Assuming that the 'cancel' button sends the payment ID as a POST parameter
    $paymentId = $_POST['cancel'];

    // Update the payment status to 'Cancelled'
    $updatePaymentStatusSql = "UPDATE payment SET payment_status = 'Cancelled' WHERE id = $paymentId";
    mysqli_query($conn, $updatePaymentStatusSql);
}

// Initialize filter variables
$filterToPay = isset($_POST['to_pay']) && $_POST['to_pay'] === 'on';
$filterPaid = isset($_POST['paid']) && $_POST['paid'] === 'on';

// Build the WHERE clause based on the selected checkboxes
$whereClause = '';
if ($filterToPay && !$filterPaid) {
    $whereClause = "WHERE payment_status = 'To Pay'";
} elseif (!$filterToPay && $filterPaid) {
    $whereClause = "WHERE payment_status = 'Paid'";
}

// Construct the SQL query with the WHERE clause
$paymanttablesql = "SELECT * FROM payment $whereClause";
$paymantresult = mysqli_query($conn, $paymanttablesql);
$nums = mysqli_num_rows($paymantresult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
	<link rel="stylesheet" href="css/roombook.css">
</head>
<body>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="roombooktable" class="table-responsive-xl">
    <div class="filter-form">
        <form id="filterForm" method="post">
            <label><input type="checkbox" name="to_pay" <?php echo $filterToPay ? 'checked' : ''; ?> onchange="submitForm()"> To Pay</label>
            <label><input type="checkbox" name="paid" <?php echo $filterPaid ? 'checked' : ''; ?> onchange="submitForm()"> Paid</label>
        </form>
    </div>

        <table class="table table-bordered" id="table-data">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Room Type</th>
                    <th scope="col">Bed Type</th>
                    <th scope="col">Check In</th>
                    <th scope="col">Check In</th>
					<th scope="col">No of Day</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Total Bill</th>
                    <th scope="col">Payment Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>

            <tbody>
            <?php
            while ($res = mysqli_fetch_array($paymantresult)) {
            ?>
                <tr>
                    <td><?php echo $res['id'] ?></td>
                    <td><?php echo $res['Name'] ?></td>
                    <td><?php echo $res['RoomType'] ?></td>
                    <td><?php echo $res['Bed'] ?></td>
					<td><?php echo $res['cin'] ?></td>
                    <td><?php echo $res['cout'] ?></td>
					<td><?php echo $res['noofdays'] ?></td>
                    <td><?php echo $res['created_at'] ?></td>
                    <td><?php echo $res['finaltotal'] ?></td>
                    <td><?php echo $res['payment_status'] ?></td>
                    <td class="action">
                        <button class="btn btn-primary" onclick="viewDetails('<?php echo $res['Name'] ?>', '<?php echo $res['RoomType'] ?>', '<?php echo $res['Bed'] ?>', '<?php echo $res['cin'] ?>', '<?php echo $res['cout'] ?>', '<?php echo $res['noofdays'] ?>', '<?php echo $res['created_at'] ?>', '<?php echo $res['finaltotal'] ?>',  '<?php echo $res['payment_status'] ?>')">View</button>
                        <?php if ($res['payment_status'] == 'To Pay'): ?>
                            <form id="cancelForm<?php echo $res['id']; ?>" method="post" style="display: inline;">
                            <input type="hidden" name="cancel" value="<?php echo $res['id']; ?>">
                            <button type="button" class="btn btn-danger" onclick="confirmCancel(<?php echo $res['id']; ?>)">Cancel</button>
                        </form>
                        <form id="updateForm<?php echo $res['id']; ?>" method="post" style="display: inline;">
                        <input type="hidden" name="pay" value="<?php echo $res['id']; ?>">
                        <button type="button" class="btn btn-success" onclick="confirmPayment(<?php echo $res['id']; ?>)">Update Payment</button>
                    </form>
                    <?php endif; ?>
                </td>
            
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
        
        <!-- REFRESH BUTTON -->
        <button class="btn btn-primary" id="refreshButton">
            <i class="fas fa-sync-alt"></i></button>


            <script>
            // Refresh the page when the refresh button is clicked
            document.getElementById('refreshButton').addEventListener('click', function () {
                location.reload();
                });
                
                </script>
                
            </div>


                <script>
                function viewDetails(name, roomType, bed, cin, cout, noofdays, created_at, finaltotal, payment_status) {
                    var modalContent = `
                    <b>Name:</b> ${name}<br>
                    <b>Room Type:</b> ${roomType}<br>
                    <b>Bed Type:</b> ${bed}<br>
                    <b>Check In:</b> ${cin}<br>
                    <b>Check Out:</b> ${cout}<br>
                    <b>No of Days:</b> ${noofdays}<br>
                    <b>Created At:</b> ${created_at}<br>
                    <b>Total Bill:</b> ${finaltotal}<br>
                    <b>Payment Status:</b> ${payment_status}<br>
                    `;

                    document.getElementById('modalBody').innerHTML = modalContent;
                    var viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
                    viewModal.show();
                }
                
                function confirmPayment(paymentId) {
                    Swal.fire({
                        title: 'Confirm Payment',
                        text: 'Are you sure you want to update the payment status? It will mark as paid',
                        iconHtml: '<i class="fa-solid fa-peso-sign" style="font-size: 36px;"></i>',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, update payment!',
                        cancelButtonText: 'Cancel',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`updateForm${paymentId}`).submit();
                        }
                    });
                }
                </script>
                
                <script>
                function confirmCancel(paymentId) {
                    Swal.fire({
                        title: 'Cancel Payment',
                        text: 'Are you sure you want to cancel this payment? It will be marked as cancelled.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, cancel it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`cancelForm${paymentId}`).submit();
                        }
                    });
                }
                
                </script>
                
                <script>
                        // Filter Checkbox
                    function submitForm() {
                        document.getElementById('filterForm').submit();
                    }
                    </script>
    </body>
</html>
