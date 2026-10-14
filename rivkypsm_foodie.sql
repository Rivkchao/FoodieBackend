-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 14 Okt 2026 pada 18.40
-- Versi server: 11.4.9-MariaDB-cll-lve
-- Versi PHP: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `rivkypsm_foodie`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `slug`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'all', 'Semua Menu', 'UtensilsCrossed', '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(2, 'Burgers', 'Burgers', 'Flame', '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(3, 'Pizza', 'Pizza', 'Pizza', '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(4, 'Chicken', 'Chicken', 'Drumstick', '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(5, 'Pasta', 'Pasta', 'Wheat', '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(6, 'Asian', 'Asian', 'Soup', '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(7, 'Drinks', 'Drinks', 'Coffee', '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(8, 'Desserts', 'Desserts', 'IceCream', '2026-09-27 04:05:25', '2026-09-27 04:05:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `foods`
--

CREATE TABLE `foods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `rating` decimal(3,1) NOT NULL DEFAULT 5.0,
  `reviews` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `image` text NOT NULL,
  `ingredients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ingredients`)),
  `prepTime` varchar(255) NOT NULL DEFAULT '15-20 min',
  `calories` varchar(255) NOT NULL DEFAULT '500 kkal',
  `popular` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `foods`
--

INSERT INTO `foods` (`id`, `name`, `category`, `category_id`, `description`, `price`, `rating`, `reviews`, `image`, `ingredients`, `prepTime`, `calories`, `popular`, `created_at`, `updated_at`) VALUES
(1, 'Truffle Mushroom Burger', 'Burgers', 2, 'Juicy Australian Angus beef patty dipadukan dengan tumisan jamur champignon liar, saus black truffle mayo yang creamy, dan keju Swiss meleleh di atas brioche bun panggang bermentega.', 68000.00, 4.9, 248, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800&auto=format&fit=crop&q=80', '[\"Angus Beef Patty\", \"Wild Truffle Sauce\", \"Swiss Melted Cheese\", \"Sautéed Champignon\", \"Brioche Bun\"]', '15-20 min', '650 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(2, 'Crispy BBQ Bacon Burger', 'Burgers', 2, 'Daging sapi panggang tebal dengan lapisan bacon renyah, saus smoky barbecue racikan rahasia, keju cheddar matang, dan onion rings krispi.', 62000.00, 4.8, 189, 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=800&auto=format&fit=crop&q=80', '[\"Prime Beef\", \"Smoked Beef Bacon\", \"Cheddar Cheese\", \"Smoky BBQ Sauce\", \"Crispy Onion Ring\"]', '15-20 min', '710 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(3, 'Double Wagyu Smash Burger', 'Burgers', 2, 'Dua lapis patty wagyu yang di-smash hingga pinggirannya karamel renyah, dilapisi double American cheese, acar segar, dan saus spesial Foodie.', 78000.00, 4.9, 312, 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=800&auto=format&fit=crop&q=80', '[\"Double Wagyu Patty\", \"American Cheese\", \"Secret Foodie Sauce\", \"House Pickles\", \"Butter Brioche\"]', '10-15 min', '820 kkal', 0, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(4, 'Margherita Artisan Pizza', 'Pizza', 3, 'Pizza Neapolitan otentik dengan fermentasi adonan 48 jam, saus tomat San Marzano manis gurih, keju fresh mozzarella fior di latte, dan daun basil segar.', 85000.00, 4.9, 420, 'https://images.unsplash.com/photo-1604382355076-af4b0eb60143?w=800&auto=format&fit=crop&q=80', '[\"San Marzano Tomato\", \"Fresh Mozzarella\", \"Extra Virgin Olive Oil\", \"Fresh Basil Leaf\", \"48h Fermented Crust\"]', '20-25 min', '780 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(5, 'Quattro Formaggi Pizza', 'Pizza', 3, 'Kombinasi surgawi empat keju pilihan dunia: Mozzarella mulur, Gorgonzola aromatik, Parmigiano-Reggiano gurih, dan Ricotta lembut dengan tetesan madu liar.', 98000.00, 4.8, 174, 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=800&auto=format&fit=crop&q=80', '[\"Mozzarella\", \"Gorgonzola Cheese\", \"Parmesan Aged 24m\", \"Creamy Ricotta\", \"Wild Honey Drizzle\"]', '20-25 min', '890 kkal', 0, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(6, 'Pepperoni Supreme Pizza', 'Pizza', 3, 'Dibalut keju mozzarella melimpah dengan irisan premium beef pepperoni yang melengkung garing saat dipanggang di tungku kayu bakar tradisi Italia.', 89000.00, 4.9, 356, 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=800&auto=format&fit=crop&q=80', '[\"Crispy Beef Pepperoni\", \"Italian Herb Sauce\", \"Melted Mozzarella\", \"Oregano\", \"Woodfired Crust\"]', '15-20 min', '840 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(7, 'Crispy Garlic Herb Fried Chicken', 'Chicken', 4, 'Ayam goreng renyah bumbu marinasi 12 rempah rahasia selama 24 jam, digoreng keemasan lalu diglasir minyak bawang putih panggang dan parsley segar.', 48000.00, 4.8, 290, 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?w=800&auto=format&fit=crop&q=80', '[\"Fresh Cut Chicken\", \"Roasted Garlic Butter\", \"12 Secret Spices\", \"Chopped Parsley\", \"Honey Dip Sauce\"]', '15-20 min', '590 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(8, 'Nashville Hot Chicken Wings', 'Chicken', 4, 'Sayap ayam krispi ekstra pedas khas Nashville dengan bumbu cabai cayenne membara, disajikan dengan irisan acar timun dingin dan saus ranch.', 52000.00, 4.7, 145, 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=800&auto=format&fit=crop&q=80', '[\"Chicken Wings\", \"Nashville Cayenne Glaze\", \"Dill Pickles\", \"Buttermilk Ranch Sauce\"]', '15-18 min', '610 kkal', 0, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(9, 'Creamy Truffle Carbonara', 'Pasta', 5, 'Fettuccine al dente dibalut saus kuning telur kaya rasa khas Roma, keju pecorino romano, potongan smoked beef gurih, dan taburan minyak black truffle.', 65000.00, 4.9, 280, 'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=800&auto=format&fit=crop&q=80', '[\"Fettuccine Pasta\", \"Pecorino Romano\", \"Egg Yolk Crema\", \"Smoked Beef\", \"Truffle Oil Drizzle\"]', '15-20 min', '680 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(10, 'Spicy Aglio Olio Smoked Beef', 'Pasta', 5, 'Spaghetti tumis minyak zaitun murni kualitas tinggi dengan aroma bawang putih semerbak, cabai rawit merah, smoked beef iris, dan daun parsley.', 55000.00, 4.7, 198, 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?w=800&auto=format&fit=crop&q=80', '[\"Spaghetti\", \"EV Olive Oil\", \"Golden Garlic\", \"Birdseye Chili\", \"Smoked Beef Ribs\"]', '12-15 min', '540 kkal', 0, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(11, 'Spicy Chicken Chashu Ramen', 'Asian', 6, 'Mie ramen kenyal dalam kaldu ayam kolagen kental yang dimasak 16 jam, dilengkapi irisan chicken chashu bakar empuk, ajitsuke tamago lembut, dan chili oil aromatik.', 58000.00, 4.9, 490, 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=800&auto=format&fit=crop&q=80', '[\"Ramen Noodle\", \"Rich Chicken Broth\", \"Torched Chashu\", \"Ajitsuke Soft Egg\", \"Nori & Scallion\"]', '15-18 min', '640 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(12, 'Beef Teriyaki Donburi', 'Asian', 6, 'Irisan daging sapi US Shortplate empuk ditumis dengan saus teriyaki manis gurih autentik Tokyo, disajikan di atas nasi hangat Jepang, wijen sangrai, dan telur onsen.', 62000.00, 4.8, 215, 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&auto=format&fit=crop&q=80', '[\"US Beef Shortplate\", \"Japanese Rice\", \"Teriyaki Glaze\", \"Onsen Soft Egg\", \"Toasted Sesame\"]', '12-15 min', '610 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(13, 'Salmon Mentai Rice Bowl', 'Asian', 6, 'Nasi pulen Jepang bertabur nori furikake dengan potongan salmon panggang segar dan saus tobiko mentai creamy yang di-blowtorch harum menggoda.', 69000.00, 4.9, 310, 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&auto=format&fit=crop&q=80', '[\"Fresh Salmon\", \"Tobiko Caviar\", \"Secret Mentai Crema\", \"Japanese Furikake Rice\", \"Torched Mayo\"]', '15-18 min', '570 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(14, 'Matcha Cream Oat Latte', 'Drinks', 7, 'Matcha Uji ceremonial grade asal Kyoto dipadukan dengan creamy oat milk bebas laktosa dan sentuhan madu kelapa organik yang menyegarkan.', 34000.00, 4.8, 165, 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=800&auto=format&fit=crop&q=80', '[\"Ceremonial Uji Matcha\", \"Barista Oat Milk\", \"Organic Coconut Nectar\", \"Ice\"]', '5-8 min', '180 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(15, 'Sparkling Peach Yuzu Tea', 'Drinks', 7, 'Seduhan teh melati dingin segar dipadukan dengan sari buah persik matang, citrus yuzu Jepang yang asam segar, dan soda berkarbonasi lembut.', 28000.00, 4.7, 142, 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=800&auto=format&fit=crop&q=80', '[\"Jasmine Cold Brew\", \"Japanese Yuzu Puree\", \"Sweet Peach Slices\", \"Sparkling Soda\", \"Fresh Mint\"]', '5 min', '120 kkal', 0, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(16, 'Caramel Macchiato Cold Brew', 'Drinks', 7, 'Cold brew arabika single origin yang diseduh 18 jam dengan susu vanila kental, es batu, dan drizzle saus karamel mentega gurih manis.', 35000.00, 4.9, 210, 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?w=800&auto=format&fit=crop&q=80', '[\"18h Arabica Cold Brew\", \"Fresh Whole Milk\", \"Salted Butter Caramel\", \"Madagascar Vanilla\"]', '5 min', '210 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(17, 'Molten Chocolate Lava Cake', 'Desserts', 8, 'Cake cokelat Belgia lembut hangat dengan lelehan cokelat pekat lumer di bagian tengah, disajikan dengan es krim vanilla bourbon dan buah berry segar.', 42000.00, 4.9, 320, 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=800&auto=format&fit=crop&q=80', '[\"70% Belgian Dark Chocolate\", \"Bourbon Vanilla Bean Gelato\", \"Organic Butter\", \"Fresh Strawberries\"]', '12-15 min', '490 kkal', 1, '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(18, 'Basque Burnt Cheesecake', 'Desserts', 8, 'Cheesecake panggang gaya San Sebastian dengan permukaan karamel cokelat terbakar yang eksotis, bagian dalam creamy meleleh, dan rasa keju yang intens.', 45000.00, 4.8, 188, 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=800&auto=format&fit=crop&q=80', '[\"Cream Cheese Philladelphia\", \"Heavy Cream\", \"Caramelized Top Layer\", \"Sea Salt Flakes\"]', '5 min', '420 kkal', 0, '2026-09-27 04:05:25', '2026-09-27 04:05:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_27_110243_create_categories_table', 2),
(5, '2026_09_27_110244_create_food_table', 2),
(6, '2026_09_27_110246_create_restaurants_table', 2),
(7, '2026_09_27_110247_create_orders_table', 2),
(8, '2026_09_27_110248_create_order_items_table', 2),
(9, '2026_09_27_120143_add_snap_token_and_payment_type_to_orders_table', 3),
(10, '2026_10_01_184500_create_vouchers_table', 4),
(11, '2026_10_01_184501_add_voucher_to_orders_table', 4),
(12, '2026_10_02_140000_create_newsletter_subscribers_table', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `voucher_code` varchar(255) NOT NULL DEFAULT 'WELCOME10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `customer_address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `delivery_type` varchar(255) NOT NULL DEFAULT 'standard',
  `payment_method` varchar(255) NOT NULL DEFAULT 'gopay',
  `snap_token` varchar(255) DEFAULT NULL,
  `payment_channel` varchar(255) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `delivery_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `platform_fee` decimal(12,2) NOT NULL DEFAULT 2000.00,
  `voucher_code` varchar(255) DEFAULT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `customer_name`, `customer_phone`, `customer_address`, `notes`, `delivery_type`, `payment_method`, `snap_token`, `payment_channel`, `subtotal`, `delivery_fee`, `platform_fee`, `voucher_code`, `discount_amount`, `total`, `status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 'FD-262690', 'Budi Santoso', '081234567890', 'Jl. Sudirman No. 45 Jakarta', 'Pagar hitam', 'standard', 'gopay', NULL, NULL, 136000.00, 0.00, 2000.00, NULL, 0.00, 138000.00, 'processing', NULL, '2026-09-27 04:07:19', '2026-09-27 04:07:19'),
(2, 'FD-674087', 'Budi Santoso', '081234567890', 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan', 'Pagar warna hitam, tolong bel bila sudah sampai.', 'standard', 'gopay', NULL, 'Tunai (COD Lunas)', 68000.00, 12000.00, 2000.00, NULL, 0.00, 82000.00, 'completed', '2026-10-01 23:16:49', '2026-09-27 04:54:03', '2026-10-01 23:16:49'),
(3, 'FD-154714', 'Rps', '081234567890', 'Jl. jalan No. 45, Kebayoran Baru, Jakarta Selatan', 'ty ty', 'express', 'midtrans', NULL, NULL, 136000.00, 8000.00, 2000.00, NULL, 0.00, 146000.00, 'pending', NULL, '2026-09-27 05:16:05', '2026-09-27 05:16:05'),
(4, 'FD-779614', 'Budi Santoso', '081234567890', 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan', 'Pagar warna hitam, tolong bel bila sudah sampai.', 'standard', 'cash', NULL, 'Tunai (COD)', 85000.00, 12000.00, 2000.00, NULL, 0.00, 99000.00, 'pending', NULL, '2026-09-27 05:16:31', '2026-09-27 05:16:31'),
(5, 'FD-775957', 'Budi Santoso', '081234567890', 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan', 'Pagar warna hitam, tolong bel bila sudah sampai.', 'express', 'midtrans', '52f5323f-5136-4f7d-bc4b-90a1cb9ed69f', 'BANK TRANSFER', 48000.00, 20000.00, 2000.00, NULL, 0.00, 70000.00, 'on_delivery', '2026-09-27 05:23:58', '2026-09-27 05:23:14', '2026-10-01 23:15:53'),
(6, 'FD-800307', 'Siti Nurhaliza', '081399887766', 'Jl. Mawar No. 12, Jakarta', 'Jangan pakai sendok plastik', 'standard', 'cash', NULL, 'Tunai (COD)', 68000.00, 12000.00, 2000.00, 'FOODIEHEMAT', 15000.00, 67000.00, 'pending', NULL, '2026-10-01 05:19:04', '2026-10-01 05:19:04'),
(7, 'FD-113178', 'Budi Santoso', '081234567890', 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan', 'Pagar warna hitam, tolong bel bila sudah sampai.', 'express', 'cash', NULL, 'Tunai (COD)', 89000.00, 20000.00, 2000.00, 'DISKON20', 17800.00, 93200.00, 'processing', NULL, '2026-10-01 05:21:49', '2026-10-01 23:17:08'),
(8, 'FD-789401', 'Budi Santoso', '081234567890', 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan', 'Pagar warna hitam, tolong bel bila sudah sampai.', 'standard', 'cash', NULL, 'Tunai (COD)', 68000.00, 12000.00, 2000.00, 'GRATISONGKIR', 12000.00, 70000.00, 'completed', '2026-10-01 23:05:57', '2026-10-01 23:04:54', '2026-10-01 23:06:04'),
(9, 'FD-504994', 'Budi Santoso', '081234567890', 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan', 'Pagar warna hitam, tolong bel bila sudah sampai.', 'express', 'cash', NULL, 'Tunai (COD)', 85000.00, 20000.00, 2000.00, 'SUPERDEAL50', 40000.00, 67000.00, 'pending', NULL, '2026-10-01 23:23:16', '2026-10-01 23:23:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `food_id` bigint(20) UNSIGNED DEFAULT NULL,
  `food_name` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `food_id`, `food_name`, `price`, `qty`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Truffle Mushroom Burger', 68000.00, 2, 136000.00, '2026-09-27 04:07:19', '2026-09-27 04:07:19'),
(2, 2, 1, 'Truffle Mushroom Burger', 68000.00, 1, 68000.00, '2026-09-27 04:54:03', '2026-09-27 04:54:03'),
(3, 3, 1, 'Truffle Mushroom Burger', 68000.00, 2, 136000.00, '2026-09-27 05:16:05', '2026-09-27 05:16:05'),
(4, 4, 4, 'Margherita Artisan Pizza', 85000.00, 1, 85000.00, '2026-09-27 05:16:31', '2026-09-27 05:16:31'),
(5, 5, 7, 'Crispy Garlic Herb Fried Chicken', 48000.00, 1, 48000.00, '2026-09-27 05:23:14', '2026-09-27 05:23:14'),
(6, 6, 1, 'Truffle Mushroom Burger', 68000.00, 1, 68000.00, '2026-10-01 05:19:04', '2026-10-01 05:19:04'),
(7, 7, 6, 'Pepperoni Supreme Pizza', 89000.00, 1, 89000.00, '2026-10-01 05:21:49', '2026-10-01 05:21:49'),
(8, 8, 1, 'Truffle Mushroom Burger', 68000.00, 1, 68000.00, '2026-10-01 23:04:54', '2026-10-01 23:04:54'),
(9, 9, 4, 'Margherita Artisan Pizza', 85000.00, 1, 85000.00, '2026-10-01 23:23:16', '2026-10-01 23:23:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `restaurants`
--

CREATE TABLE `restaurants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `cuisine` varchar(255) NOT NULL,
  `rating` decimal(3,1) NOT NULL DEFAULT 5.0,
  `distance` varchar(255) NOT NULL DEFAULT '1.0 km',
  `duration` varchar(255) NOT NULL DEFAULT '20 min',
  `image` text NOT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `cuisine`, `rating`, `distance`, `duration`, `image`, `badge`, `created_at`, `updated_at`) VALUES
(1, 'Burger Garage Artisanal', 'Burgers & American', 4.9, '1.2 km', '20 min', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=700&auto=format&fit=crop&q=80', 'Trending #1', '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(2, 'Bella Napoli Woodfired Pizza', 'Authentic Italian', 4.8, '2.4 km', '25 min', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=700&auto=format&fit=crop&q=80', 'Chef Choice', '2026-09-27 04:05:25', '2026-09-27 04:05:25'),
(3, 'Tokyo Ramen Street', 'Japanese Ramen & Don', 4.9, '1.8 km', '18 min', 'https://images.unsplash.com/photo-1552611052-33e04de081de?w=700&auto=format&fit=crop&q=80', 'Top Rated', '2026-09-27 04:05:25', '2026-09-27 04:05:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('003pulFzvUV6wZDE4kTyq9TtxatSJbNhhwm4HOfk', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidEloTTBmb3pIVkRTaWs3dmJzN0t1eXJOTzNVN0dHSGF4V1pXUW5WMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTc6Imh0dHA6Ly9mb29kaWUtYmFja2VuZC5hdXRvL2FkbWluL29yZGVycz9zdGF0dXM9cHJvY2Vzc2luZyI7czo1OiJyb3V0ZSI7czoxODoiYWRtaW4ub3JkZXJzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1790857402),
('8wbGxpVhhk8Gbl4aWLEaHK8BLtZgXxJh5IKDRUPT', NULL, '103.121.102.126', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieElrcTRsT1NTQUNSN2NzbEhrQVJnWlM3OXNEVGlXNUxKOXJFOU9xUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vZm9vZGllYmFja2VuZC5yaXZreXBzLm15LmlkL2FkbWluL2xvZ2luIjtzOjU6InJvdXRlIjtzOjExOiJhZG1pbi5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790931003),
('AaXOECVjmoRMWG32xXtl5iueoe6yuLTgqjEOj8LK', NULL, '127.0.0.1', 'curl/8.21.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiczFHZW41Yk05NzlFRXlGM2xRM0U0NFBEWkhuRWpRcG1KS255ckFIMyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MToiaHR0cDovLzEyNy4wLjAuMS9mb29kaWUtYmFja2VuZC9wdWJsaWMvYWRtaW4vb3JkZXJzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjEvZm9vZGllLWJhY2tlbmQvcHVibGljL2FkbWluL29yZGVycyI7czo1OiJyb3V0ZSI7czoxODoiYWRtaW4ub3JkZXJzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790857357),
('eHElrC6JoQbpTzlMGuS7lkMD8i5QddzbJRG1Y6XP', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRXhJdlpZajBVdUlaS2xDaWdqNjhhTG5XSlRsWEpXZEQ4TllBRmh4eCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9mb29kaWUtYmFja2VuZC5hdXRvL2FkbWluL21lbnUiO3M6NToicm91dGUiO3M6MTY6ImFkbWluLm1lbnUuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1790929078),
('EOCgQAwoeSlSmDqsBPY0xBhSmShhpibYyY3TmQt1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0xxNjZnOGlkWERZOE9GSnd0ZkNpZW0xSEpjc3hiSVo1QnNSQTNlSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vZm9vZGllLWJhY2tlbmQuYXV0byI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790843618),
('eRSmxCpUGDoxmi2kupNLJpuwgZvf3yURvp57bt97', NULL, '127.0.0.1', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDdlZm5iRmd5QjZGeHhKdUNsU05idk5NbWM4UkpadHVxVk9weE44aSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjM6Imh0dHA6Ly8xMjcuMC4wLjEvZm9vZGllLWJhY2tlbmQvcHVibGljL29yZGVycy9GRC04MDAzMDcvcmVjZWlwdCI7czo1OiJyb3V0ZSI7czoxNDoib3JkZXJzLnJlY2VpcHQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790857175),
('GcHANzTA4UnmMVhyZziiT0ujJLk81phWA48oH3iz', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiejJUdjZtNDRSMjkxSVNXSEYxOHI4UDVCYXBrd3loTmh0bU5iWm1IOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjM6Imh0dHA6Ly9sb2NhbGhvc3QvZm9vZGllLWJhY2tlbmQvcHVibGljL29yZGVycy9GRC03NzU5NTcvcmVjZWlwdCI7czo1OiJyb3V0ZSI7czoxNDoib3JkZXJzLnJlY2VpcHQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790511841),
('IlQEYACW68iR1lQaQuUiHabAkNTNN2G5m8CTrWWj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGZxNUJZbjRGeHRYR0JWdk9tTUdpZjFpMlJuaW1QUGJRTFNyTHFnSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9mb29kaWUtYmFja2VuZC5hdXRvIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790843618),
('QcGwSiIpprOLWp6nZ6dKF0cwxTLi6shBIie50Yzz', NULL, '127.0.0.1', 'curl/8.21.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidXVIbkVNdU9tNTBHODJQQXhmd2R4UXJhU0JCVm1HMUM1allMVTQyUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790856805),
('VX2VWea3PdFahVAZp2k4LIZjHJu16Jbx9bmdD9qz', NULL, '103.121.102.126', 'curl/8.21.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlhmWUtOQjdEU2dsc09CT281aGFVM29HU1dTa1U1TWtmNVV5aDJhRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly9mb29kaWViYWNrZW5kLnJpdmt5cHMubXkuaWQvYWRtaW4vbG9naW4iO3M6NToicm91dGUiO3M6MTE6ImFkbWluLmxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790930750),
('X7cKFlel79rA1lL3eDsAX6Um8FSMLTOKFxgWCnMI', NULL, '103.121.102.126', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTXBpWllPMFJkZVFmbGRtUWQ2cnprTXp3ZkhRSXRHV1B2WVpieWxNZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHBzOi8vZm9vZGllYmFja2VuZC5yaXZreXBzLm15LmlkL2FkbWluL2xvZ2luIjtzOjU6InJvdXRlIjtzOjExOiJhZG1pbi5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6NDg6Imh0dHBzOi8vZm9vZGllYmFja2VuZC5yaXZreXBzLm15LmlkL2FkbWluL29yZGVycyI7fX0=', 1790931220);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Foodie', 'admin@foodie.test', '2026-10-01 04:43:29', '$2y$12$QitKrTsKGwIjI7f5lStBd.ormz4.In.yH9cLZMXwGLawCNqeZ5HCu', 'wOt3Jj0SsltFDTaq8ObDW7qvHTxlcEnpnxYbCk9alg9gHk7hjVlRlxqd4jnr', '2026-10-01 04:43:30', '2026-10-01 04:43:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `discount_type` enum('fixed','percentage') NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(12,2) NOT NULL,
  `min_spend` decimal(12,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(12,2) DEFAULT NULL,
  `quota` int(11) NOT NULL DEFAULT 100,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `valid_until` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `name`, `discount_type`, `discount_value`, `min_spend`, `max_discount`, `quota`, `used_count`, `valid_until`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'FOODIEHEMAT', 'Diskon Spesial Foodie Hemat', 'fixed', 15000.00, 40000.00, NULL, 200, 6, '2027-01-01', 1, '2026-10-01 04:43:30', '2026-10-01 05:19:04'),
(2, 'DISKON20', 'Diskon Kilat 20% All Menu', 'percentage', 20.00, 50000.00, 25000.00, 150, 13, '2026-12-01', 1, '2026-10-01 04:43:30', '2026-10-01 05:21:49'),
(3, 'GRATISONGKIR', 'Potongan Ongkir Rp 12.000', 'fixed', 12000.00, 30000.00, NULL, 300, 29, '2027-04-01', 1, '2026-10-01 04:43:30', '2026-10-01 23:04:53'),
(4, 'SUPERDEAL50', 'Super Deal Pesta Makanan 50%', 'percentage', 50.00, 70000.00, 40000.00, 50, 19, '2026-11-01', 1, '2026-10-01 04:43:30', '2026-10-01 23:23:16'),
(5, 'WELCOME10', 'Diskon Pelanggan Baru 10%', 'percentage', 10.00, 25000.00, 15000.00, 100, 0, '2027-10-02', 1, '2026-10-01 23:59:43', '2026-10-01 23:59:43');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foods_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `newsletter_subscribers_email_unique` (`email`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_id_unique` (`order_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_food_id_foreign` (`food_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `foods`
--
ALTER TABLE `foods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `foods`
--
ALTER TABLE `foods`
  ADD CONSTRAINT `foods_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_food_id_foreign` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
