<?php 
$mime = ["image/jpeg", "image/jpg", "image/gif", "image/png"];
$maxFile = 2000000;

require "../connect.php";

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];  
    $sql = "SELECT * FROM nevjegyek WHERE id = {$id}";
    $result = mysqli_query($dbconn, $sql);
    $nevjegy = mysqli_fetch_assoc($result);
}

if(isset($_POST['rendben'])){

    //változók mentése és tisztítása
    $nev = htmlspecialchars(strip_tags(ucwords(strtolower(trim($_POST['nev'])))));
    $cegnev = htmlspecialchars(strip_tags(ucwords(strtolower(trim($_POST['cegnev'])))));
    $mobil = htmlspecialchars(strip_tags(trim($_POST['mobil'])));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    $id = (int)$_POST['id'];  


    if(empty($nev)){
        $hibak[] = "Nem adott meg nevet!";
    } elseif(strlen($nev) < 3){
        $hibak[] = "Az Ön neve gyanúsan rövid, hacsak Ön nem kínai!";
    }

    if(!empty($mobil)){
        if(strlen($mobil) < 9 ){
            $hibak[] = "Ön a segélyhívót akarta hívni?";
        }
    }

    if(!empty($email)){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $hibak[] = "Nem megfelelő az e-mail formátuma";
        }
    }

    // kép ellenőrzése
    if($_FILES['foto']['error'] == 0 && $_FILES['foto']['size'] > $maxFile){
        $hibak[] = "Túl nagy méretű képet tölt fel, max 2MB";
    }

    if($_FILES['foto']['error'] == 0 && !in_array($_FILES['foto']['type'], $mime)){
        $hibak[] = "Nem megfelelő képformátum!";
    }

    
    if($_FILES['foto']['error'] == 0){
        switch($_FILES['foto']['type']){
            case "image/png": $kit = ".png"; break;
            case "image/gif": $kit = ".gif"; break;
            case "image/jpeg": $kit = ".jpeg"; break;
            default: $kit = ".jpg";
        }
        $foto = date("U").$kit;
    } else {
        $foto = $nevjegy['foto'];  //nincs új kép feltöltve, a régi marad
    }

    // hibaüzenetek 
    if(isset($hibak)){
        $kimenet = "<ul>\n";
        foreach($hibak as $hiba){
            $kimenet .= "<li>{$hiba}</li>";
        }
        $kimenet .= "</ul>\n";
    } else {
        // adatbázisban frissítése
        $sql = "UPDATE nevjegyek 
                SET foto = '{$foto}', nev = '{$nev}', cegnev = '{$cegnev}', mobil = '{$mobil}', email = '{$email}' 
                WHERE id = {$id}";

        mysqli_query($dbconn, $sql);

        // kép mozgatása, ha van új kép
        if($_FILES['foto']['error'] == 0){
            move_uploaded_file($_FILES['foto']['tmp_name'], "../kepek/{$foto}");
        }
        
        header("Location: lista.php");  
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stilus.css">
    <title>Névjegy módosítása</title>
</head>
<body>
    <h1>Névjegy módosítása</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <?php if(isset($kimenet)) print $kimenet; ?>

        <input type="hidden" name="id" value="<?php if(isset($nevjegy)) print $nevjegy['id']; ?>">

        <p><label for="foto">Fotó:</label></p>
        <input type="file" name="foto" id="foto">
        <?php if(isset($nevjegy['foto'])): ?>
            <p><img src="../kepek/<?php print $nevjegy['foto']; ?>" width="100" alt="Jelenlegi fotó"></p>
        <?php endif; ?>

        <p><label for="nev">Név: *</label></p>
        <input type="text" name="nev" id="nev" value="<?php if(isset($nevjegy)) print $nevjegy['nev']; ?>">

        <p><label for="cegnev">Cég név: *</label></p>
        <input type="text" name="cegnev" id="cegnev" value="<?php if(isset($nevjegy)) print $nevjegy['cegnev']; ?>">

        <p><label for="mobil">Telefon: *</label></p>
        <input type="tel" name="mobil" id="mobil" value="<?php if(isset($nevjegy)) print $nevjegy['mobil']; ?>">

        <p><label for="email">Email: *</label></p>
        <input type="email" name="email" id="email" value="<?php if(isset($nevjegy)) print $nevjegy['email']; ?>">

        <p><em>*-al jelölt mezők kitöltése kötelező</em></p>

        <input type="submit" value="Rendben" name="rendben" id="rendben">
        <input type="reset" value="Mégsem">
    
    </form>
</body>
</html>
