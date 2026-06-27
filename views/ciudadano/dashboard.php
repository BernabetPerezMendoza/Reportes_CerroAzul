<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel - Lunahuaná Limpia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

    <nav class="bg-emerald-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <span class="text-xl font-bold tracking-wider">Lunahuaná Limpia</span>
                <div class="flex items-center space-x-4">
                    <span class="text-sm bg-emerald-800 px-3 py-1.5 rounded-lg">👤 <?= htmlspecialchars($_SESSION['nombre']) ?></span>
                    <a href="index.php?action=logout" class="text-sm bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded-lg transition-colors font-medium">Salir</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Registrar Incidencia</h2>
                
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="bg-emerald-50 text-emerald-600 p-3 rounded-lg text-sm mb-4"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=guardar_reporte" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción del problema</label>
                        <textarea name="descripcion" rows="4" required placeholder="Ej. Hay un cúmulo de basura en la av. principal..." class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Evidencia Fotográfica</label>
                        <input type="file" name="foto" accept="image/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 rounded-lg transition-colors shadow-sm">
                        Enviar Alerta
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Historial de tus Alertas</h2>
            
            <?php if(empty($reportes)): ?>
                <div class="bg-white p-8 text-center rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-gray-500">Aún no has realizado ningún reporte urbano. ¡Tu participación es importante!</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach($reportes as $rep): ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col justify-between">
                            <img src="public/uploads/<?= htmlspecialchars($rep['foto']) ?>" alt="Evidencia" class="w-full h-44 object-cover">
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <p class="text-gray-600 text-sm line-clamp-2 mb-3"><?= htmlspecialchars($rep['descripcion']) ?></p>
                                
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full <?= $rep['estado'] === 'PENDIENTE' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' ?>">
                                        <?= $rep['estado'] ?>
                                    </span>
                                    <button onclick="abrirModal('<?= htmlspecialchars($rep['foto']) ?>', '<?= htmlspecialchars(addslashes($rep['descripcion'])) ?>', '<?= $rep['estado'] ?>')" class="text-emerald-600 hover:text-emerald-800 font-semibold text-xs tracking-wider uppercase">
                                        Ver detalles →
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="modalReporte" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 hidden backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl transform transition-all scale-95 duration-200">
            <div class="relative">
                <img id="modalImg" src="" alt="Foto Reporte" class="w-full h-64 object-cover">
                <button onclick="cerrarModal()" class="absolute top-3 right-3 bg-black/50 hover:bg-black/70 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-lg transition-colors">&times;</button>
            </div>
            <div class="p-6">
                <div class="mb-3">
                    <span id="modalEstado" class="px-2.5 py-1 text-xs font-bold rounded-full"></span>
                </div>
                <p id="modalDesc" class="text-gray-700 text-sm leading-relaxed"></p>
            </div>
        </div>
    </div>

    <script>
        function abrirModal(foto, descripcion, estado) {
            document.getElementById('modalImg').src = 'public/uploads/' + foto;
            document.getElementById('modalDesc').innerText = descripcion;
            
            const badge = document.getElementById('modalEstado');
            badge.innerText = estado;
            if(estado === 'PENDIENTE') {
                badge.className = "px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800";
            } else {
                badge.className = "px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800";
            }
            
            const modal = document.getElementById('modalReporte');
            modal.classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modalReporte').classList.add('hidden');
        }
    </script>
</body>
</html>