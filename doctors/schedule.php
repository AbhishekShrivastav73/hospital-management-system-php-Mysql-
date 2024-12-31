<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../php/src/PHPMailer.php';
require '../php/src/SMTP.php';
require '../php/src/Exception.php';

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

// Handle submit request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $action = strtolower($_POST['action']);
    $status = ($action === 'accept') ? 'Confirmed' : 'Cancelled';
    $sql = "UPDATE appointments SET status = '$status' WHERE id = $id";

    if (mysqli_query($con, $sql)) {
        // Fetch patient details for email notification
        $patientQuery = "SELECT p.email, p.name, a.appointment_date, a.appointment_time 
                         FROM appointments a 
                         JOIN patients p ON a.patient_id = p.id 
                         WHERE a.id = $id";
        $patientResult = mysqli_query($con, $patientQuery);

        if ($patientResult && $status === 'Confirmed') {
            $patient = mysqli_fetch_assoc($patientResult);

            // Send email notification
            $mail = new PHPMailer(true);

            try {
                $mail->SMTPDebug = 0;    
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP host
                $mail->SMTPAuth = true;
                $mail->Username = 'abhisheks.infoseek@gmail.com'; // Replace with your email
                $mail->Password = 'grhyqzhmkwfsdimg'; // Replace with your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email Settings
                $mail->setFrom('abhisheks.infoseek@gmail.com', 'Hospital Management System'); // Replace with your sender email and name
                $mail->addAddress($patient['email'], $patient['name']);
                $mail->isHTML(true);
                $mail->Subject = 'Appointment Confirmation';
                $mail->Body = "
                    <h2>Appointment Confirmed</h2>
                    <p>Dear {$patient['name']},</p>
                    <p>Your appointment has been confirmed.</p>
                    <p><strong>Date:</strong> {$patient['appointment_date']}<br>
                    <strong>Time:</strong> {$patient['appointment_time']}</p>
                    <p>Thank you for choosing our services.</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                // Handle email sending errors
                error_log("Email sending failed: {$mail->ErrorInfo}");
            }
        }

        header("Location: ./dashboard.php");
        exit();
    }
}

// Fetch patients' appointments
$appointments = [];
$sql = "SELECT a.id, p.name, p.phone, p.email, a.appointment_date, a.appointment_time, a.status 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.id 
        WHERE a.doctor_id = '$doctor[id]' AND a.status = 'Pending'";

$resultA = mysqli_query($con, $sql);

if ($resultA) {
    while ($row = mysqli_fetch_assoc($resultA)) {
        $appointments[] = $row;
    }
}

// Close database connection
mysqli_close($con);
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
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Pending Appointments</h1>
            <div class="bg-white shadow-md rounded-lg p-6">
                <table class="w-full table-auto border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="border border-gray-300 px-4 py-2 text-left">Appointment ID</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Patient Name</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Contact</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Time</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($appointments)) : ?>
                            <?php foreach ($appointments as $appointment) : ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['id']; ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['name']; ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['phone']; ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['appointment_date']; ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo $appointment['appointment_time']; ?></td>
                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                        <form method="POST" class="inline-block">
                                            <input type="hidden" name="id" value="<?php echo $appointment['id']; ?>">
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                                                Accept
                                            </button>
                                        </form>
                                        <form method="POST" class="inline-block">
                                            <input type="hidden" name="id" value="<?php echo $appointment['id']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-4">No pending appointments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>
