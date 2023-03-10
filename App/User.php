<?php

// Class and its methods are discussed in the README.md

class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn->getInstance();
    }

    public function create()
    {
        try {
            $this->validateCreateRequest();
        } catch(Exception $e) {
            bad_request($e->getMessage()); return;
        }

        $sql = "INSERT INTO user (username, password, permission, readonly)
            VALUES ('{$_GET['username']}', '{$_GET['password']}', '{$_GET['permission']}', '{$_GET['readonly']}')";

        if($this->conn->query($sql)) {
            to_json("User created.", 201);
        } else {
            bad_request("An unexpected error occurred.");
        }

    }

    function login()
    {
        try {
            $this->validateLoginRequest();
        } catch(Exception $e) {
            bad_request($e->getMessage()); return;
        }

        $sql = "SELECT * FROM user WHERE username = '{$_GET['user']}' AND password = '{$_GET['password']}'";

        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Store data in session variables
            $_SESSION["logged_in"] = true;
            $_SESSION["userid"] = $user['userid'];
            $_SESSION["username"] = $user['username'];
            $_SESSION["permission"] = $user['permission'];
            $_SESSION["readonly"] = $user['readonly'];

            to_json("User logged-in.");
        } else {
            bad_request("Credentials do not match the records in the database.");
        }
    }

    // Request validation methods.
    // Good idea would be to refactor them into a separate class to follow the SOLID principle.
    // However, for simplicity, I will leave them in this class.

    /**
     * @throws ErrorException
     */
    private function validateCreateRequest()
    {
        if(!User::validateCreateParams()) {
            throw new ErrorException("Invalid GET parameters.");
        }

        if($this->checkIfUserExists()) {
            throw new ErrorException("User {$_GET['username']} exists");
        }
    }

    /**
     * @throws ErrorException
     */
    public function validateLoginRequest()
    {
        if(!User::validateLoginParams()) {
            throw new ErrorException("Invalid GET parameters.");
        }

    }

    private static function validateCreateParams()
    {
        return isset($_GET['username']) && isset($_GET['password']) && isset($_GET['permission']) && isset($_GET['readonly']);
    }

    private static function validateLoginParams()
    {
        return isset($_GET['user']) && isset($_GET['password']);
    }

    private function checkIfUserExists()
    {
        $sql = "SELECT * FROM user WHERE username = '{$_GET['username']}'";

        $result = $this->conn->query($sql);

        return $result->num_rows > 0;
    }
}