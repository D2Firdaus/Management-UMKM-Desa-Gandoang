<?php
$is_view = (strpos($_SERVER['SCRIPT_NAME'], '/view/') !== false);
$asset_path = $is_view ? '../asset' : 'asset';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
footer { background: #2d5a3f; color: white; padding: 1.5rem 3rem; font-family: 'Poppins', sans-serif; }
footer .footer-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; }
footer .info h3 { font-size: 1rem; margin-bottom: 0.3rem; }
footer .info p { font-size: 0.75rem; opacity: 0.9; margin: 0.2rem 0; }
footer .info a { color: white; text-decoration: none; font-size: 0.75rem; }
footer .copyright { font-size: 0.75rem; opacity: 0.8; text-align: center; width: 100%; margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 0.8rem; }
</style>

<footer>
    <div class="footer-top">
        <div class="info">
            <h3>Sistem Manajemen UMKM Desa</h3>
            <p>Jl. Raya Cileungsi - Jonggol No.9, Gandoang, Kec. Cileungsi, Kabupaten Bogor, Jawa Barat 16820</p>
            <p><a href="https://wa.me/6281312333735"><i class="bi bi-whatsapp"></i> 0813-1233-3735</a></p>
        </div>
    </div>
    <div class="copyright">&copy; 2026 Sistem Manajemen UMKM Desa</div>
</footer>
