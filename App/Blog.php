<?php

// Class and its methods are discussed in the README.md

class Blog
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn->getInstance();
    }

    function getAll()
    {
        $sql = "SELECT blog.id, blog.text, user.username, user.permission, user.readonly
                FROM blog
                JOIN user ON blog.userid = user.userid";

        if($result = $this->conn->query($sql)) {
            to_json($result->fetch_all(MYSQLI_ASSOC));
        } else {
            bad_request("An unexpected error occurred.");
        }
    }

    function create()
    {
        try {
            Blog::validateCreateRequest();
        } catch(Exception $e) {
            bad_request($e->getMessage()); return;
        }

        $sql = "INSERT INTO blog (text, userid) VALUES ('{$_GET['text']}', '{$_SESSION['userid']}')";

        if($this->conn->query($sql)) {
            to_json("Blog created.", 201);
        } else {
            bad_request("An unexpected error occurred.");
        }
    }

    function delete()
    {
        try {
            Blog::validateDeleteRequest();
        } catch(Exception $e) {
            bad_request($e->getMessage()); return;
        }

        $sql = "DELETE FROM blog WHERE id = {$_GET['id']}";

        if($this->conn->query($sql)) {
            to_json("Blog deleted.");
        } else {
            bad_request("An unexpected error occurred.");
        }

    }

    // Request validation methods.
    // Good idea would be to refactor them into a separate class to follow the SOLID principle.
    // However, for simplicity, I will leave them in this class.

    /**
     * @throws ErrorException
     */
    private static function validateCreateRequest()
    {
        if(!is_logged_in()) {
            throw new ErrorException("You must be logged-in to create a new blog post.");
        }

        if(!Blog::validateCreateParams()) {
            throw new ErrorException("Invalid GET parameters");
        }

        if(is_read_only($_SESSION['readonly'])) {
            throw new ErrorException("Unable to create: You have readonly access");
        }
    }

    /**
     * @throws ErrorException
     */
    private function validateDeleteRequest()
    {
        if(!is_logged_in()) {
            throw new ErrorException("You must be logged-in to delete a blog post.");
        }

        if(!Blog::validateDeleteParams()) {
            throw new ErrorException("Invalid GET parameters");
        }

        if(is_read_only($_SESSION['readonly'])) {
            throw new ErrorException("Unable to delete: You have readonly access");
        }

        if(!$this->checkIfBlogExists())
        {
            throw new ErrorException("The blog does not exist.");
        }
    }

    private static function validateDeleteParams()
    {
        return isset($_GET['id']);
    }

    private static function validateCreateParams()
    {
        return isset($_GET['text']);
    }

    private function checkIfBlogExists()
    {
        $sql = "SELECT * FROM blog WHERE id = '{$_GET['id']}'";

        $result = $this->conn->query($sql);

        return $result->num_rows > 0;
    }
}