<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Sistema de Alertas de Cerro Azul</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-teal-50 to-emerald-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-emerald-800 tracking-tight">Cerro Azul Limpia</h1>
            <p class="text-gray-500 mt-2">Reporta incidencias en tu comunidad</p>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-emerald-50 text-emerald-600 p-3 rounded-lg text-sm mb-4">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=login_process" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de Usuario</label>
                <input type="text" name="username" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition-colors shadow-md shadow-emerald-200">
                Iniciar Sesión
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">¿No tienes cuenta? <a href="index.php?action=register" class="text-emerald-600 font-semibold hover:underline">Regístrate usando tu DNI</a></p>
        </div>
    </div>

</body>
</html>