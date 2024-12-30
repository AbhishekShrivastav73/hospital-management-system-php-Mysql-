<?php
session_start();
if ($_SESSION['role'] != 'patient') {
    header("Location: ../login.php");
    exit();
}
include '../api/db.php';
include '../components/sideNav.php';

$username = $_SESSION['username'];
$email = $_SESSION['email'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <main class="flex w-full h-screen bg-gray-100">
        <?php sidenav(); ?>

        <section class="flex-1 p-6 space-y-6">
            <!-- Header -->
            <header class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Welcome, <?php echo $username; ?></h1>
                <a href="./book_appointment.php" 
                   class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600">
                    Book Appointment
                </a>
            </header>

            <!-- Cards Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Upcoming Appointments -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Upcoming Appointments</h2>
                    <ul class="space-y-3">
                        <li class="p-4 bg-gray-50 rounded-lg shadow">
                            <p><strong>Doctor:</strong> Dr. Smith</p>
                            <p><strong>Date:</strong> 25th December 2024</p>
                            <p><strong>Time:</strong> 10:30 AM</p>
                        </li>
                        <li class="p-4 bg-gray-50 rounded-lg shadow">
                            <p><strong>Doctor:</strong> Dr. Williams</p>
                            <p><strong>Date:</strong> 27th December 2024</p>
                            <p><strong>Time:</strong> 2:00 PM</p>
                        </li>
                    </ul>
                </div>

                <!-- Medical History -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Medical History</h2>
                    <ul class="space-y-3">
                        <li class="p-4 bg-gray-50 rounded-lg shadow">
                            <p><strong>Doctor:</strong> Dr. Smith</p>
                            <p><strong>Date:</strong> 10th November 2024</p>
                            <p><strong>Diagnosis:</strong> Flu</p>
                        </li>
                        <li class="p-4 bg-gray-50 rounded-lg shadow">
                            <p><strong>Doctor:</strong> Dr. Johnson</p>
                            <p><strong>Date:</strong> 15th October 2024</p>
                            <p><strong>Diagnosis:</strong> Back Pain</p>
                        </li>
                    </ul>
                </div>

                <!-- Profile Details -->
                <div class="bg-green-200 rounded-lg shadow-md p-4 h-fit">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Profile</h2>
                    <p><strong>Name:</strong> <?php echo $username; ?></p>
                    <p><strong>Email:</strong> <?php echo $email; ?></p>
                    <p><strong>Phone:</strong> +1234567890</p>
                    <p><strong>Age:</strong> 30</p>
                </div>
            </div>
        </section>
    </main>
</body>

</html>

