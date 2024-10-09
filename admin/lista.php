<?php
require "../connect.php";
//$kifejezes = isset($_POST["kifejezes"]) ? $_POST["kifejezes"] :"";
//$rendez  = isset($_POST["rendez"]) ? $_POST["rendez"] :"";
$rendez = $_GET["rendez"] ?? "nev";
$kifejezes = $_POST["kifejezes"] ?? "";

$validColumns = ["nev","cegnev","mobil","email"];
$rendez = in_array($rendez, $validColumns) ? $rendez : "nev";

$sql = "SELECT * 
        FROM nevjegyek
        WHERE (
        nev LIKE ?
        OR cegnev LIKE ?
        OR email LIKE ?
        OR mobil LIKE ?
        )
        ORDER BY {$rendez} ASC";

//$result = mysqli_query($dbconn, $sql);
$stmt = mysqli_prepare($dbconn, $sql);
$stmt = $dbconn -> prepare($sql);

if ($stmt) {
    $searchTerm = "%{$kifejezes}%";
    //paraméterek betöltése
    //mysqli_stmt_bind_param($stmt,"ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt -> bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    //utasítás végrehajtása
    //mysqli_stmt_execute($stmt);
    $stmt -> execute();
    //$eredmeny = mysqli_stmt_get_result($stmt);
    $eredmeny = $stmt -> get_result();
    if ($eredmeny) {
        $kimenet = "<table>
                        <tr>
                            <th>Kép</th>
                            <th><a href=\"?rendez=nev\">Név</a></th>
                            <th><a href=\"?rendez=cegnev\">Cégnév</a></th>
                            <th><a href=\"?rendez=mobil\">Mobil</a></th>
                            <th><a href=\"?rendez=email\">Email</a></th>
                            <th>Művelet</th>
                        </tr>";
        while ($sor = mysqli_fetch_array($eredmeny)) {  
            $foto = htmlspecialchars($sor["foto"], ENT_QUOTES, "utf-8");
            $nev = htmlspecialchars($sor["nev"], ENT_QUOTES, "utf-8");
            $cegnev = htmlspecialchars($sor["cegnev"], ENT_QUOTES, "utf-8");
            $mobil = htmlspecialchars($sor["mobil"], ENT_QUOTES, "utf-8");
            $email = htmlspecialchars($sor["email"], ENT_QUOTES, "utf-8");
            $kimenet .= "<tr>
                            <td><img src=\"../kepek/{$foto}\"></td>
                            <td>{$nev}</td>
                            <td>{$cegnev}</td>
                            <td>{$mobil}</td>
                            <td>{$email}</td>
                            <td><a href=\"torles.php?id={$sor['id']}\">Törlés</a> | <a href=\"modositas.php?id={$sor['id']}\">Módosítás</a></td>
                        </tr>";
        }
        $kimenet .= "</table>";
    }
    $stmt -> close();
}
else {
    echo"Hiba az elkészített utasítás létrehozásában" . mysqli_error( $dbconn );
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stilus.css">
    <title>Document</title>
</head>
<body>
    <h1>Névjegyzék</h1>
    <form method="post">
        <input type="search" name="kifejezes" id="kifejezes">
    </form>
    <!--lapozó-->
    <div class="container">
        <!--kimenet ->adatbázisból beolvasott adatok-->
        <?php //print $lapozo; ?>
        <a href="felvitel.php">Új névjegy hozzáadása</a>
        <?php print $kimenet; ?>
    </div>
</body>
</html>