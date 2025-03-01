<?php
$lokasi_absen = $_SESSION['lokasi_absen'];
$id = $_SESSION['id'];

$lokasi = mysqli_query($conn, "SELECT * FROM tb_lokasi WHERE nama_lokasi = '$lokasi_absen'");
while($data  = mysqli_fetch_array($lokasi)):
    $jam_kantor = date('H:i:s', strtotime($data['jam_masuk']));
endwhile;

$query = mysqli_query($conn, "SELECT * FROM presensi WHERE id_pegawai = '$id' ORDER BY tanggal_masuk ASC");
$presensiList = mysqli_fetch_all($query, MYSQLI_ASSOC);

$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

$filteredData = [];
if (!empty($search)) {
    foreach ($presensiList as $row) {
        if (strpos(strtolower($row['tanggal_masuk']), $search) !== false) {
            $filteredData[] = $row;
        }
    }
} else {
    $filteredData = $presensiList;
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                        <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
                    </svg>
                    Rekap Absensi Harian
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
                        <div class="d-flex">
                            <div class="text-secondary">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <select class="form-control form-control-sm" id="limit-select">
                                        <option value="<?= count($presensiList) ?>" <?= $limit == count($presensiList) ? 'selected' : '' ?>>All</option>
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
                                    <th >Tanggal</th>
                                    <th class="text-center">Jam Masuk</th>
                                    <th class="text-center">Jam Pulang</th>
                                    <th class="text-center">Total Jam Kerja</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($pagedData) === 0): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data</td>
                                    </tr>
                                <?php else: ?>
                                    <?php
                                    $no = $offset + 1;
                                    foreach ($pagedData as $row): 
                                        if (!empty($row['tanggal_keluar']) && !empty($row['jam_keluar'])) {
                                            $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($row['tanggal_masuk'] . ' ' . $row['jam_masuk']));
                                            $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime($row['tanggal_keluar'] . ' ' . $row['jam_keluar']));
                                    
                                            $timestamp_masuk = strtotime($jam_tanggal_masuk);
                                            $timestamp_keluar = strtotime($jam_tanggal_keluar);
                                    
                                            $hitung = $timestamp_keluar - $timestamp_masuk;
                                            $jam_kerja = floor($hitung / 3600);
                                            $hitung -= $jam_kerja * 3600;
                                            $menit_kerja = floor($hitung / 60);
                                        }

                                        $jam_masuk = !empty($row['jam_masuk']) ? date('H:i:s', strtotime($row['jam_masuk'])) : null;
                                        $timestamp_jam_pegawai = $jam_masuk ? strtotime($jam_masuk) : null;
                                        $timestamp_jam_kantor = !empty($jam_kantor) ? strtotime($jam_kantor) : null;

                                        $terlambat = $timestamp_jam_pegawai - $timestamp_jam_kantor;
                                        $jam_terlambat = floor($terlambat / 3600);
                                        $terlambat -= $jam_terlambat * 3600;
                                        $menit_terlambat = floor($terlambat / 60);
                                        $detik_terlambat = $terlambat % 60;
                                    ?>
                                        <tr>
                                            <td>
                                                <div><?= $no++ ?></div>
                                            </td>
                                            <td>
                                                <div><?= htmlspecialchars(date('d F Y', strtotime($row['tanggal_masuk']))) ?></div>
                                            </td>
                                            <td>
                                                <div class="text-center"><?= htmlspecialchars($row['jam_masuk']) ?></div>
                                            </td>
                                            <td>
                                            <?php if ($row['tanggal_keluar'] === NULL) : ?>
                                                <div class="text-center">00:00:00</div>
                                            <?php else :?>
                                                <div class="text-center"><?= htmlspecialchars($row['jam_keluar']) ?></div>
                                            <?php endif;?>
                                            </td>
                                            <td>
                                            <?php if ($row['tanggal_keluar'] === NULL) : ?>
                                                <div class="text-center">0 Jam 0 Menit</div>
                                            <?php else :?>
                                                <div class="text-center"><?= $jam_kerja . ' Jam ' . $menit_kerja . ' Menit' ?></div>
                                            <?php endif;?>
                                            </td>
                                            <td>
                                            <?php if ($terlambat <= 0) : ?>
                                                <span class="badge bg-success text-white text-center">On Time</span>
                                            <?php else :?>
                                                <div class="badge bg-danger text-white text-center"><?= $jam_terlambat . ' Jam ' . $menit_terlambat . ' Menit' ?></div>
                                            <?php endif;?>
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
                                    href="?route=harian&search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= ($page - 1) ?>"
                                    tabindex="-1" aria-disabled="true">
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
                                        href="?route=harian&search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="?route=harian&search=<?= urlencode($search) ?>&limit=<?= $limit ?>&page=<?= ($page + 1) ?>">
                                    next
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
        window.location.href = "?route=harian&limit=" + this.value + "&search=<?= urlencode($search) ?>";
    });

    document.getElementById("search-input").addEventListener("keyup", function (e) {
        if (e.key === "Enter") {
            window.location.href = "?route=harian&limit=<?= $limit ?>&search=" + encodeURIComponent(this.value);
        }
    });
</script>