<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Include the database connection

// Check if the edit_id is provided in the URL
if (!isset($_GET['edit_id']) || !is_numeric($_GET['edit_id'])) {
    header('Location: /index.php'); // Redirect if no valid ID is provided
    exit();
}

$offerId = (int)$_GET['edit_id'];

// Fetch the offer details from the database
$stmt = $conn->prepare("
    SELECT o.id, o.title, o.description, o.sector, o.location, o.start_date, o.end_date, o.deadline, o.compensation
    FROM offers o
    WHERE o.id = ?
");
$stmt->bind_param("i", $offerId);
$stmt->execute();
$result = $stmt->get_result();
$offer = $result->fetch_assoc();

if (!$offer) {
    header('Location: /index.php'); // Redirect if the offer doesn't exist
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $sector = htmlspecialchars($_POST['sector']);
    $location = htmlspecialchars($_POST['location']);
    $startDate = htmlspecialchars($_POST['start_date']);
    $endDate = htmlspecialchars($_POST['end_date']);
    $deadline = htmlspecialchars($_POST['deadline']);
    $compensation = htmlspecialchars($_POST['compensation']);

    // Update the offer in the database
    try {
        $stmt = $conn->prepare("
            UPDATE offers
            SET title = ?, description = ?, sector = ?, location = ?, start_date = ?, end_date = ?, deadline = ?, compensation = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssssssssi", $title, $description, $sector, $location, $startDate, $endDate, $deadline, $compensation, $offerId);
        $stmt->execute();

        // Set a success message in the session
        $_SESSION['success_message'] = "L'offre a été mise à jour avec succès.";
        header("Location: editoffer.php?edit_id=$offerId"); // Redirect back to the update page
        exit();
    } catch (Exception $e) {
        $error = "Erreur lors de la mise à jour de l'offre: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une offre - Plateforme de stages</title>
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
   
</head>
<body class="bg-gray-50 font-poppins">
    <!-- Sidebar -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/sidebar/sidebar.php'; ?>

    <!-- Update Offer Form -->
    <section class="update-offer-section p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Modifier une offre</h2>

        <!-- Display success message if set -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 max-w-lg mx-auto">
                <?php echo $_SESSION['success_message']; ?>
                <?php unset($_SESSION['success_message']); // Clear the message after displaying ?>
            </div>
        <?php endif; ?>

        <!-- Display error message if set -->
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 max-w-lg mx-auto">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="form-container mx-auto bg-white p-8 rounded-lg shadow-md">
            <!-- Step 1: Title, Description, Sector -->
            <div id="step1">
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-gray-700 font-semibold mb-2">Titre de l'offre</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($offer['title']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required><?php echo htmlspecialchars($offer['description']); ?></textarea>
                </div>

                <!-- Sector -->
                <div class="mb-6">
                    <label for="sector" class="block text-gray-700 font-semibold mb-2">Secteur</label>
                    <input type="text" id="sector" name="sector" value="<?php echo htmlspecialchars($offer['sector']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Next Button -->
                <div class="flex justify-end">
                    <button type="button" onclick="showStep2()" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Suivant</button>
                </div>
            </div>

            <!-- Step 2: Remaining Fields -->
            <div id="step2">
                <!-- Location -->
                <div class="mb-6">
                    <label for="location" class="block text-gray-700 font-semibold mb-2">Lieu</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($offer['location']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Start Date and End Date -->
                <div class="mb-6 date-inputs">
                    <div>
                        <label for="start_date" class="block text-gray-700 font-semibold mb-2">Date de début</label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($offer['start_date']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-gray-700 font-semibold mb-2">Date de fin</label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($offer['end_date']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <!-- Deadline -->
                <div class="mb-6">
                    <label for="deadline" class="block text-gray-700 font-semibold mb-2">Dernier délai</label>
                    <input type="date" id="deadline" name="deadline" value="<?php echo htmlspecialchars($offer['deadline']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Compensation -->
                <div class="mb-6">
                    <label for="compensation" class="block text-gray-700 font-semibold mb-2">Gratification (Dz/mois)</label>
                    <input type="number" id="compensation" name="compensation" value="<?php echo htmlspecialchars($offer['compensation']); ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Return and Submit Buttons -->
                <div class="flex justify-between">
                    <button type="button" onclick="showStep1()" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-300">Retour</button>
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Mettre à jour</button>
                </div>
            </div>
        </form>
    </section>

    <script src="offer.js"></script>
</body>
</html>