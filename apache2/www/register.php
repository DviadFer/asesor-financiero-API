<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página registro</title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
  <style>
    h1{
      margin-top: 5%;
      margin-left: 5%;
      position: absolute;
      top: -5%;
      left: 10%;
    }
    main.container{
      width: 85%;
      position: absolute;
      top: 7%;
      left: 5%;
    }
    p{
      margin-top: 2.5%;
      margin-left: 80%;
    }
    a{
      position: relative;
      left: 80%;
      z-index: 1;
    }
    nav.fluid-container{
      position: absolute;
      top: 2.5%;
      left: 5%;
    }
    main.container form{
      width: 55%;
      float: left;
    }
  </style>
</head>
<body>
  <?php
    if($_GET['register_success'] == 'True') {
      echo "<p>Nuevo usuario registrado</p>";
      echo "<br>";
      echo "<a href='/login.php'>Login</a>";
      echo "<br>";
    }
    if($_GET['register_failed_email'] == 'True') {
      echo "<p>Ya existe un usuario con ese email</p>";
    }
    if($_GET['register_failed_unknown'] == 'True') {
      echo "<p>El registro fallo por motivos desconocidos</p>";
    }
  ?>
  <nav class="fluid-container">
    <img src="static/Logo.png" alt="Logotipo" width="30%" height="auto">
  </nav>
  <h1>Registro</h1>
  <main class="container">
    <article>
      <form method="post" action="/do_register.php">
        <label>Nombre:
          <input id="nombre" name="name" type="text" placeholder="Nombre a registrar" required></input>
        </label>
        <label>Apellidos:
          <input id="apellidos" name="surname" type="text" placeholder="Apellidos a registrar" required></input>
        </label>
        <label>E-mail:
          <input id="email" name="email" type="text" placeholder="Email a registrar" required></input>
        </label>
        <label>Contraseña:
          <input id="pass" name="pass" type="password" placeholder="Contraseña a registrar"></input>
        </label>
        <label>Confirmar contraseña
          <input id="pass2" name="pass2" type="password" placeholder="Confirme la contraseña"></input>
        </label>
        <input type="submit" value="Enviar" class="contrast"></input>
      </form>
      <img src="static/Dinero.jpg" alt="Imagen" style="float: right; width: 450px; height: auto; display: flex; justify-content: center;">
    </article>
  </main>
</body>
</html>
