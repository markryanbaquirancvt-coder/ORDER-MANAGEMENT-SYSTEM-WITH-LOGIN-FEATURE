<?php
// CRITICAL: Must include dbConfig FIRST
require_once 'dbConfig.php'; 
require_once 'models.php';

global $pdo; 

// =========================================================================
// 1. REGISTER USER
// =========================================================================
if (isset($_POST['registerUserBtn'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    if (!empty($username) && !empty($password)) {
        $insertQuery = insertNewUser($pdo, $username, $password);
        if ($insertQuery) {
            header("Location: ../login.php"); exit(); 
        } else {
            header("Location: ../register.php"); exit();
        }
    } else {
        $_SESSION['message'] = "Please make sure the input fields are not empty for registration!";
        header("Location: ../login.php"); exit();
    }
}

// =========================================================================
// 2. LOGIN USER
// =========================================================================
if (isset($_POST['loginUserBtn'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    if (!empty($username) && !empty($password)) {
        $loginQuery = loginUser($pdo, $username, $password);
        if ($loginQuery) {
            header("Location: ../index.php"); exit();
        } else {
            header("Location: ../login.php"); exit();
        }
    } else {
        $_SESSION['message'] = "Please make sure the input fields are not empty for the login!";
        header("Location: ../login.php"); exit();
    }
}

// =========================================================================
// 3. LOGOUT USER
// =========================================================================
if (isset($_GET['logoutAUser'])) {
    unset($_SESSION['username']);
    $_SESSION['message'] = "You have been successfully logged out.";
    header('Location: ../login.php');
    exit();
}

// =========================================================================
// 4. SUBMIT CANTEEN ORDER (STORES TOTAL AND CHANGE)
// =========================================================================
if (isset($_POST['submitOrderBtn'])) {
    
    if (!isset($_SESSION['username'])) {
        $_SESSION['message'] = "You must be logged in to place an order.";
        header("Location: ../login.php");
        exit();
    }

    $item = $_POST['order'];
    $quantity = (int)$_POST['quantity'];
    $cash = (int)$_POST['cash'];
    
    // Determine price
    $price = 0;
    switch ($item) {
        case 'Fishball': $price = 30; break;
        case 'Kikiam': $price = 40; break;
        case 'Corndog': $price = 50; break;
        default: $price = 0;
    }

    $total = $price * $quantity;
    $change = $cash - $total;

    if ($change < 0) {
        $_SESSION['message'] = "Cash provided (P{$cash}) is insufficient for the total order (P{$total}).";
        header("Location: ../index.php");
        exit();
    }
    
    // CRITICAL: Store the individual receipt components
    $_SESSION['receipt_total'] = $total;
    $_SESSION['receipt_change'] = $change; 
    
    // Redirect back to index.php to display the receipt view
    header("Location: ../index.php"); 
    exit();
}
?>