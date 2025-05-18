<?php
require_once '../config/db.php';
session_start();

$lang = $_GET['lang'] ?? 'es';
$errores = [];

// Verificar si ya hay sesión o cookie activa
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php?lang=$lang");
    exit;
} elseif (isset($_COOKIE['usuario_id'])) {
    $_SESSION['usuario_id'] = $_COOKIE['usuario_id'];
    $_SESSION['usuario_nombre'] = $_COOKIE['usuario_nombre'];
    
    // Restaurar estado de administrador desde cookie si existe
    if (isset($_COOKIE['es_admin'])) {
        $_SESSION['es_admin'] = $_COOKIE['es_admin'];
    }
    
    header("Location: ../index.php?lang=$lang");
    exit;
}

// Mostrar mensaje si viene de registro
$registroExitoso = isset($_GET['registro']) && $_GET['registro'] === 'exito';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $recordarme = isset($_POST['recordarme']);

    if (!$usuario || !$contrasena) {
        $errores[] = $lang === 'es' ? "Usuario y contraseña son obligatorios." : "Username and password are required.";
    } else {
        // Primero, verificar si el usuario es un administrador
        $stmtAdmin = $pdo->prepare("SELECT * FROM admin WHERE nombre = :usuario OR correo = :usuario LIMIT 1");
        $stmtAdmin->execute(['usuario' => $usuario]);
        $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

        if ($admin && $admin['contrasena'] === $contrasena) {
            // Usuario es admin y la contraseña coincide directamente (sin hash)
            $_SESSION['usuario_id'] = $admin['id_admin'];
            $_SESSION['usuario_nombre'] = $admin['nombre'];
            $_SESSION['es_admin'] = true;

            // Si eligió recordar sesión
            if ($recordarme) {
                setcookie("usuario_id", $admin['id_admin'], time() + (30 * 24 * 60 * 60), "/");
                setcookie("usuario_nombre", $admin['nombre'], time() + (30 * 24 * 60 * 60), "/");
                setcookie("es_admin", true, time() + (30 * 24 * 60 * 60), "/");
            }

            header("Location: ../index.php?lang=$lang");
            exit;
        } else {
            // Si no es admin o la contraseña no coincide, verifica usuarios regulares
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = :usuario OR correo = :usuario LIMIT 1");
            $stmt->execute(['usuario' => $usuario]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($contrasena, $user['contrasena'])) {
                // Iniciar sesión para usuario regular
                $_SESSION['usuario_id'] = $user['id_usuario'];
                $_SESSION['usuario_nombre'] = $user['nombre'];
                $_SESSION['es_admin'] = false;

                // Si eligió recordar sesión
                if ($recordarme) {
                    setcookie("usuario_id", $user['id_usuario'], time() + (30 * 24 * 60 * 60), "/");
                    setcookie("usuario_nombre", $user['nombre'], time() + (30 * 24 * 60 * 60), "/");
                    setcookie("es_admin", false, time() + (30 * 24 * 60 * 60), "/");
                }

                header("Location: ../index.php?lang=$lang");
                exit;
            } else {
                $errores[] = $lang === 'es' ? "Usuario o contraseña incorrectos." : "Incorrect username or password.";
            }
        }
    }
}

// Texto según idioma
$textos = [
    'es' => [
        'titulo' => 'Iniciar sesión',
        'correo_usuario' => 'Correo o usuario',
        'contrasena' => 'Contraseña',
        'recordarme' => 'Recordarme',
        'olvide' => '¿Olvidé mi contraseña?',
        'btn_login' => 'Iniciar sesión',
        'no_cuenta' => '¿No tienes cuenta? ¡Regístrate!',
        'volver' => 'Volver a la página principal',
    ],
    'en' => [
        'titulo' => 'Log in',
        'correo_usuario' => 'Email or username',
        'contrasena' => 'Password',
        'recordarme' => 'Remember me',
        'olvide' => 'Forgot my password?',
        'btn_login' => 'Log in',
        'no_cuenta' => 'Don\'t have an account? Register!',
        'volver' => 'Back to homepage',
    ]
];

$txt = $textos[$lang] ?? $textos['es'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $txt['titulo'] ?></title>
  <link rel="stylesheet" href="../views/css/login.css">
  <link rel="stylesheet" href="../views/css/font/font.css">
  <link rel="stylesheet" href="../views/css/auth-transitions.css">
  <script defer src="../views/js/auth-carousel.js"></script>
  <script defer src="../views/js/auth-transition.js"></script>
  <style>
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }
    .password-wrapper {
      position: relative;
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="auth-container">
    <!-- IZQUIERDA: Carrusel -->
    <div class="pane carousel-pane">
      <?php foreach (range(1,5) as $n): ?>
        <div class="slide <?= $n===1 ? 'is-active' : '' ?>">
          <img src="../carousel-fotos/<?= $n ?>.jpg" alt="Slide <?= $n ?>">
        </div>
      <?php endforeach ?>
    </div>

    <!-- DERECHA: Formulario -->
    <div class="pane form-pane">
      <h2><?= $txt['titulo'] ?></h2>

      <?php if ($registroExitoso): ?>
        <div class="exito"><?= $lang === 'es' ? '¡Registro exitoso! Ahora puedes iniciar sesión.' : 'Registration successful! You can now log in.' ?></div>
      <?php endif; ?>

      <?php if (!empty($errores)): ?>
        <div class="errores">
          <ul>
            <?php foreach ($errores as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php?lang=<?= $lang ?>">
        <input type="text" name="usuario" placeholder="<?= $txt['correo_usuario'] ?>" required value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>">

        <div class="password-wrapper">
          <input type="password" id="contrasena" name="contrasena" placeholder="<?= $txt['contrasena'] ?>" required>
          <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>

        <label>
          <input type="checkbox" name="recordarme" <?= isset($_POST['recordarme']) ? 'checked' : '' ?>>
          <span><?= $txt['recordarme'] ?></span>
        </label>

        <a class="forgot" href="#"><?= $txt['olvide'] ?></a>
        <button type="submit" class="btn-login"><?= $txt['btn_login'] ?></button>
      </form>

      <div class="links">
        <a href="registro.php?lang=<?= $lang ?>" class="switch"><?= $txt['no_cuenta'] ?></a>
        <a href="../index.php?lang=<?= $lang ?>" class="back"><?= $txt['volver'] ?></a>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const pass = document.getElementById("contrasena");
      pass.type = (pass.type === "password") ? "text" : "password";
    }
  </script>
</body>
</html>