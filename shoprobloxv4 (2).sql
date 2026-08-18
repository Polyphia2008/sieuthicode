-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th4 16, 2025 lúc 12:58 AM
-- Phiên bản máy phục vụ: 10.6.20-MariaDB-cll-lve-log
-- Phiên bản PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `gdnjkyv_shoprobloxv4`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `sub_id` int(11) DEFAULT 0,
  `type` text DEFAULT NULL,
  `type_category` text DEFAULT NULL,
  `username_post` text DEFAULT NULL,
  `detail` longtext DEFAULT NULL,
  `image` text DEFAULT NULL,
  `money` int(11) DEFAULT 0,
  `sale` int(11) NOT NULL DEFAULT 0,
  `fake` int(11) NOT NULL DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'on',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `advertisement`
--

CREATE TABLE `advertisement` (
  `id` int(11) NOT NULL,
  `stt` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `advertisement`
--

INSERT INTO `advertisement` (`id`, `stt`, `image`, `link`, `status`, `created_at`) VALUES
(1, 0, '/upload/link/linkNUGL.png', 'dsadsada', 0, '2025-02-21 13:50:02'),
(3, 0, '/upload/link/linkI2RN.png', 'dsadsa', 0, '2025-02-21 13:57:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bank`
--

CREATE TABLE `bank` (
  `id` int(11) NOT NULL,
  `short_name` text NOT NULL,
  `accountNumber` text NOT NULL,
  `accountName` text NOT NULL,
  `url_api` text DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banned_ips`
--

CREATE TABLE `banned_ips` (
  `id` int(11) NOT NULL,
  `ip` varchar(55) DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `banned` int(11) NOT NULL DEFAULT 0,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `stt` int(11) DEFAULT 1,
  `image` text DEFAULT NULL,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `banner`
--

INSERT INTO `banner` (`id`, `stt`, `image`, `status`) VALUES
(1, 3, '/upload/banner/bannerA8TC.png', 1),
(3, 3, '/upload/banner/banner7HDB.png', 1),
(4, 4, '/upload/banner/bannerGOTJ.png', 1),
(5, 1, '/upload/banner/banner9U83.png', 1),
(6, 2, '/upload/banner/banner3QN9.png', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `display` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `view` int(11) NOT NULL DEFAULT 0,
  `footer` int(11) NOT NULL DEFAULT 0,
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `boostings`
--

CREATE TABLE `boostings` (
  `id` int(11) NOT NULL,
  `stt` int(11) DEFAULT 1,
  `name` text DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `boostings`
--

INSERT INTO `boostings` (`id`, `stt`, `name`, `slug`, `status`) VALUES
(2, 3, 'Dịch vụ cày thuê', 'dich-vu-cay-thue', 1),
(3, 2, 'Bán Item Roblox', 'ban-item-roblox', 1),
(4, 1, 'Dịch Vụ Game Hot', 'dich-vu-game-hot', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `trans_id` varchar(255) DEFAULT NULL,
  `telco` varchar(255) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL DEFAULT 0,
  `serial` text DEFAULT NULL,
  `pin` text DEFAULT NULL,
  `status` varchar(55) NOT NULL DEFAULT 'pending',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `stt` int(11) DEFAULT 1,
  `name` text DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `stt`, `name`, `slug`, `icon`, `content`, `status`) VALUES
(1, 4, 'Tài Khoản Liên Quân Tuỳ Chọn', 'tai-khoan-lien-quan-tuy-chon', '/upload/icon/iconKTSV.png', 'VuG7m2kgaMOgbmcgbmfDoG4gdMOgaSBraG/huqNuIExpw6puIFF1w6JuIG5nb24sIGdpw6EgY+G6oyBo4bujcCBsw70gLSBMw6Agc+G7sSBs4buxYSBjaOG7jW4gcGjDuSBo4bujcCBjaG8gYuG6oW4=', 1),
(2, 5, 'FREE FIRE', 'free-fire', '/upload/icon/icon5E9L.png', '', 1),
(5, 2, 'GIẢM GIÁ TÀI KHOẢN ROBLOX CUỐI NĂM', 'giam-gia-tai-khoan-roblox-cuoi-nam', '/upload/icon/icon1WV4.png', '', 1),
(7, 3, 'Tài Khoản Liên Minh Tuỳ Chọn', 'tai-khoan-lien-minh-tuy-chon', '/upload/icon/iconSVBT.png', 'VuG7m2kgaMOgbmcgbmfDoG4gdMOgaSBraG/huqNuIExNSFQgbmdvbiwgZ2nDoSBj4bqjIGjhu6NwIGzDvSAtIEzDoCBz4buxIGzhu7FhIGNo4buNbiBwaMO5IGjhu6NwIGNobyBi4bqhbi4=', 1),
(8, 1, 'Random Fisch', 'random-fisch', '/upload/icon/iconRSA7.png', 'ZGVtbw==', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category_items`
--

CREATE TABLE `category_items` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `unit` text DEFAULT NULL,
  `factor` float DEFAULT 0,
  `min_value` int(11) DEFAULT 0,
  `max_value` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `category_items`
--

INSERT INTO `category_items` (`id`, `name`, `unit`, `factor`, `min_value`, `max_value`, `status`) VALUES
(1, 'Roblox', 'Robux', 0.002, 10000, 500000, 1),
(2, 'Ngá»c rá»“ng online', 'Thá»i vĂ ng', 10, 20000, 1000000, 1),
(4, 'LiĂªn quĂ¢n', 'QuĂ¢n huy', 0.001, 10000, 500000, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_attempts`
--

CREATE TABLE `failed_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `create_gettime` datetime NOT NULL,
  `type` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Đang đổ dữ liệu cho bảng `failed_attempts`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `acc_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `flash_sales`
--

CREATE TABLE `flash_sales` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `flash_sales`
--

INSERT INTO `flash_sales` (`id`, `name`, `start_time`, `end_time`) VALUES
(11, 'dsadsa', '2025-02-13 10:00:00', '2025-02-13 15:00:00'),
(12, 'dsadsa', '2025-02-13 16:00:00', '2025-02-13 19:00:00'),
(13, 'fdsfs', '2025-02-13 20:00:00', '2025-02-13 23:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `flash_sale_products`
--

CREATE TABLE `flash_sale_products` (
  `id` int(11) NOT NULL,
  `flash_sale_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `discount_price` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `flash_sale_products`
--

INSERT INTO `flash_sale_products` (`id`, `flash_sale_id`, `product_id`, `discount_price`, `created_at`) VALUES
(29, 11, 50, 2000, '2025-02-12 11:48:35'),
(30, 11, 28, 2000, '2025-02-12 11:48:35'),
(31, 11, 16, 3000, '2025-02-13 10:58:25'),
(32, 11, 9, 3000, '2025-02-13 10:58:38'),
(33, 11, 12, 3000, '2025-02-13 10:58:38'),
(34, 11, 11, 3000, '2025-02-13 10:58:49'),
(35, 11, 3, 3000, '2025-02-13 10:58:49'),
(36, 11, 4, 3000, '2025-02-13 10:59:01'),
(37, 11, 6, 3000, '2025-02-13 10:59:11'),
(38, 11, 5, 3000, '2025-02-13 10:59:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `history_buy`
--

CREATE TABLE `history_buy` (
  `id` int(11) NOT NULL,
  `id_acc` int(11) NOT NULL,
  `trans_id` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT 0,
  `type_category` text DEFAULT NULL,
  `username` text DEFAULT NULL,
  `username_post` text DEFAULT NULL,
  `detail` longtext DEFAULT NULL,
  `cash` int(11) NOT NULL DEFAULT 0,
  `cost` int(11) NOT NULL DEFAULT 0,
  `created_at` text DEFAULT NULL,
  `updated_at` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `user_id` text DEFAULT NULL,
  `trans_id` text DEFAULT NULL,
  `payment_method` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `create_time` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ip_white`
--

CREATE TABLE `ip_white` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `stt` int(11) NOT NULL DEFAULT 0,
  `title` varchar(250) DEFAULT NULL,
  `slug` varchar(250) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `links`
--

INSERT INTO `links` (`id`, `stt`, `title`, `slug`, `image`, `link`, `status`, `created_at`) VALUES
(1, 3, 'Nạp Tiền Ngay', 'nap-tien-ngay', '/upload/link/linkHQUE.png', '/customer/deposit', 1, '2025-02-11 05:20:50'),
(2, 2, 'Fanpage hỗ trợ', 'fanpage-ho-tro', '/upload/link/linkJYEV.png', 'https://www.facebook.com/nguyennhatloc?locale=vi_VN', 1, '2025-02-11 05:22:49'),
(3, 1, 'Giảm giá giờ vàng', 'giam-gia-gio-vang', '/upload/link/linkT0IX.png', '/flashsale', 1, '2025-02-11 05:23:25'),
(4, 4, 'Minigame hấp dẫn', 'minigame-hap-dan', '/upload/link/link8LUD.png', '/minigame', 1, '2025-02-11 05:23:53'),
(5, 5, 'Facebook', 'facebook', '/upload/link/link9ADZ.png', 'https://www.facebook.com/nguyennhatloc?locale=vi_VN', 1, '2025-02-23 07:40:33'),
(6, 6, 'Zalo', 'zalo', '/upload/link/linkZSXN.png', 'https://zalo.me/0978364572', 1, '2025-02-23 07:41:06'),
(7, 7, 'Telegram', 'telegram', '/upload/link/linkL3ZH.png', 'https://t.me/locdzme', 1, '2025-02-23 07:41:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` text DEFAULT NULL,
  `device` text DEFAULT NULL,
  `create_date` text DEFAULT NULL,
  `action` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `logs`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `log_balance`
--

CREATE TABLE `log_balance` (
  `id` int(11) NOT NULL,
  `trans_id` varchar(50) DEFAULT NULL,
  `money_before` text DEFAULT NULL,
  `money_change` text DEFAULT NULL,
  `money_after` text DEFAULT NULL,
  `type` enum('-','+') DEFAULT NULL,
  `time` timestamp NULL DEFAULT NULL,
  `content` text DEFAULT NULL,
  `user_id` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `log_balance`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `log_ref`
--

CREATE TABLE `log_ref` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `sotientruoc` int(11) DEFAULT 0,
  `sotienthaydoi` int(11) DEFAULT 0,
  `sotienhientai` int(11) DEFAULT 0,
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `log_ref`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Đang đổ dữ liệu cho bảng `options`
--

INSERT INTO `options` (`id`, `key`, `value`) VALUES
(1, 'title', 'Shop acc game liên quân uy tín giá rẻ, bảo hành trọn đời, nhiều minigame event hấp dẫn, rút quân huy tự động, hỗ trợ 24/24.'),
(2, 'description', 'Shop Game Liên Quân Uy Tín Giá Rẻ Số 1 Việt Nam. Cung Cấp Acc Giá Rẻ Nhất Thị Trường. Nhận Quân Huy, Skin Vip Và Nick Xịn Xò Miễn Phí Hàng Ngày'),
(3, 'keywords', ''),
(4, 'author', 'Admin'),
(5, 'email_smtp', ''),
(6, 'pass_email_smtp', ''),
(11, 'noidung_naptien', 'NAPTIEN_'),
(12, 'thongbao', ''),
(13, 'anhbia', '/upload/theme/OLCXS.png'),
(14, 'favicon', '/upload/theme/QCT87R.png'),
(17, 'logo', '/upload/theme/J5KNLMH1O8.png'),
(18, 'theme_color1', '#ff0000'),
(27, 'minrut_ctv', '10000'),
(29, 'status_withdraw_ctv', '1'),
(30, 'popup_home', '<p style=\"text-align:start\"><span style=\"font-size:14px\"><span style=\"color:#1c1c1c\"><span style=\"background-color:#ffffff\">🎉&nbsp;<strong>TẾT SI&Ecirc;U DEAL &ndash; CƠ HỘI V&Agrave;NG CHỈ C&Oacute; MỘT LẦN!</strong>&nbsp;🎉<br />\r\n🔥&nbsp;<span style=\"color:#e03e2d\"><strong>X3 nạp thẻ</strong></span>&nbsp;&ndash; Nạp 100K nhận ngay 300K, lợi gấp 3!<br />\r\n🎁&nbsp;<strong>Acc s&uacute;ng LV7&nbsp;<span style=\"color:#e03e2d\">chỉ từ 50K</span></strong>&nbsp;&ndash; Tết n&agrave;y săn ngay, kẻo lỡ cơ hội v&agrave;ng!</span></span></span></p>\r\n\r\n<p style=\"text-align:start\"><span style=\"font-size:14px\"><span style=\"color:#1c1c1c\"><span style=\"background-color:#ffffff\">💸&nbsp;<strong>Thu mua acc gi&aacute; cao</strong>, thanh to&aacute;n si&ecirc;u tốc</span></span></span></p>\r\n\r\n<p style=\"text-align:start\"><span style=\"font-size:14px\"><span style=\"color:#1c1c1c\"><span style=\"background-color:#ffffff\">⏳&nbsp;<strong>Ưu đ&atilde;i c&oacute; hạn</strong>&nbsp;&ndash; Chốt nhanh để đ&oacute;n Tết trọn niềm vui! 🎮✨</span></span></span></p>\r\n'),
(31, 'hotline', ''),
(32, 'email', ''),
(33, 'theme_color', '#ff0000'),
(34, 'notice_withdraw_ctv', '<p>dsa</p>\r\n'),
(42, 'listbank_ctv', 'mbbank\r\nacb'),
(43, 'notice_home', '<h1 style=\"text-align:center\"><strong>Shop Roblox ch&iacute;nh thức. Chuy&ecirc;n nạp robux ch&iacute;nh h&atilde;ng, robux 5 ng&agrave;y, b&aacute;n gamepass, acc roblox, c&agrave;y thu&ecirc; uy t&iacute;n, gi&aacute; rẻ, nhanh gọn</strong></h1>\r\n\r\n<p>Roblox l&agrave; một tr&ograve; chơi trực tuyến rất phổ biến được thiết kế cho mọi lứa tuổi. Trong game, người chơi được tham gia v&agrave;o một thế giới ảo đầy m&agrave;u sắc v&agrave; đa dạng với h&agrave;ng ng&agrave;n tr&ograve; chơi kh&aacute;c nhau được tạo ra bởi cộng đồng người chơi tr&ecirc;n to&agrave;n thế giới. Roblox cho ph&eacute;p người sử dụng Roblox Studio - một c&ocirc;ng cụ mạnh mẽ để thiết kế v&agrave; x&acirc;y dựng c&aacute;c tr&ograve; chơi của ri&ecirc;ng m&igrave;nh. Sau đ&oacute;, họ c&oacute; thể chia sẻ tr&ograve; chơi đ&oacute; với c&aacute;c người chơi kh&aacute;c tr&ecirc;n to&agrave;n cầu v&agrave; nhận được phản hồi từ cộng đồng. Đ&oacute; cũng ch&iacute;nh l&agrave; một điểm đặc biệt th&uacute; vị của Roblox.&nbsp;Với đồ họa sắc n&eacute;t v&agrave; &acirc;m thanh sống động, Roblox mang đến cho người chơi một trải nghiệm giải tr&iacute; tuyệt vời.</p>\r\n\r\n<p>C&oacute;&nbsp;hơn 100 triệu người chơi tr&ecirc;n to&agrave;n thế giới, Roblox được cho&nbsp;l&agrave; một trong những game trực tuyến phổ biến nhất hiện nay. N&oacute; kh&ocirc;ng chỉ đơn thuần l&agrave; một tr&ograve; chơi, m&agrave; c&ograve;n l&agrave; một cộng đồng mạnh mẽ, nơi người chơi c&oacute; thể tương t&aacute;c v&agrave; chia sẻ sở th&iacute;ch của m&igrave;nh với nhau. Ch&iacute;nh v&igrave; thế m&agrave; nhu cầu nạp game cũng đang ng&agrave;y một tăng l&ecirc;n đ&aacute;ng kể.&nbsp;Nắm bắt được nhu cầu của kh&aacute;ch h&agrave;ng ch&uacute;ng t&ocirc;i đ&atilde; ph&aacute;t triển h&igrave;nh thức Nạp Robux gi&aacute; rẻ v&ocirc; c&ugrave;ng tiện lợi v&agrave; an to&agrave;n.</p>\r\n\r\n<h2><strong>Robux l&agrave; g&igrave; ?&nbsp;C&aacute;c c&aacute;ch nạp robux hiện nay</strong></h2>\r\n\r\n<h3><strong>1. Robux l&agrave; g&igrave;?</strong></h3>\r\n\r\n<p>Robux l&agrave; đơn vị tiền tệ chung được sử dụng trong hệ thống&nbsp;tr&ograve; chơi Roblox v&agrave; l&agrave; đơn vị duy nhất v&agrave; quan trọng trong Roblox. Ch&uacute;ng được giao dịch h&agrave;ng ng&agrave;y bởi c&aacute;c game thủ để sở hữu trang phục cho nh&acirc;n vật của họ v&agrave; khả năng đặc biệt m&agrave; họ c&oacute; thể thực hiện trong c&aacute;c trận đấu hoặc họ cũng c&oacute; thể sử dụng để tạo nh&oacute;m, c&aacute;c bang v&agrave; thiết kế vị tr&iacute; của bạn trong thế giới game. Robux thường được sử dụng để mua c&aacute;c mặt h&agrave;ng độc quyền chỉ c&oacute; sẵn trong cửa h&agrave;ng trong game trong một thời gian giới hạn. Mặc d&ugrave; Roblox l&agrave; miễn ph&iacute;, nhưng vẫn c&oacute; phi&ecirc;n bản cao cấp cho ph&eacute;p người chơi truy cập v&agrave;o tất cả c&aacute;c chế độ tr&ograve; chơi. C&oacute; nghĩa l&agrave; ở&nbsp;một số chế độ tr&ograve; chơi kh&ocirc;ng thể truy cập miễn ph&iacute; v&agrave; y&ecirc;u cầu họ phải chi ti&ecirc;u Robux để chỉ đơn giản l&agrave; chơi game.&nbsp;</p>\r\n\r\n<p>Bạn c&oacute; thể t&igrave;m mua Robux th&ocirc;ng qua c&aacute;c trang web b&ecirc;n thứ ba với gi&aacute; rẻ hơn. Tuy nhi&ecirc;n thực trạng lừa đảo tr&ecirc;n thị trường nạp game diễn ra kh&aacute; phổ biến n&ecirc;n c&aacute;c bạn cần t&igrave;m hiểu kỹ trước khi đưa ra quyết định tin tưởng. H&atilde;y đảm bảo rằng địa chỉ nạp Robux đ&oacute; l&agrave; uy t&iacute;n v&agrave; an to&agrave;n</p>\r\n\r\n<h3><strong>2. L&agrave;m c&aacute;ch n&agrave;o để nạp Robux</strong></h3>\r\n\r\n<p>C&oacute; kh&aacute; nhiều h&igrave;nh thức để nạp Robux cho bạn lựa chọn:</p>\r\n\r\n<ul>\r\n	<li>Thanh to&aacute;n trực tuyến: nạp Robux bằng c&aacute;ch sử dụng c&aacute;c phương thức thanh to&aacute;n trực tuyến như thẻ t&iacute;n dụng, thẻ nạp điện thoại, v&iacute; điện tử như PayPal, ZaloPay, Momo, Viettel Money, VTC Pay,...</li>\r\n	<li>Nạp qua t&agrave;i khoản Apple hoặc Google Play: Nếu bạn đang sử dụng thiết bị iOS hoặc Android, bạn c&oacute; thể nạp Robux bằng c&aacute;ch sử dụng t&agrave;i khoản Apple hoặc Google Play.</li>\r\n	<li>Sử dụng Gift Card: Roblox cũng cung cấp Gift Card (thẻ qu&agrave; tặng) cho người d&ugrave;ng, người chơi c&oacute; thể mua thẻ qu&agrave; tặng n&agrave;y tại c&aacute;c cửa h&agrave;ng như Best Buy, Walmart, Amazon, v.v...</li>\r\n	<li>Nạp Robux th&ocirc;ng qua c&aacute;c website v&agrave; dịch vụ của b&ecirc;n thứ ba: Ngo&agrave;i việc nạp Robux trực tiếp tr&ecirc;n website ch&iacute;nh thức của Roblox, người chơi cũng c&oacute; thể sử dụng c&aacute;c website v&agrave; dịch vụ của b&ecirc;n thứ ba để mua Robux. Tuy nhi&ecirc;n, trước khi sử dụng bất kỳ dịch vụ nạp Robux n&agrave;o, người chơi cần kiểm tra t&iacute;nh an to&agrave;n v&agrave; uy t&iacute;n của trang web đ&oacute; để tr&aacute;nh rủi ro.</li>\r\n</ul>\r\n\r\n<p>Một trong những website nạp robux an to&agrave;n v&agrave; uy t&iacute;n hiện nay c&oacute; gần như đầy đủ c&aacute;c phương thức nạp robux hiện nay ch&iacute;nh l&agrave; website:&nbsp;<a href=\"https://shopsheep.net/\" rel=\"follow\" target=\"_blank\">shopsheep.net</a>. Tại shopsheep.net ch&uacute;ng t&ocirc;i c&oacute; c&aacute;c h&igrave;nh thức nạp robux như sau: nạp robux bằng thẻ c&agrave;o điện thoại, nạp robux bằng thẻ game, nạp robux bằng ATM, nạp robux bằng v&iacute; điện tự MoMo</p>\r\n\r\n<h2><strong>Giới thiệu c&aacute;c sản phẩm của shop</strong></h2>\r\n\r\n<h3><strong>1. Nạp Robux - Gamepass</strong></h3>\r\n\r\n<p>C&oacute; thể mua Robux trực tuyến để sử dụng trong game Roblox v&agrave; mua c&aacute;c sản phẩm v&agrave; dịch vụ kh&aacute;c. Robux l&agrave; loại tiền tệ trong Roblox, gi&uacute;p người chơi mua c&aacute;c gamepass, trang phục, vũ kh&iacute; v&agrave; nhiều hơn thế trong game. Nếu muốn sở hữu c&aacute;c vật phẩm đặc biệt trong game hoặc trải nghiệm t&iacute;nh năng mới, bạn phải mua Robux.</p>\r\n\r\n<p><img alt=\"Nạp Robux/ Gamepass\" src=\"https://cdn3.upanh.info/upload/server-sw3/images/T%E1%BA%BFt/D%E1%BB%8Bch%20v%E1%BB%A5/Robux%20120h%20Gamepass.png\" style=\"height:240px; width:400px\" title=\"Nạp Robux/ Gamepass\" /></p>\r\n\r\n<p><em>Nạp Robux/ Gamepass</em></p>\r\n\r\n<p>Để mua Robux, bạn c&oacute; thể truy cập website của ch&uacute;ng t&ocirc;i v&agrave; chọn sản phẩm Robux m&agrave; bạn muốn mua. Sau đ&oacute;, bạn sẽ được hướng dẫn qua qu&aacute; tr&igrave;nh thanh to&aacute;n, bao gồm c&aacute;c bước như chọn h&igrave;nh thức thanh to&aacute;n, nhập th&ocirc;ng tin v&agrave; x&aacute;c nhận giao dịch. Khi giao dịch ho&agrave;n tất, Robux sẽ được chuyển v&agrave;o t&agrave;i khoản Roblox của bạn v&agrave; bạn c&oacute; thể sử dụng ch&uacute;ng để mua c&aacute;c sản phẩm trong game.</p>\r\n\r\n<p>Ch&uacute;ng t&ocirc;i cung cấp một loạt c&aacute;c sản phẩm Robux v&agrave; gamepass, bao gồm:</p>\r\n\r\n<ul>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/robux-5-ngay-120h-gia-re\" rel=\"follow\" target=\"_blank\">Robux 5 Ng&agrave;y (120h) - Gi&aacute; Rẻ</a></li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/robux-gamepass-120h\" rel=\"follow\" target=\"_blank\">Robux gamepass</a></li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/mua-gamepass-blox-fruits\" rel=\"follow\" target=\"_blank\">Gamepass Blox Fruit</a></li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/ban-robux-chinh-hang\" rel=\"follow\" target=\"_blank\">Robux ch&iacute;nh h&atilde;ng</a></li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/mua-gamepass-shindo\" rel=\"follow\" target=\"_blank\">Gamepass shindo</a></li>\r\n</ul>\r\n\r\n<h3><strong>2. C&agrave;y thu&ecirc; Roblox</strong></h3>\r\n\r\n<p>Ngo&agrave;i việc mua Robux, chơi tr&ograve; chơi nhận robux, bạn cũng c&oacute; thể chọn dịch vụ c&agrave;y thu&ecirc; Roblox để trải nghiệm game một c&aacute;ch dễ d&agrave;ng hơn nếu bạn đ&atilde; sở hữu một acc roblox. Ch&uacute;ng t&ocirc;i cung cấp dịch vụ c&agrave;y thu&ecirc; Roblox với gi&aacute; cả cạnh tranh v&agrave; chất lượng tốt nhất. Bạn c&oacute; thể chọn game v&agrave; g&oacute;i c&agrave;y thu&ecirc; roblox m&agrave; bạn muốn c&agrave;y v&agrave; ch&uacute;ng t&ocirc;i sẽ gi&uacute;p bạn ho&agrave;n th&agrave;nh nhanh ch&oacute;ng v&agrave; đảm bảo an to&agrave;n cho t&agrave;i khoản của bạn. Ch&uacute;ng t&ocirc;i sử dụng c&ocirc;ng nghệ ti&ecirc;n tiến v&agrave; kỹ thuật an to&agrave;n để c&agrave;y thu&ecirc; roblox v&agrave; giữ t&iacute;nh bảo mật cao nhất cho t&agrave;i khoản của bạn. Bạn c&oacute; thể y&ecirc;n t&acirc;m rằng dịch vụ của ch&uacute;ng t&ocirc;i sẽ gi&uacute;p bạn nhanh ch&oacute;ng đạt được mong muốn v&agrave; y&ecirc;u cầu c&agrave;y thu&ecirc; đề ra để trải nghiệm game tốt hơn.</p>\r\n\r\n<p><img alt=\"Cày thuê Roblox\" src=\"https://cdn3.upanh.info/upload/server-sw3/images/T%E1%BA%BFt/D%E1%BB%8Bch%20v%E1%BB%A5/Cay%20Thue%20Blox%20Fruit_32.png\" style=\"height:240px; width:400px\" title=\"Cày thuê Roblox\" /></p>\r\n\r\n<p><em>C&agrave;y thu&ecirc; Roblox</em></p>\r\n\r\n<p>C&aacute;c dịch vụ c&agrave;y thu&ecirc; roblox Shopsheep.net cung cấp bao gồm:</p>\r\n\r\n<ul>\r\n	<li>\r\n	<h3><a href=\"https://shopsheep.net/dich-vu/ban-trai-ac-quy-ruong\" rel=\"follow\" target=\"_blank\">B&aacute;n tr&aacute;i &aacute;c quỷ rương</a></h3>\r\n	</li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/cay-thue-blox-fruit\" rel=\"follow\" target=\"_blank\">C&agrave;y thu&ecirc; Blox Fruit</a></li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/up-toc-v4-blox-fruits-sieu-re\" rel=\"follow\" target=\"_blank\">Up tộc V4 Blox Fruit si&ecirc;u rẻ</a></li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/all-star-tower-defense\" rel=\"follow\" target=\"_blank\">All star tower defense</a></li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/cay-thue-king-legacy\" rel=\"follow\" target=\"_blank\">C&agrave;y thu&ecirc; King Legacy</a></li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/cay-thue-shindo-life\" rel=\"follow\" target=\"_blank\">C&agrave;y thu&ecirc; Shindo Life</a></li>\r\n	<li><a href=\"https://shopsheep.net/dich-vu/cay-thue-pet-simulator-x\" rel=\"follow\" target=\"_blank\">C&agrave;y thu&ecirc; Pet Simulator X</a></li>\r\n</ul>\r\n\r\n<h3><strong>3. B&aacute;n acc Roblox</strong></h3>\r\n\r\n<p>Ngo&agrave;i ra, ch&uacute;ng t&ocirc;i c&ograve;n cung cấp dịch vụ mua Acc Roblox với nhiều lựa chọn t&ugrave;y theo nhu cầu của bạn. Tất cả c&aacute;c t&agrave;i khoản đều được kiểm tra v&agrave; đảm bảo sạch sẽ trước khi b&aacute;n cho bạn. Ch&uacute;ng t&ocirc;i cam kết giữ cho bạn an to&agrave;n v&agrave; tr&aacute;nh xa mọi rủi ro li&ecirc;n quan đến việc mua acc. Shop b&aacute;n acc roblox của ch&uacute;ng t&ocirc;i c&oacute; đầy đủ c&aacute;c thẻ loại acc game roblox như: blox fruit, king lagacy, all star tower defen, anime fighter simulator, grand piece, ....</p>\r\n\r\n<p><img alt=\"Mua acc Roblox\" src=\"https://cdn3.upanh.info/upload/server-sw3/images/T%E1%BA%BFt/Nick/Ban%20Acc%20Roblox_8.png\" style=\"height:240px; width:400px\" title=\"Mua acc Roblox\" /></p>\r\n\r\n<p><em>Mua acc Roblox</em></p>\r\n\r\n<p>C&aacute;c danh mục b&aacute;n acc roblox của ch&uacute;ng t&ocirc;i bao gồm:</p>\r\n\r\n<ul>\r\n	<li><a href=\"https://shopsheep.net/mua-acc/ban-acc-roblox\" rel=\"follow\" target=\"_blank\">B&aacute;n acc Roblox</a></li>\r\n	<li><a href=\"https://shopsheep.net/mua-acc/random-blox-fruit-20k\" rel=\"follow\" target=\"_blank\">Random Blox Fruit 20k</a></li>\r\n	<li><a href=\"https://shopsheep.net/mua-acc/random-blox-fruit-50k\" rel=\"follow\" target=\"_blank\">Random Blox Fruit 50k</a></li>\r\n	<li><a href=\"https://shopsheep.net/mua-acc/random-blox-fruit-120k\" rel=\"follow\" target=\"_blank\">Random Blox Fruit 120k</a></li>\r\n	<li><a href=\"https://shopsheep.net/mua-acc/random-blox-fruit-200k\" rel=\"follow\" target=\"_blank\">Random Blox Fruit 200k</a></li>\r\n	<li><a href=\"https://shopsheep.net/mua-acc/acc-blox-fruit-max-lever-co-leopad\" rel=\"follow\" target=\"_blank\">acc Blox Fruit max lever c&oacute; leopad</a></li>\r\n	<li><a href=\"https://shopsheep.net/mua-acc/acc-blox-fruit-max-lever-co-mochi-v2\" rel=\"follow\" target=\"_blank\">Acc Blox Fruit Max lever C&oacute; Mochi V2</a></li>\r\n	<li><a href=\"https://shopsheep.net/mua-acc/acc-bloxfruit-race-v4-full-gear\" rel=\"follow\" target=\"_blank\">Acc BloxFruit Race V4 Full Gear</a></li>\r\n</ul>\r\n\r\n<h2><strong>L&yacute; do tại sao n&ecirc;n chọn website của ch&uacute;ng t&ocirc;i</strong></h2>\r\n\r\n<p>Mỗi ng&agrave;y, trang web của ch&uacute;ng t&ocirc;i phục vụ cho hơn 50.000 giao dịch nạp Robux gi&aacute; rẻ cho kh&aacute;ch h&agrave;ng. Ngo&agrave;i dịch vụ nạp Robux Roblox ch&iacute;nh h&atilde;ng, ch&uacute;ng t&ocirc;i c&ograve;n cung cấp nhiều dịch vụ chất lượng kh&aacute;c như c&agrave;y thu&ecirc; Blox Fruit, Random Nick Blox Fruit, C&agrave;y Thu&ecirc; Theo Y&ecirc;u Cầu, Mua Gamepass Shindo, Mua Gamepass Blox Fruits, Mua Gamepass Anime Fighters Simulator, V&ograve;ng quay Robux, Lật thẻ săn Robux, m&aacute;y x&egrave;ng Robux,...</p>\r\n\r\n<p>Cửa h&agrave;ng Robux của ch&uacute;ng t&ocirc;i c&oacute; giao diện đơn giản nhưng hiện đại, thuận tiện cho việc truy cập v&agrave; sử dụng tr&ecirc;n mọi thiết bị kết nối Internet như smartphone, tablet, laptop,... Chỉ cần chọn số Robux cần nạp, đặt h&agrave;ng v&agrave; thanh to&aacute;n trực tuyến, bạn sẽ nhận được tiền ảo trong game Roblox một c&aacute;ch nhanh ch&oacute;ng v&agrave; dễ d&agrave;ng.</p>\r\n\r\n<p><img alt=\"​​​​Lý do nên chọn website shopsheep.net \" src=\"https://cdn3.upanh.info/upload/server-sw3/images/screenshot_1736393523.png\" style=\"height:781px; width:1471px\" title=\"​​​​Lý do nên chọn website shopsheep.net \" /></p>\r\n\r\n<p><em>​​​​L&yacute; do n&ecirc;n chọn website</em></p>\r\n\r\n<p>Thời gian giao dịch v&agrave; nhận tiền Robux trong game chỉ mất chưa đầy 30 gi&acirc;y, kh&ocirc;ng cần phải đợi l&acirc;u để trải nghiệm. Ch&uacute;ng t&ocirc;i cam kết b&aacute;n Robux một c&aacute;ch an to&agrave;n v&agrave; đảm bảo uy t&iacute;n h&agrave;ng đầu. Ch&uacute;ng t&ocirc;i cũng cam kết bảo vệ th&ocirc;ng tin t&agrave;i khoản của kh&aacute;ch h&agrave;ng bằng c&aacute;ch sử dụng c&aacute;c biện ph&aacute;p bảo mật tối ưu v&agrave; cao cấp nhất.</p>\r\n\r\n<p>Đội ngũ nh&acirc;n vi&ecirc;n chuy&ecirc;n nghiệp của ch&uacute;ng t&ocirc;i sẵn s&agrave;ng phục vụ kh&aacute;ch h&agrave;ng mọi l&uacute;c mọi nơi, 24/7 qua website, email v&agrave; fanpage. Ch&uacute;ng t&ocirc;i cam kết đặt lợi &iacute;ch của kh&aacute;ch h&agrave;ng l&ecirc;n h&agrave;ng đầu v&agrave; cung cấp dịch vụ tốt nhất khi nạp game tại cửa h&agrave;ng.</p>\r\n\r\n<p>Ch&uacute;ng t&ocirc;i cũng cung cấp nhiều h&igrave;nh thức thanh to&aacute;n linh hoạt v&agrave; đa dạng như thanh to&aacute;n qua thẻ c&agrave;o điện thoại, thanh to&aacute;n tự động bằng ATM,... để đem đến sự tiện lợi v&agrave; chi ph&iacute; cho kh&aacute;ch h&agrave;ng!</p>\r\n\r\n<h2><strong>Hướng dẫn mua Robux</strong></h2>\r\n\r\n<p>Để mua robux c&aacute;c bạn thực hiện thao c&aacute;c bước sau:</p>\r\n\r\n<p><strong>Bước 1:&nbsp;</strong>Đăng nhập / Đăng k&iacute; t&agrave;i khoản:</p>\r\n\r\n<p><img alt=\"Đăng nhập, đăng ký\" src=\"https://cdn3.upanh.info/upload/server-sw3/images/Dang%20nhap.png\" style=\"height:827px; width:371px\" title=\"Đăng nhập, đăng ký\" /></p>\r\n\r\n<p><em>Đăng nhập, đăng k&yacute;</em></p>\r\n\r\n<p><strong>Bước 2:</strong>&nbsp;Nạp tiền v&agrave;o t&agrave;i khoản. C&aacute;c bạn c&oacute; thể lựa chọn một trong c&aacute;c h&igrave;nh thức nạp tiền như: nạp thẻ c&agrave;o / nạp bằng ATM, v&iacute; điện tử</p>\r\n\r\n<p><img alt=\"Nạp tiền vào tài khoản\" src=\"https://cdn3.upanh.info/upload/server-sw3/images/screenshot_1736393656.png\" style=\"height:755px; width:364px\" title=\"Nạp tiền vào tài khoản\" /></p>\r\n\r\n<p><em>Nạp tiền v&agrave;o t&agrave;i khoản</em></p>\r\n\r\n<p><strong>Bước 3:&nbsp;</strong>Lựa chọn sản phẩm Robux / Gamepass muốn mua. Với mỗi sản phẩm ch&uacute;ng t&ocirc;i đều c&oacute; hướng dẫn chi tiết c&aacute;c bước để l&agrave;m sao mua được robux / gamepass một c&aacute;ch đơn giản nhất.</p>\r\n\r\n<p><strong>Bước 4:&nbsp;</strong>Thanh to&aacute;n v&agrave; đợi robux/ gamepass về t&agrave;i khoản.</p>\r\n\r\n<h2><strong>Hướng dẫn c&agrave;y thu&ecirc; Roblox</strong></h2>\r\n\r\n<h2>Để thu&ecirc; c&agrave;y roblox c&aacute;c bạn thực hiện thao c&aacute;c bước sau:</h2>\r\n\r\n<p><strong>Bước 1:</strong>&nbsp;Đăng nhập / Đăng k&iacute; t&agrave;i khoản:</p>\r\n\r\n<p><strong>Bước 2:</strong>&nbsp;Nạp tiền v&agrave;o t&agrave;i khoản. C&aacute;c bạn c&oacute; thể lựa chọn một trong c&aacute;c h&igrave;nh thức nạp tiền như: nạp thẻ c&agrave;o / nạp bằng ATM, v&iacute; điện tử,</p>\r\n\r\n<p><strong>Bước 3:&nbsp;</strong>Lựa game muốn c&agrave;y. Với mỗi sản phẩm ch&uacute;ng t&ocirc;i đều c&oacute; hướng dẫn chi tiết c&aacute;c bước để l&agrave;m sao c&aacute;c bạn c&oacute; thể thu&ecirc; c&agrave;y một c&aacute;ch nhanh ch&oacute;ng nhất</p>\r\n\r\n<p><img alt=\"Lựa game muốn cày\" src=\"https://cdn3.upanh.info/upload/server-sw3/images/screenshot_1736393701.png\" style=\"height:678px; width:1410px\" title=\"Lựa game muốn cày\" /></p>\r\n\r\n<p><em>Lựa game muốn c&agrave;y</em></p>\r\n\r\n<p><strong>Bước 4:</strong>&nbsp;Thanh to&aacute;n v&agrave; đợi ho&agrave;n th&agrave;nh đơn. C&aacute;c bạn c&oacute; thể theo d&otilde;i đơn h&agrave;ng</p>\r\n\r\n<h2><strong>Hướng dẫn mua Acc Roblox</strong></h2>\r\n\r\n<h2>Để mua acc roblox c&aacute;c bạn thực hiện thao c&aacute;c bước sau:</h2>\r\n\r\n<p><strong>Bước 1:</strong>&nbsp;Đăng nhập / Đăng k&iacute; t&agrave;i khoản:</p>\r\n\r\n<p><strong>Bước 2:&nbsp;</strong>Nạp tiền v&agrave;o t&agrave;i khoản. C&aacute;c bạn c&oacute; thể lựa chọn một trong c&aacute;c h&igrave;nh thức nạp tiền như: nạp thẻ c&agrave;o / nạp bằng ATM, v&iacute; điện tử</p>\r\n\r\n<p><strong>Bước 3:&nbsp;</strong>Lựa game muốn mua. Với mỗi sản phẩm ch&uacute;ng t&ocirc;i đều c&oacute; hướng dẫn chi tiết c&aacute;c bước để l&agrave;m sao c&aacute;c bạn c&oacute; thể mua được đ&uacute;ng acc roblox như mong muốn của m&igrave;nh một c&aacute;ch đơn giản v&agrave; dễ d&agrave;ng nhất.</p>\r\n\r\n<p><img alt=\"lựa acc game muốn mua\" src=\"https://cdn3.upanh.info/upload/server-sw3/images/screenshot_1736393753.png\" style=\"height:773px; width:1339px\" title=\"lựa acc game muốn mua\" /></p>\r\n\r\n<p><em>Lựa acc game muốn mua</em></p>\r\n\r\n<p><strong>Bước 4:&nbsp;</strong>Thanh to&aacute;n v&agrave; nhận nick. C&aacute;c bạn c&oacute; thể xem lại những nick đ&atilde; mua</p>\r\n'),
(44, 'card_partner_id', ''),
(45, 'card_partner_key', ''),
(46, 'card_url_api', 'https://cardsieutoc.vn/chargingws/v2'),
(47, 'bank_status', '0'),
(53, 'status_minigame', '1'),
(54, 'thumb_items', '/upload/theme/thumb_6A0.png'),
(55, 'status_items', '1'),
(56, 'unit', 'Robux'),
(57, 'min_withdraw', '200'),
(58, 'max_withdraw', '1000'),
(59, 'notice_minigame', 'Vá»›i hĂ ng ngĂ n tĂ i khoáº£n pháº§n quĂ  Ä‘ang chá» báº¡n'),
(60, 'fake_items', '130'),
(61, 'noidungnap', 'hd'),
(62, 'card_status', '0'),
(63, 'status_login_google', '0'),
(64, 'display_api_mbbank', '0'),
(65, 'page_policy', ''),
(66, 'notice_items', '<p><strong>Há»‡ thá»‘ng náº¡p ho&agrave;n to&agrave;n&nbsp;tá»± Ä‘á»™ng.</strong><br />\r\n- Ae chá»n sá»‘ tiá»n náº¡p sáº½ nháº­n Ä‘Æ°á»£c sá»‘ GP tÆ°Æ¡ng á»©ng.<br />\r\n- Sau khi thanh to&aacute;n. GP sáº½ Ä‘Æ°á»£c chuyá»ƒn v&agrave;o t&agrave;i khoáº£n Ä‘á»ƒ ngÆ°á»i chÆ¡i&nbsp;tá»± quy Ä‘á»•i sang Zeni&nbsp;v&agrave;o Game.<br />\r\n- Äá»ƒ kiá»ƒm tra GP hiá»‡n táº¡i c&aacute;c báº¡n click v&agrave;o&nbsp;<a href=\"https://vngates.com/\">Ä&Acirc;Y</a><br />\r\n<br />\r\n<strong>V&eacute; váº­n may:</strong><br />\r\nVá»›i má»—i Ä‘Æ¡n l&agrave; bá»™i sá»‘ cá»§a&nbsp;<strong>200.000Ä‘</strong>, báº¡n sáº½ nháº­n Ä‘Æ°á»£c 1 v&eacute; thá»­ váº­n may.<br />\r\n<em>*Hiá»‡n v&eacute; thá»­ váº­n may Ä‘ang báº£o tr&igrave;, t&iacute; sáº½ cá»™ng láº¡i cho ae Ä‘&atilde; náº¡p h&ocirc;m nay*</em><br />\r\n<br />\r\n<strong>Giftcode Daily V&otilde; Ä&agrave;i&nbsp;T9&nbsp;l&agrave; g&igrave;:</strong><br />\r\n- Vá»›i má»—i Ä‘Æ¡n tr&ecirc;n&nbsp;<strong>500.000Ä‘</strong>, báº¡n sáº½ nháº­n Ä‘Æ°á»£c&nbsp;1<strong>&nbsp;GIFTCODE THáº¦N LINH</strong><br />\r\n- Má»—i nh&acirc;n váº­t chá»‰ sá»­ dá»¥ng Ä‘Æ°á»£c 1 láº§n.<br />\r\n- Háº¡n sá»­ dá»¥ng: 30/11/2023.<br />\r\n- Code bao gá»“m:&nbsp;<strong>&nbsp;5 RÆ°Æ¡ng Ä&aacute;&nbsp;+ 3.000.000 XU&nbsp;+&nbsp;20 Ä&ugrave;i G&agrave;&nbsp;+&nbsp;Danh hiá»‡u Si&ecirc;u Nh&acirc;n</strong></p>\r\n'),
(67, 'status_popup_home', '1'),
(68, 'affiliate_status', '0'),
(69, 'status_flash_sale', '1'),
(70, 'title_shop_items', 'Dá»CH Vá»¤ VDTT - Náº P GAME Tá»° Äá»˜NG'),
(71, 'zalo', ''),
(72, 'facebook', ''),
(73, 'telegram', ''),
(74, 'status_demo', '0'),
(75, 'money_api_vcb', '30000'),
(78, 'notice_topnap', '<p><strong>Nội dung thưởng</strong></p>\r\n'),
(79, 'status_captcha', '0'),
(80, 'secret_key', ''),
(81, 'site_key', ''),
(83, 'ck_ref', '20'),
(84, 'notice_ref', '<ul>\r\n	<li><span style=\"font-size:14px\"><span style=\"color:#000000\">Chia sáº»&nbsp;li&ecirc;n káº¿t n&agrave;y l&ecirc;n máº¡ng x&atilde; há»™i hoáº·c báº¡n b&egrave; cá»§a báº¡n.</span></span></li>\r\n	<li><span style=\"font-size:14px\"><span style=\"color:#000000\">Báº¡n sáº½ nháº­n Ä‘Æ°á»£c hoa há»“ng khi báº¡n b&egrave; Ä‘Æ°á»£c báº¡n giá»›i thiá»‡u sá»­ dá»¥ng dá»‹ch vá»¥ cá»§a API.SIEUTHICODE.NET</span></span></li>\r\n	<li><span style=\"font-size:14px\"><span style=\"color:#000000\">Náº¿u báº¡n A nháº¥n v&agrave;o link tiáº¿p thá»‹ cá»§a báº¡n, báº¡n A náº¡p tiá»n v&agrave;o há»‡ thá»‘ng 100.000Ä‘, báº¡n sáº½ Ä‘Æ°á»£c hoa há»“ng 20% cá»§a 100.000Ä‘ l&agrave; 20.000Ä‘</span></span></li>\r\n	<li><span style=\"font-size:14px\"><span style=\"color:#000000\">TrÆ°á»ng há»£p ngÆ°á»i Ä‘Æ°á»£c báº¡n giá»›i thiá»‡u náº¡p tiá»n sai ná»™i dung, há»‡ thá»‘ng sáº½ kh&ocirc;ng t&iacute;nh hoa há»“ng, chá»‰ t&iacute;nh hoa há»“ng khi ngÆ°á»i báº¡n giá»›i thiá»‡u náº¡p tiá»n th&agrave;nh c&ocirc;ng v&agrave;o há»‡ thá»‘ng.</span></span></li>\r\n	<li><span style=\"font-size:14px\"><span style=\"color:#000000\">Hoa há»“ng sáº½ t&iacute;nh m&atilde;i m&atilde;i, kh&ocirc;ng giá»›i háº¡n thá»i gian n&ecirc;n báº¡n y&ecirc;n t&acirc;m nh&eacute;.</span></span></li>\r\n	<li><span style=\"font-size:14px\"><span style=\"color:#000000\">Nghi&ecirc;m cáº¥m h&agrave;nh vi tá»± giá»›i thiá»‡u báº£n th&acirc;n Ä‘á»ƒ giáº£m gi&aacute; b&aacute;n, ph&aacute;t hiá»‡n sáº½ kh&oacute;a t&agrave;i khoáº£n v&agrave; kh&ocirc;ng duyá»‡t r&uacute;t tiá»n.</span></span></li>\r\n</ul>\r\n'),
(85, 'minrut_ref', '10000'),
(86, 'listbank_ref', 'VCB\r\nMBBANK\r\nACB\r\nBIDV'),
(87, 'status_ref', '1'),
(88, 'prefix_autobank', 'hoadon'),
(89, 'bank_min', '2000'),
(90, 'bank_max', '10000000'),
(91, 'bank_notice', '<p>demo</p>\r\n'),
(92, 'google_app_id', ''),
(93, 'google_app_secret', ''),
(94, 'telegram_status', '0'),
(95, 'telegram_chat_id', ''),
(96, 'telegram_token', ''),
(97, 'noti_recharge', '[{time}] <b>{username}</b> vá»«a náº¡p {amount} vĂ o {method} thá»±c nháº­n {price}.'),
(98, 'noti_action', '[{time}] \r\n- <b>Username</b>: <code>{username}</code>\r\n- <b>Action</b>:  <code>{action}</code>\r\n- <b>IP</b>: <code>{ip}</code>'),
(99, 'card_notice', '<p><span style=\"color:#e74c3c\"><strong>L&AElig;&deg;u &yacute;: Theo quy &Auml;&lsquo;&aacute;&raquo;&lsaquo;nh c&aacute;&raquo;&sect;a nh&agrave; m&aacute;&ordm;&iexcl;ng, n&aacute;&ordm;&iexcl;p sai m&aacute;&raquo;&Dagger;nh gi&aacute; s&aacute;&ordm;&frac12; b&aacute;&raquo;&lsaquo; m&aacute;&ordm;&yen;t th&aacute;&ordm;&raquo;.</strong></span></p>\r\n'),
(100, 'card_ck', '20'),
(101, 'faq', '<p>hi</p>\r\n'),
(102, 'status_event', '1'),
(103, 'balance', '200000'),
(104, 'max', '50'),
(105, 'min', '10'),
(108, 'img_event', '/upload/theme/6T2Z0B.png'),
(109, 'noti_bootsting', '[{time}] \r\n- <b>Username</b>: <code>{username}</code>\r\n- <b>Name</b>:  <code>{name}</code>\r\n- <b>UID</b>: <code>{input_user}</code>\r\n- <b>Password</b>: <code>{input_pass}</code>\r\n- <b>Info</b>: <code>{input_extra}</code>\r\n- <b>Note</b>: <code>{order_note}</code>\r\n- <b>Payment</b>: <code>{payment}</code>'),
(110, 'notice_withdraw', ''),
(111, 'time_support', '24/7'),
(112, 'text_flash_sale', ''),
(113, 'status_banner', '1'),
(114, 'youtube', 'V1fsBqjRAE4'),
(115, 'notice_purchasing', '<p>demo n&egrave; nha</p>\r\n'),
(116, 'background', '/upload/theme/R4C91JN.png'),
(117, 'background_login', '/upload/theme/HTRI58N01A.png'),
(118, 'notice_event', '<p>🎉&nbsp;<strong>NẠP LẦN ĐẦU - RINH NGAY KIM CƯƠNG MIỄN PH&Iacute;!</strong>&nbsp;🎉</p>\r\n\r\n<p>Đừng bỏ lỡ cơ hội c&oacute; 1-0-2 n&agrave;y! Nạp lần đầu tr&ecirc;n shop, nhận ngay kim cương miễn ph&iacute; để thỏa sức mua sắm những acc FreeFire y&ecirc;u th&iacute;ch. Số lượng c&oacute; hạn, nhanh tay l&ecirc;n n&agrave;o!</p>\r\n'),
(119, 'status_only_ip_login_admin', '0'),
(120, 'status_security', '0'),
(121, 'smtp_status', '1'),
(122, 'notice_ctv', '<p>th&ocirc;ng b&aacute;o</p>\r\n');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_sub` varchar(255) NOT NULL,
  `payment` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `receiver` varchar(255) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `package_id` int(11) NOT NULL DEFAULT 0,
  `sub_id` int(11) NOT NULL,
  `image_path` text DEFAULT NULL,
  `admin_note` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name_package` text DEFAULT NULL,
  `unit` varchar(255) NOT NULL,
  `value` float NOT NULL DEFAULT 0,
  `input_user` text NOT NULL,
  `input_pass` text NOT NULL,
  `input_extra` varchar(500) NOT NULL,
  `payment` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `package_id` int(11) NOT NULL,
  `order_note` varchar(255) DEFAULT NULL,
  `admin_note` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `package_boostings`
--

CREATE TABLE `package_boostings` (
  `id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL DEFAULT 0,
  `stt` int(11) DEFAULT 1,
  `name` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `price` int(11) DEFAULT 0,
  `thele` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `package_boostings`
--

INSERT INTO `package_boostings` (`id`, `sub_id`, `stt`, `name`, `image`, `price`, `thele`, `status`, `created_at`) VALUES
(10, 6, 1, 'Cày từ level 1->2550 (Max level)', NULL, 100000, 'PHA+aGloaTwvcD4NCg==', 1, '2025-02-14 12:09:54'),
(11, 6, 1, 'Cày từ level 1->750', NULL, 200000, 'PHA+aGloaTwvcD4NCg==', 1, '2025-02-14 12:10:49'),
(12, 7, 1, 'demo', NULL, 10000, 'PHA+ZGdkPC9wPg0K', 1, '2025-02-14 21:16:25'),
(13, 8, 1, '500 Gems', '/upload/item/itemJOLD.png', 50000, 'PHA+ZGVtbzwvcD4NCg==', 1, '2025-02-14 21:47:37'),
(14, 8, 2, '1250 Gems', '/upload/item/item5VHJ.png', 110000, 'PHA+ZGVtbzwvcD4NCg==', 1, '2025-02-14 21:48:07'),
(15, 8, 3, '2500 Gems', '/upload/item/item2E05.png', 180000, 'PHA+ZGVtbzwvcD4NCg==', 1, '2025-02-14 21:51:02'),
(16, 8, 4, '6000 Gems', '/upload/item/item2Y7B.png', 330000, 'PHA+ZHNhPC9wPg0K', 1, '2025-02-15 11:47:00'),
(17, 8, 4, '25000 Gems', '/upload/item/itemYVC6.png', 1000000, 'PHA+ZHNhPC9wPg0K', 1, '2025-02-15 11:47:22'),
(18, 8, 6, 'VIP', '/upload/item/item3ILG.png', 670000, 'PHA+ZHNhPC9wPg0K', 1, '2025-02-15 11:48:55'),
(19, 8, 6, 'Shiny Hunter', '/upload/item/itemZTUN.png', 225000, 'PHA+ZHNhPC9wPg0K', 1, '2025-02-15 11:49:24'),
(20, 8, 6, 'Extra Unit Storage', '/upload/item/itemETYS.png', 33000, 'PHA+ZHNhPC9wPg0K', 1, '2025-02-15 11:50:02'),
(21, 8, 6, 'Display All Units', '/upload/item/itemUCZ2.png', 112000, 'PHA+ZHNhPC9wPg0K', 1, '2025-02-15 11:50:26'),
(22, 8, 6, '1 Reroll Shards', '/upload/item/itemVZWH.png', 33000, 'PHA+ZHNhPC9wPg0K', 1, '2025-02-15 11:50:50'),
(23, 10, 1, '400 Robux (440 Robux Nếu Có Premium)', '', 148000, '', 1, '2025-02-16 17:28:15'),
(24, 11, 1, 'Lấy Melee Sanguine ART', '', 10000, '', 1, '2025-02-16 17:31:57'),
(25, 11, 1, 'Lấy Shark Anchor', '', 15000, '', 1, '2025-02-16 17:32:07'),
(26, 11, 3, 'Lấy Soul Guitar', '', 20000, '', 1, '2025-02-16 17:32:24'),
(27, 11, 4, 'Lấy Cursed Dual Katana', '', 25000, '', 1, '2025-02-16 17:32:40'),
(28, 11, 5, 'Lấy Godhuman', '', 30000, '', 1, '2025-02-16 17:32:53'),
(29, 11, 6, 'Lấy True Triple Katana', '', 30000, '', 1, '2025-02-16 17:33:03'),
(30, 11, 7, 'Cày lv1 - Max', '', 30000, '', 1, '2025-02-16 17:33:13'),
(31, 11, 7, 'Cày lv1000 - Max', '', 30000, '', 1, '2025-02-16 17:33:21'),
(32, 11, 7, 'Cày lv1500 - Max', '', 30000, '', 1, '2025-02-16 17:33:29'),
(33, 11, 7, 'Cày lv2000 - Max', '', 30000, '', 1, '2025-02-16 17:33:36'),
(34, 11, 7, 'Cày 5000 Fragments', '', 30000, '', 1, '2025-02-16 17:33:45'),
(35, 11, 7, 'Cày 10000 Fragments', '', 30000, '', 1, '2025-02-16 17:33:54'),
(36, 11, 7, 'Cày 5M Beli', '', 30000, '', 1, '2025-02-16 17:34:01'),
(37, 11, 7, 'Cày 10M Beli', '', 30000, '', 1, '2025-02-16 17:34:08'),
(38, 9, 1, 'Dragon', '/upload/item/item58W0.png', 500000, '', 1, '2025-02-16 17:41:55'),
(39, 9, 2, 'Kitsune', '/upload/item/itemNOGS.png', 100000, '', 1, '2025-02-16 17:42:15'),
(40, 9, 3, 'Yeti', '/upload/item/item4JVE.png', 100000, '', 1, '2025-02-16 17:42:49'),
(41, 9, 4, 'Gas', '/upload/item/item65MI.png', 100000, '', 1, '2025-02-16 17:43:18'),
(42, 9, 5, 'Leopard', '/upload/item/itemPWLR.png', 100000, '', 1, '2025-02-16 17:43:37'),
(43, 9, 5, 'Dough', '/upload/item/itemBY9K.png', 100000, '', 1, '2025-02-16 17:44:16'),
(44, 9, 5, 'T-Rex', '/upload/item/itemV19G.png', 100000, '', 1, '2025-02-16 17:44:33'),
(45, 9, 5, 'Mammoth', '/upload/item/itemJNB4.png', 100000, '', 1, '2025-02-16 17:44:50'),
(46, 9, 5, 'Sound', '/upload/item/item7ULE.png', 100000, '', 1, '2025-02-16 17:45:04'),
(47, 9, 5, 'Spirit', '/upload/item/itemH10M.png', 100000, '', 1, '2025-02-16 17:45:18'),
(48, 17, 1, 'Xayda (có skill5)', '', 50000, 'PHA+Q+G6p24gY2h14bqpbiBi4buLIDUwIG5n4buNYzwvcD4NCg==', 1, '2025-02-24 11:49:12'),
(49, 17, 2, 'Trái Đất - Namec (có skill5)', '', 100000, 'PHA+Q+G6p24gY2h14bqpbiBi4buLIDEwMCBuZ+G7jWM8L3A+DQo=', 1, '2025-02-24 11:49:26'),
(50, 17, 3, 'Sơ Sinh', '', 200000, 'PHA+Q+G6p24gY2h14bqpbiBi4buLIDIwMCBuZ+G7jWM8L3A+DQo=', 1, '2025-02-24 11:49:43'),
(51, 18, 1, 'Quân huy × 40', '', 25000, '', 1, '2025-02-24 12:19:43'),
(52, 18, 2, 'Quân huy × 102', '', 65000, '', 1, '2025-02-24 12:19:57'),
(53, 18, 3, 'Quân huy × 204', '', 130000, '', 1, '2025-02-24 12:20:13'),
(54, 18, 4, 'Quân huy × 408', '', 260000, '', 1, '2025-02-24 12:20:39'),
(55, 18, 5, 'Quân huy × 1020', '', 650000, '', 1, '2025-02-24 12:20:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `package_units`
--

CREATE TABLE `package_units` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `stt` int(11) DEFAULT 0,
  `name` varchar(50) DEFAULT NULL,
  `value` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `package_units`
--

INSERT INTO `package_units` (`id`, `unit_id`, `stt`, `name`, `value`, `status`) VALUES
(1, 1, 1, '100 Robux', 100, 1),
(2, 1, 2, '200 Robux', 200, 1),
(3, 1, 3, '300 Robux', 300, 1),
(5, 1, 4, '500 Robux', 500, 1),
(6, 2, 1, 'Gói 113 Kim Cương', 113, 1),
(7, 2, 2, 'Gói 283 Kim Cương', 283, 1),
(8, 2, 3, 'Gói 566 Kim Cương', 566, 1),
(9, 2, 4, 'Gói 1132 Kim Cương', 1132, 1),
(10, 2, 5, 'Gói 2830 Kim Cương', 2830, 1),
(11, 3, 1, '45 Thỏi Vàng', 45, 1),
(12, 3, 1, '90 Thỏi Vàng', 90, 1),
(13, 3, 3, '180 Thỏi Vàng', 180, 1),
(14, 3, 4, '450 Thỏi Vàng', 450, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `stt` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `slug` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `link` text DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `view` int(11) NOT NULL DEFAULT 0,
  `noti` int(11) NOT NULL DEFAULT 0,
  `footer` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `stt`, `category_id`, `title`, `image`, `slug`, `link`, `content`, `status`, `view`, `noti`, `footer`, `created_at`) VALUES
(19, 2, 0, 3, 'Rito Affiliate là gì?', '/upload/blog/blog8UTE.png', 'rito-affiliate-la-gi-', NULL, 'PHA+ZGVtbzwvcD4NCg==', 1, 0, 0, 0, '2025-02-19 12:31:24'),
(20, 2, 0, 3, 'Chính sách bán hàng và đổi trả', '/upload/blog/blogEHAX.png', 'chinh-sach-ban-hang-va-doi-tra', NULL, 'PGgyPjxzdHJvbmc+MS4gQ2gmaWFjdXRlO25oIFMmYWFjdXRlO2NoIEImYWFjdXRlO24gSCZhZ3JhdmU7bmc8L3N0cm9uZz48L2gyPg0KDQo8cD5MdSZvY2lyYztuIHPhurVuIHMmYWdyYXZlO25nIGjhu5cgdHLhu6MsIHTGsCB24bqlbiBjaG8ga2gmYWFjdXRlO2NoIGgmYWdyYXZlO25nIGzhu7FhIGNo4buNbiBz4bqjbiBwaOG6qW0gaGnhu4d1IHF14bqjIG5o4bqldC4gxJDhu4MgxJHhuqNtIGLhuqNvIGMmb2FjdXRlOyB0aOG7gyDEkSZhYWN1dGU7cCDhu6luZyDEkeG6p3kgxJHhu6cgY2hvIG3hu5dpIG5odSBj4bqndSBj4bunYSB04burbmcga2gmYWFjdXRlO2NoIGgmYWdyYXZlO25nLjwvcD4NCg0KPHA+VOG6pXQgY+G6oyBz4bqjbiBwaOG6qW0gc2hvcGJhY2dhdS5jb20gY3VuZyBj4bqlcCDEkeG7gXUgxJHhuqNtIGLhuqNvIMSR4buZIHPhuqFjaCwgdXkgdCZpYWN1dGU7bi4gS2gmb2NpcmM7bmcgTOG7qkEgxJDhuqJPLCBjJmFhY3V0ZTtjIHPhuqNuIHBo4bqpbSwgZOG7i2NoIHbhu6UgxJEmdWFjdXRlO25nIGNodeG6qW4gdGhlbyBtJm9jaXJjOyB04bqjLiBT4bqjbiBwaOG6qW0sIGThu4tjaCB24bulIGxpJmVjaXJjO24gcXVhbiDEkeG6v24gYyZvY2lyYztuZyBuZ2jhu4cgYyZhYWN1dGU7YyBsaSZlY2lyYztuIGvhur90IHRyJmVjaXJjO24gU2hvcCBjYW0ga+G6v3QgdOG6pXQgY+G6oyDEkeG7gXUgc+G6oWNoLCBhbiB0byZhZ3JhdmU7biBraCZvY2lyYztuZyBs4bqtdSwga2gmb2NpcmM7bmcgbGluayDhuqNvLC4uLjwvcD4NCg==', 1, 0, 0, 1, '2025-02-19 12:32:14'),
(21, 2, 0, 3, 'Điều khoản sử dụng', '/upload/blog/blogWD1E.png', 'dieu-khoan-su-dung', NULL, 'PGgyPjxzdHJvbmc+xJBp4buBdSBraG/huqNuIHPhu60gZOG7pW5nIHdlYnNpdGU8L3N0cm9uZz48L2gyPg0K', 1, 0, 0, 1, '2025-02-19 12:33:01'),
(22, 2, 0, 3, 'Chính sách bảo mật', '/upload/blog/blog3ZTL.png', 'chinh-sach-bao-mat', NULL, 'PGgzPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj48c3Ryb25nPjEuIEdp4bubaSB0aGnhu4d1PC9zdHJvbmc+PC9zcGFuPjwvaDM+DQoNCjxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj5DaCZ1YWN1dGU7bmcgdCZvY2lyYztpIGNhbSBr4bq/dCBi4bqjbyB24buHIHF1eeG7gW4gcmkmZWNpcmM7bmcgdMawIHYmYWdyYXZlOyB0aCZvY2lyYztuZyB0aW4gYyZhYWN1dGU7IG5oJmFjaXJjO24gY+G7p2Ega2gmYWFjdXRlO2NoIGgmYWdyYXZlO25nIGtoaSBz4butIGThu6VuZyBk4buLY2ggduG7pSB0ciZlY2lyYztuIHdlYnNpdGUuIENoJmlhY3V0ZTtuaCBzJmFhY3V0ZTtjaCBi4bqjbyBt4bqtdCBuJmFncmF2ZTt5IGdp4bqjaSB0aCZpYWN1dGU7Y2ggYyZhYWN1dGU7Y2ggY2gmdWFjdXRlO25nIHQmb2NpcmM7aSB0aHUgdGjhuq1wLCBz4butIGThu6VuZyB2JmFncmF2ZTsgYuG6o28gduG7hyB0aCZvY2lyYztuZyB0aW4gY+G7p2EgYuG6oW4uPC9zcGFuPjwvcD4NCg0KPGgzPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj48c3Ryb25nPjIuIFRoJm9jaXJjO25nIHRpbiB0aHUgdGjhuq1wPC9zdHJvbmc+PC9zcGFuPjwvaDM+DQoNCjxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj5DaCZ1YWN1dGU7bmcgdCZvY2lyYztpIGMmb2FjdXRlOyB0aOG7gyB0aHUgdGjhuq1wIGMmYWFjdXRlO2MgbG/huqFpIHRoJm9jaXJjO25nIHRpbiBzYXU6PC9zcGFuPjwvcD4NCg0KPHVsPg0KCTxsaT4NCgk8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+PHN0cm9uZz5UaCZvY2lyYztuZyB0aW4gYyZhYWN1dGU7IG5oJmFjaXJjO248L3N0cm9uZz46IEjhu40gdCZlY2lyYztuLCDEkeG7i2EgY2jhu4kgZW1haWwsIHPhu5EgxJFp4buHbiB0aG/huqFpLCDEkeG7i2EgY2jhu4kgdGhhbmggdG8mYWFjdXRlO24uPC9zcGFuPjwvcD4NCgk8L2xpPg0KCTxsaT4NCgk8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+PHN0cm9uZz5UaCZvY2lyYztuZyB0aW4gZ2lhbyBk4buLY2g8L3N0cm9uZz46IEzhu4tjaCBz4butIG11YSBoJmFncmF2ZTtuZywgcGjGsMahbmcgdGjhu6ljIHRoYW5oIHRvJmFhY3V0ZTtuLjwvc3Bhbj48L3A+DQoJPC9saT4NCgk8bGk+DQoJPHA+PHNwYW4gc3R5bGU9ImNvbG9yOiMwMDAwMDAiPjxzdHJvbmc+VGgmb2NpcmM7bmcgdGluIGvhu7kgdGh14bqtdDwvc3Ryb25nPjogxJDhu4thIGNo4buJIElQLCBsb+G6oWkgdHImaWdyYXZlO25oIGR1eeG7h3QsIHRoaeG6v3QgYuG7iyBz4butIGThu6VuZy48L3NwYW4+PC9wPg0KCTwvbGk+DQo8L3VsPg0KDQo8aDM+PHNwYW4gc3R5bGU9ImNvbG9yOiMwMDAwMDAiPjxzdHJvbmc+My4gTeG7pWMgxJEmaWFjdXRlO2NoIHPhu60gZOG7pW5nIHRoJm9jaXJjO25nIHRpbjwvc3Ryb25nPjwvc3Bhbj48L2gzPg0KDQo8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+Q2gmdWFjdXRlO25nIHQmb2NpcmM7aSBz4butIGThu6VuZyB0aCZvY2lyYztuZyB0aW4gdGh1IHRo4bqtcCDEkcaw4bujYyDEkeG7gzo8L3NwYW4+PC9wPg0KDQo8dWw+DQoJPGxpPg0KCTxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj5Y4butIGwmeWFjdXRlOyDEkcahbiBoJmFncmF2ZTtuZyB2JmFncmF2ZTsgY3VuZyBj4bqlcCBk4buLY2ggduG7pS48L3NwYW4+PC9wPg0KCTwvbGk+DQoJPGxpPg0KCTxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj5I4buXIHRy4bujIGtoJmFhY3V0ZTtjaCBoJmFncmF2ZTtuZywgZ2nhuqNpIHF1eeG6v3Qga2hp4bq/dSBu4bqhaS48L3NwYW4+PC9wPg0KCTwvbGk+DQoJPGxpPg0KCTxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj5D4bqjaSB0aGnhu4duIGNo4bqldCBsxrDhu6NuZyBk4buLY2ggduG7pSB2JmFncmF2ZTsgdHLhuqNpIG5naGnhu4dtIG5nxrDhu51pIGQmdWdyYXZlO25nLjwvc3Bhbj48L3A+DQoJPC9saT4NCgk8bGk+DQoJPHA+PHNwYW4gc3R5bGU9ImNvbG9yOiMwMDAwMDAiPsSQ4bqjbSBi4bqjbyBhbiB0byZhZ3JhdmU7biB2JmFncmF2ZTsgY2jhu5FuZyBnaWFuIGzhuq1uLjwvc3Bhbj48L3A+DQoJPC9saT4NCgk8bGk+DQoJPHA+PHNwYW4gc3R5bGU9ImNvbG9yOiMwMDAwMDAiPkfhu61pIHRoJm9jaXJjO25nIGImYWFjdXRlO28sIMawdSDEkSZhdGlsZGU7aSBu4bq/dSBraCZhYWN1dGU7Y2ggaCZhZ3JhdmU7bmcgxJHhu5NuZyAmeWFjdXRlOyBuaOG6rW4uPC9zcGFuPjwvcD4NCgk8L2xpPg0KPC91bD4NCg0KPGgzPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj48c3Ryb25nPjQuIELhuqNvIG3huq10IHRoJm9jaXJjO25nIHRpbjwvc3Ryb25nPjwvc3Bhbj48L2gzPg0KDQo8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+Q2gmdWFjdXRlO25nIHQmb2NpcmM7aSAmYWFjdXRlO3AgZOG7pW5nIGMmYWFjdXRlO2MgYmnhu4duIHBoJmFhY3V0ZTtwIGLhuqNvIG3huq10IG5naGkmZWNpcmM7bSBuZ+G6t3QgxJHhu4MgYuG6o28gduG7hyB0aCZvY2lyYztuZyB0aW4gYyZhYWN1dGU7IG5oJmFjaXJjO24gY+G7p2Ega2gmYWFjdXRlO2NoIGgmYWdyYXZlO25nLCBiYW8gZ+G7k206PC9zcGFuPjwvcD4NCg0KPHVsPg0KCTxsaT4NCgk8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+TSZhdGlsZGU7IGgmb2FjdXRlO2EgZOG7ryBsaeG7h3UgdHJvbmcgcXUmYWFjdXRlOyB0ciZpZ3JhdmU7bmggdHJ1eeG7gW4gdOG6o2kuPC9zcGFuPjwvcD4NCgk8L2xpPg0KCTxsaT4NCgk8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+TMawdSB0cuG7ryB0aCZvY2lyYztuZyB0aW4gdHImZWNpcmM7biBo4buHIHRo4buRbmcgYuG6o28gbeG6rXQgY2FvLjwvc3Bhbj48L3A+DQoJPC9saT4NCgk8bGk+DQoJPHA+PHNwYW4gc3R5bGU9ImNvbG9yOiMwMDAwMDAiPkjhuqFuIGNo4bq/IHRydXkgY+G6rXAgdiZhZ3JhdmU7byB0aCZvY2lyYztuZyB0aW4gYyZhYWN1dGU7IG5oJmFjaXJjO24uPC9zcGFuPjwvcD4NCgk8L2xpPg0KPC91bD4NCg0KPGgzPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj48c3Ryb25nPjUuIENoaWEgc+G6uyB0aCZvY2lyYztuZyB0aW48L3N0cm9uZz48L3NwYW4+PC9oMz4NCg0KPHA+PHNwYW4gc3R5bGU9ImNvbG9yOiMwMDAwMDAiPkNoJnVhY3V0ZTtuZyB0Jm9jaXJjO2kgY2FtIGvhur90IGtoJm9jaXJjO25nIGNoaWEgc+G6uyB0aCZvY2lyYztuZyB0aW4gYyZhYWN1dGU7IG5oJmFjaXJjO24gY+G7p2Ega2gmYWFjdXRlO2NoIGgmYWdyYXZlO25nIHbhu5tpIGImZWNpcmM7biB0aOG7qSBiYSwgdHLhu6sgYyZhYWN1dGU7YyB0csaw4budbmcgaOG7o3Agc2F1Ojwvc3Bhbj48L3A+DQoNCjx1bD4NCgk8bGk+DQoJPHA+PHNwYW4gc3R5bGU9ImNvbG9yOiMwMDAwMDAiPktoaSBjJm9hY3V0ZTsgc+G7sSDEkeG7k25nICZ5YWN1dGU7IGPhu6dhIGtoJmFhY3V0ZTtjaCBoJmFncmF2ZTtuZy48L3NwYW4+PC9wPg0KCTwvbGk+DQoJPGxpPg0KCTxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj5UaGVvIHkmZWNpcmM7dSBj4bqndSBj4bunYSBjxqEgcXVhbiBwaCZhYWN1dGU7cCBsdeG6rXQuPC9zcGFuPjwvcD4NCgk8L2xpPg0KCTxsaT4NCgk8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+xJDhu4MgYuG6o28gduG7hyBxdXnhu4FuIGzhu6NpIGjhu6NwIHBoJmFhY3V0ZTtwIGPhu6dhIGNoJnVhY3V0ZTtuZyB0Jm9jaXJjO2kuPC9zcGFuPjwvcD4NCgk8L2xpPg0KPC91bD4NCg0KPGgzPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj48c3Ryb25nPjYuIFF1eeG7gW4gbOG7o2kgY+G7p2Ega2gmYWFjdXRlO2NoIGgmYWdyYXZlO25nPC9zdHJvbmc+PC9zcGFuPjwvaDM+DQoNCjxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj5LaCZhYWN1dGU7Y2ggaCZhZ3JhdmU7bmcgYyZvYWN1dGU7IHF1eeG7gW46PC9zcGFuPjwvcD4NCg0KPHVsPg0KCTxsaT4NCgk8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+VHJ1eSBj4bqtcCwgY2jhu4luaCBz4butYSBob+G6t2MgeSZlY2lyYzt1IGPhuqd1IHgmb2FjdXRlO2EgdGgmb2NpcmM7bmcgdGluIGMmYWFjdXRlOyBuaCZhY2lyYztuIGPhu6dhIG0maWdyYXZlO25oLjwvc3Bhbj48L3A+DQoJPC9saT4NCgk8bGk+DQoJPHA+PHNwYW4gc3R5bGU9ImNvbG9yOiMwMDAwMDAiPlkmZWNpcmM7dSBj4bqndSBuZ+G7q25nIG5o4bqtbiB0aCZvY2lyYztuZyBiJmFhY3V0ZTtvIHRp4bq/cCB0aOG7iy48L3NwYW4+PC9wPg0KCTwvbGk+DQoJPGxpPg0KCTxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj5LaGnhur91IG7huqFpIG7hur91IHBoJmFhY3V0ZTt0IGhp4buHbiB2aSBwaOG6oW0gYuG6o28gbeG6rXQuPC9zcGFuPjwvcD4NCgk8L2xpPg0KPC91bD4NCg0KPGgzPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj48c3Ryb25nPjcuIFRoYXkgxJHhu5VpIGNoJmlhY3V0ZTtuaCBzJmFhY3V0ZTtjaDwvc3Ryb25nPjwvc3Bhbj48L2gzPg0KDQo8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+Q2gmdWFjdXRlO25nIHQmb2NpcmM7aSBjJm9hY3V0ZTsgdGjhu4MgY+G6rXAgbmjhuq10IGNoJmlhY3V0ZTtuaCBzJmFhY3V0ZTtjaCBi4bqjbyBt4bqtdCBraGkgY+G6p24gdGhp4bq/dC4gTeG7jWkgdGhheSDEkeG7lWkgc+G6vSDEkcaw4bujYyB0aCZvY2lyYztuZyBiJmFhY3V0ZTtvIHRyJmVjaXJjO24gd2Vic2l0ZS48L3NwYW4+PC9wPg0KDQo8aDM+PHNwYW4gc3R5bGU9ImNvbG9yOiMwMDAwMDAiPjxzdHJvbmc+OC4gTGkmZWNpcmM7biBo4buHPC9zdHJvbmc+PC9zcGFuPjwvaDM+DQoNCjxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj5O4bq/dSBjJm9hY3V0ZTsgYuG6pXQga+G7syBjJmFjaXJjO3UgaOG7j2kgbiZhZ3JhdmU7byB24buBIGNoJmlhY3V0ZTtuaCBzJmFhY3V0ZTtjaCBi4bqjbyBt4bqtdCwgdnVpIGwmb2dyYXZlO25nIGxpJmVjaXJjO24gaOG7hzo8L3NwYW4+PC9wPg0KDQo8dWw+DQoJPGxpPg0KCTxwPjxzcGFuIHN0eWxlPSJjb2xvcjojMDAwMDAwIj48c3Ryb25nPkVtYWlsPC9zdHJvbmc+OiBzdXBwb3J0QHdlYnNpdGUuY29tPC9zcGFuPjwvcD4NCgk8L2xpPg0KCTxsaT4NCgk8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+PHN0cm9uZz5Ib3RsaW5lPC9zdHJvbmc+OiAwMTIzLTQ1Ni03ODk8L3NwYW4+PC9wPg0KCTwvbGk+DQo8L3VsPg0KDQo8cD48c3BhbiBzdHlsZT0iY29sb3I6IzAwMDAwMCI+Q2gmaWFjdXRlO25oIHMmYWFjdXRlO2NoIG4mYWdyYXZlO3kgYyZvYWN1dGU7IGhp4buHdSBs4buxYyB04burIG5nJmFncmF2ZTt5IFtuZyZhZ3JhdmU7eSB0aCZhYWN1dGU7bmcgbsSDbV0uPC9zcGFuPjwvcD4NCg==', 1, 0, 0, 1, '2025-02-19 12:33:37'),
(23, 2, 0, 3, 'Chương trình giới thiệu bạn bè', '/upload/blog/blog270X.png', 'chuong-trinh-gioi-thieu-ban-be', NULL, 'PHA+R2nhu5tpIHRoaeG7h3UgY2hvIGLhuqFuIGImZWdyYXZlOyDEkeG7gyBnaeG6o20gZ2kmYWFjdXRlOyBz4bqjbiBwaOG6qW0sIG5o4bqtbiB0aeG7gW4sIHbhuq10IHBo4bqpbSZuYnNwO3YmYWdyYXZlOyBuaOG6rW4gaG9hIGjhu5NuZyB2xKluaCB2aeG7hW4uPC9wPg0K', 1, 0, 0, 0, '2025-02-19 12:34:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_category`
--

CREATE TABLE `post_category` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `slug` text NOT NULL,
  `content` longtext NOT NULL,
  `icon` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `post_category`
--

INSERT INTO `post_category` (`id`, `name`, `slug`, `content`, `icon`, `status`, `created_at`) VALUES
(3, 'Tin tức', 'tin-tuc', 'PHA+ZGVtbzwvcD4NCg==', '/upload/blog/iconZHYK.png', 1, '2024-05-27 11:25:34'),
(4, 'Hướng dẫn', 'huong-dan', 'PHA+ZGVtbzwvcD4NCg==', '/upload/blog/iconKD3H.png', 1, '2024-05-27 11:26:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `seller` varchar(50) NOT NULL DEFAULT '0',
  `seller_id` int(11) NOT NULL DEFAULT 0,
  `history_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `acc_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `service_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `rating` int(11) NOT NULL DEFAULT 0,
  `review` text DEFAULT NULL,
  `type` varchar(50) DEFAULT 'account',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `spin_quests`
--

CREATE TABLE `spin_quests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `stt` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL DEFAULT 'custom',
  `prizes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `image` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `play` varchar(50) DEFAULT NULL,
  `played` int(11) DEFAULT 0,
  `descr` longtext DEFAULT NULL,
  `price` int(11) NOT NULL,
  `sale` int(11) NOT NULL DEFAULT 0,
  `store_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `priority` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `spin_quests`
--

INSERT INTO `spin_quests` (`id`, `name`, `stt`, `type`, `prizes`, `image`, `cover`, `play`, `played`, `descr`, `price`, `sale`, `store_id`, `status`, `priority`, `created_at`, `updated_at`) VALUES
(8, 'LIÊN QUÂN', 2, 'custom', '[{\"percent\":\"20\",\"value\":\"20\",\"text\":\"Ch\\u00fac m\\u1eebng b\\u1ea1n \\u0111\\u00e3 tr\\u00fang 20 qu\\u00e2n huy\",\"min\":null,\"max\":null,\"random\":null},{\"percent\":\"80\",\"value\":\"99\",\"text\":\"Ch\\u00fac m\\u1eebng b\\u1ea1n \\u0111\\u00e3 tr\\u00fang 80 qu\\u00e2n huy\",\"min\":null,\"max\":null,\"random\":null},{\"percent\":\"1\",\"value\":\"368\",\"text\":\"Ch\\u00fac m\\u1eebng b\\u1ea1n \\u0111\\u00e3 tr\\u00fang 368 qu\\u00e2n huy\",\"min\":null,\"max\":null,\"random\":null},{\"percent\":\"1\",\"value\":\"688\",\"text\":\"Ch\\u00fac m\\u1eebng b\\u1ea1n \\u0111\\u00e3 tr\\u00fang 688 qu\\u00e2n huy\",\"min\":null,\"max\":null,\"random\":null},{\"percent\":\"1\",\"value\":\"1000\",\"text\":\"Ch\\u00fac m\\u1eebng b\\u1ea1n \\u0111\\u00e3 tr\\u00fang 1000 qu\\u00e2n huy\",\"min\":null,\"max\":null,\"random\":null},{\"percent\":\"1\",\"value\":\"1500\",\"text\":\"Ch\\u00fac m\\u1eebng b\\u1ea1n \\u0111\\u00e3 tr\\u00fang 1500 qu\\u00e2n huy\",\"min\":null,\"max\":null,\"random\":null},{\"percent\":\"1\",\"value\":\"1800\",\"text\":\"Ch\\u00fac m\\u1eebng b\\u1ea1n \\u0111\\u00e3 tr\\u00fang 1800 qu\\u00e2n huy\",\"min\":null,\"max\":null,\"random\":null},{\"percent\":\"1\",\"value\":\"2999\",\"text\":\"Ch\\u00fac m\\u1eebng b\\u1ea1n \\u0111\\u00e3 tr\\u00fang 2999 qu\\u00e2n huy\",\"min\":null,\"max\":null,\"random\":null}]', '/upload/spin/spinCK65.png', '/upload/spin/spin4PNT.png', '/upload/spin/spinPQY9.png', 2, '<p style=\"text-align:center\"><strong>KHI B&aacute;&ordm;&nbsp;N C&Oacute; &Auml;&aacute;&raquo;&brvbar; 10K B&aacute;&ordm;&nbsp;N S&aacute;&ordm;&frac14; &Auml;&AElig;&macr;&aacute;&raquo;&cent;C 1 L&AElig;&macr;&aacute;&raquo;&cent;T QUAY&nbsp;</strong></p>\r\n\r\n<p style=\"text-align:center\"><strong>B&aacute;&ordm;&nbsp;N S&aacute;&ordm;&frac14; C&Oacute; C&AElig;&nbsp; H&Ocirc;̀&pound;I NH&Acirc;̀&pound;N &Auml;&AElig;&macr;&aacute;&raquo;&cent;C T&aacute;&raquo;I 2999 KIM C&AElig;&macr;&AElig;&nbsp;NG V&Agrave; NHI&aacute;&raquo;&euro;U PH&aacute;&ordm;&brvbar;N QU&Agrave; H&aacute;&ordm;&curren;P D&aacute;&ordm;&ordf;N KH&Aacute;C</strong></p>\r\n\r\n<p style=\"text-align:center\"><strong>QUAY NGAY N&Agrave;O!!!</strong></p>\r\n', 10000, 20, NULL, 1, 0, '2024-06-29 11:02:06', '2025-02-16 09:47:10'),
(9, 'VÒNG QUAY AK RỒNG XANH', 1, 'custom', '[]', '/upload/spin/spin05VJ.png', '/upload/spin/spinUL9V.png', '/upload/spin/spin9EI1.png', 0, '', 20000, 10, NULL, 1, 0, '2024-06-29 14:14:32', '2024-06-29 14:14:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `spin_quest_logs`
--

CREATE TABLE `spin_quest_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trans_id` varchar(50) DEFAULT NULL,
  `prize` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `spin_id` int(11) NOT NULL DEFAULT 0,
  `name_spin` varchar(255) DEFAULT NULL,
  `content` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `is_fake_data` tinyint(1) NOT NULL DEFAULT 0,
  `spin_quest_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `spin_quest_logs`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subboostings`
--

CREATE TABLE `subboostings` (
  `id` int(11) NOT NULL,
  `stt` int(11) DEFAULT 1,
  `type` varchar(50) DEFAULT NULL,
  `category` int(11) DEFAULT 0,
  `type_category` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `detail` longtext DEFAULT NULL,
  `coefficient` float DEFAULT 0,
  `fake` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `subboostings`
--

INSERT INTO `subboostings` (`id`, `stt`, `type`, `category`, `type_category`, `link`, `detail`, `coefficient`, `fake`, `status`) VALUES
(6, 1, 'caythue', 2, 'cay-thue-blox-fruits', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"C\\u00c0Y THU\\u00ca BLOX FRUITS\",\"thumb\":\"upload\\/product\\/d0758a674afe3e480b57ebce27a4012a.gif\",\"thele\":\"<p>demo<\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i Kho\\u1ea3n Game\",\"type\":\"text\",\"name\":\"taikhoangame\",\"option\":null},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u Game\",\"type\":\"text\",\"name\":\"matkhaugame\",\"option\":null},{\"id\":2,\"label\":\"Ghi ch\\u00fa\",\"type\":\"text\",\"name\":\"ghichu\",\"option\":null}]}', 0, 100, 1),
(8, 1, 'item', 3, 'dich-vu-anime-reborn', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"D\\u1ecbch v\\u1ee5 Anime Reborn\",\"thumb\":\"upload\\/product\\/482d7316965a12f2818759b2543b5d10.png\",\"thele\":\"<p>demo<\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00ean \\u0111\\u0103ng nh\\u1eadp Roblox\",\"type\":\"text\",\"name\":\"tendangnhaproblox\",\"option\":null},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u Roblox\",\"type\":\"text\",\"name\":\"matkhauroblox\",\"option\":null},{\"id\":2,\"label\":\"T\\u1eaft 2step + \\u0110i\\u1ec1n 3 game ch\\u01a1i g\\u1ea7n nh\\u1ea5t + Backup code( n\\u1ebfu c\\u00f3)\",\"type\":\"text\",\"name\":\"tat2stepdien3gamechoigannhatbackupcodeneuco\",\"option\":null}]}', 0, 100, 1),
(9, 2, 'item', 4, 'ban-trai-ac-quy-ruong', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"B\\u00e1n Tr\\u00e1i \\u00c1c Qu\\u1ef7 R\\u01b0\\u01a1ng\",\"thumb\":\"upload\\/product\\/c46dcced52c7a0272862222633e2be59.jpg\",\"thele\":\"<p><span style=\\\"color:#e74c3c\\\">demo n\\u00e8<\\/span><\\/p>\",\"coefficient\":\"\",\"unit\":\"\",\"min\":\"\",\"max\":\"\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i Kho\\u1ea3n Game\",\"type\":\"text\",\"name\":\"taikhoangame\",\"option\":null,\"content\":null},{\"id\":1,\"label\":\"M\\u1eadt Kh\\u1ea9u Game\",\"type\":\"text\",\"name\":\"matkhaugame\",\"option\":null,\"content\":null},{\"id\":2,\"label\":\"H\\u00e3y t\\u1eaft x\\u00e1c minh 2 b\\u01b0\\u1edbc v\\u00e0 \\u0111i\\u1ec1n 3 game \\u0111ang ch\\u01a1i n\\u1ebfu c\\u00f3\",\"type\":\"text\",\"name\":\"haytatxacminh2buocvadien3gamedangchoineuco\",\"option\":null,\"content\":null}]}', 0, 100, 1),
(10, 2, 'caythue', 4, 'ban-robux-chinh-hang', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"B\\u00e1n Robux Ch\\u00ednh H\\u00e3ng\",\"thumb\":\"upload\\/product\\/826253441dec09878a2a276599891793.jpg\",\"thele\":\"<p>da<\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n Roblox\",\"type\":\"text\",\"name\":\"taikhoanroblox\",\"option\":null},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u Roblox\",\"type\":\"text\",\"name\":\"matkhauroblox\",\"option\":null},{\"id\":2,\"label\":\"3 Game \\u0111ang ch\\u01a1i n\\u1ebfu c\\u00f3\",\"type\":\"text\",\"name\":\"3gamedangchoineuco\",\"option\":null}]}', 0, 200, 1),
(11, 3, 'caythue', 4, 'cay-thue-blox-fruit', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"C\\u00e0y Thu\\u00ea Blox Fruit\",\"thumb\":\"upload\\/product\\/21452c8e0f6a501924cf2226135b998e.jpg\",\"thele\":\"<p><span style=\\\\\\\"color:#e74c3c\\\\\\\"><strong>L\\u01afU \\u00dd TR\\u01af\\u1edaC KHI C\\u00c0Y THU\\u00ca \\u0110\\u1ec2 TR\\u00c1NH GI\\u00c1N \\u0110O\\u1ea0N QU\\u00c1 TR\\u00ccNH C\\u00c0Y<\\/strong><\\/span><\\/p>\\r\\n\\r\\n<p><strong>- T\\u1eaft x\\u00e1c minh 2 b\\u01b0\\u1edbc b\\u1eb1ng c\\u00e1ch thay mail ng\\u1eabu nhi\\u00ean ( kh\\u00f4ng verify )<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- Kh\\u00f4ng v\\u00e0o acc qu\\u00e1 3 l\\u1ea7n khi admin \\u0111ang c\\u00e0y\\u00a0<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- Th\\u1eddi gian ho\\u00e0n t\\u1ea5t d\\u1ecbch v\\u1ee5 t\\u1eeb 1-5 ng\\u00e0y<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- Trong qu\\u00e1 tr\\u00ecnh d\\u1ecbch v\\u1ee5 th\\u1ef1c hi\\u1ec7n s\\u1ebd c\\u00f3 kho\\u1ea3ng th\\u1eddi gian off nick \\u0111\\u1ec3 \\u1ed5n \\u0111\\u1ecbnh m\\u1ea1ng<\\/strong><\\/p>\\r\\n\\r\\n<p><span style=\\\\\\\"color:#e74c3c\\\\\\\"><strong>- Vi ph\\u1ea1m nh\\u1eefng \\u0111i\\u1ec1u tr\\u00ean th\\u00ec shop s\\u1ebd d\\u1eebng d\\u1ecbch v\\u1ee5 v\\u00e0 kh\\u00f4ng ho\\u00e0n ti\\u1ec1n<\\/strong><\\/span><\\/p>\\r\\n\\r\\n<p><strong>- T\\u1ea5t c\\u1ea3 th\\u00f4ng tin d\\u1ecbch v\\u1ee5 vui l\\u00f2ng check chi ti\\u1ebft v\\u00e0 nh\\u1eafn tin qua khi\\u1ebfu n\\u1ea1i<\\/strong><\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i Kho\\u1ea3n\",\"type\":\"text\",\"name\":\"taikhoan\",\"option\":null},{\"id\":1,\"label\":\"M\\u1eadt Kh\\u1ea9u\",\"type\":\"text\",\"name\":\"matkhau\",\"option\":null},{\"id\":2,\"label\":\"Vui l\\u00f2ng kh\\u00f4ng v\\u00e0o acc trong th\\u1eddi gian \\u0111\\u01a1n \\u0111ang th\\u1ef1c hi\\u1ec7n\",\"type\":\"text\",\"name\":\"vuilongkhongvaoacctrongthoigiandondangthuchien\",\"option\":null}]}', 0, 100, 1),
(12, 4, 'item', 4, 'mua-gamepass-blox-fruits', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"Mua Gamepass Blox Fruits\",\"thumb\":\"upload\\/product\\/3239baab80f143b99d3730d72637df02.jpg\",\"thele\":\"<p><strong>Ch\\u00fa \\u00fd:<\\/strong><\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>T\\u1eaft m\\u00e3 PIN , b\\u1ea3o m\\u1eadt 2 b\\u01b0\\u1edbc\\u00a0v\\u00e0 thay 1 gmail kh\\u00f4ng x\\u00e1c minh \\u0111\\u1ec3 tr\\u00e1nh b\\u1ea3o m\\u1eadt 2 b\\u01b0\\u1edbc<\\/li>\\r\\n\\t<li>Spam qu\\u00e1 3 l\\u1ea7n khi b\\u1ecb 2-Step s\\u1ebd KH\\u00d4NG NH\\u1eacN \\u0110\\u01af\\u1ee2C V\\u1eacT PH\\u1ea8M\\u00a0v\\u00e0 KH\\u00d4NG HO\\u00c0N TI\\u1ec0N<\\/li>\\r\\n\\t<li>KH\\u00d4NG thay \\u0111\\u1ed5i th\\u00f4ng tin ho\\u1eb7c v\\u00e0o acc khi \\u0111\\u01a1n \\u0111ang th\\u1ef1c hi\\u1ec7n<\\/li>\\r\\n\\t<li>VI PH\\u1ea0M NH\\u1eeeNG QUY \\u0110\\u1ecaNH TR\\u00caN S\\u1ebc KH\\u00d4NG NH\\u1eacN \\u0110\\u01af\\u1ee2C V\\u1eacT PH\\u1ea8M v\\u00e0 KH\\u00d4NG HO\\u00c0N TI\\u1ec0N<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>B\\u1ea1n s\\u1ebd nh\\u1eadn \\u0111\\u01b0\\u1ee3c v\\u1eadt ph\\u1ea9m sau 5-10p k\\u1ec3 t\\u1eeb l\\u00fac \\u0111\\u01a1n \\u0111\\u01b0\\u1ee3c nh\\u1eadn<\\/p>\\r\\n\\r\\n<p>Sau khi \\u0111\\u01a1n ho\\u00e0n t\\u1ea5t b\\u1ea1n vui l\\u00f2ng v\\u00e0o game \\u0111\\u1ec3 ki\\u1ec3m tra<\\/p>\\r\\n\\r\\n<p>M\\u1ecdi v\\u1ea5n \\u0111\\u1ec1 c\\u1ea7n h\\u1ed7 tr\\u1ee3 vui l\\u00f2ng li\\u00ean h\\u1ec7 CSKH \\u0111\\u1ec3 \\u0111\\u01b0\\u1ee3c h\\u1ed7 tr\\u1ee3<\\/p>\\r\\n\\r\\n<h2><strong>Gamepass l\\u00e0 g\\u00ec v\\u00e0 c\\u00f3 t\\u00e1c d\\u1ee5ng g\\u00ec?<\\/strong><\\/h2>\\r\\n\\r\\n<p>Gamepass l\\u00e0 nh\\u1eefng b\\u1ed5 tr\\u1ee3 trong c\\u1eeda h\\u00e0ng m\\u00e0 b\\u1ea1n c\\u00f3 th\\u1ec3 mua b\\u1eb1ng robux. Ch\\u00fang r\\u1ea5t h\\u1eefu \\u00edch nh\\u01b0ng b\\u1ea1n ph\\u1ea3i tr\\u1ea3 robux \\u0111\\u1ec3 s\\u1edf h\\u1eefu ch\\u00fang.\\u00a0Trong game blox fruit, c\\u00f3 nhi\\u1ec1u lo\\u1ea1i gamepass kh\\u00e1c nhau v\\u1edbi c\\u00e1c hi\\u1ec7u \\u1ee9ng v\\u00e0 gi\\u00e1 c\\u1ea3 kh\\u00e1c nhau.\\u00a0M\\u1ed9t s\\u1ed1 gamepass li\\u00ean quan \\u0111\\u1ebfn c\\u00e1c lo\\u1ea1i tr\\u00e1i c\\u00e2y blox m\\u00e0 b\\u1ea1n c\\u00f3 th\\u1ec3 s\\u1eed d\\u1ee5ng \\u0111\\u1ec3 chi\\u1ebfn \\u0111\\u1ea5u v\\u00e0 n\\u00e2ng c\\u1ea5p.\\u00a0 B\\u1ea1n c\\u00f3 th\\u1ec3 tham kh\\u1ea3o th\\u00f4ng tin chi ti\\u1ebft v\\u1ec1 t\\u1eebng lo\\u1ea1i gamepass c\\u0169ng nh\\u01b0 c\\u00f4ng d\\u1ee5ng c\\u1ee7a ch\\u00fang \\u1edf b\\u00ean d\\u01b0\\u1edbi.<\\/p>\\r\\n\\r\\n<h2><strong>Danh s\\u00e1ch c\\u00e1c lo\\u1ea1i gamepass trong Blox Fruit<\\/strong><\\/h2>\\r\\n\\r\\n<p><strong>Sau \\u0111\\u00e2y l\\u00e0 danh s\\u00e1ch c\\u00e1c lo\\u1ea1i gamepass trong Blox Fuits c\\u0169ng nh\\u01b0 c\\u00f4ng d\\u1ee5ng v\\u00e0 gi\\u00e1 th\\u00e0nh c\\u1ee7a ch\\u00fang khi mua b\\u1eb1ng robux<\\/strong><\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li><strong>X2 Money:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 450 robux s\\u1ebd nh\\u00e2n \\u0111\\u00f4i s\\u1ed1 ti\\u1ec1n b\\u1ea1n ki\\u1ebfm \\u0111\\u01b0\\u1ee3c trong game.\\u00a0Tuy nhi\\u00ean, \\u0111i\\u1ec1u n\\u00e0y ch\\u1ec9 \\u00e1p d\\u1ee5ng cho NPC v\\u00e0 nhi\\u1ec7m v\\u1ee5, kh\\u00f4ng \\u00e1p d\\u1ee5ng cho c\\u00e1c r\\u01b0\\u01a1ng tr\\u00ean b\\u1ea3n \\u0111\\u1ed3.<\\/li>\\r\\n\\t<li><strong>X2 Mastery<\\/strong>: Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd nh\\u00e2n \\u0111\\u00f4i s\\u1ed1 \\u0111i\\u1ec3m k\\u1ef9 n\\u0103ng b\\u1ea1n ki\\u1ebfm \\u0111\\u01b0\\u1ee3c khi t\\u1ea5n c\\u00f4ng ho\\u1eb7c ch\\u1ecbu \\u0111\\u1ef1ng s\\u00e1t th\\u01b0\\u01a1ng<\\/li>\\r\\n\\t<li><strong>Fast Boats:\\u00a0<\\/strong>Mua gamepass n\\u00e0y v\\u1edbi 350 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n \\u0111i\\u1ec1u khi\\u1ec3n c\\u00e1c lo\\u1ea1i thuy\\u1ec1n nhanh h\\u01a1n v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng bay<\\/li>\\r\\n\\t<li><strong>X2 Drop Chance:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd nh\\u00e2n \\u0111\\u00f4i t\\u1ef7 l\\u1ec7 r\\u01a1i c\\u1ee7a c\\u00e1c tr\\u00e1i c\\u00e2y v\\u00e0 c\\u00e1c v\\u1eadt ph\\u1ea9m qu\\u00fd hi\\u1ebfm kh\\u00e1c<\\/li>\\r\\n\\t<li><strong>Dark Blade<\\/strong>: Mua gamepass n\\u00e0y v\\u1edbi 300 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a thanh ki\\u1ebfm m\\u00e0u \\u0111en c\\u00f3 kh\\u1ea3 n\\u0103ng t\\u1ea1o ra s\\u00f3ng c\\u1eaft v\\u00e0 g\\u00e2y s\\u00e1t th\\u01b0\\u01a1ng cao.<\\/li>\\r\\n\\t<li><strong>Fruit Notifier:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 350 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n nh\\u1eadn \\u0111\\u01b0\\u1ee3c th\\u00f4ng b\\u00e1o khi c\\u00f3 tr\\u00e1i c\\u00e2y m\\u1edbi xu\\u1ea5t hi\\u1ec7n tr\\u00ean b\\u1ea3n \\u0111\\u1ed3<\\/li>\\r\\n\\t<li><strong>Kilo:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a s\\u1ee9c m\\u1ea1nh Kilo, m\\u1ed9t lo\\u1ea1i s\\u1ee9c m\\u1ea1nh \\u0111\\u1eb7c bi\\u1ec7t kh\\u00f4ng ph\\u1ea3i l\\u00e0 tr\\u00e1i c\\u00e2y.\\u00a0S\\u1ee9c m\\u1ea1nh n\\u00e0y cho ph\\u00e9p b\\u1ea1n thay \\u0111\\u1ed5i kh\\u1ed1i l\\u01b0\\u1ee3ng c\\u1ee7a c\\u01a1 th\\u1ec3 v\\u00e0 c\\u00e1c v\\u1eadt th\\u1ec3 xung quanh<\\/li>\\r\\n\\t<li><strong>Spin:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a s\\u1ee9c m\\u1ea1nh Spin, m\\u1ed9t lo\\u1ea1i s\\u1ee9c m\\u1ea1nh \\u0111\\u1eb7c bi\\u1ec7t kh\\u00f4ng ph\\u1ea3i l\\u00e0 tr\\u00e1i c\\u00e2y.\\u00a0S\\u1ee9c m\\u1ea1nh n\\u00e0y cho ph\\u00e9p b\\u1ea1n t\\u1ea1o ra c\\u00e1c l\\u01b0\\u1ee1i dao xoay quanh c\\u01a1 th\\u1ec3 v\\u00e0 g\\u00e2y s\\u00e1t th\\u01b0\\u01a1ng li\\u00ean t\\u1ee5c<\\/li>\\r\\n\\t<li><strong>Chop:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a s\\u1ee9c m\\u1ea1nh Chop, m\\u1ed9t lo\\u1ea1i s\\u1ee9c m\\u1ea1nh \\u0111\\u1eb7c bi\\u1ec7t kh\\u00f4ng ph\\u1ea3i l\\u00e0 tr\\u00e1i c\\u00e2y.\\u00a0S\\u1ee9c m\\u1ea1nh n\\u00e0y cho ph\\u00e9p b\\u1ea1n t\\u1ea1o ra c\\u00e1c l\\u01b0\\u1ee1ng c\\u1ef1c ki\\u1ebfm v\\u00e0 ch\\u00e9m xuy\\u00ean qua kh\\u00f4ng gian<\\/li>\\r\\n\\t<li><strong>Spring:\\u00a0<\\/strong>Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a s\\u1ee9c m\\u1ea1nh Spring, m\\u1ed9t lo\\u1ea1i s\\u1ee9c m\\u1ea1nh \\u0111\\u1eb7c bi\\u1ec7t kh\\u00f4ng ph\\u1ea3i l\\u00e0 tr\\u00e1i c\\u00e2y.\\u00a0S\\u1ee9c m\\u1ea1nh n\\u00e0y cho ph\\u00e9p b\\u1ea1n bi\\u1ebfn c\\u00e1c b\\u1ed9 ph\\u1eadn c\\u01a1 th\\u1ec3 th\\u00e0nh l\\u00f2 xo v\\u00e0 t\\u0103ng t\\u1ed1c \\u0111\\u1ed9 di chuy\\u1ec3n v\\u00e0 nh\\u1ea3y cao.<\\/li>\\r\\n\\t<li><strong>Bomb:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a s\\u1ee9c m\\u1ea1nh Bomb, m\\u1ed9t lo\\u1ea1i s\\u1ee9c m\\u1ea1nh \\u0111\\u1eb7c bi\\u1ec7t kh\\u00f4ng ph\\u1ea3i l\\u00e0 tr\\u00e1i c\\u00e2y.\\u00a0S\\u1ee9c m\\u1ea1nh n\\u00e0y cho ph\\u00e9p b\\u1ea1n t\\u1ea1o ra c\\u00e1c qu\\u1ea3 bom v\\u00e0 n\\u00e9m ch\\u00fang v\\u00e0o k\\u1ebb th\\u00f9<\\/li>\\r\\n\\t<li><strong>Smoke:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a s\\u1ee9c m\\u1ea1nh Smoke, m\\u1ed9t lo\\u1ea1i s\\u1ee9c m\\u1ea1nh \\u0111\\u1eb7c bi\\u1ec7t kh\\u00f4ng ph\\u1ea3i l\\u00e0 tr\\u00e1i c\\u00e2y.\\u00a0S\\u1ee9c m\\u1ea1nh n\\u00e0y cho ph\\u00e9p b\\u1ea1n t\\u1ea1o ra v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n kh\\u00f3i \\u0111\\u1ec3 che gi\\u1ea5u ho\\u1eb7c t\\u1ea5n c\\u00f4ng<\\/li>\\r\\n\\t<li><strong>Spike:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a s\\u1ee9c m\\u1ea1nh Spike, m\\u1ed9t lo\\u1ea1i s\\u1ee9c m\\u1ea1nh \\u0111\\u1eb7c bi\\u1ec7t kh\\u00f4ng ph\\u1ea3i l\\u00e0 tr\\u00e1i c\\u00e2y.\\u00a0S\\u1ee9c m\\u1ea1nh n\\u00e0y cho ph\\u00e9p b\\u1ea1n t\\u1ea1o ra v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n c\\u00e1c gai nh\\u1ecdn \\u0111\\u1ec3 b\\u1ea3o v\\u1ec7 ho\\u1eb7c g\\u00e2y s\\u00e1t th\\u01b0\\u01a1ng<\\/li>\\r\\n\\t<li><strong>Flame:<\\/strong>\\u00a0Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a s\\u1ee9c m\\u1ea1nh Flame, m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y thu\\u1ed9c h\\u1ec7 Logia.\\u00a0S\\u1ee9c m\\u1ea1nh n\\u00e0y cho ph\\u00e9p b\\u1ea1n bi\\u1ebfn th\\u00e0nh l\\u1eeda v\\u00e0 t\\u1ea1o ra c\\u00e1c ng\\u1ecdn l\\u1eeda \\u0111\\u1ec3 \\u0111\\u1ed1t ch\\u00e1y k\\u1ebb th\\u00f9<\\/li>\\r\\n\\t<li><strong>Bird:\\u00a0<\\/strong>Falcon: Mua gamepass n\\u00e0y v\\u1edbi 750 robux s\\u1ebd cho ph\\u00e9p b\\u1ea1n m\\u1edf kh\\u00f3a s\\u1ee9c m\\u1ea1nh Bird: Falcon, m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y thu\\u1ed9c h\\u1ec7 Zoan.\\u00a0S\\u1ee9c m\\u1ea1nh n\\u00e0y cho ph\\u00e9p b\\u1ea1n bi\\u1ebfn th\\u00e0nh chim c\\u1eaft ho\\u1eb7c h\\u00ecnh d\\u1ea1ng lai v\\u00e0 bay l\\u01b0\\u1ee3n tr\\u00ean b\\u1ea7u tr\\u1eddi.<\\/li>\\r\\n\\t<li><strong>Ice:\\u00a0<\\/strong>L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Ice.\\u00a0N\\u00f3 c\\u00f3 gi\\u00e1 350,000 ho\\u1eb7c 750 robux.\\u00a0N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Elemental v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng t\\u1ea1o ra v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n b\\u0103ng<\\/li>\\r\\n\\t<li><strong>Sand:\\u00a0<\\/strong>L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Sand. N\\u00f3 c\\u00f3 gi\\u00e1 250,000 ho\\u1eb7c 550 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Logia v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng bi\\u1ebfn h\\u00ecnh th\\u00e0nh v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n c\\u00e1t.<\\/li>\\r\\n\\t<li><strong>Dark:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Dark. N\\u00f3 c\\u00f3 gi\\u00e1 1,500,000 ho\\u1eb7c 1,800 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Logia v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng bi\\u1ebfn h\\u00ecnh th\\u00e0nh v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n b\\u00f3ng t\\u1ed1i.<\\/li>\\r\\n\\t<li><strong>Revive:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n h\\u1ed3i sinh ngay l\\u1eadp t\\u1ee9c sau khi ch\\u1ebft v\\u1edbi \\u0111\\u1ea7y m\\u00e1u v\\u00e0 stamina. N\\u00f3 c\\u00f3 gi\\u00e1 300 robux. N\\u00f3 r\\u1ea5t h\\u1eefu \\u00edch khi b\\u1ea1n \\u0111ang chi\\u1ebfn \\u0111\\u1ea5u v\\u1edbi c\\u00e1c boss ho\\u1eb7c ng\\u01b0\\u1eddi ch\\u01a1i kh\\u00e1c.<\\/li>\\r\\n\\t<li><strong>Diamond:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Diamond. N\\u00f3 c\\u00f3 gi\\u00e1 750,000 ho\\u1eb7c 1,100 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Paramecia v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng bi\\u1ebfn da c\\u1ee7a b\\u1ea1n th\\u00e0nh kim c\\u01b0\\u01a1ng \\u0111\\u1ec3 t\\u0103ng s\\u1ee9c b\\u1ec1n v\\u00e0 s\\u00e1t th\\u01b0\\u01a1ng.<\\/li>\\r\\n\\t<li><strong>Light:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Light.\\u00a0N\\u00f3 c\\u00f3 gi\\u00e1 650,000 ho\\u1eb7c 1,100 robux.\\u00a0N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Elemental v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng t\\u1ea1o ra v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n \\u00e1nh s\\u00e1ng.<\\/li>\\r\\n\\t<li><strong>Love:\\u00a0<\\/strong>L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Love. N\\u00f3 c\\u00f3 gi\\u00e1 350,000 ho\\u1eb7c 750 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Paramecia v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng b\\u1eafn ra nh\\u1eefng tr\\u00e1i tim \\u0111\\u1ec3 g\\u00e2y s\\u00e1t th\\u01b0\\u01a1ng v\\u00e0 l\\u00e0m say \\u0111\\u1eafm k\\u1ebb \\u0111\\u1ecbch.<\\/li>\\r\\n\\t<li><strong>Rubber:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Rubber. N\\u00f3 c\\u00f3 gi\\u00e1 250,000 ho\\u1eb7c 550 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Paramecia v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng bi\\u1ebfn c\\u01a1 th\\u1ec3 c\\u1ee7a b\\u1ea1n th\\u00e0nh cao su \\u0111\\u1ec3 t\\u0103ng \\u0111\\u00e0n h\\u1ed3i v\\u00e0 kh\\u1ea3 n\\u0103ng ch\\u1ecbu \\u0111i\\u1ec7n.<\\/li>\\r\\n\\t<li><strong>Barrier:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Barrier. N\\u00f3 c\\u00f3 gi\\u00e1 500,000 ho\\u1eb7c 900 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Paramecia v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng t\\u1ea1o ra nh\\u1eefng l\\u00e1 ch\\u1eafn \\u0111\\u1ec3 b\\u1ea3o v\\u1ec7 b\\u1ea3n th\\u00e2n ho\\u1eb7c t\\u1ea5n c\\u00f4ng k\\u1ebb \\u0111\\u1ecbch.<\\/li>\\r\\n\\t<li><strong>Magma:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Magma. N\\u00f3 c\\u00f3 gi\\u00e1 750,000 ho\\u1eb7c 1,100 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Logia v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng bi\\u1ebfn h\\u00ecnh th\\u00e0nh v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n dung nham.<\\/li>\\r\\n\\t<li><strong>Quake:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Quake.\\u00a0N\\u00f3 c\\u00f3 gi\\u00e1 1,000,000 ho\\u1eb7c 1,500 robux.\\u00a0N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Natural v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng t\\u1ea1o ra v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n c\\u00e1c c\\u00fa \\u0111\\u1ea5m s\\u1ed1c \\u0111\\u1ecba ch\\u1ea5n.<\\/li>\\r\\n\\t<li><strong>Human: Buddha:\\u00a0<\\/strong>L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Human: Buddha. N\\u00f3 c\\u00f3 gi\\u00e1 750,000 ho\\u1eb7c 1,100 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Zoan v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng bi\\u1ebfn h\\u00ecnh th\\u00e0nh m\\u1ed9t b\\u1ee9c t\\u01b0\\u1ee3ng Ph\\u1eadt kh\\u1ed5ng l\\u1ed3 \\u0111\\u1ec3 t\\u0103ng s\\u1ee9c m\\u1ea1nh v\\u00e0 k\\u00edch th\\u01b0\\u1edbc.<\\/li>\\r\\n\\t<li><strong>String:\\u00a0<\\/strong>L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 String. N\\u00f3 c\\u00f3 gi\\u00e1 750,000 ho\\u1eb7c 1,100 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Paramecia v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng t\\u1ea1o ra v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n c\\u00e1c s\\u1ee3i d\\u00e2y \\u0111\\u1ec3 b\\u1eaft ho\\u1eb7c c\\u1eaft k\\u1ebb \\u0111\\u1ecbch.<\\/li>\\r\\n\\t<li><strong>Bird: Phoenix:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Bird: Phoenix. N\\u00f3 c\\u00f3 gi\\u00e1 750,000 ho\\u1eb7c 1,100 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Zoan v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng bi\\u1ebfn h\\u00ecnh th\\u00e0nh m\\u1ed9t con chim ph\\u01b0\\u1ee3ng ho\\u00e0ng l\\u1eeda \\u0111\\u1ec3 bay l\\u01b0\\u1ee3n v\\u00e0 g\\u00e2y ch\\u00e1y k\\u1ebb \\u0111\\u1ecbch.<\\/li>\\r\\n\\t<li><strong>Portal:<\\/strong>\\u00a0L\\u00e0 m\\u1ed9t gamepass cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i \\u00e1c qu\\u1ef7 Portal. N\\u00f3 c\\u00f3 gi\\u00e1 500,000 ho\\u1eb7c 900 robux. N\\u00f3 l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i \\u00e1c qu\\u1ef7 thu\\u1ed9c h\\u1ec7 Paramecia v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng t\\u1ea1o ra c\\u00e1c c\\u1ed5ng v\\u00f2m \\u0111\\u1ec3 di chuy\\u1ec3n t\\u1ee9c th\\u1eddi.<\\/li>\\r\\n\\t<li><strong>Rumble:<\\/strong>\\u00a0\\u0110\\u00e2y l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y blox thu\\u1ed9c lo\\u1ea1i Nguy\\u00ean t\\u1ed1. N\\u00f3 c\\u00f3 gi\\u00e1 2.100.000 ho\\u1eb7c 2.100 t\\u1eeb Ng\\u01b0\\u1eddi b\\u00e1n Tr\\u00e1i c\\u00e2y Blox. N\\u00f3 c\\u0169ng c\\u00f3 th\\u1ec3 \\u0111\\u01b0\\u1ee3c nh\\u1eadn v\\u1edbi m\\u1ed9t kh\\u1ea3 n\\u0103ng nh\\u1ecf t\\u1eeb Anh h\\u1ecd c\\u1ee7a Ng\\u01b0\\u1eddi b\\u00e1n Tr\\u00e1i c\\u00e2y Blox. Tr\\u00e1i c\\u00e2y n\\u00e0y c\\u00f3 kh\\u1ea3 n\\u0103ng 2,25% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong m\\u1ed7i kho h\\u00e0ng v\\u00e0 kh\\u1ea3 n\\u0103ng 2,31% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong tr\\u00f2 ch\\u01a1i m\\u1ed7i gi\\u1edd. \\u0110\\u00e2y c\\u0169ng l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y t\\u1ed1t cho vi\\u1ec7c luy\\u1ec7n t\\u1eadp.<\\/li>\\r\\n\\t<li><strong>Paw:<\\/strong>\\u00a0\\u0110\\u00e2y l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y blox thu\\u1ed9c lo\\u1ea1i T\\u1ef1 nhi\\u00ean. N\\u00f3 c\\u00f3 gi\\u00e1 2.300.000 ho\\u1eb7c 2.200 t\\u1eeb Ng\\u01b0\\u1eddi b\\u00e1n Tr\\u00e1i c\\u00e2y Blox. Tr\\u00e1i c\\u00e2y n\\u00e0y c\\u00f3 kh\\u1ea3 n\\u0103ng 1,9% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong kho h\\u00e0ng v\\u00e0 2,83% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n d\\u01b0\\u1edbi m\\u1ed9t c\\u00e2y trong tr\\u00f2 ch\\u01a1i. Tr\\u00e1i c\\u00e2y n\\u00e0y \\u0111\\u01b0\\u1ee3c th\\u00eam v\\u00e0o C\\u1eadp nh\\u1eadt 7. \\u0110\\u00e2y l\\u00e0 m\\u1ed9t trong 13 lo\\u1ea1i tr\\u00e1i c\\u00e2y ph\\u00e1t s\\u00e1ng trong h\\u00ecnh th\\u1ee9c v\\u1eadt l\\u00fd c\\u1ee7a n\\u00f3.<\\/li>\\r\\n\\t<li><strong>Blizzard:<\\/strong>\\u00a0\\u0110\\u00e2y l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y blox thu\\u1ed9c lo\\u1ea1i Nguy\\u00ean t\\u1ed1. Ng\\u01b0\\u1eddi b\\u00e1n Tr\\u00e1i c\\u00e2y Blox t\\u00ednh ph\\u00ed 2.400.000 ho\\u1eb7c 2.250 cho n\\u00f3 v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng 1,8% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong kho h\\u00e0ng v\\u00e0 1,24% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong tr\\u00f2 ch\\u01a1i. N\\u00f3 \\u0111\\u01b0\\u1ee3c th\\u00eam v\\u00e0o C\\u1eadp nh\\u1eadt 18 (C\\u1eadp nh\\u1eadt Gi\\u00e1ng sinh). T\\u1ea5t c\\u1ea3 c\\u00e1c \\u0111\\u1ed9ng t\\u00e1c \\u0111\\u1ec1u c\\u00f3 th\\u1ec3 g\\u00e2y s\\u00e1t th\\u01b0\\u01a1ng cho c\\u00e1c con qu\\u00e1i v\\u1eadt bi\\u1ec3n.<\\/li>\\r\\n\\t<li><strong>Gravity:<\\/strong>\\u00a0\\u0110\\u00e2y l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y blox thu\\u1ed9c lo\\u1ea1i T\\u1ef1 nhi\\u00ean. Gravity \\u0111\\u01b0\\u1ee3c l\\u00e0m l\\u1ea1i trong C\\u1eadp nh\\u1eadt 17.3. N\\u00f3 c\\u00f3 gi\\u00e1 2.500.000 ho\\u1eb7c 2.300 t\\u1eeb Ng\\u01b0\\u1eddi b\\u00e1n Tr\\u00e1i c\\u00e2y Blox. Tr\\u00e1i c\\u00e2y n\\u00e0y c\\u00f3 kh\\u1ea3 n\\u0103ng 1,7% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong m\\u1ed7i kho h\\u00e0ng v\\u00e0 1,59% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong tr\\u00f2 ch\\u01a1i m\\u1ed7i gi\\u1edd. \\u0110\\u00e2y l\\u00e0 m\\u1ed9t trong s\\u1ed1 nhi\\u1ec1u lo\\u1ea1i tr\\u00e1i c\\u00e2y kh\\u00e1c c\\u00f3 ho\\u1ea1t h\\u00ecnh tr\\u00ean h\\u00ecnh th\\u1ee9c v\\u1eadt l\\u00fd.<\\/li>\\r\\n\\t<li><strong>Dough<\\/strong>:\\u00a0\\u0110\\u00e2y l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y blox thu\\u1ed9c lo\\u1ea1i T\\u1ef1 nhi\\u00ean. N\\u00f3 c\\u00f3 gi\\u00e1 2.500.000 ho\\u1eb7c 2.300 t\\u1eeb Ng\\u01b0\\u1eddi b\\u00e1n Tr\\u00e1i c\\u00e2y Blox v\\u00e0 c\\u00f3 kh\\u1ea3 n\\u0103ng 1,7% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong kho h\\u00e0ng v\\u00e0 1,59% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong tr\\u00f2 ch\\u01a1i m\\u1ed7i gi\\u1edd. Tr\\u00e1i c\\u00e2y n\\u00e0y c\\u00f3 th\\u1ec3 bi\\u1ebfn c\\u01a1 th\\u1ec3 ng\\u01b0\\u1eddi d\\u00f9ng th\\u00e0nh b\\u1ed9t v\\u00e0 t\\u1ea1o ra c\\u00e1c v\\u0169 kh\\u00ed b\\u1eb1ng b\\u1ed9t.<\\/li>\\r\\n\\t<li><strong>Shadow:\\u00a0<\\/strong>\\u0110\\u00e2y l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y blox thu\\u1ed9c lo\\u1ea1i T\\u1ef1 nhi\\u00ean. N\\u00f3 c\\u00f3 gi\\u00e1 2.900.000 ho\\u1eb7c 2.425 t\\u1eeb Ng\\u01b0\\u1eddi b\\u00e1n Tr\\u00e1i c\\u00e2y Blox v\\u00e0 \\u0111\\u01b0\\u1ee3c th\\u00eam v\\u00e0o C\\u1eadp nh\\u1eadt 16. Tr\\u00e1i c\\u00e2y n\\u00e0y c\\u00f3 kh\\u1ea3 n\\u0103ng 1,3% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong m\\u1ed7i kho h\\u00e0ng v\\u00e0 1,1% \\u0111\\u1ec3 xu\\u1ea5t hi\\u1ec7n trong tr\\u00f2 ch\\u01a1i m\\u1ed7i gi\\u1edd. Tr\\u00e1i c\\u00e2y n\\u00e0y c\\u00f3 th\\u1ec3 bi\\u1ebfn c\\u01a1 th\\u1ec3 ng\\u01b0\\u1eddi d\\u00f9ng th\\u00e0nh b\\u00f3ng t\\u1ed1i v\\u00e0 t\\u1ea1o ra c\\u00e1c v\\u0169 kh\\u00ed b\\u1eb1ng b\\u00f3ng t\\u1ed1i.<\\/li>\\r\\n\\t<li><strong>Venom:\\u00a0<\\/strong>Gamepass Venom cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i c\\u00e2y Venom, m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y t\\u1ef1 nhi\\u00ean c\\u00f3 kh\\u1ea3 n\\u0103ng t\\u1ea1o ra v\\u00e0 \\u0111i\\u1ec1u khi\\u1ec3n ch\\u1ea5t \\u0111\\u1ed9c.\\u00a0N\\u00f3 c\\u00f3 gi\\u00e1 3 tri\\u1ec7u ti\\u1ec1n trong game ho\\u1eb7c 2.450 robux t\\u1eeb Blox Fruit Dealer<\\/li>\\r\\n\\t<li><strong>Control:\\u00a0<\\/strong>Gamepass Control cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i c\\u00e2y Control, m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y t\\u1ef1 nhi\\u00ean hi\\u1ebfm c\\u00f3 kh\\u1ea3 n\\u0103ng \\u0111i\\u1ec1u khi\\u1ec3n c\\u00e1c v\\u1eadt th\\u1ec3 v\\u00e0 sinh v\\u1eadt.\\u00a0N\\u00f3 c\\u00f3 gi\\u00e1 3.200.000 ti\\u1ec1n trong game ho\\u1eb7c 2.500 robux t\\u1eeb Blox Fruit Dealer.\\u00a0\\u0110\\u00e2y l\\u00e0 lo\\u1ea1i tr\\u00e1i c\\u00e2y \\u0111\\u1eaft th\\u1ee9 t\\u01b0 trong game<\\/li>\\r\\n\\t<li><strong>Spirit:<\\/strong>\\u00a0Gamepass Spirit cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i c\\u00e2y Spirit, m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y t\\u1ef1 nhi\\u00ean c\\u00f3 kh\\u1ea3 n\\u0103ng t\\u1ea1o ra v\\u00e0 s\\u1eed d\\u1ee5ng c\\u00e1c \\u0111\\u00f2n t\\u1ea5n c\\u00f4ng d\\u1ef1a tr\\u00ean b\\u0103ng v\\u00e0 l\\u1eeda. \\u0110\\u00e2y l\\u00e0 m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y hi\\u1ec7u qu\\u1ea3 cho PVP v\\u00e0 l\\u00e0 lo\\u1ea1i tr\\u00e1i c\\u00e2y \\u0111\\u1eaft th\\u1ee9 ba trong game.\\u00a0N\\u00f3 c\\u00f3 gi\\u00e1 3.400.000 ti\\u1ec1n trong game ho\\u1eb7c 2.550 robux t\\u1eeb Blox Fruit Dealer.\\u00a0\\u0110\\u00e2y l\\u00e0 m\\u1ed9t trong s\\u1ed1 \\u00edt c\\u00e1c lo\\u1ea1i tr\\u00e1i c\\u00e2y c\\u00f3 hi\\u1ec7u \\u1ee9ng ho\\u1ea1t h\\u00ecnh khi \\u1edf d\\u1ea1ng v\\u1eadt l\\u00fd.<\\/li>\\r\\n\\t<li><strong>Dragon:<\\/strong>\\u00a0Gamepass Dragon cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i c\\u00e2y Dragon, m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y th\\u00fa c\\u00f3 kh\\u1ea3 n\\u0103ng bi\\u1ebfn h\\u00ecnh th\\u00e0nh m\\u1ed9t con r\\u1ed3ng v\\u00e0 t\\u1ea1o ra c\\u00e1c \\u0111\\u00f2n t\\u1ea5n c\\u00f4ng d\\u1ef1a tr\\u00ean l\\u1eeda.\\u00a0\\u0110\\u00e2y l\\u00e0 lo\\u1ea1i tr\\u00e1i c\\u00e2y \\u0111\\u1eaft nh\\u1ea5t trong game v\\u1edbi gi\\u00e1 3.500.000 ti\\u1ec1n trong game ho\\u1eb7c 2.600 robux t\\u1eeb Blox Fruit Dealer.\\u00a0\\u0110\\u00e2y c\\u0169ng l\\u00e0 lo\\u1ea1i tr\\u00e1i c\\u00e2y c\\u00f3 h\\u00ecnh d\\u1ea1ng bi\\u1ebfn h\\u00ecnh l\\u1edbn nh\\u1ea5t trong game, l\\u1edbn h\\u01a1n c\\u1ea3 Human: Buddha, Bird: Phoenix v\\u00e0 Leopard.<\\/li>\\r\\n\\t<li><strong>Leopard:\\u00a0<\\/strong>Gamepass Leopard cho ph\\u00e9p b\\u1ea1n s\\u1eed d\\u1ee5ng tr\\u00e1i c\\u00e2y Leopard, m\\u1ed9t lo\\u1ea1i tr\\u00e1i c\\u00e2y th\\u00fa c\\u00f3 kh\\u1ea3 n\\u0103ng bi\\u1ebfn h\\u00ecnh th\\u00e0nh m\\u1ed9t con b\\u00e1o v\\u00e0 t\\u1ea1o ra c\\u00e1c \\u0111\\u00f2n t\\u1ea5n c\\u00f4ng d\\u1ef1a tr\\u00ean m\\u00f3ng vu\\u1ed1t v\\u00e0 nh\\u1ea3y.\\u00a0\\u0110\\u00e2y l\\u00e0 lo\\u1ea1i tr\\u00e1i c\\u00e2y \\u0111\\u1eaft nh\\u1ea5t trong game v\\u1edbi gi\\u00e1 5.000.000 ti\\u1ec1n trong game ho\\u1eb7c 3.000 robux t\\u1eeb Blox Fruit Dealer.\\u00a0\\u0110\\u00e2y l\\u00e0 lo\\u1ea1i tr\\u00e1i c\\u00e2y th\\u00fa th\\u1ee9 n\\u0103m \\u0111\\u01b0\\u1ee3c th\\u00eam v\\u00e0o game v\\u00e0 l\\u00e0 lo\\u1ea1i tr\\u00e1i c\\u00e2y \\u0111\\u1ea7u ti\\u00ean c\\u00f3 gi\\u00e1 tr\\u1ecb cao h\\u01a1n Dragon<\\/li>\\r\\n<\\/ul>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n Roblox\",\"type\":\"text\",\"name\":\"taikhoanroblox\",\"option\":null}]}', 0, 100, 1),
(13, 5, 'item', 4, 'dich-vu-spongebob-tower-defense', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"D\\u1ecbch v\\u1ee5 SpongeBob Tower Defense\",\"thumb\":\"upload\\/product\\/7e01c4eb7434b1f2153f4b1a19a7fe3d.jpg\",\"thele\":\"<p>demo<\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n Roblox\",\"type\":\"text\",\"name\":\"taikhoanroblox\",\"option\":null},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u Roblox\",\"type\":\"text\",\"name\":\"matkhauroblox\",\"option\":null},{\"id\":2,\"label\":\"T\\u1eaft 2step + \\u0110i\\u1ec1n 3 game ch\\u01a1i g\\u1ea7n nh\\u1ea5t + Backup code (n\\u1ebfu c\\u00f3)\",\"type\":\"text\",\"name\":\"tat2stepdien3gamechoigannhatbackupcodeneuco\",\"option\":null}]}', 0, 100, 1);
INSERT INTO `subboostings` (`id`, `stt`, `type`, `category`, `type_category`, `link`, `detail`, `coefficient`, `fake`, `status`) VALUES
(15, 1, 'robux', 4, 'robux-120h-gamepass', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"Robux 120h Gamepass\",\"thumb\":\"upload\\/product\\/731989ef86c2361636771a62a88cdde5.jpg\",\"thele\":\"<table align=\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"center\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\" border=\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"1\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\" cellpadding=\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"1\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\" cellspacing=\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"1\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\">\\r\\n\\t<tbody>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u2705 N\\u1ea1p<em><strong>\\u00a0Robux<\\/strong><\\/em>\\u00a0s\\u1ea1ch, ch\\u00ednh h\\u00e3ng v\\u00e0 uy t\\u00edn<\\/td>\\r\\n\\t\\t\\t<td><strong>\\u2b50 Robux<\\/strong>\\u00a0\\u0111\\u01b0\\u1ee3c b\\u00e1n t\\u1ea1i Shop game Roblox l\\u00e0 ho\\u00e0n to\\u00e0n ch\\u00ednh h\\u00e3ng 100%<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u2705 Shop b\\u00e1n\\u00a0<strong><em>ROBUX GAMEPASS (120H)\\u00a0<\\/em><\\/strong>gi\\u00e1 r\\u1ebb, ti\\u1ec7n l\\u1ee3i<\\/td>\\r\\n\\t\\t\\t<td><strong>\\u2b50 Mua Roblox\\u00a0<\\/strong>v\\u1edbi gi\\u00e1 si\\u00eau r\\u1ebb so v\\u1edbi c\\u00e1c c\\u00e1ch mua th\\u00f4ng th\\u01b0\\u1eddng<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u2705 Shop\\u00a0<em><strong>B\\u00e1n Robux<\\/strong><\\/em>\\u00a0an to\\u00e0n nh\\u1ea5t<\\/td>\\r\\n\\t\\t\\t<td><strong>\\u2b50\\u00a0<\\/strong>H\\u1ec7 th\\u1ed1ng b\\u00e1n Robux an to\\u00e0n nh\\u1ea5t, n\\u1ea1p ngay tr\\u1ef1c ti\\u1ebfp v\\u00e0o t\\u00e0i kho\\u1ea3n game c\\u1ee7a ng\\u01b0\\u1eddi ch\\u01a1i ch\\u1ec9 b\\u1eb1ng t\\u00ean nh\\u00e2n v\\u1eadt\\u00a0<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t<\\/tbody>\\r\\n<\\/table>\\r\\n\\r\\n<p>M\\u1ee5c l\\u1ee5c<\\/p>\\r\\n\\r\\n<h2><strong>1. C\\u00e1ch t\\u1ea1o gamepass trong Roblox<\\/strong><\\/h2>\\r\\n\\r\\n<p>\\u0110\\u1ec3 t\\u1ea1o Gamepass trong game Roblox th\\u00ec b\\u1ea1n th\\u1ef1c hi\\u1ec7n \\u0111\\u00fang nh\\u01b0 nh\\u1eefng b\\u01b0\\u1edbc thao t\\u00e1c m\\u00e0 Shop game Roblox\\u00a0h\\u01b0\\u1edbng d\\u1eabn \\u1edf ph\\u00eda d\\u01b0\\u1edbi \\u0111\\u00e2y:<\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc\\u00a01: \\u0110\\u0103ng nh\\u1eadp<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>B\\u01b0\\u1edbc 1<\\/em><\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc\\u00a02: Ch\\u1ecdn Inventory &gt; Places &gt; Created by me<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>B\\u01b0\\u1edbc 2<\\/em><\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc\\u00a03: Ch\\u1ecdn Places c\\u1ee7a m\\u00ecnh &gt; Ch\\u1ecdn Store &gt; Add Pass<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>B\\u01b0\\u1edbc 3<\\/em><\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc\\u00a04: Ch\\u1ecdn Manage my experiences<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>B\\u01b0\\u1edbc 4<\\/em><\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc\\u00a05: Ch\\u1ecdn Creations &gt; Experience &gt; ch\\u1ecdn Place c\\u1ee7a m\\u00ecnh<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>B\\u01b0\\u1edbc 5<\\/em><\\/p>\\r\\n\\r\\n<p>Sau \\u0111\\u00f3, b\\u1ea1n ch\\u1ecdn \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"<strong>Basic settings<\\/strong>\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\" v\\u00e0\\u00a0c\\u00e0i \\u0111\\u1eb7t cho Place c\\u1ee7a m\\u00ecnh th\\u00e0nh public.\\u00a0<\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>C\\u00e0i \\u0111\\u1eb7t c\\u00f4ng khai<\\/em><\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc 6:<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- C\\u00e1ch 1:\\u00a0Ch\\u1ecdn Associated Items &gt; Passes &gt; Create a pass<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>T\\u1ea1o pass<\\/em><\\/p>\\r\\n\\r\\n<p><strong>- C\\u00e1ch 2: Ch\\u1ecdn Monetization Products &gt;\\u00a0<\\/strong><strong>Passes &gt; Create a pass<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>T\\u1ea1o pass<\\/em><\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc\\u00a07: Ch\\u1ecdn Upload image &gt; Create pass<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>B\\u01b0\\u1edbc 7<\\/em><\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc\\u00a08: Ch\\u1ecdn Pass v\\u1eeba t\\u1ea1o &gt; Sales &gt; nh\\u1ea5n m\\u1edf n\\u00fat Item for Sale &gt; c\\u00e0i s\\u1ed1 Robux &gt; nh\\u1ea5n Save changes<\\/strong><\\/p>\\r\\n\\r\\n<p>S\\u1ed1 Robux \\u1edf \\u0111\\u00e2y b\\u1ea1n c\\u00f3 th\\u1ec3 c\\u00e0i \\u0111\\u1eb7t l\\u1ea1i b\\u1ea5t c\\u1ee9 l\\u00fac n\\u00e0o b\\u1ea1n c\\u1ea7n.<\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>B\\u01b0\\u1edbc 8<\\/em><\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc\\u00a09: Nh\\u1ea5n Profile &gt; Creations\\u00a0\\u0111\\u1ec3 xem Gamepass b\\u1ea1n v\\u1eeba \\u00a0t\\u1ea1o<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>B\\u01b0\\u1edbc 9<\\/em><\\/p>\\r\\n\\r\\n<p>V\\u1eady l\\u00e0 ch\\u1ec9 v\\u1edbi nh\\u1eefng b\\u01b0\\u1edbc nh\\u01b0 tr\\u00ean Shop game Roblox\\u00a0h\\u01b0\\u1edbng d\\u1eabn th\\u00ec b\\u1ea1n \\u0111\\u00e3 c\\u00f3 th\\u1ec3 t\\u1ea1o Gamepass trong tr\\u00f2 ch\\u01a1i Roblox th\\u00e0nh c\\u00f4ng m\\u1ed9t c\\u00e1ch nhanh ch\\u00f3ng nh\\u1ea5t r\\u1ed3i!<\\/p>\\r\\n\\r\\n<h2><strong>2. N\\u1ea1p\\u00a0ROBUX GAMEPASS (120H) t\\u1ea1i Shop game Roblox!<\\/strong><\\/h2>\\r\\n\\r\\n<p><strong>Robux<\\/strong>\\u00a0hay c\\u00f2n \\u0111\\u01b0\\u1ee3c g\\u1ecdi\\u00a0l\\u00e0\\u00a0<strong>R$\\u00a0<\\/strong>\\u0111\\u1ec1u \\u0111\\u01b0\\u1ee3c xem l\\u00e0 m\\u1ed9t \\u0111\\u01a1n v\\u1ecb ti\\u1ec1n t\\u1ec7 \\u0111\\u01b0\\u1ee3c\\u00a0d\\u00f9ng chung cho t\\u1ea5t c\\u1ea3 nh\\u1eefng\\u00a0lo\\u1ea1i tr\\u00f2 ch\\u01a1i\\u00a0c\\u1ee7a Roblox. Ch\\u00fang \\u0111\\u01b0\\u1ee3c ra \\u0111\\u1eddi nh\\u01b0 m\\u1ed9t quy chu\\u1ea9n chung c\\u1ee7a nh\\u00e0 ph\\u00e1t h\\u00e0nh d\\u00e0nh cho t\\u1ea5t c\\u1ea3 t\\u1ef1a game c\\u1ee7a h\\u1ec7 th\\u1ed1ng\\u00a0<strong>roblox<\\/strong>, gi\\u00fap cho ng\\u01b0\\u1eddi ch\\u01a1i game n\\u00e0y c\\u00f3 \\u0111\\u01b0\\u1ee3c nh\\u1eefng s\\u1ef1 ti\\u1ec7n l\\u1ee3i t\\u01b0\\u01a1ng \\u0111\\u1ed1i cao, ch\\u1ec9 c\\u1ea7n d\\u00f9ng robux th\\u00f4i l\\u00e0 \\u0111\\u00e3 c\\u00f3 th\\u1ec3 mua \\u0111\\u01b0\\u1ee3c t\\u1ea5t c\\u1ea3 nh\\u1eefng th\\u1ee9 c\\u00f3 trong t\\u1ef1a game n\\u00e0y r\\u1ed3i.<\\/p>\\r\\n\\r\\n<p>Robux c\\u00f3 c\\u00e1c lo\\u1ea1i c\\u00f4ng d\\u1ee5ng nh\\u01b0 l\\u00e0:<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\u0110em l\\u1ea1i cho ng\\u01b0\\u1eddi ch\\u01a1i r\\u1ea5t nhi\\u1ec1u nh\\u1eefng s\\u1ef1 ti\\u1ec7n l\\u1ee3i \\u0111\\u1ec3 c\\u00f3 th\\u1ec3 mua \\u0111\\u01b0\\u1ee3c t\\u1ea5t c\\u1ea3 nh\\u1eefng th\\u1ee9 c\\u00f3 trong c\\u00e1c tr\\u00f2 ch\\u01a1i thu\\u1ed9c h\\u1ec7 th\\u1ed1ng\\u00a0c\\u1ee7a Roblox n\\u00e0y nh\\u01b0 l\\u00e0 c\\u00e1c trang ph\\u1ee5c, v\\u1eadt ph\\u1ea9m,... si\\u00eau hot hit,\\u00a0hay l\\u00e0 d\\u00f9ng\\u00a0\\u0111\\u1ec3 n\\u00e2ng c\\u1ea5p v\\u00e0\\u00a0trang b\\u1ecb cho c\\u00e1c nh\\u00e2n v\\u1eadt game c\\u1ee7a m\\u00ecnh nh\\u1eefng v\\u1eadt ph\\u1ea9m tuy\\u1ec7t \\u0111\\u1eb9p.\\u00a0<\\/li>\\r\\n\\t<li>Robux c\\u00f2n d\\u00f9ng\\u00a0\\u0111\\u1ec3 trao \\u0111\\u1ed5i c\\u00e1c n\\u0103ng l\\u1ef1c\\u00a0\\u0111\\u1eb7c bi\\u1ec7t \\u1edf trong c\\u00e1c tr\\u00f2 ch\\u01a1i\\u00a0thu\\u1ed9c c\\u00f9ng h\\u1ec7 th\\u1ed1ng\\u00a0game Roblox. T\\u1eeb \\u0111\\u00f3 th\\u00ec s\\u1ebd t\\u1ea1o cho\\u00a0ng\\u01b0\\u1eddi ch\\u01a1i nh\\u1eefng gi\\u00e2y ph\\u00fat ch\\u01a1i game v\\u00f4 c\\u00f9ng h\\u1ea5p d\\u1eabn v\\u00e0\\u00a0th\\u00fa v\\u1ecb.<\\/li>\\r\\n\\t<li>Robux\\u00a0c\\u0169ng \\u0111\\u01b0\\u1ee3c ng\\u01b0\\u1eddi ch\\u01a1i game Roblox d\\u00f9ng \\u0111\\u1ec3 t\\u1ea1o nh\\u00f3m, x\\u00e2y d\\u1ef1ng c\\u00e1c gia t\\u1ed9c, t\\u1ea1o ra m\\u1ed9t h\\u1ec7 th\\u1ed1ng nh\\u00e0 \\u1edf,...\\u00a0trong th\\u1ebf gi\\u1edbi game Roblox.<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>Hi\\u1ec7n t\\u1ea1i th\\u00ec c\\u00f3 r\\u1ea5t nhi\\u1ec1u nh\\u1eefng c\\u00e1ch th\\u1ee9c kh\\u00e1c nhau \\u0111\\u1ec3 ng\\u01b0\\u1eddi ch\\u01a1i c\\u00f3 th\\u1ec3 n\\u1ea1p \\u0111\\u01b0\\u1ee3c s\\u1ed1 l\\u01b0\\u1ee3ng robux m\\u00e0 ng\\u01b0\\u1eddi d\\u00f9ng mu\\u1ed1n.\\u00a0<strong>N\\u1ea1p robux<\\/strong>\\u00a0ngay tr\\u00ean trang n\\u1ea1p ch\\u00ednh th\\u1ee9c c\\u1ee7a Roblox \\u0111\\u01b0\\u1ee3c coi l\\u00e0 c\\u00e1ch th\\u1ee9c th\\u00f4ng th\\u01b0\\u1eddng nh\\u1ea5t, v\\u00e0 anh em ng\\u01b0\\u1eddi ch\\u01a1i game n\\u00e0y s\\u1eed d\\u1ee5ng nhi\\u1ec1u nh\\u1ea5t \\u0111\\u1ec3\\u00a0<strong>N\\u1ea1p Robux\\u00a0<\\/strong>an to\\u00e0n nh\\u1ea5t.<\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>N\\u1ea1p ROBUX GAMEPASS (120H) t\\u1ea1i Shop game Roblox<\\/em><\\/p>\\r\\n\\r\\n<p>Nh\\u01b0ng v\\u1edbi nh\\u1eefng\\u00a0s\\u1ef1 ph\\u00e1t tri\\u1ec3n c\\u1ee7a x\\u00e3 h\\u1ed9i\\u00a0hi\\u1ec7n nay, th\\u00ec b\\u1ea1n c\\u00f3 th\\u1ec3\\u00a0l\\u1ef1a ch\\u1ecdn\\u00a0<strong>N\\u1ea1p ROBUX GAMEPASS (120H)\\u00a0<\\/strong>ngay t\\u1ea1i\\u00a0<strong><em>Shop game Roblox.<\\/em><\\/strong>\\u00a0L\\u1ef1a ch\\u1ecdn n\\u1ea1p robux gamepass (120h) ngay t\\u1ea1i Shop game Roblox th\\u00ec ng\\u01b0\\u1eddi ch\\u01a1i s\\u1ebd mang v\\u1ec1 \\u0111\\u01b0\\u1ee3c s\\u1ed1 l\\u01b0\\u1ee3ng Robux c\\u1ef1c l\\u1edbn v\\u1edbi nh\\u1eefng m\\u1ee9c gi\\u00e1 c\\u1ea3 v\\u00f4 c\\u00f9ng r\\u1ebb.<\\/p>\\r\\n\\r\\n<p>Nh\\u1eefng robux \\u0111\\u01b0\\u1ee3c b\\u00e1n t\\u1ea1i danh m\\u1ee5c c\\u1ee7a\\u00a0<strong>shop Roblox\\u00a0<\\/strong>n\\u00e0y c\\u1ee7a ch\\u00fang t\\u00f4i \\u0111\\u01b0\\u1ee3c cung c\\u1ea5p m\\u1ed9t c\\u00e1ch ch\\u00ednh h\\u00e3ng v\\u00e0 an to\\u00e0n nh\\u1ea5t n\\u00ean ng\\u01b0\\u1eddi ch\\u01a1i c\\u00f3 th\\u1ec3 ho\\u00e0n to\\u00e0n y\\u00ean t\\u00e2m mua ch\\u00fang.<\\/p>\\r\\n\\r\\n<p>Ngo\\u00e0i ra th\\u00ec v\\u1eabn c\\u00f2n c\\u00f3 r\\u1ea5t nhi\\u1ec1u nh\\u1eefng l\\u00fd do kh\\u00e1c khi\\u1ebfn cho d\\u1ecbch v\\u1ee5\\u00a0<strong>N\\u1ea1p ROBUX GAMEPASS (120H)<\\/strong>\\u00a0t\\u1ea1i website<em><strong>\\u00a0Shop game Roblox\\u00a0<\\/strong><\\/em>v\\u1eabn\\u00a0lu\\u00f4n lu\\u00f4n \\u0111\\u01b0\\u1ee3c ng\\u01b0\\u1eddi ch\\u01a1i Roblox l\\u1ef1a ch\\u1ecdn s\\u1eed d\\u1ee5ng nhi\\u1ec1u nh\\u1ea5t hi\\u1ec7n nay, nh\\u01b0:<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>Shop game Roblox l\\u00e0 m\\u1ed9t\\u00a0<strong><em>shop b\\u00e1n Robux Roblox<\\/em><\\/strong>\\u00a0ch\\u00ednh h\\u00e3ng nh\\u1ea5t, ch\\u00fang t\\u00f4i c\\u00f3 th\\u1ec3 cam k\\u1ebft v\\u1edbi b\\u1ea1n r\\u1eb1ng nh\\u1eefng l\\u01b0\\u1ee3ng Robux \\u0111\\u01b0\\u1ee3c b\\u00e1n ra\\u00a0t\\u1eeb \\u0111\\u00e2y s\\u1ebd s\\u1ea1ch v\\u00e0 uy t\\u00edn\\u00a0100%. Nh\\u1eefng giao d\\u1ecbch t\\u1ea1i \\u0111\\u00e2y \\u0111\\u01b0\\u1ee3c c\\u00f4ng khai v\\u1ec1 gi\\u00e1 c\\u1ea3 v\\u00e0 ng\\u01b0\\u1eddi d\\u00f9ng c\\u00f3 th\\u1ec3 check l\\u1ea1i khi \\u0111\\u00e3 mua xong, n\\u1ebfu nh\\u01b0 sai ch\\u00fang t\\u00f4i s\\u1ebd \\u0111\\u1ed5i l\\u1ea1i cho b\\u1ea1n m\\u1ed9t c\\u00e1ch nhanh ch\\u00f3ng nh\\u1ea5t.<\\/li>\\r\\n\\t<li>Ch\\u00fang t\\u00f4i lu\\u00f4n c\\u1ed1 g\\u1eafng c\\u1ea3i thi\\u1ec7n h\\u1ec7 th\\u1ed1ng b\\u00e1n h\\u00e0ng c\\u1ee7a shop \\u0111\\u1ec3 mang \\u0111\\u1ebfn cho ng\\u01b0\\u1eddi ch\\u01a1i\\u00a0nh\\u1eefng d\\u1ecbch v\\u1ee5 game v\\u1ec1 roblox t\\u1ed1t nh\\u1ea5t. Qua m\\u1ed9t kho\\u1ea3ng th\\u1eddi gian ho\\u1ea1t \\u0111\\u1ed9ng v\\u00f4 c\\u00f9ng t\\u00edch c\\u1ef1c v\\u1eeba qua th\\u00ec\\u00a0shop ch\\u00fang t\\u00f4i \\u0111\\u00e3 nh\\u1eadn v\\u1ec1 nh\\u1eefng s\\u1ef1\\u00a0\\u1ee7ng h\\u1ed9 r\\u1ea5t nhi\\u1ec7t t\\u00ecnh t\\u1eeb c\\u00e1c kh\\u00e1ch h\\u00e0ng c\\u1ee7a m\\u00ecnh.\\u00a0V\\u00e0 ch\\u00fang t\\u00f4i c\\u0169ng \\u0111\\u00e3\\u00a0m\\u1edf ra r\\u1ea5t nhi\\u1ec1u nh\\u1eefng ch\\u01b0\\u01a1ng tr\\u00ecnh \\u01b0u \\u0111\\u00e3i v\\u00e0 khuy\\u1ebfn m\\u00e3i\\u00a0r\\u1ea5t l\\u1edbn cho c\\u00e1c kh\\u00e1ch h\\u00e0ng c\\u1ee7a m\\u00ecnh. Ch\\u00fang t\\u00f4i kh\\u1eb3g \\u0111\\u1ecbnh r\\u1eb1ng m\\u00ecnh c\\u00f3 c\\u00e1c d\\u1ecbch v\\u1ee5 b\\u00e1n robux, n\\u1ea1p robux gi\\u00e1 r\\u1ebb nh\\u1ea5t tr\\u00ean th\\u1ecb tr\\u01b0\\u1eddng hi\\u1ec7n nay.<\\/li>\\r\\n\\t<li>Shop game Roblox l\\u00e0 shop b\\u00e1n b\\u00e1n Robux ch\\u00ednh h\\u00e3ng, ch\\u00fang t\\u00f4i c\\u00f3 th\\u1ec3 gi\\u00fap cho ng\\u01b0\\u1eddi ch\\u01a1i n\\u1ea1p Robux KH\\u00d4NG GI\\u1edaI H\\u1ea0N. \\u0110\\u00e1p \\u1ee9ng \\u0111\\u01b0\\u1ee3c nh\\u1eefng l\\u01b0\\u1ee3ng nhu c\\u1ea7u c\\u1ef1c l\\u1edbn c\\u1ee7a ng\\u01b0\\u1eddi d\\u00f9ng game n\\u00e0y.<\\/li>\\r\\n\\t<li>Shop game Roblox ch\\u00fang t\\u00f4i\\u00a0lu\\u00f4n t\\u1ef1 h\\u00e0o v\\u00e0 c\\u00f3 th\\u1ec3 kh\\u1eb3ng \\u0111\\u1ecbnh m\\u00ecnh ch\\u00ednh l\\u00e0 m\\u1ed9t n\\u01a1i c\\u00f3\\u00a0<strong>b\\u00e1n Robux Roblox<\\/strong>\\u00a0uy t\\u00edn, ch\\u1ea5t l\\u01b0\\u1ee3ng v\\u00e0 c\\u00f3 c\\u00e1c m\\u1ee9c gi\\u00e1 v\\u00f4 c\\u00f9ng \\u01b0u \\u0111\\u00e3i, ph\\u1ea3i ch\\u0103ng h\\u00e0ng \\u0111\\u1ea7u tr\\u00ean th\\u1ecb tr\\u01b0\\u1eddng.<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>Shop game Roblox c\\u00f3 d\\u1ecbch v\\u1ee5<em><strong>\\u00a0N\\u1ea0P\\u00a0ROBUX GAMEPASS (120H)\\u00a0<\\/strong><\\/em>v\\u1edbi m\\u1ed9t m\\u1ee9c gi\\u00e1 \\u0111a d\\u1ea1ng, t\\u1eeb\\u00a05,000\\u0111 \\u0111\\u1ebfn 200,000\\u0111. N\\u1ebfu nh\\u01b0 m\\u00e0 b\\u1ea1n c\\u0169ng \\u0111ang mu\\u1ed1n N\\u1ea1p ROBUX GAMEPASS (120H) ngay b\\u00e2y gi\\u1edd? H\\u00e3y tham kh\\u1ea3o nh\\u1eefng b\\u01b0\\u1edbc h\\u01b0\\u1edbng d\\u1eabn\\u00a0d\\u01b0\\u1edbi \\u0111\\u00e2y \\u0111\\u1ec3 s\\u1eed d\\u1ee5ng d\\u1ecbch v\\u1ee5 n\\u1ea1p robux gamepass (120h) v\\u00e0 mang v\\u1ec1 nh\\u1eefng l\\u01b0\\u1ee3ng Robux gi\\u00e1 r\\u1ebb t\\u1eeb Shop nh\\u00e9!<\\/p>\\r\\n\\r\\n<h2><strong>3. H\\u01b0\\u1edbng d\\u1eabn\\u00a0n\\u1ea1p ROBUX GAMEPASS (120H)\\u00a0t\\u1ea1i Shop Shop game Roblox<\\/strong><\\/h2>\\r\\n\\r\\n<p><em><strong>N\\u1ea1p ROBUX GAMEPASS (120H)<\\/strong><\\/em>\\u00a0t\\u1ea1i shop Shop game Roblox \\u0111\\u1ec3 mua c\\u00e1c v\\u1eadt ph\\u1ea9m, trang ph\\u1ee5c c\\u1ea7n thi\\u1ebft cho nh\\u00e2n v\\u1eadt\\u00a0game Roblox c\\u1ee7a b\\u1ea1n. H\\u00e3y c\\u00f9ng tham kh\\u1ea3o c\\u00e1ch\\u00a0N\\u1ea1p Robux\\u00a0t\\u1ea1i\\u00a0<strong>Shop game Roblox<\\/strong>\\u00a0theo c\\u00e1c b\\u01b0\\u1edbc d\\u01b0\\u1edbi \\u0111\\u00e2y nh\\u00e9!<\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc 1:\\u00a0\\u0110\\u0103ng nh\\u1eadp v\\u00e0o t\\u00e0i kho\\u1ea3n c\\u1ee7a b\\u1ea1n \\u1edf website Shop game Roblox<\\/strong><\\/p>\\r\\n\\r\\n<p>Tr\\u01b0\\u1edbc h\\u1ebft, b\\u1ea1n s\\u1ebd c\\u1ea7n\\u00a0\\u0110\\u0103ng nh\\u1eadp v\\u00e0o t\\u00e0i kho\\u1ea3n shop ho\\u1eb7c \\u0110\\u0103ng k\\u00fd m\\u1ed9t t\\u00e0i kho\\u1ea3n t\\u1ea1i website Shop game Roblox. C\\u00e1ch l\\u00e0m nh\\u01b0 sau: T\\u1ea1i g\\u00f3c m\\u00e0n h\\u00ecnh\\u00a0ph\\u00eda tr\\u00ean b\\u00ean ph\\u1ea3i, b\\u1ea1n ch\\u1ecdn\\u00a0\\u0110\\u0103ng nh\\u1eadp\\/\\u0110\\u0103ng k\\u00fd<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li><strong>\\u0110\\u0103ng nh\\u1eadp:<\\/strong>\\u00a0B\\u1ea1n \\u0111i\\u1ec1n th\\u00f4ng tin t\\u00e0i kho\\u1ea3n \\u0111\\u1ec3 \\u0111\\u0103ng nh\\u1eadp khi \\u0111\\u00e3 c\\u00f3 t\\u00e0i kho\\u1ea3n. C\\u00f2n\\u00a0n\\u1ebfu ch\\u01b0a c\\u00f3 t\\u00e0i kho\\u1ea3n, b\\u1ea1n c\\u00f3 th\\u1ec3 Login\\u00a0b\\u1eb1ng<strong>\\u00a0Facebook.\\u00a0<\\/strong>N\\u1ebfu ch\\u01b0a c\\u00f3 t\\u00e0i kho\\u1ea3n v\\u00e0 kh\\u00f4ng mu\\u1ed1n \\u0111\\u0103ng nh\\u1eadp b\\u1eb1ng FB, b\\u1ea1n c\\u00f3 th\\u1ec3 ch\\u1ecdn\\u00a0sang ph\\u1ea7n\\u00a0\\u0110\\u0103ng k\\u00fd t\\u00e0i kho\\u1ea3n.<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>\\u0110\\u0103ng nh\\u1eadp ho\\u1eb7c \\u0111\\u0103ng k\\u00fd<\\/em><\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li><strong>\\u0110\\u0103ng k\\u00fd:<\\/strong>\\u00a0b\\u1ea1n vui l\\u00f2ng\\u00a0\\u0111i\\u1ec1n nh\\u1eefng th\\u00f4ng tin nh\\u01b0 tr\\u00ean khung hi\\u1ec3n th\\u1ecb y\\u00eau c\\u1ea7u. R\\u1ed3i \\u0111\\u0103ng k\\u00fd l\\u00e0 \\u0111\\u00e3 c\\u00f3 th\\u1ec3 t\\u1ea1o \\u0111\\u01b0\\u1ee3c t\\u00e0i kho\\u1ea3n th\\u00e0nh c\\u00f4ng.<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc 2: N\\u1ea1p ti\\u1ec1n v\\u00e0o webiste\\u00a0Shop game Roblox<\\/strong><\\/p>\\r\\n\\r\\n<p>Hi\\u1ec7n t\\u1ea1i,\\u00a0<strong>Shop game Roblox<\\/strong>\\u00a0c\\u00f3\\u00a0cung c\\u1ea5p \\u0111\\u1ebfn b\\u1ea1n\\u00a0h\\u00ecnh th\\u1ee9c n\\u1ea1p\\u00a0ch\\u00ednh l\\u00e0\\u00a0N\\u1ea1p ti\\u1ec1n qua th\\u1ebb c\\u00e0o v\\u00e0 atm t\\u1ef1 \\u0111\\u1ed9ng.<\\/p>\\r\\n\\r\\n<p>\\u00a0<\\/p>\\r\\n\\r\\n<p><em>N\\u1ea1p ti\\u1ec1n<\\/em><\\/p>\\r\\n\\r\\n<p><strong>B\\u01b0\\u1edbc 3: N\\u1ea1p Robux Gamepass\\u00a0t\\u1ea1i Shop game Roblox<\\/strong><\\/p>\\r\\n\\r\\n<p>Trong danh m\\u1ee5c \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"<strong><a href=\\\\\\\"\\\\\\\\\\\\\\\" rel=\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"follow\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\" target=\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"_blank\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\">D\\u1ecaCH V\\u1ee4<\\/a>\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"<\\/strong>, ch\\u1ecdn danh m\\u1ee5c\\u00a0\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"<strong>Mua ROBUX GAMEPASS (120H) Gi\\u00e1 R\\u1ebb\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\".<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u0110\\u1ec3 ti\\u1ebfn h\\u00e0nh n\\u1ea1p Robux, b\\u1ea1n nh\\u1eadp:<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li><strong>S\\u1ed1 ti\\u1ec1n c\\u1ea7n mua:<\\/strong>\\u00a0t\\u1eeb 5,000\\u0111 \\u0111\\u1ebfn 200,000\\u0111.<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p><strong>L\\u01b0u \\u00fd:<\\/strong>\\u00a0B\\u1ea1n c\\u1ea7n nh\\u1eadp \\u0111\\u1ec3 mua \\u0111\\u00fang s\\u1ed1 Robux m\\u00e0 b\\u1ea1n \\u0111\\u00e3 c\\u00e0i \\u0111\\u1eb7t \\u1edf trong Gamepass c\\u1ee7a b\\u1ea1n (<u>B\\u01b0\\u1edbc 8 \\u1edf m\\u1ee5c \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"C\\u00e1ch t\\u1ea1o\\u00a0Gamepass trong Roblox\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\" \\u1edf ph\\u00eda tr\\u00ean<\\/u>).<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>H\\u1ec7 s\\u1ed1 cho g\\u00f3i n\\u1ea1p t\\u1eeb\\u00a0<strong>10,000\\u0111<\\/strong>\\u00a0\\u0111\\u1ebfn d\\u01b0\\u1edbi\\u00a0<strong>50,000\\u0111<\\/strong>\\u00a0l\\u00e0\\u00a0<strong>6,9\\u00a0<\\/strong>c\\u00f2n \\u0111\\u1ed1i v\\u1edbi g\\u00f3i n\\u1ea1p t\\u1eeb\\u00a0<strong>50,000\\u0111\\u00a0<\\/strong>\\u0111\\u1ebfn d\\u01b0\\u1edbi<strong>\\u00a0100,000\\u0111<\\/strong>\\u00a0h\\u1ec7 s\\u1ed1 s\\u1ebd l\\u00e0\\u00a0<strong>7,3<\\/strong>\\u00a0v\\u00e0\\u00a0t\\u1eeb\\u00a0<strong>100,000\\u0111\\u00a0<\\/strong>tr\\u1edf l\\u00ean h\\u1ec7 s\\u1ed1 s\\u1ebd \\u0111\\u01b0\\u1ee3c n\\u00e2ng l\\u00ean\\u00a0<strong>7,7<\\/strong>.\\u00a0<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>Ti\\u1ebfp theo, b\\u1ea1n c\\u1ea7n nh\\u1eadp\\u00a0\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"<strong>t\\u00ean nh\\u00e2n v\\u1eadt Roblox c\\u1ee7a b\\u1ea1n\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"<\\/strong>. R\\u1ed3i sau \\u0111\\u00f3 nh\\u1ea5n ch\\u1ecdn\\u00a0<strong>Thanh to\\u00e1n<\\/strong>\\u00a0v\\u00e0\\u00a0<strong>X\\u00e1c nh\\u1eadn<\\/strong>.<\\/p>\\r\\n\\r\\n<p>Khi th\\u1ef1c hi\\u1ec7n \\u0111\\u01b0\\u1ee3c \\u0111\\u1ea7y \\u0111\\u1ee7 c\\u00e1c b\\u01b0\\u1edbc nh\\u01b0 tr\\u00ean v\\u00e0 \\u0111\\u1ee3i 5 ng\\u00e0y sau th\\u00ec\\u00a0giao d\\u1ecbch<em><strong>\\u00a0n\\u1ea1p Robux gamepass (120h)<\\/strong><\\/em>\\u00a0c\\u1ee7a b\\u1ea1n qua\\u00a0<strong>Shop game Roblox<\\/strong>\\u00a0\\u0111\\u00e3 ho\\u00e0n th\\u00e0nh r\\u1ed3i. Nhanh tay th\\u1eed ngay th\\u00f4i!<\\/p>\\r\\n\\r\\n<p><a href=\\\\\\\"\\\\\\\\\\\\\\\" rel=\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"follow\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\" target=\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"_blank\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"\\\\\\\\\\\\\\\"\\\\\\\"><strong>Shop game Roblox<\\/strong><\\/a><strong> <\\/strong>- Shop cung c\\u1ea5p c\\u00e1c d\\u1ecbch v\\u1ee5 game h\\u00e0ng \\u0111\\u1ea7u t\\u1ea1i Vi\\u1ec7t Nam. Ngo\\u00e0i b\\u00e1n acc, shop c\\u00f2n c\\u00f3 r\\u1ea5t nhi\\u1ec1u s\\u1ea3n ph\\u1ea9m d\\u1ecbch v\\u1ee5 game kh\\u00e1c: <strong><em>Nick Li\\u00ean Minh, N\\u1ea1p Kim C\\u01b0\\u01a1ng Free Fire, B\\u00e1n Robux Ch\\u00ednh H\\u00e3ng<\\/em><\\/strong>, ... H\\u00e3y truy c\\u1eadp v\\u00e0 tr\\u1ea3i nghi\\u1ec7m ngay!<\\/p>\",\"coefficient\":\"5.5\",\"unit\":\"Robux\",\"min\":\"10000\",\"max\":\"500000\",\"data\":[{\"id\":0,\"label\":\"Link gamepass\",\"type\":\"text\",\"name\":\"linkgamepass\",\"option\":null},{\"id\":1,\"label\":\"Ghi ch\\u00fa\",\"type\":\"text\",\"name\":\"ghichu\",\"option\":null}]}', 0, 100, 1),
(17, 2, 'caythue', 2, 'lam-de-san-de-nro', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"L\\u00e0m \\u0110\\u1ec7 - S\\u0103n \\u0110\\u1ec7 NRO\",\"thumb\":\"upload\\/product\\/26b4cc0f0989c04f47429228264c2b40.jpg\",\"thele\":\"<p>demo<\\/p>\",\"coefficient\":\"\",\"unit\":\"\",\"min\":\"\",\"max\":\"\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n game\",\"type\":\"text\",\"name\":\"taikhoangame\",\"option\":null,\"content\":null},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u Game\",\"type\":\"text\",\"name\":\"matkhaugame\",\"option\":null,\"content\":null},{\"id\":2,\"label\":\"Ch\\u1ecdn m\\u00e1y ch\\u1ee7\",\"type\":\"select\",\"name\":\"chonmaychu\",\"option\":\"M\\u00e1y ch\\u1ee7 1, M\\u00e1y ch\\u1ee7 2, M\\u00e1y ch\\u1ee7 3,M\\u00e1y ch\\u1ee7 4, M\\u00e1y ch\\u1ee7 5, M\\u00e1y ch\\u1ee7 6, M\\u00e1y ch\\u1ee7 7, M\\u00e1y ch\\u1ee7 8, M\\u00e1y ch\\u1ee7 9, M\\u00e1y ch\\u1ee7 10, M\\u00e1y ch\\u1ee7 11, M\\u00e1y ch\\u1ee7 12\",\"content\":\"Qu\\u00fd kh\\u00e1ch c\\u1ea7n chu\\u1ea9n b\\u1ecb s\\u1eb5n 50 ng\\u1ecdc \\u0111\\u1ec3 ch\\u00fang t\\u00f4i l\\u00e0m nhi\\u1ec7m v\\u1ee5\"}]}', 0, 100, 1),
(18, 7, 'caythue', 4, 'nap-quan-huy-lien-quan', '', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"N\\u1ea1p Qu\\u00e2n Huy Li\\u00ean Qu\\u00e2n\",\"thumb\":\"upload\\/product\\/5c9ef910f8430a121156d912f8da6a7a.png\",\"thele\":\"<p>Chi ti\\u1ebft d\\u1ecbch v\\u1ee5<\\/p>\\r\\n\\r\\n<table align=\\\\\\\"center\\\\\\\" border=\\\\\\\"1\\\\\\\" cellpadding=\\\\\\\"1\\\\\\\" cellspacing=\\\\\\\"1\\\\\\\">\\r\\n\\t<tbody>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u2705 N\\u1ea1p qu\\u00e2n huy uy t\\u00edn<\\/td>\\r\\n\\t\\t\\t<td><strong>\\u2b50<\\/strong>Qu\\u00e2n huy s\\u1ea1ch 100% c\\u00f3 x\\u00e1c nh\\u1eadn t\\u1eeb NPH Garena g\\u1eedi v\\u1ec1 th\\u01b0<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u2705 N\\u1ea1p qu\\u00e2n huy gi\\u00e1 r\\u1ebb<\\/td>\\r\\n\\t\\t\\t<td><strong>\\u2b50<\\/strong>Gi\\u00e1 r\\u1ebb nh\\u1ea5t th\\u1ecb tr\\u01b0\\u1eddng, n\\u1ea1p 150k l\\u00e3i 20k so v\\u1edbi n\\u1ea1p b\\u1eb1ng th\\u1ebb Garena<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u2705 N\\u1ea1p qu\\u00e2n huy nhanh<\\/td>\\r\\n\\t\\t\\t<td><strong>\\u2b50<\\/strong>Ch\\u1ec9 30s sau khi thanh to\\u00e1n l\\u00e0 qu\\u00e2n huy \\u0111\\u00e3 c\\u00f3 trong t\\u00e0i kho\\u1ea3n Li\\u00ean Qu\\u00e2n<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u2705 N\\u1ea1p qu\\u00e2n huy an to\\u00e0n<\\/td>\\r\\n\\t\\t\\t<td><strong>\\u2b50<\\/strong>C\\u00e1c giao d\\u1ecbch \\u0111\\u01b0\\u1ee3c m\\u00e3 h\\u00f3a 100%, \\u0111\\u1ea3m b\\u1ea3o v\\u1ec1 b\\u1ea3o m\\u1eadt t\\u00e0i kho\\u1ea3n kh\\u00e1ch h\\u00e0ng<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t<\\/tbody>\\r\\n<\\/table>\\r\\n\\r\\n<p><strong>Qu\\u00e2n Huy<\\/strong>\\u00a0l\\u00e0 \\u0111\\u01a1n v\\u1ecb ti\\u1ec1n t\\u1ec7 trong game\\u00a0<strong>Li\\u00ean Qu\\u00e2n Mobile<\\/strong>.\\u00a0Qu\\u00e2n huy\\u00a0c\\u00f3 th\\u1ec3 n\\u1ea1p topup\\u00a0tr\\u1ef1c ti\\u1ebfp, n\\u1ea1p\\u00a0b\\u1eb1ng th\\u1ebb Garena, card\\u00a0\\u0111i\\u1ec7n tho\\u1ea1i, ATM, v\\u00ed \\u0111i\\u1ec7n t\\u1eed,...Ng\\u01b0\\u1eddi ch\\u01a1i s\\u1eed d\\u1ee5ng Qu\\u00e2n Huy \\u0111\\u1ec3 mua ng\\u1ecdc, mua\\u00a0trang ph\\u1ee5c, mua t\\u01b0\\u1edbng\\u00a0t\\u01b0\\u1edbng\\u00a0trong game,\\u00a0quay kho b\\u00e1u v\\u00e0\\u00a0n\\u00e2ng c\\u1ea5p nh\\u00e2n v\\u1eadt nhanh ch\\u00f3ng.\\u00a0T\\u00f9y v\\u00e0o nhu c\\u1ea7u v\\u00e0 \\u0111i\\u1ec1u ki\\u1ec7n cho ph\\u00e9p, ng\\u01b0\\u1eddi ch\\u01a1i c\\u00f3 th\\u1ec3 n\\u1ea1p qu\\u00e2n huy theo c\\u00e1c c\\u00e1ch kh\\u00e1c nhau hay\\u00a0t\\u1eebng m\\u1ee9c \\u0111\\u1ed9 kh\\u00e1c nhau.<\\/p>\\r\\n\\r\\n<p>L\\u00e0m sao\\u00a0\\u0111\\u1ec3 c\\u00f3 th\\u1eadt nhi\\u1ec1u qu\\u00e2n huy\\u00a0v\\u1edbi gi\\u00e1 r\\u1ebb nh\\u1ea5t\\u00a0th\\u00ec l\\u1ea1i l\\u00e0 c\\u00e2u h\\u1ecfi c\\u1ee7a\\u00a0nhi\\u1ec1u ng\\u01b0\\u1eddi ch\\u01a1i game c\\u1ea7n \\u0111\\u00e1p \\u00e1n. V\\u1eady \\u0111\\u1ec3\\u00a0c\\u00f3 qu\\u00e2n huy\\u00a0trong li\\u00ean qu\\u00e2n\\u00a0v\\u1edbi gi\\u00e1 r\\u1ebb\\u00a0th\\u00ec ph\\u1ea3i l\\u00e0m ra sao ? Ngo\\u00e0i c\\u00e1ch\\u00a0mua th\\u1ebb garena\\u00a0\\u0111\\u1ec3 n\\u1ea1p\\u00a0tr\\u1ef1c ti\\u1ebfp, c\\u00e1c b\\u1ea1n c\\u00f2n c\\u00f3 m\\u1ed9t l\\u1ef1a ch\\u1ecdn kh\\u00e1c \\u0111\\u00f3 l\\u00e0\\u00a0<em><strong>Mua Qu\\u00e2n Huy Li\\u00ean Qu\\u00e2n\\u00a0Gi\\u00e1 R\\u1ebb<\\/strong><\\/em>\\u00a0t\\u1ea1i website n\\u1ea1p game gi\\u00e1 r\\u1ebb, uy t\\u00edn.\\u00a0<\\/p>\\r\\n\\r\\n<p>\\u0110\\u1ec3 mua qu\\u00e2n huy\\u00a0gi\\u00e1 r\\u1ebb b\\u1ea1n c\\u1ea7n ph\\u1ea3i t\\u00ecm nh\\u1eefng \\u0111\\u1ecba ch\\u1ec9\\u00a0<em><strong>n\\u1ea1p qu\\u00e2n huy\\u00a0gi\\u00e1 r\\u1ebb, uy t\\u00edn<\\/strong><\\/em>. M\\u1ed9t trong nh\\u1eefng \\u0111\\u1ecba ch\\u1ec9\\u00a0<u><strong>n\\u1ea1p qu\\u00e2n huy\\u00a0gi\\u00e1 r\\u1ebb, uy t\\u00edn<\\/strong><\\/u>\\u00a0\\u0111\\u01b0\\u1ee3c r\\u1ea5t nhi\\u1ec1u anh em game th\\u1ee7, youtuber nh\\u1eafc \\u0111\\u1ebfn trong th\\u1eddi gian v\\u1eeba qua ch\\u00ednh l\\u00e0 website. Ch\\u00fang t\\u00f4i l\\u00e0\\u00a0<strong>shop n\\u1ea1p qu\\u00e2n huy li\\u00ean qu\\u00e2n\\u00a0uy t\\u00edn<\\/strong>\\u00a0s\\u1ed1 1 hi\\u1ec7n nay.\\u00a0<\\/p>\\r\\n\\r\\n<p>B\\u1ea1n c\\u00f3 th\\u1ec3 ch\\u1ecdn g\\u00f3i\\u00a0<strong>n\\u1ea1p qu\\u00e2n huy\\u00a0<\\/strong>t\\u1eeb 16\\u00a0qu\\u00e2n huy\\u00a0\\u0111\\u1ebfn 340\\u00a0qu\\u00e2n huy. Ch\\u00fang t\\u00f4i\\u00a0<strong>b\\u00e1n qu\\u00e2n huy li\\u00ean qu\\u00e2n<\\/strong>\\u00a0v\\u1edbi s\\u1ed1 l\\u01b0\\u1ee3ng kh\\u00f4ng gi\\u1edbi h\\u1ea1n v\\u00e0 gi\\u00e1\\u00a0<strong>ch\\u1ec9 t\\u1eeb 7.700 \\u0111\\u1ed3ng<\\/strong>. \\u0110\\u00e2y l\\u00e0 g\\u00f3i n\\u1ea1p qu\\u00e2n huy\\u00a0v\\u00f4 c\\u00f9ng r\\u1ebb so v\\u1edbi c\\u00e1ch n\\u1ea1p b\\u1eb1ng th\\u1ebb Garena th\\u00f4ng th\\u01b0\\u1eddng. Khi b\\u1ea1n ch\\u1ecdn\\u00a0<strong>g\\u00f3i n\\u1ea1p 150K<\\/strong>, b\\u1ea1n s\\u1ebd\\u00a0<strong>c\\u00f3 ngay 340 qu\\u00e2n huy<\\/strong>,\\u00a0<strong>ti\\u1ebft ki\\u1ec7m \\u0111\\u1ebfn 20k<\\/strong>\\u00a0so v\\u1edbi khi\\u00a0<strong>n\\u1ea1p b\\u1eb1ng th\\u1ebb Garena<\\/strong>\\u00a0th\\u00f4ng th\\u01b0\\u1eddng v\\u1edbi\\u00a0<strong>m\\u1ed7i l\\u1ea7n mua<\\/strong>\\u00a0g\\u00f3i qu\\u00e2n huy150K, mua c\\u00e0ng nhi\\u1ec1u gi\\u00e1 c\\u00e0ng r\\u1ebb, ti\\u1ebft ki\\u1ec7m c\\u00e0ng nhi\\u1ec1u. Sau \\u0111\\u00e2y l\\u00e0 b\\u1ea3ng so s\\u00e1nh n\\u1ea1p qu\\u00e2n huy b\\u1eb1ng th\\u1ebb garena th\\u00f4ng th\\u01b0\\u1eddng v\\u00e0 n\\u1ea1p qu\\u00e2n huy t\\u1ea1i shop nick.vn<\\/p>\\r\\n\\r\\n<table align=\\\\\\\"center\\\\\\\" border=\\\\\\\"1\\\\\\\" cellpadding=\\\\\\\"1\\\\\\\" cellspacing=\\\\\\\"1\\\\\\\">\\r\\n\\t<tbody>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td colspan=\\\\\\\"2\\\\\\\"><strong>N\\u1ea1p Qu\\u00e2n Huy b\\u1eb1ng th\\u1ebb Garena<\\/strong><\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td><strong>M\\u1ec7nh gi\\u00e1<\\/strong><\\/td>\\r\\n\\t\\t\\t<td><strong>Qu\\u00e2n Huy\\u00a0<\\/strong><\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>10.000 VND<\\/td>\\r\\n\\t\\t\\t<td>20 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>20.000 VND<\\/td>\\r\\n\\t\\t\\t<td>40 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>50.000 VND<\\/td>\\r\\n\\t\\t\\t<td>105 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>100.000 VND\\u00a0<\\/td>\\r\\n\\t\\t\\t<td>210 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>200.000 VND<\\/td>\\r\\n\\t\\t\\t<td>425 Qu\\u00e2n Huy\\u00a0<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>500.000 VND<\\/td>\\r\\n\\t\\t\\t<td>1070 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td colspan=\\\\\\\"2\\\\\\\"><strong>N\\u1ea1p Qu\\u00e2n Huy qua shop<\\/strong><\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td><strong>M\\u1ec7nh gi\\u00e1<\\/strong><\\/td>\\r\\n\\t\\t\\t<td><strong>Qu\\u00e2n Huy<\\/strong><\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u00a0<\\/td>\\r\\n\\t\\t\\t<td>16 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u00a0<\\/td>\\r\\n\\t\\t\\t<td>32 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u00a0<\\/td>\\r\\n\\t\\t\\t<td>84 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u00a0<\\/td>\\r\\n\\t\\t\\t<td>168 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u00a0<\\/td>\\r\\n\\t\\t\\t<td>340 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t\\t<tr>\\r\\n\\t\\t\\t<td>\\u00a0<\\/td>\\r\\n\\t\\t\\t<td>856 Qu\\u00e2n Huy<\\/td>\\r\\n\\t\\t<\\/tr>\\r\\n\\t<\\/tbody>\\r\\n<\\/table>\\r\\n\\r\\n<p>T\\u1eeb\\u00a0b\\u1ea3ng tr\\u00ean, ta c\\u00f3 th\\u1ec3 th\\u1ea5y vi\\u1ec7c n\\u1ea1p Qu\\u00e2n Huy Li\\u00ean Qu\\u00e2n Mobile th\\u00f4ng qua Shop\\u00a0b\\u1ea1n nh\\u1eadn \\u0111\\u01b0\\u1ee3c nhi\\u1ec1u Qu\\u00e2n Huy c\\u0169ng nh\\u01b0 ti\\u1ebft ki\\u1ec7m \\u0111\\u01b0\\u1ee3c kh\\u00e1 nhi\\u1ec1u chi ph\\u00ed.<\\/p>\\r\\n\\r\\n<p><strong>Shop\\u00a0<\\/strong>c\\u00f3 giao di\\u1ec7n hi\\u1ec7n \\u0111\\u1ea1i, \\u0111\\u01a1n gi\\u1ea3n, b\\u1ea1n s\\u1ebd d\\u1ec5 d\\u00e0ng s\\u1eed d\\u1ee5ng tr\\u00ean c\\u00e1c thi\\u1ebft b\\u1ecb c\\u00f3 k\\u1ebft n\\u1ed1i internet nh\\u01b0: smartphone, tablet, laptop,... B\\u1ea1n ch\\u1ec9 c\\u1ea7n ch\\u1ecdn g\\u00f3i qu\\u00e2n huy\\u00a0m\\u00ecnh c\\u1ea7n n\\u1ea1p, \\u0111\\u1eb7t h\\u00e0ng v\\u00e0 thanh to\\u00e1n online l\\u00e0 b\\u1ea1n \\u0111\\u00e3 c\\u00f3 ngay qu\\u00e2n huy\\u00a0trong game li\\u00ean qu\\u00e2n mobile. Th\\u1eddi gian giao d\\u1ecbch v\\u00e0\\u00a0<strong>nh\\u1eadn qu\\u00e2n huy<\\/strong>\\u00a0trong game\\u00a0ch\\u01b0a \\u0111\\u1ebfn\\u00a0<strong>30 gi\\u00e2y<\\/strong>. B\\u1ea1n ho\\u00e0n to\\u00e0n c\\u00f3 th\\u1ec3 y\\u00ean t\\u00e2m v\\u1ec1 s\\u1ed1 qu\\u00e2n huy\\u00a0n\\u00e0y\\u00a0v\\u00e0 ch\\u00fang t\\u00f4i xin\\u00a0<strong>\\u0111\\u1ea3m b\\u1ea3o<\\/strong>\\u00a0<strong>qu\\u00e2n huy\\u00a0<\\/strong>\\u0111\\u01b0\\u1ee3c b\\u00e1n ra b\\u1edfi\\u00a0<em><u><strong>shop n\\u1ea1p qu\\u00e2n huy li\\u00ean qu\\u00e2n\\u00a0<\\/strong><\\/u><\\/em>l\\u00e0 ho\\u00e0n to\\u00e0n\\u00a0<strong>s\\u1ea1ch<\\/strong>\\u00a0v\\u00e0\\u00a0<strong>an to\\u00e0n 100%<\\/strong>\\u00a0v\\u00ec ch\\u00fang t\\u00f4i l\\u00e0\\u00a0<strong>\\u0111\\u1ea1i l\\u00fd n\\u1ea1p qu\\u00e2n huy \\u0111\\u01b0\\u1ee3c \\u1ee7y quy\\u1ec1n ch\\u00ednh th\\u1ee9c b\\u1edfi NPH Garena<\\/strong>.<\\/p>\\r\\n\\r\\n<p>Ch\\u00fang t\\u00f4i lu\\u00f4n b\\u1ea3o v\\u1ec7 th\\u00f4ng tin kh\\u00e1ch h\\u00e0ng b\\u1eb1ng nh\\u1eefng bi\\u1ec7n ph\\u00e1p b\\u1ea3o m\\u1eadt t\\u1ed1i \\u01b0u nh\\u1ea5t. \\u0110\\u1ed9i ng\\u0169 nh\\u00e2n vi\\u00ean lu\\u00f4n tr\\u1ef1c 24\\/7\\/365 s\\u1eb5n s\\u00e0ng \\u0111\\u1ec3 ph\\u1ee5c v\\u1ee5 kh\\u00e1ch h\\u00e0ng m\\u1ecdi l\\u00fac, m\\u1ecdi n\\u01a1i. Cam k\\u1ebft \\u0111\\u1ea3m b\\u1ea3o quy\\u1ec1n l\\u1ee3i c\\u1ee7a kh\\u00e1ch h\\u00e0ng khi n\\u1ea1p\\u00a0game t\\u1ea1i shop.<\\/p>\\r\\n\\r\\n<p>T\\u1ea1i\\u00a0<strong>shop<\\/strong>, b\\u1ea1n c\\u00f3 th\\u1ec3 th\\u1ef1c hi\\u1ec7n mua qu\\u00e2n huy li\\u00ean qu\\u00e2n mobile Garena v\\u1edbi nh\\u1eefng h\\u00ecnh th\\u1ee9c thanh to\\u00e1n \\u0111a d\\u1ea1ng nh\\u01b0:\\u00a0<strong>n\\u1ea1p qu\\u00e2n huy li\\u00ean qu\\u00e2n\\u00a0b\\u1eb1ng th\\u1ebb c\\u00e0o \\u0111i\\u1ec7n tho\\u1ea1i ho\\u1eb7c th\\u1ebb game kh\\u00e1c,\\u00a0mua qu\\u00e2n huy li\\u00ean qu\\u00e2n\\u00a0b\\u1eb1ng v\\u00ed \\u0111i\\u1ec7n t\\u1eed - ATM,\\u2026\\u00a0<\\/strong><\\/p>\\r\\n\\r\\n<p>V\\u1edbi nh\\u1eefng \\u01b0u \\u0111i\\u1ec3m n\\u1ed5i b\\u1eadt tr\\u00ean, ch\\u1eafc h\\u1eb3n ai ai trong ch\\u00fang ta c\\u0169ng s\\u1ebd l\\u1ef1a ch\\u1ecdn giao d\\u1ecbch mua\\u00a0<strong>qu\\u00e2n huy li\\u00ean qu\\u00e2n<\\/strong>\\u00a0t\\u1ea1i\\u00a0<strong>shop n\\u1ea1p qu\\u00e2n huy li\\u00ean qu\\u00e2n<\\/strong>\\u00a0ph\\u1ea3i kh\\u00f4ng?<\\/p>\\r\\n\\r\\n<p>Shop ch\\u00fang t\\u00f4i<strong>\\u00a0<\\/strong>l\\u00e0<strong>\\u00a0<\\/strong>shop\\u00a0n\\u1ea1p qu\\u00e2n huy\\u00a0uy t\\u00edn<strong>\\u00a0chuy\\u00ean cung c\\u1ea5p d\\u1ecbch v\\u1ee5 n\\u1ea1p\\u00a0qu\\u00e2n huy lq v\\u1edbi c\\u00e1c c\\u00e1ch n\\u1ea1p sau:\\u00a0<\\/strong>n\\u1ea1p qu\\u00e2n huy\\u00a0gi\\u00e1 r\\u1ebb,\\u00a0n\\u1ea1p qu\\u00e2n huy,\\u00a0<a href=\\\\\\\"https:\\/\\/theme5.kitio.net\\/dich-vu\\/nap-quan-huy-lien-quan\\\\\\\" rel=\\\\\\\"nofollow\\\\\\\" target=\\\\\\\"_blank\\\\\\\">n\\u1ea1p qu\\u00e2n huy\\u00a0li\\u00ean qu\\u00e2n<\\/a>,\\u00a0n\\u1ea1p qu\\u00e2n huy\\u00a0b\\u1eb1ng th\\u1ebb c\\u00e0o,\\u00a0n\\u1ea1p qu\\u00e2n huy\\u00a0li\\u00ean qu\\u00e2n b\\u1eb1ng th\\u1ebb c\\u00e0o,\\u00a0n\\u1ea1p qu\\u00e2n huy\\u00a0trong li\\u00ean qu\\u00e2n,\\u00a0n\\u1ea1p qu\\u00e2n huy\\u00a0b\\u1eb1ng th\\u1ebb ng\\u00e2n h\\u00e0ng,\\u00a0n\\u1ea1p qu\\u00e2n huy\\u00a0qua garena<\\/p>\",\"coefficient\":\"\",\"unit\":\"\",\"min\":\"\",\"max\":\"\",\"data\":[{\"id\":0,\"label\":\"T\\u00ean t\\u00e0i kho\\u1ea3n \\u0111\\u0103ng nh\\u1eadp li\\u00ean qu\\u00e2n\",\"type\":\"text\",\"name\":\"tentaikhoandangnhaplienquan\",\"option\":null,\"content\":null},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u \\u0111\\u0103ng nh\\u1eadp\",\"type\":\"text\",\"name\":\"matkhaudangnhap\",\"option\":null,\"content\":null}]}', 0, 100, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL,
  `stt` int(11) NOT NULL DEFAULT 1,
  `category` int(11) DEFAULT NULL,
  `type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `type_category` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `fake` int(11) NOT NULL DEFAULT 0,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_vietnamese_ci ROW_FORMAT=DYNAMIC;

--
-- Đang đổ dữ liệu cho bảng `subcategory`
--

INSERT INTO `subcategory` (`id`, `stt`, `category`, `type`, `type_category`, `detail`, `fake`, `status`) VALUES
(41, 5, 1, 'ACCOUNT', 'nick-lien-quan-tu-chon', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"NICK LI\\u00caN QU\\u00c2N T\\u1ef0 CH\\u1eccN\",\"thumb\":\"upload\\/product\\/efa8d44c35cfe47fbb09e914739315b0.gif\",\"tag\":\"\\/upload\\/tag\\/tagKMX1.png\",\"thele\":\"<p><a href=\\\\\\\"https:\\/\\/lienquan.garena.vn\\/\\\\\\\"><strong>Game Li\\u00ean Qu\\u00e2n Mobile<\\/strong><\\/a><strong>\\u00a0\\u0111\\u01b0\\u1ee3c ph\\u00e1t h\\u00e0nh b\\u1edfi\\u00a0<\\/strong><a href=\\\\\\\"https:\\/\\/www.garena.vn\\/\\\\\\\"><strong>GARENA<\\/strong><\\/a><strong>\\u00a0<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>\\u0110\\u1ed1i v\\u1edbi Acc TR\\u1eaeNG TH\\u00d4NG TIN<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- B\\u1eaft bu\\u1ed9c ph\\u1ea3i \\u0111\\u1ed5i M\\u1eadt Kh\\u1ea9u sau khi giao d\\u1ecbch th\\u00e0nh c\\u00f4ng t\\u1ea1i WEB, B\\u00ean SHOP s\\u1ebd kh\\u00f4ng b\\u1ea3o h\\u00e0nh nh\\u1eefng Acc Tr\\u1eafng th\\u00f4ng tin<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- SHOP ch\\u1ec9 b\\u1ea3o h\\u00e0nh Tr\\u01b0\\u1eddng H\\u1ee3p Acc B\\u1ecb C\\u1ea5m Do Tranh Ch\\u1ea5p ( tr\\u01b0\\u1eddng h\\u1ee3p acc b\\u1ecb kh\\u00f3a do s\\u1eed d\\u1ee5ng ph\\u1ea7n m\\u1ec1m th\\u1ee9 3, tool hack, mod skin\\u2026 shop kh\\u00f4ng h\\u1ed7 tr\\u1ee3 b\\u1ea3o h\\u00e0nh )<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- Trong tr\\u01b0\\u1eddng h\\u1ee3p Acc c\\u00f3 li\\u00ean k\\u1ebft FB th\\u00ec b\\u00ean shop \\u0111\\u00e3 KH\\u00d3A FB c\\u0169 n\\u00ean ch\\u1ec9 ch\\u01a1i tr\\u00ean Acc Garena Shop cung c\\u1ea5p, Tr\\u01b0\\u1eddng h\\u1ee3p FB kh\\u00f3a SHOP ch\\u1ec9 b\\u1ea3o h\\u00e0nh 30 ng\\u00e0y, qu\\u00e1 30 ng\\u00e0y b\\u00ean SHOP kh\\u00f4ng h\\u1ed7 tr\\u1ee3 b\\u1ea3o h\\u00e0nh<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>\\u0110\\u1ed1i v\\u1edbi Acc C\\u00f3 S\\u1ed1 \\u0110i\\u1ec7n Tho\\u1ea1i<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- Do c\\u01a1 ch\\u1ebf c\\u1ee7a Garena m\\u1ed7i t\\u00e0i kho\\u1ea3n Garena ch\\u1ec9 \\u0111\\u01b0\\u1ee3c thay \\u0111\\u1ed5i th\\u00f4ng tin m\\u1ed7i 30 ng\\u00e0y m\\u1ed9t l\\u1ea7n<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- N\\u00ean nh\\u1eefng t\\u00e0i kho\\u1ea3n c\\u00f3 S\\u0110T \\u1edf shop ch\\u01b0a t\\u1edbi ng\\u00e0y \\u0111\\u1ed5i S\\u0110T s\\u1ebd \\u0111\\u01b0\\u1ee3c shop b\\u1ea3o h\\u00e0nh trong th\\u01a1i gian ch\\u1edd \\u0111\\u1ed5i S\\u0110T<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- Kh\\u00e1ch H\\u00e0ng c\\u00f3 quy\\u1ec1n y\\u00eau c\\u1ea7u SHOP h\\u1ed7 tr\\u1ee3 c\\u00e0i th\\u00f4ng tin nh\\u01b0 Gmail, CMND (n\\u1ebfu \\u0111\\u01b0\\u1ee3c), LK FB (n\\u1ebfu \\u0111\\u01b0\\u1ee3c)<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>Trong th\\u1eddi gian ch\\u01b0a thay \\u0111\\u1ed5i th\\u00f4ng tin qu\\u00fd kh\\u00e1ch vui l\\u00f2ng KH\\u00d4NG N\\u1ea0P TI\\u1ec0N \\u0111\\u1ec3 tr\\u00e1nh th\\u01b0\\u1eddng h\\u1ee3p acc l\\u1ed7i shop s\\u1ebd KH\\u00d4NG ch\\u1ecbu tr\\u00e1ch nhi\\u1ec7m v\\u1ec1 s\\u1ed1 ti\\u1ec1n kh\\u00e1ch n\\u1ea1p<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>Vui L\\u00f2ng \\u0110\\u1ed5i M\\u1eadt Kh\\u1ea9u Ngay Sau Khi Mua Nick\\u00a0<\\/strong><strong>\\u00a0<\\/strong><a href=\\\\\\\"https:\\/\\/sso.garena.com\\/ui\\/login?app_id=10100&amp;redirect_uri=https%3A%2F%2Faccount.garena.com%2F%3Flocale_name%3DVN&amp;locale=vi-VN\\\\\\\"><strong>T\\u1ea1i \\u0110\\u00e2y<\\/strong><\\/a><\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"},{\"id\":2,\"label\":\"Rank\",\"type\":\"select\",\"name\":\"rank\",\"value\":\"\\u0110\\u1ed3ng|B\\u1ea1c|V\\u00e0ng|B\\u1ea1ch Kim|Kim \",\"show\":\"on\"},{\"id\":3,\"label\":\"\\u0110\\u00e1 qu\\u00fd\",\"type\":\"select\",\"name\":\"daquy\",\"value\":\"Kh\\u00f4ng|C\\u00f3\",\"show\":\"on\"},{\"id\":4,\"label\":\"Trang ph\\u1ee5c\",\"type\":\"number\",\"name\":\"trangphuc\",\"value\":\"\",\"show\":\"on\"},{\"id\":5,\"label\":\"T\\u01b0\\u1edbng\",\"type\":\"number\",\"name\":\"tuong\",\"value\":\"\",\"show\":\"on\"}]}', 1000, 1),
(43, 2, 1, 'ACCOUNT', '100-trung-nick-sieu-pham', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"100% TR\\u00daNG NICK SI\\u00caU PH\\u1ea8M\",\"thumb\":\"upload\\/product\\/9f8e24572f620fc9df0e3e1a6bf169bf.gif\",\"tag\":\"\\/upload\\/tag\\/tag35MO.png\",\"thele\":\"\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(44, 1, 2, 'ACCOUNT', 'mua-nick-free-fire-sieu-re', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"MUA NICK FREE FIRE SI\\u00caU R\\u1eba\",\"thumb\":\"upload\\/product\\/321df7c6dc353b7dbf60049824229c6e.gif\",\"tag\":\"\",\"thele\":\"\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(45, 2, 2, 'ACCOUNT', 'nick-free-fire-sale-90-', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"NICK FREE FIRE SALE 90%\",\"thumb\":\"upload\\/product\\/8b0147aacc75fee0b829964a5830221c.gif\",\"tag\":\"\",\"thele\":\"<p>Ch\\u00e0o m\\u1eebng b\\u1ea1n \\u0111\\u1ebfn v\\u1edbi ch\\u01b0\\u01a1ng tr\\u00ecnh \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"NICK FREE FIRE FLASH SALE TH\\u00c1NG 8\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\" ! T\\u1ea1i \\u0111\\u00e2y, b\\u1ea1n c\\u00f3 th\\u1ec3 t\\u00ecm th\\u1ea5y nh\\u1eefng t\\u00e0i kho\\u1ea3n VIP v\\u1edbi gi\\u00e1 c\\u1ef1c k\\u1ef3 h\\u1ea5p d\\u1eabn, gi\\u1ea3m \\u0111\\u1ebfn 90%. Ch\\u00fang t\\u00f4i s\\u1ebd c\\u1eadp nh\\u1eadt nh\\u1eefng t\\u00e0i kho\\u1ea3n HOT m\\u1ed7i ng\\u00e0y, v\\u1edbi s\\u1ed1 l\\u01b0\\u1ee3ng gi\\u1edbi h\\u1ea1n v\\u00e0 gi\\u00e1 c\\u1ef1c s\\u1ed1c \\u0111\\u1ec3 b\\u1ea1n c\\u00f3 th\\u1ec3 s\\u1edf h\\u1eefu nhanh ch\\u00f3ng. \\u0110\\u1eebng b\\u1ecf l\\u1ee1 c\\u01a1 h\\u1ed9i v\\u00e0 c\\u00f9ng nhau s\\u0103n l\\u00f9ng nh\\u1eefng t\\u00e0i kho\\u1ea3n VIP gi\\u00e1 r\\u1ebb t\\u1ea1i ch\\u01b0\\u01a1ng tr\\u00ecnh \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"NICK FREE FIRE FLASH SALE TH\\u00c1NG 8\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\"<\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(46, 3, 2, 'ACCOUNT', 'ban-nick-freefire-sever-indo', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"B\\u00c1N NICK FREEFIRE SEVER INDO\",\"thumb\":\"upload\\/product\\/d6ae3893c11bfe1df939e6a105daeca7.gif\",\"tag\":\"\",\"cash\":\"\",\"thele\":\"\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"on\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"select\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(47, 1, 5, 'RANDOM', 'ramdom-acc-toc-v4-100-co-mochi-v2', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"RAMDOM ACC T\\u1ed8C V4 100% C\\u00d3 MOCHI V2\",\"thumb\":\"upload\\/product\\/edd54c4e483277fa05280e9bbf3f737a.gif\",\"tag\":\"\\/upload\\/tag\\/tagZYR4.png\",\"cash\":\"9000\",\"thele\":\"\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(48, 2, 5, 'RANDOM', 'acc-100-toc-v4-random-gear', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"ACC 100% T\\u1ed8C V4 RANDOM GEAR\",\"thumb\":\"upload\\/product\\/780f9170a33bf9fc2886c82108c4bc5f.gif\",\"tag\":\"\\/upload\\/tag\\/tagZYR4.png\",\"cash\":\"9000\",\"thele\":\"<p><strong>SHOP CAM K\\u1ebeT Acc 100% T\\u1ed9c V4 : Random T\\u1ed9c\\u00a0<\\/strong><strong>Human, C\\u00e1, Th\\u1ecf, Cybord, Qu\\u1ef7, Thi\\u00ean Th\\u1ea7n<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>Random 1-5 Gear<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>T\\u1ec9 l\\u1ec7 cao CDK, Soul Guitar, Triple Katana<\\/strong><\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 500, 1),
(49, 3, 5, 'RANDOM', 'danh-muc-moi-acc-co-god-cdk-soul-guitar', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"DANH M\\u1ee4C ( M\\u1edaI ) ACC C\\u00d3 GOD - CDK - SOUL GUITAR\",\"thumb\":\"upload\\/product\\/f67f4ca37089058a0430da409358e130.gif\",\"tag\":\"\\/upload\\/tag\\/tagZYR4.png\",\"cash\":\"10000\",\"thele\":\"<p>Acc 100% C\\u00f3 GodHuman, CDK, Soul Guitar<\\/p>\\r\\n\\r\\n<p>Random tr\\u00e1i VIP<\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 555, 1),
(50, 4, 5, 'RANDOM', '-sieu-moi-acc-co-trai-kitsune', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"( SI\\u00caU M\\u1edaI ) ACC C\\u00d3 TR\\u00c1I KITSUNE\",\"thumb\":\"upload\\/product\\/5f506c24f6f711f2d39cfa8d3d6ca579.gif\",\"tag\":\"\\/upload\\/tag\\/tagZYR4.png\",\"cash\":\"300000\",\"thele\":\"<p><strong>T\\u00e0i Kho\\u1ea3n Roblox Cam K\\u1ebft :<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>100% Acc C\\u00f3 Tr\\u00e1i Kitsune\\u00a0<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>Random Godhuman - CDK\\/SG<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>50% T\\u1ef7 l\\u1ec7 c\\u00f3 th\\u00eam Mochi , Leopard - C\\u00f3 acc c\\u00f3 2 tr\\u00e1i Kitsune<\\/strong><\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 555, 1),
(51, 5, 5, 'RANDOM', '-sieu-hot-san-acc-blox-fruit-9k', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"(SI\\u00caU HOT) S\\u0102N ACC BLOX FRUIT 9K\",\"thumb\":\"upload\\/product\\/07e80fa8194f4a09797052de81567023.gif\",\"tag\":\"\\/upload\\/tag\\/tag35MO.png\",\"cash\":\"200000\",\"thele\":\"<p>RANDOM 30% LEOPARD, DOUGH v.v<\\/p>\\r\\n\\r\\n<p>RANDOM MELEE V2 C\\u1ef0C NGON<\\/p>\\r\\n\\r\\n<p>ACC LV TH\\u1ea4P NH\\u1ea4T 1XXX - MAX LEVEL<\\/p>\\r\\n\\r\\n<p>S\\u1ed0 L\\u01af\\u1ee2NG C\\u00d3 H\\u1ea0N CH\\u1ec8 200 ACC M\\u1ed6I NG\\u00c0Y<\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 555, 1),
(52, 1, 7, 'ACCOUNT', 'tai-khoan-lien-minh-huyen-thoai', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"T\\u00e0i Kho\\u1ea3n Li\\u00ean Minh Huy\\u1ec1n Tho\\u1ea1i\",\"thumb\":\"upload\\/product\\/e18059feb098f23bd60821180f3784c4.gif\",\"tag\":\"\\/upload\\/tag\\/tagZYR4.png\",\"thele\":\"<p><strong>- T\\u1ea5t c\\u1ea3 c&aacute;c t&agrave;i kho\\u1ea3n tr&ecirc;n shop \\u0111\\u1ec1u l&agrave; t&agrave;i kho\\u1ea3n riot. Anh em mua acc vui l&ograve;ng \\u0111\\u0103ng nh\\u1eadp riot kh&ocirc;ng ph\\u1ea3i \\u0111\\u0103ng nh\\u1eadp garena.<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>!!!&nbsp;CH&Uacute; &Yacute; : \\u0110&acirc;y l&agrave; t&agrave;i kho\\u1ea3n riot game.Mua xong acc b\\u1ea1n vui l&ograve;ng v&agrave;o link :&nbsp;<\\/strong><a href=\\\"\\\\\\\"><span style=\\\"color:#e74c3c\\\"><strong>https:\\/\\/account.riotgames.com\\/#personal-information<\\/strong><\\/span><\\/a><strong>&nbsp;<\\/strong><strong>V&agrave;o link tr&ecirc;n : \\u0110\\u1ed5i Mail - \\u0110\\u1ed5i M\\u1eadt Kh\\u1ea9u - B\\u1eadt X&aacute;c Minh 2 b\\u01b0\\u1edbc, t&agrave;i kho\\u1ea3n s\\u1ebd \\u0111\\u01b0\\u1ee3c b\\u1ea3o h&agrave;nh 1 th&aacute;ng k\\u1ec3 t\\u1eeb ng&agrave;y mua sau 1 th&aacute;ng n\\u1ebfu x\\u1ea3y ra t&igrave;nh tr\\u1ea1ng m\\u1ea5t acc shop kh&ocirc;ng ch\\u1ecbu tr&aacute;ch nhi\\u1ec7m<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- M\\u1ecdi th\\u1eafc m\\u1eafc anh em li&ecirc;n h\\u1ec7 fanpage b&ecirc;n d\\u01b0\\u1edbi g&oacute;c ph\\u1ea3i m&agrave;n h&igrave;nh! xin c\\u1ea3m \\u01a1n!<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>Gi&aacute; Nick Li&ecirc;n Minh \\u0110ang Gi\\u1ea3m 50% Anh EM Mua Ngay N&agrave;o<\\/strong><\\/p>\\r\\n\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"},{\"id\":2,\"label\":\"Rank\",\"type\":\"select\",\"name\":\"rank\",\"value\":\"S\\u1eaft|\\u0110\\u1ed3ng|B\\u1ea1c|B\\u1ea1ch kim|Kim C\\u01b0\\u01a1ng|Cao th\\u1ee7\",\"show\":\"on\"},{\"id\":3,\"label\":\"T\\u01b0\\u1edbng\",\"type\":\"select\",\"name\":\"tuong\",\"value\":\"Annie|Olaf|Galio|Twisted Fate|Xin Zhao|Urgot|LeBlanc|Vladimir\",\"show\":\"on\"},{\"id\":4,\"label\":\"Trang ph\\u1ee5c\",\"type\":\"select\",\"name\":\"trangphuc\",\"value\":\"Annie G\\u00f4-t\\u00edch|Annie Qu\\u00e0ng Kh\\u0103n \\u0110\\u1ecf|Annie \\u1edf X\\u1ee9 Th\\u1ea7n Ti\\u00ean\",\"show\":\"on\"},{\"id\":5,\"label\":\"Linh th\\u00fa\",\"type\":\"select\",\"name\":\"linhthu\",\"value\":\"Th\\u1ee7y Th\\u1ea7n|Th\\u1ee7y Th\\u1ea7n Ngo\\u00e0i H\\u00e0nh Tinh|Th\\u1ee7y Th\\u1ea7n Ng\\u1ecdc \\u0110\\u1ebf\",\"show\":\"on\"}]}', 1000, 1),
(53, 2, 7, 'RANDOM', 'van-may-dtcl-trung-pet-tim', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"V\\u1eadn May \\u0110TCL Tr\\u00fang Pet T\\u00edm\",\"thumb\":\"upload\\/product\\/d2dcd8b81b8cb3e29812c4b60530ccad.png\",\"tag\":\"\\/upload\\/tag\\/tagZYR4.png\",\"cash\":\"200000\",\"thele\":\"<p style=\\\\\\\"text-align:center\\\\\\\"><strong>Th\\u1eed v\\u1eadn may \\u0110TCL 200K b\\u1ea1n ph\\u1ea3i ch\\u1ea5p nh\\u1eadn h\\u00ean xui<\\/strong><\\/p>\\r\\n\\r\\n<p style=\\\\\\\"text-align:center\\\\\\\">\\u2b50\\ufe0f<strong>\\u00a0100% Acc Tr\\u1eafng Th\\u00f4ng Tin :\\u00a0\\u0110\\u1ed5i \\u0110\\u01b0\\u1ee3c Email V\\u00e0 M\\u1eadt Kh\\u1ea9u<\\/strong>\\u2b50\\ufe0f<strong>\\u00a0<\\/strong><\\/p>\\r\\n\\r\\n<p style=\\\\\\\"text-align:center\\\\\\\"><strong>100% Tr\\u00fang Acc C\\u00f3 2 P\\u00e9t 3* Tr\\u1edf L\\u00eanHo\\u1eb7c Acc Tr\\u00ean 30 P\\u00e9t (Kh\\u00f4ng T\\u00ednh P\\u00e9t M\\u1eb7c \\u0110\\u1ecbnh)<\\/strong><\\/p>\\r\\n\\r\\n<p style=\\\\\\\"text-align:center\\\\\\\">\\u2b50\\ufe0f<strong>\\u00a0<\\/strong><strong>20% Tr\\u00fang Acc C\\u00f3 p\\u00e9t T\\u00edm<\\/strong><\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 444, 1),
(54, 3, 7, 'RANDOM', 'van-may-lien-minh-100k', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"V\\u1eadn May Li\\u00ean Minh 100k\",\"thumb\":\"upload\\/product\\/f6956d76baf9718c9f4372bd9688f569.png\",\"tag\":\"\\/upload\\/tag\\/tagZYR4.png\",\"cash\":\"100000\",\"thele\":\"<p><strong>Th\\u1eed v\\u1eadn may 100K b\\u1ea1n ph\\u1ea3i ch\\u1ea5p nh\\u1eadn h\\u00ean xui<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u2705<strong>\\u00a0100% Acc \\u0110\\u00fang T\\u00e0i Kho\\u1ea3n V\\u00e0 M\\u1eadt Kh\\u1ea9u<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u2705\\u00a0<strong>100% Acc Tr\\u1eafng Th\\u00f4ng Tin : \\u0110\\u1ed5i \\u0110\\u01b0\\u1ee3c Mail V\\u00e0 Pass<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u2705\\u00a0<strong>100% Tr\\u00fang Acc Tr\\u00ean 20 t\\u01b0\\u1edbng skin h\\u00ean xui<\\/strong><\\/p>\\r\\n\\r\\n<p>\\u2705\\u00a0<strong>30% Tr\\u00fang Acc Kh\\u1ee7ng T\\u1eeb 200 \\u0110\\u1ebfn 500 Skin<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>Anh Em \\u0110\\u1ecdc K\\u0129 Th\\u00f4ng B\\u00e1o , Tr\\u00e1nh Hi\\u1ec3u Nh\\u1ea7m<\\/strong><strong>Ch\\u00fac Anh Em May M\\u1eafn<\\/strong><\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 54353, 1),
(55, 4, 7, 'RANDOM', 'tai-khoan-level-30-chua-rank', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"T\\u00e0i Kho\\u1ea3n Level 30 Ch\\u01b0a Rank\",\"thumb\":\"upload\\/product\\/94c8b670e01b20d1fe3dd7b5940716af.gif\",\"tag\":\"\",\"cash\":\"90000\",\"thele\":\"<p><strong>Acc Lever 30 Ch\\u01b0a Rank<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>\\u0110\\u1ed1i v\\u1edbi Acc TR\\u1eaeNG TH\\u00d4NG TIN<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>-B\\u1eaft bu\\u1ed9c ph\\u1ea3i \\u0111\\u1ed5i M\\u1eadt Kh\\u1ea9u sau khi giao d\\u1ecbch th\\u00e0nh c\\u00f4ng t\\u1ea1i WEB, B\\u00ean SHOP s\\u1ebd kh\\u00f4ng b\\u1ea3o h\\u00e0nh nh\\u1eefng Acc Tr\\u1eafng th\\u00f4ng tin<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>-SHOP ch\\u1ec9 b\\u1ea3o h\\u00e0nh Tr\\u01b0\\u1eddng H\\u1ee3p Acc B\\u1ecb C\\u1ea5m, Tranh Ch\\u1ea5p\\u0110\\u1ed1i v\\u1edbi Acc C\\u00f3 S\\u1ed1 \\u0110i\\u1ec7n Tho\\u1ea1i<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>-Do c\\u01a1 ch\\u1ebf c\\u1ee7a Garena m\\u1ed7i t\\u00e0i kho\\u1ea3n Garena ch\\u1ec9 \\u0111\\u01b0\\u1ee3c thay \\u0111\\u1ed5i th\\u00f4ng tin m\\u1ed7i 30 ng\\u00e0y m\\u1ed9t l\\u1ea7n<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>-N\\u00ean nh\\u1eefng t\\u00e0i kho\\u1ea3n c\\u00f3 S\\u0110T \\u1edf shop ch\\u01b0a t\\u1edbi ng\\u00e0y \\u0111\\u1ed5i S\\u0110T s\\u1ebd \\u0111\\u01b0\\u1ee3c shop b\\u1ea3o h\\u00e0nh trong th\\u01a1i gian ch\\u1edd \\u0111\\u1ed5i S\\u0110T<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>-Kh\\u00e1ch H\\u00e0ng c\\u00f3 quy\\u1ec1n y\\u00eau c\\u1ea7u SHOP h\\u1ed7 tr\\u1ee3 c\\u00e0i th\\u00f4ng tin nh\\u01b0 Gmail, CMND (n\\u1ebfu \\u0111\\u01b0\\u1ee3c)<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>Vui L\\u00f2ng \\u0110\\u1ed5i M\\u1eadt Kh\\u1ea9u Ngay Sau Khi Mua Nick\\u00a0<\\/strong><strong>\\u00a0<\\/strong><a href=\\\\\\\"https:\\/\\/sso.garena.com\\/ui\\/login?app_id=10100&amp;redirect_uri=https%3A%2F%2Faccount.garena.com%2F%3Flocale_name%3DVN&amp;locale=vi-VN\\\\\\\"><strong><u>T\\u1ea1i \\u0110\\u00e2y<\\/u><\\/strong><\\/a><\\/p>\\r\\n\\r\\n<p><strong>Gi\\u00e1 Nick Li\\u00ean Minh \\u0110ang Gi\\u1ea3m 30% Anh EM Mua Ngay N\\u00e0o<\\/strong><\\/p>\",\"data\":[{\"id\":0,\"label\":\"\",\"type\":\"input\",\"name\":\"\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 43543, 1),
(56, 5, 7, 'RANDOM', 'tai-khoan-lmht-rank-cao', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"T\\u00e0i Kho\\u1ea3n LMHT Rank Cao\",\"thumb\":\"upload\\/product\\/a17911020354550b7dde4301f683484c.gif\",\"tag\":\"\\/upload\\/tag\\/tagZYR4.png\",\"cash\":\"150000\",\"thele\":\"<p><strong>- T\\u1ea5t c\\u1ea3 c\\u00e1c t\\u00e0i kho\\u1ea3n tr\\u00ean shop \\u0111\\u1ec1u l\\u00e0 t\\u00e0i kho\\u1ea3n riot. Anh em mua acc vui l\\u00f2ng \\u0111\\u0103ng nh\\u1eadp riot kh\\u00f4ng ph\\u1ea3i \\u0111\\u0103ng nh\\u1eadp garena.<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>!!!\\u00a0CH\\u00da \\u00dd : \\u0110\\u00e2y l\\u00e0 t\\u00e0i kho\\u1ea3n riot game.Mua xong acc b\\u1ea1n vui l\\u00f2ng v\\u00e0o link :\\u00a0<\\/strong><a href=\\\\\\\"https:\\/\\/account.riotgames.com\\/#personal-information\\\\\\\"><strong>https:\\/\\/account.riotgames.com\\/#personal-information<\\/strong><\\/a><strong>\\u00a0<\\/strong><strong>V\\u00e0o link tr\\u00ean : \\u0110\\u1ed5i Mail - \\u0110\\u1ed5i M\\u1eadt Kh\\u1ea9u - B\\u1eadt X\\u00e1c Minh 2 b\\u01b0\\u1edbc<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>- M\\u1ecdi th\\u1eafc m\\u1eafc anh em li\\u00ean h\\u1ec7 fanpage b\\u00ean d\\u01b0\\u1edbi g\\u00f3c ph\\u1ea3i m\\u00e0n h\\u00ecnh! xin c\\u1ea3m \\u01a1n!<\\/strong><\\/p>\\r\\n\\r\\n<p><strong>Gi\\u00e1 Nick Li\\u00ean Minh \\u0110ang Gi\\u1ea3m 50% Anh EM Mua Ngay N\\u00e0o<\\/strong><\\/p>\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 454, 1),
(57, 1, 1, 'ACCOUNT', 'garena-lien-quan-mobile', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"Garena Li\\u00ean Qu\\u00e2n Mobile\",\"thumb\":\"upload\\/product\\/de1c2f15b80660e588307b0605e069b7.jpg\",\"tag\":\"\\/upload\\/tag\\/tag35MO.png\",\"thele\":\"<p>dsadsada<\\/p>\\r\\n\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"},{\"id\":2,\"label\":\"M\\u00f4 t\\u1ea3\",\"type\":\"input\",\"name\":\"mota\",\"value\":\"\",\"show\":\"on\"},{\"id\":3,\"label\":\"Rank\",\"type\":\"select\",\"name\":\"rank\",\"value\":\"\\u0110\\u1ed3ng|B\\u1ea1c|V\\u00e0ng|Kim c\\u01b0\\u01a1ng\",\"show\":\"on\"},{\"id\":4,\"label\":\"T\\u01b0\\u1edbng\",\"type\":\"number\",\"name\":\"tuong\",\"value\":\"\",\"show\":\"on\"}]}', 2000, 1),
(58, 1, 8, 'RANDOM', 'random-fisch-lv-1000', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"Random Fisch Lv 1000\",\"thumb\":\"upload\\/product\\/487fc68927d1d022a2022ae89d1f9a9e.jpg\",\"tag\":\"\",\"cash\":\"500000\",\"thele\":\"<p>Random fisch lv1000+ random nhi\\u1ec1u c\\u1ea7n + coin<\\/p>\\r\\n\\r\\n<p><strong>*L\\u01b0u &yacute;: Check acc v&agrave; th&ecirc;m mail&nbsp;ngay sau khi mua, shop b\\u1ea3o h&agrave;nh 24h k\\u1ec3 t\\u1eeb th\\u1eddi \\u0111i\\u1ec3m mua. Qu&aacute; 24h nick l\\u1ed7i s\\u1ebd kh&ocirc;ng \\u0111\\u01b0\\u1ee3c \\u0111\\u1ed5i tr\\u1ea3<\\/strong><\\/p>\\r\\n\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(59, 2, 8, 'RANDOM', 'random-fisch-lv750-random-nhieu-can-coin', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"Random fisch lv750+ random nhi\\u1ec1u c\\u1ea7n + coin\",\"thumb\":\"upload\\/product\\/34159024b02f8a6a6256a50b3e20a5ab.jpg\",\"tag\":\"\",\"cash\":\"500000\",\"thele\":\"<p>Random fisch lv750+ random nhi\\u1ec1u c\\u1ea7n + coin<\\/p>\\r\\n\\r\\n<p><strong>*L\\u01b0u &yacute;: Check acc v&agrave; th&ecirc;m mail&nbsp;ngay sau khi mua, shop b\\u1ea3o h&agrave;nh 24h k\\u1ec3 t\\u1eeb th\\u1eddi \\u0111i\\u1ec3m mua. Qu&aacute; 24h nick l\\u1ed7i s\\u1ebd kh&ocirc;ng \\u0111\\u01b0\\u1ee3c \\u0111\\u1ed5i tr\\u1ea3<\\/strong><\\/p>\\r\\n\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"input\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(60, 3, 8, 'RANDOM', 'random-fisch-lv300-random-nhieu-can-coin', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"Random fisch lv300+ random nhi\\u1ec1u c\\u1ea7n + coin\",\"thumb\":\"upload\\/product\\/a0b548efa41f77551cb19ada525b7d07.jpg\",\"tag\":\"\",\"cash\":\"100000\",\"thele\":\"<p>Random fisch lv300+ random nhi\\u1ec1u c\\u1ea7n + coin<\\/p>\\r\\n\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"input\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(61, 4, 8, 'RANDOM', 'random-fisch-2m-coins', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"Random fisch 2m Coins\",\"thumb\":\"upload\\/product\\/4a84190264d132f104e3260f197761e9.png\",\"tag\":\"\",\"cash\":\"20000\",\"thele\":\"<p>Random fisch 2m Coins<\\/p>\\r\\n\\r\\n<p><strong>*L\\u01b0u &yacute;: Check acc v&agrave; th&ecirc;m mail&nbsp;ngay sau khi mua, shop b\\u1ea3o h&agrave;nh 24h k\\u1ec3 t\\u1eeb th\\u1eddi \\u0111i\\u1ec3m mua. Qu&aacute; 24h nick l\\u1ed7i s\\u1ebd kh&ocirc;ng \\u0111\\u01b0\\u1ee3c \\u0111\\u1ed5i tr\\u1ea3<\\/strong><\\/p>\\r\\n\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(62, 5, 8, 'RANDOM', 'random-fisch-lv500-random-nhieu-can-coin', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"Random fisch lv500+ random nhi\\u1ec1u c\\u1ea7n + coin\",\"thumb\":\"upload\\/product\\/51ce80ce7113f2be8c13a10cfdfab336.png\",\"tag\":\"\",\"cash\":\"250000\",\"thele\":\"<p>Random fisch lv500+ random nhi\\u1ec1u c\\u1ea7n + coin<\\/p>\\r\\n\\r\\n<p><strong>*L\\u01b0u &yacute;: Check acc v&agrave; th&ecirc;m mail&nbsp;ngay sau khi mua, shop b\\u1ea3o h&agrave;nh 24h k\\u1ec3 t\\u1eeb th\\u1eddi \\u0111i\\u1ec3m mua. Qu&aacute; 24h nick l\\u1ed7i s\\u1ebd kh&ocirc;ng \\u0111\\u01b0\\u1ee3c \\u0111\\u1ed5i tr\\u1ea3<\\/strong><\\/p>\\r\\n\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"off\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"password\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"off\"}]}', 100, 1),
(65, 5, 1, 'RANDOM', '654', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"654\",\"thumb\":\"upload\\/product\\/7b3ac135fb3764cce6ffd85b954a235a.png\",\"tag\":\"\",\"cash\":\"64654\",\"thele\":\"<p>fdsfs<\\/p>\\r\\n\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"on\"}]}', 654, 0),
(66, 1, 1, 'ACCOUNT', 'dsa', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"dsa\",\"thumb\":\"upload\\/product\\/31cc287eca5c21a46979bf90a1a125ab.jpg\",\"tag\":\"\",\"cash\":\"\",\"thele\":\"<p>dfdds<\\/p>\\r\\n\",\"data\":[{\"id\":0,\"label\":\"T\\u00e0i kho\\u1ea3n\",\"type\":\"input\",\"name\":\"taikhoan\",\"value\":\"\",\"show\":\"on\"},{\"id\":1,\"label\":\"M\\u1eadt kh\\u1ea9u\",\"type\":\"input\",\"name\":\"matkhau\",\"value\":\"\",\"show\":\"on\"}]}', 54, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `create_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tag`
--

INSERT INTO `tag` (`id`, `name`, `images`, `create_date`) VALUES
(2, '30%', '/upload/tag/tagKMX1.png', '2024-05-27 10:42:31'),
(3, 'NEW', '/upload/tag/tagZYR4.png', '2024-05-27 10:42:53'),
(4, 'Giáº£m giĂ¡', '/upload/tag/tag35MO.png', '2024-05-27 10:44:46');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_coupons`
--

CREATE TABLE `tbl_coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `used` int(11) NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `createdate` datetime NOT NULL,
  `updatedate` datetime NOT NULL,
  `min` int(11) NOT NULL DEFAULT 1000,
  `max` int(11) NOT NULL DEFAULT 10000000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_coupon_used`
--

CREATE TABLE `tbl_coupon_used` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `trans_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `createdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tb_pusher`
--

CREATE TABLE `tb_pusher` (
  `id` int(10) UNSIGNED NOT NULL,
  `pusher_app_id` varchar(255) NOT NULL,
  `pusher_cluster` varchar(255) NOT NULL,
  `pusher_key` varchar(255) NOT NULL,
  `pusher_secret` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tb_pusher`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tick`
--

CREATE TABLE `tick` (
  `id` int(11) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tick`
--

INSERT INTO `tick` (`id`, `icon`, `name`, `created_at`) VALUES
(1, '/upload/tick/tickV90K.png', 'Tick xanh', '2025/02/23 16:51:01'),
(2, '/upload/tick/tickH25P.png', 'Tick vip', '2025/02/23 16:52:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `top`
--

CREATE TABLE `top` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `method` text DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `top`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `stt` int(11) DEFAULT 0,
  `slug` varchar(200) DEFAULT NULL,
  `detail` longtext DEFAULT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `units`
--

INSERT INTO `units` (`id`, `stt`, `slug`, `detail`, `status`) VALUES
(1, 1, 'vat-pham-game-roblox', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"V\\u1eadt ph\\u1ea9m game roblox\",\"thele\":\"<p>demo<\\/p>\",\"data\":[{\"id\":0,\"label\":\"Nh\\u1eadp T\\u00ean T\\u00e0i Kho\\u1ea3n Ho\\u1eb7c Link C\\u00e0i Gi\\u00e1 Sever\",\"type\":\"text\",\"name\":\"nhaptentaikhoanhoaclinkcaigiasever\",\"option\":null},{\"id\":1,\"label\":\"M\\u1eadt Kh\\u1ea9u Game (T\\u1eaft 2 Step)\",\"type\":\"text\",\"name\":\"matkhaugametat2step\",\"option\":null}]}', 1),
(2, 2, 'vat-pham-game-free-fire', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"V\\u1eadt ph\\u1ea9m game free fire\",\"thele\":\"<p>demo<\\/p>\",\"data\":[{\"id\":0,\"label\":\"ID Game\",\"type\":\"text\",\"name\":\"idgame\",\"option\":null}]}', 1),
(3, 3, 'rut-thoi-vang', '{\"author\":\"SIEUTHICODE.NET\",\"name_product\":\"R\\u00fat th\\u1ecfi v\\u00e0ng\",\"thele\":\"<p>demo ne<\\/p>\",\"data\":[{\"id\":0,\"label\":\"ID nh\\u00e2n v\\u1eadt\",\"type\":\"text\",\"name\":\"idnhanvat\",\"option\":null}]}', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `level` varchar(50) DEFAULT 'member',
  `ctv` int(11) DEFAULT 0,
  `ctv_account` int(11) DEFAULT 0,
  `ctv_boosting` int(11) DEFAULT 0,
  `token` text DEFAULT NULL,
  `ip` text DEFAULT NULL,
  `device` text DEFAULT NULL,
  `otp` text DEFAULT NULL,
  `token_password` text CHARACTER SET utf8mb3 COLLATE utf8mb3_vietnamese_ci DEFAULT NULL,
  `money` int(11) NOT NULL DEFAULT 0,
  `cost` int(11) NOT NULL DEFAULT 0,
  `coin` int(11) NOT NULL DEFAULT 0,
  `total_money` int(11) NOT NULL DEFAULT 0,
  `chietkhau` int(11) NOT NULL DEFAULT 0,
  `chietkhau_banacc` int(11) NOT NULL DEFAULT 0,
  `maxprice` int(11) NOT NULL DEFAULT 0,
  `role_category` text DEFAULT NULL,
  `banned` bigint(20) NOT NULL DEFAULT 0,
  `telegram_id` text DEFAULT NULL,
  `telegram_token` text DEFAULT NULL,
  `status_telegram` int(11) NOT NULL DEFAULT 0,
  `create_date` text DEFAULT NULL,
  `time_session` text DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `provider` text DEFAULT NULL,
  `provider_id` text DEFAULT NULL,
  `secretkey` text DEFAULT NULL,
  `status_2fa` int(11) DEFAULT 0,
  `ref_id` int(11) DEFAULT 0,
  `ref_click` int(11) DEFAULT 0,
  `ref_money` int(11) DEFAULT 0,
  `ref_total_money` int(11) DEFAULT 0,
  `ref_amount` int(11) DEFAULT 0,
  `ref_ck` int(11) DEFAULT 0,
  `debit` int(11) DEFAULT 0,
  `noti_extend` int(11) DEFAULT 0,
  `time_request` int(11) DEFAULT 0,
  `gift` int(11) DEFAULT 0,
  `transacted` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--


INSERT INTO `users` (`id`, `username`, `password`, `level`, `ctv`, `token`, `banned`, `create_date`, `secretkey`, `status_2fa`) VALUES
(1, 'admin', '!', 'superadmin', 0, '', 0, NOW(), '', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `withdraw_ctv`
--

CREATE TABLE `withdraw_ctv` (
  `id` int(11) NOT NULL,
  `trans_id` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bank` text DEFAULT NULL,
  `stk` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `amount` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 0,
  `create_gettime` datetime DEFAULT NULL,
  `update_gettime` datetime DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `withdraw_logs`
--

CREATE TABLE `withdraw_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trans_id` varchar(100) DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `value` varchar(255) NOT NULL,
  `detail` longtext DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `admin_note` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `current_balance` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `withdraw_logs`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `withdraw_ref`
--

CREATE TABLE `withdraw_ref` (
  `id` int(11) NOT NULL,
  `trans_id` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bank` text DEFAULT NULL,
  `stk` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `amount` int(11) DEFAULT 0,
  `status` int(11) DEFAULT 0,
  `create_gettime` datetime DEFAULT NULL,
  `update_gettime` datetime DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Chỉ mục cho bảng `advertisement`
--
ALTER TABLE `advertisement`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Chỉ mục cho bảng `banned_ips`
--
ALTER TABLE `banned_ips`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `boostings`
--
ALTER TABLE `boostings`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_id` (`trans_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `category_items`
--
ALTER TABLE `category_items`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `failed_attempts`
--
ALTER TABLE `failed_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `flash_sales`
--
ALTER TABLE `flash_sales`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `flash_sale_products`
--
ALTER TABLE `flash_sale_products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `history_buy`
--
ALTER TABLE `history_buy`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ip_white`
--
ALTER TABLE `ip_white`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `log_balance`
--
ALTER TABLE `log_balance`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `log_ref`
--
ALTER TABLE `log_ref`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `package_boostings`
--
ALTER TABLE `package_boostings`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `package_units`
--
ALTER TABLE `package_units`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `post_category`
--
ALTER TABLE `post_category`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `spin_quests`
--
ALTER TABLE `spin_quests`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `spin_quest_logs`
--
ALTER TABLE `spin_quest_logs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `subboostings`
--
ALTER TABLE `subboostings`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Chỉ mục cho bảng `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tbl_coupons`
--
ALTER TABLE `tbl_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `tbl_coupon_used`
--
ALTER TABLE `tbl_coupon_used`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tb_pusher`
--
ALTER TABLE `tb_pusher`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tick`
--
ALTER TABLE `tick`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `top`
--
ALTER TABLE `top`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `withdraw_ctv`
--
ALTER TABLE `withdraw_ctv`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `withdraw_logs`
--
ALTER TABLE `withdraw_logs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `withdraw_ref`
--
ALTER TABLE `withdraw_ref`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT cho bảng `advertisement`
--
ALTER TABLE `advertisement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT cho bảng `bank`
--
ALTER TABLE `bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `banned_ips`
--
ALTER TABLE `banned_ips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `boostings`
--
ALTER TABLE `boostings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `category_items`
--
ALTER TABLE `category_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `failed_attempts`
--
ALTER TABLE `failed_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `flash_sales`
--
ALTER TABLE `flash_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `flash_sale_products`
--
ALTER TABLE `flash_sale_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `history_buy`
--
ALTER TABLE `history_buy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `ip_white`
--
ALTER TABLE `ip_white`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=500;

--
-- AUTO_INCREMENT cho bảng `log_balance`
--
ALTER TABLE `log_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT cho bảng `log_ref`
--
ALTER TABLE `log_ref`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `package_boostings`
--
ALTER TABLE `package_boostings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT cho bảng `package_units`
--
ALTER TABLE `package_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `post_category`
--
ALTER TABLE `post_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `spin_quests`
--
ALTER TABLE `spin_quests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `spin_quest_logs`
--
ALTER TABLE `spin_quest_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT cho bảng `subboostings`
--
ALTER TABLE `subboostings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT cho bảng `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tbl_coupons`
--
ALTER TABLE `tbl_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `tbl_coupon_used`
--
ALTER TABLE `tbl_coupon_used`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `tb_pusher`
--
ALTER TABLE `tb_pusher`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tick`
--
ALTER TABLE `tick`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `top`
--
ALTER TABLE `top`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `withdraw_ctv`
--
ALTER TABLE `withdraw_ctv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `withdraw_logs`
--
ALTER TABLE `withdraw_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `withdraw_ref`
--
ALTER TABLE `withdraw_ref`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
