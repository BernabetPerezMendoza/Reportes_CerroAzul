<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel - Cerro Azul</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F8FAFC] min-h-screen font-sans text-slate-800 selection:bg-blue-200">

    <!-- Navbar Azul -->
    <nav class="bg-blue-900 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">Cerro Azul</span>
                </div>
                <div class="flex items-center space-x-4 sm:space-x-6">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-sm font-semibold"><?= htmlspecialchars($_SESSION['nombre']) ?></span>
                        <span class="text-[10px] text-blue-400 font-medium uppercase tracking-wider">Ciudadano</span>
                    </div>
                    
                    <a href="index.php?action=ciudadano_perfil" class="text-sm font-semibold text-blue-200 hover:text-white transition-colors">Mi Perfil</a>
                    <div class="w-px h-6 bg-blue-700/50"></div>
                    
                    <a href="index.php?action=logout" class="flex items-center gap-2 bg-red-500/10 hover:bg-red-500 hover:text-white text-red-400 px-4 py-2 rounded-lg transition-all font-medium text-sm">
                        Salir
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- COLUMNA IZQUIERDA: FORMULARIO -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 sticky top-24">
                <h3 class="text-xl font-extrabold text-slate-900 mb-1">Registrar Incidencia</h3>
                <p class="text-sm text-slate-500 mb-6">Ayuda a mantener nuestra ciudad limpia.</p>
                
                <!-- Alertas -->
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="flex items-center gap-3 bg-red-50 text-red-700 p-4 rounded-xl text-sm mb-6 shadow-sm border border-red-200">
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="flex items-center gap-3 bg-blue-50 text-blue-700 p-4 rounded-xl text-sm mb-6 shadow-sm border border-blue-200">
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?action=guardar_reporte" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Descripción del problema</label>
                        <textarea name="descripcion" rows="4" required placeholder="Ej. Hay un cúmulo de basura en la av. principal..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Evidencia Fotográfica</label>
                        <div class="relative w-full">
                            <input type="file" name="foto" accept="image/*" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl transition-all">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-xl transition-all shadow-md text-sm mt-4">
                        Enviar Alerta
                    </button>
                </form>
            </div>
        </div>

        <!-- COLUMNA DERECHA: HISTORIAL DE REPORTES EN TABLA -->
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-extrabold text-slate-900 mb-6">Historial de tus Alertas</h2>
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full">
                <?php if(empty($reportes)): ?>
                    <div class="p-16 text-center text-slate-400">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-slate-500 font-medium text-lg">Aún no has realizado ningún reporte.</p>
                        <p class="text-sm text-slate-400 mt-1">¡Tu participación ciudadana es importante!</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-200">
                                    <th class="p-4 sm:p-5 font-semibold w-20 sm:w-24">Evidencia</th>
                                    <th class="p-5 font-semibold hidden md:table-cell">Detalle del Problema</th>
                                    <th class="p-4 sm:p-5 font-semibold text-center">Fecha</th>
                                    <th class="p-5 font-semibold text-center hidden md:table-cell">Estado</th>
                                    <th class="p-4 sm:p-5 font-semibold text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-reportes-body" class="divide-y divide-slate-100 text-sm text-slate-600">
                                <?php foreach($reportes as $index => $row): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors fila-reporte hidden" data-index="<?= $index ?>">
                                        <td class="p-4 sm:p-5 align-top">
                                            <div class="w-16 h-12 sm:w-20 sm:h-14 rounded-lg overflow-hidden shadow-sm border border-slate-100">
                                                <img src="public/uploads/<?= htmlspecialchars($row['foto']) ?>" class="w-full h-full object-cover">
                                            </div>
                                        </td>
                                        <td class="p-5 align-top hidden md:table-cell">
                                            <p class="line-clamp-2 text-sm leading-relaxed text-slate-700 font-medium" title="<?= htmlspecialchars($row['descripcion']) ?>">
                                                <?= htmlspecialchars($row['descripcion']) ?>
                                            </p>
                                        </td>
                                        <!-- En móvil y desktop esta columna siempre se verá -->
                                        <td class="p-4 sm:p-5 align-middle text-center text-xs text-slate-500 font-medium">
                                            <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                                        </td>
                                        <td class="p-5 align-middle text-center hidden md:table-cell">
                                            <span class="px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider <?= $row['estado'] === 'PENDIENTE' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' ?>">
                                                <?= $row['estado'] ?>
                                            </span>
                                        </td>
                                        <td class="p-4 sm:p-5 align-middle text-center">
                                            <!-- Botón solo para Móvil -->
                                            <button onclick="abrirModal('<?= htmlspecialchars($row['foto']) ?>', '<?= htmlspecialchars(addslashes($row['descripcion'])) ?>', '<?= $row['estado'] ?>', '<?= date('d/m/Y', strtotime($row['created_at'])) ?>')" class="md:hidden bg-slate-100 border border-slate-200 hover:bg-slate-200 text-slate-700 w-full px-3 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                Ver Más
                                            </button>
                                            <!-- Botones para Escritorio -->
                                            <div class="hidden md:flex justify-center gap-2">
                                                <button onclick="abrirModal('<?= htmlspecialchars($row['foto']) ?>', '<?= htmlspecialchars(addslashes($row['descripcion'])) ?>', '<?= $row['estado'] ?>', '<?= date('d/m/Y', strtotime($row['created_at'])) ?>')" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                    Detalles
                                                </button>
                                                <button onclick="verImagenDirecta('<?= htmlspecialchars($row['foto']) ?>')" class="bg-blue-50 border border-blue-100 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                    Ver Foto
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginador Reportes -->
                    <div class="p-4 sm:p-5 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <span class="text-xs sm:text-sm text-slate-500 font-medium">Mostrando <span id="pag-info-reportes" class="font-bold text-slate-800"></span> registros</span>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button onclick="cambiarPaginaReportes(-1)" id="btn-prev-reportes" class="flex-1 sm:flex-none px-4 py-2 border border-slate-200 bg-white rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all">Anterior</button>
                            <button onclick="cambiarPaginaReportes(1)" id="btn-next-reportes" class="flex-1 sm:flex-none px-4 py-2 border border-slate-200 bg-white rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all">Siguiente</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- MODAL 1: DETALLES DEL REPORTE -->
    <div id="modalReporte" class="fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4 hidden backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl relative flex flex-col max-h-[90vh]">
            <button onclick="cerrarModal()" class="absolute top-4 right-4 text-white bg-black/40 hover:bg-black/70 rounded-full w-8 h-8 flex items-center justify-center transition-colors z-10">&times;</button>
            <img id="modalImg" src="" alt="Foto Reporte" class="w-full h-48 sm:h-56 object-cover flex-shrink-0">
            <div class="p-6 overflow-y-auto flex-1">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Estado Actual</h4>
                        <span id="modalEstado" class="px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider mt-1 inline-block"></span>
                    </div>
                    <div class="text-right">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fecha de Envío</h4>
                        <span id="modalFecha" class="text-slate-800 font-bold text-sm mt-1 inline-block"></span>
                    </div>
                </div>
                <div class="mb-4">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Descripción de la incidencia</h4>
                    <p id="modalDesc" class="text-slate-600 text-sm leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100"></p>
                </div>
                
                <div class="pt-2 border-t border-slate-100">
                    <button onclick="verImagenCompleta()" class="w-full bg-blue-50 border border-blue-100 hover:bg-blue-100 text-blue-700 px-4 py-2.5 rounded-xl text-sm font-bold transition-all text-center">
                        Ver Fotografía Completa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: IMAGEN PANTALLA COMPLETA -->
    <div id="modalImagen" class="fixed inset-0 bg-black/95 z-50 hidden flex items-center justify-center p-4 backdrop-blur-md">
        <button onclick="cerrarModalImagen()" class="absolute top-6 right-6 text-white hover:text-red-400 bg-white/10 hover:bg-white/20 rounded-full w-12 h-12 flex items-center justify-center text-2xl transition-colors">&times;</button>
        <img id="modalImgFull" src="" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl border border-white/10">
    </div>

    <script>
        // === PAGINADOR JS PARA REPORTES ===
        const reportesRows = document.querySelectorAll('.fila-reporte');
        const itemsPerPage = 10;
        let currentPage = 1;
        const totalPages = Math.ceil(reportesRows.length / itemsPerPage);

        function actualizarPaginador() {
            if (reportesRows.length === 0) return;
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;

            reportesRows.forEach((row, index) => {
                if (index >= start && index < end) row.classList.remove('hidden');
                else row.classList.add('hidden');
            });

            document.getElementById('pag-info-reportes').innerText = `${start + 1} a ${Math.min(end, reportesRows.length)} de ${reportesRows.length}`;
            document.getElementById('btn-prev-reportes').disabled = currentPage === 1;
            document.getElementById('btn-next-reportes').disabled = currentPage === totalPages || totalPages === 0;
        }

        function cambiarPaginaReportes(dir) {
            currentPage += dir;
            actualizarPaginador();
        }
        document.addEventListener('DOMContentLoaded', actualizarPaginador);

        // === MODALES ===
        let rutaImagenActual = '';

        function abrirModal(foto, descripcion, estado, fecha) {
            rutaImagenActual = 'public/uploads/' + foto;
            
            document.getElementById('modalImg').src = rutaImagenActual;
            document.getElementById('modalDesc').innerText = descripcion;
            document.getElementById('modalFecha').innerText = fecha;
            
            const badge = document.getElementById('modalEstado');
            badge.innerText = estado;
            if(estado === 'PENDIENTE') {
                badge.className = "px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider mt-1 inline-block bg-amber-100 text-amber-700";
            } else {
                badge.className = "px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider mt-1 inline-block bg-blue-100 text-blue-700";
            }
            
            document.getElementById('modalReporte').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modalReporte').classList.add('hidden');
        }

        function verImagenCompleta() {
            document.getElementById('modalImgFull').src = rutaImagenActual;
            document.getElementById('modalImagen').classList.remove('hidden');
        }

        function verImagenDirecta(foto) {
            rutaImagenActual = 'public/uploads/' + foto;
            verImagenCompleta();
        }

        function cerrarModalImagen() {
            document.getElementById('modalImagen').classList.add('hidden');
        }
    </script>
</body>
</html>