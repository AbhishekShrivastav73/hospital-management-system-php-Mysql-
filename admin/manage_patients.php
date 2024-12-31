<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location:../login.php");
    exit();
}

include '../components/sideNav.php';
include '../api/db.php';

$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = mysqli_real_escape_string($con, $_GET['search']);
}

$sql = "SELECT p.id, p.name as username, p.email, p.phone, a.appointment_date, a.appointment_time, d.name as doctor_name, a.status 
        FROM patients AS p 
        JOIN appointments AS a ON p.id = a.patient_id 
        JOIN doctors AS d ON a.doctor_id = d.id";

if ($searchQuery !== '') {
    $sql .= " WHERE p.name LIKE '%$searchQuery%' OR d.name LIKE '%$searchQuery%'";
}

$result = mysqli_query($con, $sql);

$patients = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $patients[] = $row;
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <main class="w-full flex">
        <?php sidenav(); ?>
        <section class="flex-1 overflow-auto h-screen p-6 bg-gray-100">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Manage Patients</h1>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-700">Patient Appointments</h2>
                    <form action="" method="GET" class="flex items-center space-x-3">
                        
                        <input  type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search by Patient or Doctor's name " class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-300 hover:text-gray-800">
                            Search
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Phone</th>
                                <th class="px-4 py-3">Appointment Date</th>
                                <th class="px-4 py-3">Time</th>
                                <th class="px-4 py-3">Doctor</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($patients)) : ?>
                                <?php foreach ($patients as $patient) : ?>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-3"><?php echo $patient['id']; ?></td>
                                        <td class="px-4 py-3"><?php echo $patient['username']; ?></td>
                                        <td class="px-4 py-3"><?php echo $patient['email']; ?></td>
                                        <td class="px-4 py-3"><?php echo $patient['phone']; ?></td>
                                        <td class="px-4 py-3"><?php echo $patient['appointment_date']; ?></td>
                                        <td class="px-4 py-3"><?php echo $patient['appointment_time']; ?></td>
                                        <td class="px-4 py-3"><?php echo $patient['doctor_name']; ?></td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 rounded-full text-white 
                                                <?php echo $patient['status'] === 'confirmed' ? 'bg-green-500' : ($patient['status'] === 'pending' ? 'bg-yellow-500' : 'bg-red-500'); ?>">
                                                <?php echo $patient['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
