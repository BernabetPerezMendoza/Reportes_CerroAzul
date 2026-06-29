<?php
if (!isset($usuario) || !$usuario) {
    $usuario = [
        'dni_user' => 'Error',
        'name_user' => 'Usuario no encontrado',
        'father_surname_user' => '',
        'mother_surname_user' => '',
        'username_user' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Admin - AdminCenter</title>
    
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
                <div class="flex items-center gap-4">
                    <div class="bg-blue-500/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">Mi<span class="text-blue-400">Perfil</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.php?action=admin_dashboard" class="text-sm font-semibold text-blue-200 hover:text-white transition-colors">Volver al Panel</a>
                    <div class="w-px h-6 bg-blue-700/50"></div>
                    <a href="index.php?action=logout" class="flex items-center gap-2 bg-red-500/10 hover:bg-red-500 hover:text-white text-red-400 px-4 py-2 rounded-lg transition-all font-medium text-sm">
                        Salir
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-slate-900">Configuración de Administrador</h2>
            <p class="text-slate-500 mt-2">Gestiona tus credenciales de acceso al panel de control.</p>
        </div>
        
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

        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
            
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="bg-slate-100 p-3 rounded-full">
                            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Identidad Validada</h3>
                            <p class="text-xs text-slate-400">Datos obtenidos de RENIEC</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Documento Nacional (DNI)</label>
                            <div class="bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100 text-slate-600 font-mono font-semibold text-sm">
                                <?= htmlspecialchars($usuario['dni_user']) ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nombres Completos</label>
                            <div class="bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100 text-slate-800 font-bold text-sm">
                                <?= htmlspecialchars($usuario['name_user']) ?>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ap. Paterno</label>
                                <div class="bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100 text-slate-800 font-bold text-sm">
                                    <?= htmlspecialchars($usuario['father_surname_user']) ?>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ap. Materno</label>
                                <div class="bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100 text-slate-800 font-bold text-sm">
                                    <?= htmlspecialchars($usuario['mother_surname_user']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-3">
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-lg font-extrabold text-slate-900 mb-1">Credenciales de Acceso</h3>
                    <p class="text-sm text-slate-500 mb-6">Actualiza tu usuario o contraseña. Si dejas la contraseña en blanco, se mantendrá la actual.</p>
                    
                    <form action="index.php?action=actualizar_perfil_admin" method="POST" class="space-y-5">
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nombre de Usuario (Login)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-500 font-bold">@</span>
                                <input type="text" id="username_input" name="username" value="<?= htmlspecialchars($usuario['username_user']) ?>" required onkeyup="validarUsername()" class="w-full pl-9 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-semibold text-slate-800">
                            </div>
                            <span id="username_mensaje" class="text-xs mt-1 block hidden"></span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nueva Contraseña <span class="text-slate-400 font-normal lowercase">(Opcional)</span></label>
                            <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-medium placeholder-slate-300">
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <button type="submit" id="btn_submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold px-8 py-3 rounded-xl transition-all shadow-md text-sm">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <script>
        const usernameOriginal = "<?= htmlspecialchars($usuario['username_user']) ?>";
        let timeoutUsername = null;

        async function validarUsername() {
            const inputUsername = document.getElementById('username_input').value.trim();
            const mensaje = document.getElementById('username_mensaje');
            const inputField = document.getElementById('username_input');
            const btnSubmit = document.getElementById('btn_submit');
            
            clearTimeout(timeoutUsername);
            
            if (inputUsername === usernameOriginal || inputUsername.length === 0) {
                mensaje.classList.add('hidden');
                inputField.classList.remove('border-red-500', 'border-blue-500');
                btnSubmit.disabled = false;
                return;
            }

            btnSubmit.disabled = true; 

            timeoutUsername = setTimeout(async () => {
                try {
                    const response = await fetch('index.php?action=verificar_username_ajax&username=' + encodeURIComponent(inputUsername));
                    const result = await response.json();
                    
                    mensaje.classList.remove('hidden');
                    if (result.existe) {
                        mensaje.innerText = "❌ Este usuario ya está en uso.";
                        mensaje.className = "text-[10px] mt-1 block text-red-600 font-bold uppercase tracking-wide";
                        inputField.classList.add('border-red-500');
                        inputField.classList.remove('border-blue-500');
                        btnSubmit.disabled = true;
                    } else {
                        mensaje.innerText = "✓ Usuario disponible";
                        mensaje.className = "text-[10px] mt-1 block text-blue-600 font-bold uppercase tracking-wide";
                        inputField.classList.add('border-blue-500');
                        inputField.classList.remove('border-red-500');
                        btnSubmit.disabled = false;
                    }
                } catch (error) {
                    console.error("Error al validar username");
                }
            }, 500);
        }
    </script>
</body>
</html>