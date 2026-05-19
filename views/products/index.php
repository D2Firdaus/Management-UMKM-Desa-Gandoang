<?php
$search     = isset($_GET['search'])   ? trim($_GET['search'])      : '';
$per_page   = isset($_GET['show'])     ? (int)$_GET['show']         : 3;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../asset/boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../asset/boostrap/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../asset/css/products.css">

</head>

<body>
    <!-- Navbar -->

    <!-- Left Sidebar -->
    <!-- Content -->
    <div class="container-sm p-md-5 mt-5 content rounded-5">
        <div class="card-header">
            <h1 class="fs-2 fw-bold">Detail Produk</h1>
            <p class="fs-5">Daftar produk yang tersedia.</p>
        </div>

        <!-- TOOLBAR: Show Entries + Search -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 pt-5">

            <!-- Show entries -->
            <form method="GET" class="show-entries d-flex align-items-center gap-2">
                <label for="show">Show</label>
                <select name="show" id="show" onchange="this.form.submit()">
                    <?php foreach ([3, 5, 10, 25] as $opt): ?>
                        <option value="<?= $opt ?>" <?= $per_page == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
                <label>entries</label>
                <!-- Pertahankan search saat ganti show -->
                <?php if ($search): ?>
                    <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                <?php endif; ?>
            </form>

            <!-- Search -->
            <form method="GET" class="search-form">
                <!-- Pertahankan show saat search -->
                <div class="input-group justify-content-center align-items-center border border-1 m-3 border-black rounded-3">
                    <input
                        type="text"
                        name="search"
                        class="input-group-text text-start input-search"
                        placeholder="Cari Produk..."
                        value="<?= htmlspecialchars($search) ?>"
                        autocomplete="off">
                    <button class="input-group-text bg-white border-0" onclick="this.form.submit()">
                        <i class="bi bi-search"></i>
                    </button>
                    <input type="hidden" name="show" value="<?= $per_page ?>">
                    <input type="hidden" name="page" value="1">
                </div>
            </form>

        </div>

        <!-- Table -->
        <div class="table-responsive mt-5">
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nama Produk</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop produk -->
                    <tr>
                        <td>1</td>
                        <td>Produk A</td>
                        <td>Rp 100.000</td>
                        <td>50</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Produk B</td>
                        <td>Rp 150.000</td>
                        <td>30</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Produk C</td>
                        <td>Rp 200.000</td>
                        <td>20</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
        <!-- Footer -->
</body>

</html>