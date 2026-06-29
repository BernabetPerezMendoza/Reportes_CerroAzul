<?php
if (!isset($stats)) $stats = ['total' => 0, 'pendiente' => 0, 'revisado' => 0];
if (!isset($reportes)) $reportes = [];
if (!isset($usuarios)) $usuarios = []; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Cerro Azul Limpio</title>
    
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

    <nav class="bg-blue-900 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-500/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">Cerro Azul<span class="text-blue-400">Admin</span></span>
                </div>
                <div class="flex items-center space-x-4 sm:space-x-6">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-sm font-semibold"><?= htmlspecialchars($_SESSION['nombre'] ?? 'Administrador') ?></span>
                        <span class="text-[10px] text-blue-400 font-medium uppercase tracking-wider">Admin</span>
                    </div>
                    
                    <a href="index.php?action=admin_perfil" class="text-sm font-semibold text-blue-200 hover:text-white transition-colors">Mi Perfil</a>
                    <div class="w-px h-6 bg-blue-700/50"></div>
                    
                    <a href="index.php?action=logout" class="flex items-center gap-2 bg-red-500/10 hover:bg-red-500 hover:text-white text-red-400 px-4 py-2 rounded-lg transition-all font-medium text-sm">
                        Salir
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="flex items-center gap-3 bg-blue-50 text-blue-700 p-4 rounded-xl text-sm mb-6 shadow-sm border border-blue-200">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="flex items-center gap-3 bg-red-50 text-red-700 p-4 rounded-xl text-sm mb-6 shadow-sm border border-red-200">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Histórico</p>
                <p class="text-4xl font-extrabold text-slate-800 mt-2"><?= $stats['total'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400"></div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Por Revisar</p>
                <p class="text-4xl font-extrabold text-amber-500 mt-2"><?= $stats['pendiente'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-400"></div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Atendidos</p>
                <p class="text-4xl font-extrabold text-blue-600 mt-2"><?= $stats['revisado'] ?></p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex w-full sm:w-auto bg-slate-200/50 p-1 rounded-xl">
                <button onclick="cambiarTab('reportes')" id="btn-tab-reportes" class="flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-sm">Reportes</button>
                <button onclick="cambiarTab('usuarios')" id="btn-tab-usuarios" class="flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-semibold transition-all text-slate-500 hover:text-slate-900">Usuarios</button>
            </div>
            <button onclick="abrirModalRegistro()" class="w-full sm:w-auto bg-blue-700 hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-md flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Registrar Usuario
            </button>
        </div>

        <div id="tab-reportes" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden w-full">
            <?php if(empty($reportes)): ?>
                <div class="p-16 text-center text-slate-400">Bandeja vacía. No hay incidencias registradas en Cerro Azul.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="p-4 sm:p-5 font-semibold w-20 sm:w-24">Evidencia</th>
                                <th class="p-4 sm:p-5 font-semibold hidden md:table-cell">Ciudadano</th>
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
                                    <td class="p-4 sm:p-5 align-top hidden md:table-cell">
                                        <div class="font-bold text-slate-800 text-sm sm:text-base"><?= htmlspecialchars($row['name_user']) ?></div>
                                        <div class="text-slate-500 text-[10px] sm:text-xs"><?= htmlspecialchars($row['father_surname_user'] . ' ' . $row['mother_surname_user']) ?></div>
                                        <div class="text-blue-600 text-[10px] sm:text-xs font-semibold mt-1">@<?= htmlspecialchars($row['username_user']) ?></div>
                                    </td>
                                    <td class="p-5 align-top hidden md:table-cell">
                                        <p class="line-clamp-2 text-sm leading-relaxed" title="<?= htmlspecialchars($row['descripcion']) ?>">
                                            <?= htmlspecialchars($row['descripcion']) ?>
                                        </p>
                                    </td>
                                    <td class="p-4 sm:p-5 align-middle text-center text-xs text-slate-500 font-medium">
                                        <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                                    </td>
                                    <td class="p-5 align-middle text-center hidden md:table-cell">
                                        <span class="px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider <?= $row['estado'] === 'PENDIENTE' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' ?>">
                                            <?= $row['estado'] ?>
                                        </span>
                                    </td>
                                    <td class="p-4 sm:p-5 align-middle text-center">
                                        <button onclick="verDetalles('<?= htmlspecialchars($row['foto']) ?>', '<?= htmlspecialchars(addslashes($row['descripcion'])) ?>', '<?= htmlspecialchars(addslashes($row['name_user'] . ' ' . $row['father_surname_user'])) ?>', '<?= $row['estado'] ?>', '<?= date('d/m/Y', strtotime($row['created_at'])) ?>', <?= $row['id'] ?>)" class="md:hidden bg-slate-100 border border-slate-200 hover:bg-slate-200 text-slate-700 w-full px-3 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm">
                                            Ver Más
                                        </button>
                                        <div class="hidden md:flex justify-center gap-2">
                                            <button onclick="verDetalles('<?= htmlspecialchars($row['foto']) ?>', '<?= htmlspecialchars(addslashes($row['descripcion'])) ?>', '<?= htmlspecialchars(addslashes($row['name_user'] . ' ' . $row['father_surname_user'])) ?>', '<?= $row['estado'] ?>', '<?= date('d/m/Y', strtotime($row['created_at'])) ?>', <?= $row['id'] ?>)" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                Detalles
                                            </button>
                                            <button onclick="verImagenCompleta('<?= htmlspecialchars($row['foto']) ?>')" class="bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 text-indigo-700 px-3 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                Ver Foto
                                            </button>
                                            <?php if($row['estado'] === 'PENDIENTE'): ?>
                                                <a href="index.php?action=revisar_reporte&id=<?= $row['id'] ?>" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                                                    Atender
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 sm:p-5 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <span class="text-xs sm:text-sm text-slate-500 font-medium">Mostrando <span id="pag-info-reportes" class="font-bold text-slate-800"></span> registros</span>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <button onclick="cambiarPaginaReportes(-1)" id="btn-prev-reportes" class="flex-1 sm:flex-none px-4 py-2 border border-slate-200 bg-white rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all">Anterior</button>
                        <button onclick="cambiarPaginaReportes(1)" id="btn-next-reportes" class="flex-1 sm:flex-none px-4 py-2 border border-slate-200 bg-white rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all">Siguiente</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div id="tab-usuarios" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hidden w-full">
            <?php if(empty($usuarios)): ?>
                <div class="p-16 text-center text-slate-400">No hay usuarios registrados.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-200">
                                <th class="p-4 sm:p-5 font-semibold hidden md:table-cell">DNI</th>
                                <th class="p-4 sm:p-5 font-semibold">Nombre Completo</th>
                                <th class="p-5 font-semibold hidden md:table-cell">Usuario (Login)</th>
                                <th class="p-5 font-semibold text-center hidden md:table-cell">Rol</th>
                                <th class="p-4 sm:p-5 font-semibold text-center">Fecha Registro</th>
                                <th class="p-4 sm:p-5 font-semibold text-center md:hidden">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                            <?php foreach($usuarios as $user): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-4 sm:p-5 font-mono text-slate-500 hidden md:table-cell"><?= htmlspecialchars($user['dni_user']) ?></td>
                                    <td class="p-4 sm:p-5 font-bold text-slate-800 text-sm sm:text-base">
                                        <?= htmlspecialchars($user['name_user'] . ' ' . $user['father_surname_user'] . ' ' . $user['mother_surname_user']) ?>
                                    </td>
                                    <td class="p-5 text-blue-600 font-semibold hidden md:table-cell">@<?= htmlspecialchars($user['username_user']) ?></td>
                                    <td class="p-5 text-center hidden md:table-cell">
                                        <span class="px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider <?= (int)$user['rol_user'] === 1 ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                                            <?= (int)$user['rol_user'] === 1 ? 'Admin' : 'Ciudadano' ?>
                                        </span>
                                    </td>
                                    <td class="p-4 sm:p-5 align-middle text-center text-xs text-slate-500 font-medium">
                                        <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                    </td>
                                    <td class="p-4 sm:p-5 align-middle text-center md:hidden">
                                        <button onclick="verUsuarioDetalles('<?= htmlspecialchars($user['dni_user']) ?>', '<?= htmlspecialchars(addslashes($user['name_user'] . ' ' . $user['father_surname_user'] . ' ' . $user['mother_surname_user'])) ?>', '<?= htmlspecialchars($user['username_user']) ?>', <?= (int)$user['rol_user'] ?>, '<?= date('d/m/Y', strtotime($user['created_at'])) ?>')" class="bg-slate-100 border border-slate-200 hover:bg-slate-200 text-slate-700 w-full px-3 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm">
                                            Ver Más
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="modalRegistroUsuario" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl p-6 relative">
            <button onclick="cerrarModalRegistro()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-800 bg-slate-100 rounded-full w-8 h-8 flex items-center justify-center transition-colors">&times;</button>
            <h3 class="text-xl font-extrabold text-slate-900 mb-1">Registrar Usuario</h3>
            <p class="text-sm text-slate-500 mb-6">Valida el DNI para autocompletar los datos.</p>
            
            <form action="index.php?action=admin_crear_usuario" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">DNI del usuario</label>
                    <div class="flex gap-2">
                        <input type="text" id="dni_input" name="dni" maxlength="8" required onkeyup="validarDNIExistente()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-medium">
                        <button type="button" onclick="validarDNI()" id="btn_validar" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">Buscar</button>
                    </div>
                    <span id="dni_mensaje" class="text-xs mt-1 block hidden"></span>
                </div>

                <div id="campos_nombres" class="hidden space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nombres Obtenidos</label>
                        <input type="text" id="nombres_input" name="nombres" readonly class="w-full bg-transparent text-sm font-bold text-slate-800 outline-none mt-1">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ap. Paterno</label>
                            <input type="text" id="paterno_input" name="paterno" readonly class="w-full bg-transparent text-sm font-bold text-slate-800 outline-none mt-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ap. Materno</label>
                            <input type="text" id="materno_input" name="materno" readonly class="w-full bg-transparent text-sm font-bold text-slate-800 outline-none mt-1">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre de Usuario (Login)</label>
                    <input type="text" id="username_input" name="username" required onkeyup="validarUsername()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-medium">
                    <span id="username_mensaje" class="text-xs mt-1 block hidden"></span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Contraseña Inicial</label>
                    <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rol de Acceso</label>
                    <select name="rol" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium bg-white">
                        <option value="2">Ciudadano</option>
                        <option value="1">Administrador</option>
                    </select>
                </div>
                <button type="submit" id="btn_submit" disabled class="w-full bg-slate-900 disabled:bg-slate-300 hover:bg-slate-800 text-white font-bold py-3 rounded-xl transition-all shadow-md text-sm mt-4">
                    Crear Cuenta
                </button>
            </form>
        </div>
    </div>

    <div id="modalAdmin" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl relative flex flex-col max-h-[90vh]">
            <button onclick="cerrarModalDetalles()" class="absolute top-4 right-4 text-white bg-black/40 hover:bg-black/70 rounded-full w-8 h-8 flex items-center justify-center transition-colors z-10">&times;</button>
            <img id="m_foto" src="" alt="Evidencia" class="w-full h-48 sm:h-56 object-cover flex-shrink-0">
            <div class="p-6 overflow-y-auto flex-1">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reportado por</h4>
                        <p id="m_ciudadano" class="text-slate-800 font-extrabold text-lg mt-1"></p>
                    </div>
                    <span id="m_estado" class="px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider mt-2"></span>
                </div>
                <div class="mb-4">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Descripción de la incidencia</h4>
                    <p id="m_desc" class="text-slate-600 text-sm leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100"></p>
                </div>
                <div class="text-right mb-4">
                    <span class="text-xs text-slate-400 font-medium">Fecha: <span id="m_fecha" class="text-slate-600"></span></span>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-2 border-t border-slate-100">
                    <button id="m_btn_foto" class="flex-1 bg-blue-50 border border-blue-100 hover:bg-blue-100 text-blue-700 px-4 py-2.5 rounded-xl text-sm font-bold transition-all text-center">
                        Ver Fotografía Completa
                    </button>
                    <a id="m_btn_atender" href="" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all text-center hidden">
                        Atender Reporte
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="modalUsuarioDetalle" class="fixed inset-0 bg-slate-900/60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl p-6 relative">
            <button onclick="cerrarModalUsuario()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-800 bg-slate-100 rounded-full w-8 h-8 flex items-center justify-center transition-colors">&times;</button>
            <h3 class="text-lg font-extrabold text-slate-900 mb-6">Detalles del Usuario</h3>
            
            <div class="space-y-4">
                <div>
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nombre Completo</h4>
                    <p id="mu_nombre" class="text-slate-800 font-bold text-base mt-1"></p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">DNI</h4>
                    <p id="mu_dni" class="text-slate-600 font-medium text-sm mt-1"></p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Usuario (Login)</h4>
                    <p id="mu_username" class="text-blue-600 font-bold text-sm mt-1"></p>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <div>
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fecha de Registro</h4>
                        <p id="mu_fecha" class="text-slate-600 font-medium text-sm mt-1"></p>
                    </div>
                    <span id="mu_rol" class="px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider"></span>
                </div>
            </div>
        </div>
    </div>

    <div id="modalImagen" class="fixed inset-0 bg-black/95 z-50 hidden flex items-center justify-center p-4 backdrop-blur-md">
        <button onclick="cerrarModalImagen()" class="absolute top-6 right-6 text-white hover:text-red-400 bg-white/10 hover:bg-white/20 rounded-full w-12 h-12 flex items-center justify-center text-2xl transition-colors">&times;</button>
        <img id="m_foto_full" src="" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl border border-white/10">
    </div>

    <script>
        function cambiarTab(tab) {
            const btnReportes = document.getElementById('btn-tab-reportes');
            const btnUsuarios = document.getElementById('btn-tab-usuarios');
            const secReportes = document.getElementById('tab-reportes');
            const secUsuarios = document.getElementById('tab-usuarios');

            if (tab === 'reportes') {
                secReportes.classList.remove('hidden'); secUsuarios.classList.add('hidden');
                btnReportes.className = 'flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-sm';
                btnUsuarios.className = 'flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-semibold transition-all text-slate-500 hover:text-slate-900';
            } else {
                secUsuarios.classList.remove('hidden'); secReportes.classList.add('hidden');
                btnUsuarios.className = 'flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-sm';
                btnReportes.className = 'flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-semibold transition-all text-slate-500 hover:text-slate-900';
            }
        }

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

        function abrirModalRegistro() { document.getElementById('modalRegistroUsuario').classList.remove('hidden'); }
        function cerrarModalRegistro() { document.getElementById('modalRegistroUsuario').classList.add('hidden'); }
        function cerrarModalDetalles() { document.getElementById('modalAdmin').classList.add('hidden'); }
        function cerrarModalImagen() { document.getElementById('modalImagen').classList.add('hidden'); }
        function cerrarModalUsuario() { document.getElementById('modalUsuarioDetalle').classList.add('hidden'); }

        function verDetalles(foto, descripcion, ciudadano, estado, fecha, idReporte) {
            document.getElementById('m_foto').src = 'public/uploads/' + foto;
            document.getElementById('m_ciudadano').innerText = ciudadano;
            document.getElementById('m_desc').innerText = descripcion;
            document.getElementById('m_fecha').innerText = fecha;
            
            const badge = document.getElementById('m_estado');
            badge.innerText = estado;
            badge.className = estado === 'PENDIENTE' 
                ? "px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider mt-2 bg-amber-100 text-amber-700"
                : "px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider mt-2 bg-blue-100 text-blue-700";

            document.getElementById('m_btn_foto').onclick = function() { verImagenCompleta(foto); };
            
            const btnAtender = document.getElementById('m_btn_atender');
            if(estado === 'PENDIENTE') {
                btnAtender.href = 'index.php?action=revisar_reporte&id=' + idReporte;
                btnAtender.classList.remove('hidden');
            } else {
                btnAtender.classList.add('hidden');
            }

            document.getElementById('modalAdmin').classList.remove('hidden');
        }

        function verUsuarioDetalles(dni, nombre, username, rol, fecha) {
            document.getElementById('mu_dni').innerText = dni;
            document.getElementById('mu_nombre').innerText = nombre;
            document.getElementById('mu_username').innerText = '@' + username;
            document.getElementById('mu_fecha').innerText = fecha;
            
            const badge = document.getElementById('mu_rol');
            badge.innerText = rol === 1 ? 'Admin' : 'Ciudadano';
            badge.className = rol === 1 
                ? "px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider mt-2 bg-purple-100 text-purple-700"
                : "px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider mt-2 bg-blue-100 text-blue-700";

            document.getElementById('modalUsuarioDetalle').classList.remove('hidden');
        }

        function verImagenCompleta(foto) {
            document.getElementById('m_foto_full').src = 'public/uploads/' + foto;
            document.getElementById('modalImagen').classList.remove('hidden');
        }

        let isDniValid = false;
        let isUsernameValid = false;
        let dniYaRegistrado = false;

        function verificarBotonSubmit() {
            document.getElementById('btn_submit').disabled = !(isDniValid && isUsernameValid);
        }

        async function validarDNI() {
            if (dniYaRegistrado) return;

            const inputDNI = document.getElementById('dni_input').value;
            const btn = document.getElementById('btn_validar');
            const mensaje = document.getElementById('dni_mensaje');
            const camposNombres = document.getElementById('campos_nombres');
            
            if (inputDNI.length !== 8) {
                alert("El DNI debe tener 8 dígitos."); return;
            }

            btn.innerText = "Buscando..."; btn.disabled = true;
            mensaje.classList.add('hidden'); camposNombres.classList.add('hidden');
            isDniValid = false; verificarBotonSubmit();

            try {
                const response = await fetch('index.php?action=consultar_dni_ajax&dni=' + inputDNI);
                const result = await response.json();

                if (result.success) {
                    document.getElementById('nombres_input').value = result.data.nombres;
                    document.getElementById('paterno_input').value = result.data.apellido_paterno;
                    document.getElementById('materno_input').value = result.data.apellido_materno;
                    camposNombres.classList.remove('hidden');
                    mensaje.innerText = "✓ Identidad verificada con éxito en RENIEC";
                    mensaje.className = "text-[10px] mt-1 block text-blue-600 font-bold uppercase tracking-wide";
                    isDniValid = true;
                } else {
                    mensaje.innerText = "❌ " + result.message;
                    mensaje.className = "text-[10px] mt-1 block text-red-600 font-bold uppercase tracking-wide";
                }
            } catch (error) {
                mensaje.innerText = "Error de conexión al consultar.";
                mensaje.className = "text-xs mt-1 block text-red-600";
            }
            btn.innerText = "Buscar"; btn.disabled = false; verificarBotonSubmit();
        }

        let timeoutDNI = null;
        async function validarDNIExistente() {
            const inputDNI = document.getElementById('dni_input').value.trim();
            const mensaje = document.getElementById('dni_mensaje');
            const inputField = document.getElementById('dni_input');
            const btnValidar = document.getElementById('btn_validar');
            
            clearTimeout(timeoutDNI);
            isDniValid = false; 
            dniYaRegistrado = false;
            verificarBotonSubmit();

            if (inputDNI.length !== 8) {
                mensaje.classList.add('hidden');
                inputField.classList.remove('border-red-500', 'border-blue-500');
                btnValidar.disabled = false;
                btnValidar.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            timeoutDNI = setTimeout(async () => {
                try {
                    const response = await fetch('index.php?action=verificar_dni_existente&dni=' + encodeURIComponent(inputDNI));
                    const result = await response.json();
                    mensaje.classList.remove('hidden');
                    
                    if (result.existe) {
                        mensaje.innerText = "❌ Este DNI ya está registrado.";
                        mensaje.className = "text-[10px] mt-1 block text-red-600 font-bold uppercase tracking-wide";
                        inputField.classList.add('border-red-500');
                        inputField.classList.remove('border-blue-500');
                        
                        btnValidar.disabled = true;
                        btnValidar.classList.add('opacity-50', 'cursor-not-allowed');
                        dniYaRegistrado = true;
                        isDniValid = false;
                    } else {
                        mensaje.innerText = "✓ DNI disponible (Haz clic en 'Buscar' para validar)";
                        mensaje.className = "text-[10px] mt-1 block text-blue-600 font-bold uppercase tracking-wide";
                        inputField.classList.add('border-blue-500');
                        inputField.classList.remove('border-red-500');
                        
                        btnValidar.disabled = false;
                        btnValidar.classList.remove('opacity-50', 'cursor-not-allowed');
                        dniYaRegistrado = false;
                    }
                    verificarBotonSubmit();
                } catch (error) {}
            }, 500);
        }

        let timeoutUsername = null;
        async function validarUsername() {
            const inputUsername = document.getElementById('username_input').value.trim();
            const mensaje = document.getElementById('username_mensaje');
            const inputField = document.getElementById('username_input');
            
            clearTimeout(timeoutUsername);
            isUsernameValid = false; verificarBotonSubmit();

            if (inputUsername.length === 0) {
                mensaje.classList.add('hidden');
                inputField.classList.remove('border-red-500', 'border-blue-500');
                return;
            }

            timeoutUsername = setTimeout(async () => {
                try {
                    const response = await fetch('index.php?action=verificar_username_ajax&username=' + encodeURIComponent(inputUsername));
                    const result = await response.json();
                    mensaje.classList.remove('hidden');
                    if (result.existe) {
                        mensaje.innerText = "❌ Este usuario ya existe, elige otro.";
                        mensaje.className = "text-[10px] mt-1 block text-red-600 font-bold uppercase tracking-wide";
                        inputField.classList.add('border-red-500');
                        inputField.classList.remove('border-blue-500');
                        isUsernameValid = false;
                    } else {
                        mensaje.innerText = "✓ Usuario disponible";
                        mensaje.className = "text-[10px] mt-1 block text-blue-600 font-bold uppercase tracking-wide";
                        inputField.classList.add('border-blue-500');
                        inputField.classList.remove('border-red-500');
                        isUsernameValid = true;
                    }
                    verificarBotonSubmit();
                } catch (error) {}
            }, 500);
        }
    </script>
</body>
</html>