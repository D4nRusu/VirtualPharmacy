<?php

class User {

    public $errors;
    /**
     * @var Database
     */
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function isLoggedIn(): bool
    {
        if (isset($_SESSION['isLoggedIn'])) {
            if ($_SESSION['isLoggedIn'] === true) {
                return true;
            }
        }
        return false;
    }

    public function login() {
        if (!empty($_POST)) {
            $_POST['username'] = $this->sanitize($_POST['username']);
            $_POST['password'] = $this->sanitize($_POST['password']);
            $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
            $stmt = $this->database->getDb()->prepare($sql);
            $stmt->bind_param("ss", $_POST['username'], $_POST['password']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            // if the user is found in the database, we can log them in
            if (!empty($row)) {
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['user'] = $_POST['username'];
                $_SESSION['userId'] = $row['id'];
                $_SESSION['userEmail'] = $row['email'];
                $_SESSION['userFirstName'] = $row['first_name'];
                $_SESSION['userLastName'] = $row['last_name'];
                $_SESSION['userRegDate'] = $row['registration_date'];
            } else {
                $_SESSION['LoginError'] = 'Login failed: username/password combination does not match.';
            }
        }
    }

    public function register() {
        // Data validation
        if (!empty($_POST)) {
            $_POST['email'] = $this->sanitize($_POST['email']);
            $_POST['username'] = $this->sanitize($_POST['username']);
            $_POST['firstName'] = $this->sanitize($_POST['firstName']);
            $_POST['lastName'] = $this->sanitize($_POST['lastName']);
            $_POST['password'] = $this->sanitize($_POST['password']);

            if ($this->isEmail($_POST['email']) == false) {
                $this->errors['email'] = 'Invalid email';
            }
            if ($this->isUniqueEmail($_POST['email']) > 0) {
                $this->errors['email'] = 'Email already in use';
            }

            if ($this->isUniqueUsername($_POST['username']) > 0) {
                $this->errors['username'] = "Username is already in use";
            }
            if ($this->isAlphaNumeric($_POST['username']) == 0) {
                $this->errors['username'] = "Username has to be alphanumeric";
            }
            if (strlen($_POST['username']) < 4 || strlen($_POST['username']) > 18) {
                $this->errors['username'] = "Username must be between 4 and 18 characters";
            }

            if ($this->isAlpha($_POST['firstName']) == 0) {
                $this->errors['firstName'] = "First name has to be alpha";
            }
            if (strlen($_POST['firstName']) < 1 || strlen($_POST['firstName']) > 25) {
                $this->errors['firstName'] = "First name must be between 1 and 25 characters";
            }

            if ($this->isAlpha($_POST['lastName']) == 0) {
                $this->errors['lastName'] = "Last name has to be alpha";
            }
            if (strlen($_POST['lastName']) < 1 || strlen($_POST['lastName']) > 25) {
                $this->errors['lastName'] = "Last name must be between 1 and 25 characters";
            }

        }
        if (empty($this->errors)) {
            $this->save(); // if there are no errors, register the user
            $_SESSION['Register'] = true;
            if (isset($_SESSION['RegisterError'])) {
                unset($_SESSION['RegisterError']);
            }
        } else {
            $this->errorHandler(); // if there are errors, this handles them
            $_SESSION['Register'] = false;
        }
    }


    private function isAlpha($username) {
        return preg_match("/^[a-zA-Z ]*$/", $username);
    }

    private function isAlphaNumeric($username) {
        return preg_match("/^[a-zA-Z0-9 ]*$/", $username);
    }

    private function sanitize($data): string {
        return htmlspecialchars(trim(stripslashes($data)));
    }

    private function isEmail(string $email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function isUniqueUsername($username): int {
        $sql = "SELECT username FROM users WHERE username =?";
        $stmt = $this->database->getDb()->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;
    }

    public function isUniqueEmail($email): int {
        $sql = "SELECT `email` FROM `users` WHERE `email` =?";
        $stmt = $this->database->getDb()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;
    }

    private function save() {
        $sql = "INSERT INTO users (email, username, first_name, last_name, password, registration_date) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->database->getDb()->prepare($sql);
        $date = date("Y-m-d");
        $stmt->bind_param(
            "ssssss", $_POST['email'], $_POST['username'], $_POST['firstName'], $_POST['lastName'], $_POST['password'], $date
        );
        $stmt->execute();
    }

    private function errorHandler() {
        if (isset($this->errors['email'])) {
            $_SESSION['emailError'] = $this->errors['email'];
        }
        if (isset($this->errors['username'])) {
            $_SESSION['usernameError'] = $this->errors['username'];
        }
        if (isset($this->errors['firstName'])) {
            $_SESSION['firstNameError'] = $this->errors['firstName'];
        }
        if (isset($this->errors['lastName'])) {
            $_SESSION['lastNameError'] = $this->errors['lastName'];
        }
    }

}