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
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/sidebar/sidebar.php';
require_once '../../includes/db/db.php'; // Include the database connection

// Fetch all branches from the database
$branches = [];
if ($_SESSION['role'] === 'admin') {
    $branchQuery = "SELECT * FROM branch";
    $branchResult = mysqli_query($conn, $branchQuery);
    $branches = mysqli_fetch_all($branchResult, MYSQLI_ASSOC);
}

// Handle branch deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    if ($_SESSION['role'] === 'admin') {
        $deleteQuery = "DELETE FROM branch WHERE id = $delete_id";
        if (mysqli_query($conn, $deleteQuery)) {
            $_SESSION['success_message'] = "Branch deleted successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $_SESSION['error_message'] = "Error deleting branch: " . mysqli_error($conn);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Handle form submission for adding a new branch
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['branch'])) {
    $name_branch = mysqli_real_escape_string($conn, $_POST['branch']);

    if ($_SESSION['role'] === 'admin') {
        // Check if the branch name already exists
        $checkQuery = "SELECT * FROM branch WHERE name = '$name_branch'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $_SESSION['error_message'] = "Error: Branch name already exists!";
        } else {
            $insertQuery = "INSERT INTO branch (name) VALUES ('$name_branch')";
            if (mysqli_query($conn, $insertQuery)) {
                $_SESSION['success_message'] = "Branch added successfully!";
            } else {
                $_SESSION['error_message'] = "Error adding branch: " . mysqli_error($conn);
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['error_message'] = "You do not have permission to add a branch.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// End output buffering and flush the buffer
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <!-- Success Message Alert -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="document.getElementById('successMessage').style.display='none';">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Error Message Alert -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="document.getElementById('errorMessage').style.display='none';">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <h1 class="text-2xl font-bold text-center mb-6">Branch Management</h1>

        <!-- Add Branch Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h3 class="text-lg font-semibold mb-4">AJoute nv Branch</h3>
            <form method="POST" action="">
                <div class="flex gap-4">
                    <input type="text" name="branch" id="branch" placeholder="Enter branch name" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Add Branch
                    </button>
                </div>
            </form>
        </div>

        <!-- Existing Branches List -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4">Existing Branches</h3>
            <?php if (!empty($branches)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Branch Name</th>
                                <th scope="col" class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($branches as $index => $branch): ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 <?php echo $index >= 5 ? 'hidden more-branch' : ''; ?>">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <?php echo htmlspecialchars($branch['name']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="updatebranch.php?id=<?php echo $branch['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-2">
                                            Edit
                                        </a>

                                        <a href="?delete_id=<?php echo $branch['id']; ?>" class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('Are you sure you want to delete this branch?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (count($branches) > 5): ?>
                    <button id="seeMoreBtn" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Plus de branches
                    </button>
                    <button id="backBtn" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" style="display: none;">
                        Back
                    </button>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-gray-600">No branches found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="ajoute.js"></script>
</body>

</html>