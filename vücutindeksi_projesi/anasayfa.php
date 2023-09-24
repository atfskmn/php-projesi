<?php
require 'sayfa.ust.php';
require_once('db.php');

// Vücut kitle indeksi hesaplaması ve sonucun ekrana yazdırılması sadece POST isteği gönderildiğinde yapılmalıdır.
$vucut_kitle_indeksi = null;
$vki_aralik = "";
$vki_renk = "";

if (isset($_POST['adsoyad_form'])) {
    // Gelen verileri temizleme ve doğrulama işlemleri
    $adsoyad = $_POST['adsoyad_form'];
    $yas = $_POST['yas_form'];
    $boy = $_POST['boy_form'];
    $kilo = $_POST['kilo_form'];

    // Vücut kitle indeksi hesaplama
    $boy_metre_cinsinden = $boy / 100;
    $vucut_kitle_indeksi = $kilo / ($boy_metre_cinsinden * $boy_metre_cinsinden);

    // Veri eklemek için sorgu oluşturma
    $sql = "INSERT INTO kullanicilar (adsoyad, yas, boy, kilo) VALUES (:adsoyad, :yas, :boy, :kilo)";
    $SORGU = $DB->prepare($sql);

    // Bağlamaları gerçekleştirme
    $SORGU->bindParam(':adsoyad', $adsoyad);
    $SORGU->bindParam(':yas', $yas);
    $SORGU->bindParam(':boy', $boy);
    $SORGU->bindParam(':kilo', $kilo);

    // Sorguyu çalıştırma
    if ($SORGU->execute()) {
        echo "bilgi başarıyla eklendi.";
    } else {
        echo "Veri ekleme hatası: " . $SORGU->errorInfo()[2];
    }
}


    // Vücut kitle indeksi aralığını belirle
    if ($vucut_kitle_indeksi < 18.5) {
        $vki_aralik = "Zayıf";
        $vki_renk = "warning"; // Sarı - Bootstrap "warning" sınıfı
    } elseif ($vucut_kitle_indeksi >= 18.5 && $vucut_kitle_indeksi <= 25.0) {
        $vki_aralik = "Normal ağırlıkta";
        $vki_renk = "success"; // Yeşil - Bootstrap "success" sınıfı
    } elseif ($vucut_kitle_indeksi >= 25.0 && $vucut_kitle_indeksi <= 100) {
        $vki_aralik = "Kilolu";
        $vki_renk = "danger"; // Kırmızı - Bootstrap "danger" sınıfı
    }


$id = $_GET['id'];

$sql = "SELECT * FROM kullanicilar WHERE id = :id";
$SORGU = $DB->prepare($sql);

$SORGU->bindParam(':id', $id);

$SORGU->execute();

$kullanicilar = $SORGU->fetchAll(PDO::FETCH_ASSOC);
$kullanici  = $kullanicilar[0];

?>
<div class='container'>
    <div class="offset-3 col-6">
        <div class='row text-center'>
            <h1 class='alert alert-warning'>Vücut Kitle İndeksi Hesaplama</h1>
        </div>
        <form method="POST">
            <div class="mb-3">
                <label for="adsoyad" class="form-label">Ad Soyad:</label>
                <input type="text" name='adsoyad_form' class="form-control" value='<?php echo $kullanici['adsoyad']; ?>' id="adsoyad" >
            </div>
            <div class="mb-3">
                <label for="yas" class="form-label">Yaş:</label>
                <input type="text" name='yas_form' class="form-control" value='<?php echo $kullanici['yas']; ?>' id="yas">
            </div>
            <div class="mb-3">
                <label for="kilo" class="form-label">Kilo (kg):</label>
                <input type="text" name='kilo_form' class="form-control" value='<?php echo $kullanici['kilo']; ?>' id="kilo">
            </div>
            <div class="mb-3">
                <label for="boy" class="form-label">Boy (cm):</label>
                <input type="text" name='boy_form' class="form-control" value='<?php echo $kullanici['boy']; ?>' id="boy">
            </div>
            <button type="submit" class="btn btn-primary">Hesapla ve Güncelle</button>
        </form>
        <?php
        // Vücut kitle indeksi hesapladıktan sonra ekrana yazdır
        if (isset($vucut_kitle_indeksi) && !empty($vki_aralik)) {
            echo "<div class='alert alert-$vki_renk'><strong>Vücut Kitle İndeksi: $vucut_kitle_indeksi - Vücut Kitle İndeksi Aralığı: $vki_aralik</strong></div>";
        }
        ?>
    </div>
</div>

<?php
require 'sayfa.alt.php';
?>
