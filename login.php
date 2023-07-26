<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login/Signup</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./style_alert.css">
</head>

<?php
function testdata($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<body>
    <div class="form-structor">
        <div class="signup">
            <h2 class="form-title" id="signup"><span>or</span>Sign up</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-holder">
                    <input type="text" class="input" placeholder="Name" name="uname" />
                    <input type="email" class="input" placeholder="Email" name="email" />
                    <input type="password" class="input" placeholder="Password" name="pass" />
                </div>
                <button class="submit-btn" type="submit" name="signUp">Sign up</button>
            </form>
        </div>
        <div class="login slide-up">
            <div class="center">
                <h2 class="form-title" id="login"><span>or</span>Log in</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-holder">
                        <input type="email" class="input" placeholder="Email" name="email" />
                        <input type="password" class="input" placeholder="Password" name="pass" />
                    </div>
                    <button class="submit-btn" type="submit" name="logIn">Log in</button>
                </form>
                <div style="display: flex;
				justify-content: center;
				align-items: center;
				margin: 1rem;">
                <p>OR</p>
                </div>
                <div style="display: flex;
				justify-content: center;
				align-items: center;
				margin: 1rem;">
                    <?php
                    require_once 'vendor/autoload.php';
                    $clientID = '17550590323-511l504tp4punj6h0ms2o8d7bf21ine4.apps.googleusercontent.com';
                    $clientSecret = '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';
                    // $rediectUrl = 'http://localhost/CAT_Innovative/h.html';
                    $rediectUrl='http://localhost/CAT_Innovative/UI.html';

                    $client = new Google_Client();
                    $client->setClientId($clientID);
                    $client->setClientSecret($clientSecret);
                    $client->setRedirectUri($rediectUrl);
                    $client->addScope('profile');
                    $client->addScope('email');

                    if (!isset($_POST['code'])) {
                        echo '<a href="' . $client->createAuthUrl() . '"><img src="gpic-removebg-preview.png" alt="Sign in With Google" style="height: 2.5rem;"></a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="./script.js"></script>
</body>

<?php
if (isset($_POST['logIn'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $pass = $_POST["pass"];
        $err = "";
        if (empty($email) || empty($pass)) {
            $err = "Please enter Email and Password";
        } else {
            $email = testdata($email);
            $pass = testdata($pass);

            $sql = "SELECT Password FROM `user_details` WHERE Email='" . $email . "';";

            $sn = "localhost";
            $un = "root";
            $ps = "";
            $db = "vlab";

            $conn = mysqli_connect($sn, $un, $ps, $db);
            if (!$conn) {
                die("Some Error Occured");
            }
            $res = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($res);
            if ($num == 0) {
                $err = "Please Sign Up First";
            } else {
                $row = mysqli_fetch_assoc($res);
                if (strcmp($pass, $row["Password"]) == 0) {
                    header("Location: http://localhost/CAT_Innovative/UI.html");
                } else {
                    $err = "Invalid Credentials";
                }
            }
        }
        if (strcmp($err, "")) {
            echo '<div class="alert error">
  				  <input type="checkbox" id="alert1"/>
  				  <label class="close" title="close" for="alert1">
    			  <i class="icon-remove"></i>
  				  </label>
  				  <p class="inner"> ' . $err . '
  				  </p>
				  </div>';
        }
    }
}
?>

<?php
if (isset($_POST['signUp'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uname = testdata($_POST["uname"]);
        $email = testdata($_POST["email"]);
        $pass = testdata($_POST["pass"]);
        $err = "";
        $succ = "";

        if (empty($uname) || empty($email) || empty($pass)) {
            $err = "Please Fill All the Details";
        } else {
            $sql1 = "SELECT Password FROM `user_details` WHERE Email='" . $email . "';";
            $sql = "INSERT INTO `user_details` (`User_Name`, `Email`, `Password`) VALUES ('" . $uname . "', '" . $email . "', '" . $pass . "');";

            $sn = "localhost";
            $un = "root";
            $ps = "";
            $db = "vlab";

            $conn = mysqli_connect($sn, $un, $ps, $db);
            if (!$conn) {
                die("Some Error Occured");
            }
            $res1 = mysqli_query($conn, $sql1);
            if (!$res1) {
                $res = mysqli_query($conn, $sql);
                // var_dump($res);
                if (!$res) {
                    $err = "Some error occured. Please Try Again!";
                } else {
                    $succ = "You are Successfully Registered. Please Login to continue.";
                }
            }
            else{
                $err="Email is already Registered";
            }
        }
        if (strcmp($err, "")) {
            echo '<div class="alert error">
						<input type="checkbox" id="alert1"/>
						<label class="close" title="close" for="alert1">
					  <i class="icon-remove"></i>
						</label>
						<p class="inner"> ' . $err . '
						</p>
					  </div>';
        }
        if (strcmp($succ, "")) {
            echo '<div class="alert success">
  				  <input type="checkbox" id="alert2"/>
  				  <label class="close" title="close" for="alert2">
    			  <i class="icon-remove"></i>
  				  </label>
  				  <p class="inner">
    				' . $succ . '
  				  </p>
				  </div>';
        }
    }
}
?>

</html>