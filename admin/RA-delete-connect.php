<?php
    require_once('../connect.php');
    header('Content-Type: application/json; charset=utf8');

    if ($_SERVER['REQUEST_METHODE'] == 'GET' && isset($_GET['id']) && is_numeric($_GET['id'])) 
    {
        $id = (int) $_GET['id'];
        print_r($id);
        $sql = "DELETE FROM nevjegyek WHERE id=?";
        /**
         

    @var $dbconn: ez az objektum a már létező adatbázis kapcsolatot tartalmazza;
        @method prepare(): Ezzel a metódussal lekérdjük az sql utasítást de nem fut le*/$stmt = $dbconn -> prepare($sql);$stmt -> bind_param('i', $id);
            if ($stmt -> execute()){
                header('LOCATION: Ra-angular-list.html');
                exit();}
            else{
                http_response_code(500);
                echo json_encode(['error'=> 'adatbázis hiba: ' . mysqli_error($dbconn)], JSON_UNESCAPED_UNICODE);}}
        else{
            http_response_code(400);
            echo json_encode(['error'=> 'Érvénytelen '], JSON_UNESCAPED_UNICODE);}
            
?>