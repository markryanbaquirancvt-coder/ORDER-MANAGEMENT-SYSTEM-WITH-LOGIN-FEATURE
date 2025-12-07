<?php
// Line 1: FIX: Clean opening tag (fixes Parse error: unexpected token "require_once")

// NOTE: It is better practice to include dbConfig.php ONLY in handleForms.php
// However, since your existing code uses it, we'll keep it, but ensure $pdo is passed.


// =========================================================================
// FUNCTION 1: INSERT NEW USER (Uses secure password_hash)
// =========================================================================
function insertNewUser($pdo, $username, $password) {

    // 1. Check if the username already exists
    $checkUserSql = "SELECT username FROM user_passwords WHERE username = ?";
    $checkUserSqlStmt = $pdo->prepare($checkUserSql);
    $checkUserSqlStmt->execute([$username]);

    if ($checkUserSqlStmt->rowCount() > 0) {
        $_SESSION['message'] = "User already exists. Please choose a different username.";
        return false;
    }

    // 2. Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 3. Insert the new user with the HASHED password
    $sql = "INSERT INTO user_passwords (username, password) VALUES(?, ?)";
    $stmt = $pdo->prepare($sql);
    
    $executeQuery = $stmt->execute([$username, $hashedPassword]);

    if ($executeQuery) {
        $_SESSION['message'] = "Registration successful! You may now log in.";
        return true;
    } else {
        $_SESSION['message'] = "An error occurred during registration.";
        return false;
    }
}


// =========================================================================
// FUNCTION 2: LOGIN USER (Uses secure password_verify)
// =========================================================================
function loginUser($pdo, $username, $password) {
    $sql = "SELECT username, password FROM user_passwords WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]); 

    if ($stmt->rowCount() == 1) {
        $userInfoRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $passwordHashFromDB = $userInfoRow['password']; 

        // Verify the provided raw password against the stored hash
        if (password_verify($password, $passwordHashFromDB)) {
            $_SESSION['username'] = $userInfoRow['username'];
            $_SESSION['message'] = "Login successful!";
            return true;
        }
    }
    
    // Generic message for security
    $_SESSION['message'] = "Invalid username or password.";
    return false;
}


// =========================================================================
// FUNCTION 3 & 4 (Remaining functions should remain as they were)
// =========================================================================
function getAllUsers($pdo) {
    $sql = "SELECT * FROM user_passwords";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute();

    if ($executeQuery) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return false;
}


function getUserByID($pdo, $user_id) {
    $sql = "SELECT * FROM user_passwords WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([$user_id]);
    
    if ($executeQuery) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return false;
}
?>