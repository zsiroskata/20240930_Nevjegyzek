<?php
require_once( "../connect.php");
header("Content-type: application/json; charset=utf-8 ");

if (isset($_GET["id"]) && is_numeric($_GET["id"]) ) {
    $id = (int)$_GET["id"];

    $sql = "SELECT *
    FROM nevjegyek
    WHERE id = ?";

    $stmt = $dbconn ->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0){
        $contact = $result->fetch_assoc();
        echo json_encode($contact, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT);
    }

    //ha nincs ilyen névjegy:
    else{
        http_response_code(404);
        echo json_encode(array("error"=> "névjegy nem található"), JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT);
    }

}
else {
    $sql = "SELECT *
    FROM nevjegyek";
    $result = $dbconn -> query($sql);
    $contacts = [];
    while ($row = $result -> fetch_assoc()) {
        $contacts[] = $row;
    }
    echo json_encode($contacts, JSON_UNESCAPED_UNICODE, JSON_PRETTY_PRINT);

}


?>