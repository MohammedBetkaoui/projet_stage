<?php
<<<<<<< HEAD

// Rediriger si l'utilisateur n'est pas connecté
=======
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php';

// Redirect if the user is not logged in
>>>>>>> if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../../index.php');
    exit();
}

<<<<<<< HEAD

// Récupérer le rôle de l'utilisateur
$role = $_SESSION['role'];
$auth = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
=======
// Fetch user details from the session
$role = $_SESSION['role'];
$auth = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']);

// Fetch notifications for the logged-in user
$notifications = [];
$sql = "SELECT * FROM notifications WHERE user_id = '$auth' ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">

>>>>>>> <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/includes/sidebar/sidebar.css">
<<<<<<< HEAD
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Sidebar</title>
</head>
=======
    <link rel="stylesheet" type="text/css" href="/chat/chat.css">
    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
    <script src="/chat//chat.js" defer></script>
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Sidebar</title>
</head>

>>>>>>> <body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
<<<<<<< HEAD
                <span class="image">
                    <!--<img src="logo.png" alt="">-->
                </span>
                <div class="text logo-text">
                    <span class="name">Bonjour</span>
                    <span class="profession"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
=======
                <span class="image"></span>
                <div class="text logo-text">
                    <span class="name">Bonjour</span>
                    <span class="profession"><?php echo $username; ?></span>
>>>>>>>                 </div>
            </div>
            <i class='bx bx-chevron-right toggle'></i>
        </header>
<<<<<<< HEAD
        <div class="menu-bar">
            <div class="menu">
                
=======

        <div class="menu-bar">
            <div class="menu">
>>>>>>>                 <ul class="menu-links">
                    <li class="nav-link">
                        <a href="/home/index.php">
                            <i class='bx bx-globe icon'></i>
                            <span class="text nav-text">Accueil</span>
                        </a>
                    </li>
<<<<<<< HEAD
=======

                    <li class="nav-link">
                        <a href="#" id="notification-btn">
                            <i class='bx bx-bell icon'></i>
                            <span class="text nav-text">Notifications</span>
                        </a>
                    </li>

>>>>>>>                     <li class="nav-link">
                        <a href="/company/company_dashboard.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Tableau de bord</span>
                        </a>
                    </li>
<<<<<<< HEAD
=======
                    <?php if ($role == 'admin'): ?>
                        <li class="nav-link">
                            <a href="/admin/users/users.php">
                                <i class='bx bx-user icon'></i>
                                <span class="text nav-text">Les Utilisateurs</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="/admin/branch_function/ajoutebranch.php">
                                <i class='bx bx-building icon'></i>
                                <span class="text nav-text">Les branches</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="/admin/offers/offer.php">
                                <i class='bx bx-gift icon'></i>
                                <span class="text nav-text">Les offers</span>
                            </a>
                        </li>



                    <?php endif ?>
>>>>>>> 
                    <?php if ($role === 'company'): ?>
                        <li class="nav-link">
                            <a href="../../company/offre/ajouter_offre.php">
                                <i class='bx bx-plus-circle icon'></i>
                                <span class="text nav-text">Publier une offre</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="../../company/get_offres/mes_offres.php">
                                <i class='bx bx-list-ul icon'></i>
                                <span class="text nav-text">Mes offres</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="/company/condidature/condidature.php">
                                <i class='bx bx-file icon'></i>
                                <span class="text nav-text">Mes candidatures</span>
                            </a>
                        </li>
                    <?php elseif ($role === 'student'): ?>
                        <li class="nav-link">
                            <a href="#">
                                <i class='bx bx-file icon'></i>
                                <span class="text nav-text">Mes candidatures</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="/home/offres.php">
                                <i class='bx bx-briefcase icon'></i>
                                <span class="text nav-text">Offres disponibles</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-link">
<<<<<<< HEAD
                        <a href="/notifications.php">
                            <i class='bx bx-bell icon'></i>
                            <span class="text nav-text">Notifications</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <li class="">
                    <?php if($auth){  ?>
                    <a href="../../auth/logout/logout.php">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Déconnexion</span>
                    </a>
                    <?php } ?>
                </li>
               
            </div>
        </div>
    </nav>
    <section class="home">
        <div></div>
    </section>
    <script src="/includes/sidebar/sidebar.js"></script>
    
</body>
=======
                        <a href="#" id="chat-toggle">
                            <i class='bx bx-message icon'></i>
                            <span class="text nav-text">Boîte de discussion</span>
                        </a>
                    </li>
                    <li class="nav-link" id="list_users_link">
                        <a href="#">
                            <i class='bx bx-message icon'></i>
                            <span class="text nav-text">List Users</span>
                        </a>
                    </li>

                    <div id="user_list"></div> <!-- This will display the fetched user list -->


                </ul>
            </div>
            <!-- Chat Drawer -->
            <div class="chat-drawer" id="chat-drawer">
                <div class="chat-header">


                    <?php echo $auth; ?>
                    <input value="<?php echo $auth; ?>" id="user_id" hidden>

                    <span class="name">stage chat </span>

                    <button class="close-btn" id="close-btn">×</button>
                </div>
                <div class="chat" id="messages">
                    <!-- Chat messages will be appended here -->
                </div>
                <div class="write">
                    <input type="text" id="message-input" class="input" placeholder="Type your message..." />
                    <button id="send-button">Send</button>

                </div>
            </div>
            <div id="notification" class="notification">New message received!</div>




            <div class="bottom-content">
                <li>
                    <?php if ($auth): ?>
                        <a href="../../auth/logout/logout.php">
                            <i class='bx bx-log-out icon'></i>
                            <span class="text nav-text">Déconnexion</span>
                        </a>
                    <?php endif; ?>
                </li>
            </div>
        </div>
    </nav>
    <div id="userListContainer"></div>


    <!-- Notification Modal -->
    <div id="notification-dialog" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Notifications</h2>
            <ul id="notification-list">
                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $notification): ?>
                        <li><?php echo htmlspecialchars($notification['message']); ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Aucune notification disponible.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>



</body>
<script src="/includes/sidebar/sidebar.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/chat/listusers.js"></script>

>>>>>>> </html>