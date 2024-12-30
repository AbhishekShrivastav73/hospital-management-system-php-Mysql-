<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}
include '../components/sideNav.php';
include '../api/db.php';


$sql = "SELECT * FROM appointments JOIN patient WHERE patient_id = id"

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <main class="flex w-full h-screen bg-gray-100">
        <?php sidenav(); ?>
        <section class="flex-1 h-screen p-6">
        </section>
    </main>
</body>

</html>