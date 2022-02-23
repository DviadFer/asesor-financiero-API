<?php
    ini_set('display_errors', 'On');
    require __DIR__ . '/../php_util/db_connection.php';
    $mysql = get_db_connection_or_die();

    $amount = $_POST['cantidad'];
    $type = $_POST['tipo'];
    $category_id = $_POST['categoria'];

    try {
        $sql = "INSERT INTO tTransaction (amount, category_id, type) values (?, ?, ?)";
        $stmt = $mysql -> prepare($sql);
        $stmt -> bind_param("iis", $amount, $category_id, $type);
        $stmt -> execute();
        $stmt -> close();
    } catch  (Exception $e) {
        error_log($e);
    }
    header('Location:./main.php');
?>
