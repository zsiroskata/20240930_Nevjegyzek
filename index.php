<?php
require "connect.php";
$kifejezes = isset($_POST["kifejezes"]) ? $_POST["kifejezes"] : "";

// SQL lekérdezés a szűrt adatok megcountolására
$count_sql = "SELECT COUNT(*) as total FROM nevjegyek 
              WHERE (nev LIKE '%{$kifejezes}%' 
                  OR cegnev LIKE '%{$kifejezes}%' 
                  OR email LIKE '%{$kifejezes}%')";
$count_result = mysqli_query($dbconn, $count_sql);
$total_row = mysqli_fetch_assoc($count_result);
$osszes = $total_row['total'];

$mennyi = 9;
$lapok = ceil($osszes / $mennyi);
$aktualis = isset($_GET['oldal']) ? (int)$_GET['oldal'] : 1;
$honnan = ($aktualis - 1) * $mennyi;

$sql = "SELECT * FROM nevjegyek 
        WHERE (nev LIKE '%{$kifejezes}%' 
            OR cegnev LIKE '%{$kifejezes}%' 
            OR email LIKE '%{$kifejezes}%')
        ORDER BY nev ASC
        LIMIT {$honnan}, {$mennyi}";
$result = mysqli_query($dbconn, $sql);

if (mysqli_num_rows($result) < 1) {
    $kimenet = "<article><h2>Nincs ilyen a rendszerben</h2></article>";
} else {
    $kimenet = "";
    while ($row = mysqli_fetch_assoc($result)){
        $kimenet .= 
            "<article>
                <img src=\"kepek/{$row['foto']}\" alt=\"{$row['foto']}\">
                <h2>{$row['nev']}</h2>
                <h3>{$row['cegnev']}</h3>
            </article>";
    }
}

// lapozo
$lapozo = '<p>';
$lapozo .= $aktualis != 1 ? '<a href="?oldal=1">Első</a>' : 'Első |';
$lapozo .= ($aktualis > 1) ? '<a href="?oldal=' .($aktualis - 1).'">Előző</a>' : 'Előző |';

for ($oldal = 1; $oldal <= $lapok; $oldal++) {
    $lapozo .= $aktualis != $oldal ? "<a href=\"?oldal={$oldal}\">{$oldal}</a> |" : $oldal . ' | ';
}

$lapozo .= ($aktualis < $lapok) ? '<a href="?oldal=' .($aktualis + 1).'">Következő</a> |' : 'Következő |';
$lapozo .= $aktualis < $lapok ? '<a href="?oldal=' . $lapok . '">Utolsó</a>' : 'Utolsó';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stilus.css">
    <title>Névjegyzék</title>
</head>
<body>
    <h1>Névjegyzék:</h1>
    <form action="" method="post">
        <input type="search" name="kifejezes" id="kifejezes" value="<?php echo htmlspecialchars($kifejezes); ?>">
        <button type="submit">Keresés</button>
    </form>

    <div class="container">
        <?php 
        print $lapozo;
        print $kimenet;
        ?>
    </div>
</body>
</html>
