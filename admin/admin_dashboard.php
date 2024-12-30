<?php 
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
}

include '../components/sideNav.php';
include '../api/db.php';

$sql  = "SELECT COUNT(*) FROM doctors ";

$result = mysqli_query($con, $sql);

if ($result) {
    $totalDoctors = mysqli_fetch_array($result)[0];
} else {
    $totalDoctors = 0;
}

$sql  = "SELECT COUNT(*) FROM patients ";

$result = mysqli_query($con, $sql);

if ($result) {
    $totalPatients = mysqli_fetch_array($result)[0];
} else {
    $totalPatients = 0;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <main class="flex w-full h-screen">
        <?php sidenav(); ?>
        
        <section class="flex-1 p-6 bg-gray-100 overflow-y-auto">
            <h1 class="text-2xl font-bold mb-4">Welcome, Admin</h1>

            <!-- Metrics Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-white shadow-md rounded-lg flex items-center">
                    <i class="ri-user-2-line text-blue-500 text-3xl mr-4"></i>
                    <div>
                        <p class="text-gray-500">Total Doctors</p>
                        <h2 class="text-xl font-bold"><?php echo $totalDoctors; ?></h2>
                    </div>
                </div>
                <div class="p-4 bg-white shadow-md rounded-lg flex items-center">
                    <i class="ri-heart-line text-red-500 text-3xl mr-4"></i>
                    <div>
                        <p class="text-gray-500">Total Patients</p>
                        <h2 class="text-xl font-bold"><?php echo $totalPatients; ?></h2>
                    </div>
                </div>
                <div class="p-4 bg-white shadow-md rounded-lg flex items-center">
                    <i class="ri-calendar-check-line text-green-500 text-3xl mr-4"></i>
                    <div>
                        <p class="text-gray-500">Total Appointments</p>
                        <h2 class="text-xl font-bold">300</h2>
                    </div>
                </div>
            </div>

            <!-- Actionable Links -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <a href="./add_doctor.php" class="p-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md text-center">
                    <i class="ri-user-add-line text-2xl mb-2"></i>
                    <p>Add Doctor</p>
                </a>
                <a href="managePatients.php" class="p-4 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-md text-center">
                    <i class="ri-hospital-line text-2xl mb-2"></i>
                    <p>Manage Patients</p>
                </a>
                <a href="manageAppointments.php" class="p-4 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow-md text-center">
                    <i class="ri-calendar-line text-2xl mb-2"></i>
                    <p>Manage Appointments</p>
                </a>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white p-6 shadow-md rounded-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Recent Activity</h2>
                <ul class="space-y-3">
                    <li class="flex justify-between text-gray-700">
                        <span>Dr. John Doe added as Cardiologist</span>
                        <span class="text-sm text-gray-500">2 hours ago</span>
                    </li>
                    <li class="flex justify-between text-gray-700">
                        <span>Appointment booked by Jane Smith</span>
                        <span class="text-sm text-gray-500">4 hours ago</span>
                    </li>
                </ul>
            </div>

            <!-- System Notifications -->
            <div class="bg-white p-6 shadow-md rounded-lg">
                <h2 class="text-xl font-bold mb-4">Notifications</h2>
                <p class="text-gray-600">No new notifications</p>
            </div>
        </section>
    </main>
</body>

</html>
