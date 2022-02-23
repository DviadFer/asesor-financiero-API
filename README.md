# Asesor Financiero - API REST

## Dependencias para visualizar el proyecto de manera local.

- Instalar PHP y la librería para Apache2.
- Instalar mariadb y crear la bases de datos `asesordb` con usuario `dev` y contraseña `1234`.
- Sustituir 'raspi' por 'localhost' en el `db_connection.php` de los proyectos:

```php
  function get_db_connection_or_die() {
    //OLD: $mysqli = new mysqli('raspi', 'dev', '1234', 'asesordb');
    $mysqli = new mysqli('localhost', 'dev', '1234', 'asesordb');
    if ($mysqli->connect_error) {
		// Mensaje de error
    }
    return $mysqli;
  }
```

> :eyes: Los datos necesarios para crear la bd están en `create-tables-asesor-financiero.sql`

## Tareas realizadas en el proyecto.

Fui encargado de implementar el registro de nuevas operaciones, a través de un nuevo formulario HTML (`apache2/www/new_transaction.php`) y su correspondiente código en PHP (`apache2/www/do_new_transaction.php`) para procesar los datos enviados e insertarlos en la base de datos.

### new_transaction.php

```php+HTML
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
```

### do_new_transaction.php

````php
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

````

