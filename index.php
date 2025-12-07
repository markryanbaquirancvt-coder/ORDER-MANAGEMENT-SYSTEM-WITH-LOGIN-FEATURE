<?php 
// CRITICAL FIX: Ensure all core files are included
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

global $pdo; 

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
    exit(); 
}

// Check for the receipt data to determine which view to show
$is_receipt = isset($_SESSION['receipt_total']) && isset($_SESSION['receipt_change']);
$receipt_total = $is_receipt ? $_SESSION['receipt_total'] : null;
$receipt_change = $is_receipt ? $_SESSION['receipt_change'] : null;

if ($is_receipt) {
    // Clear the receipt data after reading it, so the menu shows next time
    unset($_SESSION['receipt_total']); 
    unset($_SESSION['receipt_change']); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Canteen Order Page</title>
	<style>
		body {
			font-family: "Arial";
			/* Style for the receipt output to match your image */
			font-size: 1.5em; 
		}
		input, select {
			font-size: 1.5em;
			height: 50px;
			width: 200px;
			margin-top: 5px;
		}
	</style>
</head>
<body>
    
    <?php if ($is_receipt) { ?>
        <p>The total cost is <?php echo $receipt_total; ?></p>
        <p>Your change is <?php echo $receipt_change; ?></p>
        <p>Thanks for the order! <?php echo $_SESSION['username']; ?></p>
        
        <br>
        <hr>
        
        <a href="index.php">Go back to Canteen Menu</a> | <a href="core/handleForms.php?logoutAUser=1">Logout</a>

    <?php } else { ?>
        <?php if (isset($_SESSION['message'])) { ?>
            <h1 style="color: red;"><?php echo $_SESSION['message']; ?></h1>
        <?php } unset($_SESSION['message']); ?>


        <h1>Welcome to the canteen, <span style="color: red;"><?php echo $_SESSION['username']; ?></span></h1>
        <a href="core/handleForms.php?logoutAUser=1">Logout</a>
        <hr>
        
        <h2>Here are the prices:</h2>
        <ul>
            <li>Fishball - 30 PHP</li>
            <li>Kikiam - 40 PHP</li>
            <li>Corndog - 50 PHP</li>
        </ul>

        <form action="core/handleForms.php" method="POST">
            <p>
                <label for="order">Choose your order:</label>
                <select name="order">
                    <option value="Fishball">Fishball</option>
                    <option value="Kikiam">Kikiam</option>
                    <option value="Corndog">Corndog</option>
                </select>
            </p>
            <p>
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" required>
            </p>
            <p>
                <label for="cash">Cash:</label>
                <input type="number" name="cash" required>
            </p>
            <input type="submit" name="submitOrderBtn" value="Submit">
        </form>

    <?php } ?>
</body>
</html>