</div>
<footer class="bg-white border-t border-slate-200 py-6 mt-12 text-center text-sm text-slate-500">
    &copy; 2026 Municipalidad de Lunahuaná - Reporte Ciudadano Optimizado.
</footer>
<script>
function marcarLeidas() {
    fetch('index.php?action=marcar_leidas')
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            const badge = document.getElementById('notif-badge');
            if(badge) badge.remove();
        }
    });
}
</script>
</body>
</html>