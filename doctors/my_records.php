<?php
    include '../components/sideNav.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex ">
    <?php sidenav(); ?>
    <div class="w-2/3 px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">My Appointments</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            <table class="w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-300 px-4 py-2 text-left">Appointment ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Patient Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Contact</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($appointments)) : ?>
                        <?php foreach ($appointments as $appointment) : ?>
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['appointment_id']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['patient_name']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['patient_contact']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['appointment_date']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['appointment_time']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
