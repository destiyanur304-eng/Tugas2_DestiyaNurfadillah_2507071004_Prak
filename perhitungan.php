<?php
function hitungNilaiAkhir($tugas, $uts, $uas) {
    return ($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4);
}

function tentukanGrade($nilaiAkhir) {
    if ($nilaiAkhir >= 85) {
        return "A";
    } elseif ($nilaiAkhir >= 75) {
        return "B";
    } elseif ($nilaiAkhir >= 65) {
        return "C";
    } elseif ($nilaiAkhir >= 50) {
        return "D";
    } else {
        return "E";
    }
}

function tentukanStatus($nilaiAkhir) {
    if ($nilaiAkhir >= 65) {
        return "Lulus";
    } else {
        return "Tidak Lulus";
    }
}

function aman($data) {
    return htmlspecialchars((string)$data, ENT_QUOTES, "UTF-8");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

$nama = trim($_POST["nama"] ?? "");
$nim = trim($_POST["nim"] ?? "");
$tugasInput = $_POST["nilaiTugas"] ?? "";
$utsInput = $_POST["nilaiUTS"] ?? "";
$uasInput = $_POST["nilaiUAS"] ?? "";
$error = "";

if (
    $nama === "" || $nim === "" ||
    $tugasInput === "" || $utsInput === "" ||
    $uasInput === ""
) {
    $error = "Semua kolom wajib diisi.";
} elseif (
    !is_numeric($tugasInput) ||
    !is_numeric($utsInput) ||
    !is_numeric($uasInput) ||
    (float)$tugasInput < 0 || (float)$tugasInput > 100 ||
    (float)$utsInput < 0 || (float)$utsInput > 100 ||
    (float)$uasInput < 0 || (float)$uasInput > 100
) {
    $error = "Nilai harus berupa angka antara 0 sampai 100.";
} else {
    $nilaiTugas = (float)$tugasInput;
    $nilaiUTS = (float)$utsInput;
    $nilaiUAS = (float)$uasInput;

    $nilaiAkhir = hitungNilaiAkhir(
        $nilaiTugas,
        $nilaiUTS,
        $nilaiUAS
    );

    $grade = tentukanGrade($nilaiAkhir);
    $status = tentukanStatus($nilaiAkhir);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perhitungan Nilai</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header class="page-header">
            <p class="eyebrow">PROGRAM AKADEMIK</p>
            <h1>Hasil Perhitungan Nilai</h1>
            <p>Berikut hasil perhitungan nilai akhir mahasiswa.</p>
        </header>

        <main>
            <section class="card">
                <?php if ($error !== ""): ?>
                    <div class="error">
                        <?= aman($error) ?>
                    </div>

                    <a class="back-link" href="index.html">
                        Kembali ke Form
                    </a>

                <?php else: ?>
                    <p class="eyebrow">HASIL PERHITUNGAN</p>
                    <h2>Data Mahasiswa</h2>

                    <div class="result-score">
                        <span>Nilai Akhir</span>
                        <strong>
                            <?= number_format($nilaiAkhir, 2, ",", ".") ?>
                        </strong>
                    </div>

                    <div class="result-grid">
                        <div class="result-item">
                            <span>Nama</span>
                            <strong><?= aman($nama) ?></strong>
                        </div>

                        <div class="result-item">
                            <span>NIM</span>
                            <strong><?= aman($nim) ?></strong>
                        </div>

                        <div class="result-item">
                            <span>Nilai Tugas</span>
                            <strong>
                                <?= number_format($nilaiTugas, 2, ",", ".") ?>
                            </strong>
                        </div>

                        <div class="result-item">
                            <span>Nilai UTS</span>
                            <strong>
                                <?= number_format($nilaiUTS, 2, ",", ".") ?>
                            </strong>
                        </div>

                        <div class="result-item">
                            <span>Nilai UAS</span>
                            <strong>
                                <?= number_format($nilaiUAS, 2, ",", ".") ?>
                            </strong>
                        </div>

                        <div class="result-item">
                            <span>Grade</span>
                            <strong><?= aman($grade) ?></strong>
                        </div>
                    </div>

                    <div class="status <?= $status === 'Lulus' ? 'lulus' : 'tidak-lulus' ?>">
                        Status: <?= aman($status) ?>
                    </div>

                    <a class="back-link" href="index.html">
                        Hitung Nilai Lagi
                    </a>
                <?php endif; ?>
            </section>
        </main>

        <footer>
            <p>Program Penghitung Nilai Mahasiswa | Tugas 2 PBW</p>
        </footer>
    </div>
</body>
</html>