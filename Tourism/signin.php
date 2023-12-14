<?php
$db = mysqli_connect('localhost:3307', 'root', '', 'travel');

// Use mysqli_real_escape_string to prevent SQL injection
$username = mysqli_real_escape_string($db, $_POST["user"]);
$password = mysqli_real_escape_string($db, $_POST["pass"]);
$d = date("Y-m-d h:i:sa");
$i = 0;
$usern = "";
$passd = "";

// Use prepared statements to prevent SQL injection
$que = "INSERT INTO `login` (`user`,`pass`,`date_time`) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($db, $que);
mysqli_stmt_bind_param($stmt, 'sss', $username, $password, $d);

$sql = "SELECT fname, password FROM `customer` WHERE fname=? AND password=?";
$stmt2 = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt2, 'ss', $username, $password);

if (isset($_POST['submit'])) {
    if ($username == 'admin' and $password == 'ad123') {
        mysqli_stmt_execute($stmt);
        header('location:admin.php');
    } elseif (mysqli_stmt_execute($stmt2)) {
        $result2 = mysqli_stmt_get_result($stmt2);
        while ($rows = mysqli_fetch_assoc($result2) and $i == 0) {
            $usern = $rows['fname'];
            $passd = $rows['password'];
            $i = $i + 1;
        }
        if ($usern == $username and $passd == $password) {
            mysqli_stmt_execute($stmt);
            header("location:mainPage.html");
        } else {
            ?>
            <script>
                alert("Invalid username or password");
            </script>
            <?php
        }
    }
}

// Close the prepared statements and database connection when done
mysqli_stmt_close($stmt);
mysqli_stmt_close($stmt2);
mysqli_close($db);
?>
