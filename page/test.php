<?php
include "../db/dbhelper.php";

$sql = "SELECT * FROM gallery " ;

$result = executeResult($sql);


if (!empty($result)) {
    echo "<div style='display: flex; flex-wrap: wrap;'>";
    foreach ($result as $row) {
        echo "<div style='margin: 10px; text-align: center;'>";
        echo "<img src='" . $row["image"] . "' alt='Product " . $row["product_id"] . "' style='width: 150px; height: auto;'><br>";
        echo "<p>Product ID: " . $row["product_id"] . "</p>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Không có hình ảnh nào trong cơ sở dữ liệu.";
}


?>

INSERT INTO gallery (product_id, image) VALUES
(6, '../img/TrangSuc/Nhan/6.nhanhinhno.webp'),
(6, '../img/TrangSuc/Nhan/6.nhanhinhno_2.webp'),
(7, '../img/TrangSuc/Nhan/7.canhhodiep.webp'),
(7, '../img/TrangSuc/Nhan/7.canhhodiep_2.webp'),
(8, '../img/TrangSuc/Nhan/8.odabonchau.webp'),
(8, '../img/TrangSuc/Nhan/8.odabonchau_2.webp'),
(9, '../img/TrangSuc/Nhan/9.duoicadinhda.webp'),
(9, '../img/TrangSuc/Nhan/9.duoicadinhda_2.webp'),
(10, '../img/TrangSuc/Nhan/10.nhandoi1.webp'),
(11, '../img/TrangSuc/Nhan/11.nhandoivatcheodinhda.webp'),
(11, '../img/TrangSuc/Nhan/11.nhandoivatcheodinhda_2.webp'),
(12, '../img/TrangSuc/Nhan/0.cach_do_nhan.webp');



INSERT INTO gallery (product_id, image) VALUES
(12, '../img/TrangSuc/VongCo/12.vongcongoctrai.webp'),
(13, '../img/TrangSuc/VongCo/13.dinhdacaocap.webp'),
(13, '../img/TrangSuc/VongCo/13.dinhdacaocap_2.webp'),
(14, '../img/TrangSuc/VongCo/14.ngoctraino.webp'),
(15, '../img/TrangSuc/VongCo/15.hoabachnhat.webp'),
(15, '../img/TrangSuc/VongCo/15.hoabachnhat_2.webp'),
(16, '../img/TrangSuc/VongCo/16.canhlanguyetque.webp'),
(16, '../img/TrangSuc/VongCo/16.canhlanguyetque_2.webp');


INSERT INTO gallery (product_id, image) VALUES
(17, '../img/TrangSuc/VongTay/17.vongmixngoc_2.webp'),
(17, '../img/TrangSuc/VongTay/17.vongmixngoc.webp'),
(18, '../img/TrangSuc/VongTay/18.dayxulaplanh.webp'),
(18, '../img/TrangSuc/VongTay/18.dayxulaplanh_2.webp'),
(19, '../img/TrangSuc/VongTay/19.co4lamayman.webp'),
(19, '../img/TrangSuc/VongTay/19.co4lamayman_2.webp'),
(20, '../img/TrangSuc/VongTay/20.dayrutdinhda_2.webp'),
(20, '../img/TrangSuc/VongTay/20.dayrutdinhda.webp'),
(21, '../img/TrangSuc/VongTay/21.hinhtraitim.webp'),
(21, '../img/TrangSuc/VongTay/21.hinhtraitim_2.webp');


INSERT INTO gallery (product_id, image) VALUES
(22, '../img/TuiXachVaVi/22.Yummy_tuideovaida_hinhbannguyet190k.webp'),
(22, '../img/TuiXachVaVi/22.Yummy_tuideovaida_hinhbannguyet190k_3.webp'),
(23, '../img/TuiXachVaVi/23.Yummy_Vidangcamtaynhogon49K.webp'),
(23, '../img/TuiXachVaVi/23.Yummy_Vidangcamtaynhogon49K_2.webp'),
(24, '../img/TuiXachVaVi/24.Yummy_tuideocheodayrut_190k.webp'),
(24, '../img/TuiXachVaVi/24.Yummy_tuideocheodayrut_190k_2.webp'),
(25, '../img/TuiXachVaVi/25.Hapas_viCardgapdoijeans_250k.webp'),
(25, '../img/TuiXachVaVi/25.Hapas_viCardgapdoijeans_250k_2.webp'),
(26, '../img/TuiXachVaVi/26.Hapas_tuideovaiAuraHobo_700k.webp'),
(26, '../img/TuiXachVaVi/26.Hapas_tuideovaiAuraHobo_700k_2.webp'),
(27, '../img/TuiXachVaVi/27.Hapas_tuixachtaybacguong_900k.webp'),
(27, '../img/TuiXachVaVi/27.Hapas_tuixachtaybacguong_900k_2.webp'),
(27, '../img/TuiXachVaVi/27.Hapas_tuixachtaybacguong_900k_3.webp'),
(28, '../img/TuiXachVaVi/28.Lesac_tuideovai_500k.webp'),
(28, '../img/TuiXachVaVi/28.Lesac_tuideovai_500k_2.webp'),
(29, '../img/TuiXachVaVi/29.Lesac_vinuminiTongueWallet_250k.webp'),
(29, '../img/TuiXachVaVi/29.Lesac_vinuminiTongueWallet_250k_2.webp'),
(30, '../img/TuiXachVaVi/30.Lesac_tuideovainuCelina_520k.webp'),
(30, '../img/TuiXachVaVi/30.Lesac_tuideovainuCelina_520k_2.webp');


INSERT INTO gallery (product_id, image) VALUES
(31, '../img/PhuKienToc/31.CaiTocNoTo30k.jpeg'),
(32, '../img/PhuKienToc/32.Kep5RangCharmThach2k.jpeg'),
(32, '../img/PhuKienToc/32.Kep5RangCharmThach2k_2.jpeg'),
(33, '../img/PhuKienToc/33.KepMaiSaoBienPhunMau9k.jpeg'),
(34, '../img/PhuKienToc/34.KepMiniXaCuBau35k.jpeg'),
(35, '../img/PhuKienToc/35.Keptocdai7cm20k_2.jpeg'),
(36, '../img/PhuKienToc/36.keptocthatnutchuthap10k.jpeg'),
(36, '../img/PhuKienToc/36.keptocthatnutchuthap_2.jpeg'),
(37, '../img/PhuKienToc/37.scrunchieRen10k.jpeg'),
(37, '../img/PhuKienToc/37.scrunchieRen10k_2.jpeg');


INSERT INTO gallery (product_id, image) VALUES
(38, '../img/DongHo/38.Casio_donghonudaykimloai_600k.webp'),
(38, '../img/DongHo/38.Casio_donghonudaykimloai_600k_2.webp'),
(39, '../img/DongHo/39.Casio_donghonuthepkhonggi_650k.webp'),
(40, '../img/DongHo/40.Casio_donghonudaykimloaimatmauxanh_1500k_2.webp'),
(41, '../img/DongHo/41.Clioa_donghothachanhchongthamnuoc_462k.webp'),
(41, '../img/DongHo/41.Clioa_donghothachanhchongthamnuoc_462k_2.webp'),
(42, '../img/DongHo/42.Ciloa_donghodaydeoxuongca_560k.webp'),
(42, '../img/DongHo/42.Clioa_donghodaydeoxuongca_560k_2.webp'),
(43, '../img/DongHo/43.Clioa_donghonukimcuongmaubac_400k.webp'),
(43, '../img/DongHo/43.Clioa_donghonukimcuongmaubac_400k_2.webp');


INSERT INTO gallery (product_id, image) VALUES
(44, '../img/TrangSuc/44.bongtai.webp'),
(44, '../img/TrangSuc/44.vongco.webp'),
(44, '../img/TrangSuc/44.vongtay.webp'),
(44, '../img/TrangSuc/44.botrangsuc.webp'),
(44, '../img/TrangSuc/44.botrangsucno.PNG'),
(44, '../img/TrangSuc/44.botrangsucno_2.webp');

INSERT INTO news_images (news_id, image) VALUES
(1, '../img/news/chon_qua_yeu_1.jpg'),
(1, '../img/news/chon_qua_yeu_2.jpg'),
(1, '../img/news/chon_qua_yeu_3.jpg');