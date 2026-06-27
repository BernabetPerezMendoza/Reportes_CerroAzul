<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Municipales - Lunahuaná</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans antialiased">
<?php if(isset($_SESSION['user_id'])): ?>
<nav class="bg-white shadow-md border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-3">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-2xl"></i>
                <span class="font-bold text-xl text-slate-800 tracking-tight">Lunahuaná Alerta</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-slate-600 hidden md:inline">Hola, <strong><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></strong></span>
                <a href="index.php?action=logout" class="bg-rose-500 hover:bg-rose-600 text-white text-sm px-4 py-2 rounded-xl transition shadow-sm font-medium">Salir</a>
            </div>
        </div>
    </div>
</nav>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
<?php endif; ?>