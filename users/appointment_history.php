<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

include '../api/db.php';
include '../components/sideNav.php';

// Fetch the user's email from the session
$email = $_SESSION['email'];

// Fetch the patient's appointments
$sqlAppointments = "SELECT a.id, d.name AS doctor_name, d.specialization, a.appointment_date, a.appointment_time, a.status 
                    FROM appointments a
                    JOIN patients p ON a.patient_id = p.id
                    JOIN doctors d ON a.doctor_id = d.id
                    WHERE p.email = ?
                    ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$stmt = mysqli_prepare($con, $sqlAppointments);
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$appointments = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }
    mysqli_free_result($result);
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
<main class="flex w-full h-screen bg-gray-100">
    <?php sidenav(); ?>

    <section class="flex-1 h-screen overflow-auto p-6">
        <header class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Appointment History</h1>
        </header>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <?php if (count($appointments) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Specialization</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($appointment['specialization']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-900 capitalize"><?php echo htmlspecialchars($appointment['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500">You have no appointment history.</p>
            <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>
