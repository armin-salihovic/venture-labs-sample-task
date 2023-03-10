<?php

require 'App/Database.php';
require 'App/User.php';
require 'App/Blog.php';
require 'includes/helpers.php';

// Initialize the session
session_start();

// The 3 classes discussed in the README.md are instantiated
$conn = new Database();

$user = new User($conn);

$blog = new Blog($conn);

// Once the action parameters is validated, based on the value, the appropriate methods are called.
if(isset($_GET['action'])) {
    switch ($_GET['action']) {

        case 'login':
            $user->login();
            break;

        case 'new':
            $blog->create();
            break;

        case 'new_user':
            $user->create();
            break;

        case 'delete':
            $blog->delete();
            break;

    }
} else {
    // In case no action param is set, the blog list will be returned as JSON array.
    $blog->getAll();
}