-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: nanya_events
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bank_accounts`
--

DROP TABLE IF EXISTS `bank_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bank_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_holder` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_accounts`
--

LOCK TABLES `bank_accounts` WRITE;
/*!40000 ALTER TABLE `bank_accounts` DISABLE KEYS */;
INSERT INTO `bank_accounts` VALUES (1,'BCA','8830-1928-33','Nanyang Zhi Hui School',1,'2026-08-14 20:39:53','2026-08-14 20:39:53'),(2,'Mandiri','137-00-99201-882','Nanyang Zhi Hui School',1,'2026-08-14 20:39:53','2026-08-14 20:39:53');
/*!40000 ALTER TABLE `bank_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('nanya-events-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0','i:1;',1786767470),('nanya-events-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer','i:1786767470;',1786767470),('nanya-events-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}',1787102402);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_sessions`
--

DROP TABLE IF EXISTS `event_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `session_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_sessions_event_id_foreign` (`event_id`),
  CONSTRAINT `event_sessions_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_sessions`
--

LOCK TABLES `event_sessions` WRITE;
/*!40000 ALTER TABLE `event_sessions` DISABLE KEYS */;
INSERT INTO `event_sessions` VALUES (1,1,'2026-09-26','15:00:00','17:30:00','2026-08-14 20:39:53','2026-08-14 20:39:53');
/*!40000 ALTER TABLE `event_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `venue_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `poster_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `event_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pertunjukan',
  `payment_verification_timeout_hours` int NOT NULL DEFAULT '24',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'coming_soon',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  KEY `events_venue_id_foreign` (`venue_id`),
  KEY `events_created_by_foreign` (`created_by`),
  CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `events_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,1,1,'Shine in Harmony','shine-in-harmony','event-posters/shine-in-harmony-seat-poster.jpg','<p>Celebrating a cherished tradition and honoring the rich cultural tapestry of the Mid-Autumn Festival through music, dance, and verse.</p><p><strong>Penyelenggara:</strong> Nanyang Zhi Hui Modern Indonesian School</p><p><strong>Syarat &amp; Ketentuan:</strong></p><ul><li><p>Penonton wajib hadir 30 menit sebelum sesi pertunjukan dimulai.</p></li><li><p>E-tiket ber-QR code wajib ditunjukkan kepada petugas pintu masuk venue.</p></li><li><p>Dilarang membawa makanan dan minuman dari luar ke dalam Auditorium.</p></li></ul>','Pertunjukan',24,'registration','2026-08-14 20:39:53','2026-08-17 20:47:36');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_15_021354_create_permission_tables',1),(5,'2026_08_15_021500_add_google_auth_to_users_table',1),(6,'2026_08_15_022000_create_venues_table',1),(7,'2026_08_15_022001_create_seat_categories_table',1),(8,'2026_08_15_022002_create_seat_masters_table',1),(9,'2026_08_15_023000_create_events_table',1),(10,'2026_08_15_023001_create_event_sessions_table',1),(11,'2026_08_15_023002_create_seat_availabilities_table',1),(12,'2026_08_15_023003_create_bank_accounts_table',1),(13,'2026_08_15_024000_create_orders_table',1),(14,'2026_08_15_024001_create_payments_table',1),(15,'2026_08_15_025000_create_tickets_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3),(3,'App\\Models\\User',5);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `event_session_id` bigint unsigned NOT NULL,
  `bank_account_id` bigint unsigned DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `unique_code` int NOT NULL DEFAULT '0',
  `final_amount` decimal(12,2) NOT NULL,
  `status` enum('pending_payment','waiting_verification','paid','cancelled','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `expired_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_event_session_id_foreign` (`event_session_id`),
  KEY `orders_bank_account_id_foreign` (`bank_account_id`),
  CONSTRAINT `orders_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_event_session_id_foreign` FOREIGN KEY (`event_session_id`) REFERENCES `event_sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'NYA-20260818-FZPFHN',3,1,2,1125000.00,0,1125000.00,'paid','2026-08-18 18:45:21','2026-08-17 18:45:21','2026-08-17 18:50:36'),(2,'NYA-20260818-ZGOTVH',3,1,1,550000.00,0,550000.00,'paid','2026-08-18 19:16:17','2026-08-17 19:16:17','2026-08-17 19:17:27'),(3,'NYA-20260818-Z90DJP',3,1,2,1100000.00,0,1100000.00,'rejected','2026-08-18 19:32:46','2026-08-17 19:32:46','2026-08-17 19:35:39'),(4,'NYA-20260818-1WUNDP',5,1,1,1100000.00,0,1100000.00,'paid','2026-08-18 20:48:10','2026-08-17 20:48:10','2026-08-17 20:49:40'),(5,'NYA-20260818-DDT8T7',5,1,1,3625000.00,0,3625000.00,'paid','2026-08-18 21:32:27','2026-08-17 21:32:27','2026-08-17 21:33:47'),(7,'NYA-VVIP-20260818-NJPJUI',2,1,1,0.00,0,0.00,'paid','2027-08-17 22:05:58','2026-08-17 22:05:58','2026-08-17 22:05:58');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('admin@nanyaevents.com','$2y$12$BEcVvhkMQ8tEVNVOR2M07eO86dcB0JVnfiNzCOo39LshZru6Otxye','2026-08-17 18:14:22'),('itprogrammer@nanyangzh.sch.id','$2y$12$1wdaH3KYb6DM50nUzqogx.9pvNTqVE6OMCE50hQUn3nXSrPEQcjXW','2026-08-17 18:14:40');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `proof_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_bank` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_amount` decimal(12,2) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL,
  `verified_by` bigint unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_foreign` (`order_id`),
  KEY `payments_verified_by_foreign` (`verified_by`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'payment-proofs/lR64QuxANXdoBWJnL4ibE2B4JbEvYViryRSNyaBq.jpg','bca','budi',1125000.00,'2026-08-17 18:47:38',2,'2026-08-17 18:50:36',NULL,'2026-08-17 18:47:38','2026-08-17 18:50:36'),(2,2,'payment-proofs/zyY4TKwxq14U8z80S8Pt8bGEylXTnb1wH50YdgWD.jpg','bca','budi',550000.00,'2026-08-17 19:16:40',2,'2026-08-17 19:17:27',NULL,'2026-08-17 19:16:40','2026-08-17 19:17:27'),(3,4,'payment-proofs/GKWuJm2k6TkVFC7vXG5XTkZuHUha01R4gNcgDbnQ.jpg','bca','testing',1100000.00,'2026-08-17 20:48:43',2,'2026-08-17 20:49:40',NULL,'2026-08-17 20:48:43','2026-08-17 20:49:40'),(4,5,'payment-proofs/dnhQn8zebErzC7UuY2HiLBaFFUivdy6o7aU6AiSh.jpg','bca','testing 12',3625000.00,'2026-08-17 21:32:38',2,'2026-08-17 21:33:47',NULL,'2026-08-17 21:32:38','2026-08-17 21:33:47'),(5,7,'vvip_complimentary','VVIP','Panitia Event Demo',0.00,'2026-08-17 22:05:58',2,'2026-08-17 22:05:58',NULL,'2026-08-17 22:05:58','2026-08-17 22:05:58');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','web','2026-08-14 20:39:53','2026-08-14 20:39:53'),(2,'Admin','web','2026-08-14 20:39:53','2026-08-14 20:39:53'),(3,'User','web','2026-08-14 20:39:53','2026-08-14 20:39:53');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seat_availabilities`
--

DROP TABLE IF EXISTS `seat_availabilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seat_availabilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_session_id` bigint unsigned NOT NULL,
  `seat_master_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `status` enum('available','locked','sold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `locked_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seat_availabilities_event_session_id_seat_master_id_unique` (`event_session_id`,`seat_master_id`),
  KEY `seat_availabilities_seat_master_id_foreign` (`seat_master_id`),
  KEY `seat_availabilities_order_id_index` (`order_id`),
  CONSTRAINT `seat_availabilities_event_session_id_foreign` FOREIGN KEY (`event_session_id`) REFERENCES `event_sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seat_availabilities_seat_master_id_foreign` FOREIGN KEY (`seat_master_id`) REFERENCES `seat_masters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seat_availabilities`
--

LOCK TABLES `seat_availabilities` WRITE;
/*!40000 ALTER TABLE `seat_availabilities` DISABLE KEYS */;
INSERT INTO `seat_availabilities` VALUES (555,1,647,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(556,1,648,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(557,1,649,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(558,1,650,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(559,1,651,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(560,1,652,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(561,1,653,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(562,1,654,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(563,1,655,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(564,1,656,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(565,1,657,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(566,1,658,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(567,1,659,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(568,1,660,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(569,1,661,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(570,1,662,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(571,1,663,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(572,1,664,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(573,1,665,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(574,1,666,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(575,1,667,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(576,1,668,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(577,1,669,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(578,1,670,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(579,1,671,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(580,1,672,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(581,1,673,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(582,1,674,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(583,1,675,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(584,1,676,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(585,1,677,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(586,1,678,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(587,1,679,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(588,1,680,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(589,1,681,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(590,1,682,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(591,1,683,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(592,1,684,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(593,1,685,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(594,1,686,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(595,1,687,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(596,1,688,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(597,1,689,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(598,1,690,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(599,1,691,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(600,1,692,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(601,1,693,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(602,1,694,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(603,1,695,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(604,1,696,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(605,1,697,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(606,1,698,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(607,1,699,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(608,1,700,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(609,1,701,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(610,1,702,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(611,1,703,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(612,1,704,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(613,1,705,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(614,1,706,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(615,1,707,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(616,1,708,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(617,1,709,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(618,1,710,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(619,1,711,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:21:46'),(620,1,712,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(621,1,713,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(622,1,714,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(623,1,715,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(624,1,716,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(625,1,717,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(626,1,718,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(627,1,719,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(628,1,720,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(629,1,721,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(630,1,722,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(631,1,723,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(632,1,724,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(633,1,725,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(634,1,726,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(635,1,727,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(636,1,728,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(637,1,729,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(638,1,730,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(639,1,731,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(640,1,732,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(641,1,733,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(642,1,734,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(643,1,735,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(644,1,736,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(645,1,737,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(646,1,738,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(647,1,739,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(648,1,740,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(649,1,741,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(650,1,742,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(651,1,743,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(652,1,744,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(653,1,745,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(654,1,746,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(655,1,747,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(656,1,748,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(657,1,749,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(658,1,750,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(659,1,751,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(660,1,752,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(661,1,753,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(662,1,754,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(663,1,755,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(664,1,756,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(665,1,757,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(666,1,758,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(667,1,759,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(668,1,760,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(669,1,761,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(670,1,762,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(671,1,763,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(672,1,764,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(673,1,765,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(674,1,766,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(675,1,767,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(676,1,768,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(677,1,769,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(678,1,770,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(679,1,771,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(680,1,772,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(681,1,773,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(682,1,774,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(683,1,775,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(684,1,776,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(685,1,777,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(686,1,778,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(687,1,779,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(688,1,780,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(689,1,781,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(690,1,782,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(691,1,783,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(692,1,784,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(693,1,785,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(694,1,786,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(695,1,787,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(696,1,788,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(697,1,789,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(698,1,790,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(699,1,791,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(700,1,792,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(701,1,793,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(702,1,794,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(703,1,795,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(704,1,796,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(705,1,797,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(706,1,798,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(707,1,799,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(708,1,800,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(709,1,801,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(710,1,802,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(711,1,803,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(712,1,804,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(713,1,805,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(714,1,806,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(715,1,807,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(716,1,808,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(717,1,809,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(718,1,810,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(719,1,811,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(720,1,812,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(721,1,813,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(722,1,814,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(723,1,815,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(724,1,816,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(725,1,817,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(726,1,818,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(727,1,819,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(728,1,820,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(729,1,821,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(730,1,822,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(731,1,823,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(732,1,824,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(733,1,825,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(734,1,826,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(735,1,827,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(736,1,828,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(737,1,829,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(738,1,830,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(739,1,831,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(740,1,832,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(741,1,833,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(742,1,834,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(743,1,835,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(744,1,836,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(745,1,837,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(746,1,838,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(747,1,839,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(748,1,840,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(749,1,841,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(750,1,842,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(751,1,843,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(752,1,844,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(753,1,845,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(754,1,846,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(755,1,847,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(756,1,848,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(757,1,849,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(758,1,850,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(759,1,851,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(760,1,852,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(761,1,853,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(762,1,854,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(763,1,855,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(764,1,856,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(765,1,857,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(766,1,858,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(767,1,859,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(768,1,860,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(769,1,861,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(770,1,862,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(771,1,863,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(772,1,864,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(773,1,865,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(774,1,866,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(775,1,867,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(776,1,868,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(777,1,869,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(778,1,870,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(779,1,871,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(780,1,872,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(781,1,873,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(782,1,874,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(783,1,875,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(784,1,876,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(785,1,877,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(786,1,878,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(787,1,879,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(788,1,880,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(789,1,881,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(790,1,882,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(791,1,883,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(792,1,884,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(793,1,885,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(794,1,886,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(795,1,887,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(796,1,888,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(797,1,889,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(798,1,890,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(799,1,891,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(800,1,892,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(801,1,893,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(802,1,894,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(803,1,895,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(804,1,896,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(805,1,897,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(806,1,898,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(807,1,899,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(808,1,900,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(809,1,901,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(810,1,902,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(811,1,903,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(812,1,904,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(813,1,905,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(814,1,906,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(815,1,907,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(816,1,908,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(817,1,909,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(818,1,910,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(819,1,911,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(820,1,912,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(821,1,913,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(822,1,914,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(823,1,915,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(824,1,916,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(825,1,917,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(826,1,918,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(827,1,919,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(828,1,920,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(829,1,921,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(830,1,922,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(831,1,923,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(832,1,924,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(833,1,925,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(834,1,926,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(835,1,927,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(836,1,928,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(837,1,929,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(838,1,930,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(839,1,931,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(840,1,932,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(841,1,933,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(842,1,934,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(843,1,935,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(844,1,936,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(845,1,937,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(846,1,938,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(847,1,939,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(848,1,940,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(849,1,941,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(850,1,942,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(851,1,943,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(852,1,944,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(853,1,945,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(854,1,946,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(855,1,947,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(856,1,948,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(857,1,949,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(858,1,950,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(859,1,951,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(860,1,952,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(861,1,953,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(862,1,954,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(863,1,955,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(864,1,956,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(865,1,957,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(866,1,958,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(867,1,959,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(868,1,960,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(869,1,961,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(870,1,962,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(871,1,963,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(872,1,964,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(873,1,965,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(874,1,966,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(875,1,967,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(876,1,968,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(877,1,969,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(878,1,970,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(879,1,971,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(880,1,972,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(881,1,973,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(882,1,974,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(883,1,975,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(884,1,976,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(885,1,977,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(886,1,978,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(887,1,979,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(888,1,980,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(889,1,981,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(890,1,982,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(891,1,983,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(892,1,984,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(893,1,985,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(894,1,986,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(895,1,987,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(896,1,988,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(897,1,989,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(898,1,990,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(899,1,991,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(900,1,992,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(901,1,993,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(902,1,994,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(903,1,995,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(904,1,996,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(905,1,997,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(906,1,998,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(907,1,999,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(908,1,1000,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(909,1,1001,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(910,1,1002,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(911,1,1003,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(912,1,1004,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(913,1,1005,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(914,1,1006,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(915,1,1007,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(916,1,1008,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(917,1,1009,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(918,1,1010,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(919,1,1011,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(920,1,1012,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(921,1,1013,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(922,1,1014,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(923,1,1015,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(924,1,1016,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(925,1,1017,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(926,1,1018,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(927,1,1019,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(928,1,1020,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(929,1,1021,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(930,1,1022,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(931,1,1023,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(932,1,1024,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(933,1,1025,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(934,1,1026,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(935,1,1027,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(936,1,1028,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(937,1,1029,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(938,1,1030,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(939,1,1031,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(940,1,1032,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(941,1,1033,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(942,1,1034,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(943,1,1035,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(944,1,1036,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(945,1,1037,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(946,1,1038,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(947,1,1039,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(948,1,1040,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(949,1,1041,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(950,1,1042,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(951,1,1043,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(952,1,1044,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(953,1,1045,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(954,1,1046,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(955,1,1047,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(956,1,1048,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(957,1,1049,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(958,1,1050,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(959,1,1051,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(960,1,1052,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(961,1,1053,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(962,1,1054,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(963,1,1055,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(964,1,1056,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(965,1,1057,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(966,1,1058,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(967,1,1059,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(968,1,1060,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(969,1,1061,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(970,1,1062,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(971,1,1063,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(972,1,1064,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(973,1,1065,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(974,1,1066,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(975,1,1067,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(976,1,1068,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(977,1,1069,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(978,1,1070,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(979,1,1071,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(980,1,1072,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(981,1,1073,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(982,1,1074,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(983,1,1075,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(984,1,1076,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(985,1,1077,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(986,1,1078,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(987,1,1079,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(988,1,1080,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(989,1,1081,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(990,1,1082,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(991,1,1083,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(992,1,1084,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(993,1,1085,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(994,1,1086,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(995,1,1087,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(996,1,1088,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(997,1,1089,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(998,1,1090,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(999,1,1091,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1000,1,1092,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1001,1,1093,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1002,1,1094,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1003,1,1095,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1004,1,1096,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1005,1,1097,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1006,1,1098,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1007,1,1099,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1008,1,1100,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1009,1,1101,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1010,1,1102,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1011,1,1103,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1012,1,1104,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1013,1,1105,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1014,1,1106,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1015,1,1107,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1016,1,1108,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1017,1,1109,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1018,1,1110,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1019,1,1111,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1020,1,1112,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1021,1,1113,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1022,1,1114,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1023,1,1115,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1024,1,1116,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1025,1,1117,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1026,1,1118,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1027,1,1119,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1028,1,1120,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1029,1,1121,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1030,1,1122,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1031,1,1123,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1032,1,1124,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1033,1,1125,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1034,1,1126,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1035,1,1127,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1036,1,1128,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1037,1,1129,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1038,1,1130,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1039,1,1131,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1040,1,1132,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1041,1,1133,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1042,1,1134,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1043,1,1135,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1044,1,1136,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1045,1,1137,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1046,1,1138,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1047,1,1139,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1048,1,1140,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1049,1,1141,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1050,1,1142,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1051,1,1143,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1052,1,1144,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1053,1,1145,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1054,1,1146,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1055,1,1147,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1056,1,1148,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1057,1,1149,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1058,1,1150,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1059,1,1151,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1060,1,1152,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1061,1,1153,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1062,1,1154,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1063,1,1155,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1064,1,1156,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1065,1,1157,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1066,1,1158,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1067,1,1159,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1068,1,1160,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1069,1,1161,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1070,1,1162,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1071,1,1163,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1072,1,1164,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1073,1,1165,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1074,1,1166,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1075,1,1167,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1076,1,1168,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1077,1,1169,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1078,1,1170,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1079,1,1171,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1080,1,1172,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1081,1,1173,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1082,1,1174,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1083,1,1175,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1084,1,1176,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1085,1,1177,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1086,1,1178,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1087,1,1179,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1088,1,1180,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1089,1,1181,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1090,1,1182,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1091,1,1183,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1092,1,1184,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1093,1,1185,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1094,1,1186,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1095,1,1187,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1096,1,1188,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1097,1,1189,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1098,1,1190,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1099,1,1191,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1100,1,1192,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1101,1,1193,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1102,1,1194,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1103,1,1195,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1104,1,1196,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1105,1,1197,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1106,1,1198,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1107,1,1199,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1108,1,1200,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1109,1,1201,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1110,1,1202,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1111,1,1203,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1112,1,1204,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1113,1,1205,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1114,1,1206,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1115,1,1207,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1116,1,1208,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1117,1,1209,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1118,1,1210,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1119,1,1211,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1120,1,1212,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1121,1,1213,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1122,1,1214,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1123,1,1215,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1124,1,1216,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1125,1,1217,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1126,1,1218,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1127,1,1219,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1128,1,1220,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1129,1,1221,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1130,1,1222,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1131,1,1223,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1132,1,1224,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1133,1,1225,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1134,1,1226,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1135,1,1227,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1136,1,1228,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1137,1,1229,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1138,1,1230,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1139,1,1231,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1140,1,1232,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1141,1,1233,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1142,1,1234,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1143,1,1235,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1144,1,1236,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1145,1,1237,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1146,1,1238,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1147,1,1239,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1148,1,1240,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1149,1,1241,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1150,1,1242,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1151,1,1243,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1152,1,1244,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1153,1,1245,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1154,1,1246,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1155,1,1247,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1156,1,1248,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1157,1,1249,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1158,1,1250,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1159,1,1251,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1160,1,1252,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1161,1,1253,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1162,1,1254,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1163,1,1255,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1164,1,1256,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1165,1,1257,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1166,1,1258,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1167,1,1259,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1168,1,1260,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1169,1,1261,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1170,1,1262,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1171,1,1263,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1172,1,1264,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1173,1,1265,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1174,1,1266,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1175,1,1267,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1176,1,1268,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1177,1,1269,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1178,1,1270,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1179,1,1271,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1180,1,1272,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1181,1,1273,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1182,1,1274,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1183,1,1275,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1184,1,1276,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1185,1,1277,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1186,1,1278,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1187,1,1279,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1188,1,1280,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1189,1,1281,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1190,1,1282,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1191,1,1283,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1192,1,1284,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1193,1,1285,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1194,1,1286,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1195,1,1287,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1196,1,1288,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1197,1,1289,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1198,1,1290,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1199,1,1291,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27'),(1200,1,1292,NULL,'available',NULL,'2026-08-17 18:25:08','2026-08-17 22:10:27');
/*!40000 ALTER TABLE `seat_availabilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seat_categories`
--

DROP TABLE IF EXISTS `seat_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seat_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `venue_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3B82F6',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seat_categories_venue_id_foreign` (`venue_id`),
  CONSTRAINT `seat_categories_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seat_categories`
--

LOCK TABLES `seat_categories` WRITE;
/*!40000 ALTER TABLE `seat_categories` DISABLE KEYS */;
INSERT INTO `seat_categories` VALUES (4,1,'DIAMOND','#00D4E6',275000.00,'2026-08-17 18:25:02','2026-08-17 18:30:17'),(5,1,'GOLD','#FFD000',225000.00,'2026-08-17 18:25:02','2026-08-17 18:30:17'),(6,1,'PINK','#FF539B',150000.00,'2026-08-17 18:25:02','2026-08-17 18:30:17');
/*!40000 ALTER TABLE `seat_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seat_masters`
--

DROP TABLE IF EXISTS `seat_masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seat_masters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `venue_id` bigint unsigned NOT NULL,
  `seat_category_id` bigint unsigned DEFAULT NULL,
  `seat_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `row_num` int NOT NULL,
  `col_num` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seat_masters_venue_id_seat_code_unique` (`venue_id`,`seat_code`),
  KEY `seat_masters_seat_category_id_foreign` (`seat_category_id`),
  CONSTRAINT `seat_masters_seat_category_id_foreign` FOREIGN KEY (`seat_category_id`) REFERENCES `seat_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `seat_masters_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1293 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seat_masters`
--

LOCK TABLES `seat_masters` WRITE;
/*!40000 ALTER TABLE `seat_masters` DISABLE KEYS */;
INSERT INTO `seat_masters` VALUES (647,1,5,'A-1',1,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(648,1,5,'A-2',1,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(649,1,5,'A-3',1,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(650,1,5,'A-4',1,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(651,1,5,'A-5',1,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(652,1,5,'A-6',1,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(653,1,5,'A-7',1,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(654,1,5,'A-8',1,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(655,1,5,'GAP-1-9',1,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(656,1,5,'GAP-1-10',1,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(657,1,4,'GAP-1-11',1,11,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(658,1,4,'GAP-1-12',1,12,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(659,1,4,'GAP-1-13',1,13,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(660,1,4,'GAP-1-14',1,14,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(661,1,4,'GAP-1-15',1,15,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(662,1,4,'GAP-1-16',1,16,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(663,1,4,'GAP-1-17',1,17,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(664,1,4,'GAP-1-18',1,18,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(665,1,4,'GAP-1-19',1,19,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(666,1,4,'GAP-1-20',1,20,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(667,1,4,'GAP-1-21',1,21,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(668,1,4,'GAP-1-22',1,22,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(669,1,4,'GAP-1-23',1,23,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(670,1,4,'GAP-1-24',1,24,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(671,1,4,'GAP-1-25',1,25,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(672,1,4,'GAP-1-26',1,26,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(673,1,4,'GAP-1-27',1,27,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(674,1,4,'GAP-1-28',1,28,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(675,1,5,'GAP-1-29',1,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(676,1,5,'GAP-1-30',1,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(677,1,5,'A-31',1,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(678,1,5,'A-32',1,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(679,1,5,'A-33',1,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(680,1,5,'A-34',1,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(681,1,5,'A-35',1,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(682,1,5,'A-36',1,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(683,1,5,'A-37',1,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(684,1,5,'A-38',1,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(685,1,5,'B-1',2,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(686,1,5,'B-2',2,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(687,1,5,'B-3',2,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(688,1,5,'B-4',2,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(689,1,5,'B-5',2,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(690,1,5,'B-6',2,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(691,1,5,'B-7',2,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(692,1,5,'B-8',2,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(693,1,5,'GAP-2-9',2,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(694,1,5,'GAP-2-10',2,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(695,1,4,'GAP-2-11',2,11,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(696,1,4,'B-12',2,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(697,1,4,'B-13',2,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(698,1,4,'B-14',2,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(699,1,4,'B-15',2,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(700,1,4,'B-16',2,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(701,1,4,'B-17',2,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(702,1,4,'B-18',2,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(703,1,4,'B-19',2,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(704,1,4,'B-20',2,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(705,1,4,'B-21',2,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(706,1,4,'B-22',2,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(707,1,4,'B-23',2,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(708,1,4,'B-24',2,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(709,1,4,'B-25',2,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(710,1,4,'B-26',2,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(711,1,4,'B-27',2,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(712,1,4,'GAP-2-28',2,28,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(713,1,5,'GAP-2-29',2,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(714,1,5,'GAP-2-30',2,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(715,1,5,'B-31',2,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(716,1,5,'B-32',2,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(717,1,5,'B-33',2,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(718,1,5,'B-34',2,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(719,1,5,'B-35',2,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(720,1,5,'B-36',2,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(721,1,5,'B-37',2,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(722,1,5,'B-38',2,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(723,1,5,'C-1',3,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(724,1,5,'C-2',3,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(725,1,5,'C-3',3,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(726,1,5,'C-4',3,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(727,1,5,'C-5',3,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(728,1,5,'C-6',3,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(729,1,6,'C-7',3,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(730,1,6,'C-8',3,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(731,1,5,'GAP-3-9',3,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(732,1,5,'GAP-3-10',3,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(733,1,4,'C-11',3,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(734,1,4,'C-12',3,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(735,1,4,'C-13',3,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(736,1,4,'C-14',3,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(737,1,4,'C-15',3,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(738,1,4,'C-16',3,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(739,1,4,'C-17',3,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(740,1,4,'C-18',3,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(741,1,4,'C-19',3,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(742,1,4,'C-20',3,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(743,1,4,'C-21',3,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(744,1,4,'C-22',3,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(745,1,4,'C-23',3,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(746,1,4,'C-24',3,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(747,1,4,'C-25',3,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(748,1,4,'C-26',3,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(749,1,4,'C-27',3,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(750,1,4,'C-28',3,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(751,1,5,'GAP-3-29',3,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(752,1,5,'GAP-3-30',3,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(753,1,6,'C-31',3,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(754,1,6,'C-32',3,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(755,1,5,'C-33',3,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(756,1,5,'C-34',3,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(757,1,5,'C-35',3,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(758,1,5,'C-36',3,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(759,1,5,'C-37',3,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(760,1,5,'C-38',3,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(761,1,5,'D-1',4,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(762,1,5,'D-2',4,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(763,1,5,'D-3',4,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(764,1,5,'D-4',4,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(765,1,6,'D-5',4,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(766,1,6,'D-6',4,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(767,1,6,'D-7',4,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(768,1,6,'D-8',4,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(769,1,5,'GAP-4-9',4,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(770,1,5,'GAP-4-10',4,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(771,1,4,'D-11',4,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(772,1,4,'D-12',4,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(773,1,4,'D-13',4,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(774,1,4,'D-14',4,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(775,1,4,'D-15',4,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(776,1,4,'D-16',4,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(777,1,4,'D-17',4,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(778,1,4,'D-18',4,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(779,1,4,'D-19',4,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(780,1,4,'D-20',4,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(781,1,4,'D-21',4,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(782,1,4,'D-22',4,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(783,1,4,'D-23',4,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(784,1,4,'D-24',4,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(785,1,4,'D-25',4,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(786,1,4,'D-26',4,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(787,1,4,'D-27',4,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(788,1,4,'D-28',4,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(789,1,5,'GAP-4-29',4,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(790,1,5,'GAP-4-30',4,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(791,1,6,'D-31',4,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(792,1,6,'D-32',4,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(793,1,6,'D-33',4,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(794,1,6,'D-34',4,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(795,1,5,'D-35',4,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(796,1,5,'D-36',4,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(797,1,5,'D-37',4,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(798,1,5,'D-38',4,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(799,1,5,'E-1',5,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(800,1,5,'E-2',5,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(801,1,5,'E-3',5,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(802,1,6,'E-4',5,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(803,1,6,'E-5',5,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(804,1,6,'E-6',5,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(805,1,6,'E-7',5,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(806,1,5,'E-8',5,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(807,1,5,'GAP-5-9',5,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(808,1,5,'GAP-5-10',5,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(809,1,4,'E-11',5,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(810,1,4,'E-12',5,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(811,1,4,'E-13',5,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(812,1,4,'E-14',5,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(813,1,4,'E-15',5,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(814,1,4,'E-16',5,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(815,1,4,'E-17',5,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(816,1,4,'E-18',5,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(817,1,4,'E-19',5,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(818,1,4,'E-20',5,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(819,1,4,'E-21',5,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(820,1,4,'E-22',5,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(821,1,4,'E-23',5,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(822,1,4,'E-24',5,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(823,1,4,'E-25',5,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(824,1,4,'E-26',5,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(825,1,4,'E-27',5,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(826,1,4,'E-28',5,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(827,1,5,'GAP-5-29',5,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(828,1,5,'GAP-5-30',5,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(829,1,5,'E-31',5,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(830,1,6,'E-32',5,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(831,1,6,'E-33',5,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(832,1,6,'E-34',5,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(833,1,6,'E-35',5,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(834,1,5,'E-36',5,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(835,1,5,'E-37',5,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(836,1,5,'E-38',5,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(837,1,5,'F-1',6,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(838,1,5,'F-2',6,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(839,1,6,'F-3',6,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(840,1,6,'F-4',6,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(841,1,6,'F-5',6,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(842,1,6,'F-6',6,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(843,1,5,'F-7',6,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(844,1,5,'F-8',6,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(845,1,5,'GAP-6-9',6,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(846,1,5,'GAP-6-10',6,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(847,1,4,'F-11',6,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(848,1,4,'F-12',6,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(849,1,4,'F-13',6,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(850,1,4,'F-14',6,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(851,1,4,'F-15',6,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(852,1,4,'F-16',6,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(853,1,4,'F-17',6,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(854,1,4,'F-18',6,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(855,1,4,'F-19',6,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(856,1,4,'F-20',6,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(857,1,4,'F-21',6,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(858,1,4,'F-22',6,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(859,1,4,'F-23',6,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(860,1,4,'F-24',6,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(861,1,4,'F-25',6,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(862,1,4,'F-26',6,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(863,1,4,'F-27',6,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(864,1,4,'F-28',6,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(865,1,5,'GAP-6-29',6,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(866,1,5,'GAP-6-30',6,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(867,1,5,'F-31',6,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(868,1,5,'F-32',6,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(869,1,6,'F-33',6,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(870,1,6,'F-34',6,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(871,1,6,'F-35',6,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(872,1,6,'F-36',6,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(873,1,5,'F-37',6,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(874,1,5,'F-38',6,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(875,1,6,'G-1',7,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(876,1,6,'G-2',7,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(877,1,6,'G-3',7,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(878,1,6,'G-4',7,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(879,1,6,'G-5',7,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(880,1,5,'G-6',7,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(881,1,5,'G-7',7,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(882,1,5,'G-8',7,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(883,1,5,'GAP-7-9',7,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(884,1,5,'GAP-7-10',7,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(885,1,4,'G-11',7,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(886,1,4,'G-12',7,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(887,1,4,'G-13',7,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(888,1,4,'G-14',7,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(889,1,4,'G-15',7,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(890,1,4,'G-16',7,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(891,1,4,'G-17',7,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(892,1,4,'G-18',7,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(893,1,4,'G-19',7,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(894,1,4,'G-20',7,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(895,1,4,'G-21',7,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(896,1,4,'G-22',7,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(897,1,4,'G-23',7,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(898,1,4,'G-24',7,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(899,1,4,'G-25',7,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(900,1,4,'G-26',7,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(901,1,4,'G-27',7,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(902,1,4,'G-28',7,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(903,1,5,'GAP-7-29',7,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(904,1,5,'GAP-7-30',7,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(905,1,5,'G-31',7,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(906,1,5,'G-32',7,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(907,1,5,'G-33',7,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(908,1,6,'G-34',7,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(909,1,6,'G-35',7,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(910,1,6,'G-36',7,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(911,1,6,'G-37',7,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(912,1,6,'G-38',7,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(913,1,6,'H-1',8,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(914,1,6,'H-2',8,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(915,1,6,'H-3',8,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(916,1,6,'H-4',8,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(917,1,5,'H-5',8,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(918,1,5,'H-6',8,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(919,1,5,'H-7',8,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(920,1,5,'H-8',8,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(921,1,5,'GAP-8-9',8,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(922,1,5,'GAP-8-10',8,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(923,1,4,'H-11',8,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(924,1,4,'H-12',8,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(925,1,4,'H-13',8,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(926,1,4,'H-14',8,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(927,1,4,'H-15',8,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(928,1,4,'H-16',8,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(929,1,4,'H-17',8,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(930,1,4,'H-18',8,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(931,1,4,'H-19',8,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(932,1,4,'H-20',8,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(933,1,4,'H-21',8,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(934,1,4,'H-22',8,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(935,1,4,'H-23',8,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(936,1,4,'H-24',8,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(937,1,4,'H-25',8,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(938,1,4,'H-26',8,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(939,1,4,'H-27',8,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(940,1,4,'H-28',8,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(941,1,5,'GAP-8-29',8,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(942,1,5,'GAP-8-30',8,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(943,1,5,'H-31',8,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(944,1,5,'H-32',8,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(945,1,5,'H-33',8,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(946,1,5,'H-34',8,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(947,1,6,'H-35',8,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(948,1,6,'H-36',8,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(949,1,6,'H-37',8,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(950,1,6,'H-38',8,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(951,1,5,'J-1',9,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(952,1,5,'J-2',9,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(953,1,5,'J-3',9,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(954,1,5,'J-4',9,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(955,1,5,'J-5',9,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(956,1,5,'J-6',9,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(957,1,5,'J-7',9,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(958,1,5,'J-8',9,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(959,1,5,'GAP-9-9',9,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(960,1,5,'GAP-9-10',9,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(961,1,4,'J-11',9,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(962,1,4,'J-12',9,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(963,1,4,'J-13',9,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(964,1,4,'J-14',9,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(965,1,4,'J-15',9,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(966,1,4,'J-16',9,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(967,1,4,'J-17',9,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(968,1,4,'J-18',9,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(969,1,4,'J-19',9,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(970,1,4,'J-20',9,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(971,1,4,'J-21',9,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(972,1,4,'J-22',9,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(973,1,4,'J-23',9,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(974,1,4,'J-24',9,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(975,1,4,'J-25',9,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(976,1,4,'J-26',9,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(977,1,4,'J-27',9,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(978,1,4,'J-28',9,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(979,1,5,'GAP-9-29',9,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(980,1,5,'GAP-9-30',9,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(981,1,5,'J-31',9,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(982,1,5,'J-32',9,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(983,1,5,'J-33',9,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(984,1,5,'J-34',9,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(985,1,5,'J-35',9,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(986,1,5,'J-36',9,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(987,1,5,'J-37',9,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(988,1,5,'J-38',9,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(989,1,5,'K-1',10,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(990,1,5,'K-2',10,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(991,1,5,'K-3',10,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(992,1,5,'K-4',10,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(993,1,5,'K-5',10,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(994,1,5,'K-6',10,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(995,1,5,'K-7',10,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(996,1,5,'K-8',10,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(997,1,5,'GAP-10-9',10,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(998,1,5,'GAP-10-10',10,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(999,1,4,'GAP-10-11',10,11,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1000,1,4,'K-12',10,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1001,1,4,'K-13',10,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1002,1,4,'K-14',10,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1003,1,4,'K-15',10,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1004,1,4,'K-16',10,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1005,1,4,'K-17',10,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1006,1,4,'K-18',10,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1007,1,4,'K-19',10,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1008,1,4,'K-20',10,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1009,1,4,'K-21',10,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1010,1,4,'K-22',10,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1011,1,4,'K-23',10,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1012,1,4,'K-24',10,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1013,1,4,'K-25',10,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1014,1,4,'K-26',10,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1015,1,4,'K-27',10,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1016,1,4,'GAP-10-28',10,28,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1017,1,5,'GAP-10-29',10,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1018,1,5,'GAP-10-30',10,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1019,1,5,'K-31',10,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1020,1,5,'K-32',10,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1021,1,5,'K-33',10,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1022,1,5,'K-34',10,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1023,1,5,'K-35',10,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1024,1,5,'K-36',10,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1025,1,5,'K-37',10,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1026,1,5,'K-38',10,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1027,1,5,'L-1',11,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1028,1,5,'L-2',11,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1029,1,5,'L-3',11,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1030,1,5,'L-4',11,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1031,1,5,'L-5',11,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1032,1,5,'L-6',11,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1033,1,5,'L-7',11,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1034,1,5,'L-8',11,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1035,1,5,'GAP-11-9',11,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1036,1,5,'GAP-11-10',11,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1037,1,4,'L-11',11,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1038,1,4,'L-12',11,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1039,1,4,'L-13',11,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1040,1,4,'L-14',11,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1041,1,4,'L-15',11,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1042,1,4,'L-16',11,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1043,1,4,'L-17',11,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1044,1,4,'L-18',11,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1045,1,4,'L-19',11,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1046,1,4,'L-20',11,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1047,1,4,'L-21',11,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1048,1,4,'L-22',11,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1049,1,4,'L-23',11,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1050,1,4,'L-24',11,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1051,1,4,'L-25',11,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1052,1,4,'L-26',11,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1053,1,4,'L-27',11,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1054,1,4,'L-28',11,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1055,1,5,'GAP-11-29',11,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1056,1,5,'GAP-11-30',11,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1057,1,5,'L-31',11,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1058,1,5,'L-32',11,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1059,1,5,'L-33',11,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1060,1,5,'L-34',11,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1061,1,5,'L-35',11,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1062,1,5,'L-36',11,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1063,1,5,'L-37',11,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1064,1,5,'L-38',11,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1065,1,5,'M-1',12,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1066,1,5,'M-2',12,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1067,1,5,'M-3',12,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1068,1,5,'M-4',12,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1069,1,5,'M-5',12,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1070,1,5,'M-6',12,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1071,1,5,'M-7',12,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1072,1,5,'M-8',12,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1073,1,5,'GAP-12-9',12,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1074,1,5,'GAP-12-10',12,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1075,1,4,'M-11',12,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1076,1,4,'M-12',12,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1077,1,4,'M-13',12,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1078,1,4,'M-14',12,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1079,1,4,'M-15',12,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1080,1,4,'M-16',12,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1081,1,4,'M-17',12,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1082,1,4,'M-18',12,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1083,1,4,'M-19',12,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1084,1,4,'M-20',12,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1085,1,4,'M-21',12,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1086,1,4,'M-22',12,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1087,1,4,'M-23',12,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1088,1,4,'M-24',12,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1089,1,4,'M-25',12,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1090,1,4,'M-26',12,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1091,1,4,'M-27',12,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1092,1,4,'M-28',12,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1093,1,5,'GAP-12-29',12,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1094,1,5,'GAP-12-30',12,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1095,1,5,'M-31',12,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1096,1,5,'M-32',12,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1097,1,5,'M-33',12,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1098,1,5,'M-34',12,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1099,1,5,'M-35',12,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1100,1,5,'M-36',12,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1101,1,5,'M-37',12,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1102,1,5,'M-38',12,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1103,1,5,'N-1',13,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1104,1,5,'N-2',13,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1105,1,5,'N-3',13,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1106,1,5,'N-4',13,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1107,1,5,'N-5',13,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1108,1,5,'N-6',13,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1109,1,5,'N-7',13,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1110,1,5,'N-8',13,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1111,1,5,'GAP-13-9',13,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1112,1,5,'GAP-13-10',13,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1113,1,4,'N-11',13,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1114,1,4,'N-12',13,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1115,1,4,'N-13',13,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1116,1,4,'N-14',13,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1117,1,4,'N-15',13,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1118,1,4,'N-16',13,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1119,1,4,'N-17',13,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1120,1,4,'N-18',13,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1121,1,4,'N-19',13,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1122,1,4,'N-20',13,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1123,1,4,'N-21',13,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1124,1,4,'N-22',13,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1125,1,4,'N-23',13,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1126,1,4,'N-24',13,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1127,1,4,'N-25',13,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1128,1,4,'N-26',13,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1129,1,4,'N-27',13,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1130,1,4,'N-28',13,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1131,1,5,'GAP-13-29',13,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1132,1,5,'GAP-13-30',13,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1133,1,5,'N-31',13,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1134,1,5,'N-32',13,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1135,1,5,'N-33',13,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1136,1,5,'N-34',13,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1137,1,5,'N-35',13,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1138,1,5,'N-36',13,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1139,1,5,'N-37',13,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1140,1,5,'N-38',13,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1141,1,5,'P-1',14,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1142,1,5,'P-2',14,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1143,1,5,'P-3',14,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1144,1,5,'P-4',14,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1145,1,5,'P-5',14,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1146,1,5,'P-6',14,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1147,1,5,'P-7',14,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1148,1,5,'P-8',14,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1149,1,5,'GAP-14-9',14,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1150,1,5,'GAP-14-10',14,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1151,1,4,'P-11',14,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1152,1,4,'P-12',14,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1153,1,4,'P-13',14,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1154,1,4,'P-14',14,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1155,1,4,'P-15',14,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1156,1,4,'P-16',14,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1157,1,4,'P-17',14,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1158,1,4,'P-18',14,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1159,1,4,'P-19',14,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1160,1,4,'P-20',14,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1161,1,4,'P-21',14,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1162,1,4,'P-22',14,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1163,1,4,'P-23',14,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1164,1,4,'P-24',14,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1165,1,4,'P-25',14,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1166,1,4,'P-26',14,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1167,1,4,'P-27',14,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1168,1,4,'P-28',14,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1169,1,5,'GAP-14-29',14,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1170,1,5,'GAP-14-30',14,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1171,1,5,'P-31',14,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1172,1,5,'P-32',14,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1173,1,5,'P-33',14,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1174,1,5,'P-34',14,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1175,1,5,'P-35',14,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1176,1,5,'P-36',14,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1177,1,5,'P-37',14,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1178,1,5,'P-38',14,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1179,1,5,'R-1',15,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1180,1,5,'R-2',15,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1181,1,5,'R-3',15,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1182,1,5,'R-4',15,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1183,1,5,'R-5',15,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1184,1,5,'R-6',15,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1185,1,5,'R-7',15,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1186,1,5,'R-8',15,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1187,1,5,'GAP-15-9',15,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1188,1,5,'GAP-15-10',15,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1189,1,4,'R-11',15,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1190,1,4,'R-12',15,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1191,1,4,'R-13',15,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1192,1,4,'R-14',15,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1193,1,4,'R-15',15,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1194,1,4,'R-16',15,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1195,1,4,'R-17',15,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1196,1,4,'R-18',15,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1197,1,4,'R-19',15,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1198,1,4,'R-20',15,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1199,1,4,'R-21',15,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1200,1,4,'R-22',15,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1201,1,4,'R-23',15,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1202,1,4,'R-24',15,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1203,1,4,'R-25',15,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1204,1,4,'R-26',15,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1205,1,4,'R-27',15,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1206,1,4,'R-28',15,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1207,1,5,'GAP-15-29',15,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1208,1,5,'GAP-15-30',15,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1209,1,5,'R-31',15,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1210,1,5,'R-32',15,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1211,1,5,'R-33',15,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1212,1,5,'R-34',15,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1213,1,5,'R-35',15,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1214,1,5,'R-36',15,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1215,1,5,'R-37',15,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1216,1,5,'R-38',15,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1217,1,5,'S-1',16,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1218,1,5,'S-2',16,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1219,1,5,'S-3',16,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1220,1,5,'S-4',16,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1221,1,5,'S-5',16,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1222,1,5,'S-6',16,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1223,1,5,'S-7',16,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1224,1,5,'S-8',16,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1225,1,5,'GAP-16-9',16,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1226,1,5,'GAP-16-10',16,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1227,1,4,'GAP-16-11',16,11,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1228,1,4,'S-12',16,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1229,1,4,'S-13',16,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1230,1,4,'S-14',16,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1231,1,4,'S-15',16,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1232,1,4,'S-16',16,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1233,1,4,'S-17',16,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1234,1,4,'S-18',16,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1235,1,4,'S-19',16,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1236,1,4,'S-20',16,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1237,1,4,'S-21',16,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1238,1,4,'S-22',16,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1239,1,4,'S-23',16,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1240,1,4,'S-24',16,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1241,1,4,'S-25',16,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1242,1,4,'S-26',16,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1243,1,4,'S-27',16,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1244,1,4,'GAP-16-28',16,28,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1245,1,5,'GAP-16-29',16,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1246,1,5,'GAP-16-30',16,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1247,1,5,'S-31',16,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1248,1,5,'S-32',16,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1249,1,5,'S-33',16,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1250,1,5,'S-34',16,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1251,1,5,'S-35',16,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1252,1,5,'S-36',16,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1253,1,5,'S-37',16,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1254,1,5,'S-38',16,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1255,1,5,'T-1',17,1,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1256,1,5,'T-2',17,2,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1257,1,5,'T-3',17,3,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1258,1,5,'T-4',17,4,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1259,1,5,'T-5',17,5,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1260,1,5,'T-6',17,6,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1261,1,5,'T-7',17,7,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1262,1,5,'T-8',17,8,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1263,1,5,'GAP-17-9',17,9,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1264,1,5,'GAP-17-10',17,10,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1265,1,4,'T-11',17,11,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1266,1,4,'T-12',17,12,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1267,1,4,'T-13',17,13,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1268,1,4,'T-14',17,14,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1269,1,4,'T-15',17,15,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1270,1,4,'T-16',17,16,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1271,1,4,'T-17',17,17,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1272,1,4,'T-18',17,18,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1273,1,4,'T-19',17,19,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1274,1,4,'T-20',17,20,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1275,1,4,'T-21',17,21,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1276,1,4,'T-22',17,22,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1277,1,4,'T-23',17,23,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1278,1,4,'T-24',17,24,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1279,1,4,'T-25',17,25,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1280,1,4,'T-26',17,26,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1281,1,4,'T-27',17,27,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1282,1,4,'T-28',17,28,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1283,1,5,'GAP-17-29',17,29,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1284,1,5,'GAP-17-30',17,30,0,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1285,1,5,'T-31',17,31,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1286,1,5,'T-32',17,32,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1287,1,5,'T-33',17,33,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1288,1,5,'T-34',17,34,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1289,1,5,'T-35',17,35,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1290,1,5,'T-36',17,36,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1291,1,5,'T-37',17,37,1,'2026-08-17 18:25:02','2026-08-17 18:25:02'),(1292,1,5,'T-38',17,38,1,'2026-08-17 18:25:02','2026-08-17 18:25:02');
/*!40000 ALTER TABLE `seat_masters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('3UJAyH1nKvkzIEIa0rNy4RGJVPjJcPlUg6TKzWlS',5,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYks0cllCN0xydlhUN0tBSVlMc3VDVExwdXBVN1pXdDFnNmlacm9uNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9teS10aWNrZXRzL1RLVC0yMDI2MDgxOC1JVzZTR0YiO3M6NToicm91dGUiO3M6MTU6Im15LXRpY2tldHMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7czoyMToiY2hlY2tvdXRfdG90YWxfYW1vdW50IjtkOjM2MjUwMDA7fQ==',1787028373),('6IHwMxc1w10wtZtSJHOmMIEyiRn937UiEbBK8VO8',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiMEFoc2FVb3dPeEd1SVROZGhwQkpLdExPSjZpWXd0UlJWcHFBZWhWdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zY2FuLXRpY2tldCI7czo1OiJyb3V0ZSI7czoxNzoic2Nhbi10aWNrZXQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiI5OTQ0Y2I0Y2Q1NjUxZTE1M2U3NWI4N2ZlNTQxOThlZDk5OTRjMmZiMjdmOWZlZjFmMWE3NmMwMzI4MGVkMTczIjtzOjY6InRhYmxlcyI7YTo0OntzOjQwOiI5MzAxNGQ4ZWJiYjgwMzU5NmY1ZGVlYmZkMTliNTUwOF9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoib3JkZXJfY29kZSI7czo1OiJsYWJlbCI7czoxMDoiS29kZSBPcmRlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToidXNlci5uYW1lIjtzOjU6ImxhYmVsIjtzOjg6IlBlbm9udG9uIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyNDoiZXZlbnRTZXNzaW9uLmV2ZW50LnRpdGxlIjtzOjU6ImxhYmVsIjtzOjU6IkV2ZW50IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEyOiJmaW5hbF9hbW91bnQiO3M6NToibGFiZWwiO3M6MTE6IlRvdGFsIEJheWFyIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyMToiYmFua0FjY291bnQuYmFua19uYW1lIjtzOjU6ImxhYmVsIjtzOjQ6IkJhbmsiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Njoic3RhdHVzIjtzOjU6ImxhYmVsIjtzOjY6IlN0YXR1cyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6OToiVGdsIFBlc2FuIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MDt9fXM6NDA6IjU0Y2FmMjdmYzNhZGQ5MjhhN2VkNGY4YjgxM2VlNjg2X2NvbHVtbnMiO2E6Nzp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJwb3N0ZXJfcGF0aCI7czo1OiJsYWJlbCI7czo2OiJQb3N0ZXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6InRpdGxlIjtzOjU6ImxhYmVsIjtzOjExOiJKdWR1bCBFdmVudCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InZlbnVlLm5hbWUiO3M6NToibGFiZWwiO3M6NToiVmVudWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjIwOiJldmVudF9zZXNzaW9uc19jb3VudCI7czo1OiJsYWJlbCI7czoxMDoiVG90YWwgU2VzaSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MzQ6InBheW1lbnRfdmVyaWZpY2F0aW9uX3RpbWVvdXRfaG91cnMiO3M6NToibGFiZWwiO3M6MTM6IlRpbWVvdXQgQmF5YXIiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6InN0YXR1cyI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo2O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjY6IkRpYnVhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiI0ZWY1MGViODY2Y2RiMmQ1ZDE2YzFiMTRhOTQyODkwMl9jb2x1bW5zIjthOjQ6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoic2Vzc2lvbl9kYXRlIjtzOjU6ImxhYmVsIjtzOjEyOiJUYW5nZ2FsIFNlc2kiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJzdGFydF90aW1lIjtzOjU6ImxhYmVsIjtzOjk6IkphbSBNdWxhaSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6ODoiZW5kX3RpbWUiO3M6NToibGFiZWwiO3M6MTE6IkphbSBTZWxlc2FpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoyNToic2VhdF9hdmFpbGFiaWxpdGllc19jb3VudCI7czo1OiJsYWJlbCI7czoyMjoiU3RhdHVzIEt1cnNpIEdlbmVyYXRlZCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319czo0MDoiZTUxNjgyNDQyYjg5ZjQ1YjQ4YWVhZWEzODc2YzdkY2VfY29sdW1ucyI7YTo2OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czoxMDoiTmFtYSBWZW51ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InRvdGFsX3Jvd3MiO3M6NToibGFiZWwiO3M6NToiQmFyaXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJ0b3RhbF9jb2x1bW5zIjtzOjU6ImxhYmVsIjtzOjU6IktvbG9tIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxODoic2VhdF9tYXN0ZXJzX2NvdW50IjtzOjU6ImxhYmVsIjtzOjIwOiJUb3RhbCBLdXJzaSBQaHlzaWNhbCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaXNfYWN0aXZlIjtzOjU6ImxhYmVsIjtzOjEyOiJTdGF0dXMgQWt0aWYiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjY6IkRpYnVhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX19czo4OiJmaWxhbWVudCI7YTowOnt9fQ==',1787036055),('qLC9h8I1EBPS9eBNoNFXqjsXxHtjxGa0tf0HqU9M',3,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOTc1a212T1BVaFdRb2FBdzdMWWRkc0FLbGFNOEpnbjNxdFZmMGkzMiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ldmVudHMvc2hpbmUtaW4taGFybW9ueSI7czo1OiJyb3V0ZSI7czoxMToiZXZlbnRzLnNob3ciO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MjE6ImNoZWNrb3V0X3RvdGFsX2Ftb3VudCI7ZDoxMTAwMDAwO30=',1787030529);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_code_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `seat_availability_id` bigint unsigned NOT NULL,
  `status` enum('valid','used','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid',
  `scanned_at` timestamp NULL DEFAULT NULL,
  `scanned_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tickets_ticket_code_unique` (`ticket_code`),
  UNIQUE KEY `tickets_qr_code_hash_unique` (`qr_code_hash`),
  KEY `tickets_order_id_foreign` (`order_id`),
  KEY `tickets_seat_availability_id_foreign` (`seat_availability_id`),
  KEY `tickets_scanned_by_foreign` (`scanned_by`),
  CONSTRAINT `tickets_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tickets_scanned_by_foreign` FOREIGN KEY (`scanned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tickets_seat_availability_id_foreign` FOREIGN KEY (`seat_availability_id`) REFERENCES `seat_availabilities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (1,'TKT-20260818-9Y6AJV','7e88dd842f0e14f96038f91d26def847df824008afb47057b397d027b0bc2ce7',1,558,'cancelled',NULL,NULL,'2026-08-17 18:50:36','2026-08-17 22:10:27'),(2,'TKT-20260818-ZB6DBB','e1f5201623e7f1be79bf01647a5de3cef96c8cd38edd08804087964bc245d57a',1,559,'cancelled',NULL,NULL,'2026-08-17 18:50:36','2026-08-17 22:10:27'),(3,'TKT-20260818-Y1AHD4','62be892e8f9d24bb0f2a36130433c96c48e4d4b1a0d65bc4c861707b47ad33ed',1,560,'cancelled',NULL,NULL,'2026-08-17 18:50:36','2026-08-17 22:10:27'),(4,'TKT-20260818-OJLDKA','ffdab4b738ce10a7b6bb9d7a4447fd9ebac468b999df10673592c72b65fd0749',1,561,'cancelled',NULL,NULL,'2026-08-17 18:50:36','2026-08-17 22:10:27'),(5,'TKT-20260818-ZZUBBO','4c2e892cbca9d28a8530c8e5637e7d01ce23ea875d02da504bc458135cc498ba',1,562,'cancelled',NULL,NULL,'2026-08-17 18:50:36','2026-08-17 22:10:27'),(6,'TKT-20260818-K3O9MD','aae9605ed9e7b32a9a4fe4e175de82ff807f1ae7a0b604cd92cd3c495d386695',2,611,'cancelled',NULL,NULL,'2026-08-17 19:17:27','2026-08-17 22:10:27'),(7,'TKT-20260818-USU3W4','4431e53d6fa8fbc038eda78247322c680abe3080f01bab77e789422e7f387572',2,612,'cancelled',NULL,NULL,'2026-08-17 19:17:27','2026-08-17 22:10:27'),(8,'TKT-20260818-RIBRAH','0dadee4ac4792565e00147e771a03fa113d624295a1a09953a0e81052d3f925d',4,604,'cancelled',NULL,NULL,'2026-08-17 20:49:40','2026-08-17 22:10:27'),(9,'TKT-20260818-ZBDUTZ','5e3606d3fd85f935386d0ca16bf588e648db1abf8a2670ff579ce8e3e2c3fd03',4,605,'cancelled',NULL,NULL,'2026-08-17 20:49:40','2026-08-17 22:10:27'),(10,'TKT-20260818-ESIUWF','d2f8835515fe8c873e464561f15bfd3927033a87c0d5d10f50589bdb2cdfb860',4,606,'cancelled',NULL,NULL,'2026-08-17 20:49:40','2026-08-17 22:10:27'),(11,'TKT-20260818-V1LQHZ','ffbe0adde457c216a3688854a3f192630ae3d3cffa2bc0990a7c659f129d34a5',4,607,'cancelled',NULL,NULL,'2026-08-17 20:49:40','2026-08-17 22:10:27'),(12,'TKT-20260818-HOOWGL','1e2b21304f28fcf3d44041e124e131bf1cf1f5f8727424b0762da2ce3830dcb4',5,644,'cancelled','2026-08-17 21:40:38',2,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(13,'TKT-20260818-IW6SGF','30b5bfe1f62c0912383ddeb647b7d1ec022fd2274a1ee67841e121df4d568d5f',5,645,'cancelled','2026-08-17 21:46:34',2,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(14,'TKT-20260818-H4G4M4','83b8a1d43be33ac8f08f993da253d68a860d3b23232369782f77d6df73ded7e9',5,646,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(15,'TKT-20260818-OKQFRB','caddfdfa9c258f0ed915ce489528f1ca8a20464d3cbfd84cdabc0c93e88e9828',5,647,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(16,'TKT-20260818-MPFQK4','7824ba6f8ec50712e3b2bf725ea36366984fe970a29f6d6dcd8734acfe2776b0',5,648,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(17,'TKT-20260818-QJLEGB','15c3415e3ff6588c99eead8cce41a3a6dbffb4f6d82d3274690405272d7ebcac',5,649,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(18,'TKT-20260818-WWOO04','f0a25b6539d6da3e0b99668b13b4cc9df3e697826d19a59650f2ff8483204153',5,650,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(19,'TKT-20260818-332YZH','973ed15405a919a6c2978839233ed37f79a9999ba06d8b82040b7fef4181d953',5,651,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(20,'TKT-20260818-IMCPAS','3c70200dbbe9c156d7ccd94ffcd68f6068617651a1bca3772fdff88a7064d1a4',5,813,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(21,'TKT-20260818-JMEW7F','a71cf90b40400186733099c505597da60a77c51af61c755a19dad4fb6768832b',5,814,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(22,'TKT-20260818-0CNSRW','95a9bca7609d7955ce084810c74b13c22146f5d5d60f44c7d1dc60d6ddc93a36',5,815,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(23,'TKT-20260818-JOYRHZ','a3ffcdbef1755883dcaa5fc29cb13d7371235be806119122b7bb0540f6cb40e2',5,816,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(24,'TKT-20260818-VHRFZV','f749f3b1afdba2d35dfdabfde709fc2138074abbc5c9ae0738c7954ac7493151',5,817,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(25,'TKT-20260818-GN9AYA','73c9aceaa0ca9de4501f53cbbb694f2c17185c18f286530586d612abc03902e3',5,818,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(26,'TKT-20260818-FPZ7VU','1178bdc92f95c1fc47f510ae7c04c654126030fbf73097f1a82acaa3dbdd85ea',5,819,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(27,'TKT-20260818-OUVEBA','ce537ef608cf62eaaf717c4b17ab7d60c8f0f0e89853bc5db562ffcd9704476e',5,820,'cancelled',NULL,NULL,'2026-08-17 21:33:47','2026-08-17 22:10:27'),(28,'TKT-20260818-3D7GMR','a4510b135081671b7022c5400ed9a0c333a7c2876ec16904334eab3fc52ae029',7,1097,'cancelled',NULL,NULL,'2026-08-17 22:05:58','2026-08-17 22:10:27'),(29,'TKT-20260818-R1V6GG','4c847434683fccf92629c8d4faeb959ff29118e62a2cd89e917f73e9f0a5c651',7,1098,'cancelled',NULL,NULL,'2026-08-17 22:05:58','2026-08-17 22:10:27'),(30,'TKT-20260818-RIVDD5','117dbed0fb30385f2e0df6249d7975e916f6434962a8c86a51df47abcba0bed5',7,1099,'cancelled',NULL,NULL,'2026-08-17 22:05:58','2026-08-17 22:10:27'),(31,'TKT-20260818-K8H9JJ','d4e0a91b52ce32d9de40c29b363793ac6bfa27aff9000e111b42db0b77ef5f4a',7,1100,'cancelled',NULL,NULL,'2026-08-17 22:05:58','2026-08-17 22:10:27');
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin Nanya Events','admin@nanyaevents.com',NULL,NULL,'2026-08-14 20:39:53','$2y$12$ZqfXXXoxaKhsGPypmlrb7OMo3378y29HXIZ9.LsEZZ12qOyMqd60a',NULL,'2026-08-14 20:39:53','2026-08-14 20:39:53'),(2,'Panitia Event Demo','panitia@nanyaevents.com',NULL,NULL,'2026-08-14 20:39:53','$2y$12$tdV8enlad6IMA9yPHbKOJOqfOTdz/WeJBSq4v44cs3xlNNP2/51uy',NULL,'2026-08-14 20:39:53','2026-08-14 20:39:53'),(3,'Budi Penonton','penonton@gmail.com',NULL,NULL,'2026-08-14 20:39:53','$2y$12$YSmoAmPI57EHBj3erDioretZc1mJqTJfhlv2ZPhNSqRSEi0ZR3XzO',NULL,'2026-08-14 20:39:53','2026-08-14 20:39:53'),(4,'IT Programmer','itprogrammer@nanyangzh.sch.id',NULL,NULL,NULL,'$2y$12$/sAvRhmOT/RkLQ8teMdoSe5/FCRE51xlEOSQFG7wwmBarWJ9W.Mme',NULL,'2026-08-17 18:14:40','2026-08-17 18:14:40'),(5,'002 Testing Siswa','002@nanyangzh.sch.id','116543262394736443200','https://lh3.googleusercontent.com/a/ACg8ocKwevFjiHrdx7AaJFbxdlyaMOkqJq1CNyM6MkMZuFpy1B5mfg=s96-c',NULL,'$2y$12$oItGlzBmlG9tRjlDzuXgWeALIyY25/TuUZBP6enCi/P1zn6Ca4xF2','LaZ0QFabrWdLX1m5MImWbWTkxqLb8intBagSxm91NNTVdkUutFcC9pxQcWzh','2026-08-17 20:46:38','2026-08-17 20:46:38');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venues`
--

DROP TABLE IF EXISTS `venues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `total_rows` int NOT NULL DEFAULT '10',
  `total_columns` int NOT NULL DEFAULT '12',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venues`
--

LOCK TABLES `venues` WRITE;
/*!40000 ALTER TABLE `venues` DISABLE KEYS */;
INSERT INTO `venues` VALUES (1,'Auditorium Sailendra Lt. 3','Vihara Borobudur, Jl. Imam Bonjol No. 21 Medan',17,38,1,'2026-08-14 20:39:53','2026-08-14 20:39:53');
/*!40000 ALTER TABLE `venues` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-18 13:56:03
