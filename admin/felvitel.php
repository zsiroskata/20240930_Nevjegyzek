<?php
if (isset($_POST["rendben"])) {
    //változók mentés és tisztítása
    $nev = htmlspecialchars(strip_tags(ucwords(strtolower(trim ($_POST["nev"])))));
    $ceg = htmlspecialchars(strip_tags(ucwords(strtolower(trim ($_POST["cegnev"])))));
    $mobil = htmlspecialchars(strip_tags(trim ($_POST["mobil"])));
    $email = htmlspecialchars(strip_tags(trim ($_POST["email"])));
    
    //var_dump($nev, $ceg, $mobil, $email);
    //kép file-ok megengedett tipusainak tárolása
    $mime=["image/jpeg", "image/gif", "image/jpg", "image/png"];

    if (empty($nev)) {
        $hibak[] = "Nem adott meg nevet!"; 
    }elseif (strlen($nev) < 2) {
        $hibak[] = "Az ön neve gyanúsan rövid, hacsak nem kínai!!";
    }

    if (!empty($mobil)) {
        if (strlen($mobil) < 9) {
            $hibak[] = "Rövid a telefonszám";
        }/*elseif (!preg_match("/^[0-9]{9,}$/", $mobil)) {
            $hibak[] = "A mobil szám csak számokat tartalmazhat!";
        }*/
    }
    
    if (!empty($email)) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hibak[] = "Nem megfelelő az e-mail formátum!"
        }
    }

    if($_FILES["foto"]["error"] == 0 && $_FILES["foto"]["size"] < 2000000) {
        $hibak[] = "Túl nagy méretű képet tölt fel max 2MB";
    }

    if ($_FILES["foto"]["error"] == 0 && !in_array($_FILES["foto"]["type"], $mime)) {
        $hibak[] = "Nem megfelelő kép formátum!";
        //kép nevének összeállítása
        switch ($_FILES['foto']['type']) {
            case 'image/png':
                $kit = ".png";
                break;
            case 'image/gif':
                $kit = ".gif";
                break;
            case 'image/jpeg':
                $kit = ".jpeg";
                break;
            default:
                $kit = ".jpg"
                break;
        }

        $foto = data("U");
    }

    if (!empty($email))
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $hibak[] = "Nem megfelelő az e-mail formátumnak";
        }
    }

    if ($_FILES['foto']['error'] == 0 && $_FILES['foto']['size'] > 2000000)
    {
        $hibak [] = "Túl nagy méretű képet tölt fel, max 2MB";
    }

    if($_FILES['foto']['error'] == 0 && !in_array($_FILES['foto']['type'], $mime)) 
    $hibak[] ="Nem megfelelő képformátum";

    //Kép nevének összeállítása
    switch ($_FILES["foto"]["type"])
    {
        case "image/png": $kit = ".png"; break;
        case "image/gif": $kit = ".gif"; break;
        case "image/jpeg": $kit = ".jpeg"; break;
        default: $kit = ".jpg";
    }

    $foto = date("U") . $kit;
    //$unix = date("U");
    //print_r($unix);

    //$uniqID = uniqid();
    //print_r ($uniqID);

    //hiba üzenetek
    if(isset($hibak))
    {
        $kimenet = "<ul>\n";
        foreach($hibak as $hiba)
        {
            $kimenet .= "<li>{$hiba}</li>";
        }
        $kimenet = "</ul>\n";
    }
    else
    {
        //felvitel az adatbázisba
        require "../connect.php";
        $sql = "INSERT INTO nevjegyek
        (foto, nev, cegnev, mobil, email)
        VALUES
        ('{$foto}','{$nev}','{$cegnev}','{$mobile}','{$email}')";
        mysqli_query($conn, $sql);

        move_uploaded_file($_FILES["foto"]["tmp_name"], "../kepek/{$foto}");
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stilus.css">
    <title>Új névjegyzék felvitele</title>
</head>
<body>
    <h1>Névjegy felvitele</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <!-- hiba üzi a usernek, ha valamit elront -->

        <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
        <p><label for="foto">Fotó:</label></p>
        <input type="file" name="foto" id="foto" value="<?php if (isset($foto)) print $foto ?>">

        <p><label for="nev">Név: *</label></p>
        <input type="text" name="nev" id="nev" value="<?php if (isset($nev)) print $nev ?>">
        
        <p><label for="cegnev">Cég név: *</label></p>
        <input type="text" name="cegnev" id="cegnev">
        
        <p><label for="mobil">Mobil: *</label></p>
        <input type="tel" name="mobil" id="mobil">


        <p><label for="email">email: *</label></p>
        <input type="email" name="email" id="email">

        <p><em>*-al jelölt mezők kitöltése kötelező</em></p>

        <input type="submit" value="Rendben" name="rendben" id="rendben">
        <input type="reset" value="Mégse">

    </form>
</body>
</html>