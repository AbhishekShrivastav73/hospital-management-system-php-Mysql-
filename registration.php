<?php

session_start();

include './api/db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone - $_POST['phone'];

    $sql = "INSERT INTO users (username, password, email,phone) VALUES ('$username', '$password', '$email', '$phone')";

    if (mysqli_query($con, $sql)) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['userId'] = mysqli_insert_id($con);
        $_SESSION['role'] = 'patient';

        header("Location: ./users/dashboard.php");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="w-full h-screen flex bg-zinc-100">
        <!-- Left Section with Image -->
        <div class="w-1/2 bg-green-200 h-full flex flex-col items-center justify-center">
            <img class="w-1/4 mix-blend-multiply" src="https://imgs.search.brave.com/ji-z4N5G57kbJ6mfMOHiOREmoDYOI1sAFP-w3Ou9yHU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90NC5m/dGNkbi5uZXQvanBn/LzA4LzA2LzI0Lzg5/LzM2MF9GXzgwNjI0/ODk0OF84Q2FtajRp/S3JSQnlyS1NlNm5k/V21NRWxDN1RJTEhs/MS5qcGc" alt="Hospital Image">
            <h1 class="text-zinc-900 font-bold text-xl">Hospital Management System</h1>
        </div>

        <!-- Right Section with Form -->
        <div class="w-1/2 flex flex-col items-center justify-center h-full">
            <h2 class="text-green-400 font-black text-3xl">Create an Account</h2>
            <p class="text-sm italic mb-6">Hospital Management System</p>

            <!-- Registration Form -->
            <form class="flex flex-col gap-4 w-[60%]" method="POST">
                <!-- Username -->
                <div class="flex flex-col gap-2">
                    <label for="username" class="font-semibold">Username:</label>
                    <input class="px-4 py-2 border border-gray-300 rounded-lg" type="text" name="username" required>
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-2">
                    <label for="email" class="font-semibold">Email:</label>
                    <input class="px-4 py-2 border border-gray-300 rounded-lg" type="email" name="email" required>
                </div>

                <!-- Phone -->
                <div class="flex flex-col gap-2">
                    <label for="phone" class="font-semibold">Phone Number:</label>
                    <input class="px-4 py-2 border border-gray-300 rounded-lg" type="tel" name="phone" required>
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-2">
                    <label for="password" class="font-semibold">Password:</label>
                    <input class="px-4 py-2 border border-gray-300 rounded-lg" type="password" name="password" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="text-white bg-green-400 w-fit px-6 mt-3 mx-auto rounded-xl py-2 font-semibold">Register</button>
            </form>

            <!-- Login Link -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Already have an account? <a href="login.php" class="text-green-500 hover:text-green-700">Login</a></p>
            </div>
        </div>
    </div>
</body>

</html>