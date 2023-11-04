<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data)) {
        echo json_encode(array("error" => "Invalid data received."));
        exit();
    }

    $COURSE = $data['COURSE'];
    $CODE = $data['CODE'];
    $INSTRUCTOR = $data['INSTRUCTOR'];
    $ROOM_NUMBER = $data['ROOM_NUMBER'];
    $SYLLABUS = $data['SYLLABUS'];
    $TIME = $data['TIME'];
    $OBJECTIVE = $data['OBJECTIVE'];

    $dbHost = '51.81.160.154';
    $dbName = 'sxv0451_qaprofile';
    $dbUser = 'sxv0451_sanjay';
    $dbPass = 'JusticeLeague';


    $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($mysqli->connect_error) {
        echo json_encode(array("error" => "Connection failed: " . $mysqli->connect_error));
        exit();
    }

    $sql = "UPDATE courses SET COURSE=?, INSTRUCTOR=?, ROOM_NUMBER=?, SYLLABUS=?, TIME=?, OBJECTIVE=? WHERE CODE=?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo json_encode(array("error" => "Prepare failed: " . $mysqli->error));
        exit();
    }

    $stmt->bind_param("ssssiss", $COURSE, $INSTRUCTOR, $ROOM_NUMBER, $SYLLABUS, $TIME, $OBJECTIVE, $CODE);

    if ($stmt->execute()) {
        echo json_encode(array("message" => "Course data updated successfully"));
    } else {
        echo json_encode(array("error" => "Error updating Course data: " . $stmt->error));
    }

    $stmt->close();
    $mysqli->close();
} else {
    header("HTTP/1.0 405 Method Not Allowed");
    echo "Method Not Allowed";
}
?>
