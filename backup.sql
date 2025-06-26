-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2025 at 01:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `multitenant_ecommerce`
--

--
-- Dumping data for table `cache`
--

TRUNCATE TABLE `cache`;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('multitenant_ecommerce_cache_seller@seller.com|188.253.99.54', 'i:1;', 1750562292),
('multitenant_ecommerce_cache_seller@seller.com|188.253.99.54:timer', 'i:1750562292;', 1750562292);

--
-- Dumping data for table `migrations`
--

TRUNCATE TABLE `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_21_125408_create_saas_seller_profiles_table', 1),
(5, '2025_05_21_125447_create_saas_customer_profiles_table', 1),
(6, '2025_05_21_125607_create_saas_categories_table', 1),
(7, '2025_05_21_125644_create_saas_sub_categories_table', 1),
(8, '2025_05_21_125727_create_saas_child_categories_table', 1),
(9, '2025_05_21_125813_create_saas_brands_table', 1),
(10, '2025_05_21_125848_create_saas_units_table', 1),
(11, '2025_05_21_125921_create_saas_products_table', 1),
(12, '2025_05_21_125946_create_saas_product_images_table', 1),
(13, '2025_05_21_130000_create_saas_wishlists_table', 1),
(14, '2025_05_21_130037_create_saas_attributes_table', 1),
(15, '2025_05_21_130117_create_saas_attribute_values_table', 1),
(16, '2025_05_21_130154_create_saas_product_variations_table', 1),
(17, '2025_05_21_130229_create_saas_coupons_table', 1),
(18, '2025_05_21_130309_create_saas_orders_table', 1),
(19, '2025_05_21_130350_create_saas_order_items_table', 1),
(20, '2025_05_21_130429_create_saas_product_reviews_table', 1),
(21, '2025_05_21_130516_create_saas_banners_table', 1),
(22, '2025_05_21_130541_create_saas_flash_deals_table', 1),
(23, '2025_05_21_130608_create_saas_flash_deal_products_table', 1),
(25, '2025_05_21_221815_create_saas_payment_methods_table', 1),
(26, '2025_05_21_221816_create_saas_withdrawals_table', 1),
(28, '2025_05_22_194328_add_has_variations_to_saas_products_table', 1),
(29, '2025_05_22_202412_add_discount_fields_to_product_variations', 1),
(30, '2025_06_02_075423_add_views_to_saas_products_table', 1),
(31, '2025_06_09_000000_create_saas_settings_table', 1),
(32, '2025_06_10_000006_create_saas_carts_table', 1),
(33, '2025_06_10_231355_add_tax_and_shipping_settings_to_saas_settings_table', 1),
(34, '2025_06_11_001100_modify_saas_carts_for_multiple_variations', 1),
(35, '2025_06_11_210837_add_order_notes_to_saas_orders_table', 1),
(36, '2025_06_11_214741_add_address_fields_and_coupon_to_saas_orders_table', 1),
(37, '2025_06_11_221436_add_weight_to_saas_products_table', 1),
(38, '2025_06_11_231225_fix_coupon_discount_type_enum_in_orders_table', 1),
(39, '2025_06_14_203812_add_cancelled_at_to_saas_orders_table', 1),
(40, '2025_06_14_205154_add_admin_note_to_saas_orders_table', 1),
(41, '2025_06_14_210000_add_missing_fields_to_saas_product_reviews_table', 1),
(42, '2025_06_15_000001_add_product_type_and_digital_fields_to_saas_products_table', 1),
(43, '2025_06_15_000002_make_seller_id_nullable_for_in_house_products', 1),
(44, '2025_06_15_000003_create_saas_in_house_sales_table', 1),
(45, '2025_06_15_000004_create_saas_in_house_sale_items_table', 1),
(46, '2025_06_15_141448_add_seller_id_to_saas_flash_deals_table', 1),
(47, '2025_06_15_224043_add_payment_status_to_saas_orders_table_if_missing', 1),
(48, '2025_06_17_000001_create_saas_pages_table', 1),
(49, '2025_06_17_000002_create_saas_subscribers_table', 1),
(50, '2025_06_17_000003_create_saas_contact_messages_table', 1),
(51, '2025_06_19_192648_add_customer_id_to_saas_in_house_sales_table', 1),
(52, '2025_06_19_194756_remove_customer_fields_from_saas_in_house_sales_table', 1),
(53, '2025_06_21_000000_remove_old_address_fields_from_saas_orders', 1),
(54, '2025_06_22_000000_remove_paid_due_amount_from_saas_in_house_sales', 1),
(55, '2025_06_25_000001_enhance_saas_pages_table', 1),
(56, '2025_06_25_000002_create_saas_blog_categories_table', 1),
(57, '2025_06_25_000003_create_saas_blog_posts_table', 1),
(58, '2025_06_27_000000_add_apply_share_link_to_saas_settings_table', 1),
(59, '2025_06_28_000001_update_banner_positions', 1),
(68, '2025_06_25_230737_make_seller_id_nullable_in_saas_order_items_table', 2);

--
-- Dumping data for table `saas_banners`
--

TRUNCATE TABLE `saas_banners`;
INSERT INTO `saas_banners` (`id`, `title`, `image`, `link_url`, `position`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'best deals', 'banner_images/6857a15aeacdb.png', NULL, 'main_section', 1, '2025-06-22 01:23:22', '2025-06-22 01:23:22'),
(2, 'wew', 'banner_images/6857a19b78ce6.png', NULL, 'main_section', 1, '2025-06-22 01:24:27', '2025-06-22 01:24:27');

--
-- Dumping data for table `saas_brands`
--

TRUNCATE TABLE `saas_brands`;
INSERT INTO `saas_brands` (`id`, `name`, `slug`, `image`, `created_at`, `updated_at`) VALUES
(1, 'in house(allsewa)', 'in-houseallsewa', NULL, '2025-06-22 01:27:02', '2025-06-22 01:27:02'),
(2, 'clothes', 'clothes', NULL, '2025-06-22 01:27:19', '2025-06-22 01:27:19');

--
-- Dumping data for table `saas_carts`
--

TRUNCATE TABLE `saas_carts`;
INSERT INTO `saas_carts` (`id`, `user_id`, `session_id`, `product_id`, `variation_id`, `variations_data`, `variation_details`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 1, NULL, NULL, NULL, 1, 900.00, '2025-06-22 01:36:15', '2025-06-22 01:36:15');

--
-- Dumping data for table `saas_categories`
--

TRUNCATE TABLE `saas_categories`;
INSERT INTO `saas_categories` (`id`, `name`, `slug`, `image`, `status`, `featured`, `created_at`, `updated_at`) VALUES
(1, 'Women\'s Fashion', 'womens-fashion', 'category_images/6857a087d577b.png', 1, 1, '2025-06-22 01:19:51', '2025-06-22 01:19:52'),
(2, 'Men\'s Fashion', 'mens-fashion', 'category_images/6857a0a6cad0f.jpg', 1, 1, '2025-06-22 01:20:22', '2025-06-22 01:20:22'),
(3, 'Babies & Toys', 'babies-toys', 'category_images/6857a0c394b93.png', 1, 1, '2025-06-22 01:20:51', '2025-06-22 01:20:51'),
(4, 'Watches & Accessories', 'watches-accessories', 'category_images/6857a0ebb6f76.jpeg', 1, 1, '2025-06-22 01:21:31', '2025-06-22 01:21:31'),
(5, 'Home & Lifestyle', 'home-lifestyle', 'category_images/6857a10dcc114.png', 1, 1, '2025-06-22 01:22:05', '2025-06-22 01:22:05');

--
-- Dumping data for table `saas_customer_profiles`
--

TRUNCATE TABLE `saas_customer_profiles`;
INSERT INTO `saas_customer_profiles` (`id`, `user_id`, `shipping_address`, `billing_address`, `created_at`, `updated_at`) VALUES
(1, 3, 'anamnagar kathmandu', 'Anamnagar', '2025-06-22 01:35:40', '2025-06-22 01:35:40');

--
-- Dumping data for table `saas_in_house_sales`
--

TRUNCATE TABLE `saas_in_house_sales`;
INSERT INTO `saas_in_house_sales` (`id`, `sale_number`, `customer_id`, `subtotal`, `tax_amount`, `discount_amount`, `discount_type`, `shipping_amount`, `total_amount`, `payment_method`, `payment_status`, `notes`, `cashier_id`, `sale_date`, `created_at`, `updated_at`) VALUES
(1, 'IHS202506220001', 3, 1000.00, 0.00, 0.00, 'flat', 0.00, 1000.00, 'cash', 'paid', NULL, 1, '2025-06-22 01:41:57', '2025-06-22 01:41:57', '2025-06-22 01:41:57');

--
-- Dumping data for table `saas_in_house_sale_items`
--

TRUNCATE TABLE `saas_in_house_sale_items`;
INSERT INTO `saas_in_house_sale_items` (`id`, `sale_id`, `product_id`, `variation_id`, `product_name`, `product_sku`, `unit_price`, `quantity`, `discount_amount`, `discount_type`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'Premium SSD Hosting', '90-21', 1000.00, 1, 0.00, 'flat', 1000.00, '2025-06-22 01:41:57', '2025-06-22 01:41:57');

--
-- Dumping data for table `saas_orders`
--

TRUNCATE TABLE `saas_orders`;
INSERT INTO `saas_orders` (`id`, `order_number`, `customer_id`, `seller_id`, `total`, `subtotal`, `discount`, `tax`, `shipping_fee`, `payment_status`, `shipping_name`, `shipping_email`, `shipping_phone`, `shipping_country`, `shipping_street_address`, `shipping_city`, `shipping_state`, `shipping_postal_code`, `billing_name`, `billing_email`, `billing_phone`, `billing_country`, `billing_street_address`, `billing_city`, `billing_state`, `billing_postal_code`, `coupon_code`, `coupon_discount_amount`, `coupon_discount_type`, `order_status`, `payment_method`, `order_notes`, `placed_at`, `created_at`, `updated_at`, `cancelled_at`, `cancellation_reason`, `admin_note`) VALUES
(6, 'ORD-20250625-4001', 4, NULL, 6215.00, 5500.00, 0.00, 715.00, 0.00, 'pending', 'Ravi Pangali', 'legendromeoravi@gmail.com', '9822426473', 'Nepal', 'chappargaudi janaki nagar', 'Banke', 'Lumbini', '21900', 'Ravi Pangali', 'legendromeoravi@gmail.com', '9822426473', 'Nepal', 'chappargaudi janaki nagar', 'Banke', 'Lumbini', '21900', NULL, 0.00, NULL, 'pending', 'cash_on_delivery', NULL, '2025-06-25 17:24:55', '2025-06-25 17:24:55', '2025-06-25 17:24:55', NULL, NULL, NULL),
(7, 'ORD-20250625-4504', 4, 2, 2237.40, 1980.00, 0.00, 257.40, 0.00, 'refunded', 'Ravi Pangali', 'legendromeoravi@gmail.com', '9822426473', 'Nepal', 'chappargaudi janaki nagar', 'Banke', 'Lumbini', '21900', 'Ravi Pangali', 'legendromeoravi@gmail.com', '9822426473', 'Nepal', 'chappargaudi janaki nagar', 'Banke', 'Lumbini', '21900', NULL, 0.00, NULL, 'refunded', 'cash_on_delivery', NULL, '2025-06-25 23:40:50', '2025-06-25 17:41:22', '2025-06-25 17:55:50', NULL, NULL, NULL);

--
-- Dumping data for table `saas_order_items`
--

TRUNCATE TABLE `saas_order_items`;
INSERT INTO `saas_order_items` (`id`, `order_id`, `seller_id`, `product_id`, `variation_id`, `quantity`, `price`, `discount`, `tax`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, NULL, 3, NULL, 1, 5500.00, 2500.00, 715.00, 'pending', '2025-06-25 17:24:55', '2025-06-25 17:24:55'),
(2, 7, 2, 6, NULL, 2, 990.00, 10.00, 257.40, 'pending', '2025-06-25 17:41:22', '2025-06-25 17:41:22');

--
-- Dumping data for table `saas_payment_methods`
--

TRUNCATE TABLE `saas_payment_methods`;
INSERT INTO `saas_payment_methods` (`id`, `user_id`, `type`, `title`, `details`, `is_default`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'bank_transfer', 'Bank Acc', '{\"account_name\":\"Seller\",\"bank_name\":\"Nabil\",\"bank_branch\":\"Kohalpur\",\"account_number\":\"123453321335321\",\"mobile_number\":null}', 1, 1, NULL, '2025-06-25 17:35:58', '2025-06-25 17:35:58'),
(2, 4, 'esewa', NULL, '{\"bank_name\":null,\"branch_name\":null,\"account_name\":null,\"account_number\":null,\"esewa_id\":\"4567890234\",\"khalti_id\":null}', 1, 1, NULL, '2025-06-25 17:47:53', '2025-06-25 17:47:53');

--
-- Dumping data for table `saas_products`
--

TRUNCATE TABLE `saas_products`;
INSERT INTO `saas_products` (`id`, `seller_id`, `name`, `slug`, `product_type`, `is_in_house_product`, `file`, `description`, `short_description`, `category_id`, `subcategory_id`, `child_category_id`, `brand_id`, `SKU`, `unit_id`, `stock`, `weight`, `price`, `discount`, `discount_type`, `tax`, `is_active`, `is_featured`, `seller_publish_status`, `views`, `has_variations`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Premium SSD Hosting', 'premium-ssd-hosting', 'Physical', 1, NULL, 'Model: BPC F200M\r\nPressure locked safety lid \r\nautomatic safety valve\r\nbetter pressure regulation\r\nrust proof components stay cool handles\r\nNew improved vent weight \r\nsize 2 ltr', 'Pessure Cooker : MEGNA HARD ANODIZED BPC F200M', 2, NULL, NULL, 1, '90-21', 1, 99, 0.50, 1000.00, 100.00, 'flat', 0.00, 1, 0, 'approved_product', 1, 0, NULL, NULL, '2025-06-22 01:30:09', '2025-06-25 16:38:27'),
(2, NULL, 'Formal Pant For Ladies', 'formal-pant-for-ladies', 'Physical', 1, NULL, 'Formal Pant For Ladies Belly Cut Design\r\n\r\n Ladies Formal Pant with Belly Cut Design\r\n• Plain pattern and stretchable material for comfort\r\n• Bootcut & Flare fit for a stylish look\r\n• Made with high-quality, formal clothing material\r\n• Suitable for summer', 'Product thumb\r\nFormal Pant For Ladies', 1, NULL, NULL, 2, '90-22', 1, 5, 0.50, 2500.00, 250.00, 'flat', 0.00, 1, 1, 'approved_product', 0, 0, NULL, NULL, '2025-06-22 02:15:40', '2025-06-25 16:38:30'),
(3, NULL, 'Duck Chu Chu Sound Bathtub Toys for Toddler Kids', 'duck-chu-chu-sound-bathtub-toys-for-toddler-kids', 'Physical', 1, NULL, 'Bath toys are colourful and just the right size to fit into little hands and engage kids during bath time. Non-toxic and no sharp edges, colorful floating bath toy provides fun during bath time\r\nMakes fun and surprising \'ChuChu sound, learning and educating toys while playing and bathing. Doesn\'t require any battery\r\nCreative Kids Duck Family Bath Toys Very attractive and bright color. play with it and squeeze it. It makes sound when you squeeze it\r\nPackage Contains : Mother duck : 1 piece , baby ducks : 3 piece.\r\nDuck Family Bath Toy, Provides Fun During Bath time.', 'Duck Chu Chu Sound Bathtub Toys for Toddler Kids', 3, NULL, NULL, 1, '90-23', 1, 9, 0.50, 8000.00, 2500.00, 'flat', 0.00, 1, 0, 'approved_product', 0, 0, NULL, NULL, '2025-06-22 02:17:54', '2025-06-25 17:24:55'),
(4, NULL, 'Dancing Cactus Toy', 'dancing-cactus-toy', 'Physical', 1, NULL, 'Product Dimensions 3.5 x 3.5 x 12.2 inches (8.9 x 8.9 x 31 cm)\r\nItem Weight 7 ounces (198.45 grams)\r\nManufacturer recommended age 3 years and up Toy figure type Stuffed Toy Brand Redini Animal theme\r\nOtter Item Dimensions LxWxH3.5 x 3.5 x 12.2 inches (8.9 x 8.9 x 31 cm)\r\nAge Range (Description)Baby child adult\r\nRechargable', 'Dancing Cactus Toy', 3, NULL, NULL, 1, '90-24', 1, 12, 0.50, 2500.00, 150.00, 'flat', 0.00, 1, 1, 'approved_product', 0, 0, NULL, NULL, '2025-06-22 02:21:03', '2025-06-25 16:38:23'),
(5, NULL, 'Dancing Cactus Toy', 'dancing-cactus-toy-1', 'Physical', 1, NULL, 'Dancing Cactus Toy', 'Dancing Cactus Toy', 3, NULL, NULL, 1, '90-26', 1, 8, 0.50, 400.00, 10.00, 'flat', 0.00, 1, 0, 'approved_product', 0, 0, NULL, NULL, '2025-06-22 02:22:19', '2025-06-25 16:38:22'),
(6, 2, 'Tony Stark Biography', 'tony-stark-biography', 'Digital', 0, 'digital_products/685c829983912_1750893209.pdf', 'Long', 'HSor', 3, NULL, NULL, 2, '2343245', 2, 999997, 0.50, 1000.00, 10.00, 'flat', 0.00, 1, 0, 'approved_product', 0, 0, NULL, NULL, '2025-06-25 17:28:24', '2025-06-25 17:41:22');

--
-- Dumping data for table `saas_product_images`
--

TRUNCATE TABLE `saas_product_images`;
INSERT INTO `saas_product_images` (`id`, `product_id`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'product_images/6857a2f1ca1bb.png', '2025-06-22 01:30:09', '2025-06-22 01:30:09'),
(2, 1, 'product_images/6857a2f21a754.png', '2025-06-22 01:30:10', '2025-06-22 01:30:10'),
(3, 2, 'product_images/6857ad9ca24ea.jpg', '2025-06-22 02:15:40', '2025-06-22 02:15:40'),
(4, 2, 'product_images/6857ad9caf1a4.png', '2025-06-22 02:15:40', '2025-06-22 02:15:40'),
(5, 3, 'product_images/6857ae2212b1f.jpg', '2025-06-22 02:17:54', '2025-06-22 02:17:54'),
(6, 3, 'product_images/6857ae2222338.jpg', '2025-06-22 02:17:54', '2025-06-22 02:17:54'),
(7, 4, 'product_images/6857aee095967.jpg', '2025-06-22 02:21:04', '2025-06-22 02:21:04'),
(8, 4, 'product_images/6857aee134a85.jpg', '2025-06-22 02:21:05', '2025-06-22 02:21:05'),
(9, 5, 'product_images/6857af2befeb4.jpeg', '2025-06-22 02:22:19', '2025-06-22 02:22:19'),
(10, 5, 'product_images/6857af2c0103e.jpeg', '2025-06-22 02:22:20', '2025-06-22 02:22:20'),
(11, 6, 'product_images/685c82999d263.png', '2025-06-25 17:28:29', '2025-06-25 17:28:29');

--
-- Dumping data for table `saas_refunds`
--

TRUNCATE TABLE `saas_refunds`;
INSERT INTO `saas_refunds` (`id`, `customer_id`, `order_id`, `seller_id`, `payment_method_id`, `order_amount`, `commission_rate`, `commission_amount`, `refund_amount`, `seller_deduct_amount`, `currency`, `status`, `customer_reason`, `admin_notes`, `admin_attachment`, `processed_at`, `rejected_reason`, `processed_by`, `created_at`, `updated_at`) VALUES
(1, 4, 7, 2, 2, 2237.40, 50.00, 1118.70, 2237.40, 1118.70, 'NPR', 'approved', 'Money abck', 'Ok', NULL, '2025-06-25 17:55:50', NULL, 1, '2025-06-25 17:48:14', '2025-06-25 17:55:50');

--
-- Dumping data for table `saas_seller_profiles`
--

TRUNCATE TABLE `saas_seller_profiles`;
INSERT INTO `saas_seller_profiles` (`id`, `user_id`, `store_name`, `store_logo`, `store_banner`, `store_description`, `is_approved`, `address`, `created_at`, `updated_at`) VALUES
(1, 2, 'naya kapada pasal', NULL, NULL, 'sabai saman painxa', 1, 'Dhangadhi', '2025-06-21 22:15:43', '2025-06-21 22:15:43');

--
-- Dumping data for table `saas_settings`
--

TRUNCATE TABLE `saas_settings`;
INSERT INTO `saas_settings` (`id`, `site_name`, `site_logo`, `site_favicon`, `site_description`, `site_keywords`, `site_footer`, `site_email`, `site_phone`, `site_address`, `site_facebook`, `site_twitter`, `site_instagram`, `site_linkedin`, `site_youtube`, `site_whatsapp`, `site_currency_symbol`, `site_currency_code`, `seller_commission`, `balance`, `mail_host`, `mail_port`, `mail_username`, `mail_password`, `mail_encryption`, `mail_from_address`, `mail_from_name`, `minimum_withdrawal_amount`, `gateway_transaction_fee`, `esewa_merchant_id`, `esewa_secret_key`, `khalti_public_key`, `khalti_secret_key`, `withdrawal_policy`, `shipping_enable_free`, `shipping_free_min_amount`, `shipping_flat_rate_enable`, `shipping_flat_rate_cost`, `shipping_enable_local_pickup`, `shipping_local_pickup_cost`, `shipping_allow_seller_config`, `shipping_seller_free_enable`, `shipping_seller_flat_rate_enable`, `shipping_seller_zone_based_enable`, `shipping_policy_info`, `tax_enable`, `tax_rate`, `tax_shipping`, `tax_inclusive_pricing`, `shipping_weight_rate`, `shipping_min_weight`, `shipping_max_weight`, `shipping_zone_based_enable`, `shipping_local_rate`, `shipping_regional_rate`, `shipping_remote_rate`, `apply_share_link`, `created_at`, `updated_at`) VALUES
(1, 'All sewa', NULL, NULL, 'Allsewa is for everyone', 'ecommerce, shopping', 'developed by saas tech nepal', 'Info@allsewa.com.np', '9860247858', 'anamnagar kathmandu', NULL, NULL, NULL, NULL, NULL, NULL, 'Rs', 'NPR', 50.00, -2237.40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 0, 0, 0, 0, NULL, 1, 13.00, 0, 0, NULL, 0.50, 50.00, 0, 50.00, 100.00, 200.00, 'https://saastechnepal.com/', '2025-06-22 02:26:41', '2025-06-25 17:55:50');

--
-- Dumping data for table `saas_transactions`
--

TRUNCATE TABLE `saas_transactions`;
INSERT INTO `saas_transactions` (`id`, `user_id`, `transaction_type`, `amount`, `balance_before`, `balance_after`, `order_id`, `reference_type`, `reference_id`, `description`, `commission_percentage`, `commission_amount`, `status`, `meta_data`, `transaction_date`, `created_at`, `updated_at`) VALUES
(1, NULL, 'deposit', 2237.40, 0.00, 2237.40, 7, 'order', 7, 'Order payment received - Order #ORD-20250625-4504', NULL, NULL, 'completed', NULL, '2025-06-25 17:43:08', '2025-06-25 17:43:08', '2025-06-25 17:43:08'),
(2, 2, 'commission', 2237.40, 0.00, 2237.40, 7, 'order', 7, 'Commission earned from Order #ORD-20250625-4504', 0.00, 0.00, 'completed', NULL, '2025-06-25 17:43:08', '2025-06-25 17:43:08', '2025-06-25 17:43:08'),
(3, NULL, 'refund', 2237.40, 2237.40, 0.00, 7, 'order', 7, 'Order refunded - Order #ORD-20250625-4504', NULL, NULL, 'completed', NULL, '2025-06-25 17:44:48', '2025-06-25 17:44:48', '2025-06-25 17:44:48'),
(4, 2, 'refund', 1118.70, 2237.40, 1118.70, 7, 'order', 7, 'Commission refunded for Order #ORD-20250625-4504', 50.00, 1118.70, 'completed', NULL, '2025-06-25 17:44:48', '2025-06-25 17:44:48', '2025-06-25 17:44:48'),
(5, NULL, 'deposit', 2237.40, 0.00, 2237.40, 7, 'order', 7, 'Order payment received - Order #ORD-20250625-4504', NULL, NULL, 'completed', NULL, '2025-06-25 17:46:38', '2025-06-25 17:46:38', '2025-06-25 17:46:38'),
(6, 2, 'commission', 1118.70, 0.00, 1118.70, 7, 'order', 7, 'Commission earned from Order #ORD-20250625-4504', 50.00, 1118.70, 'completed', NULL, '2025-06-25 17:46:38', '2025-06-25 17:46:38', '2025-06-25 17:46:38'),
(7, NULL, 'refund', 2237.40, 2237.40, 0.00, 7, 'order', 7, 'Customer refund processed - Order #ORD-20250625-4504', NULL, NULL, 'completed', NULL, '2025-06-25 17:55:50', '2025-06-25 17:55:50', '2025-06-25 17:55:50'),
(8, 2, 'refund', 1118.70, 1118.70, 0.00, 7, 'order', 7, 'Refund deduction - Order #ORD-20250625-4504', 50.00, 1118.70, 'completed', NULL, '2025-06-25 17:55:50', '2025-06-25 17:55:50', '2025-06-25 17:55:50'),
(9, NULL, 'refund', 2237.40, 0.00, -2237.40, 7, 'order', 7, 'Order refunded - Order #ORD-20250625-4504', NULL, NULL, 'completed', NULL, '2025-06-25 17:55:50', '2025-06-25 17:55:50', '2025-06-25 17:55:50'),
(10, 2, 'refund', 1118.70, 0.00, -1118.70, 7, 'order', 7, 'Commission refunded for Order #ORD-20250625-4504', 50.00, 1118.70, 'completed', NULL, '2025-06-25 17:55:50', '2025-06-25 17:55:50', '2025-06-25 17:55:50');

--
-- Dumping data for table `saas_units`
--

TRUNCATE TABLE `saas_units`;
INSERT INTO `saas_units` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'piece', '2025-06-22 01:28:44', '2025-06-22 01:28:44'),
(2, 'kg', '2025-06-22 01:28:50', '2025-06-22 01:28:50'),
(3, 'liter', '2025-06-22 01:28:57', '2025-06-22 01:28:57'),
(4, 'meter', '2025-06-22 01:29:03', '2025-06-22 01:29:03');

--
-- Dumping data for table `sessions`
--

TRUNCATE TABLE `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('aPO5lexM6aEd1zGb4UERcgSeDzxH5HYbCz3P3MNx', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNzFSYnpRcThlVGtQUmJXYmJBYmQycDk5Yk5WWEN6NWhqaFNrRFpEWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2VsbGVyL3Byb2R1Y3RzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1750893105),
('Grx9kwFCEVGaXTllkHhjMrJpwQNWdQvuFtnD4ql5', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMGhVbnpITExtcTQ0RjVsZk1QVllDTXpxY1MwM3J6NlFQQkFkV3NPaSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc2VsbGVyL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1750894866),
('XQbh3DR2XmE1uNsj19sSo9qpwDjsw2JzAfwyn6Qm', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiclNCZ0x4Nk9xeVdIWHF6QUt3aDNMS1dxU2tWNUdSTzVReFNIaTBkVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9yZWZ1bmRzLzEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1750894860),
('ZIDoKy5D8fo83oMUpSKRetFN3Kglq0lq5ymv2xLW', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiZGFlTU1KMWU4WHZ5b3NCbG5KRTlscVFKWWNtNFMzQzNOaElqSlE3YSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vcmVmdW5kcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo1OiJhbGVydCI7YTowOnt9fQ==', 1750894855);

--
-- Dumping data for table `users`
--

TRUNCATE TABLE `users`;
INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `email_verified_at`, `password`, `phone`, `profile_photo`, `is_active`, `commission`, `balance`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, 'admin@admin.com', 'admin', NULL, '$2y$12$kMnj/0mds6pxKwNSap43fe6gpZJYDBIigJ.B6OYSIzqpH99a/.nmO', NULL, NULL, 1, 0.00, 0.00, NULL, NULL, '2025-06-21 17:41:28', '2025-06-21 17:41:28'),
(2, 'jhalak Dawadi', NULL, 'ravipangaliharinarayan@gmail.com', 'seller', NULL, '$2y$12$38WAfT9GvFBpx1P1vjgamugKhQXOzj7RpHeu87ZNRmy0zMiiYEpim', '+9779860247858', NULL, 1, 0.00, -1118.70, NULL, NULL, '2025-06-21 22:15:43', '2025-06-25 17:55:50'),
(3, 'Jhalak Dawadi', NULL, 'jhalak.newsakhabar@gmail.com', 'customer', NULL, '$2y$12$Ukqq0rVdifJ8FG7B/U/u.u17mmOG.9QsAYuzeYplwMVuxqJ7CPC1q', '9860247858', 'profile_photos/wElGZsRhjIIAUx2xwV4XQYyGUTXy6UzQAHzfU6gd.jpg', 1, 0.00, 0.00, NULL, NULL, '2025-06-22 01:32:29', '2025-06-22 01:35:40'),
(4, 'Ravi Pangali', NULL, 'legendromeoravi@gmail.com', 'customer', NULL, '$2y$12$xHX5WLchB1UcecNxKDXqCuLoQGSLif.sIDN9YA1O1OQGBUqJlchsm', NULL, NULL, 1, 0.00, 0.00, NULL, NULL, '2025-06-25 16:45:07', '2025-06-25 16:45:07');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
