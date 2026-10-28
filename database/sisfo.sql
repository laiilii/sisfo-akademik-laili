-- ============================================================================
-- SISFO AKADEMIK - struktur & data awal database (MySQL / MariaDB)
-- Cara impor : buat database `sisfo`, lalu impor file ini lewat phpMyAdmin
--              (tab Import) atau:  mysql -u root sisfo < database/sisfo.sql
-- Alternatif : php artisan migrate:fresh --seed   (hasilnya sama dengan file ini)
-- Akun admin : admin@admin.com / password   (segera ganti setelah login pertama)
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `sisfo` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sisfo`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `nilais`, `siswas`, `mapels`, `gurus`, `kelas`,
    `failed_jobs`, `job_batches`, `jobs`, `cache_locks`, `cache`,
    `sessions`, `password_reset_tokens`, `users`, `migrations`;

-- ---------------------------------------------------------------- migrations
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------- users
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------- cache & queue
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- data master
CREATE TABLE `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(255) NOT NULL,
  `tingkat` varchar(10) NOT NULL,
  `jurusan` varchar(255) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `siswas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nis` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `alamat` text,
  `no_hp` varchar(255) DEFAULT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `siswas_nis_unique` (`nis`),
  KEY `siswas_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `siswas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `gurus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nip` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `alamat` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gurus_nip_unique` (`nip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `mapels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(255) NOT NULL,
  `nama_mapel` varchar(255) NOT NULL,
  `kkm` tinyint unsigned NOT NULL DEFAULT '75',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mapels_kode_mapel_unique` (`kode_mapel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `nilais` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `mapel_id` bigint unsigned NOT NULL,
  `nilai_tugas` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_uts` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_uas` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(5,2) NOT NULL DEFAULT '0.00',
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nilais_siswa_id_mapel_id_unique` (`siswa_id`,`mapel_id`),
  KEY `nilais_mapel_id_foreign` (`mapel_id`),
  CONSTRAINT `nilais_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nilais_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapels` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- data awal
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2026_09_29_051430_create_kelas_table', 1),
('2026_09_29_063539_create_siswas_table', 1),
('2026_10_01_051505_create_gurus_table', 1),
('2026_10_01_051600_create_mapels_table', 1),
('2026_10_01_051640_create_nilais_table', 1);

-- Password = "password" (bcrypt). Segera ganti setelah login pertama.
INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Administrator SISFO', 'admin@admin.com', '$2y$12$.ED1BxbsM58iCdOWhztuQOGtvZ6Hn4Cq2VDMgiK0abuXyiHHJh4yS', NOW(), NOW());

INSERT INTO `kelas` (`id`, `nama_kelas`, `tingkat`, `jurusan`, `tahun_ajaran`, `created_at`, `updated_at`) VALUES
(1, 'XI RPL 1', 'XI', 'Rekayasa Perangkat Lunak', '2025/2026', NOW(), NOW()),
(2, 'XI RPL 2', 'XI', 'Rekayasa Perangkat Lunak', '2025/2026', NOW(), NOW()),
(3, 'XII TKJ 1', 'XII', 'Teknik Komputer & Jaringan', '2025/2026', NOW(), NOW());

INSERT INTO `siswas` (`id`, `nis`, `nama`, `jenis_kelamin`, `alamat`, `no_hp`, `kelas_id`, `created_at`, `updated_at`) VALUES
(1, '2026001', 'Budi Santoso', 'L', 'Jl. Merdeka No. 12, Bandung', '081234567890', 1, NOW(), NOW()),
(2, '2026002', 'Siti Nurhaliza', 'P', 'Jl. Mawar No. 45, Bandung', '081987654321', 1, NOW(), NOW()),
(3, '2026003', 'Rizky Pratama', 'L', 'Jl. Sukajadi No. 88, Bandung', '082112233445', 2, NOW(), NOW());

INSERT INTO `gurus` (`id`, `nip`, `nama`, `jenis_kelamin`, `email`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(1, '198501152010011001', 'Drs. Ahmad Dahlan, M.Pd', 'L', 'ahmad.dahlan@sekolah.sch.id', '081399887766', 'Jl. Setiabudi No. 100, Bandung', NOW(), NOW()),
(2, '199003202015022002', 'Dewi Sartika, S.Kom', 'P', 'dewi.sartika@sekolah.sch.id', '081544332211', 'Jl. Sunda No. 25, Bandung', NOW(), NOW());

INSERT INTO `mapels` (`id`, `kode_mapel`, `nama_mapel`, `kkm`, `created_at`, `updated_at`) VALUES
(1, 'MP-PWPB', 'Pemrograman Web & Perangkat Bergerak', 75, NOW(), NOW()),
(2, 'MP-PBO', 'Pemrograman Berorientasi Objek', 75, NOW(), NOW()),
(3, 'MP-BASDAT', 'Basis Data', 78, NOW(), NOW());

-- Nilai Akhir = Tugas x 30% + UTS x 30% + UAS x 40%
INSERT INTO `nilais` (`id`, `siswa_id`, `mapel_id`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `nilai_akhir`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 85.00, 80.00, 90.00, 85.50, 'Lulus', NOW(), NOW()),
(2, 1, 3, 90.00, 88.00, 92.00, 90.20, 'Lulus', NOW(), NOW()),
(3, 2, 1, 80.00, 76.00, 100.00, 86.80, 'Lulus', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
