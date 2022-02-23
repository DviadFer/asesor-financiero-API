<?php
#DEV TOOLS. ¡COMENTAR LÍNEA  3 ANTES DE HACER COMMIT!
#ini_set('display_errors', 'On'); // Something useful!
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF8">
	<link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<nav class="fluid-container">
		<img src="static/logo.png" alt="logo de asesor financiero" width="10%" height="10%">
	</nav>

	<main class="container">
		<article class="grid">
			<div>
				<!--Información básica para el usuario-->
				<hgroup>
					<h1>Login</h1>
					<h2>Inicia sesión con tu cuenta de Asesor Financiero para acceder a tus datos.</h2>
				</hgroup>

				<!--Errores que se mostraran en caso de que se produzcan-->
				<?php
					//Error que se devuelve cuando no hay ningún usuario con el mail introducido
					if (isset($_GET['login_failed_email']) == 'True') {
						echo "<p>No existe ningún usuario con registrado con ese email.</p>";
					}

					//Error que se devuelve cuando la contraseña no es correcta
					if (isset($_GET['login_failed_password']) == 'True') {
						echo "<p>La contraseña no es correcta.</p>";
					}

					//Error que se devuelve cuando se desconoce qué ha causado el error
					if (isset($_GET['login_failed_unknown']) == 'True') {
						echo "<p>Error desconido. Si el error continúa, inténtalo más tarde.</p>";
					}
				?>

				<!--Formulario de Login-->
				<form method="POST" action="do_login.php">
					<label>Email</label><br>
					<input id="email" name="email" type="email" placeholder="example@gmail.com" required><br>
					<label>Password</label><br>
					<input id="password" name="password" type="password" required><br><br>
					<input type="submit" value="Enviar">
				</form>
			</div>
			<div></div>

		</article>
	</main>
	<footer class="container-fluid">
		<small>Asesor financiero</small>
	</footer>
</body>

</html>