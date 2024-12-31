<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../login.php");
    exit();
};


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
    <title>Doctor Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #edf2f7, #e2e8f0);
        }
    </style>
</head>

<body class="flex min-h-screen">
    <?php sidenav(); ?>
    <div class="flex-1 px-8 py-6">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-8">My Appointments</h1>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-700 mb-6">Confirmed Appointments</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-sm font-semibold uppercase">
                            <th class="border-b-2 border-gray-200 px-6 py-4">Appointment ID</th>
                            <th class="border-b-2 border-gray-200 px-6 py-4">Patient Name</th>
                            <th class="border-b-2 border-gray-200 px-6 py-4">Contact</th>
                            <th class="border-b-2 border-gray-200 px-6 py-4">Date</th>
                            <th class="border-b-2 border-gray-200 px-6 py-4">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($appointments)) : ?>
                            <?php foreach ($appointments as $appointment) : ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-600"><?php echo $appointment['id']; ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo $appointment['name']; ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo $appointment['phone']; ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo $appointment['appointment_date']; ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo $appointment['appointment_time']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No appointments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
