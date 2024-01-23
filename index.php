<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="./css/styleadmin.css" />
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <form action="./login/procesar_login.php" method="post">
        <label for="rol">Rol:</label>
        <select name="rol" id="rol" required>
            <option value="estudiante">Estudiante</option>
            <option value="docente">Docente</option>
        </select>

        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required>

        <button type="submit">Iniciar Sesión</button>
    </form>

    <p>No tienes una cuenta? <a href="./login/crear_cuenta.php">Crear cuenta</a></p>
</body>
</html>
