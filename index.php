<?php
require "connect.php";

// Keresési feltétel
$kifejezes = isset($_POST["kifejezes"]) ? $_POST["kifejezes"] : "";

// Felhasználó által választott névjegykártyák száma
$mennyit = isset($_GET["mennyit"]) ? (int)$_GET["mennyit"] : 9;

// Először összes adat megszámolása a lapozáshoz
$sql = "SELECT * 
        FROM nevjegyek
        WHERE (
        nev LIKE '%" . $kifejezes . "%'
        OR cegnev LIKE '%" . $kifejezes . "%'
        OR email LIKE '%" . $kifejezes . "%'
        )";

$result = mysqli_query($dbconn, $sql);
$osszes = mysqli_num_rows($result);

// Lapozáshoz szükséges adatok
$lapok = ceil($osszes / $mennyit);
$aktualis = isset($_GET["oldal"]) ? (int)$_GET["oldal"] : 1;
$honnan = ($aktualis - 1) * $mennyit;

// SQL lekérdezés lapozással
$sql = "SELECT * 
        FROM nevjegyek
        WHERE (
        nev LIKE '%" . $kifejezes . "%'
        OR cegnev LIKE '%" . $kifejezes . "%'
        OR email LIKE '%" . $kifejezes . "%'
        )
        ORDER BY nev ASC
        LIMIT $honnan, $mennyit";

$result = mysqli_query($dbconn, $sql);

// SQL hiba ellenőrzése
if (!$result) {
    die("SQL Hiba: " . mysqli_error($dbconn));
}

// Lapozó építése
$lapozo = '<p>';
$lapozo .= $aktualis != 1 ? '<a href="?oldal=1&mennyit=' . $mennyit . '">Első</a>' : 'Első';
$lapozo .= ($aktualis > 1 && $aktualis <= $lapok) ? '<a href="?oldal='.($aktualis-1).'&mennyit='.$mennyit.'"> | Előző</a>' : 'Előző | ';

for ($oldal = 1; $oldal <= $lapok; $oldal++) { 
    $lapozo .= ($aktualis != $oldal ? '<a href="?oldal=' . $oldal . '&mennyit='.$mennyit.'">' . $oldal . '</a> | ' : $oldal . ' | ');
}

$lapozo .= $aktualis < $lapok ? '<a href="?oldal='.($aktualis+1).'&mennyit='.$mennyit.'">Következő</a>' : 'Következő';
$lapozo .= $aktualis != $lapok ? '<a href="?oldal='.$lapok.'&mennyit='.$mennyit.'">Utolsó</a>' : 'Utolsó';
$lapozo .= '</p>';

// Adatok kiíratása
if (mysqli_num_rows($result) < 1) {
    $kimenet = "<article>
    <h2>Nincs ilyen találat a rendszerben!</h2>
    </article>\n";
} else {
    $kimenet = "";
    while ($sor = mysqli_fetch_assoc($result)) {
        $kimenet .= "<article>
        <img src=\"kepek/{$sor['foto']}\" alt=\"{$sor['nev']}\" />
        <h2>{$sor['nev']}</h2>
        <h3>{$sor['cegnev']}</h3>
        </article>\n";
    }
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stilus.css">
    <title>Névjegyzék</title>
</head>
<body>
    <h1>Névjegyzék</h1>
    <form method="post">
        <input type="search" name="kifejezes" id="kifejezes" placeholder="Keresés...">
    </form>
    
    <!-- Lapozó -->
    <div class="container">
            <form method="get">
                <select name="mennyit" id="mennyit" onchange="this.form.submit()">
                    <option value="9" <?php if ($mennyit == 9) echo 'selected'; ?>>9</option>
                    <option value="30" <?php if ($mennyit == 30) echo 'selected'; ?>>30</option>
                    <option value="60" <?php if ($mennyit == 60) echo 'selected'; ?>>60</option>
                </select>
            </form>
        <?php
        print $lapozo;
        print $kimenet;
        ?>
    </div>
</body>
</html>