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
        echo '<h3><a href=./main.php class=contrast>Página principal</a></h3>';
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
      echo '<h4>Tus transacciones</h4>';
      echo "<table>";
      echo "<thead>";
      echo "<th>Id</th><th>Fecha</th><th>Cantidad</th><th>Tipo</th>";
      echo "</thead>";
      echo "<tbody>";
      // Controlo los parametros de entrada
      if (isset($_GET['category_id'])) {
        $result = mysqli_query($mysqli, "SELECT * FROM tTransaction where category_id = " . $_GET['category_id']);
      } else {
        $result = mysqli_query($mysqli, "SELECT * FROM tTransaction inner join tCategory on tTransaction.category_id = tCategory.id where tCategory.author_id = " . $_SESSION['user_id']);
      }
      // Genero la lista de transacciones
      while ($row = mysqli_fetch_array($result)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['datetime'] . '</td>';
        echo '<td>' . $row['amount'] . '</td>';
        echo '<td>' . $row['type'] . '</td>';
        echo '</tr>';
      }
      echo "</tbody>";
      echo "</table>";
      mysqli_close($mysqli);
      ?>
      <button type="button" onclick="window.location.href='/new_transaction.php'">Añadir transacción</button>
      <button type="button" onclick="window.location.href='/add_category.php'">Añadir categoría</button>
    </article>
  </main>
</body>

</html>