<?php require_once dirname(__DIR__, 2) . '/config/Config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Ciudadano - Cerro Azul Limpio</title>
    
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
<body class="bg-[#F8FAFC] min-h-screen flex flex-col items-center justify-center p-4 selection:bg-blue-200">

    <div class="w-full max-w-md my-8">
        
        <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-extrabold text-slate-900">Crea tu Cuenta</h1>
                <p class="text-sm text-slate-500 mt-2 font-medium">Validaremos tu identidad con RENIEC</p>
            </div>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="flex items-center gap-3 bg-red-50 text-red-700 p-4 rounded-xl text-sm mb-6 border border-red-100">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=register_process" method="POST" class="space-y-5">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Número de DNI</label>
                    <div class="flex gap-2">
                        <input type="text" id="dni_input" name="dni" maxlength="8" required onkeyup="validarDNIExistente()" placeholder="Ej: 12345678" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-medium bg-slate-50 focus:bg-white text-slate-800">
                        <button type="button" onclick="validarDNI()" id="btn_validar" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-sm font-bold transition-all shadow-md">Buscar</button>
                    </div>
                    <span id="dni_mensaje" class="text-xs mt-1 block hidden"></span>
                </div>

                <div id="campos_nombres" class="hidden space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nombres Completos</label>
                        <input type="text" id="nombres_input" name="nombres" readonly class="w-full bg-transparent text-sm font-bold text-slate-800 outline-none mt-1">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Apellido Paterno</label>
                            <input type="text" id="paterno_input" name="paterno" readonly class="w-full bg-transparent text-sm font-bold text-slate-800 outline-none mt-1">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Apellido Materno</label>
                            <input type="text" id="materno_input" name="materno" readonly class="w-full bg-transparent text-sm font-bold text-slate-800 outline-none mt-1">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nombre de Usuario</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-500 font-bold">@</span>
                        <input type="text" id="username_input" name="username" required onkeyup="validarUsername()" class="w-full pl-9 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-medium bg-slate-50 focus:bg-white text-slate-800">
                    </div>
                    <span id="username_mensaje" class="text-xs mt-1 block hidden"></span>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Contraseña</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-medium bg-slate-50 focus:bg-white text-slate-800">
                </div>

                <div class="pt-4">
                    <button type="submit" id="btn_submit" disabled class="w-full bg-slate-900 disabled:bg-slate-300 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg text-sm">
                        Registrarme
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="index.php?action=login" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Login
            </a>
        </div>
    </div>

    <script>
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

            btn.innerText = "..."; btn.disabled = true;
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
                    mensaje.innerText = "✓ Identidad verificada con éxito";
                    mensaje.className = "text-[10px] mt-1 block text-blue-600 font-bold uppercase tracking-wide";
                    isDniValid = true;
                } else {
                    mensaje.innerText = "❌ " + result.message;
                    mensaje.className = "text-[10px] mt-1 block text-red-600 font-bold uppercase tracking-wide";
                }
            } catch (error) {
                mensaje.innerText = "Error al consultar la RENIEC.";
                mensaje.className = "text-[10px] mt-1 block text-red-600 font-bold uppercase tracking-wide";
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
            isDniValid = false; dniYaRegistrado = false; verificarBotonSubmit();

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
                        mensaje.innerText = "❌ Este DNI ya está registrado. Inicia sesión.";
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
                        mensaje.innerText = "❌ Este usuario ya está en uso.";
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