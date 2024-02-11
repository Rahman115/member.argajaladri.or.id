<?php
// include 'config.php';
// session_start();

// if (isset($_SESSION['username'])) {
//     header("Location: index.php");
//     exit();
// }

// if (isset($_POST['submit'])) {
//     $username = $_POST['username'];
//     $email = $_POST['email'];
//     $password = hash('sha256', $_POST['password']); // Hash the input password using SHA-256
//     $cpassword = hash('sha256', $_POST['cpassword']); // Hash the input confirm password using SHA-256

//     if ($password == $cpassword) {
//         $sql = "SELECT * FROM users WHERE email='$email'";
//         $result = mysqli_query($conn, $sql);
//         if (!$result->num_rows > 0) {
//             $sql = "INSERT INTO users (username, email, password)
//                     VALUES ('$username', '$email', '$password')";
//             $result = mysqli_query($conn, $sql);
//             if ($result) {
//                 echo "<script>alert('Selamat, registrasi berhasil!')</script>";
//                 $username = "";
//                 $email = "";
//                 $_POST['password'] = "";
//                 $_POST['cpassword'] = "";
//             } else {
//                 echo "<script>alert('Woops! Terjadi kesalahan.')</script>";
//             }
//         } else {
//             echo "<script>alert('Woops! Email Sudah Terdaftar.')</script>";
//         }
//     } else {
//         echo "<script>alert('Password Tidak Sesuai')</script>";
//     }
//}
?>

<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Register Member</title>
</head>

<body>
    <div class="container">
        <form action="" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Register</p>
            <p class="reg" style="font-size: .7rem; font-weight: 600; color:red">Untuk Sementara Registrasi Belum di Tambahkan Silahkan Hubungi Admin (Mentel)</p>
            <div class="input-group">
                <input type="text" placeholder="Username" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div class="input-group">
                <input type="email" placeholder="Email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" value="<?php echo $_POST['password']; ?>"
                    required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Confirm Password" name="cpassword"
                    value="<?php echo $_POST['cpassword']; ?>" required>
            </div>
            <div class="input-group">
                <button name="submit" class="btn" disabled>Register</button>
            </div>
            <p class="login-register-text">Anda sudah punya akun? <a href="index.php">Login</a></p>
        </form>
    </div>
</body>

</html>