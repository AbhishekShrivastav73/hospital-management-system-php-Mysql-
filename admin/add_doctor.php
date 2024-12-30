<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
}
include '../components/sideNav.php';
include '../api/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $specialization = $_POST['specialization'];
    $fullname = $_POST['full_name'];
    $availability = $_POST['availability'];
    $contact_phone = $_POST['contact_phone'];
    $contact_email = $_POST['contact_email'];

    // SQL query to insert the doctor data
    $sqlUser = "INSERT INTO users (username, email, phone, role, password) VALUES ('$username', '$email', '$phone', 'doctor', '$password')";

    if (mysqli_query($con, $sqlUser)) {
        $userId = mysqli_insert_id($con);   
        $sqlDoctor = "INSERT INTO doctors (user_id, name, specialization, availability, phone, email        ) VALUES ($userId, '$fullname', '$specialization', '$availability', '$contact_phone', '$contact_email')";

        if (mysqli_query($con, $sqlDoctor)) {   
            echo "New doctor created successfully";
            header("Location: ./admin_dashboard.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <main class="flex w-full">
        <?php sidenav(); ?>
        <section class="flex-1 overflow-auto h-screen p-6 bg-gray-100">
            <h1 class="text-2xl font-bold mb-6">Add Doctor</h1>
            <form  method="POST" class="bg-white shadow-md rounded-lg p-6 space-y-6">
                <!-- Doctor Credentials -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Doctor Credentials</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" id="username" name="username" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" id="phone" name="phone" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <input type="text" id="role" name="role" value="doctor" readonly
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-200">
                        </div>
                    </div>
                </div>

                <!-- Doctor Details -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Doctor Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="full_name" name="full_name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>
                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                            <input type="text" id="specialization" name="specialization" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                            <input type="text" id="contact_phone" name="contact_phone" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                            <input type="email" id="contact_email" name="contact_email" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>
                        <div>
                            <label for="availability" class="block text-sm font-medium text-gray-700">Availability</label>
                            <input type="text" id="availability" name="availability" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-green-500 text-white py-2 px-6 rounded-md hover:bg-green-600 shadow-md">
                        Add Doctor
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>