<?php
session_start();
include 'koneksimysql.php';

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Kla Computer Admin Dashboard">
    <meta name="author" content="">

    <title>Kla Computer - Admin Login</title>

    <link rel="icon" href="images/logo.png">

    <!-- Custom fonts -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles -->
    <style>
        :root {
            --primary-color: #481969;
            --secondary-color: #3A1453;
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            box-sizing: border-box;
            font-family: 'Nunito', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            padding: 2rem;
            width: 100%;
            max-width: 450px;
        }

        .glass-card h1 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 1rem;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        input::placeholder {
            color: #ddd;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .logo img {
            height: 80px;
        }

        @media (max-width: 500px) {
            .glass-card {
                padding: 1.5rem;
                margin: 1rem;
            }

            .logo img {
                height: 60px;
            }
        }
    </style>

    <!-- alert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        // Query untuk memeriksa email dan password
        $sql = "SELECT * FROM user WHERE email = ? AND password = ? AND role = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['foto'] = $row['foto'];
            echo "<script>
                let timerInterval;
                Swal.fire({
                    title: 'Berhasil Login!',
                    html: 'Selamat datang di halaman dashboard admin!',
                    timer: 3000,
                    timerProgressBar: false,
                    icon:'success',
                    showConfirmButton: false,
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = 'index.php';
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Email atau password salah!',
                    icon: 'error'
                });
            </script>";
        }
        $stmt->close();
    } ?>

    <form method="POST" action="login.php" class="glass-card">
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
        </div>
        <h1>Login Admin</h1>
        <input type="email" name="email" placeholder="Enter Email Address..." required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn-login">Login</button>
    </form>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php $conn->close(); ?>