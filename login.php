<?php
session_start();
include './api/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to fetch user data
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            // Redirect based on user role
            if ($user['role'] == 'admin') {
                header("Location: ./admin/admin_dashboard.php");
            } else if($user['role'] == 'doctor') {
                header("Location: ./doctors/dashboard.php");
            } else {
                header("Location: ./users/dashboard.php");
            }
        } else {
            echo "<p class='text-red-500'>Invalid password!</p>";
        }
    } else {
        echo "<p class='text-red-500'>No user found with this email!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hospital Management System </title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="w-full h-screen flex bg-zinc-100">
        <div class="w-1/2 bg-green-200 h-full flex flex-col items-center justify-center ">
            <img class="w-1/4 mix-blend-multiply" src="https://imgs.search.brave.com/ji-z4N5G57kbJ6mfMOHiOREmoDYOI1sAFP-w3Ou9yHU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90NC5m/dGNkbi5uZXQvanBn/LzA4LzA2LzI0Lzg5/LzM2MF9GXzgwNjI0/ODk0OF84Q2FtajRp/S3JSQnlyS1NlNm5k/V21NRWxDN1RJTEhs/MS5qcGc" alt="">
            <h1 class="text-zinc-900 font-bold text-xl">Hospital Management System</h1>
        </div>
        <div class="w-1/2 flex flex-col items-center justify-center h-full ">
                <h2 class="text-green-400 font-black text-3xl">Login</h2>
                <p class="text-sm italic">Hospital Management System   </p>
                <form class="flex flex-col gap-2 w-[60%]" method="POST">
                    
                    <div class="flex flex-col gap-2">
                        <label for="email" class="font-semibold">Email : </label>
                        <input class="px-4 py-2 border border-gray-300 rounded-lg" type="email" name="email">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="password" class="font-semibold">password : </label>
                        <input class="px-4 py-2 border border-gray-300 rounded-lg" type="password" name="password">
                    </div>
                    <button type="submit" class="text-white bg-green-400 w-fit px-6 mt-3 mx-auto rounded-xl py-2 font-semibold">Login</button>
                </form>
                <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Don't have an account? <a href="registration.php" class="text-green-500 hover:text-green-700">Sign Up</a></p>
            </div>
        </div>

    </div>
</body>
</html>