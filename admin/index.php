<?php
    session_start();
    if (isset($_POST['rendben'])) {
        $email = htmlspecialchars($_POST['email']);
        $jelszo = $_POST['jelszo'];

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hiba = "Hibás email cím vagy jelszó!";
        } 
        else{
            //sikeres
            if ($email == "zita@gmail.com" && sha1($jelszo) == "24a5a37e074d43f54d3d6e033d86886e") {
                $_SESSION["belepett"] = true;
                header("Location: felvitel.php");
            }
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Belépés</h1>
    <form method="post">
    <?php
    if (isset($hiba)) {
        echo '<p style="color: red;">'. $hiba. '</p>';
    }
   
    ?>
            <p>
                <label for="email">email</label>
                <input type="email" name="email" id="email">
            </p>
            
            <p>
                <label for="jelszo">jelszo</label>
                <input type="password" name="jelszo" id="jelszo">
            </p>
            <input type="submit" name="rendben" value="Belépés">
    </form>
</body>
</html>