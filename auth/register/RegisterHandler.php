<?php
require '../../includes/db/db.php'; 

class RegisterHandler {
    private $conn;
    private $errors = [];

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    public function getBranches() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM branch");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            $this->errors[] = "Erreur lors de la récupération des branches: " . $e->getMessage();
            return [];
        }
    }

    public function validateInput($data) {
        $this->errors = [];

        // Validation des champs obligatoires
        $required = ['username', 'password', 'email', 'role', 'full_name'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->errors[] = "Le champ " . str_replace('_', ' ', $field) . " est requis.";
            }
        }

        // Validation email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "L'email n'est pas valide.";
        }

        // Validation rôle
        if ($data['role'] === 'admin') {
            $this->errors[] = "Le rôle admin n'est pas autorisé à s'inscrire ici.";
        }

        // Validation branche pour étudiants
        if ($data['role'] === 'student' && empty($data['branch'])) {
            $this->errors[] = "Veuillez sélectionner une branche.";
        }

        return empty($this->errors);
    }

    public function isEmailTaken($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function registerUser($userData) {
        if (!$this->validateInput($userData)) {
            return false;
        }

        if ($this->isEmailTaken($userData['email'])) {
            $this->errors[] = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
            return false;
        }

        $password_hash = password_hash($userData['password'], PASSWORD_BCRYPT);
        $branch_id = $userData['role'] === 'student' ? $userData['branch'] : null;

        $stmt = $this->conn->prepare("INSERT INTO users 
            (username, password, email, role, full_name, phone, address, id_branch) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssssssi", 
            $userData['username'],
            $password_hash,
            $userData['email'],
            $userData['role'],
            $userData['full_name'],
            $userData['phone'],
            $userData['address'],
            $branch_id
        );

        if ($stmt->execute()) {
            return true;
        } else {
            $this->errors[] = "Une erreur s'est produite lors de l'inscription.";
            return false;
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}
?>