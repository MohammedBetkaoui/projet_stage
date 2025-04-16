<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['role'] ?? 'guest';

$user_id = $_SESSION['user_id'] ?? null; // Get user_id from session

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Stage</title>
    <link rel="stylesheet" href="/includes/navbar/navbar.css"> 
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Boxicons pour les icônes -->

    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script> <!-- Socket.IO Library -->

</head>
<body>
    <nav>
        <div class="logo">
            <h1>StageFinder</h1>
        </div>

        <!-- Menu desktop -->
        <ul class="desktop-menu">
            <li><a href="/home/index.php"><i class='bx bx-home'></i> Accueil</a></li>
            <li><a href="/home/propos/propos.php"><i class='bx bx-info-circle'></i> À propos</a></li>
            <li><a href="/home/offres.php"><i class='bx bx-info-circle'></i> Offres</a></li>

            <?php if ($role === 'student'): ?>
                <li><a href="../../studant/home_dashboard/student_dashboard.php"><i class='bx bx-user'></i> Espace Étudiant</a></li>
            <?php elseif ($role === 'company'): ?>
                <li><a href="../../company/company_dashboard.php"><i class='bx bx-briefcase'></i> Espace Entreprise</a></li>
            <?php elseif ($role === 'admin'): ?>
                <li><a href="../../admin/admin_dashboard.php"><i class='bx bx-shield'></i> Espace Admin</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>


                <!-- Notification Icon -->
                <li>
                    <a href="#" id="notificationLink">
                        <i class='bx bx-bell'></i>
                        <span id="notificationBadge" class="badge" style="display: none;">0</span>
                    </a>
                    <ul id="notificationDropdown" class="notification-menu" style="display: none;"></ul>
                </li>

                <li><a href="../../auth/logout/logout.php"><i class='bx bx-log-out'></i> Déconnexion</a></li>
            <?php else: ?>
                <li><a href="../../auth/login/login.php"><i class='bx bx-log-in'></i> Connexion</a></li>
            <?php endif; ?>
        </ul>

        <!-- Bouton hamburger pour mobile -->
        <div class="hamburger" onclick="toggleMenu()">
            <span class="line">|</span>
            <span class="line">|</span>
            <span class="line">|</span>
        </div>

        <!-- Menu mobile -->
        <div class="menubar" id="mobileMenu">
            <ul>
                <li><a href="/home/index.php"><i class='bx bx-home'></i> Accueil</a></li>
                <li><a href="/home/propos/propos.php"><i class='bx bx-info-circle'></i> À propos</a></li>
                <li><a href="/home/offres.php"><i class='bx bx-info-circle'></i> Offres</a></li>

                <?php if ($role === 'student'): ?>
                    <li><a href="../../studant/home_dashboard/student_dashboard.php"><i class='bx bx-user'></i> Espace Étudiant</a></li>
                <?php elseif ($role === 'company'): ?>
                    <li><a href="../../company/company_dashboard.php"><i class='bx bx-briefcase'></i> Espace Entreprise</a></li>
                <?php elseif ($role === 'admin'): ?>
                    <li><a href="../../admin/admin_dashboard.php"><i class='bx bx-shield'></i> Espace Admin</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>


                    <!-- Notification Icon for Mobile -->
                    <li>
                        <a href="#" id="notificationLinkMobile">
                            <i class='bx bx-bell'></i>
                            <span id="notificationBadgeMobile" class="badge" style="display: none;">0</span>
                        </a>
                        <ul id="notificationDropdownMobile" class="notification-menu" style="display: none;"></ul>
                    </li>

                    <li><a href="../../auth/logout/logout.php"><i class='bx bx-log-out'></i> Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="../../auth/login/login.php"><i class='bx bx-log-in'></i> Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Overlay pour mobile -->
        <div class="overlay" id="overlay" onclick="toggleMenu()"></div>
    </nav>

    <script src="/includes/navbar/navbar.js"></script> <!-- Script pour le toggle -->
    <script src="/includes/navbar/navbar.js"></script>
    <script>
    // Get user_id from PHP session
    const userId = <?php echo json_encode($user_id); ?>;

    // Connect to the Socket.IO server
    const socket = io('http://localhost:5000', {
        transports: ['websocket'],
    });

    // Emit the user_id to the backend when connected
    socket.on('connect', () => {
        console.log('Connected to the server with socket ID:', socket.id);
        if (userId) {
            // Emit the 'join_user_room' event to join the user-specific room
            socket.emit('join_user_room', userId);
        }
    });

    // Listen for the 'new_offer' event
    socket.on('new_offer', (data) => {
        console.log('New offer notification:', data.message);

        // Update the notification badge
        const badge = document.getElementById('notificationBadge');
        const badgeMobile = document.getElementById('notificationBadgeMobile');
        const count = parseInt(badge.textContent || 0) + 1;
        badge.textContent = count;
        badgeMobile.textContent = count;
        badge.style.display = 'inline';
        badgeMobile.style.display = 'inline';

        // Add the new notification to the dropdown
        const notificationItem = document.createElement('li');
        notificationItem.textContent = data.message;

        const dropdown = document.getElementById('notificationDropdown');
        const dropdownMobile = document.getElementById('notificationDropdownMobile');
        dropdown.appendChild(notificationItem);
        dropdownMobile.appendChild(notificationItem.cloneNode(true));

        // Show the dropdown if hidden
        dropdown.style.display = 'block';
        dropdownMobile.style.display = 'block';
    });

    // Toggle notification dropdown (desktop)
    document.getElementById('notificationLink').addEventListener('click', (e) => {
        e.preventDefault();
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    });

    // Toggle notification dropdown (mobile)
    document.getElementById('notificationLinkMobile').addEventListener('click', (e) => {
        e.preventDefault();
        const dropdown = document.getElementById('notificationDropdownMobile');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    });
</script>
</body>
</html>