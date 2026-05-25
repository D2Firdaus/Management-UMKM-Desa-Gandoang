<?php
$is_view = (strpos($_SERVER['SCRIPT_NAME'], '/view/') !== false);
$asset_path = $is_view ? '../asset' : 'asset';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
footer { background: linear-gradient(to bottom, #0B1615, #162E2B); color: white; padding: 2rem 3rem; font-family: 'Poppins', sans-serif; }
footer .footer-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; }
footer .info h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem; }
footer .info p { font-size: 0.85rem; opacity: 0.9; margin: 0.3rem 0; }
footer .info a { color: white; text-decoration: none; font-size: 0.85rem; }
footer .copyright { font-size: 0.8rem; opacity: 0.8; text-align: center; width: 100%; margin-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem; }

@media (max-width: 768px) {
    footer { padding: 1.5rem 1rem; }
    footer .info h3 { font-size: 1.1rem; }
    footer .info p { font-size: 0.8rem; }
}
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
