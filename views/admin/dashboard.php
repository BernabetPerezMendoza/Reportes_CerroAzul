<?php

if (!isset($stats)) {
    $stats = ['total' => 0, 'pendiente' => 0, 'revisado' => 0];
}
if (!isset($reportes)) {
    $reportes = [];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Municipalidad</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen font-sans">

    <nav class="bg-slate-800 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <span class="text-xl font-bold tracking-wider text-teal-400">Control de Gestión (Admin)</span>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-slate-300">Bienvenido Administrador</span>
                    <a href="index.php?action=logout" class="text-sm bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded-lg transition-colors font-medium">Salir</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm mb-6 shadow-sm border border-emerald-100">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between">
                <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total de Reportes</span>
                <span class="text-4xl font-extrabold text-slate-800 mt-2"><?= $stats['total'] ?></span>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between border-l-4 border-l-amber-500">
                <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Reportes Sin Ver (Pendientes)</span>
                <span class="text-4xl font-extrabold text-amber-600 mt-2"><?= $stats['pendiente'] ?></span>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between border-l-4 border-l-emerald-500">
                <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Reportes Listos (Revisados)</span>
                <span class="text-4xl font-extrabold text-emerald-600 mt-2"><?= $stats['revisado'] ?></span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800">Alertas Enviadas por la Comunidad</h2>
            </div>
            
            <?php if(empty($reportes)): ?>
                <div class="p-8 text-center text-slate-500">No hay incidencias registradas en la plataforma todavía.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 text-sm font-semibold border-b border-slate-100">
                                <th class="p-4">Imagen</th>
                                <th class="p-4">Vecino / Usuario</th>
                                <th class="p-4">Descripción</th>
                                <th class="p-4">Estado</th>
                                <th class="p-4 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            <?php foreach($reportes as $row): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="p-4">
                                        <img src="public/uploads/<?= htmlspecialchars($row['foto']) ?>" alt="Incidencia" class="w-20 h-14 object-cover rounded-lg shadow-sm cursor-zoom-in" onclick="window.open('public/uploads/<?= htmlspecialchars($row['foto']) ?>', '_blank')">
                                    </td>
                                    <td class="p-4">
                                        <div class="font-semibold"><?= htmlspecialchars($row['name_user'] . ' ' . $row['father_surname_user'] . ' ' . $row['mother_surname_user']) ?></div>
                                        <div class="text-xs text-slate-400">@<?= htmlspecialchars($row['username_user']) ?></div>
                                    </td>
                                    <td class="p-4 max-w-xs truncate" title="<?= htmlspecialchars($row['descripcion']) ?>">
                                        <?= htmlspecialchars($row['descripcion']) ?>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-0.5 rounded-full font-bold text-xs <?= $row['estado'] === 'PENDIENTE' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' ?>">
                                            <?= $row['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if($row['estado'] === 'PENDIENTE'): ?>
                                            <a href="index.php?action=revisar_reporte&id=<?= $row['id'] ?>" class="bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                                Marcar Revisado
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-400 text-xs italic">Atendido ✔</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>