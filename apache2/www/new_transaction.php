<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  echo '<!DOCTYPE html>
  <html lang="es_ES">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Sesión no iniciada</title>
      <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css" />
      <link rel="stylesheet" href="style.css">
    </head>
    <body>
      <dialog open>
        <article class="container-fluid">
          <h3 class=contrast>Sesión no iniciada</h3>
          <p>Se ha dectado que usted no ha iniciado sesión.</p>
          <footer>
            <a href="./do_logout.php" class="contrast" role="button">Iniciar sesión</a>
          </footer>
        </article>
      </dialog>
    </body>
  </html>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css" />
    <link rel="stylesheet" href="style.css">
    <title>Nueva transaccion</title>
</head>
<body>
    <nav class="container-fluid">
        <ul>
            <li>
                <h3><a class=contrast>Añadir transacción</a></h3>
            </li>
        </ul>
        <ul>
            <li>
                <a href="./login.php" class="contrast">Cerrar sesión</a>
            </li>
        </ul>
    </nav>
    <main class="container">
        <article class="grid">
            <div>
                <form method="post" action="./do_new_transaction.php">
                    <label for="cantidad">Cantidad (€):</label>
                    <input type="number" min="1" id="cantidad" name="cantidad">
                    <label for="tipo">Tipo:</label>
                    <select name="tipo" id="tipo">
                        <option value="income">Income</option>
                        <option value="outcome">Outcome</option>
                    </select>
                    <!--Categorías implementadas en el formulario-->
                    <?php
                        require __DIR__ . '/../php_util/db_connection.php';
                        $mysql = get_db_connection_or_die();
                        $result = mysqli_query($mysql, "SELECT * FROM tCategory where author_id = ". $_SESSION['user_id']) or die('Query error');;
                        if (mysqli_num_rows($result)==0) {
                          echo "<p>Este usuario no tiene categorías añadidas.</p>";
                        } else {
                          echo "<label for='categoria'>Categoría:</label>";
                          echo "<select name='categoria' id='categoria'>";
                          while ($row = mysqli_fetch_array($result)) {
                              echo "<option value=".$row['id'].">".$row['name']."</option>";
                          }
                          echo "</select>";
                        }
                        mysqli_close($mysql);
                    ?>
                    <button type="submit">Añadir</button>
                </form>
                <button type="button" onclick="window.location.href='/main.php'">Cancelar</button>
            </div>
        </article>
    </main>
    <footer class="container-fluid">
		<small>Asesor financiero</small>
	</footer>
</body>
</html>
