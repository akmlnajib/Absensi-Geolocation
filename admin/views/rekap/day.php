<?php
$lokasi_absen = $_SESSION['lokasi_absen'];
$id = $_SESSION['id'];

if (empty($_POST['date_from'])) {
    $query = mysqli_query($conn, "SELECT presensi.*, pegawai.nama, pegawai.lokasi_absen 
        FROM presensi JOIN pegawai ON presensi.id_pegawai = pegawai.id WHERE YEAR(tanggal_masuk) = YEAR(NOW()) AND MONTH(tanggal_masuk) = MONTH(NOW()) 
        ORDER BY tanggal_masuk ASC");
     $tanggal = date('F Y');
} else {
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $query = mysqli_query($conn, "SELECT presensi.*, pegawai.nama, pegawai.lokasi_absen FROM presensi JOIN pegawai ON presensi.id_pegawai = pegawai.id WHERE tanggal_masuk BETWEEN '$date_from' AND '$date_to' ORDER BY tanggal_masuk ASC
    ");
    $tanggal = date('d F Y', strtotime($date_from)) . ' - ' . date('d F Y', strtotime($date_to));
}


$presensiList = mysqli_fetch_all($query, MYSQLI_ASSOC);

$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

$filteredData = [];
if (!empty($search)) {
    foreach ($presensiList as $row) {
        if (strpos(strtolower($row['tanggal_masuk']), strtolower($row['nama']), $search) !== false) {
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
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-squares-selected"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 10a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /><path d="M8 14.5l6.492 -6.492" /><path d="M13.496 20l6.504 -6.504z" /><path d="M8.586 19.414l10.827 -10.827" /><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" /></svg>
                    Rekap Absensi
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
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    <i class="fa-solid fa-file-export me-2"></i>
                                    Export Excel
                                </button>
                            </div>
                            
                            <div class="col-md-10">
                                <form method="POST">
                                    <div class="input-group">
                                            <input type="date" id="datepicker-default" class="form-control" name="date_from">
                                               
                                            <input type="date" class="form-control" name="date_to">
                                            <button type="submit" class="btn btn-primary">Cari</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
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

                    <span class="mb-2">Rekap Absensi : <?= $tanggal ?></span>
                    <div class="table-responsive mt-2">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Pegawai</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Jam Masuk</th>
                                    <th class="text-center">Jam Pulang</th>
                                    <th class="text-center">Total Jam Kerja</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                <?php if (count($pagedData) === 0): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data</td>
                                    </tr>
                                <?php else: ?>
                                    <?php
                                    $no = $offset + 1;
                                    foreach ($pagedData as $row):
                                        $lokasi = mysqli_query($conn, "SELECT * FROM tb_lokasi WHERE nama_lokasi = '$lokasi_absen'");
                                    while ($data = mysqli_fetch_array($lokasi)):
                                        $jam_kantor = date('H:i:s', strtotime($data['jam_masuk']));
                                    endwhile;
                                        if (!empty($row['tanggal_keluar']) && !empty($row['jam_keluar'])) {
                                            $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime($row['tanggal_masuk'] . ' ' . $jam_kantor));
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
                                        ?>
                                        <tr>
                                            <td>
                                                <div><?= $no++ ?></div>
                                            </td>
                                            <td>
                                                <div class="text-center"><?= htmlspecialchars($row['nama']) ?></div>
                                            </td>
                                            <td>
                                                <div><?= htmlspecialchars(date('d F Y', strtotime($row['tanggal_masuk']))) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center"><?= htmlspecialchars($row['jam_masuk']) ?></div>
                                            </td>
                                            <td>
                                                <?php if ($row['tanggal_keluar'] === NULL): ?>
                                                    <div class="text-center">00:00:00</div>
                                                <?php else: ?>
                                                    <div class="text-center"><?= htmlspecialchars($row['jam_keluar']) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row['tanggal_keluar'] === NULL): ?>
                                                    <div class="text-center">0 Jam 0 Menit</div>
                                                <?php else: ?>
                                                    <div class="text-center"><?= $jam_kerja . ' Jam ' . $menit_kerja . ' Menit' ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($jam_terlambat < 0 || $menit_terlambat < 0 || ($jam_terlambat == 0 && $menit_terlambat == 0)): ?>
                                                    <span class="badge bg-success text-white text-center">On Time</span>
                                                <?php else: ?>
                                                    <div class="badge bg-danger text-white text-center">
                                                        <?= $jam_terlambat . ' Jam ' . $menit_terlambat . ' Menit' ?></div>
                                                <?php endif; ?>
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
</div>
<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="rekap/export.php" method="POST">
                <div class="modal-body">
                <div class="mb-3">
                    <label for="">Tanggal Awal</label>
                    <input type="date" name="date_from" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="">Tanggal Akhir</label>
                    <input type="date" name="date_to" class="form-control">
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="" class="btn btn-primary" data-bs-dismiss="modal">Export</button>
                </div>
            </form>
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