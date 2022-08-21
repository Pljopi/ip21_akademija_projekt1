<?php
if (isset($_POST["submit"])) {

    // Grabbing the data
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];

    // Instantiate SignupContr class
    include "../classes/login-contr.classes.php";
    $login = new LoginContr($uid, $pwd);
    //$twig->addGlobal('session', $_SESSION);
    // Running error handlers and user signup
    $login->loginUser();

    // Going to back to front page
    header("location: ../login_user.php?user=" . $_SESSION['useruid']);

}
