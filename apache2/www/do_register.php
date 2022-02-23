<?php
  ini_set('display_errors', 'On');
  require __DIR__ . '/../php_util/db_connection.php';
  $mysql = get_db_connection_or_die();

  $name = $_POST['name'];
  $surname = $_POST['surname'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass2 = $_POST['pass2'];

  if (empty($name) or empty($surname) or empty($email) or empty($pass) or empty($pass2)){
    die("Introduce todos los datos");
  }

  if ($pass != $pass2){
    die("Debes poner la misma contraseña");
  }
/*
  try{
    $sql = "insert into tUser (name, surname, email, encrypted_password) values (?, ?, ?, ?)";
    $stmt = $mysql -> prepare($sql);
    $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
    $stmt -> bind_param("ssss", $name, $surname, $email, $pass);
    $stmt -> execute();
    $stmt -> close();
  } catch(Exception $e){
    error_log($e);
  }
*/

  /*$q = mysqli_query("select * from tUser where email = '$email'");*/

  $sql2 = "select * from tUser where email = ?";
  $stmt = $mysql -> prepare($sql2);
  $stmt -> bind_param("s", $email);
  $stmt -> execute();
  $result = $stmt -> get_result();
  /*echo "$sql";
  exit;*/
  if ($result -> num_rows == 1) {
    header("Location: register.php?register_failed_email=True");
    exit;
  } else {
    try {
      $sql = "insert into tUser (name, surname, email, encrypted_password) values (?, ?, ?, ?)";
      $stmt = $mysql -> prepare($sql);
      $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
      $stmt -> bind_param("ssss", $name, $surname, $email, $pass);
      $stmt -> execute();
      $stmt -> close();
    } catch(Exception $e) {
      error_log($e);
      header("Location: register.php?register_failed_unknown=True");
    }/* Llave del catch */
    header("Location: register.php?register_success=True");
  }/* Llave  del if else */

  mysqli_close($mysql);

?>
