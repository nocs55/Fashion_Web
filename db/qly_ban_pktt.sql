-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 04:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qly_ban_pktt`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int(9) NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `image_link` varchar(200) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `promotion_type` enum('none','category','specific_products') DEFAULT 'none',
  `sort_order` int(9) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `promotion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `name`, `image_link`, `link`, `promotion_type`, `sort_order`, `created_at`, `promotion_id`) VALUES
(1, 'Banner 1', '../img/Banner/banner1.png', 'products_sale.php', 'none', 1, '2025-06-02 16:35:04', 44),
(2, 'Banner 2', '../img/Banner/banner2.png', 'https://example.com/page2', 'none', 2, '2025-06-02 16:35:04', NULL),
(3, 'Banner 3', '../img/Banner/banner3.png', 'https://example.com/page3', 'none', 3, '2025-06-02 16:35:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(7, 2, 44, 3, '2025-06-04 13:29:58'),
(16, 2, 39, 2, '2025-06-07 10:11:00');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(9) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `parent_id`) VALUES
(1, 'Trang sức', NULL),
(2, 'Túi xách và ví', NULL),
(3, 'Phụ kiện tóc', NULL),
(4, 'Đồng hồ', NULL),
(5, 'Bông tai', 1),
(6, 'Nhẫn', 1),
(7, 'Vòng cổ', 1),
(8, 'Vòng tay', 1),
(9, 'Yummy', 2),
(10, 'Hapas', 2),
(11, 'Lesac', 2),
(12, 'Casio', 4),
(13, 'Ciloa', 4);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'Nguyễn Thị Thu Thủy', 'nttthuy.dhmt16a2hn@sv.uneti.edu.vn', '0964786423', 'shop nhiều đồ xinkkk', '2025-05-27 17:08:25');

-- --------------------------------------------------------

--
-- Table structure for table `footer`
--

CREATE TABLE `footer` (
  `id` int(11) NOT NULL,
  `section` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer`
--

INSERT INTO `footer` (`id`, `section`, `content`, `sort_order`) VALUES
(1, 'Designer', 'Nguyễn Thị Thu Thủy', 1),
(2, 'Designer', 'Dương Đức Trung', 2),
(3, 'Designer', 'Lê Thị Thanh Nhàn', 3),
(4, 'Designer', 'Bùi Thị Thanh Nhàn', 4),
(5, 'Class', 'DHMT16A2HN', 5),
(6, 'Class', 'DHMT16A2HN', 6),
(7, 'Class', 'DHMT16A2HN', 7),
(8, 'Class', 'DHMT16A2HN', 8),
(9, 'University', 'ĐH Kinh tế-Kỹ thuật Công nghiệp', 9),
(10, 'University', 'ĐH Kinh tế-Kỹ thuật Công nghiệp', 10),
(11, 'University', 'ĐH Kinh tế-Kỹ thuật Công nghiệp', 11),
(12, 'University', 'ĐH Kinh tế-Kỹ thuật Công nghiệp', 12),
(13, 'Place', '315 Trần Hưng Đạo, Bà Triệu, Nam Định', 13),
(14, 'Place', '315 Trần Hưng Đạo, Bà Triệu, Nam Định', 14),
(15, 'Place', '315 Trần Hưng Đạo, Bà Triệu, Nam Định', 15),
(16, 'Place', '315 Trần Hưng Đạo, Bà Triệu, Nam Định', 16);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(9) NOT NULL,
  `product_id` int(9) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `product_id`, `image`) VALUES
(1, 1, '../img/TrangSuc/BongTai/1.bongtaino_100k.webp'),
(2, 1, '../img/TrangSuc/BongTai/1.bongtaino_100k_2.webp'),
(3, 1, '../img/TrangSuc/BongTai/1.bongtaino_100k_3.webp'),
(4, 2, '../img/TrangSuc/BongTai/2.bongtaidangbau90k.webp'),
(5, 2, '../img/TrangSuc/BongTai/2.bongtaidangbau90k_2.webp'),
(6, 3, '../img/TrangSuc/BongTai/3.bongtaihinhno150k.webp'),
(7, 3, '../img/TrangSuc/BongTai/3.bongtaihinhno150k_2.webp'),
(8, 4, '../img/TrangSuc/BongTai/4.bongtaiganno_200k.webp'),
(9, 4, '../img/TrangSuc/BongTai/4.bongtaiganno_200k_2.webp'),
(10, 5, '../img/TrangSuc/BongTai/5.hoa5cnh100K.webp'),
(11, 5, '../img/TrangSuc/BongTai/5.hoa5cnh100K_2.webp'),
(12, 6, '../img/TrangSuc/Nhan/6.nhanhinhno.webp'),
(13, 6, '../img/TrangSuc/Nhan/6.nhanhinhno_2.webp'),
(14, 6, '../img/TrangSuc/Nhan/0.cach_do_nhan.webp'),
(15, 7, '../img/TrangSuc/Nhan/7.canhhodiep.webp'),
(16, 7, '../img/TrangSuc/Nhan/7.canhhodiep_2.webp'),
(17, 7, '../img/TrangSuc/Nhan/0.cach_do_nhan.webp'),
(18, 8, '../img/TrangSuc/Nhan/8.odabonchau.webp'),
(19, 8, '../img/TrangSuc/Nhan/8.odabonchau_2.webp'),
(20, 8, '../img/TrangSuc/Nhan/0.cach_do_nhan.webp'),
(21, 9, '../img/TrangSuc/Nhan/9.duoicadinhda.webp'),
(22, 9, '../img/TrangSuc/Nhan/9.duoicadinhda_2.webp'),
(23, 9, '../img/TrangSuc/Nhan/0.cach_do_nhan.webp'),
(24, 10, '../img/TrangSuc/Nhan/10.nhandoi1.webp'),
(25, 10, '../img/TrangSuc/Nhan/0.cach_do_nhan.webp'),
(26, 11, '../img/TrangSuc/Nhan/11.nhandoivatcheodinhda.webp'),
(27, 11, '../img/TrangSuc/Nhan/11.nhandoivatcheodinhda_2.webp'),
(28, 11, '../img/TrangSuc/Nhan/0.cach_do_nhan.webp'),
(29, 12, '../img/TrangSuc/VongCo/12.vongcongoctrai.webp'),
(30, 12, '../img/TrangSuc/VongCo/12.vongcongoctrai_2.webp'),
(31, 13, '../img/TrangSuc/VongCo/13.dinhdacaocap.webp'),
(32, 13, '../img/TrangSuc/VongCo/13.dinhdacaocap_2.webp'),
(33, 14, '../img/TrangSuc/VongCo/14.ngoctraino.webp'),
(34, 15, '../img/TrangSuc/VongCo/15.hoabachnhat.webp'),
(35, 15, '../img/TrangSuc/VongCo/15.hoabachnhat_2.webp'),
(36, 16, '../img/TrangSuc/VongCo/16.canhlanguyetque.webp'),
(37, 16, '../img/TrangSuc/VongCo/16.canhlanguyetque_2.webp'),
(38, 17, '../img/TrangSuc/VongTay/17.vongmixngoc_2.webp'),
(39, 17, '../img/TrangSuc/VongTay/17.vongmixngoc.webp'),
(40, 18, '../img/TrangSuc/VongTay/18.dayxulaplanh.webp'),
(41, 18, '../img/TrangSuc/VongTay/18.dayxulaplanh_2.webp'),
(42, 19, '../img/TrangSuc/VongTay/19.co4lamayman.webp'),
(43, 19, '../img/TrangSuc/VongTay/19.co4lamayman_2.webp'),
(44, 20, '../img/TrangSuc/VongTay/20.dayrutdinhda_2.webp'),
(45, 20, '../img/TrangSuc/VongTay/20.dayrutdinhda.webp'),
(46, 21, '../img/TrangSuc/VongTay/21.hinhtraitim.webp'),
(47, 21, '../img/TrangSuc/VongTay/21.hinhtraitim_2.webp'),
(48, 22, '../img/TuiXachVaVi/22.Yummy_tuideovaida_hinhbannguyet190k.webp'),
(49, 22, '../img/TuiXachVaVi/22.Yummy_tuideovaida_hinhbannguyet190k_3.webp'),
(50, 23, '../img/TuiXachVaVi/23.Yummy_Vidangcamtaynhogon49K.webp'),
(51, 23, '../img/TuiXachVaVi/23.Yummy_Vidangcamtaynhogon49K_2.webp'),
(52, 24, '../img/TuiXachVaVi/24.Yummy_tuideocheodayrut_190k.webp'),
(53, 24, '../img/TuiXachVaVi/24.Yummy_tuideocheodayrut_190k_2.webp'),
(54, 25, '../img/TuiXachVaVi/25.Hapas_viCardgapdoijeans_250k.webp'),
(55, 25, '../img/TuiXachVaVi/25.Hapas_viCardgapdoijeans_250k_2.webp'),
(56, 26, '../img/TuiXachVaVi/26.Hapas_tuideovaiAuraHobo_700k.webp'),
(57, 26, '../img/TuiXachVaVi/26.Hapas_tuideovaiAuraHobo_700k_2.webp'),
(58, 27, '../img/TuiXachVaVi/27.Hapas_tuixachtaybacguong_900k.webp'),
(59, 27, '../img/TuiXachVaVi/27.Hapas_tuixachtaybacguong_900k_2.webp'),
(60, 27, '../img/TuiXachVaVi/27.Hapas_tuixachtaybacguong_900k_3.webp'),
(61, 28, '../img/TuiXachVaVi/28.Lesac_tuideovai_500k.webp'),
(62, 28, '../img/TuiXachVaVi/28.Lesac_tuideovai_500k_2.webp'),
(63, 29, '../img/TuiXachVaVi/29.Lesac_vinuminiTongueWallet_250k.webp'),
(64, 29, '../img/TuiXachVaVi/29.Lesac_vinuminiTongueWallet_250k_2.webp'),
(65, 30, '../img/TuiXachVaVi/30.Lesac_tuideovainuCelina_520k.webp'),
(66, 30, '../img/TuiXachVaVi/30.Lesac_tuideovainuCelina_520k_2.webp'),
(67, 31, '../img/PhuKienToc/31.CaiTocNoTo30k.jpeg'),
(68, 32, '../img/PhuKienToc/32.Kep5RangCharmThach2k.jpeg'),
(69, 32, '../img/PhuKienToc/32.Kep5RangCharmThach2k_2.jpeg'),
(70, 33, '../img/PhuKienToc/33.KepMaiSaoBienPhunMau9k.jpeg'),
(71, 34, '../img/PhuKienToc/34.KepMiniXaCuBau35k.jpeg'),
(72, 35, '../img/PhuKienToc/35.Keptocdai7cm20k_2.jpg'),
(73, 36, '../img/PhuKienToc/36.keptocthatnutchuthap10k.jpg'),
(74, 36, '../img/PhuKienToc/36.keptocthatnutchuthap_2.jpeg'),
(75, 37, '../img/PhuKienToc/37.scrunchieRen10k.jpeg'),
(76, 37, '../img/PhuKienToc/37.scrunchieRen10k_2.jpeg'),
(77, 38, '../img/DongHo/38.Casio_donghonudaykimloai_600k.webp'),
(78, 38, '../img/DongHo/38.Casio_donghonudaykimloai_600k_2.webp'),
(79, 39, '../img/DongHo/39.Casio_donghonuthepkhonggi_650k.webp'),
(80, 40, '../img/DongHo/40.Casio_donghonudaykimloaimatmauxanh_1500k_2.webp'),
(81, 41, '../img/DongHo/41.Ciloa_donghothachanhchongthamnuoc_462K.webp'),
(82, 41, '../img/DongHo/41.Ciloa_donghothachanhchongthamnuoc_462K_2.webp'),
(83, 42, '../img/DongHo/42.Ciloa_donghodaydeoxuongca_560k.webp'),
(84, 42, '../img/DongHo/42.Ciloa_donghodaydeoxuongca_560k_2.webp'),
(85, 43, '../img/DongHo/43.Clioa_donghonukimcuongmaubac_400k.webp'),
(86, 43, '../img/DongHo/43.Clioa_donghonukimcuongmaubac_400k_2.webp'),
(87, 44, '../img/TrangSuc/44.botrangsucno_2.webp'),
(88, 44, '../img/TrangSuc/44.vongco.webp'),
(89, 44, '../img/TrangSuc/44.vongtay.webp'),
(90, 44, '../img/TrangSuc/44.botrangsuc.webp'),
(91, 44, '../img/TrangSuc/44.bongtai.webp'),
(92, 44, '../img/TrangSuc/44.botrangsucno.PNG');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(9) NOT NULL,
  `product_id` int(9) DEFAULT NULL,
  `quantity_added` int(9) DEFAULT NULL,
  `quantity_removed` int(9) DEFAULT NULL,
  `note` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marquee`
--

CREATE TABLE `marquee` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marquee`
--

INSERT INTO `marquee` (`id`, `message`, `status`, `sort_order`) VALUES
(1, 'Khuyến mãi lớn! Giảm giá 28% tất cả đồng hồ ! ', 'active', 1),
(2, 'Miễn phí giao hàng toàn quốc!', 'inactive', 2),
(3, 'Hot! Bộ trang sức nơ giảm 22% !', 'inactive', 3);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `link`, `sort_order`) VALUES
(0, 'Trang Chủ', './', 0),
(1, 'Trang Sức', 'categories.php?category=1', 1),
(2, 'Túi Xách Và Ví', 'categories.php?category=2', 2),
(3, 'Phụ Kiện Tóc', 'categories.php?category=3', 3),
(4, 'Đồng Hồ', 'categories.php?category=4', 4),
(5, 'Liên Hệ', 'contact.php', 5),
(7, 'Giới thiệu', 'introduce.php', 6);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'CHỌN QUÀ YÊU – NHIỀU HƠN TỪ YÊU', '💍 Nhẫn đôi – Không chỉ là trang sức, mà là biểu tượng của tình yêu\n\n\r\nCó những món quà chỉ đẹp trong khoảnh khắc, nhưng cũng có những món quà mang giá trị vĩnh cửu. Một chiếc nhẫn đôi không chỉ là món phụ kiện thời trang mà còn là minh chứng cho một lời hẹn ước, một sự gắn kết mãi mãi giữa hai tâm hồn đồng điệu.\n\n\r\n1. **Chiếc nhẫn nhỏ – Tình yêu lớn**\n\r\nMột cái nắm tay, một cái ôm nhẹ, hay một chiếc nhẫn lấp lánh trên ngón tay cũng có thể khiến trái tim rung động. Nhẫn đôi không chỉ đơn thuần là một món đồ trang sức, mà nó còn tượng trưng cho sự đồng hành, một cách ngầm khẳng định rằng \"chúng ta thuộc về nhau\".\n\n\r\n2. **Nhẫn đôi có gì đặc biệt?**\n\r\n✨ Nhẫn bạc S925 – Thanh lịch và tinh tế, thể hiện sự chân thành.\n\r\n💖 Nhẫn vàng đính đá – Lấp lánh, sang trọng, dành cho những ai yêu sự nổi bật.\n\r\n🔗 Nhẫn khắc tên – Cá nhân hóa, để tình yêu có dấu ấn riêng.\n\r\n🇰🇷 Nhẫn đôi phong cách Hàn Quốc – Hiện đại, trẻ trung, phù hợp với mọi khoảnh khắc.\n\n\r\n3. **Tặng nhẫn đôi – Tặng lời yêu thương**\n\r\nTình yêu không chỉ được thể hiện qua những lời nói, mà còn qua những hành động nhỏ nhặt nhất. Một chiếc nhẫn đôi không chỉ thể hiện sự gắn kết mà còn là minh chứng rằng dù cách xa nhau, hai trái tim vẫn chung một nhịp đập.\n\n\r\n💎 \"Có những thứ dù nhỏ bé nhưng lại chứa đựng cả một thế giới yêu thương.\"\n\n\r\nNếu bạn đang tìm kiếm một món quà đặc biệt để gửi đến nửa kia, hãy để nhẫn đôi làm cầu nối cho tình yêu của bạn! ❤️', '2025-05-22 11:20:13'),
(2, 'PHỤ KIỆN TẠO SỰ KHÁC BIỆT: PHÁI ĐẸP KHÔNG THỂ THIẾU', 'Phụ kiện không chỉ là điểm nhấn trong thời trang, mà còn là dấu ấn cá nhân, thể hiện phong cách và gu thẩm mỹ của mỗi người. Đối với phái đẹp, những món phụ kiện nhỏ bé lại mang sức mạnh vô cùng lớn—chúng giúp nâng tầm diện mạo và tạo nên sự khác biệt đầy cuốn hút.\n\n\r\n1. **Trang sức – Khi vẻ đẹp trở thành nghệ thuật**\n\r\nCó những món đồ bạn có thể dễ dàng bỏ qua, nhưng một chiếc nhẫn tinh tế, một đôi bông tai lấp lánh, hay một sợi dây chuyền nhẹ nhàng lại có thể khiến bạn tỏa sáng. Trang sức không chỉ làm đẹp mà còn là cách để thể hiện cá tính và phong thái riêng:\n\r\n- Nhẫn đôi – Biểu tượng của tình yêu và sự gắn kết\n\r\n- Dây chuyền bạc S925 – Tinh tế, nhẹ nhàng, phù hợp với mọi phong cách\n\r\n- Vòng tay đá phong thủy – Không chỉ thời trang mà còn mang ý nghĩa tích cực về năng lượng\n\n\r\n2. **Túi xách – Nàng tự tin, phong cách nâng tầm**\n\r\nTúi xách không chỉ là vật dụng mang theo bên mình mà còn là phụ kiện giúp khẳng định gu thời trang. Từ túi mini nhỏ nhắn đến túi tote thanh lịch, một chiếc túi phù hợp có thể khiến bộ trang phục trở nên hoàn hảo hơn.\n\n\r\n3. **Phụ kiện tóc – Điểm nhấn cho sự nữ tính**\n\r\nMột chiếc kẹp tóc xinh xắn, một chiếc scrunchie nhẹ nhàng, hay một băng đô thời trang đều có thể khiến mái tóc trở nên ấn tượng và thanh lịch hơn. Đừng xem nhẹ những món phụ kiện nhỏ—chúng giúp gương mặt bạn tỏa sáng theo cách riêng.\n\n\r\n4. **Đồng hồ – Hơn cả một món trang sức**\n\r\nKhông chỉ để xem giờ, đồng hồ còn là tuyên ngôn về phong cách. Một chiếc đồng hồ thanh lịch sẽ làm nổi bật vẻ hiện đại, trẻ trung, hoặc quyền lực của người đeo.\n\n\r\n~~ Phụ kiện không chỉ làm đẹp mà còn nói lên bạn là ai\n\r\nMỗi món phụ kiện đều mang đến sự tinh tế, quyến rũ và cá tính cho chủ nhân của nó. Hãy để những món đồ nhỏ bé này làm nổi bật phong cách riêng của bạn, giúp bạn tỏa sáng mọi lúc mọi nơi! ✨💖', '2025-05-22 11:28:13'),
(3, '5 CÁCH BẢO QUẢN BẠC TRẮNG SÁNG', 'Bạc là một trong những chất liệu được yêu thích bởi vẻ đẹp tinh tế và sang trọng. Tuy nhiên, theo thời gian, bạc có thể bị xỉn màu nếu không được bảo quản đúng cách. Dưới đây là 5 mẹo đơn giản giúp bạn giữ trang sức bạc luôn trắng sáng:\n\n\r\n1. **Tránh tiếp xúc với hóa chất**\n\r\nCác hóa chất có trong nước hoa, mỹ phẩm, hoặc chất tẩy rửa có thể làm bạc bị oxi hóa nhanh hơn. Hãy tháo trang sức bạc trước khi dùng những sản phẩm này.\n\n\r\n2. **Lau sạch sau mỗi lần sử dụng**\n\r\nSau khi đeo, bạn nên lau nhẹ trang sức bằng khăn mềm để loại bỏ mồ hôi và bụi bẩn, giúp bạc giữ được độ sáng bóng.\n\n\r\n3. **Sử dụng cách làm sạch tự nhiên**\n\r\nBạn có thể dùng baking soda, giấm trắng hoặc nước muối để làm sạch bạc. Những phương pháp này giúp loại bỏ lớp xỉn màu một cách an toàn mà không làm ảnh hưởng đến chất liệu bạc.\n\n\r\n4. **Bảo quản bạc đúng cách**\n\r\nTrang sức bạc nên được cất trong hộp kín, có lớp vải mềm để tránh trầy xước và hạn chế tiếp xúc với không khí gây oxi hóa.\n\n\r\n5. **Đeo bạc thường xuyên**\n\r\nNghe có vẻ lạ, nhưng việc đeo bạc thường xuyên giúp hạn chế tình trạng xỉn màu vì bạc có thể tự làm sạch khi tiếp xúc với da người.\n\n\r\nGiữ bạc sáng bóng không hề khó, chỉ cần áp dụng những mẹo đơn giản trên, trang sức của bạn sẽ luôn lung linh như mới!', '2025-05-22 11:28:32'),
(4, 'TÚI XÁCH – MÓN PHỤ KIỆN KHÔNG THỂ THIẾU CỦA PHÁI ĐẸP', 'Túi xách không chỉ đơn thuần là vật dụng giúp mang theo những món đồ cần thiết mà còn là biểu tượng của phong cách, đẳng cấp và cá tính. Một chiếc túi phù hợp có thể nâng tầm diện mạo, giúp bạn tự tin hơn khi bước xuống phố.\n\n\r\n1. **Túi xách – Phụ kiện “định hình” phong cách**\n\r\nMỗi chiếc túi đều mang một vẻ đẹp riêng, phản ánh gu thời trang của người sở hữu:\n\r\n🎒 Túi tote – Đơn giản nhưng thanh lịch, dành cho cô nàng năng động.\n\r\n👜 Túi đeo chéo – Gọn nhẹ, tiện lợi, thích hợp cho những chuyến đi dạo phố.\n\r\n👛 Clutch sang trọng – Món đồ không thể thiếu khi dự tiệc hoặc các sự kiện quan trọng.\n\r\n🛍 Túi mini thời thượng – Xu hướng hot trong làng thời trang, tạo điểm nhấn ấn tượng.\n\n\r\n2. **Chất liệu túi xách – Yếu tố tạo nên đẳng cấp**\n\r\nTúi không chỉ đẹp mà còn cần có chất liệu bền bỉ. Một chiếc túi da thật mang lại vẻ sang trọng, trong khi túi vải canvas lại giúp nàng thể hiện sự trẻ trung. Hiện nay, các dòng túi bảo vệ môi trường như túi làm từ da thực vật hoặc túi tái chế cũng rất được ưa chuộng.\n\n\r\n3. **Cách chọn túi xách phù hợp với dáng người**\n\r\n🔹 Dáng người nhỏ nhắn → Chọn túi mini hoặc túi có dây đeo dài giúp tôn dáng.\n\r\n🔹 Dáng cao gầy → Túi có thiết kế mềm mại hoặc túi tote sẽ cân bằng tỷ lệ cơ thể.\n\r\n🔹 Dáng đầy đặn → Túi có kích thước trung bình, tránh túi quá nhỏ hoặc quá to.\n\n\r\n4. **Những cách phối túi xách giúp nàng nổi bật**\n\r\n🔸 Cùng màu với trang phục → Set đồ trở nên thanh lịch, tinh tế hơn.\n\r\n🔸 Tạo điểm nhấn → Túi có màu sắc nổi bật sẽ làm sáng cả bộ trang phục.\n\r\n🔸 Phối theo phong cách → Túi thể thao đi cùng street style, clutch phù hợp với váy dạ hội.\n\n\r\n~~ Túi xách không chỉ là món phụ kiện đơn thuần, mà là biểu tượng của phong cách riêng biệt. Hãy chọn cho mình một chiếc túi phù hợp để tự tin hơn mỗi ngày! ✨', '2025-05-22 11:28:56'),
(5, 'ĐỒNG HỒ - BIỂU TƯỢNG CỦA PHONG CÁCH VÀ SỰ TINH TẾ', 'Đồng hồ không chỉ là một công cụ xem giờ mà còn là món phụ kiện khẳng định phong cách của phái đẹp. Một chiếc đồng hồ nữ phù hợp có thể tôn lên vẻ duyên dáng, thanh lịch, đồng thời tạo điểm nhấn cho trang phục hàng ngày.\n\n\r\n1. **Đồng hồ – Hơn cả một món trang sức**\n\r\nMột chiếc đồng hồ không chỉ giúp quản lý thời gian mà còn là điểm nhấn hoàn hảo cho phong cách:\n\r\n⌚ Đồng hồ dây kim loại – Vẻ đẹp mạnh mẽ, bền bỉ dành cho người yêu sự sang trọng.\n\r\n🎽 Đồng hồ dây da – Thanh lịch, cổ điển, phù hợp với mọi phong cách.\n\r\n🏃 Đồng hồ thể thao – Hiện đại, tiện ích, dành cho người yêu vận động.\n\n\r\n2. **Chọn đồng hồ phù hợp với phong cách**\n\r\n✨ Phong cách tối giản → Chọn đồng hồ mặt tròn, thiết kế đơn giản.\n\r\n💼 Doanh nhân lịch lãm → Đồng hồ dây kim loại hoặc dây da sang trọng.\n\r\n🔥 Cá tính, trẻ trung → Đồng hồ điện tử hoặc kiểu dáng phá cách.\n\r\n🏋️ Năng động, thể thao → Đồng hồ chống nước, bền bỉ, nhiều tính năng thông minh.\n\n\r\n3. **Cách bảo quản đồng hồ luôn sáng đẹp như mới**\n\r\n✔ Tránh tiếp xúc với nước nếu không phải đồng hồ chống nước.\n\r\n✔ Lau mặt kính và dây bằng khăn mềm để tránh trầy xước.\n\r\n✔ Bảo quản đồng hồ ở nơi khô ráo, tránh nhiệt độ quá cao.\n\n\r\n~~ Đồng hồ không chỉ giúp bạn quản lý thời gian mà còn là cách bạn tạo nên dấu ấn cá nhân. Hãy chọn cho mình một chiếc đồng hồ phù hợp để khẳng định phong cách riêng!', '2025-05-22 11:31:30');

-- --------------------------------------------------------

--
-- Table structure for table `news_images`
--

CREATE TABLE `news_images` (
  `id` int(11) NOT NULL,
  `news_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_images`
--

INSERT INTO `news_images` (`id`, `news_id`, `image`) VALUES
(1, 1, '../img/TrangSuc/Nhan/10.nhandoi1.webp'),
(2, 1, '../img/TrangSuc/Nhan/11.nhandoivatcheodinhda.webp'),
(3, 1, '../img/TrangSuc/Nhan/11.nhandoivatcheodinhda_2.webp'),
(4, 2, '../img/TrangSuc/Nhan/9.duoicadinhda_2.webp'),
(5, 2, '../img/TrangSuc/VongCo/16.canhlanguyetque.webp'),
(6, 2, '../img/TrangSuc/VongTay/17.vongmixngoc.webp'),
(7, 2, '../img/TuiXachVaVi/30.Lesac_tuideovainuCelina_520k_2.webp'),
(8, 2, '../img/PhuKienToc/37.scrunchieRen10k_2.jpeg'),
(9, 2, '../img/DongHo/42.Ciloa_donghodaydeoxuongca_560k.webp'),
(10, 3, '../img/TrangSuc/photo.jpg'),
(11, 4, '../img/TuiXachVaVi/tui.jpg'),
(12, 5, '../img/DongHo/dongho.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(9) NOT NULL,
  `user_id` int(9) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','approved','shipping','delivered','cancelled') DEFAULT 'pending',
  `total_money` double DEFAULT NULL,
  `payment_method` enum('bank_transfer','cod') DEFAULT 'cod',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `payment_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone_number`, `address`, `note`, `order_date`, `status`, `total_money`, `payment_method`, `payment_status`, `payment_date`) VALUES
(1, 2, 'Nguyễn Ngọc Lan', '0987453632', '112 Vĩnh Hưng-Hoàng Mai-Hà Nội', '', '2025-06-07 10:52:07', 'cancelled', 275000, 'bank_transfer', 'pending', NULL),
(2, 2, 'Bùi Thị Thanh Nhàn', '0987453632', '112 Vĩnh Hưng-Hoàng Mai-Hà Nội', '', '2025-06-07 13:41:20', 'cancelled', 625000, 'bank_transfer', 'pending', NULL),
(3, 2, 'Nguyễn Ngọc Ánh', '0964782427', '96 Bạch Đằng-Hai Bà Trưng-Hà Nội', '', '2025-06-07 13:42:25', 'delivered', 29000, 'cod', 'pending', NULL),
(4, 2, 'Nguyễn Ngọc Lan', '0964786423', '96 Bạch Đằng-Hai Bà Trưng-Hà Nội', '', '2025-06-07 17:07:38', 'delivered', 200000, 'cod', 'pending', NULL),
(5, 3, 'Bùi Ngọc Ánh', '0986767777', '18 Bạch Đằng-Hai Bà Trưng-Hà Nội', '', '2025-06-08 16:37:44', 'shipping', 1130000, 'cod', 'pending', NULL),
(6, 2, 'Nguyễn Ngọc Ánh', '0964786423', '112 Vĩnh Hưng-Hoàng Mai-Hà Nội', '', '2025-06-08 22:36:24', 'delivered', 1010000, 'bank_transfer', 'paid', '2025-06-09 00:00:50');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(9) NOT NULL,
  `order_id` int(9) DEFAULT NULL,
  `product_id` int(9) DEFAULT NULL,
  `price` int(9) DEFAULT NULL,
  `quantity` int(9) DEFAULT NULL,
  `total_money` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `price`, `quantity`, `total_money`) VALUES
(1, 1, 25, 250000, 1, 250000),
(2, 2, 38, 600000, 1, 600000),
(3, 3, 32, 2000, 2, 4000),
(4, 4, 3, 150000, 1, 150000),
(5, 5, 40, 1080000, 1, 1080000),
(6, 6, 14, 320000, 3, 960000);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(9) NOT NULL,
  `category_id` int(9) DEFAULT NULL,
  `product_name` varchar(350) DEFAULT NULL,
  `price` int(9) DEFAULT NULL,
  `discount` int(9) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `stock_quantity` int(9) DEFAULT NULL,
  `sold_count` int(9) NOT NULL DEFAULT 0,
  `status` varchar(20) DEFAULT NULL,
  `deleted` int(3) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category_id`, `product_name`, `price`, `discount`, `image`, `description`, `stock_quantity`, `sold_count`, `status`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 5, 'Bông tai đính nơ', 100000, NULL, NULL, '-Chất liệu: Bạc S925 \n\r\n-Kích thước : 1.1x0.6 cm \n\r\n-Màu sắc đá: bạc \n\r\n-Giao hàng toàn quốc \n\r\n-Bảo hành sản phẩm 24 tháng \n\r\n-Xuất xứ: Việt Nam.', 200, 10, 'available', 0, '2025-05-08 23:25:25', '2025-05-08 23:25:25'),
(2, 5, 'Bông tai dáng bầu', 180000, NULL, NULL, '- Chất liệu: Bạc S925 (92,5% Bạc và 7,5% kim loại khác sản xuất theo công nghệ Bạc Ý) hàng gia công kĩ bóng đẹp, không han gỉ, không dị ứng, dễ làm sáng và bảo quản\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Bông tai được gia công vô cùng kĩ lưỡng, tỉ mỉ tạo nên một sản phẩm trang sức hoàn hảo', 100, 0, 'available', 0, '2025-05-08 23:41:01', '2025-05-08 23:41:01'),
(3, 5, 'Bông tai nơ', 150000, NULL, NULL, '- Chất liệu: Bạc S925 tinh khiết an toàn không gây kích ứng da + đính đá Zirconia lấp lánh\n\r\n- Kích thước: 1,3cm\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Bộ trang sức bạc được thiết kế thanh lịch, trẻ trung theo xu hướng mới nhất của ngành phụ kiện trang sức\n\r\n- Tặng kèm hộp đựng trang sức cao cấp sang trọng\n\r\n- Bảo hành làm sáng sản phẩm trọn đời', 50, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(4, 5, 'Bông tai gắn nơ kèm ngọc trai ', 400000, NULL, NULL, '- Chất liệu: Bạc S925 xi vàng, ngọc trai nhân tạo 5mm\n\r\n- Kích thước: Chi tiết mô tả trên hình ảnh sản phẩm\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Bộ trang sức bạc được thiết kế thanh lịch, trẻ trung theo xu hướng mới nhất của ngành phụ kiện trang sức\n\r\n- Tặng kèm hộp đựng trang sức cao cấp', 50, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(5, 5, 'Bông tai hoa năm cánh ', 100000, NULL, NULL, '- Chất liệu: Bạc S925 tinh khiết an toàn không gây kích ứng da\n\r\n- Kích thước: 0,6cm\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Bộ trang sức bạc được thiết kế thanh lịch, trẻ trung theo xu hướng mới nhất của ngành phụ kiện trang sức\n\r\n- Tặng kèm hộp đựng trang sức cao cấp sang trọng\n\r\n- Bảo hành làm sáng sản phẩm trọn đời', 67, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(6, 6, 'Nhẫn gắn nơ ', 120000, NULL, NULL, '- Kích thước: 4,5,6,7,8,9,10\r\n\r\n- Màu sắc: Bạc\r\n\r\n- Chất liệu: Bạc ta, đá Zirconia', 10, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(7, 6, 'Nhẫn cánh hồ điệp', 130000, NULL, NULL, '- Chất liệu: Bạc ta\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Nhẫn được gia công vô cùng kỹ lưỡng, tỉ mỉ tạo nên một sản phẩm trang sức hoàn hảo', 20, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(8, 6, 'Nhẫn ổ đá bốn chấu', 130000, NULL, NULL, '- Chất liệu nhẫn bạc: Bạc S925 (92,5% Bạc và 7,5% kim loại khác sản xuất theo công nghệ Bạc Ý) hàng gia công kĩ bóng đẹp, không han gỉ, không dị ứng, dễ làm sáng và bảo quản\n\r\n- Kiểu cách nhẫn nữ: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Nhẫn bạc nữ được gia công vô cùng kĩ lưỡng, tỉ mỉ tạo nên một sản phẩm trang sức hoàn hảo', 38, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(9, 6, 'Nhẫn đuôi cá đính đá', 150000, NULL, NULL, '- Chất liệu: Bạc ta\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Nhẫn được gia công vô cùng kỹ lưỡng, tỉ mỉ tạo nên một sản phẩm trang sức hoàn hảo', 12, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(10, 6, 'Nhẫn đôi đính đá', 500000, NULL, NULL, '- Chất liệu: Bạc S925 (92,5% Bạc và 7,5% kim loại khác sản xuất theo công nghệ Bạc Ý) hàng gia công kĩ bóng đẹp, không han gỉ, không dị ứng, dễ làm sáng và bảo quản\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Khắc tên, ngày tháng miễn phí theo yêu cầu khi mua 1 CẶP NHẪN (tối đa 8 kí tự/1 nhẫn)\n\r\n- Kích cỡ: Nhẫn được làm theo size tay của khách hàng (có hướng dẫn đo size tay chi tiết) hoặc bạn có thể nhắn tin với shop để được tư vấn cụ thể hơn', 13, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(11, 6, 'Nhẫn đôi vát chéo đính đá', 540000, NULL, NULL, '- Chất liệu: Bạc S925 (92,5% Bạc và 7,5% hợp kim cao cấp sản xuất theo công nghệ Bạc Ý), Nhẫn nữ đính đá Zirconia cao cấp lấp lánh\n\r\n- Kích thước: Nhẫn freesize, cỡ nam nữ đều chỉnh được, nhẫn nam riêng và nhẫn nữ riêng\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Bộ trang sức bạc được thiết kế thanh lịch, trẻ trung theo xu hướng mới nhất của ngành phụ kiện trang sức', 11, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(12, 7, 'Vòng cổ ngọc trai', 450000, NULL, NULL, '- Xuất xứ: Việt Nam\n\r\n- Kiểu dáng: vòng cổ ngọc trai\n\r\n- Chất liệu: bạc', 5, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(13, 7, 'Vòng cổ đính đá cao cấp', 340000, NULL, NULL, '- Chất liệu: Bạc S925 (92,5% Bạc và 7,5% hợp kim cao cấp)\n\r\n\nHƯỚNG DẪN BẢO QUẢN VÀ LÀM SÁNG BẠC\n\r\n- Tránh tiếp xúc với các chất hoá học làm bay phai màu bạc\n\r\n- Nên vệ sinh khuyên tai bằng nước rửa bạc ít nhất 1 tháng 1 lần để sản phẩm luôn sáng bóng\n\r\n- Khi không sử dụng thì bảo quản trong túi zip và hộp đựng', 6, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(14, 7, 'Vòng cổ ngọc trai nơ', 320000, NULL, NULL, '- Chất liệu: Bạc S925 (92,5% Bạc và 7,5% hợp kim cao cấp)\n\r\n\nHƯỚNG DẪN BẢO QUẢN VÀ LÀM SÁNG BẠC\n\r\n- Tránh tiếp xúc với các chất hoá học làm bay phai màu bạc\n\r\n- Nên vệ sinh khuyên tai bằng nước rửa bạc ít nhất 1 tháng 1 lần để sản phẩm luôn sáng bóng\n\r\n- Khi không sử dụng thì bảo quản trong túi zip và hộp đựng', 3, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(15, 7, 'Vòng cổ hoa bách nhật', 220000, NULL, NULL, '- Chất liệu: Bạc chuẩn 925\n\r\n- Bộ sản phẩm gồm: 1 chiếc dây chuyền\n\r\n- Kiểu dáng: thiết kế dây chuyền bạc nữ tinh tế sắc sảo, dây chuyền nữ mẫu mã mới nhất theo Trend!\n\r\n- Sản xuất: vòng cổ bạc nữ được sản xuất trực tiếp tại xưởng Việt Nam hoặc nhập khẩu', 4, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(16, 7, 'Vòng cổ cành lá nguyệt quế', 230000, NULL, NULL, '- Chất liệu: Bạc S925 (92,5% Bạc và 7,5% kim loại khác sản xuất theo công nghệ Bạc Ý) hàng gia công kĩ bóng đẹp, không han gỉ, không dị ứng, dễ làm sáng và bảo quản\n\r\n- Kiểu dáng: vòng cổ cành lá nguyệt quế\n\r\n- Dây chuyền được gia công vô cùng kĩ lưỡng, tỉ mỉ tạo nên một sản phẩm trang sức hoàn hảo', 4, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(17, 8, 'Vòng tay mix ngọc ', 210000, NULL, NULL, '- Chất liệu: Bạc S925 tinh khiết an toàn không gây kích ứng da, Hạt ngọc trai nuôi nước ngọt, chất lượng ngọc AA\n\r\n- Kích thước: vòng tay 16cm, kích cỡ ngọc: Hạt gạo 4mm\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Bộ trang sức bạc được thiết kế thanh lịch, trẻ trung theo xu hướng mới nhất của ngành phụ kiện trang sức\n\r\n- Tặng kèm hộp đựng trang sức cao cấp sang trọng', 3, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(18, 8, 'Vòng tay dây xù lấp lánh', 240000, NULL, NULL, '- Chất liệu: Bạc S925 (92,5% Bạc và 7,5% hợp kim cao cấp sản xuất theo công nghệ Bạc Ý)\n\r\n- Kích thước: 15,5 + 3cm đoạn tùy chỉnh\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Lắc tay bạc được thiết kế thanh lịch, trẻ trung theo xu hướng mới nhất của ngành phụ kiện trang sức', 4, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(19, 8, 'Vòng tay cỏ bốn lá may mắn', 150000, NULL, NULL, '- Chất liệu: Bạc S925 tinh khiết + đá Zirconia cao cấp lấp lánh\n\r\n- Chiều dài: 15-20cm tuỳ chỉnh nới rộng bằng dạng rút\n\r\n- Màu sắc: Màu trắng sáng lấp lánh\n\r\n- Sản phẩm mới, đảm bảo về chất lượng, độ bóng sáng\n\r\n- Kiểu dáng thiết kế tinh tế, sắc sảo, gia công tỉ mỉ, mẫu mới nhất theo Trend\n\r\n- Xuất xứ: mẫu được sản xuất tại Việt Nam hoặc nhập khẩu', 3, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(20, 8, 'Vòng tay dây rút đính đá ', 170000, NULL, NULL, '- Chất liệu: Bạc S925 tinh khiết\n\r\n- Kích thước: Dạng DÂY RÚT có thể điều chỉnh theo size tay\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Bộ trang sức bạc được thiết kế thanh lịch, trẻ trung theo xu hướng mới nhất của ngành phụ kiện trang sức\n\r\n- Tặng kèm hộp đựng trang sức cao cấp sang trọng\n\r\n- Bảo hành làm sáng sản phẩm trọn đời', 3, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(21, 8, 'Vòng tay hình trái tim', 155000, NULL, NULL, '- Chất liệu: Bạc S925 tinh khiết an toàn không gây kích ứng da\n\r\n- Kích thước: 15.5cm, + 3cm dây điều chỉnh\n\r\n- Kiểu cách: Thiết kế thanh lịch, trẻ trung, tinh tế, sắc sảo\n\r\n- Bộ trang sức bạc được thiết kế thanh lịch, trẻ trung theo xu hướng mới nhất của ngành phụ kiện trang sức\n\r\n- Tặng kèm hộp đựng trang sức cao cấp sang trọng', 3, 0, 'available', 0, '2025-05-09 00:16:31', '2025-05-09 00:16:31'),
(22, 9, 'Yummy - Túi đeo chéo vai da hình bán nguyệt', 190000, NULL, NULL, '- Chất liệu: Da tổng hợp cao cấp\n\r\n- Kích thước: Dài 23cm x Rộng 7 x Cao 12.5cm\n\r\n- Chiều dài tối đa dây đeo chéo: 76cm\n\r\n- Số ngăn: 3 ngăn (1 ngăn chính, 2 ngăn phụ)\n\r\n- Công dụng: Đựng tiền, điện thoại, son, sổ tay nhỏ,…\n\r\n- Trọng lượng: 326 grams\n\r\n- Bảo hành: 12 tháng\n\r\n- Xuất xứ: Việt Nam', 50, 0, 'available', 0, '2025-05-15 23:38:38', NULL),
(23, 9, 'Yummy - Ví dáng cầm tay nhỏ gọn', 15000, NULL, NULL, '- Chất liệu: Da tổng hợp cao cấp\n\r\n- Kích thước: Dài 10cm, Rộng 1.5cm, Cao 8cm\n\r\n- Số ngăn: 7 ngăn (1 ngăn chính, 6 ngăn đựng thẻ)\n\r\n- Công dụng: Đựng thẻ, tiền gấp gọn, giấy tờ tùy thân,…\n\r\n- Trọng lượng: 64 grams', 45, 0, 'available', 0, '2025-05-15 23:40:49', NULL),
(24, 9, 'Yummy - Túi đeo chéo dây rút', 190000, NULL, NULL, '- Chất liệu: Da tổng hợp cao cấp\n\r\n- Kích thước: Dài 21cm, rộng 14cm, cao 16.5 cm\n\r\n- Số ngăn: 2 ngăn (1 ngăn chính, 1 ngăn phụ)\n\r\n- Công dụng: Đựng tiền, điện thoại, ví tiền,…\n\r\n- Trọng lượng: 294 grams', 40, 0, 'available', 0, '2025-05-15 23:41:08', NULL),
(25, 10, 'Hapas - Ví gấp đôi jeans', 250000, NULL, NULL, '- Kích thước: 12 x 2 x 9 cm\n\r\n- Trẻ trung, cá tính\n\r\n- Phù hợp với mọi lứa tuổi\n\r\n- Màu sắc basic dễ phối đồ\n\r\n- Phù hợp với đi chơi, đi du lịch', 35, 0, 'available', 0, '2025-05-15 23:56:25', NULL),
(26, 10, 'Hapas - Túi đeo vai aura hobo', 700000, NULL, NULL, '- Kích thước: 27 x 6 x 19 cm\n\r\n- Trẻ trung, cá tính\n\r\n- Phù hợp với mọi lứa tuổi\n\r\n- Màu sắc basic dễ phối đồ\n\r\n- Phù hợp với đi chơi, đi du lịch\n\r\n- Bảo hành: 6 tháng', 40, 0, 'available', 0, '2025-05-15 23:56:25', NULL),
(27, 10, 'Hapas - Túi xách tay bạc gương', 900000, NULL, NULL, '- Kích thước: 19 x 7 x 10 cm\n\r\n- Chất liệu: Da\n\r\n- Trẻ trung, cá tính\n\r\n- Phù hợp với mọi lứa tuổi\n\r\n- Màu sắc basic dễ phối đồ\n\r\n- Phù hợp với đi chơi, đi du lịch', 30, 0, 'available', 0, '2025-05-15 23:56:25', NULL),
(28, 11, 'Lesac - Túi đeo vai', 500000, NULL, NULL, '- Kích thước: 19 x 24 x 7cm\n\r\n- Kiểu khóa: Khóa kéo\n\r\n- Chất liệu: Da PU mịn chắc chắn\n\r\n- Phù hợp để sử dụng: Đi làm, đi chơi, dạo phố', 45, 0, 'available', 0, '2025-05-15 23:56:25', NULL),
(29, 11, 'Lesac - Ví nữ minu Tongue Wallet', 250000, NULL, NULL, '- Kích thước: 11,6 x 9,2 x 2cm\n\r\n- Kiểu khóa: Khóa kéo\n\r\n- Chất liệu: Da PU sần nhẹ, chắc chắn\n\r\n- Phù hợp để sử dụng: Đi làm, đi chơi, dạo phố', 38, 0, 'available', 0, '2025-05-15 23:56:25', NULL),
(30, 11, 'Lesac - Túi đeo vai nữ Celina', 520000, NULL, NULL, '- Hoạ tiết: Một màu, da PU mịn lì\n\r\n- Kích thước: 24 x 14 x 6cm\n\r\n- Túi khoá có 1 ngăn to chính, 2 ngăn phụ: 1 ngăn nhỏ, 1 ngăn khoá zip', 42, 0, 'available', 0, '2025-05-15 23:56:25', NULL),
(31, 3, 'Cài tóc nơ to', 30000, NULL, NULL, '- Chất liệu: nhựa PVC không mùi\n\r\n- Kiểu dáng: đa dạng\n\r\n- Thiết kế ngọt ngào, thời thượng theo phong cách Hàn Quốc, phù hợp khi các cô nàng ở nhà, đi học, đi làm, đi picnic và ngay cả những bữa tiệc sang trọng.\n', 18, 0, 'available', 0, '2025-05-16 00:23:56', NULL),
(32, 3, 'Kẹp 5 răng charm thạch', 2000, NULL, NULL, '- Chất liệu: nhựa PVC không mùi,charm gel thạch\r\n\r\n- Thiết kế ngọt ngào, thời thượng theo phong cách Hàn Quốc, phù hợp khi các cô nàng ở nhà, đi học, đi làm, đi picnic.\r\n', 15, 0, 'available', 0, '2025-05-16 00:23:56', NULL),
(33, 3, 'Kẹp mái sao biển phun màu', 9000, NULL, NULL, '- Loại sản phẩm: Kẹp tóc.\n\r\n- Màu sắc: trong suốt\n\r\n- Chất liệu: nhựa & hợp kim\n\r\n- Kích thước chiều dài: khoảng 5 cm.', 12, 0, 'available', 0, '2025-05-16 00:23:56', NULL),
(34, 3, 'Kẹp mini xà cừ bầu', 35000, NULL, NULL, '- Hình dạng: sao biển\n\r\n- Thiết kế: kết hợp kẹp mỏ vịt với sao biển để tạo ra chiếc kẹp tóc dễ thương.\n\r\n- Chúng có thể được sử dụng để kết hợp với nhiều kiểu trang phục hàng ngày khác nhau, tăng thêm sự dễ thương và vui tươi cho hầu hết phụ nữ có mái tóc dài hoặc ngắn.\n\r\n- Chất liệu: nhựa thông, không mùi, không độc hại, không dễ phai màu. Chất liệu bền và chắc chắn làm cho chúng đẹp và tinh tế hơn.\n\r\n- Kích thước sản phẩm: 5.5 x 5.5 x 1cm', 17, 0, 'available', 0, '2025-05-16 00:23:56', NULL),
(35, 3, 'Kẹp tóc dài 7cm', 20000, NULL, NULL, '- Chất liệu: nhựa PVC giả vân đá\n\r\n- Thiết kế ngọt ngào, thời thượng theo phong cách Hàn Quốc, phù hợp khi các cô nàng ở nhà, đi học, đi làm, đi picnic và ngay cả những bữa tiệc sang trọng.\n', 19, 0, 'available', 0, '2025-05-16 00:23:56', NULL),
(36, 3, 'Kẹp tóc thắt nút chữ thập', 10000, NULL, NULL, '- Chất liệu: nhựa PP\n\r\n- Chiều dài: 7 cm\n\r\n- Kiểu dáng: đa dạng\n\r\n- Thiết kế ngọt ngào, thời thượng theo phong cách Hàn Quốc, phù hợp khi các cô nàng ở nhà, đi học, đi làm, đi picnic và ngay cả những bữa tiệc sang trọng.', 14, 0, 'available', 0, '2025-05-16 00:23:56', NULL),
(37, 3, 'Scrunchies ren', 10000, NULL, NULL, '- Chất liệu: nhựa cao cấp\n\r\n- Kiểu dáng: đuôi cá\n\r\n- Chiều dài: 12cm\n\r\n- Thiết kế ngọt ngào, thời thượng theo phong cách Hàn Quốc, phù hợp khi các cô nàng ở nhà, đi học, đi làm, đi picnic và ngay cả những bữa tiệc sang trọng.', 16, 0, 'available', 0, '2025-05-16 00:23:56', NULL),
(38, 12, 'Casio - Đồng hồ nữ dây kim loại', 600000, 28, NULL, '- Các tính năng:\n\r\n  • Chống nước 3ATM\n\r\n  • Thiết kế đơn giản, dễ sử dụng\n\r\n  - Dây đeo bằng thép không gỉ\n\r\n  - Tuổi thọ pin xấp xỉ: 3 năm với pin SR626SW\n\r\n  - Kích thước vỏ: 31×25×8mm\n\r\n  - Tổng trọng lượng: 21g', 18, 0, 'available', 0, '2025-05-16 00:44:17', NULL),
(39, 12, 'Casio - Đồng hồ nữ thép không gỉ', 650000, 28, NULL, '- Các tính năng:\n\r\n  - Chống nước 3ATM\n\r\n  - Dây đeo bằng thép không gỉ\n\r\n  - Chốt gập ba\n\r\n  - Dây đeo bằng thép không gỉ\n\r\n  - Tuổi thọ pin xấp xỉ: 3 năm với pin SR626SW\n\r\n  - Kích thước vỏ: 34×28,2×7,5mm\n\r\n  - Tổng trọng lượng: 52g', 17, 0, 'available', 0, '2025-05-16 00:44:17', NULL),
(40, 12, 'Casio - Đồng hồ nữ kim loại mặt màu xanh', 1500000, 28, NULL, '- Kích thước vỏ (Dài × Rộng × Cao): 34,5 × 30,2 × 8,7mm\n\r\n- Trọng lượng: 69g\n\r\n- Vật liệu vỏ / gờ: Mạ ion\n\r\n- Dây đeo: bằng thép không gỉ', 15, 0, 'available', 0, '2025-05-16 00:44:17', NULL),
(41, 13, 'Ciloa - Đồng hồ thạch anh chống thấm nước', 462000, 28, NULL, '- Thương hiệu: CILOA\r\n\r\n- Mặt đồng hồ: Kim\r\n\r\n- Đồng hồ đeo tay: Thạch anh\r\n\r\n- Kiểu đồng hồ: Thời trang\r\n\r\n- Độ sâu chống nước: <30m\r\n\r\n- Kính đồng hồ: Kính Thủy tinh\r\n\r\n- Hạn bảo hành: 3 năm\r\n\r\n- Gửi từ: Nước ngoài', 12, 0, 'available', 0, '2025-05-16 00:44:17', '2025-06-08 08:46:26'),
(42, 13, 'Ciloa - Đồng hồ dây đeo xương cá', 560000, 28, NULL, '- Đồng hồ đeo tay: Thạch anh\r\n\r\n- Đường kính vỏ đồng hồ: 24mm\r\n\r\n- Chất liệu vỏ đồng hồ: Thép không gỉ\r\n\r\n- Chất liệu dây đeo: Thép không gỉ\r\n\r\n- Độ sâu chống nước: 30m - 50m\r\n\r\n- Hạn bảo hành: 5 năm', 14, 0, 'available', 0, '2025-05-16 00:44:17', '2025-06-08 08:47:43'),
(43, 13, 'Ciloa - Đồng hồ nữ kim cương màu bạc', 400000, 28, NULL, '- Thương hiệu: CILOA\r\n\r\n- Đồng hồ đeo tay: Thạch anh\r\n\r\n- Chất liệu vỏ đồng hồ: Thép không gỉ\r\n\r\n- Chất liệu dây đeo: Thép không gỉ\r\n\r\n- Độ sâu chống nước: <30m\r\n\r\n- Kính đồng hồ: Kính Thủy tinh\r\n\r\n- Hạn bảo hành: 3 năm', 16, 0, 'available', 0, '2025-05-16 00:44:17', '2025-06-08 08:47:29'),
(44, 1, 'Bộ trang sức nơ bạc s925 đính đá ', 600000, 22, NULL, '1. Thông số sản phẩm.\r\n\r\n- Màu sắc: Bạc\r\n\r\n- Chất liệu: Bạc S925\r\n\r\n- Kích thước: Dây chuyền chỉ có một kích thước, nhưng có thể dùng các chốt điều chỉnh để căn chỉnh chiều dài từ 40cm đến 45 cm\r\n\r\n\r\n2. Bộ trang sức gồm\r\n\r\n- Vòng tay\r\n\r\n- Dây chuyền\r\n\r\n- Khuyên tai\r\n\r\n- Hộp đựng\r\n\r\n- Túi', 10, 0, 'available', 0, '2025-05-22 18:16:29', '2025-05-22 18:16:29');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(9) NOT NULL,
  `user_id` int(9) DEFAULT NULL,
  `product_id` int(9) DEFAULT NULL,
  `rating` int(5) DEFAULT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 2, 14, 5, 'quá tuyệt vờiiii', '2025-06-09 07:24:00', '2025-06-09 07:24:00'),
(2, 2, 3, 5, 'quá đẹp ạa', '2025-06-09 07:44:13', '2025-06-09 07:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `id` int(9) NOT NULL,
  `order_id` int(9) DEFAULT NULL,
  `shipping_method` varchar(50) DEFAULT NULL,
  `shipping_address` varchar(50) DEFAULT NULL,
  `tracking_number` varchar(20) DEFAULT NULL,
  `shipping_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(9) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  `deleted` int(3) DEFAULT 0,
  `role` enum('admin','customer') DEFAULT 'customer',
  `birthday` date DEFAULT NULL,
  `sex` enum('male','female','other') DEFAULT 'other'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `phone_number`, `password`, `created_at`, `updated_at`, `deleted`, `role`, `birthday`, `sex`) VALUES
(1, 'admin', 'ntt.thuy0901@gmail.com', NULL, '66c6a7e2cc1d7b8b68f4dffbde8cf032', NULL, NULL, 0, 'admin', NULL, 'other'),
(2, 'Nguyễn Thu Thủy', 'nttthuy.dhmt16a2hn@sv.uneti.edu.vn', '0964786423', 'f41781bfacf939243e4e97191e1e5fc4', '2025-05-28 00:09:37', '2025-05-28 00:09:37', 0, 'customer', '2010-02-03', 'female'),
(3, 'Bùi Ngọc Ánh ', 'ngocanh77@email.com', '0986767777', 'f43ba321bc5451f646cb6e428f1bb052', '2025-06-08 16:23:55', '2025-06-08 16:23:55', 0, 'customer', NULL, 'other'),
(4, 'Hoài Thu', 'hoaithu223@gmail.com', '0964786426', '615cee61eae08eb13e7d97f7cc5bf36e', '2025-06-08 17:49:36', '2025-06-08 17:49:36', 0, '', '2008-01-18', 'female'),
(5, 'Hoài Nam', 'hoainam83@email.com', '0964786421', '0303c892d27617a0103b75d62d0f1a3f', '2025-06-08 18:00:44', '2025-06-08 18:00:44', 0, '', '2025-05-28', 'male');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer`
--
ALTER TABLE `footer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `marquee`
--
ALTER TABLE `marquee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_images`
--
ALTER TABLE `news_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_id` (`news_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `footer`
--
ALTER TABLE `footer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marquee`
--
ALTER TABLE `marquee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news_images`
--
ALTER TABLE `news_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `news_images`
--
ALTER TABLE `news_images`
  ADD CONSTRAINT `news_images_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `shipping_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
