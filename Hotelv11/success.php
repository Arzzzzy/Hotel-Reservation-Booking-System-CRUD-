<!-- success.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/sweetalert/dist/sweetalert.css">
    <title>Reservation Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
    </style>
</head>
<body>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        swal({
            title: 'Reservation successful',
            text: 'Your reservation has been confirmed!',
            icon: 'success',
        }).then(function() {
            window.location.href = 'home.php';
        });
    </script>
</html>

