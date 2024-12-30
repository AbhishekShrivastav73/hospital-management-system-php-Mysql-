<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to display the side navigation with user role and logout option
function sidenav()
{
    // Check if the session has the user's role (assuming it's stored in 'role' and 'username')
    if (isset($_SESSION['role']) && isset($_SESSION['username'])) {
        $role = $_SESSION['role'];
        $username = $_SESSION['username'];

        // Sidebar container
        echo '<div class="w-64 h-screen bg-green-400 text-white p-4 flex flex-col">
                    <div class="mb-8 text-center flex items-center flex-col justify-center">
                     <img class="w-1/4 mix-blend-multiply" src="https://imgs.search.brave.com/ji-z4N5G57kbJ6mfMOHiOREmoDYOI1sAFP-w3Ou9yHU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90NC5m/dGNkbi5uZXQvanBn/LzA4LzA2LzI0Lzg5/LzM2MF9GXzgwNjI0/ODk0OF84Q2FtajRp/S3JSQnlyS1NlNm5k/V21NRWxDN1RJTEhs/MS5qcGc" alt="">
                        <h2 class="text-xl font-bold">Hospital Management System</h2>
                        <p class="text-sm mt-2">Logged in as: <strong>' . $username . '</strong></p>
                        <p class="text-xs text-zinc-900">Role: ' . ucfirst($role) . '</p>
                    </div>';

        // Display different menus based on the user's role
        if ($role === 'admin') {
            // Admin menu
            echo '<ul class="space-y-4 font-semibold">
                        <li><a href="#" class="flex items-center space-x-2 text-lg"><i class="ri-dashboard-line"></i><span>Dashboard</span></a></li>
                        <li><a href="../admin/add_doctor.php" class="flex items-center space-x-2 text-lg"><i class="ri-user-add-line"></i><span>Add Doctor</span></a></li>
                        <li><a href="#" class="flex items-center space-x-2 text-lg"><i class="ri-hospital-line"></i><span>Manage Patients</span></a></li>
                        <li><a href="#" class="flex items-center space-x-2 text-lg"><i class="ri-medical-line"></i><span>Manage Appointments</span></a></li>
                        <li><a href="#" class="flex items-center space-x-2 text-lg"><i class="ri-settings-5-line"></i><span>Settings</span></a></li>
                    </ul>';
        } elseif ($role === 'doctor') {
            // Doctor menu
            echo '<ul class="space-y-4">
                        <li><a href="../doctors/dashboard.php" class="flex items-center space-x-2 text-lg"><i class="ri-dashboard-line"></i><span>Dashboard</span></a></li>
                        <li><a href="../doctors/my_records.php" class="flex items-center space-x-2 text-lg"><i class="ri-file-list-line"></i><span>My Appointments</span></a></li>
                        <li><a href="#" class="flex items-center space-x-2 text-lg"><i class="ri-file-text-line"></i><span>Medical Records</span></a></li>
                        <li><a href="../doctors/schedule.php" class="flex items-center space-x-2 text-lg"><i class="ri-calendar-line"></i><span>Schedule</span></a></li>
                    </ul>';
        } else {
            // User menu
            echo '<ul class="space-y-4">
                        <li><a href="../users/dashboard.php" class="flex items-center space-x-2 text-lg"><i class="ri-dashboard-line"></i><span>Dashboard</span></a></li>
                        <li><a href="../users/book_appointment.php" class="flex items-center space-x-2 text-lg"><i class="ri-heart-line"></i><span>Book Appointment</span></a></li>
                        <li><a href="../users/appointment_history.php" class="flex items-center space-x-2 text-lg"><i class="ri-history-line"></i><span>Appointment History</span></a></li>
                        <li><a href="#" class="flex items-center space-x-2 text-lg"><i class="ri-settings-3-line"></i><span>Settings</span></a></li>
                    </ul>';
        }

        // Logout Form
        echo '<div class="mt-auto text-center">
                    <form method="POST">
                        <button type="submit" name="logout" class="flex items-center justify-center space-x-2 text-lg mt-4 px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg">
                            <i class="ri-logout-box-line"></i><span>Logout</span>
                        </button>
                    </form>
                  </div>';

        // Closing the sidenav div
        echo '</div>';
    }
}

// Handle logout functionality
if (isset($_POST['logout'])) {
    // Destroy session and redirect to login
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}

?>
