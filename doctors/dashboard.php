<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
}

include '../components/sideNav.php';
include '../api/db.php';

$email = $_SESSION['email'];

// Fetch doctor's data
$doctorsData = "SELECT * FROM doctors WHERE email = '$email'";
$resultS = mysqli_query($con, $doctorsData);

if ($resultS) {
    $doctor = mysqli_fetch_assoc($resultS);
}

// Fetch the user's email from the session
$email = $_SESSION['email'];
$sql = "SELECT * FROM appointments WHERE doctor_id = $doctor[id]";
$result = mysqli_query($con, $sql);

$appointments = [];
$sql = "SELECT a.id, p.name, p.phone, a.appointment_date, a.appointment_time, a.status 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.id 
        WHERE a.doctor_id = '$doctor[id]' AND a.status = 'confirmed'";

$resultA = mysqli_query($con, $sql);

if ($resultA) {
    while ($row = mysqli_fetch_assoc($resultA)) {
        $appointments[] = $row;
    }
}
// echo json_encode($appointments);
mysqli_close($con);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <main class="flex w-full h-screen bg-gray-100">
        <?php sidenav(); ?>

        <section class="flex-1 p-6 overflow-auto">
            <!-- Header -->
            <header class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Welcome, Doctor!</h1>
                <p class="text-gray-600">Here's an overview of your day.</p>
            </header>

            <!-- Dashboard Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Card: Upcoming Appointments -->
                <div class="col-span-1 bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Upcoming Appointments</h2>
                    <ul class="space-y-4">
                        <?php foreach ($appointments as $appointment) : ?>
                            <li class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-800 font-semibold"><?= $appointment['name'] ?></p>
                                    <p class="text-gray-600 text-sm"><?= $appointment['appointment_date'] ?>, <?= $appointment['appointment_time'] ?></p>
                                    
                                    <div class="flex items-center gap-2">
                                        <button class="text-indigo-600 hover:text-indigo-800">
                                            
                                        <?php if ($appointment['status'] === 'pending') :?>
                                            <i class="ri-arrow-right-line text-lg"></i>
                                            
                                        <?php elseif ($appointment['status'] === 'cancelled') :?>
                                            
                                        <?php else :?>
                                            
                                        <?php endif;?>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach;?>

                       
                        <li class="text-center text-indigo-600 hover:text-indigo-800">
                            <a href="./my_records.php">View All</a>
                        </li>
                    </ul>
                </div>

                <!-- Card: Patient Statistics -->
                <div class="col-span-1 bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Patient Statistics</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-indigo-100 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-indigo-600">24</p>
                            <p class="text-gray-600 text-sm">Today's Patients</p>
                        </div>
                        <div class="bg-indigo-100 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-indigo-600">120</p>
                            <p class="text-gray-600 text-sm">This Week</p>
                        </div>
                        <div class="bg-indigo-100 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-indigo-600">520</p>
                            <p class="text-gray-600 text-sm">This Month</p>
                        </div>
                        <div class="bg-indigo-100 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-indigo-600">5000+</p>
                            <p class="text-gray-600 text-sm">Total Patients</p>
                        </div>
                    </div>
                </div>

                <!-- Card: Quick Actions -->
                <div class="col-span-1 bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h2>
                    <ul class="space-y-4">
                        <li>
                            <a href="./schedule.php" class="flex items-center text-gray-800 hover:text-indigo-600">
                                <i class="ri-calendar-check-line text-lg mr-3"></i>
                                Schedule an Appointment
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center text-gray-800 hover:text-indigo-600">
                                <i class="ri-folder-user-line text-lg mr-3"></i>
                                View Patient Records
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center text-gray-800 hover:text-indigo-600">
                                <i class="ri-file-list-line text-lg mr-3"></i>
                                Manage Prescriptions
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center text-gray-800 hover:text-indigo-600">
                                <i class="ri-bar-chart-line text-lg mr-3"></i>
                                View Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
