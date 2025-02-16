<?php
$title = "Data Lokasi";

// Ambil semua data dari database
$query = mysqli_query($conn, "SELECT * FROM tb_lokasi ORDER BY id ASC");
$lokasiList = mysqli_fetch_all($query, MYSQLI_ASSOC);

// Ambil nilai pencarian dari input user
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

// Sequential Search di PHP
$filteredData = [];
if (!empty($search)) {
    foreach ($lokasiList as $row) {
        if (strpos(strtolower($row['nama_lokasi']), $search) !== false) {
            $filteredData[] = $row;
        }
    }
} else {
    $filteredData = $lokasiList;
}

// Pagination
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int) $_GET['limit'] : 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$totalData = count($filteredData);
$totalPages = ceil($totalData / $limit);
$offset = ($page - 1) * $limit;
$pagedData = array_slice($filteredData, $offset, $limit);
?>

<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <h2 class="page-title">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                <?= $title ?>
                </h2>
            </div>
        </div>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-body border-bottom py-3">
                    <div class="d-flex mb-2">
                            <div class="ms-auto text-secondary">
                                    <div class="ms-2 d-inline-block">
                                        <a href="./?route=lokasiTambah" class="btn btn-dark">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-square-rounded-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /><path d="M15 12h-6" /><path d="M12 9v6" /></svg>    
                                        Tambah data
                                        </a>
                                    </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="text-secondary">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <select class="form-control form-control-sm" id="limit-select">
                                        <option value="<?= count($lokasiList) ?>" <?= $limit == count($lokasiList) ? 'selected' : '' ?>>All</option>
                                        <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                                        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                                        <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                                    </select>
                                </div>
                                entries
                            </div>
                            <div class="ms-auto text-secondary">
                                Search 
                                    <div class="ms-2 d-inline-block">
                                    <input type="text" class="form-control form-control-sm" id="search-input"
                                            value="<?= htmlspecialchars($search) ?>" placeholder="Cari ..">
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Lokasi</th>
                                    <th>Tipe Lokasi</th>
                                    <th>Langtitude/Longitude</th>
                                    <th>Radius</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (count($pagedData) === 0) : ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data, silakan tambah data</td>
                                </tr>
                            <?php else : ?>
                                <?php
                                $no = $offset + 1;
                                foreach ($pagedData as $row): ?>
                                    <tr>
                                        <td>
                                            <div><?= $no++ ?></div>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($row['nama_lokasi']) ?></div>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($row['tipe_lokasi']) ?></div>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($row['latitude']. "/" . $row['longitude']) ?></div>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($row['radius']) ?></div>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <div class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                        data-bs-toggle="dropdown">
                                                        Opsi
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="./?route=lokasiUbah&id=<?= $row['id'] ?>">
                                                        Ubah
                                                    </a>
                                                    <a class="dropdown-item" href="./?route=lokasiDetail&id=<?= $row['id'] ?>">
                                                        Detail
                                                    </a>
                                                    <a class="dropdown-item btn-delete" href="./?route=lokasiHapus&id=<?= $row['id'] ?>">
                                                        Hapus
                                                    </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <p class="m-0 text-secondary">Showing <span><?= min($offset + 1, $totalData) ?></span> to
                            <span><?= min($offset + $limit, $totalData) ?></span> of <span><?= $totalData ?></span>
                            entries
                        </p>
                        <ul class="pagination m-0 ms-auto">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?route=lokasi&search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= ($page - 1) ?>"
                                    tabindex="-1" aria-disabled="true">
                                    <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-left -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon icon-1">
                                        <path d="M15 6l-6 6l6 6" />
                                    </svg>
                                    prev
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?route=lokasi&search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?route=lokasi&search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= ($page + 1) ?>">
                                    next
                                    <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-right -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon icon-1">
                                        <path d="M9 6l6 6l-6 6" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("limit-select").addEventListener("change", function () {
        window.location.href = "?route=lokasi&limit=" + this.value + "&search=<?= urlencode($search) ?>";
    });

    document.getElementById("search-input").addEventListener("keyup", function (e) {
        if (e.key === "Enter") {
            window.location.href = "?route=lokasi&limit=<?= $limit ?>&search=" + encodeURIComponent(this.value);
        }
    });
</script>