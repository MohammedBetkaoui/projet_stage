<?php 
// Start output buffering
ob_start();

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if the user is not an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login/login.php");
    exit;
}

// Include necessary files
require_once '../../includes/db/db.php'; // Include the database connection
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/sidebar/sidebar.php'; 


$users = [];
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
if ($result) {
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Utilisateurs</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS for the table margin -->
    <style>
        .table-container {
            margin-left: 3cm;
        }
        @media (max-width: 768px) {
            .table-container {
                margin-left: 0;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="table-container relative overflow-x-auto shadow-md sm:rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold mb-4">AJoute nv Utilisateurs</h3>
            <a href="/auth/register/register_form.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Ajoute_Utilisateur
            </a>
        </div>
        <div class="table-responsive">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Username</th>
                        <th scope="col" class="px-6 py-3">full_name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">phone</th>
                        <th scope="col" class="px-6 py-3">adress</th>
                        <th scope="col" class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo htmlspecialchars($user['id']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($user['address']); ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="editeuser.php?id=<?php echo $user['id']; ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                <a href="suppuser.php?id=<?php echo $user['id']; ?>" class="font-medium text-red-600 dark:text-red-500 hover:underline ml-2" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>