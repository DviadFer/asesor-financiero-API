<?php
  ini_set('display_errors', 'On');
  require __DIR__ . '/../php_util/db_connection.php';
  $mysql = get_db_connection_or_die();
  session_start();

  $category = $_POST['category'];

  if (empty($category)){
    die("Introduce todos los datos");
  }

  $result = mysqli_query($mysql, "select * from tCategory where name = '".$category."'");

  if (mysqli_num_rows($result) == 0) {
    try{
      $sql = "insert into tCategory (name, author_id) values (?, ?)";
      $stmt = $mysql -> prepare($sql);
      $stmt -> bind_param("si", $category, $_SESSION['user_id']);
      $stmt -> execute();
      $stmt -> close();
    } catch(Exception $e) {
      error_log($e);
      echo '<p>fallo de insercion</p>';
      echo '<a href="/add_category.php">Volver</a>';
    }/* Llave del catch */
      echo '<p>correcta insercion</p>';
      echo '<a href="/add_category.php">Volver</a>';
  }else{
    echo '<p>ya existe</p>';
    echo '<a href="/add_category.php">Volver</a>';
  }
    mysqli_close($mysql);
?>