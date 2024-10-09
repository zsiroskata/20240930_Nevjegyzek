<?php
if (isset($_GET["id"])) {
    //print_r($_GET["id"]); így meg tudjuk nézni, hogy az id-t meg-e kaptuk
    require "../connect.php";

    $id = (int)$_GET["id"];

    $sql = "SELECT foto
        FROM nevjegyek
        WHERE id = {$id}"
        ;
        
    $result = mysqli_query($dbconn, $sql);
    $row = mysqli_fetch_assoc($result);
    //var_dump($row); ellenőrizzük hogy meg-e kaptuk

    if ($row["foto"] != "nincskep.png") {
        unlink("../kepek/{$row["foto"]}");
    }

    //adatbázisból törlünk
    $sql = "DELETE FROM nevjegyek
        WHERE id = {$id}"
        ;
        mysqli_query($dbconn, $sql);
}
header("location: lista.php");

?>