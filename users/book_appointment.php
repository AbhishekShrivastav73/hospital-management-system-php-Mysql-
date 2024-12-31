<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'patient') {
    header("Location: ../login.php");
    exit();
}

include '../api/db.php';
include '../components/sideNav.php';

// Fetch all doctors and their specializations
$sqlDoctors = "SELECT * FROM doctors";
$resultDoctors = mysqli_query($con, $sqlDoctors);

$specializations = [];
if ($resultDoctors) {
    while ($row = mysqli_fetch_assoc($resultDoctors)) {
        $specialization = $row['specialization'];
        if (!isset($specializations[$specialization])) {
            $specializations[$specialization] = [];
        }
        $specializations[$specialization][] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'availability' => $row['availability'],
        ];
    }
    mysqli_free_result($resultDoctors);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $specialization = mysqli_real_escape_string($con, $_POST['specialization']);
    $doctorId = mysqli_real_escape_string($con, $_POST['doctor']);
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $time = mysqli_real_escape_string($con, $_POST['time']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $medical_history = mysqli_real_escape_string($con, $_POST['medical_history']);
    $email = mysqli_real_escape_string($con, $_SESSION['email']);

    // Insert patient details
    $createPatient = "INSERT INTO patients (name, age, gender, address, phone, email, medical_history) 
                      VALUES ('$name', '$age', '$gender', '$address', '$phone', '$email', '$medical_history')";
    if (mysqli_query($con, $createPatient)) {
        $patientId = mysqli_insert_id($con);

        // Book appointment
        $bookAppointment = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) 
                            VALUES ($patientId, '$doctorId', '$date', '$time', 'pending')";
        if (mysqli_query($con, $bookAppointment)) {
            echo "<script>alert('Appointment booked successfully!');</script>";
        } else {
            echo "<script>alert('Failed to book appointment.');</script>";
        }
    } else {
        echo "<script>alert('Failed to save patient details.');</script>";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Hospital Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const specializations = <?php echo json_encode($specializations); ?>;

            const specializationDropdown = document.getElementById('specialization');
            const doctorDropdown = document.getElementById('doctor');
            const available = document.querySelector('.condition');

            specializationDropdown.addEventListener('change', function () {
                const selectedSpecialization = this.value;
                // available.textContent =  specializations[selectedSpecialization].availability;

                // Clear the doctor dropdown
                doctorDropdown.innerHTML = '<option value="">Select a Doctor</option>';

                if (selectedSpecialization && specializations[selectedSpecialization]) {
                    // Populate the doctor dropdown with matching doctors
                    specializations[selectedSpecialization].forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.id;
                        option.textContent = `${doctor.name} - ${doctor.availability}`;
                        doctorDropdown.appendChild(option);
                        // available.textContent = `Appointment available for ${doctor.availability} - Rest will be considered as cancelled`;
                    });
                }
            });
        });
    </script>
</head>

<body>
<main class="flex w-full h-screen bg-gray-100">
        <?php sidenav(); ?>

        <section class="flex-1 h-screen overflow-auto p-6">
            <header class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Book an Appointment</h1>
            </header>

            <form method="POST" class="bg-white p-6 rounded-lg shadow-md space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                        <select id="specialization" name="specialization" required
                            class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Specialization</option>
                            <?php foreach ($specializations as $key => $value) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="doctor" class="block text-sm font-medium text-gray-700">Doctor</label>
                        <select id="doctor" name="doctor" required
                            class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select a Doctor</option>
                        </select>
                    </div>
                </div>
                <p class="condition text-orange-400"></p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Appointment Date</label>
                        <input type="date" id="date" name="date" required
                            class="mt-1 block w-full px-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="time" class="block text-sm font-medium text-gray-700">Appointment Time</label>
                        <input type="time" id="time" name="time" required
                            class="mt-1 block w-full px-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" name="name" required
                            class="mt-1 px-4 py-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                        <input type="number" id="age" name="age" required
                            class="mt-1 block px-4 py-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                        <select id="gender" name="gender" required
                            class="mt-1 block px-4 py-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" id="phone" name="phone" required
                            class="mt-1 block px-4 py-2 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea id="address" name="address" rows="3" required
                        class="mt-1 block w-full px-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label for="medical_history" class="block text-sm font-medium text-gray-700">Medical History</label>
                    <textarea id="medical_history" name="medical_history" rows="3"
                        class="mt-1 block w-full px-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-600">
                        Submit Appointment
                    </button>
                </div>
            </form>
        </section>

    </main>
</body>

</html>
