<?php
require "connect.php";
$sql = "SELECT * FROM nevjegyek";
$kifejezes = isset($_POST["kifejezes"]) ? $_POST["kifejezes"] :"";
//var_dump($kifejezes); - testeltem hogy a kifejezést vissza-e kaptam   
$sql = "SELECT * FROM nevjegyek 
        WHERE (nev LIKE '%{$kifejezes}%' 
            OR cegnev LIKE '%{$kifejezes}%'
            OR email LIKE '%{$kifejezes}%'
        )
        ORDER BY nev ASC
        ";
$result = mysqli_query($dbconn, $sql);
if (@mysqli_num_rows($result) < 1) 
{
    $kimenet ="
    <article>
        <h2>Nincs ilyen a rendszerben</h2>
    </article>
    ";
}
else {
    $kimenet = "";
    while ($row = mysqli_fetch_assoc($result)){
        $kimenet .= 
            "<article>
                <img src=\"kepek/{$row['foto']}\" alt=\"{$row['foto']}\">
                <h2>{$row['nev']}</h2>
                <h3>{$row['cegnev']}</h3>
            </article>" ;
    }
}

//lapozo
$osszes = mysqli_num_rows($result);
var_dump($osszes);

$mennyi = 9;
$lapok = ceil($osszes / $mennyi);
$aktualis = "";
$honnan = "";

$lapozo = '<p>';
$lapozo .= 'Első |' ;

$lapozo .= '</p>';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stilus.css">
    <title>Document</title>
</head>
<body>
    <h1>Névjegyzék:</h1>
    <form action="" method="post">
        <input type="search" name="kifejezes" id="kifejezes">
    </form>

    <!-- lapozó -->
    <div class="container">
        <?php 
        print"$kimenet";
        ?>
    </div>

</body>
</html>