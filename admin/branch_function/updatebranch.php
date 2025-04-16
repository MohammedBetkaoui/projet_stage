<?php
// Start the session
session_start();

// Redirect if the user is not an admin before including sidebar
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login/login.php");
    exit;
}

// Include sidebar AFTER authentication check
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/sidebar/sidebar.php';

// Include database connection
require_once '../../includes/db/db.php';

// Get branch ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid branch ID!";
    header("Location: ajoutebranch.php");
    exit;
}

$branch_id = intval($_GET['id']);

// Fetch branch details
$query = "SELECT * FROM branch WHERE id = $branch_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    $_SESSION['error_message'] = "Branch not found!";
    header("Location: ajoutebranch.php");
    exit;
}

$branch = mysqli_fetch_assoc($result);

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['branch'])) {
    $new_branch_name = mysqli_real_escape_string($conn, $_POST['branch']);

    // Check if the branch name already exists
    $checkQuery = "SELECT * FROM branch WHERE name = '$new_branch_name' AND id != $branch_id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $_SESSION['error_message'] = "Error: Branch name already exists!";
    } else {
        $updateQuery = "UPDATE branch SET name = '$new_branch_name' WHERE id = $branch_id";
        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION['success_message'] = "Branch updated successfully!";
            header("Location: ajoutebranch.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Error updating branch: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Branch</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold text-center mb-6">Update Branch</h1>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form method="POST" action="">
                <label for="branch" class="block text-gray-700">Branch Name:</label>
                <input type="text" name="branch" id="branch" value="<?php echo htmlspecialchars($branch['name']); ?>" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Update Branch
                    </button>
                    <a href="ajoutebranch.php" class="ml-2 text-gray-600 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
