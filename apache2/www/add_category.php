<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  echo '<!DOCTYPE html>
  <html lang="es_ES">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Sesión no iniciada</title>
      <link
        rel="stylesheet"
        href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css"
      />
    </head>
  
    <body>
      <dialog open>
        <article>
          <h3>Sesión no iniciada</h3>
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

<html lang="es_ES">

<head>
  <meta charset="UTF8" />
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css" />
  <title>Página principal</title>
</head>

<body>
  <nav class="container-fluid">
    <ul>
      <li>
        <?php
        echo '<h3><a href=./main.php class=contrast>Añadir categoría</a></h3>';
        ?>
      </li>
    </ul>
    <ul>
      <li>
        <a href="./login.php" class="contrast">Cerrar sesión</a>
      </li>
    </ul>
  </nav>
  <main class="container">
    <article>
      <?php
      require __DIR__ . '/../php_util/db_connection.php';
      $mysqli = get_db_connection_or_die();
      // En caso de que se me haya dado una category no muestro
      // las categorias.
      if (!isset($_GET['category_id'])) {
        echo '<h4>Tus categorias</h4>';
        // Controlo los parametros de entrada
        if (isset($_SESSION['user_id']) && isset($_GET['category_id'])) {
          $result = mysqli_query($mysqli, "SELECT * FROM tCategory where author_id = " . $_SESSION['user_id'] . " and id = " . $_GET['category_id']);
          // El if de abajo es inecesario pero lo dejo por si acaso
        } elseif (isset($_SESSION['user_id'])) {
          $result = mysqli_query($mysqli, "SELECT * FROM tCategory where author_id = " . $_SESSION['user_id']);
        } elseif (isset($_GET['category_id'])) {
          $result = mysqli_query($mysqli, "SELECT * FROM tCategory where id = " . $_GET['category_id']);
        }
        // Genero la lista de categorias
        while ($row = mysqli_fetch_array($result)) {
          echo "<ul>";
          echo "<li><a href=/main.php?category_id=" . $row['id'] . '&user_id=' . $_SESSION['user_id'] . ">" . $row['name'] . "</a></li>";
          echo "</ul>";
        }
      }
      mysqli_close($mysqli);
      ?>
      <h4>Añadir categoría</h4>
      <form method="POST" action="do_add_category.php">
					<input id="category" name="category" type="text" placeholder="Tipo de gasto..." required><br>
					<input type="submit" value="Añadir">
                    <button type="button" onclick="window.location.href='/main.php'">Cancelar</button>

				</form>
    </article>
  </main>
</body>

</html>