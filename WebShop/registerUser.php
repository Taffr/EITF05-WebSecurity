<?php
$username = $_POST['name'];
$password = $_POST['password'];
$repeatPass = $_POST['repeatPass'];
$address = $_POST['address'];
$uppercast = preg_match('@[A-Z]@', $password);
$lowercast = preg_match('@[a-z]@', $password);
$number = preg_match('@[0-9]@', $password);

$conn = new mysqli("localhost", "root", "","WebShopDB") or die("Connect failed: %s\n". $conn -> error);
$lookupName = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users WHERE name = '$username'"));


  if($lookupName['name'] == $username){
    echo "Username not available";
    echo ' <a href="index.php">return home</a> ';
  }elseif(strpos(file_get_contents("blacklist.txt"),$password) !== false || !$uppercast || !$lowercast || !$number || strlen($password) < 8 || $address == ""){
     echo "You need to use both uppercase, lowercase and numbers in your password and it has to be longer than 8 characters. It could also be the case that your password is just to common.";
     echo ' <a href="index.php">return home</a> ';
  }elseif ($password != $repeatPass) {
    echo "Passwords are not equal";
    echo ' <a href="index.php">return home</a> ';
  }else{
    $hash = password_hash($password, PASSWORD_BCRYPT); // Hashes using bcrypt and autogenerated salt.
    $insertUser = "INSERT INTO users VALUES ('$username', '$hash', '$address')";
    $insertCounter = "INSERT INTO loginAttempts VALUES('$username', 0)";
    mysqli_query($conn, $insertUser);
    mysqli_query($conn, $insertCounter);
    echo "Congratulations, $username, you're now ready to shop!";
    echo ' <a href="index.php">return home</a> ';
  }


?>
