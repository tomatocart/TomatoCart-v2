DROP TABLE IF EXISTS `toc_address_book`;
CREATE TABLE IF NOT EXISTS `toc_address_book` (
  `address_book_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL,
  `entry_gender` char(1) NOT NULL,
  `entry_company` varchar(32) DEFAULT NULL,
  `entry_firstname` varchar(32) NOT NULL,
  `entry_lastname` varchar(32) NOT NULL,
  `entry_street_address` varchar(64) NOT NULL,
  `entry_suburb` varchar(32) DEFAULT NULL,
  `entry_postcode` varchar(10) NOT NULL,
  `entry_city` varchar(32) NOT NULL,
  `entry_state` varchar(32) DEFAULT NULL,
  `entry_country_id` int(11) NOT NULL DEFAULT '0',
  `entry_zone_id` int(11) NOT NULL DEFAULT '0',
  `entry_telephone` varchar(32) DEFAULT NULL,
  `entry_fax` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`address_book_id`),
  KEY `idx_address_book_customers_id` (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_administrators`;
CREATE TABLE IF NOT EXISTS `toc_administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) DEFAULT NULL,
  `user_password` varchar(40) NOT NULL,
  `user_settings` text,
  `email_address` varchar(96) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_administrators_access`;
CREATE TABLE IF NOT EXISTS `toc_administrators_access` (
  `administrators_id` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  PRIMARY KEY (`administrators_id`,`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_administrators_log`;
CREATE TABLE IF NOT EXISTS `toc_administrators_log` (
  `id` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  `module_action` varchar(32) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `field_key` varchar(255) NOT NULL,
  `old_value` text,
  `new_value` text,
  `action` varchar(255) NOT NULL,
  `administrators_id` int(11) NOT NULL,
  `datestamp` datetime NOT NULL,
  KEY `idx_administrators_log_id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_articles`;
CREATE TABLE IF NOT EXISTS `toc_articles` (
  `articles_id` int(11) NOT NULL AUTO_INCREMENT,
  `articles_categories_id` int(11) NOT NULL,
  `articles_status` tinyint(1) NOT NULL,
  `articles_order` int(10) NOT NULL,
  `articles_date_added` datetime NOT NULL,
  `articles_last_modified` datetime NOT NULL,
  `articles_image` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`articles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_articles_categories`;
CREATE TABLE IF NOT EXISTS `toc_articles_categories` (
  `articles_categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `articles_categories_status` tinyint(1) NOT NULL,
  `articles_categories_order` int(10) NOT NULL,
  PRIMARY KEY (`articles_categories_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_articles_categories_description`;
CREATE TABLE IF NOT EXISTS `toc_articles_categories_description` (
  `articles_categories_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `articles_categories_name` varchar(255) NOT NULL,
  `articles_categories_url` varchar(255) NOT NULL,
  `articles_categories_page_title` varchar(255) NOT NULL,
  `articles_categories_meta_keywords` varchar(255) NOT NULL,
  `articles_categories_meta_description` varchar(255) NOT NULL,
  PRIMARY KEY (`articles_categories_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_articles_description`;
CREATE TABLE IF NOT EXISTS `toc_articles_description` (
  `articles_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `articles_name` varchar(255) NOT NULL,
  `articles_url` varchar(255) NOT NULL,
  `articles_description` text NOT NULL,
  `articles_page_title` varchar(255) NOT NULL,
  `articles_meta_keywords` varchar(255) NOT NULL,
  `articles_meta_description` varchar(255) NOT NULL,
  PRIMARY KEY (`articles_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_banners`;
CREATE TABLE IF NOT EXISTS `toc_banners` (
  `banners_id` int(11) NOT NULL AUTO_INCREMENT,
  `banners_title` varchar(64) NOT NULL,
  `banners_url` varchar(255) NOT NULL,
  `banners_image` varchar(64) NOT NULL,
  `banners_group` varchar(10) NOT NULL,
  `banners_html_text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `expires_impressions` int(7) DEFAULT '0',
  `expires_date` datetime DEFAULT NULL,
  `date_scheduled` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`banners_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_banners_history`;
CREATE TABLE IF NOT EXISTS `toc_banners_history` (
  `banners_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `banners_id` int(11) NOT NULL,
  `banners_shown` int(5) NOT NULL DEFAULT '0',
  `banners_clicked` int(5) NOT NULL DEFAULT '0',
  `banners_history_date` datetime NOT NULL,
  PRIMARY KEY (`banners_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_categories`;
CREATE TABLE IF NOT EXISTS `toc_categories` (
  `categories_id` int(11) NOT NULL AUTO_INCREMENT,
  `categories_image` varchar(64) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(3) DEFAULT NULL,
  `categories_status` int(1) DEFAULT '1',
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`categories_id`),
  KEY `idx_categories_parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_categories_description`;
CREATE TABLE IF NOT EXISTS `toc_categories_description` (
  `categories_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `categories_name` varchar(64) NOT NULL,
  `categories_url` varchar(64) NOT NULL,
  `categories_page_title` varchar(255) NOT NULL,
  `categories_meta_keywords` varchar(255) NOT NULL,
  `categories_meta_description` varchar(255) NOT NULL,
  PRIMARY KEY (`categories_id`,`language_id`),
  KEY `idx_categories_name` (`categories_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_categories_ratings`;
CREATE TABLE IF NOT EXISTS `toc_categories_ratings` (
  `categories_id` int(11) NOT NULL,
  `ratings_id` int(11) NOT NULL,
  PRIMARY KEY (`categories_id`,`ratings_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_configuration`;
CREATE TABLE IF NOT EXISTS `toc_configuration` (
  `configuration_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_title` varchar(64) NOT NULL,
  `configuration_key` varchar(64) NOT NULL,
  `configuration_value` varchar(1024) NOT NULL,
  `configuration_description` varchar(255) NOT NULL,
  `configuration_group_id` int(11) NOT NULL,
  `sort_order` int(5) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `use_function` varchar(255) DEFAULT NULL,
  `set_function` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`configuration_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_configuration_group`;
CREATE TABLE IF NOT EXISTS `toc_configuration_group` (
  `configuration_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `configuration_group_title` varchar(64) NOT NULL,
  `configuration_group_description` varchar(255) NOT NULL,
  `sort_order` int(5) DEFAULT NULL,
  `visible` int(1) DEFAULT '1',
  PRIMARY KEY (`configuration_group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_counter`;
CREATE TABLE IF NOT EXISTS `toc_counter` (
  `startdate` datetime DEFAULT NULL,
  `counter` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_countries`;
CREATE TABLE IF NOT EXISTS `toc_countries` (
  `countries_id` int(11) NOT NULL AUTO_INCREMENT,
  `countries_name` varchar(64) NOT NULL,
  `countries_iso_code_2` char(2) NOT NULL,
  `countries_iso_code_3` char(3) NOT NULL,
  `address_format` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`countries_id`),
  KEY `IDX_COUNTRIES_NAME` (`countries_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_coupons`;
CREATE TABLE IF NOT EXISTS `toc_coupons` (
  `coupons_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupons_type` int(1) NOT NULL,
  `coupons_status` tinyint(1) NOT NULL,
  `coupons_include_tax` tinyint(1) NOT NULL,
  `coupons_include_shipping` tinyint(1) NOT NULL,
  `coupons_code` varchar(32) NOT NULL,
  `coupons_amount` decimal(8,4) NOT NULL,
  `coupons_minimum_order` decimal(8,4) NOT NULL,
  `uses_per_coupon` int(11) NOT NULL,
  `uses_per_customer` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `expires_date` datetime DEFAULT NULL,
  `coupons_date_created` datetime DEFAULT NULL,
  `coupons_date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`coupons_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_coupons_description`;
CREATE TABLE IF NOT EXISTS `toc_coupons_description` (
  `coupons_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `coupons_name` varchar(32) NOT NULL,
  `coupons_description` text NOT NULL,
  PRIMARY KEY (`coupons_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_coupons_redeem_history`;
CREATE TABLE IF NOT EXISTS `toc_coupons_redeem_history` (
  `coupons_redeem_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupons_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL,
  `redeem_amount` decimal(15,4) NOT NULL,
  `redeem_date` datetime DEFAULT NULL,
  `redeem_ip_address` varchar(15) NOT NULL,
  PRIMARY KEY (`coupons_redeem_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_coupons_to_categories`;
CREATE TABLE IF NOT EXISTS `toc_coupons_to_categories` (
  `coupons_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  PRIMARY KEY (`coupons_id`,`categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_coupons_to_products`;
CREATE TABLE IF NOT EXISTS `toc_coupons_to_products` (
  `coupons_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  PRIMARY KEY (`coupons_id`,`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_credit_cards`;
CREATE TABLE IF NOT EXISTS `toc_credit_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `credit_card_name` varchar(32) NOT NULL,
  `pattern` varchar(64) NOT NULL,
  `credit_card_status` char(1) NOT NULL,
  `sort_order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_currencies`;
CREATE TABLE IF NOT EXISTS `toc_currencies` (
  `currencies_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `code` char(3) NOT NULL,
  `symbol_left` varchar(12) DEFAULT NULL,
  `symbol_right` varchar(12) DEFAULT NULL,
  `decimal_places` char(1) DEFAULT NULL,
  `value` float(13,8) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`currencies_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_customers`;
CREATE TABLE IF NOT EXISTS `toc_customers` (
  `customers_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_groups_id` int(11) DEFAULT NULL,
  `customers_gender` char(1) DEFAULT NULL,
  `customers_firstname` varchar(32) NOT NULL,
  `customers_lastname` varchar(32) NOT NULL,
  `customers_dob` datetime DEFAULT NULL,
  `customers_email_address` varchar(96) NOT NULL,
  `customers_default_address_id` int(11) DEFAULT NULL,
  `customers_telephone` varchar(32) DEFAULT NULL,
  `customers_fax` varchar(32) DEFAULT NULL,
  `customers_password` varchar(40) DEFAULT NULL,
  `customers_newsletter` char(1) DEFAULT NULL,
  `customers_status` int(1) DEFAULT '0',
  `customers_ip_address` varchar(15) DEFAULT NULL,
  `customers_credits` decimal(15,4) DEFAULT '0.0000',
  `date_last_logon` datetime DEFAULT NULL,
  `number_of_logons` int(5) DEFAULT NULL,
  `date_account_created` datetime DEFAULT NULL,
  `date_account_last_modified` datetime DEFAULT NULL,
  `global_product_notifications` int(1) DEFAULT '0',
  `abandoned_cart_last_contact_date` datetime DEFAULT NULL,
  PRIMARY KEY (`customers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_customers_basket`;
CREATE TABLE IF NOT EXISTS `toc_customers_basket` (
  `customers_basket_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL,
  `products_id` tinytext NOT NULL,
  `customers_basket_quantity` int(11) NOT NULL,
  `gift_certificates_data` text,
  `customizations` text,
  `final_price` decimal(15,4) NOT NULL,
  `customers_basket_date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`customers_basket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_customers_credits_history`;
CREATE TABLE IF NOT EXISTS `toc_customers_credits_history` (
  `customers_credits_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `action_type` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`customers_credits_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_customers_groups`;
CREATE TABLE IF NOT EXISTS `toc_customers_groups` (
  `customers_groups_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_groups_discount` int(11) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`customers_groups_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_customers_groups_description`;
CREATE TABLE IF NOT EXISTS `toc_customers_groups_description` (
  `customers_groups_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `customers_groups_name` varchar(255) NOT NULL,
  PRIMARY KEY (`customers_groups_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_customers_ratings`;
CREATE TABLE IF NOT EXISTS `toc_customers_ratings` (
  `customers_ratings_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) NOT NULL,
  `reviews_id` int(11) NOT NULL,
  `ratings_id` int(11) NOT NULL,
  `ratings_value` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`customers_ratings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_customization_fields`;
CREATE TABLE IF NOT EXISTS `toc_customization_fields` (
  `customization_fields_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `is_required` tinyint(1) NOT NULL,
  PRIMARY KEY (`customization_fields_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_customization_fields_description`;
CREATE TABLE IF NOT EXISTS `toc_customization_fields_description` (
  `customization_fields_id` int(11) NOT NULL,
  `languages_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`customization_fields_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_departments`;
CREATE TABLE IF NOT EXISTS `toc_departments` (
  `departments_id` int(11) NOT NULL AUTO_INCREMENT,
  `departments_email_address` varchar(96) NOT NULL,
  PRIMARY KEY (`departments_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_departments_description`;
CREATE TABLE IF NOT EXISTS `toc_departments_description` (
  `departments_id` int(11) NOT NULL AUTO_INCREMENT,
  `languages_id` int(11) NOT NULL,
  `departments_title` varchar(64) NOT NULL DEFAULT '',
  `departments_description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`departments_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_email_templates`;
CREATE TABLE IF NOT EXISTS `toc_email_templates` (
  `email_templates_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_templates_name` varchar(100) NOT NULL,
  `email_templates_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`email_templates_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_email_templates_description`;
CREATE TABLE IF NOT EXISTS `toc_email_templates_description` (
  `email_templates_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `email_title` varchar(255) NOT NULL,
  `email_content` text NOT NULL,
  PRIMARY KEY (`email_templates_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_extensions`;
CREATE TABLE IF NOT EXISTS `toc_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `code` varchar(32) NOT NULL,
  `author_name` varchar(64) NOT NULL,
  `author_www` varchar(255) DEFAULT NULL,
  `modules_group` varchar(32) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_faqs`;
CREATE TABLE IF NOT EXISTS `toc_faqs` (
  `faqs_id` int(11) NOT NULL AUTO_INCREMENT,
  `faqs_status` tinyint(1) NOT NULL,
  `faqs_order` int(10) NOT NULL,
  `faqs_date_added` datetime NOT NULL,
  `faqs_last_modified` datetime NOT NULL,
  PRIMARY KEY (`faqs_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_faqs_description`;
CREATE TABLE IF NOT EXISTS `toc_faqs_description` (
  `faqs_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `faqs_question` varchar(255) NOT NULL,
  `faqs_url` varchar(255) NOT NULL,
  `faqs_answer` text NOT NULL,
  PRIMARY KEY (`faqs_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_geo_zones`;
CREATE TABLE IF NOT EXISTS `toc_geo_zones` (
  `geo_zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `geo_zone_name` varchar(32) NOT NULL,
  `geo_zone_description` varchar(255) NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`geo_zone_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_gift_certificates`;
CREATE TABLE IF NOT EXISTS `toc_gift_certificates` (
  `gift_certificates_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `orders_products_id` int(11) NOT NULL,
  `gift_certificates_type` int(5) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `amount` decimal(15,4) NOT NULL,
  `gift_certificates_code` varchar(32) NOT NULL,
  `senders_name` varchar(64) NOT NULL,
  `senders_email` varchar(96) NOT NULL,
  `recipients_name` varchar(64) NOT NULL,
  `recipients_email` varchar(96) NOT NULL,
  `messages` text NOT NULL,
  PRIMARY KEY (`gift_certificates_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_gift_certificates_redeem_history`;
CREATE TABLE IF NOT EXISTS `toc_gift_certificates_redeem_history` (
  `gift_certificates_redeem_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `gift_certificates_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL,
  `orders_id` int(11) NOT NULL,
  `redeem_date` datetime NOT NULL,
  `redeem_amount` decimal(15,4) NOT NULL,
  `redeem_ip_address` varchar(15) NOT NULL,
  PRIMARY KEY (`gift_certificates_redeem_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_guest_books`;
CREATE TABLE IF NOT EXISTS `toc_guest_books` (
  `guest_books_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `guest_books_status` tinyint(1) NOT NULL,
  `languages_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`guest_books_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_languages`;
CREATE TABLE IF NOT EXISTS `toc_languages` (
  `languages_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `code` char(5) NOT NULL,
  `locale` varchar(255) NOT NULL,
  `charset` varchar(32) NOT NULL,
  `date_format_short` varchar(32) NOT NULL,
  `date_format_long` varchar(32) NOT NULL,
  `time_format` varchar(32) NOT NULL,
  `text_direction` varchar(12) NOT NULL,
  `currencies_id` int(11) NOT NULL,
  `numeric_separator_decimal` varchar(12) NOT NULL,
  `numeric_separator_thousands` varchar(12) NOT NULL,
  `parent_id` int(11) DEFAULT '0',
  `sort_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`languages_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_languages_definitions`;
CREATE TABLE IF NOT EXISTS `toc_languages_definitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `languages_id` int(11) NOT NULL,
  `content_group` varchar(32) NOT NULL,
  `definition_key` varchar(255) NOT NULL,
  `definition_value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_LANGUAGES_DEFINITIONS_LANGUAGES` (`languages_id`),
  KEY `IDX_LANGUAGES_DEFINITIONS` (`languages_id`,`content_group`),
  KEY `IDX_LANGUAGES_DEFINITIONS_GROUPS` (`content_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_manufacturers`;
CREATE TABLE IF NOT EXISTS `toc_manufacturers` (
  `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturers_name` varchar(32) NOT NULL,
  `manufacturers_image` varchar(64) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`manufacturers_id`),
  KEY `IDX_MANUFACTURERS_NAME` (`manufacturers_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_manufacturers_info`;
CREATE TABLE IF NOT EXISTS `toc_manufacturers_info` (
  `manufacturers_id` int(11) NOT NULL,
  `languages_id` int(11) NOT NULL,
  `manufacturers_url` varchar(255) NOT NULL,
  `manufacturers_friendly_url` varchar(64) NOT NULL,
  `manufacturers_page_title` varchar(255) NOT NULL,
  `manufacturers_meta_keywords` varchar(255) NOT NULL,
  `manufacturers_meta_description` varchar(255) NOT NULL,
  `url_clicked` int(5) NOT NULL DEFAULT '0',
  `date_last_click` datetime DEFAULT NULL,
  PRIMARY KEY (`manufacturers_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_newsletters`;
CREATE TABLE IF NOT EXISTS `toc_newsletters` (
  `newsletters_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `module` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_sent` datetime DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `locked` int(1) DEFAULT '0',
  PRIMARY KEY (`newsletters_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_newsletters_log`;
CREATE TABLE IF NOT EXISTS `toc_newsletters_log` (
  `newsletters_id` int(11) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `date_sent` datetime DEFAULT NULL,
  KEY `IDX_NEWSLETTERS_LOG_NEWSLETTERS_ID` (`newsletters_id`),
  KEY `IDX_NEWSLETTERS_LOG_EMAIL_ADDRESS` (`email_address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders`;
CREATE TABLE IF NOT EXISTS `toc_orders` (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(10) DEFAULT NULL,
  `customers_id` int(11) NOT NULL,
  `customers_name` varchar(64) NOT NULL,
  `customers_company` varchar(32) DEFAULT NULL,
  `customers_street_address` varchar(64) NOT NULL,
  `customers_suburb` varchar(32) DEFAULT NULL,
  `customers_city` varchar(32) NOT NULL,
  `customers_postcode` varchar(10) NOT NULL,
  `customers_state` varchar(32) DEFAULT NULL,
  `customers_state_code` varchar(32) DEFAULT NULL,
  `customers_country` varchar(64) NOT NULL,
  `customers_country_iso2` char(2) NOT NULL,
  `customers_country_iso3` char(3) NOT NULL,
  `customers_telephone` varchar(32) NOT NULL,
  `customers_email_address` varchar(96) NOT NULL,
  `customers_address_format` varchar(255) NOT NULL,
  `customers_ip_address` varchar(15) DEFAULT NULL,
  `delivery_name` varchar(64) NOT NULL,
  `delivery_company` varchar(32) DEFAULT NULL,
  `delivery_street_address` varchar(64) NOT NULL,
  `delivery_suburb` varchar(32) DEFAULT NULL,
  `delivery_city` varchar(32) NOT NULL,
  `delivery_postcode` varchar(10) NOT NULL,
  `delivery_state` varchar(32) DEFAULT NULL,
  `delivery_zone_id` int(11) NOT NULL,
  `delivery_state_code` varchar(32) DEFAULT NULL,
  `delivery_country_id` int(11) NOT NULL,
  `delivery_country` varchar(64) NOT NULL,
  `delivery_country_iso2` char(2) NOT NULL,
  `delivery_country_iso3` char(3) NOT NULL,
  `delivery_address_format` varchar(255) NOT NULL,
  `delivery_telephone` varchar(32) NOT NULL,
  `billing_name` varchar(64) NOT NULL,
  `billing_company` varchar(32) DEFAULT NULL,
  `billing_street_address` varchar(64) NOT NULL,
  `billing_suburb` varchar(32) DEFAULT NULL,
  `billing_city` varchar(32) NOT NULL,
  `billing_postcode` varchar(10) NOT NULL,
  `billing_state` varchar(32) DEFAULT NULL,
  `billing_zone_id` int(11) NOT NULL,
  `billing_state_code` varchar(32) DEFAULT NULL,
  `billing_country_id` int(11) NOT NULL,
  `billing_country` varchar(64) NOT NULL,
  `billing_country_iso2` char(2) NOT NULL,
  `billing_country_iso3` char(3) NOT NULL,
  `billing_address_format` varchar(255) NOT NULL,
  `billing_telephone` varchar(32) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_module` varchar(255) NOT NULL,
  `uses_store_credit` tinyint(1) NOT NULL,
  `store_credit_amount` decimal(15,4) NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_purchased` datetime DEFAULT NULL,
  `orders_status` int(5) NOT NULL,
  `customers_comment` text,
  `admin_comment` text,
  `orders_date_finished` datetime DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `currency_value` decimal(14,6) DEFAULT NULL,
  `invoice_date` datetime DEFAULT NULL,
  `tracking_no` varchar(64) DEFAULT NULL,
  `gift_wrapping` tinyint(1) NOT NULL,
  `wrapping_message` text NOT NULL,
  PRIMARY KEY (`orders_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_products`;
CREATE TABLE IF NOT EXISTS `toc_orders_products` (
  `orders_products_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `products_type` int(4) NOT NULL DEFAULT '0',
  `products_sku` varchar(64) DEFAULT NULL,
  `products_name` varchar(255) NOT NULL,
  `products_price` decimal(15,4) NOT NULL,
  `final_price` decimal(15,4) NOT NULL,
  `products_tax` decimal(7,4) NOT NULL,
  `products_quantity` int(2) NOT NULL,
  `products_return_quantity` int(2) NOT NULL,
  PRIMARY KEY (`orders_products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_products_customizations`;
CREATE TABLE IF NOT EXISTS `toc_orders_products_customizations` (
  `orders_products_customizations_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `orders_products_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`orders_products_customizations_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_products_customizations_values`;
CREATE TABLE IF NOT EXISTS `toc_orders_products_customizations_values` (
  `orders_products_customizations_values_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_products_customizations_id` int(11) NOT NULL,
  `customization_fields_id` int(11) NOT NULL,
  `customization_fields_name` varchar(64) NOT NULL,
  `customization_fields_type` tinyint(1) NOT NULL,
  `customization_fields_value` varchar(255) NOT NULL,
  `cache_file_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`orders_products_customizations_values_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_products_download`;
CREATE TABLE IF NOT EXISTS `toc_orders_products_download` (
  `orders_products_download_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `orders_products_id` int(11) NOT NULL,
  `orders_products_filename` varchar(255) NOT NULL,
  `orders_products_cache_filename` varchar(255) NOT NULL,
  `download_maxdays` int(2) NOT NULL,
  `download_count` int(2) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`orders_products_download_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_products_variants`;
CREATE TABLE IF NOT EXISTS `toc_orders_products_variants` (
  `orders_products_variants_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `orders_products_id` int(11) NOT NULL,
  `products_variants_groups_id` int(11) NOT NULL,
  `products_variants_groups` varchar(32) NOT NULL,
  `products_variants_values_id` int(11) NOT NULL,
  `products_variants_values` varchar(32) NOT NULL,
  PRIMARY KEY (`orders_products_variants_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_refunds`;
CREATE TABLE IF NOT EXISTS `toc_orders_refunds` (
  `orders_refunds_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_refunds_type` tinyint(1) NOT NULL,
  `orders_id` int(11) NOT NULL,
  `credit_slips_id` int(11) DEFAULT NULL,
  `sub_total` decimal(15,4) NOT NULL,
  `shipping` decimal(15,4) NOT NULL,
  `handling` decimal(15,4) NOT NULL,
  `refund_total` decimal(15,4) NOT NULL,
  `comments` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`orders_refunds_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_refunds_products`;
CREATE TABLE IF NOT EXISTS `toc_orders_refunds_products` (
  `orders_refunds_products_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_refunds_id` int(11) NOT NULL,
  `orders_products_id` int(11) NOT NULL,
  `products_quantity` int(11) NOT NULL,
  PRIMARY KEY (`orders_refunds_products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_returns`;
CREATE TABLE IF NOT EXISTS `toc_orders_returns` (
  `orders_returns_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `orders_returns_status_id` int(5) NOT NULL,
  `customers_comments` text,
  `admin_comments` text,
  `date_added` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`orders_returns_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_returns_products`;
CREATE TABLE IF NOT EXISTS `toc_orders_returns_products` (
  `orders_returns_id` int(11) NOT NULL,
  `orders_products_id` int(11) NOT NULL,
  `products_quantity` int(11) NOT NULL,
  PRIMARY KEY (`orders_returns_id`,`orders_products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_returns_status`;
CREATE TABLE IF NOT EXISTS `toc_orders_returns_status` (
  `orders_returns_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `languages_id` int(11) NOT NULL,
  `orders_returns_status_name` varchar(100) NOT NULL,
  PRIMARY KEY (`orders_returns_status_id`,`languages_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_status`;
CREATE TABLE IF NOT EXISTS `toc_orders_status` (
  `orders_status_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `orders_status_name` varchar(32) NOT NULL,
  `public_flag` tinyint(1) NOT NULL DEFAULT '1',
  `downloads_flag` tinyint(1) NOT NULL DEFAULT '0',
  `returns_flag` tinyint(1) NOT NULL DEFAULT '0',
  `gift_certificates_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`orders_status_id`,`language_id`),
  KEY `idx_orders_status_name` (`orders_status_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_status_history`;
CREATE TABLE IF NOT EXISTS `toc_orders_status_history` (
  `orders_status_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `orders_status_id` int(5) NOT NULL,
  `date_added` datetime NOT NULL,
  `customer_notified` int(1) DEFAULT '0',
  `comments` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`orders_status_history_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_total`;
CREATE TABLE IF NOT EXISTS `toc_orders_total` (
  `orders_total_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `value` decimal(15,4) NOT NULL,
  `class` varchar(32) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`orders_total_id`),
  KEY `idx_orders_total_orders_id` (`orders_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_transactions_history`;
CREATE TABLE IF NOT EXISTS `toc_orders_transactions_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orders_id` int(10) unsigned NOT NULL,
  `transaction_code` int(11) NOT NULL,
  `transaction_return_value` text NOT NULL,
  `transaction_return_status` int(11) NOT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_orders_transactions_history_orders_id` (`orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_orders_transactions_status`;
CREATE TABLE IF NOT EXISTS `toc_orders_transactions_status` (
  `id` int(10) unsigned NOT NULL,
  `language_id` int(10) unsigned NOT NULL,
  `status_name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`,`language_id`),
  KEY `idx_orders_transactions_status_name` (`status_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_polls`;
CREATE TABLE IF NOT EXISTS `toc_polls` (
  `polls_id` int(11) NOT NULL AUTO_INCREMENT,
  `polls_type` tinyint(1) NOT NULL,
  `polls_status` tinyint(1) NOT NULL,
  `votes_count` int(11) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`polls_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_polls_answers`;
CREATE TABLE IF NOT EXISTS `toc_polls_answers` (
  `polls_answers_id` int(11) NOT NULL AUTO_INCREMENT,
  `polls_id` int(10) NOT NULL,
  `votes_count` int(10) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`polls_answers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_polls_answers_description`;
CREATE TABLE IF NOT EXISTS `toc_polls_answers_description` (
  `polls_answers_id` int(11) NOT NULL,
  `languages_id` int(11) NOT NULL,
  `answers_title` varchar(255) NOT NULL,
  PRIMARY KEY (`polls_answers_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_polls_description`;
CREATE TABLE IF NOT EXISTS `toc_polls_description` (
  `polls_id` int(11) NOT NULL,
  `polls_title` varchar(255) NOT NULL,
  `languages_id` int(11) NOT NULL,
  PRIMARY KEY (`polls_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_polls_votes`;
CREATE TABLE IF NOT EXISTS `toc_polls_votes` (
  `polls_votes_id` int(11) NOT NULL AUTO_INCREMENT,
  `polls_id` int(11) NOT NULL,
  `polls_answers_id` int(11) NOT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `date_voted` datetime NOT NULL,
  `customers_ip_address` varchar(32) NOT NULL,
  PRIMARY KEY (`polls_votes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products`;
CREATE TABLE IF NOT EXISTS `toc_products` (
  `products_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_type` int(4) NOT NULL DEFAULT '0',
  `products_quantity` int(4) NOT NULL DEFAULT '1',
  `products_moq` int(11) NOT NULL DEFAULT '1',
  `products_max_order_quantity` int(11) NOT NULL DEFAULT '-1',
  `products_price` decimal(15,4) NOT NULL,
  `products_sku` varchar(64) NOT NULL,
  `products_model` varchar(64) NOT NULL,
  `products_date_added` datetime NOT NULL,
  `products_last_modified` datetime DEFAULT NULL,
  `products_date_available` datetime DEFAULT NULL,
  `products_weight` decimal(5,2) NOT NULL,
  `products_weight_class` int(11) NOT NULL,
  `products_status` tinyint(1) NOT NULL,
  `products_tax_class_id` int(11) NOT NULL,
  `manufacturers_id` int(11) DEFAULT NULL,
  `products_ordered` int(11) NOT NULL DEFAULT '0',
  `quantity_discount_groups_id` int(11) DEFAULT NULL,
  `quantity_unit_class` int(11) NOT NULL,
  `order_increment` int(11) NOT NULL DEFAULT '1',
  `products_attributes_groups_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`products_id`),
  KEY `idx_products_date_added` (`products_date_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_accessories`;
CREATE TABLE IF NOT EXISTS `toc_products_accessories` (
  `products_id` int(11) NOT NULL,
  `accessories_id` int(11) NOT NULL,
  PRIMARY KEY (`products_id`,`accessories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_attachments`;
CREATE TABLE IF NOT EXISTS `toc_products_attachments` (
  `attachments_id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(128) NOT NULL,
  `cache_filename` varchar(128) NOT NULL,
  PRIMARY KEY (`attachments_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_attachments_description`;
CREATE TABLE IF NOT EXISTS `toc_products_attachments_description` (
  `attachments_id` int(11) NOT NULL,
  `languages_id` int(11) NOT NULL DEFAULT '1',
  `attachments_name` varchar(128) NOT NULL,
  `attachments_description` text,
  PRIMARY KEY (`attachments_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_attachments_to_products`;
CREATE TABLE IF NOT EXISTS `toc_products_attachments_to_products` (
  `attachments_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  PRIMARY KEY (`attachments_id`,`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_attributes`;
CREATE TABLE IF NOT EXISTS `toc_products_attributes` (
  `products_id` int(11) NOT NULL,
  `products_attributes_values_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`products_id`,`products_attributes_values_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_attributes_groups`;
CREATE TABLE IF NOT EXISTS `toc_products_attributes_groups` (
  `products_attributes_groups_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_attributes_groups_name` varchar(100) NOT NULL,
  PRIMARY KEY (`products_attributes_groups_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_attributes_values`;
CREATE TABLE IF NOT EXISTS `toc_products_attributes_values` (
  `products_attributes_values_id` int(11) NOT NULL,
  `products_attributes_groups_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `status` int(4) NOT NULL,
  `module` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`products_attributes_values_id`,`products_attributes_groups_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_description`;
CREATE TABLE IF NOT EXISTS `toc_products_description` (
  `products_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_name` varchar(255) NOT NULL DEFAULT '',
  `products_short_description` text,
  `products_description` text,
  `products_keyword` varchar(64) DEFAULT NULL,
  `products_tags` varchar(255) DEFAULT NULL,
  `products_url` varchar(255) DEFAULT NULL,
  `products_friendly_url` varchar(255) DEFAULT NULL,
  `products_page_title` varchar(255) NOT NULL,
  `products_meta_keywords` varchar(255) NOT NULL,
  `products_meta_description` varchar(255) NOT NULL,
  `products_viewed` int(5) DEFAULT '0',
  PRIMARY KEY (`products_id`,`language_id`),
  KEY `products_name` (`products_name`),
  KEY `products_description_keyword` (`products_keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_downloadables`;
CREATE TABLE IF NOT EXISTS `toc_products_downloadables` (
  `products_id` int(11) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `cache_filename` varchar(100) NOT NULL,
  `sample_filename` varchar(100) NOT NULL,
  `cache_sample_filename` varchar(100) NOT NULL,
  `number_of_downloads` int(11) NOT NULL,
  `number_of_accessible_days` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_download_history`;
CREATE TABLE IF NOT EXISTS `toc_products_download_history` (
  `products_download_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_products_download_id` int(11) NOT NULL,
  `download_date` datetime NOT NULL,
  `download_ip_address` varchar(15) NOT NULL,
  PRIMARY KEY (`products_download_history_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_frontpage`;
CREATE TABLE IF NOT EXISTS `toc_products_frontpage` (
  `products_id` int(11) NOT NULL,
  `sort_order` int(3) DEFAULT NULL,
  PRIMARY KEY (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_gift_certificates`;
CREATE TABLE IF NOT EXISTS `toc_products_gift_certificates` (
  `products_id` int(11) NOT NULL,
  `gift_certificates_type` int(5) NOT NULL,
  `gift_certificates_amount_type` int(5) NOT NULL,
  `open_amount_min_value` decimal(15,4) NOT NULL,
  `open_amount_max_value` decimal(15,4) NOT NULL,
  PRIMARY KEY (`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_images`;
CREATE TABLE IF NOT EXISTS `toc_products_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `default_flag` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `products_images_products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_images_groups`;
CREATE TABLE IF NOT EXISTS `toc_products_images_groups` (
  `id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `code` varchar(32) NOT NULL,
  `size_width` int(11) DEFAULT NULL,
  `size_height` int(11) DEFAULT NULL,
  `force_size` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_notifications`;
CREATE TABLE IF NOT EXISTS `toc_products_notifications` (
  `products_id` int(11) NOT NULL,
  `customers_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`products_id`,`customers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_to_categories`;
CREATE TABLE IF NOT EXISTS `toc_products_to_categories` (
  `products_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  PRIMARY KEY (`products_id`,`categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_variants`;
CREATE TABLE IF NOT EXISTS `toc_products_variants` (
  `products_variants_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `products_id` int(11) NOT NULL DEFAULT '0',
  `products_images_id` int(11) DEFAULT NULL,
  `products_status` tinyint(1) NOT NULL,
  `products_price` decimal(15,4) NOT NULL,
  `products_sku` varchar(64) NOT NULL,
  `products_model` varchar(255) NOT NULL,
  `products_quantity` int(4) NOT NULL,
  `products_weight` decimal(5,2) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `cache_filename` varchar(100) NOT NULL,
  PRIMARY KEY (`products_variants_id`),
  KEY `idx_products_variants_products_id` (`products_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_variants_entries`;
CREATE TABLE IF NOT EXISTS `toc_products_variants_entries` (
  `products_variants_entries_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_variants_id` int(11) NOT NULL,
  `products_variants_groups_id` int(11) NOT NULL,
  `products_variants_values_id` int(11) NOT NULL,
  PRIMARY KEY (`products_variants_entries_id`),
  KEY `idx_products_variants_groups_values_id` (`products_variants_id`,`products_variants_groups_id`,`products_variants_values_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_variants_groups`;
CREATE TABLE IF NOT EXISTS `toc_products_variants_groups` (
  `products_variants_groups_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_variants_groups_name` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`products_variants_groups_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_variants_values`;
CREATE TABLE IF NOT EXISTS `toc_products_variants_values` (
  `products_variants_values_id` int(11) NOT NULL DEFAULT '0',
  `language_id` int(11) NOT NULL DEFAULT '1',
  `products_variants_values_name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`products_variants_values_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_variants_values_to_products_variants_groups`;
CREATE TABLE IF NOT EXISTS `toc_products_variants_values_to_products_variants_groups` (
  `products_variants_values_to_products_variants_groups_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_variants_groups_id` int(11) NOT NULL,
  `products_variants_values_id` int(11) NOT NULL,
  PRIMARY KEY (`products_variants_values_to_products_variants_groups_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS toc_products_to_categories;
CREATE TABLE toc_products_to_categories (
  products_id int(11) NOT NULL,
  categories_id int(11) NOT NULL,
  PRIMARY KEY  (products_id,categories_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_products_xsell`;
CREATE TABLE IF NOT EXISTS `toc_products_xsell` (
  `products_id` int(10) unsigned NOT NULL DEFAULT '1',
  `xsell_products_id` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`products_id`,`xsell_products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_quantity_discount_groups`;
CREATE TABLE IF NOT EXISTS `toc_quantity_discount_groups` (
  `quantity_discount_groups_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity_discount_groups_name` varchar(128) NOT NULL,
  PRIMARY KEY (`quantity_discount_groups_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_quantity_discount_groups_values`;
CREATE TABLE IF NOT EXISTS `toc_quantity_discount_groups_values` (
  `quantity_discount_groups_values_id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity_discount_groups_id` int(11) NOT NULL,
  `customers_groups_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  PRIMARY KEY (`quantity_discount_groups_values_id`),
  KEY `quantity_discount_groups_id` (`quantity_discount_groups_id`),
  KEY `customers_groups_id` (`customers_groups_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_quantity_unit_classes`;
CREATE TABLE IF NOT EXISTS `toc_quantity_unit_classes` (
  `quantity_unit_class_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `quantity_unit_class_title` varchar(32) NOT NULL,
  PRIMARY KEY (`quantity_unit_class_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_ratings`;
CREATE TABLE IF NOT EXISTS `toc_ratings` (
  `ratings_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`ratings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_ratings_description`;
CREATE TABLE IF NOT EXISTS `toc_ratings_description` (
  `ratings_id` int(11) NOT NULL,
  `languages_id` int(11) NOT NULL,
  `ratings_text` varchar(64) NOT NULL,
  PRIMARY KEY (`ratings_id`,`languages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_reviews`;
CREATE TABLE IF NOT EXISTS `toc_reviews` (
  `reviews_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL,
  `customers_id` int(11) DEFAULT NULL,
  `customers_name` varchar(64) NOT NULL,
  `reviews_rating` int(1) DEFAULT NULL,
  `languages_id` int(11) NOT NULL,
  `reviews_text` text NOT NULL,
  `date_added` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `reviews_read` int(5) NOT NULL DEFAULT '0',
  `reviews_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`reviews_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_search_terms`;
CREATE TABLE IF NOT EXISTS `toc_search_terms` (
  `search_terms_id` int(10) NOT NULL AUTO_INCREMENT,
  `text` varchar(128) NOT NULL,
  `products_count` int(10) NOT NULL,
  `search_count` int(10) NOT NULL,
  `synonym` varchar(128) NOT NULL,
  `show_in_terms` tinyint(1) NOT NULL,
  `date_updated` datetime NOT NULL,
  PRIMARY KEY (`search_terms_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_sessions`;
CREATE TABLE IF NOT EXISTS `toc_sessions` (
  `session_id` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `user_agent` varchar(120) CHARACTER SET utf8 NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET utf8,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_slide_images`;
CREATE TABLE IF NOT EXISTS `toc_slide_images` (
  `image_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `group` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_url` char(255) NOT NULL,
  `sort_order` int(5) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`image_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_specials`;
CREATE TABLE IF NOT EXISTS `toc_specials` (
  `specials_id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL,
  `specials_new_products_price` decimal(15,4) NOT NULL,
  `specials_date_added` datetime DEFAULT NULL,
  `specials_last_modified` datetime DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `expires_date` datetime DEFAULT NULL,
  `date_status_change` datetime DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`specials_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_tax_class`;
CREATE TABLE IF NOT EXISTS `toc_tax_class` (
  `tax_class_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_class_title` varchar(32) NOT NULL,
  `tax_class_description` varchar(255) NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`tax_class_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_tax_rates`;
CREATE TABLE IF NOT EXISTS `toc_tax_rates` (
  `tax_rates_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_zone_id` int(11) NOT NULL,
  `tax_class_id` int(11) NOT NULL,
  `tax_priority` int(5) DEFAULT '1',
  `tax_rate` decimal(7,4) NOT NULL,
  `tax_description` varchar(255) NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`tax_rates_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_templates`;
CREATE TABLE IF NOT EXISTS `toc_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `code` varchar(32) NOT NULL,
  `author_name` varchar(64) NOT NULL,
  `author_www` varchar(255) DEFAULT NULL,
  `markup_version` varchar(32) DEFAULT NULL,
  `css_based` tinyint(4) DEFAULT NULL,
  `medium` varchar(32) DEFAULT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_templates_boxes`;
CREATE TABLE IF NOT EXISTS `toc_templates_boxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `code` varchar(32) NOT NULL,
  `author_name` varchar(64) NOT NULL,
  `author_www` varchar(255) DEFAULT NULL,
  `modules_group` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_templates_boxes_to_pages`;
CREATE TABLE IF NOT EXISTS `toc_templates_boxes_to_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templates_boxes_id` int(11) NOT NULL,
  `templates_id` int(11) NOT NULL,
  `content_page` varchar(255) NOT NULL,
  `boxes_group` varchar(32) NOT NULL,
  `sort_order` int(11) DEFAULT '0',
  `page_specific` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `templates_boxes_id` (`templates_boxes_id`,`templates_id`,`content_page`,`boxes_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_templates_modules`;
CREATE TABLE IF NOT EXISTS `toc_templates_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `templates_id` int(11) NOT NULL,
  `module` varchar(45) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `content_page` varchar(255) NOT NULL,
  `content_group` varchar(32) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `page_specific` tinyint(4) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_weight_classes`;
CREATE TABLE IF NOT EXISTS `toc_weight_classes` (
  `weight_class_id` int(11) NOT NULL DEFAULT '0',
  `weight_class_key` varchar(4) NOT NULL DEFAULT '',
  `language_id` int(11) NOT NULL DEFAULT '0',
  `weight_class_title` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`weight_class_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_weight_classes_rules`;
CREATE TABLE IF NOT EXISTS `toc_weight_classes_rules` (
  `weight_class_from_id` int(11) NOT NULL DEFAULT '0',
  `weight_class_to_id` int(11) NOT NULL DEFAULT '0',
  `weight_class_rule` decimal(15,4) NOT NULL DEFAULT '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_whos_online`;
CREATE TABLE IF NOT EXISTS `toc_whos_online` (
  `customer_id` int(11) DEFAULT NULL,
  `full_name` varchar(64) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `time_entry` varchar(14) NOT NULL,
  `time_last_click` varchar(14) NOT NULL,
  `last_page_url` varchar(255) NOT NULL,
  `referrer_url` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_wishlists`;
CREATE TABLE IF NOT EXISTS `toc_wishlists` (
  `wishlists_id` int(11) NOT NULL AUTO_INCREMENT,
  `customers_id` int(11) DEFAULT NULL,
  `wishlists_token` varchar(32) NOT NULL,
  PRIMARY KEY (`wishlists_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_wishlists_products`;
CREATE TABLE IF NOT EXISTS `toc_wishlists_products` (
  `wishlists_products_id` int(11) NOT NULL AUTO_INCREMENT,
  `wishlists_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`wishlists_products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS toc_wishlists_products_variants;
create table toc_wishlists_products_variants(
  wishlists_products_variants_id int(11) not null AUTO_INCREMENT,
  whishlists_id int(11) not null,
  whishlists_products_id int(11) not null,
  products_variants_groups_id int(11) not null,
  products_variants_groups varchar(32) not null,
  products_variants_values_id int(11) not null,
  products_variants_values varchar(32) not null,
  primary key (wishlists_products_variants_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_zones`;
CREATE TABLE IF NOT EXISTS `toc_zones` (
  `zone_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_country_id` int(11) NOT NULL,
  `zone_code` varchar(32) NOT NULL,
  `zone_name` varchar(64) NOT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `toc_zones_to_geo_zones`;
CREATE TABLE IF NOT EXISTS `toc_zones_to_geo_zones` (
  `association_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_country_id` int(11) NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `geo_zone_id` int(11) DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`association_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Name', 'STORE_NAME', 'TomatoCart', 'The name of my store', '1', '1', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Store Owner', 'STORE_OWNER', 'Store Owner', 'The name of my store owner', '1', '2', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail Address', 'STORE_OWNER_EMAIL_ADDRESS', 'root@localhost', 'The e-mail address of my store owner', '1', '3', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('E-Mail From', 'EMAIL_FROM', '"Store Owner" <root@localhost>', 'The e-mail address used in (sent) e-mails', '1', '4', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Country', 'STORE_COUNTRY', '223', 'The country my store is located in <br><br><b>Note: Please remember to update the store zone.</b>', '1', '6', 'TOC_Address::getCountryName', 'cfg_set_countries_pulldown_menu', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Zone', 'STORE_ZONE', '', 'The zone my store is located in', '1', '7', 'TOC_Address::getZoneName', 'cfg_set_zones_pulldown_menu', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Timezone(Get it at www.php.net/manual/en/timezones.php)', 'STORE_TIME_ZONE', '', 'The timezone my store is located in', '1', '8', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Send Extra Order Emails To', 'SEND_EXTRA_ORDER_EMAILS_TO', '', 'Send extra order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;, Name 2 &lt;email@address2&gt;', '1', '11', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Allow Guest To Tell A Friend', 'ALLOW_GUEST_TO_TELL_A_FRIEND', '-1', 'Allow guests to tell a friend about a product', '1', '15', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Store Address and Phone', 'STORE_NAME_ADDRESS', 'Store Name\nAddress\nCountry\nPhone', 'This is the Store Name, Address and Phone used on printable documents and displayed online', '1', '18', 'cfg_set_textarea_field', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Tax Decimal Places', 'TAX_DECIMAL_PLACES', '0', 'Pad the tax value this amount of decimal places', '1', '20', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display Prices with Tax', 'DISPLAY_PRICE_WITH_TAX', '1', 'Display prices with tax included (true) or add the tax at the end (false)', '1', '21', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Invoice Start Number', 'INVOICE_START_NUMBER', '10000', 'Invoices would be numbered according to the starting number + increment value per Step 1.', '1', '22', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Maintenance mode', 'MAINTENANCE_MODE', '-1', 'Maintenance Mode', '1', '23', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Use TinyMCE Editor', 'USE_WYSIWYG_TINYMCE_EDITOR', '-1', 'Use TinyMCE Editor', '1', '24', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display Products in the subcatalogs recursively', 'DISPLAY_SUBCATALOGS_PRODUCTS', '1', 'Display the products in the subcatalogs recursively!', '1', '25', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Synchronize cart with the database when customer logged in', 'SYNCHRONIZE_CART_WITH_DATABASE', '1', 'Synchronize cart with the database when customer logged in!', '1', '26', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Check stocks when synchronize cart with the database', 'CHECK_STOCKS_SYNCHRONIZE_CART_WITH_DATABASE', '1', 'Check stocks when synchronize cart with the database', '1', '27', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Credit Card Owner Name', 'CC_OWNER_MIN_LENGTH', '3', 'Minimum length of credit card owner name', '2', '12', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Credit Card Number', 'CC_NUMBER_MIN_LENGTH', '10', 'Minimum length of credit card number', '2', '13', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Review Text', 'REVIEW_TEXT_MIN_LENGTH', '50', 'Minimum length of review text', '2', '14', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Address Book Entries', 'MAX_ADDRESS_BOOK_ENTRIES', '100', 'Maximum address book entries a customer is allowed to have', '3', '1', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Search Results', 'MAX_DISPLAY_SEARCH_RESULTS', '12', 'Amount of products to list', '3', '2', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Categories To List Per Row', 'MAX_DISPLAY_CATEGORIES_PER_ROW', '3', 'How many categories to list per row', '3', '13', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('New Products Listing', 'MAX_DISPLAY_PRODUCTS_NEW', '10', 'Maximum number of new products to display in new products page', '3', '14', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Order History', 'MAX_DISPLAY_ORDER_HISTORY', '10', 'Maximum number of orders to display in the order history page', '3', '18', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Heading Image Width', 'HEADING_IMAGE_WIDTH', '57', 'The pixel width of heading images', '4', '3', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Heading Image Height', 'HEADING_IMAGE_HEIGHT', '40', 'The pixel height of heading images', '4', '4', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Image Required', 'IMAGE_REQUIRED', '1', 'Enable to display broken images. Good for development.', '4', '8', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Gender', 'ACCOUNT_GENDER', '1', 'Ask for or require the customers gender.', '5', '10', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, 0, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('First Name', 'ACCOUNT_FIRST_NAME', '2', 'Minimum requirement for the customers first name.', '5', '11', 'cfg_set_boolean_value(array(\'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\', \'8\', \'9\', \'10\'))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Last Name', 'ACCOUNT_LAST_NAME', '2', 'Minimum requirement for the customers last name.', '5', '12', 'cfg_set_boolean_value(array(\'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\', \'8\', \'9\', \'10\'))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Date Of Birth', 'ACCOUNT_DATE_OF_BIRTH', '1', 'Ask for the customers date of birth.', '5', '13', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('E-Mail Address', 'ACCOUNT_EMAIL_ADDRESS', '6', 'Minimum requirement for the customers e-mail address.', '5', '14', 'cfg_set_boolean_value(array(\'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\', \'8\', \'9\', \'10\'))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Password', 'ACCOUNT_PASSWORD', '5', 'Minimum requirement for the customers password.', '5', '15', 'cfg_set_boolean_value(array(\'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\', \'8\', \'9\', \'10\'))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Newsletter', 'ACCOUNT_NEWSLETTER', '1', 'Ask for a newsletter subscription.', '5', '16', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Company Name', 'ACCOUNT_COMPANY', '0', 'Ask for or require the customers company name.', '5', '17', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(\'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\', 0, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Street Address', 'ACCOUNT_STREET_ADDRESS', '5', 'Minimum requirement for the customers street address.', '5', '18', 'cfg_set_boolean_value(array(\'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\', \'8\', \'9\', \'10\'))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Suburb', 'ACCOUNT_SUBURB', '0', 'Ask for or require the customers suburb.', '5', '19', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(\'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\', 0, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Post Code', 'ACCOUNT_POST_CODE', '0', 'Minimum requirement for the customers post code.', '5', '20', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(-1, 0, \'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\', \'8\', \'9\', \'10\'))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('City', 'ACCOUNT_CITY', '4', 'Minimum requirement for the customers city.', '5', '21', 'cfg_set_boolean_value(array(\'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\', \'8\', \'9\', \'10\'))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('State', 'ACCOUNT_STATE', '2', 'Ask for or require the customers state.', '5', '22', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(\'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\', 0, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Country', 'ACCOUNT_COUNTRY', '1', 'Ask for the customers country.', '5', '23', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Telephone Number', 'ACCOUNT_TELEPHONE', '3', 'Ask for or require the customers telephone number.', '5', '24', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(\'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\', 0, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Fax Number', 'ACCOUNT_FAX', '0', 'Ask for or require the customers fax number.', '5', '25', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(\'10\', \'9\', \'8\', \'7\', \'6\', \'5\', \'4\', \'3\', \'2\', \'1\', 0, -1))', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Currency', 'DEFAULT_CURRENCY', 'USD', 'Default Currency', '6', '0', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Language', 'DEFAULT_LANGUAGE', 'en_US', 'Default Language', '6', '0', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Order Status For New Orders', 'DEFAULT_ORDERS_STATUS_ID', '1', 'When a new order is created, this order status will be assigned to it.', '6', '0', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Image Group', 'DEFAULT_IMAGE_GROUP_ID', '2', 'Default image group.', '6', '0', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Template', 'DEFAULT_TEMPLATE', 'default', 'TomatoCart Default Template', '6', '0', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Country of Origin', 'SHIPPING_ORIGIN_COUNTRY', '223', 'Select the country of origin to be used in shipping quotes.', '7', '1', 'TOC_Address::getCountryName', 'cfg_set_countries_pulldown_menu', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Postal Code', 'SHIPPING_ORIGIN_ZIP', 'NONE', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.', '7', '2', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Enter the Maximum Package Weight you will ship', 'SHIPPING_MAX_WEIGHT', '50', 'Carriers have a max weight limit for a single package. This is a common one for all.', '7', '3', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Package Tare weight.', 'SHIPPING_BOX_WEIGHT', '3', 'What is the weight of typical packaging of small to medium packages?', '7', '4', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Larger packages - percentage increase.', 'SHIPPING_BOX_PADDING', '10', 'For 10% enter 10', '7', '5', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Default Shipping Unit', 'SHIPPING_WEIGHT_UNIT',2, 'Select the unit of weight to be used for shipping.', '7', '6', 'TOC_Weight::getTitle', 'cfg_set_weight_classes_pulldown_menu', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Default Unit Class', 'DEFAULT_UNIT_CLASSES', '1', 'Default Unit Class', '6', '0', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Image', 'PRODUCT_LIST_IMAGE', '1', 'Do you want to display the Product Image?', '8', '1', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Manufaturer Name','PRODUCT_LIST_MANUFACTURER', '0', 'Do you want to display the Product Manufacturer Name?', '8', '2', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product SKU', 'PRODUCT_LIST_SKU', '0', 'Do you want to display the Product SKU?', '8', '3', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Name', 'PRODUCT_LIST_NAME', '2', 'Do you want to display the Product Name?', '8', '4', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Price', 'PRODUCT_LIST_PRICE', '3', 'Do you want to display the Product Price', '8', '5', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Quantity', 'PRODUCT_LIST_QUANTITY', '0', 'Do you want to display the Product Quantity?', '8', '6', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Product Weight', 'PRODUCT_LIST_WEIGHT', '0', 'Do you want to display the Product Weight?', '8', '7', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Display Buy Now column', 'PRODUCT_LIST_BUY_NOW', '4', 'Do you want to display the Buy Now column?', '8', '8', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display Category/Manufacturer Filter', 'PRODUCT_LIST_FILTER', '1', 'Do you want to display the Category/Manufacturer Filter?', '8', '9', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display Product Attributes Filter', 'PRODUCT_ATTRIBUTES_FILTER', '1', 'Do you want to display the Product Attributes Filter?', '8', '10', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Link the Product Attributes and Category/Manufacturer Filter', 'PRODUCT_LINK_FILTER', '1', 'Do you want to filter the products with the Product Attributes Filter and Category/Manufacturer Filter at the same time?', '8', '11', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', 'PREV_NEXT_BAR_LOCATION', '2', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)', '8', '12', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Check stock level', 'STOCK_CHECK', '1', 'Check to see if sufficent stock is available', '9', '1', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Subtract stock', 'STOCK_LIMITED', '1', 'Subtract product in stock by product orders', '9', '2', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Allow Checkout', 'STOCK_ALLOW_CHECKOUT', '1', 'Allow customer to checkout even if there is insufficient stock', '9', '3', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Mark product out of stock', 'STOCK_MARK_PRODUCT_OUT_OF_STOCK', '***', 'Display something on screen so customer can see which product has insufficient stock', '9', '4', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Stock Re-order level', 'STOCK_REORDER_LEVEL', '5', 'Define when stock needs to be re-ordered', '9', '5', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Out of stock email alerts', 'STOCK_EMAIL_ALERT', '1', 'Define send a email to administrator  if out of stock', '9', '6', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display Product Quantity', 'PRODUCT_INFO_QUANTITY', '1', 'Do you want to display the Product Quantity?', '10', '1', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display Product Minimum Order Quantity','PRODUCT_INFO_MOQ', '-1', 'Do you want to display the Product Minimum Order Quantity?', '10', '2', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display Product Order Increment', 'PRODUCT_INFO_ORDER_INCREMENT', '-1', 'Do you want to display the Order Increment?', '10', '3', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Allow gift certificate return', 'ALLOW_GIFT_CERTIFICATE_RETURN', '-1', 'Do you want to allow customer return gift certificates?', '11', '3', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Allow downloadable return', 'ALLOW_DOWNLOADABLE_RETURN', '-1', 'Do you want to allow customer return downloadable products?', '11', '4', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Allow return request', 'ALLOW_RETURN_REQUEST', '1', 'Do you want to allow customer return product?', '11', '5', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Download by redirect', 'DOWNLOAD_BY_REDIRECT', '-1', 'Use browser redirection for download. Disable on non-Unix systems.', '11', '1', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Display the telephone number', 'DISPLAY_TELEPHONE_NUMBER', '1', 'Set true to make the telephone number display in the order details.', '11', '6', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('E-Mail Transport Method', 'EMAIL_TRANSPORT', 'sendmail', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.', '12', '1', 'cfg_set_boolean_value(array(\'sendmail\', \'smtp\'))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('E-Mail Linefeeds', 'EMAIL_LINEFEED', 'LF', 'Defines the character sequence used to separate mail headers.', '12', '2', 'cfg_set_boolean_value(array(\'LF\', \'CRLF\'))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('SMTP Server', 'SMTP_HOST', '', 'The SMTP E-Mail Server', '12', '3', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('SMTP Server Port', 'SMTP_PORT', '25', 'The SMTP E-Mail Server', '12', '4', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('SMTP Username', 'SMTP_USERNAME', '', 'SMTP Username', '12', '5', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('SMTP Password', 'SMTP_PASSWORD', '', 'SMTP Password', '12', '6', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Enable SSL Connection', 'EMAIL_SSL', '1', 'Connect the smtp server with ssl connection', '12', '7', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Use MIME HTML When Sending Emails', 'EMAIL_USE_HTML', '-1', 'Send e-mails in HTML format', '12', '8', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Verify E-Mail Addresses Through DNS', 'ENTRY_EMAIL_ADDRESS_CHECK', '-1', 'Verify e-mail address through a DNS server', '12', '9', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Send E-Mails', 'SEND_EMAILS', '1', 'Send out e-mails', '12', '10', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Confirm Terms and Conditions During Checkout Procedure', 'DISPLAY_CONDITIONS_ON_CHECKOUT', '-1', 'Show the Terms and Conditions during the checkout procedure which the customer must agree to.', '16', '1', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Confirm Privacy Notice During Account Creation Procedure', 'DISPLAY_PRIVACY_CONDITIONS', '-1', 'Show the Privacy Notice during the account creation procedure which the customer must agree to.', '16', '2', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Verify With Regular Expressions', 'CFG_CREDIT_CARDS_VERIFY_WITH_REGEXP', '1', 'Verify credit card numbers with server-side regular expression patterns.', '17', '0', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Verify With Javascript', 'CFG_CREDIT_CARDS_VERIFY_WITH_JS', '1', 'Verify credit card numbers with javascript based regular expression patterns.', '17', '1', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());

INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Activate Captcha',  'ACTIVATE_CAPTCHA', '1', 'active captcha for contact us page and guest book', '19', '1', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());
INSERT INTO `toc_configuration` (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Disallow more than one vote from the same IP address', 'DISALLOW_MORE_THAN_ONE_VOTE', '1', 'Disallow more than one vote from the same IP address', '19', '2', 'cfg_use_get_boolean_value', 'cfg_set_boolean_value(array(1, -1))', now());

INSERT INTO `toc_configuration_group` VALUES ('1', 'My Store', 'General information about my store', '1', '1');
INSERT INTO `toc_configuration_group` VALUES ('2', 'Minimum Values', 'The minimum values for functions / data', '2', '1');
INSERT INTO `toc_configuration_group` VALUES ('3', 'Maximum Values', 'The maximum values for functions / data', '3', '1');
INSERT INTO `toc_configuration_group` VALUES ('4', 'Images', 'Image parameters', '4', '1');
INSERT INTO `toc_configuration_group` VALUES ('5', 'Customer Details', 'Customer account configuration', '5', '1');
INSERT INTO `toc_configuration_group` VALUES ('6', 'Module Options', 'Hidden from configuration', '6', '0');
INSERT INTO `toc_configuration_group` VALUES ('7', 'Shipping/Packaging', 'Shipping options available at my store', '7', '1');
INSERT INTO `toc_configuration_group` VALUES ('8', 'Product Listing', 'Product Listing    configuration options', '8', '1');
INSERT INTO `toc_configuration_group` VALUES ('9', 'Stock', 'Stock configuration options', '9', '1');
INSERT INTO `toc_configuration_group` VALUES ('10', 'Product Details', 'Product info page configuration', '10', '1');
INSERT INTO `toc_configuration_group` VALUES ('11', 'Order Settings', 'Order Settings', '11', '1');
INSERT INTO `toc_configuration_group` VALUES ('12', 'E-Mail Options', 'General setting for E-Mail transport and HTML E-Mails', '12', '1');
INSERT INTO `toc_configuration_group` VALUES ('16', 'Regulations', 'Regulation options', '16', '1');
INSERT INTO `toc_configuration_group` VALUES ('17', 'Credit Cards', 'Credit card options', '17', '1');
INSERT INTO `toc_configuration_group` VALUES ('19', 'Content Management System', 'Content Management System Configuration', '19', '1');

INSERT INTO `toc_countries` VALUES (1,'Afghanistan','AF','AFG','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'BDS','بد خشان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'BDG','بادغیس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'BGL','بغلان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'BAL','بلخ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'BAM','بامیان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'DAY','دایکندی');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'FRA','فراه');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'FYB','فارياب');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'GHA','غزنى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'GHO','غور');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'HEL','هلمند');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'HER','هرات');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'JOW','جوزجان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'KAB','کابل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'KAN','قندھار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'KAP','کاپيسا');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'KHO','خوست');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'KNR','کُنَر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'KDZ','كندوز');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'LAG','لغمان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'LOW','لوګر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'NAN','ننگرهار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'NIM','نیمروز');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'NUR','نورستان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'ORU','ؤروزگان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'PIA','پکتیا');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'PKA','پکتيکا');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'PAN','پنج شیر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'PAR','پروان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'SAM','سمنگان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'SAR','سر پل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'TAK','تخار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'WAR','وردک');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (1,'ZAB','زابل');

INSERT INTO `toc_countries` VALUES (2,'Albania','AL','ALB','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'BR','Beratit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'BU','Bulqizës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'DI','Dibrës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'DL','Delvinës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'DR','Durrësit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'DV','Devollit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'EL','Elbasanit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'ER','Kolonjës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'FR','Fierit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'GJ','Gjirokastrës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'GR','Gramshit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'HA','Hasit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'KA','Kavajës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'KB','Kurbinit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'KC','Kuçovës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'KO','Korçës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'KR','Krujës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'KU','Kukësit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'LB','Librazhdit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'LE','Lezhës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'LU','Lushnjës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'MK','Mallakastrës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'MM','Malësisë së Madhe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'MR','Mirditës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'MT','Matit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'PG','Pogradecit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'PQ','Peqinit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'PR','Përmetit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'PU','Pukës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'SH','Shkodrës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'SK','Skraparit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'SR','Sarandës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'TE','Tepelenës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'TP','Tropojës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'TR','Tiranës');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (2,'VL','Vlorës');

INSERT INTO `toc_countries` VALUES (3,'Algeria','DZ','DZA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'01','ولاية أدرار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'02','ولاية الشلف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'03','ولاية الأغواط');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'04','ولاية أم البواقي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'05','ولاية باتنة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'06','ولاية بجاية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'07','ولاية بسكرة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'08','ولاية بشار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'09','البليدة‎');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'10','ولاية البويرة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'11','ولاية تمنراست');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'12','ولاية تبسة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'13','تلمسان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'14','ولاية تيارت');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'15','تيزي وزو');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'16','ولاية الجزائر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'17','ولاية عين الدفلى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'18','ولاية جيجل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'19','ولاية سطيف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'20','ولاية سعيدة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'21','السكيكدة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'22','ولاية سيدي بلعباس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'23','ولاية عنابة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'24','ولاية قالمة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'25','قسنطينة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'26','ولاية المدية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'27','ولاية مستغانم');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'28','ولاية المسيلة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'29','ولاية معسكر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'30','ورقلة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'31','وهران');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'32','ولاية البيض');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'33','ولاية اليزي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'34','ولاية برج بوعريريج');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'35','ولاية بومرداس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'36','ولاية الطارف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'37','تندوف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'38','ولاية تسمسيلت');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'39','ولاية الوادي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'40','ولاية خنشلة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'41','ولاية سوق أهراس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'42','ولاية تيبازة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'43','ولاية ميلة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'44','ولاية عين الدفلى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'45','ولاية النعامة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'46','ولاية عين تموشنت');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'47','ولاية غرداية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (3,'48','ولاية غليزان');

INSERT INTO `toc_countries` VALUES (4,'American Samoa','AS','ASM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (4,'EA','Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (4,'MA','Manu\'a');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (4,'RI','Rose Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (4,'SI','Swains Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (4,'WE','Western');

INSERT INTO `toc_countries` VALUES (5,'Andorra','AD','AND','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (5,'AN','Andorra la Vella');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (5,'CA','Canillo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (5,'EN','Encamp');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (5,'LE','Escaldes-Engordany');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (5,'LM','La Massana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (5,'OR','Ordino');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (5,'SJ','Sant Juliá de Lória');

INSERT INTO `toc_countries` VALUES (6,'Angola','AO','AGO','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'BGO','Bengo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'BGU','Benguela');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'BIE','Bié');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'CAB','Cabinda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'CCU','Cuando Cubango');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'CNO','Cuanza Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'CUS','Cuanza Sul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'CNN','Cunene');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'HUA','Huambo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'HUI','Huíla');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'LUA','Luanda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'LNO','Lunda Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'LSU','Lunda Sul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'MAL','Malanje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'MOX','Moxico');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'NAM','Namibe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'UIG','Uíge');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (6,'ZAI','Zaire');

INSERT INTO `toc_countries` VALUES (7,'Anguilla','AI','AIA','');
INSERT INTO `toc_countries` VALUES (8,'Antarctica','AQ','ATA','');

INSERT INTO `toc_countries` VALUES (9,'Antigua and Barbuda','AG','ATG','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (9,'BAR','Barbuda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (9,'SGE','Saint George');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (9,'SJO','Saint John');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (9,'SMA','Saint Mary');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (9,'SPA','Saint Paul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (9,'SPE','Saint Peter');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (9,'SPH','Saint Philip');

INSERT INTO `toc_countries` VALUES (10,'Argentina','AR','ARG',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'A','Salta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'B','Buenos Aires Province');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'C','Capital Federal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'D','San Luis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'E','Entre Ríos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'F','La Rioja');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'G','Santiago del Estero');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'H','Chaco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'J','San Juan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'K','Catamarca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'L','La Pampa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'M','Mendoza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'N','Misiones');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'P','Formosa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'Q','Neuquén');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'R','Río Negro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'S','Santa Fe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'T','Tucumán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'U','Chubut');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'V','Tierra del Fuego');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'W','Corrientes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'X','Córdoba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'Y','Jujuy');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (10,'Z','Santa Cruz');

INSERT INTO `toc_countries` VALUES (11,'Armenia','AM','ARM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'AG','Արագածոտն');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'AR','Արարատ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'AV','Արմավիր');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'ER','Երևան');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'GR','Գեղարքունիք');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'KT','Կոտայք');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'LO','Լոռի');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'SH','Շիրակ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'SU','Սյունիք');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'TV','Տավուշ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (11,'VD','Վայոց Ձոր');

INSERT INTO `toc_countries` VALUES (12,'Aruba','AW','ABW','');

INSERT INTO `toc_countries` VALUES (13,'Australia','AU','AUS',":name\n:street_address\n:suburb :state_code :postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (13,'ACT','Australian Capital Territory');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (13,'NSW','New South Wales');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (13,'NT','Northern Territory');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (13,'QLD','Queensland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (13,'SA','South Australia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (13,'TAS','Tasmania');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (13,'VIC','Victoria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (13,'WA','Western Australia');

INSERT INTO `toc_countries` VALUES (14,'Austria','AT','AUT',":name\n:street_address\nA-:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (14,'1','Burgenland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (14,'2','Kärnten');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (14,'3','Niederösterreich');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (14,'4','Oberösterreich');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (14,'5','Salzburg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (14,'6','Steiermark');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (14,'7','Tirol');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (14,'8','Voralberg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (14,'9','Wien');

INSERT INTO `toc_countries` VALUES (15,'Azerbaijan','AZ','AZE','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'AB','Əli Bayramlı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'ABS','Abşeron');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'AGC','Ağcabədi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'AGM','Ağdam');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'AGS','Ağdaş');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'AGA','Ağstafa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'AGU','Ağsu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'AST','Astara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'BA','Bakı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'BAB','Babək');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'BAL','Balakən');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'BAR','Bərdə');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'BEY','Beyləqan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'BIL','Biləsuvar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'CAB','Cəbrayıl');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'CAL','Cəlilabab');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'CUL','Julfa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'DAS','Daşkəsən');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'DAV','Dəvəçi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'FUZ','Füzuli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'GA','Gəncə');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'GAD','Gədəbəy');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'GOR','Goranboy');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'GOY','Göyçay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'HAC','Hacıqabul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'IMI','İmişli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'ISM','İsmayıllı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'KAL','Kəlbəcər');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'KUR','Kürdəmir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'LA','Lənkəran');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'LAC','Laçın');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'LAN','Lənkəran');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'LER','Lerik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'MAS','Masallı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'MI','Mingəçevir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'NA','Naftalan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'NEF','Neftçala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'OGU','Oğuz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'ORD','Ordubad');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'QAB','Qəbələ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'QAX','Qax');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'QAZ','Qazax');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'QOB','Qobustan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'QBA','Quba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'QBI','Qubadlı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'QUS','Qusar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SA','Şəki');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SAT','Saatlı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SAB','Sabirabad');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SAD','Sədərək');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SAH','Şahbuz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SAK','Şəki');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SAL','Salyan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SM','Sumqayıt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SMI','Şamaxı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SKR','Şəmkir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SMX','Samux');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SAR','Şərur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SIY','Siyəzən');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SS','Şuşa (City)');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'SUS','Şuşa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'TAR','Tərtər');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'TOV','Tovuz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'UCA','Ucar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'XA','Xankəndi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'XAC','Xaçmaz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'XAN','Xanlar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'XIZ','Xızı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'XCI','Xocalı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'XVD','Xocavənd');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'YAR','Yardımlı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'YE','Yevlax (City)');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'YEV','Yevlax');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'ZAN','Zəngilan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'ZAQ','Zaqatala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'ZAR','Zərdab');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (15,'NX','Nakhichevan');

INSERT INTO `toc_countries` VALUES (16,'Bahamas','BS','BHS','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'AC','Acklins and Crooked Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'BI','Bimini');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'CI','Cat Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'EX','Exuma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'FR','Freeport');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'FC','Fresh Creek');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'GH','Governor\'s Harbour');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'GT','Green Turtle Cay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'HI','Harbour Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'HR','High Rock');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'IN','Inagua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'KB','Kemps Bay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'LI','Long Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'MH','Marsh Harbour');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'MA','Mayaguana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'NP','New Providence');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'NT','Nicholls Town and Berry Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'RI','Ragged Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'RS','Rock Sound');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'SS','San Salvador and Rum Cay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (16,'SP','Sandy Point');

INSERT INTO `toc_countries` VALUES (17,'Bahrain','BH','BHR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'01','الحد');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'02','المحرق');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'03','المنامة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'04','جد حفص');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'05','المنطقة الشمالية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'06','سترة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'07','المنطقة الوسطى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'08','مدينة عيسى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'09','الرفاع والمنطقة الجنوبية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'10','المنطقة الغربية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'11','جزر حوار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (17,'12','مدينة حمد');

INSERT INTO `toc_countries` VALUES (18,'Bangladesh','BD','BGD','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'01','Bandarban');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'02','Barguna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'03','Bogra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'04','Brahmanbaria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'05','Bagerhat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'06','Barisal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'07','Bhola');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'08','Comilla');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'09','Chandpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'10','Chittagong');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'11','Cox\'s Bazar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'12','Chuadanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'13','Dhaka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'14','Dinajpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'15','Faridpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'16','Feni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'17','Gopalganj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'18','Gazipur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'19','Gaibandha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'20','Habiganj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'21','Jamalpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'22','Jessore');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'23','Jhenaidah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'24','Jaipurhat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'25','Jhalakati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'26','Kishoreganj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'27','Khulna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'28','Kurigram');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'29','Khagrachari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'30','Kushtia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'31','Lakshmipur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'32','Lalmonirhat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'33','Manikganj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'34','Mymensingh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'35','Munshiganj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'36','Madaripur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'37','Magura');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'38','Moulvibazar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'39','Meherpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'40','Narayanganj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'41','Netrakona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'42','Narsingdi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'43','Narail');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'44','Natore');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'45','Nawabganj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'46','Nilphamari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'47','Noakhali');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'48','Naogaon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'49','Pabna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'50','Pirojpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'51','Patuakhali');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'52','Panchagarh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'53','Rajbari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'54','Rajshahi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'55','Rangpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'56','Rangamati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'57','Sherpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'58','Satkhira');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'59','Sirajganj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'60','Sylhet');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'61','Sunamganj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'62','Shariatpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'63','Tangail');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (18,'64','Thakurgaon');

INSERT INTO `toc_countries` VALUES (19,'Barbados','BB','BRB','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'A','Saint Andrew');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'C','Christ Church');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'E','Saint Peter');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'G','Saint George');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'J','Saint John');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'L','Saint Lucy');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'M','Saint Michael');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'O','Saint Joseph');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'P','Saint Philip');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'S','Saint James');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (19,'T','Saint Thomas');

INSERT INTO `toc_countries` VALUES (20,'Belarus','BY','BLR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (20,'BR','Брэ́сцкая во́бласць');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (20,'HO','Го́мельская во́бласць');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (20,'HR','Гро́дзенская во́бласць');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (20,'MA','Магілёўская во́бласць');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (20,'MI','Мі́нская во́бласць');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (20,'VI','Ві́цебская во́бласць');

INSERT INTO `toc_countries` VALUES (21,'Belgium','BE','BEL',":name\n:street_address\nB-:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'BRU','Brussel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'VAN','Antwerpen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'VBR','Vlaams-Brabant');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'VLI','Limburg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'VOV','Oost-Vlaanderen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'VWV','West-Vlaanderen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'WBR','Brabant Wallonië');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'WHT','Henegouwen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'WLG','Luik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'WLX','Luxemburg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (21,'WNA','Namen');

INSERT INTO `toc_countries` VALUES (22,'Belize','BZ','BLZ','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (22,'BZ','Belize District');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (22,'CY','Cayo District');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (22,'CZL','Corozal District');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (22,'OW','Orange Walk District');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (22,'SC','Stann Creek District');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (22,'TOL','Toledo District');

INSERT INTO `toc_countries` VALUES (23,'Benin','BJ','BEN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'AL','Alibori');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'AK','Atakora');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'AQ','Atlantique');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'BO','Borgou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'CO','Collines');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'DO','Donga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'KO','Kouffo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'LI','Littoral');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'MO','Mono');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'OU','Ouémé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'PL','Plateau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (23,'ZO','Zou');

INSERT INTO `toc_countries` VALUES (24,'Bermuda','BM','BMU','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'DEV','Devonshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'HA','Hamilton City');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'HAM','Hamilton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'PAG','Paget');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'PEM','Pembroke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'SAN','Sandys');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'SG','Saint George City');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'SGE','Saint George\'s');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'SMI','Smiths');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'SOU','Southampton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (24,'WAR','Warwick');

INSERT INTO `toc_countries` VALUES (25,'Bhutan','BT','BTN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'11','Paro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'12','Chukha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'13','Haa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'14','Samtse');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'15','Thimphu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'21','Tsirang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'22','Dagana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'23','Punakha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'24','Wangdue Phodrang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'31','Sarpang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'32','Trongsa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'33','Bumthang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'34','Zhemgang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'41','Trashigang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'42','Mongar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'43','Pemagatshel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'44','Luentse');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'45','Samdrup Jongkhar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'GA','Gasa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (25,'TY','Trashiyangse');

INSERT INTO `toc_countries` VALUES (26,'Bolivia','BO','BOL','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (26,'B','El Beni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (26,'C','Cochabamba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (26,'H','Chuquisaca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (26,'L','La Paz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (26,'N','Pando');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (26,'O','Oruro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (26,'P','Potosí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (26,'S','Santa Cruz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (26,'T','Tarija');

INSERT INTO `toc_countries` VALUES (27,'Bosnia and Herzegowina','BA','BIH','');
INSERT INTO `toc_countries` VALUES (28,'Botswana','BW','BWA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (28,'CE','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (28,'GH','Ghanzi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (28,'KG','Kgalagadi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (28,'KL','Kgatleng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (28,'KW','Kweneng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (28,'NE','North-East');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (28,'NW','North-West');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (28,'SE','South-East');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (28,'SO','Southern');

INSERT INTO `toc_countries` VALUES (29,'Bouvet Island','BV','BVT','');

INSERT INTO `toc_countries` VALUES (30,'Brazil','BR','BRA',":name\n:street_address\n:state\n:postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'AC','Acre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'AL','Alagoas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'AM','Amazônia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'AP','Amapá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'BA','Bahia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'CE','Ceará');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'DF','Distrito Federal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'ES','Espírito Santo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'GO','Goiás');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'MA','Maranhão');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'MG','Minas Gerais');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'MS','Mato Grosso do Sul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'MT','Mato Grosso');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'PA','Pará');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'PB','Paraíba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'PE','Pernambuco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'PI','Piauí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'PR','Paraná');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'RJ','Rio de Janeiro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'RN','Rio Grande do Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'RO','Rondônia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'RR','Roraima');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'RS','Rio Grande do Sul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'SC','Santa Catarina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'SE','Sergipe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'SP','São Paulo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (30,'TO','Tocantins');

INSERT INTO `toc_countries` VALUES (31,'British Indian Ocean Territory','IO','IOT','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (31,'PB','Peros Banhos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (31,'SI','Salomon Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (31,'NI','Nelsons Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (31,'TB','Three Brothers');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (31,'EA','Eagle Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (31,'DI','Danger Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (31,'EG','Egmont Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (31,'DG','Diego Garcia');

INSERT INTO `toc_countries` VALUES (32,'Brunei Darussalam','BN','BRN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (32,'BE','Belait');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (32,'BM','Brunei-Muara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (32,'TE','Temburong');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (32,'TU','Tutong');

INSERT INTO `toc_countries` VALUES (33,'Bulgaria','BG','BGR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'01','Blagoevgrad');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'02','Burgas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'03','Varna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'04','Veliko Tarnovo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'05','Vidin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'06','Vratsa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'07','Gabrovo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'08','Dobrich');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'09','Kardzhali');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'10','Kyustendil');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'11','Lovech');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'12','Montana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'13','Pazardzhik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'14','Pernik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'15','Pleven');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'16','Plovdiv');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'17','Razgrad');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'18','Ruse');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'19','Silistra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'20','Sliven');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'21','Smolyan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'23','Sofia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'22','Sofia Province');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'24','Stara Zagora');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'25','Targovishte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'26','Haskovo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'27','Shumen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (33,'28','Yambol');

INSERT INTO `toc_countries` VALUES (34,'Burkina Faso','BF','BFA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'BAL','Balé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'BAM','Bam');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'BAN','Banwa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'BAZ','Bazèga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'BGR','Bougouriba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'BLG','Boulgou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'BLK','Boulkiemdé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'COM','Komoé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'GAN','Ganzourgou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'GNA','Gnagna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'GOU','Gourma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'HOU','Houet');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'IOB','Ioba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'KAD','Kadiogo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'KEN','Kénédougou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'KMD','Komondjari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'KMP','Kompienga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'KOP','Koulpélogo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'KOS','Kossi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'KOT','Kouritenga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'KOW','Kourwéogo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'LER','Léraba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'LOR','Loroum');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'MOU','Mouhoun');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'NAM','Namentenga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'NAO','Naouri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'NAY','Nayala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'NOU','Noumbiel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'OUB','Oubritenga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'OUD','Oudalan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'PAS','Passoré');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'PON','Poni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'SEN','Séno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'SIS','Sissili');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'SMT','Sanmatenga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'SNG','Sanguié');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'SOM','Soum');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'SOR','Sourou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'TAP','Tapoa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'TUI','Tui');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'YAG','Yagha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'YAT','Yatenga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'ZIR','Ziro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'ZON','Zondoma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (34,'ZOU','Zoundwéogo');

INSERT INTO `toc_countries` VALUES (35,'Burundi','BI','BDI','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'BB','Bubanza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'BJ','Bujumbura Mairie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'BR','Bururi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'CA','Cankuzo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'CI','Cibitoke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'GI','Gitega');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'KR','Karuzi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'KY','Kayanza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'KI','Kirundo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'MA','Makamba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'MU','Muramvya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'MY','Muyinga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'MW','Mwaro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'NG','Ngozi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'RT','Rutana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (35,'RY','Ruyigi');

INSERT INTO `toc_countries` VALUES (36,'Cambodia','KH','KHM','');

INSERT INTO `toc_countries` VALUES (37,'Cameroon','CM','CMR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'AD','Adamaoua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'CE','Centre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'EN','Extrême-Nord');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'ES','Est');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'LT','Littoral');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'NO','Nord');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'NW','Nord-Ouest');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'OU','Ouest');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'SU','Sud');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (37,'SW','Sud-Ouest');

INSERT INTO `toc_countries` VALUES (38,'Canada','CA','CAN',":name\n:street_address\n:city :state_code :postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'AB','Alberta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'BC','British Columbia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'MB','Manitoba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'NB','New Brunswick');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'NL','Newfoundland and Labrador');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'NS','Nova Scotia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'NT','Northwest Territories');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'NU','Nunavut');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'ON','Ontario');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'PE','Prince Edward Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'QC','Quebec');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'SK','Saskatchewan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (38,'YT','Yukon Territory');

INSERT INTO `toc_countries` VALUES (39,'Cape Verde','CV','CPV','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'BR','Brava');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'BV','Boa Vista');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'CA','Santa Catarina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'CR','Santa Cruz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'CS','Calheta de São Miguel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'MA','Maio');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'MO','Mosteiros');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'PA','Paúl');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'PN','Porto Novo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'PR','Praia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'RG','Ribeira Grande');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'SD','São Domingos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'SF','São Filipe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'SL','Sal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'SN','São Nicolau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'SV','São Vicente');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (39,'TA','Tarrafal');

INSERT INTO `toc_countries` VALUES (40,'Cayman Islands','KY','CYM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (40,'CR','Creek');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (40,'EA','Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (40,'MI','Midland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (40,'SO','South Town');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (40,'SP','Spot Bay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (40,'ST','Stake Bay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (40,'WD','West End');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (40,'WN','Western');

INSERT INTO `toc_countries` VALUES (41,'Central African Republic','CF','CAF','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'AC ','Ouham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'BB ','Bamingui-Bangoran');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'BGF','Bangui');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'BK ','Basse-Kotto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'HK ','Haute-Kotto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'HM ','Haut-Mbomou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'HS ','Mambéré-Kadéï');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'KB ','Nana-Grébizi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'KG ','Kémo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'LB ','Lobaye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'MB ','Mbomou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'MP ','Ombella-M\'Poko');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'NM ','Nana-Mambéré');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'OP ','Ouham-Pendé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'SE ','Sangha-Mbaéré');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'UK ','Ouaka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (41,'VR ','Vakaga');

INSERT INTO `toc_countries` VALUES (42,'Chad','TD','TCD','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'BA ','Batha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'BET','Borkou-Ennedi-Tibesti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'BI ','Biltine');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'CB ','Chari-Baguirmi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'GR ','Guéra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'KA ','Kanem');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'LC ','Lac');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'LR ','Logone-Oriental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'LO ','Logone-Occidental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'MC ','Moyen-Chari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'MK ','Mayo-Kébbi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'OD ','Ouaddaï');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'SA ','Salamat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (42,'TA ','Tandjilé');

INSERT INTO `toc_countries` VALUES (43,'Chile','CL','CHL',":name\n:street_address\n:city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'AI','Aisén del General Carlos Ibañez');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'AN','Antofagasta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'AR','La Araucanía');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'AT','Atacama');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'BI','Biobío');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'CO','Coquimbo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'LI','Libertador Bernardo O\'Higgins');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'LL','Los Lagos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'MA','Magallanes y de la Antartica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'ML','Maule');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'RM','Metropolitana de Santiago');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'TA','Tarapacá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (43,'VS','Valparaíso');

INSERT INTO `toc_countries` VALUES (44,'China','CN','CHN',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'11','北京');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'12','天津');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'13','河北');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'14','山西');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'15','内蒙古自治区');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'21','辽宁');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'22','吉林');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'23','黑龙江省');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'31','上海');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'32','江苏');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'33','浙江');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'34','安徽');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'35','福建');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'36','江西');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'37','山东');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'41','河南');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'42','湖北');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'43','湖南');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'44','广东');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'45','广西壮族自治区');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'46','海南');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'50','重庆');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'51','四川');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'52','贵州');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'53','云南');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'54','西藏自治区');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'61','陕西');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'62','甘肃');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'63','青海');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'64','宁夏');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'65','新疆');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'71','臺灣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'91','香港');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (44,'92','澳門');

INSERT INTO `toc_countries` VALUES (45,'Christmas Island','CX','CXR','');

INSERT INTO `toc_countries` VALUES (46,'Cocos (Keeling) Islands','CC','CCK','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (46,'D','Direction Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (46,'H','Home Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (46,'O','Horsburgh Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (46,'S','South Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (46,'W','West Island');

INSERT INTO `toc_countries` VALUES (47,'Colombia','CO','COL','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'AMA','Amazonas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'ANT','Antioquia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'ARA','Arauca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'ATL','Atlántico');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'BOL','Bolívar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'BOY','Boyacá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'CAL','Caldas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'CAQ','Caquetá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'CAS','Casanare');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'CAU','Cauca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'CES','Cesar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'CHO','Chocó');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'COR','Córdoba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'CUN','Cundinamarca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'DC','Bogotá Distrito Capital');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'GUA','Guainía');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'GUV','Guaviare');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'HUI','Huila');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'LAG','La Guajira');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'MAG','Magdalena');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'MET','Meta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'NAR','Nariño');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'NSA','Norte de Santander');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'PUT','Putumayo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'QUI','Quindío');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'RIS','Risaralda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'SAN','Santander');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'SAP','San Andrés y Providencia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'SUC','Sucre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'TOL','Tolima');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'VAC','Valle del Cauca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'VAU','Vaupés');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (47,'VID','Vichada');

INSERT INTO `toc_countries` VALUES (48,'Comoros','KM','COM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (48,'A','Anjouan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (48,'G','Grande Comore');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (48,'M','Mohéli');

INSERT INTO `toc_countries` VALUES (49,'Congo','CG','COG','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'BC','Congo-Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'BN','Bandundu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'EQ','Équateur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'KA','Katanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'KE','Kasai-Oriental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'KN','Kinshasa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'KW','Kasai-Occidental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'MA','Maniema');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'NK','Nord-Kivu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'OR','Orientale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (49,'SK','Sud-Kivu');

INSERT INTO `toc_countries` VALUES (50,'Cook Islands','CK','COK','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'PU','Pukapuka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'RK','Rakahanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'MK','Manihiki');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'PE','Penrhyn');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'NI','Nassau Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'SU','Surwarrow');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'PA','Palmerston');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'AI','Aitutaki');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'MA','Manuae');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'TA','Takutea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'MT','Mitiaro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'AT','Atiu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'MU','Mauke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'RR','Rarotonga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (50,'MG','Mangaia');

INSERT INTO `toc_countries` VALUES (51,'Costa Rica','CR','CRI','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (51,'A','Alajuela');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (51,'C','Cartago');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (51,'G','Guanacaste');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (51,'H','Heredia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (51,'L','Limón');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (51,'P','Puntarenas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (51,'SJ','San José');

INSERT INTO `toc_countries` VALUES (52,'Cote D\'Ivoire','CI','CIV','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'01','Lagunes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'02','Haut-Sassandra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'03','Savanes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'04','Vallée du Bandama');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'05','Moyen-Comoé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'06','Dix-Huit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'07','Lacs');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'08','Zanzan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'09','Bas-Sassandra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'10','Denguélé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'11','N\'zi-Comoé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'12','Marahoué');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'13','Sud-Comoé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'14','Worodouqou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'15','Sud-Bandama');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'16','Agnébi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'17','Bafing');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'18','Fromager');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (52,'19','Moyen-Cavally');

INSERT INTO `toc_countries` VALUES (53,'Croatia','HR','HRV','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'01','Zagrebačka županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'02','Krapinsko-zagorska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'03','Sisačko-moslavačka županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'04','Karlovačka županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'05','Varaždinska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'06','Koprivničko-križevačka županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'07','Bjelovarsko-bilogorska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'08','Primorsko-goranska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'09','Ličko-senjska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'10','Virovitičko-podravska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'11','Požeško-slavonska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'12','Brodsko-posavska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'13','Zadarska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'14','Osječko-baranjska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'15','Šibensko-kninska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'16','Vukovarsko-srijemska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'17','Splitsko-dalmatinska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'18','Istarska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'19','Dubrovačko-neretvanska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'20','Međimurska županija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (53,'21','Zagreb');

INSERT INTO `toc_countries` VALUES (54,'Cuba','CU','CUB','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'01','Pinar del Río');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'02','La Habana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'03','Ciudad de La Habana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'04','Matanzas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'05','Villa Clara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'06','Cienfuegos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'07','Sancti Spíritus');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'08','Ciego de Ávila');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'09','Camagüey');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'10','Las Tunas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'11','Holguín');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'12','Granma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'13','Santiago de Cuba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'14','Guantánamo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (54,'99','Isla de la Juventud');

INSERT INTO `toc_countries` VALUES (55,'Cyprus','CY','CYP','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (55,'01','Κερύvεια');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (55,'02','Λευκωσία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (55,'03','Αμμόχωστος');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (55,'04','Λάρνακα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (55,'05','Λεμεσός');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (55,'06','Πάφος');

INSERT INTO `toc_countries` VALUES (56,'Czech Republic','CZ','CZE','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'JC','Jihočeský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'JM','Jihomoravský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'KA','Karlovarský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'VY','Vysočina kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'KR','Královéhradecký kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'LI','Liberecký kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'MO','Moravskoslezský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'OL','Olomoucký kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'PA','Pardubický kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'PL','Plzeňský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'PR','Hlavní město Praha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'ST','Středočeský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'US','Ústecký kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (56,'ZL','Zlínský kraj');

INSERT INTO `toc_countries` VALUES (57,'Denmark','DK','DNK',":name\n:street_address\nDK-:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'040','Bornholms Regionskommune');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'101','København');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'147','Frederiksberg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'070','Århus Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'015','Københavns Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'020','Frederiksborg Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'042','Fyns Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'080','Nordjyllands Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'055','Ribe Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'065','Ringkjøbing Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'025','Roskilde Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'050','Sønderjyllands Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'035','Storstrøms Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'060','Vejle Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'030','Vestsjællands Amt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (57,'076','Viborg Amt');

INSERT INTO `toc_countries` VALUES (58,'Djibouti','DJ','DJI','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (58,'AS','Region d\'Ali Sabieh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (58,'AR','Region d\'Arta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (58,'DI','Region de Dikhil');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (58,'DJ','Ville de Djibouti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (58,'OB','Region d\'Obock');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (58,'TA','Region de Tadjourah');

INSERT INTO `toc_countries` VALUES (59,'Dominica','DM','DMA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'AND','Saint Andrew Parish');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'DAV','Saint David Parish');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'GEO','Saint George Parish');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'JOH','Saint John Parish');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'JOS','Saint Joseph Parish');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'LUK','Saint Luke Parish');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'MAR','Saint Mark Parish');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'PAT','Saint Patrick Parish');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'PAU','Saint Paul Parish');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (59,'PET','Saint Peter Parish');

INSERT INTO `toc_countries` VALUES (60,'Dominican Republic','DO','DOM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'01','Distrito Nacional');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'02','Ázua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'03','Baoruco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'04','Barahona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'05','Dajabón');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'06','Duarte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'07','Elías Piña');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'08','El Seibo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'09','Espaillat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'10','Independencia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'11','La Altagracia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'12','La Romana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'13','La Vega');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'14','María Trinidad Sánchez');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'15','Monte Cristi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'16','Pedernales');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'17','Peravia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'18','Puerto Plata');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'19','Salcedo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'20','Samaná');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'21','San Cristóbal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'22','San Juan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'23','San Pedro de Macorís');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'24','Sánchez Ramírez');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'25','Santiago');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'26','Santiago Rodríguez');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'27','Valverde');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'28','Monseñor Nouel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'29','Monte Plata');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (60,'30','Hato Mayor');

INSERT INTO `toc_countries` VALUES (61,'East Timor','TP','TMP','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'AL','Aileu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'AN','Ainaro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'BA','Baucau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'BO','Bobonaro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'CO','Cova-Lima');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'DI','Dili');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'ER','Ermera');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'LA','Lautem');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'LI','Liquiçá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'MF','Manufahi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'MT','Manatuto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'OE','Oecussi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (61,'VI','Viqueque');

INSERT INTO `toc_countries` VALUES (62,'Ecuador','EC','ECU','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'A','Azuay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'B','Bolívar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'C','Carchi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'D','Orellana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'E','Esmeraldas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'F','Cañar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'G','Guayas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'H','Chimborazo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'I','Imbabura');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'L','Loja');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'M','Manabí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'N','Napo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'O','El Oro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'P','Pichincha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'R','Los Ríos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'S','Morona-Santiago');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'T','Tungurahua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'U','Sucumbíos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'W','Galápagos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'X','Cotopaxi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'Y','Pastaza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (62,'Z','Zamora-Chinchipe');

INSERT INTO `toc_countries` VALUES (63,'Egypt','EG','EGY','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'ALX','الإسكندرية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'ASN','أسوان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'AST','أسيوط');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'BA','البحر الأحمر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'BH','البحيرة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'BNS','بني سويف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'C','القاهرة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'DK','الدقهلية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'DT','دمياط');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'FYM','الفيوم');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'GH','الغربية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'GZ','الجيزة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'IS','الإسماعيلية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'JS','جنوب سيناء');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'KB','القليوبية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'KFS','كفر الشيخ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'KN','قنا');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'MN','محافظة المنيا');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'MNF','المنوفية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'MT','مطروح');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'PTS','محافظة بور سعيد');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'SHG','محافظة سوهاج');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'SHR','المحافظة الشرقيّة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'SIN','شمال سيناء');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'SUZ','السويس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (63,'WAD','الوادى الجديد');

INSERT INTO `toc_countries` VALUES (64,'El Salvador','SV','SLV','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'AH','Ahuachapán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'CA','Cabañas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'CH','Chalatenango');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'CU','Cuscatlán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'LI','La Libertad');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'MO','Morazán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'PA','La Paz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'SA','Santa Ana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'SM','San Miguel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'SO','Sonsonate');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'SS','San Salvador');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'SV','San Vicente');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'UN','La Unión');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (64,'US','Usulután');

INSERT INTO `toc_countries` VALUES (65,'Equatorial Guinea','GQ','GNQ','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (65,'AN','Annobón');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (65,'BN','Bioko Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (65,'BS','Bioko Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (65,'CS','Centro Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (65,'KN','Kié-Ntem');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (65,'LI','Litoral');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (65,'WN','Wele-Nzas');

INSERT INTO `toc_countries` VALUES (66,'Eritrea','ER','ERI','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (66,'AN','Zoba Anseba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (66,'DK','Zoba Debubawi Keyih Bahri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (66,'DU','Zoba Debub');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (66,'GB','Zoba Gash-Barka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (66,'MA','Zoba Ma\'akel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (66,'SK','Zoba Semienawi Keyih Bahri');

INSERT INTO `toc_countries` VALUES (67,'Estonia','EE','EST','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'37','Harju maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'39','Hiiu maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'44','Ida-Viru maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'49','Jõgeva maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'51','Järva maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'57','Lääne maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'59','Lääne-Viru maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'65','Põlva maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'67','Pärnu maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'70','Rapla maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'74','Saare maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'78','Tartu maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'82','Valga maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'84','Viljandi maakond');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (67,'86','Võru maakond');

INSERT INTO `toc_countries` VALUES (68,'Ethiopia','ET','ETH','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'AA','አዲስ አበባ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'AF','አፋር');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'AH','አማራ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'BG','ቤንሻንጉል-ጉምዝ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'DD','ድሬዳዋ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'GB','ጋምቤላ ሕዝቦች');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'HR','ሀረሪ ሕዝብ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'OR','ኦሮሚያ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'SM','ሶማሌ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'SN','ደቡብ ብሔሮች ብሔረሰቦችና ሕዝቦች');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (68,'TG','ትግራይ');

INSERT INTO `toc_countries` VALUES (69,'Falkland Islands (Malvinas)','FK','FLK','');
INSERT INTO `toc_countries` VALUES (70,'Faroe Islands','FO','FRO','');

INSERT INTO `toc_countries` VALUES (71,'Fiji','FJ','FJI','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (71,'C','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (71,'E','Northern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (71,'N','Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (71,'R','Rotuma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (71,'W','Western');

INSERT INTO `toc_countries` VALUES (72,'Finland','FI','FIN',":name\n:street_address\nFIN-:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (72,'AL','Ahvenanmaan maakunta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (72,'ES','Etelä-Suomen lääni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (72,'IS','Itä-Suomen lääni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (72,'LL','Lapin lääni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (72,'LS','Länsi-Suomen lääni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (72,'OL','Oulun lääni');

INSERT INTO `toc_countries` VALUES (73,'France','FR','FRA',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'01','Ain');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'02','Aisne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'03','Allier');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'04','Alpes-de-Haute-Provence');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'05','Hautes-Alpes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'06','Alpes-Maritimes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'07','Ardèche');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'08','Ardennes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'09','Ariège');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'10','Aube');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'11','Aude');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'12','Aveyron');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'13','Bouches-du-Rhône');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'14','Calvados');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'15','Cantal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'16','Charente');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'17','Charente-Maritime');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'18','Cher');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'19','Corrèze');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'21','Côte-d\'Or');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'22','Côtes-d\'Armor');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'23','Creuse');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'24','Dordogne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'25','Doubs');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'26','Drôme');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'27','Eure');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'28','Eure-et-Loir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'29','Finistère');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'2A','Corse-du-Sud');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'2B','Haute-Corse');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'30','Gard');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'31','Haute-Garonne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'32','Gers');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'33','Gironde');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'34','Hérault');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'35','Ille-et-Vilaine');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'36','Indre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'37','Indre-et-Loire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'38','Isère');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'39','Jura');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'40','Landes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'41','Loir-et-Cher');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'42','Loire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'43','Haute-Loire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'44','Loire-Atlantique');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'45','Loiret');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'46','Lot');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'47','Lot-et-Garonne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'48','Lozère');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'49','Maine-et-Loire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'50','Manche');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'51','Marne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'52','Haute-Marne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'53','Mayenne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'54','Meurthe-et-Moselle');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'55','Meuse');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'56','Morbihan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'57','Moselle');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'58','Nièvre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'59','Nord');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'60','Oise');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'61','Orne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'62','Pas-de-Calais');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'63','Puy-de-Dôme');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'64','Pyrénées-Atlantiques');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'65','Hautes-Pyrénées');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'66','Pyrénées-Orientales');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'67','Bas-Rhin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'68','Haut-Rhin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'69','Rhône');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'70','Haute-Saône');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'71','Saône-et-Loire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'72','Sarthe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'73','Savoie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'74','Haute-Savoie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'75','Paris');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'76','Seine-Maritime');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'77','Seine-et-Marne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'78','Yvelines');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'79','Deux-Sèvres');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'80','Somme');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'81','Tarn');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'82','Tarn-et-Garonne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'83','Var');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'84','Vaucluse');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'85','Vendée');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'86','Vienne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'87','Haute-Vienne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'88','Vosges');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'89','Yonne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'90','Territoire de Belfort');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'91','Essonne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'92','Hauts-de-Seine');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'93','Seine-Saint-Denis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'94','Val-de-Marne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'95','Val-d\'Oise');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'NC','Territoire des Nouvelle-Calédonie et Dependances');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'PF','Polynésie Française');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'PM','Saint-Pierre et Miquelon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'TF','Terres australes et antarctiques françaises');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'YT','Mayotte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (73,'WF','Territoire des îles Wallis et Futuna');

INSERT INTO `toc_countries` VALUES (74,'France, Metropolitan','FX','FXX',":name\n:street_address\n:postcode :city\n:country");
INSERT INTO `toc_countries` VALUES (75,'French Guiana','GF','GUF',":name\n:street_address\n:postcode :city\n:country");
INSERT INTO `toc_countries` VALUES (76,'French Polynesia','PF','PYF',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (76,'M','Archipel des Marquises');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (76,'T','Archipel des Tuamotu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (76,'I','Archipel des Tubuai');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (76,'V','Iles du Vent');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (76,'S','Iles Sous-le-Vent ');

INSERT INTO `toc_countries` VALUES (77,'French Southern Territories','TF','ATF',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (77,'C','Iles Crozet');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (77,'K','Iles Kerguelen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (77,'A','Ile Amsterdam');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (77,'P','Ile Saint-Paul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (77,'D','Adelie Land');

INSERT INTO `toc_countries` VALUES (78,'Gabon','GA','GAB','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (78,'ES','Estuaire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (78,'HO','Haut-Ogooue');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (78,'MO','Moyen-Ogooue');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (78,'NG','Ngounie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (78,'NY','Nyanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (78,'OI','Ogooue-Ivindo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (78,'OL','Ogooue-Lolo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (78,'OM','Ogooue-Maritime');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (78,'WN','Woleu-Ntem');

INSERT INTO `toc_countries` VALUES (79,'Gambia','GM','GMB','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'AH','Ashanti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'BA','Brong-Ahafo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'CP','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'EP','Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'AA','Greater Accra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'NP','Northern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'UE','Upper East');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'UW','Upper West');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'TV','Volta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (79,'WP','Western');

INSERT INTO `toc_countries` VALUES (80,'Georgia','GE','GEO','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'AB','აფხაზეთი');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'AJ','აჭარა');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'GU','გურია');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'IM','იმერეთი');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'KA','კახეთი');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'KK','ქვემო ქართლი');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'MM','მცხეთა-მთიანეთი');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'RL','რაჭა-ლეჩხუმი და ქვემო სვანეთი');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'SJ','სამცხე-ჯავახეთი');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'SK','შიდა ქართლი');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'SZ','სამეგრელო-ზემო სვანეთი');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (80,'TB','თბილისი');

INSERT INTO `toc_countries` VALUES (81,'Germany','DE','DEU',":name\n:street_address\nD-:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'BE','Berlin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'BR','Brandenburg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'BW','Baden-Württemberg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'BY','Bayern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'HB','Bremen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'HE','Hessen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'HH','Hamburg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'MV','Mecklenburg-Vorpommern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'NI','Niedersachsen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'NW','Nordrhein-Westfalen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'RP','Rheinland-Pfalz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'SH','Schleswig-Holstein');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'SL','Saarland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'SN','Sachsen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'ST','Sachsen-Anhalt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (81,'TH','Thüringen');

INSERT INTO `toc_countries` VALUES (82,'Ghana','GH','GHA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'AA','Greater Accra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'AH','Ashanti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'BA','Brong-Ahafo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'CP','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'EP','Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'NP','Northern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'TV','Volta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'UE','Upper East');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'UW','Upper West');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (82,'WP','Western');

INSERT INTO `toc_countries` VALUES (83,'Gibraltar','GI','GIB','');

INSERT INTO `toc_countries` VALUES (84,'Greece','GR','GRC','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'01','Αιτωλοακαρνανία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'03','Βοιωτία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'04','Εύβοια');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'05','Ευρυτανία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'06','Φθιώτιδα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'07','Φωκίδα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'11','Αργολίδα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'12','Αρκαδία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'13','Ἀχαΐα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'14','Ηλεία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'15','Κορινθία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'16','Λακωνία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'17','Μεσσηνία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'21','Ζάκυνθος');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'22','Κέρκυρα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'23','Κεφαλλονιά');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'24','Λευκάδα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'31','Άρτα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'32','Θεσπρωτία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'33','Ιωάννινα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'34','Πρεβεζα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'41','Καρδίτσα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'42','Λάρισα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'43','Μαγνησία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'44','Τρίκαλα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'51','Γρεβενά');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'52','Δράμα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'53','Ημαθία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'54','Θεσσαλονίκη');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'55','Καβάλα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'56','Καστοριά');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'57','Κιλκίς');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'58','Κοζάνη');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'59','Πέλλα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'61','Πιερία');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'62','Σερρών');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'63','Φλώρινα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'64','Χαλκιδική');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'69','Όρος Άθως');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'71','Έβρος');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'72','Ξάνθη');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'73','Ροδόπη');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'81','Δωδεκάνησα');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'82','Κυκλάδες');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'83','Λέσβου');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'84','Σάμος');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'85','Χίος');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'91','Ηράκλειο');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'92','Λασίθι');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'93','Ρεθύμνο');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'94','Χανίων');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (84,'A1','Αττική');

INSERT INTO `toc_countries` VALUES (85,'Greenland','GL','GRL',":name\n:street_address\nDK-:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (85,'A','Avannaa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (85,'T','Tunu ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (85,'K','Kitaa');

INSERT INTO `toc_countries` VALUES (86,'Grenada','GD','GRD','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (86,'A','Saint Andrew');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (86,'D','Saint David');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (86,'G','Saint George');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (86,'J','Saint John');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (86,'M','Saint Mark');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (86,'P','Saint Patrick');

INSERT INTO `toc_countries` VALUES (87,'Guadeloupe','GP','GLP','');
INSERT INTO `toc_countries` VALUES (88,'Guam','GU','GUM','');

INSERT INTO `toc_countries` VALUES (89,'Guatemala','GT','GTM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'AV','Alta Verapaz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'BV','Baja Verapaz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'CM','Chimaltenango');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'CQ','Chiquimula');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'ES','Escuintla');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'GU','Guatemala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'HU','Huehuetenango');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'IZ','Izabal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'JA','Jalapa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'JU','Jutiapa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'PE','El Petén');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'PR','El Progreso');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'QC','El Quiché');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'QZ','Quetzaltenango');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'RE','Retalhuleu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'SA','Sacatepéquez');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'SM','San Marcos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'SO','Sololá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'SR','Santa Rosa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'SU','Suchitepéquez');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'TO','Totonicapán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (89,'ZA','Zacapa');

INSERT INTO `toc_countries` VALUES (90,'Guinea','GN','GIN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'BE','Beyla');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'BF','Boffa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'BK','Boké');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'CO','Coyah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'DB','Dabola');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'DI','Dinguiraye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'DL','Dalaba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'DU','Dubréka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'FA','Faranah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'FO','Forécariah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'FR','Fria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'GA','Gaoual');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'GU','Guékédou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'KA','Kankan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'KB','Koubia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'KD','Kindia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'KE','Kérouané');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'KN','Koundara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'KO','Kouroussa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'KS','Kissidougou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'LA','Labé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'LE','Lélouma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'LO','Lola');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'MC','Macenta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'MD','Mandiana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'ML','Mali');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'MM','Mamou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'NZ','Nzérékoré');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'PI','Pita');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'SI','Siguiri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'TE','Télimélé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'TO','Tougué');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (90,'YO','Yomou');

INSERT INTO `toc_countries` VALUES (91,'Guinea-Bissau','GW','GNB','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (91,'BF','Bafata');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (91,'BB','Biombo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (91,'BS','Bissau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (91,'BL','Bolama');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (91,'CA','Cacheu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (91,'GA','Gabu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (91,'OI','Oio');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (91,'QU','Quinara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (91,'TO','Tombali');

INSERT INTO `toc_countries` VALUES (92,'Guyana','GY','GUY','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'BA','Barima-Waini');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'CU','Cuyuni-Mazaruni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'DE','Demerara-Mahaica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'EB','East Berbice-Corentyne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'ES','Essequibo Islands-West Demerara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'MA','Mahaica-Berbice');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'PM','Pomeroon-Supenaam');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'PT','Potaro-Siparuni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'UD','Upper Demerara-Berbice');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (92,'UT','Upper Takutu-Upper Essequibo');

INSERT INTO `toc_countries` VALUES (93,'Haiti','HT','HTI','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'AR','Artibonite');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'CE','Centre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'GA','Grand\'Anse');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'NI','Nippes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'ND','Nord');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'NE','Nord-Est');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'NO','Nord-Ouest');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'OU','Ouest');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'SD','Sud');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (93,'SE','Sud-Est');

INSERT INTO `toc_countries` VALUES (94,'Heard and McDonald Islands','HM','HMD','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (94,'F','Flat Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (94,'M','McDonald Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (94,'S','Shag Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (94,'H','Heard Island');

INSERT INTO `toc_countries` VALUES (95,'Honduras','HN','HND','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'AT','Atlántida');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'CH','Choluteca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'CL','Colón');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'CM','Comayagua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'CP','Copán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'CR','Cortés');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'EP','El Paraíso');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'FM','Francisco Morazán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'GD','Gracias a Dios');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'IB','Islas de la Bahía');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'IN','Intibucá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'LE','Lempira');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'LP','La Paz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'OC','Ocotepeque');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'OL','Olancho');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'SB','Santa Bárbara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'VA','Valle');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (95,'YO','Yoro');

INSERT INTO `toc_countries` VALUES (96,'Hong Kong','HK','HKG',":name\n:street_address\n:city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'HCW','中西區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'HEA','東區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'HSO','南區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'HWC','灣仔區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'KKC','九龍城區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'KKT','觀塘區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'KSS','深水埗區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'KWT','黃大仙區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'KYT','油尖旺區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'NIS','離島區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'NKT','葵青區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'NNO','北區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'NSK','西貢區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'NST','沙田區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'NTP','大埔區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'NTW','荃灣區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'NTM','屯門區');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (96,'NYL','元朗區');

INSERT INTO `toc_countries` VALUES (97,'Hungary','HU','HUN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'BA','Baranja megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'BC','Békéscsaba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'BE','Békés megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'BK','Bács-Kiskun megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'BU','Budapest');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'BZ','Borsod-Abaúj-Zemplén megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'CS','Csongrád megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'DE','Debrecen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'DU','Dunaújváros');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'EG','Eger');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'FE','Fejér megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'GS','Győr-Moson-Sopron megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'GY','Győr');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'HB','Hajdú-Bihar megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'HE','Heves megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'HV','Hódmezővásárhely');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'JN','Jász-Nagykun-Szolnok megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'KE','Komárom-Esztergom megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'KM','Kecskemét');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'KV','Kaposvár');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'MI','Miskolc');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'NK','Nagykanizsa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'NO','Nógrád megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'NY','Nyíregyháza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'PE','Pest megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'PS','Pécs');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'SD','Szeged');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'SF','Székesfehérvár');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'SH','Szombathely');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'SK','Szolnok');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'SN','Sopron');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'SO','Somogy megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'SS','Szekszárd');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'ST','Salgótarján');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'SZ','Szabolcs-Szatmár-Bereg megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'TB','Tatabánya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'TO','Tolna megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'VA','Vas megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'VE','Veszprém megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'VM','Veszprém');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'ZA','Zala megye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (97,'ZE','Zalaegerszeg');

INSERT INTO `toc_countries` VALUES (98,'Iceland','IS','ISL',":name\n:street_address\nIS:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (98,'1','Höfuðborgarsvæðið');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (98,'2','Suðurnes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (98,'3','Vesturland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (98,'4','Vestfirðir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (98,'5','Norðurland vestra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (98,'6','Norðurland eystra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (98,'7','Austfirðir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (98,'8','Suðurland');

INSERT INTO `toc_countries` VALUES (99,'India','IN','IND',":name\n:street_address\n:city-:postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-AN','अंडमान और निकोबार द्वीप');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-AP','ఆంధ్ర ప్రదేశ్');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-AR','अरुणाचल प्रदेश');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-AS','অসম');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-BR','बिहार');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-CH','चंडीगढ़');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-CT','छत्तीसगढ़');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-DD','દમણ અને દિવ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-DL','दिल्ली');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-DN','દાદરા અને નગર હવેલી');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-GA','गोंय');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-GJ','ગુજરાત');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-HP','हिमाचल प्रदेश');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-HR','हरियाणा');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-JH','झारखंड');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-JK','जम्मू और कश्मीर');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-KA','ಕನಾ೯ಟಕ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-KL','കേരളം');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-LD','ലക്ഷദ്വീപ്');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-ML','मेघालय');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-MH','महाराष्ट्र');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-MN','मणिपुर');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-MP','मध्य प्रदेश');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-MZ','मिज़ोरम');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-NL','नागालैंड');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-OR','उड़ीसा');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-PB','ਪੰਜਾਬ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-PY','புதுச்சேரி');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-RJ','राजस्थान');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-SK','सिक्किम');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-TN','தமிழ் நாடு');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-TR','ত্রিপুরা');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-UL','उत्तरांचल');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-UP','उत्तर प्रदेश');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (99,'IN-WB','পশ্চিমবঙ্গ');

INSERT INTO `toc_countries` VALUES (100,'Indonesia','ID','IDN',":name\n:street_address\n:city :postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'AC','Aceh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'BA','Bali');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'BB','Bangka-Belitung');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'BE','Bengkulu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'BT','Banten');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'GO','Gorontalo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'IJ','Papua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'JA','Jambi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'JI','Jawa Timur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'JK','Jakarta Raya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'JR','Jawa Barat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'JT','Jawa Tengah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'KB','Kalimantan Barat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'KI','Kalimantan Timur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'KS','Kalimantan Selatan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'KT','Kalimantan Tengah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'LA','Lampung');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'MA','Maluku');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'MU','Maluku Utara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'NB','Nusa Tenggara Barat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'NT','Nusa Tenggara Timur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'RI','Riau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'SB','Sumatera Barat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'SG','Sulawesi Tenggara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'SL','Sumatera Selatan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'SN','Sulawesi Selatan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'ST','Sulawesi Tengah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'SW','Sulawesi Utara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'SU','Sumatera Utara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (100,'YO','Yogyakarta');

INSERT INTO `toc_countries` VALUES (101,'Iran','IR','IRN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'01','محافظة آذربایجان شرقي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'02','محافظة آذربایجان غربي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'03','محافظة اردبیل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'04','محافظة اصفهان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'05','محافظة ایلام');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'06','محافظة بوشهر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'07','محافظة طهران');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'08','محافظة چهارمحل و بختیاري');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'09','محافظة خراسان رضوي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'10','محافظة خوزستان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'11','محافظة زنجان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'12','محافظة سمنان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'13','محافظة سيستان وبلوتشستان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'14','محافظة فارس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'15','محافظة کرمان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'16','محافظة کردستان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'17','محافظة کرمانشاه');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'18','محافظة کهکیلویه و بویر أحمد');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'19','محافظة گیلان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'20','محافظة لرستان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'21','محافظة مازندران');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'22','محافظة مرکزي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'23','محافظة هرمزگان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'24','محافظة همدان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'25','محافظة یزد');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'26','محافظة قم');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'27','محافظة گلستان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (101,'28','محافظة قزوين');

INSERT INTO `toc_countries` VALUES (102,'Iraq','IQ','IRQ','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'AN','محافظة الأنبار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'AR','أربيل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'BA','محافظة البصرة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'BB','بابل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'BG','محافظة بغداد');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'DA','دهوك');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'DI','ديالى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'DQ','ذي قار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'KA','كربلاء');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'MA','ميسان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'MU','المثنى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'NA','النجف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'NI','نینوى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'QA','القادسية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'SD','صلاح الدين');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'SW','محافظة السليمانية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'TS','التأمیم');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (102,'WA','واسط');

INSERT INTO `toc_countries` VALUES (103,'Ireland','IE','IRL',":name\n:street_address\nIE-:city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'C','Corcaigh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'CE','Contae an Chláir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'CN','An Cabhán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'CW','Ceatharlach');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'D','Baile Átha Cliath');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'DL','Dún na nGall');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'G','Gaillimh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'KE','Cill Dara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'KK','Cill Chainnigh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'KY','Contae Chiarraí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'LD','An Longfort');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'LH','Contae Lú');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'LK','Luimneach');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'LM','Contae Liatroma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'LS','Contae Laoise');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'MH','Contae na Mí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'MN','Muineachán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'MO','Contae Mhaigh Eo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'OY','Contae Uíbh Fhailí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'RN','Ros Comáin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'SO','Sligeach');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'TA','Tiobraid Árann');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'WD','Port Lairge');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'WH','Contae na hIarmhí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'WW','Cill Mhantáin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (103,'WX','Loch Garman');

INSERT INTO `toc_countries` VALUES (104,'Israel','IL','ISR',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (104,'D ','מחוז הדרום');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (104,'HA','מחוז חיפה');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (104,'JM','ירושלים');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (104,'M ','מחוז המרכז');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (104,'TA','תל אביב-יפו');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (104,'Z ','מחוז הצפון');

INSERT INTO `toc_countries` VALUES (105,'Italy','IT','ITA',":name\n:street_address\n:postcode-:city :state_code\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'AG','Agrigento');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'AL','Alessandria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'AN','Ancona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'AO','Valle d\'Aosta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'AP','Ascoli Piceno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'AQ','L\'Aquila');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'AR','Arezzo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'AT','Asti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'AV','Avellino');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BA','Bari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BG','Bergamo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BI','Biella');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BL','Belluno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BN','Benevento');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BO','Bologna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BR','Brindisi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BS','Brescia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BT','Barletta-Andria-Trani');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'BZ','Alto Adige');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CA','Cagliari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CB','Campobasso');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CE','Caserta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CH','Chieti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CI','Carbonia-Iglesias');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CL','Caltanissetta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CN','Cuneo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CO','Como');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CR','Cremona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CS','Cosenza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CT','Catania');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'CZ','Catanzaro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'EN','Enna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'FE','Ferrara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'FG','Foggia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'FI','Firenze');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'FM','Fermo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'FO','Forlì-Cesena');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'FR','Frosinone');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'GE','Genova');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'GO','Gorizia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'GR','Grosseto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'IM','Imperia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'IS','Isernia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'KR','Crotone');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'LC','Lecco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'LE','Lecce');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'LI','Livorno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'LO','Lodi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'LT','Latina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'LU','Lucca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'MC','Macerata');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'MD','Medio Campidano');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'ME','Messina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'MI','Milano');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'MN','Mantova');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'MO','Modena');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'MS','Massa-Carrara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'MT','Matera');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'MZ','Monza e Brianza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'NA','Napoli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'NO','Novara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'NU','Nuoro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'OG','Ogliastra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'OR','Oristano');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'OT','Olbia-Tempio');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PA','Palermo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PC','Piacenza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PD','Padova');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PE','Pescara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PG','Perugia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PI','Pisa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PN','Pordenone');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PO','Prato');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PR','Parma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PS','Pesaro e Urbino');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PT','Pistoia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PV','Pavia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'PZ','Potenza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'RA','Ravenna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'RC','Reggio Calabria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'RE','Reggio Emilia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'RG','Ragusa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'RI','Rieti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'RM','Roma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'RN','Rimini');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'RO','Rovigo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'SA','Salerno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'SI','Siena');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'SO','Sondrio');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'SP','La Spezia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'SR','Siracusa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'SS','Sassari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'SV','Savona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'TA','Taranto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'TE','Teramo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'TN','Trento');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'TO','Torino');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'TP','Trapani');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'TR','Terni');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'TS','Trieste');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'TV','Treviso');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'UD','Udine');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'VA','Varese');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'VB','Verbano-Cusio-Ossola');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'VC','Vercelli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'VE','Venezia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'VI','Vicenza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'VR','Verona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'VT','Viterbo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (105,'VV','Vibo Valentia');

INSERT INTO `toc_countries` VALUES (106,'Jamaica','JM','JAM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'01','Kingston');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'02','Half Way Tree');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'03','Morant Bay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'04','Port Antonio');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'05','Port Maria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'06','Saint Ann\'s Bay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'07','Falmouth');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'08','Montego Bay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'09','Lucea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'10','Savanna-la-Mar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'11','Black River');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'12','Mandeville');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'13','May Pen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (106,'14','Spanish Town');

INSERT INTO `toc_countries` VALUES (107,'Japan','JP','JPN',":name\n:street_address, :suburb\n:city :postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'01','北海道');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'02','青森');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'03','岩手');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'04','宮城');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'05','秋田');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'06','山形');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'07','福島');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'08','茨城');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'09','栃木');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'10','群馬');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'11','埼玉');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'12','千葉');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'13','東京');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'14','神奈川');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'15','新潟');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'16','富山');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'17','石川');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'18','福井');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'19','山梨');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'20','長野');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'21','岐阜');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'22','静岡');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'23','愛知');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'24','三重');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'25','滋賀');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'26','京都');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'27','大阪');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'28','兵庫');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'29','奈良');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'30','和歌山');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'31','鳥取');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'32','島根');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'33','岡山');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'34','広島');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'35','山口');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'36','徳島');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'37','香川');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'38','愛媛');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'39','高知');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'40','福岡');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'41','佐賀');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'42','長崎');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'43','熊本');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'44','大分');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'45','宮崎');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'46','鹿児島');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (107,'47','沖縄');

INSERT INTO `toc_countries` VALUES (108,'Jordan','JO','JOR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'AJ','محافظة عجلون');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'AM','محافظة العاصمة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'AQ','محافظة العقبة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'AT','محافظة الطفيلة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'AZ','محافظة الزرقاء');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'BA','محافظة البلقاء');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'JA','محافظة جرش');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'JR','محافظة إربد');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'KA','محافظة الكرك');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'MA','محافظة المفرق');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'MD','محافظة مادبا');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (108,'MN','محافظة معان');

INSERT INTO `toc_countries` VALUES (109,'Kazakhstan','KZ','KAZ','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'AL','Алматы');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'AC','Almaty City');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'AM','Ақмола');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'AQ','Ақтөбе');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'AS','Астана');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'AT','Атырау');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'BA','Батыс Қазақстан');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'BY','Байқоңыр');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'MA','Маңғыстау');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'ON','Оңтүстік Қазақстан');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'PA','Павлодар');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'QA','Қарағанды');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'QO','Қостанай');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'QY','Қызылорда');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'SH','Шығыс Қазақстан');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'SO','Солтүстік Қазақстан');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (109,'ZH','Жамбыл');

INSERT INTO `toc_countries` VALUES (110,'Kenya','KE','KEN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (110,'110','Nairobi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (110,'200','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (110,'300','Mombasa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (110,'400','Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (110,'500','North Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (110,'600','Nyanza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (110,'700','Rift Valley');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (110,'900','Western');

INSERT INTO `toc_countries` VALUES (111,'Kiribati','KI','KIR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (111,'G','Gilbert Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (111,'L','Line Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (111,'P','Phoenix Islands');

INSERT INTO `toc_countries` VALUES (112,'Korea, North','KP','PRK','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'CHA','자강도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'HAB','함경 북도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'HAN','함경 남도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'HWB','황해 북도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'HWN','황해 남도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'KAN','강원도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'KAE','개성시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'NAJ','라선 직할시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'NAM','남포 특급시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'PYB','평안 북도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'PYN','평안 남도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'PYO','평양 직할시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (112,'YAN','량강도');

INSERT INTO `toc_countries` VALUES (113,'Korea, South','KR','KOR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'11','서울특별시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'26','부산 광역시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'27','대구 광역시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'28','인천광역시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'29','광주 광역시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'30','대전 광역시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'31','울산 광역시');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'41','경기도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'42','강원도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'43','충청 북도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'44','충청 남도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'45','전라 북도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'46','전라 남도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'47','경상 북도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'48','경상 남도');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (113,'49','제주특별자치도');

INSERT INTO `toc_countries` VALUES (114,'Kuwait','KW','KWT','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (114,'AH','الاحمدي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (114,'FA','الفروانية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (114,'JA','الجهراء');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (114,'KU','ألعاصمه');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (114,'HW','حولي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (114,'MU','مبارك الكبير');

INSERT INTO `toc_countries` VALUES (115,'Kyrgyzstan','KG','KGZ','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (115,'B','Баткен областы');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (115,'C','Чүй областы');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (115,'GB','Бишкек');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (115,'J','Жалал-Абад областы');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (115,'N','Нарын областы');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (115,'O','Ош областы');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (115,'T','Талас областы');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (115,'Y','Ысык-Көл областы');

INSERT INTO `toc_countries` VALUES (116,'Laos','LA','LAO','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'AT','ອັດຕະປື');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'BK','ບໍ່ແກ້ວ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'BL','ບໍລິຄໍາໄຊ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'CH','ຈໍາປາສັກ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'HO','ຫົວພັນ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'KH','ຄໍາມ່ວນ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'LM','ຫລວງນໍ້າທາ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'LP','ຫລວງພະບາງ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'OU','ອຸດົມໄຊ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'PH','ຜົງສາລີ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'SL','ສາລະວັນ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'SV','ສະຫວັນນະເຂດ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'VI','ວຽງຈັນ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'VT','ວຽງຈັນ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'XA','ໄຊຍະບູລີ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'XE','ເຊກອງ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'XI','ຊຽງຂວາງ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (116,'XN','ໄຊສົມບູນ');

INSERT INTO `toc_countries` VALUES (117,'Latvia','LV','LVA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'AI','Aizkraukles rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'AL','Alūksnes rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'BL','Balvu rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'BU','Bauskas rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'CE','Cēsu rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'DA','Daugavpils rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'DGV','Daugpilis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'DO','Dobeles rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'GU','Gulbenes rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'JEL','Jelgava');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'JK','Jēkabpils rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'JL','Jelgavas rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'JUR','Jūrmala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'KR','Krāslavas rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'KU','Kuldīgas rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'LE','Liepājas rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'LM','Limbažu rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'LPX','Liepoja');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'LU','Ludzas rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'MA','Madonas rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'OG','Ogres rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'PR','Preiļu rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'RE','Rēzeknes rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'REZ','Rēzekne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'RI','Rīgas rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'RIX','Rīga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'SA','Saldus rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'TA','Talsu rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'TU','Tukuma rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'VE','Ventspils rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'VEN','Ventspils');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'VK','Valkas rajons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (117,'VM','Valmieras rajons');

INSERT INTO `toc_countries` VALUES (118,'Lebanon','LB','LBN','');

INSERT INTO `toc_countries` VALUES (119,'Lesotho','LS','LSO','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'A','Maseru');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'B','Butha-Buthe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'C','Leribe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'D','Berea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'E','Mafeteng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'F','Mohale\'s Hoek');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'G','Quthing');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'H','Qacha\'s Nek');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'J','Mokhotlong');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (119,'K','Thaba-Tseka');

INSERT INTO `toc_countries` VALUES (120,'Liberia','LR','LBR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'BG','Bong');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'BM','Bomi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'CM','Grand Cape Mount');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'GB','Grand Bassa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'GG','Grand Gedeh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'GK','Grand Kru');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'GP','Gbarpolu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'LO','Lofa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'MG','Margibi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'MO','Montserrado');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'MY','Maryland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'NI','Nimba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'RG','River Gee');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'RI','Rivercess');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (120,'SI','Sinoe');

INSERT INTO `toc_countries` VALUES (121,'Libyan Arab Jamahiriya','LY','LBY','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'AJ','Ajdābiyā');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'BA','Banghāzī');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'BU','Al Buţnān');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'BW','Banī Walīd');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'DR','Darnah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'GD','Ghadāmis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'GR','Gharyān');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'GT','Ghāt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'HZ','Al Ḩizām al Akhḑar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'JA','Al Jabal al Akhḑar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'JB','Jaghbūb');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'JI','Al Jifārah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'JU','Al Jufrah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'KF','Al Kufrah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'MB','Al Marqab');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'MI','Mişrātah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'MJ','Al Marj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'MQ','Murzuq');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'MZ','Mizdah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'NL','Nālūt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'NQ','An Nuqaţ al Khams');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'QB','Al Qubbah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'QT','Al Qaţrūn');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'SB','Sabhā');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'SH','Ash Shāţi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'SR','Surt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'SS','Şabrātah Şurmān');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'TB','Ţarābulus');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'TM','Tarhūnah-Masallātah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'TN','Tājūrā wa an Nawāḩī al Arbāʻ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'WA','Al Wāḩah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'WD','Wādī al Ḩayāt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'YJ','Yafran-Jādū');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (121,'ZA','Az Zāwiyah');

INSERT INTO `toc_countries` VALUES (122,'Liechtenstein','LI','LIE','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'B','Balzers');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'E','Eschen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'G','Gamprin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'M','Mauren');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'P','Planken');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'R','Ruggell');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'A','Schaan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'L','Schellenberg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'N','Triesen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'T','Triesenberg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (122,'V','Vaduz');

INSERT INTO `toc_countries` VALUES (123,'Lithuania','LT','LTU','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'AL','Alytaus Apskritis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'KL','Klaipėdos Apskritis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'KU','Kauno Apskritis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'MR','Marijampolės Apskritis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'PN','Panevėžio Apskritis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'SA','Šiaulių Apskritis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'TA','Tauragės Apskritis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'TE','Telšių Apskritis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'UT','Utenos Apskritis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (123,'VL','Vilniaus Apskritis');

INSERT INTO `toc_countries` VALUES (124,'Luxembourg','LU','LUX',":name\n:street_address\nL-:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (124,'D','Diekirch');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (124,'G','Grevenmacher');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (124,'L','Luxemburg');

INSERT INTO `toc_countries` VALUES (125,'Macau','MO','MAC','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (125,'I','海島市');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (125,'M','澳門市');

INSERT INTO `toc_countries` VALUES (126,'Macedonia','MK','MKD','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'BR','Berovo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'CH','Чешиново-Облешево');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'DL','Делчево');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'KB','Карбинци');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'OC','Кочани');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'LO','Лозово');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'MK','Македонска каменица');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'PH','Пехчево');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'PT','Пробиштип');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'ST','Штип');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'SL','Свети Николе');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'NI','Виница');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'ZR','Зрновци');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'KY','Кратово');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'KZ','Крива Паланка');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'UM','Куманово');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'LI','Липково');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'RN','Ранковце');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'NA','Старо Нагоричане');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'TL','Битола');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'DM','Демир Хисар');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'DE','Долнени');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'KG','Кривогаштани');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'KS','Крушево');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'MG','Могила');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'NV','Новаци');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'PP','Прилеп');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'RE','Ресен');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'VJ','Боговиње');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'BN','Брвеница');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'GT','Гостивар');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'JG','Јегуновце');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'MR','Маврово и Ростуша');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'TR','Теарце');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'ET','Тетово');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'VH','Врапчиште');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'ZE','Желино');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'AD','Аеродром');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'AR','Арачиново');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'BU','Бутел');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'CI','Чаир');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'CE','Центар');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'CS','Чучер Сандево');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'GB','Гази Баба');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'GP','Ѓорче Петров');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'IL','Илинден');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'KX','Карпош');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'VD','Кисела Вода');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'PE','Петровец');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'AJ','Сарај');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'SS','Сопиште');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'SU','Студеничани');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'SO','Шуто Оризари');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'ZK','Зелениково');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'BG','Богданци');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'BS','Босилово');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'GV','Гевгелија');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'KN','Конче');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'NS','Ново Село');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'RV','Радовиш');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'SD','Стар Дојран');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'RU','Струмица');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'VA','Валандово');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'VL','Василево');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'CZ','Центар Жупа');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'DB','Дебар');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'DA','Дебарца');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'DR','Другово');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'KH','Кичево');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'MD','Македонски Брод');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'OD','Охрид');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'OS','Осломеј');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'PN','Пласница');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'UG','Струга');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'VV','Вевчани');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'VC','Вранештица');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'ZA','Зајас');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'CA','Чашка');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'DK','Демир Капија');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'GR','Градско');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'AV','Кавадарци');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'NG','Неготино');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'RM','Росоман');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (126,'VE','Велес');

INSERT INTO `toc_countries` VALUES (127,'Madagascar','MG','MDG','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (127,'A','Toamasina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (127,'D','Antsiranana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (127,'F','Fianarantsoa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (127,'M','Mahajanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (127,'T','Antananarivo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (127,'U','Toliara');

INSERT INTO `toc_countries` VALUES (128,'Malawi','MW','MWI','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'BA','Balaka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'BL','Blantyre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'C','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'CK','Chikwawa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'CR','Chiradzulu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'CT','Chitipa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'DE','Dedza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'DO','Dowa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'KR','Karonga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'KS','Kasungu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'LK','Likoma Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'LI','Lilongwe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'MH','Machinga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'MG','Mangochi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'MC','Mchinji');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'MU','Mulanje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'MW','Mwanza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'MZ','Mzimba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'N','Northern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'NB','Nkhata');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'NK','Nkhotakota');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'NS','Nsanje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'NU','Ntcheu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'NI','Ntchisi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'PH','Phalombe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'RU','Rumphi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'S','Southern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'SA','Salima');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'TH','Thyolo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (128,'ZO','Zomba');

INSERT INTO `toc_countries` VALUES (129,'Malaysia','MY','MYS','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'01','Johor Darul Takzim');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'02','Kedah Darul Aman');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'03','Kelantan Darul Naim');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'04','Melaka Negeri Bersejarah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'05','Negeri Sembilan Darul Khusus');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'06','Pahang Darul Makmur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'07','Pulau Pinang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'08','Perak Darul Ridzuan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'09','Perlis Indera Kayangan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'10','Selangor Darul Ehsan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'11','Terengganu Darul Iman');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'12','Sabah Negeri Di Bawah Bayu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'13','Sarawak Bumi Kenyalang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'14','Wilayah Persekutuan Kuala Lumpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'15','Wilayah Persekutuan Labuan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (129,'16','Wilayah Persekutuan Putrajaya');

INSERT INTO `toc_countries` VALUES (130,'Maldives','MV','MDV','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'THU','Thiladhunmathi Uthuru');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'THD','Thiladhunmathi Dhekunu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'MLU','Miladhunmadulu Uthuru');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'MLD','Miladhunmadulu Dhekunu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'MAU','Maalhosmadulu Uthuru');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'MAD','Maalhosmadulu Dhekunu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'FAA','Faadhippolhu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'MAA','Male Atoll');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'AAU','Ari Atoll Uthuru');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'AAD','Ari Atoll Dheknu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'FEA','Felidhe Atoll');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'MUA','Mulaku Atoll');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'NAU','Nilandhe Atoll Uthuru');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'NAD','Nilandhe Atoll Dhekunu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'KLH','Kolhumadulu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'HDH','Hadhdhunmathi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'HAU','Huvadhu Atoll Uthuru');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'HAD','Huvadhu Atoll Dhekunu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'FMU','Fua Mulaku');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (130,'ADD','Addu');

INSERT INTO `toc_countries` VALUES (131,'Mali','ML','MLI','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (131,'1','Kayes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (131,'2','Koulikoro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (131,'3','Sikasso');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (131,'4','Ségou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (131,'5','Mopti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (131,'6','Tombouctou');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (131,'7','Gao');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (131,'8','Kidal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (131,'BK0','Bamako');

INSERT INTO `toc_countries` VALUES (132,'Malta','MT','MLT','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'ATT','Attard');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'BAL','Balzan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'BGU','Birgu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'BKK','Birkirkara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'BRZ','Birzebbuga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'BOR','Bormla');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'DIN','Dingli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'FGU','Fgura');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'FLO','Floriana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'GDJ','Gudja');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'GZR','Gzira');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'GRG','Gargur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'GXQ','Gaxaq');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'HMR','Hamrun');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'IKL','Iklin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'ISL','Isla');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'KLK','Kalkara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'KRK','Kirkop');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'LIJ','Lija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'LUQ','Luqa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MRS','Marsa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MKL','Marsaskala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MXL','Marsaxlokk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MDN','Mdina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MEL','Melliea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MGR','Mgarr');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MST','Mosta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MQA','Mqabba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MSI','Msida');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MTF','Mtarfa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'NAX','Naxxar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'PAO','Paola');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'PEM','Pembroke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'PIE','Pieta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'QOR','Qormi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'QRE','Qrendi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'RAB','Rabat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SAF','Safi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SGI','San Giljan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SLU','Santa Lucija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SPB','San Pawl il-Bahar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SGW','San Gwann');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SVE','Santa Venera');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SIG','Siggiewi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SLM','Sliema');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SWQ','Swieqi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'TXB','Ta Xbiex');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'TRX','Tarxien');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'VLT','Valletta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'XGJ','Xgajra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'ZBR','Zabbar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'ZBG','Zebbug');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'ZJT','Zejtun');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'ZRQ','Zurrieq');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'FNT','Fontana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'GHJ','Ghajnsielem');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'GHR','Gharb');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'GHS','Ghasri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'KRC','Kercem');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'MUN','Munxar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'NAD','Nadur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'QAL','Qala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'VIC','Victoria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SLA','San Lawrenz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'SNT','Sannat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'ZAG','Xagra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'XEW','Xewkija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (132,'ZEB','Zebbug');

INSERT INTO `toc_countries` VALUES (133,'Marshall Islands','MH','MHL','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'ALK','Ailuk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'ALL','Ailinglapalap');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'ARN','Arno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'AUR','Aur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'EBO','Ebon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'ENI','Eniwetok');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'JAB','Jabat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'JAL','Jaluit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'KIL','Kili');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'KWA','Kwajalein');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'LAE','Lae');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'LIB','Lib');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'LIK','Likiep');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'MAJ','Majuro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'MAL','Maloelap');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'MEJ','Mejit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'MIL','Mili');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'NMK','Namorik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'NMU','Namu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'RON','Rongelap');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'UJA','Ujae');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'UJL','Ujelang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'UTI','Utirik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'WTJ','Wotje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (133,'WTN','Wotho');

INSERT INTO `toc_countries` VALUES (134,'Martinique','MQ','MTQ','');

INSERT INTO `toc_countries` VALUES (135,'Mauritania','MR','MRT','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'01','ولاية الحوض الشرقي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'02','ولاية الحوض الغربي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'03','ولاية العصابة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'04','ولاية كركول');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'05','ولاية البراكنة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'06','ولاية الترارزة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'07','ولاية آدرار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'08','ولاية داخلت نواذيبو');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'09','ولاية تكانت');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'10','ولاية كيدي ماغة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'11','ولاية تيرس زمور');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'12','ولاية إينشيري');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (135,'NKC','نواكشوط');

INSERT INTO `toc_countries` VALUES (136,'Mauritius','MU','MUS','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'AG','Agalega Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'BL','Black River');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'BR','Beau Bassin-Rose Hill');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'CC','Cargados Carajos Shoals');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'CU','Curepipe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'FL','Flacq');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'GP','Grand Port');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'MO','Moka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'PA','Pamplemousses');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'PL','Port Louis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'PU','Port Louis City');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'PW','Plaines Wilhems');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'QB','Quatre Bornes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'RO','Rodrigues');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'RR','Riviere du Rempart');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'SA','Savanne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (136,'VP','Vacoas-Phoenix');

INSERT INTO `toc_countries` VALUES (137,'Mayotte','YT','MYT','');

INSERT INTO `toc_countries` VALUES (138,'Mexico','MX','MEX',":name\n:street_address\n:postcode :city, :state_code\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'AGU','Aguascalientes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'BCN','Baja California');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'BCS','Baja California Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'CAM','Campeche');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'CHH','Chihuahua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'CHP','Chiapas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'COA','Coahuila');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'COL','Colima');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'DIF','Distrito Federal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'DUR','Durango');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'GRO','Guerrero');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'GUA','Guanajuato');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'HID','Hidalgo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'JAL','Jalisco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'MEX','Mexico');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'MIC','Michoacán');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'MOR','Morelos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'NAY','Nayarit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'NLE','Nuevo León');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'OAX','Oaxaca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'PUE','Puebla');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'QUE','Querétaro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'ROO','Quintana Roo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'SIN','Sinaloa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'SLP','San Luis Potosí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'SON','Sonora');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'TAB','Tabasco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'TAM','Tamaulipas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'TLA','Tlaxcala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'VER','Veracruz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'YUC','Yucatan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (138,'ZAC','Zacatecas');

INSERT INTO `toc_countries` VALUES (139,'Micronesia','FM','FSM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (139,'KSA','Kosrae');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (139,'PNI','Pohnpei');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (139,'TRK','Chuuk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (139,'YAP','Yap');

INSERT INTO `toc_countries` VALUES (140,'Moldova','MD','MDA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'BA','Bălţi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'CA','Cahul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'CU','Chişinău');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'ED','Edineţ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'GA','Găgăuzia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'LA','Lăpuşna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'OR','Orhei');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'SN','Stânga Nistrului');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'SO','Soroca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'TI','Tighina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (140,'UN','Ungheni');

INSERT INTO `toc_countries` VALUES (141,'Monaco','MC','MCO','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'MC','Monte Carlo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'LR','La Rousse');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'LA','Larvotto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'MV','Monaco Ville');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'SM','Saint Michel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'CO','Condamine');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'LC','La Colle');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'RE','Les Révoires');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'MO','Moneghetti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (141,'FV','Fontvieille');

INSERT INTO `toc_countries` VALUES (142,'Mongolia','MN','MNG','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'1','Улаанбаатар');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'035','Орхон аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'037','Дархан-Уул аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'039','Хэнтий аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'041','Хөвсгөл аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'043','Ховд аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'046','Увс аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'047','Төв аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'049','Сэлэнгэ аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'051','Сүхбаатар аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'053','Өмнөговь аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'055','Өвөрхангай аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'057','Завхан аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'059','Дундговь аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'061','Дорнод аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'063','Дорноговь аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'064','Говьсүмбэр аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'065','Говь-Алтай аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'067','Булган аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'069','Баянхонгор аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'071','Баян Өлгий аймаг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (142,'073','Архангай аймаг');

INSERT INTO `toc_countries` VALUES (143,'Montserrat','MS','MSR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (143,'A','Saint Anthony');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (143,'G','Saint Georges');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (143,'P','Saint Peter');

INSERT INTO `toc_countries` VALUES (144,'Morocco','MA','MAR','');

INSERT INTO `toc_countries` VALUES (145,'Mozambique','MZ','MOZ','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'A','Niassa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'B','Manica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'G','Gaza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'I','Inhambane');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'L','Maputo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'MPM','Maputo cidade');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'N','Nampula');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'P','Cabo Delgado');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'Q','Zambézia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'S','Sofala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (145,'T','Tete');

INSERT INTO `toc_countries` VALUES (146,'Myanmar','MM','MMR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'AY','ဧရာ၀တီတိုင္‌း');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'BG','ပဲခူးတုိင္‌း');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'MG','မကေ္ဝးတိုင္‌း');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'MD','မန္တလေးတုိင္‌း');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'SG','စစ္‌ကုိင္‌း‌တုိင္‌း');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'TN','တနင္သာရိတုိင္‌း');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'YG','ရန္‌ကုန္‌တုိင္‌း');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'CH','ခ္ယင္‌းပ္ရည္‌နယ္‌');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'KC','ကခ္ယင္‌ပ္ရည္‌နယ္‌');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'KH','ကယား‌ပ္ရည္‌နယ္‌');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'KN','ကရင္‌‌ပ္ရည္‌နယ္‌');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'MN','မ္ဝန္‌ပ္ရည္‌နယ္‌');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'RK','ရခုိင္‌ပ္ရည္‌နယ္‌');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (146,'SH','ရုမ္‌းပ္ရည္‌နယ္‌');

INSERT INTO `toc_countries` VALUES (147,'Namibia','NA','NAM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'CA','Caprivi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'ER','Erongo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'HA','Hardap');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'KA','Karas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'KH','Khomas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'KU','Kunene');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'OD','Otjozondjupa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'OH','Omaheke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'OK','Okavango');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'ON','Oshana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'OS','Omusati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'OT','Oshikoto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (147,'OW','Ohangwena');

INSERT INTO `toc_countries` VALUES (148,'Nauru','NR','NRU','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'AO','Aiwo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'AA','Anabar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'AT','Anetan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'AI','Anibare');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'BA','Baiti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'BO','Boe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'BU','Buada');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'DE','Denigomodu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'EW','Ewa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'IJ','Ijuw');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'ME','Meneng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'NI','Nibok');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'UA','Uaboe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (148,'YA','Yaren');

INSERT INTO `toc_countries` VALUES (149,'Nepal','NP','NPL','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'BA','Bagmati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'BH','Bheri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'DH','Dhawalagiri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'GA','Gandaki');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'JA','Janakpur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'KA','Karnali');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'KO','Kosi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'LU','Lumbini');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'MA','Mahakali');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'ME','Mechi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'NA','Narayani');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'RA','Rapti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'SA','Sagarmatha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (149,'SE','Seti');

INSERT INTO `toc_countries` VALUES (150,'Netherlands','NL','NLD',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'DR','Drenthe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'FL','Flevoland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'FR','Friesland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'GE','Gelderland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'GR','Groningen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'LI','Limburg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'NB','Noord-Brabant');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'NH','Noord-Holland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'OV','Overijssel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'UT','Utrecht');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'ZE','Zeeland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (150,'ZH','Zuid-Holland');

INSERT INTO `toc_countries` VALUES (151,'Netherlands Antilles','AN','ANT',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_countries` VALUES (152,'New Caledonia','NC','NCL','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (152,'L','Province des Îles');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (152,'N','Province Nord');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (152,'S','Province Sud');

INSERT INTO `toc_countries` VALUES (153,'New Zealand','NZ','NZL',":name\n:street_address\n:suburb\n:city :postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'AUK','Auckland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'BOP','Bay of Plenty');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'CAN','Canterbury');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'GIS','Gisborne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'HKB','Hawke\'s Bay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'MBH','Marlborough');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'MWT','Manawatu-Wanganui');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'NSN','Nelson');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'NTL','Northland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'OTA','Otago');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'STL','Southland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'TAS','Tasman');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'TKI','Taranaki');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'WGN','Wellington');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'WKO','Waikato');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (153,'WTC','West Coast');

INSERT INTO `toc_countries` VALUES (154,'Nicaragua','NI','NIC','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'AN','Atlántico Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'AS','Atlántico Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'BO','Boaco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'CA','Carazo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'CI','Chinandega');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'CO','Chontales');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'ES','Estelí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'GR','Granada');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'JI','Jinotega');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'LE','León');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'MD','Madriz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'MN','Managua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'MS','Masaya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'MT','Matagalpa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'NS','Nueva Segovia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'RI','Rivas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (154,'SJ','Río San Juan');

INSERT INTO `toc_countries` VALUES (155,'Niger','NE','NER','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (155,'1','Agadez');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (155,'2','Daffa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (155,'3','Dosso');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (155,'4','Maradi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (155,'5','Tahoua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (155,'6','Tillabéry');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (155,'7','Zinder');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (155,'8','Niamey');

INSERT INTO `toc_countries` VALUES (156,'Nigeria','NG','NGA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'AB','Abia State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'AD','Adamawa State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'AK','Akwa Ibom State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'AN','Anambra State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'BA','Bauchi State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'BE','Benue State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'BO','Borno State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'BY','Bayelsa State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'CR','Cross River State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'DE','Delta State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'EB','Ebonyi State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'ED','Edo State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'EK','Ekiti State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'EN','Enugu State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'GO','Gombe State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'IM','Imo State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'JI','Jigawa State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'KB','Kebbi State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'KD','Kaduna State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'KN','Kano State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'KO','Kogi State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'KT','Katsina State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'KW','Kwara State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'LA','Lagos State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'NA','Nassarawa State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'NI','Niger State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'OG','Ogun State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'ON','Ondo State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'OS','Osun State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'OY','Oyo State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'PL','Plateau State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'RI','Rivers State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'SO','Sokoto State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'TA','Taraba State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (156,'ZA','Zamfara State');

INSERT INTO `toc_countries` VALUES (157,'Niue','NU','NIU','');
INSERT INTO `toc_countries` VALUES (158,'Norfolk Island','NF','NFK','');

INSERT INTO `toc_countries` VALUES (159,'Northern Mariana Islands','MP','MNP','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (159,'N','Northern Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (159,'R','Rota');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (159,'S','Saipan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (159,'T','Tinian');

INSERT INTO `toc_countries` VALUES (160,'Norway','NO','NOR',":name\n:street_address\nNO-:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'01','Østfold fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'02','Akershus fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'03','Oslo fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'04','Hedmark fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'05','Oppland fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'06','Buskerud fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'07','Vestfold fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'08','Telemark fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'09','Aust-Agder fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'10','Vest-Agder fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'11','Rogaland fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'12','Hordaland fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'14','Sogn og Fjordane fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'15','Møre og Romsdal fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'16','Sør-Trøndelag fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'17','Nord-Trøndelag fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'18','Nordland fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'19','Troms fylke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (160,'20','Finnmark fylke');

INSERT INTO `toc_countries` VALUES (161,'Oman','OM','OMN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (161,'BA','الباطنة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (161,'DA','الداخلية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (161,'DH','ظفار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (161,'MA','مسقط');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (161,'MU','مسندم');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (161,'SH','الشرقية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (161,'WU','الوسطى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (161,'ZA','الظاهرة');

INSERT INTO `toc_countries` VALUES (162,'Pakistan','PK','PAK','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (162,'BA','بلوچستان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (162,'IS','وفاقی دارالحکومت');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (162,'JK','آزاد کشمیر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (162,'NA','شمالی علاقہ جات');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (162,'NW','شمال مغربی سرحدی صوبہ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (162,'PB','پنجاب');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (162,'SD','سندھ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (162,'TA','وفاقی قبائلی علاقہ جات');

INSERT INTO `toc_countries` VALUES (163,'Palau','PW','PLW','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'AM','Aimeliik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'AR','Airai');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'AN','Angaur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'HA','Hatohobei');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'KA','Kayangel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'KO','Koror');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'ME','Melekeok');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'NA','Ngaraard');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'NG','Ngarchelong');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'ND','Ngardmau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'NT','Ngatpang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'NC','Ngchesar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'NR','Ngeremlengui');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'NW','Ngiwal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'PE','Peleliu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (163,'SO','Sonsorol');

INSERT INTO `toc_countries` VALUES (164,'Panama','PA','PAN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'1','Bocas del Toro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'2','Coclé');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'3','Colón');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'4','Chiriquí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'5','Darién');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'6','Herrera');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'7','Los Santos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'8','Panamá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'9','Veraguas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (164,'Q','Kuna Yala');

INSERT INTO `toc_countries` VALUES (165,'Papua New Guinea','PG','PNG','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'CPK','Chimbu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'CPM','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'EBR','East New Britain');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'EHG','Eastern Highlands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'EPW','Enga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'ESW','East Sepik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'GPK','Gulf');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'MBA','Milne Bay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'MPL','Morobe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'MPM','Madang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'MRL','Manus');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'NCD','National Capital District');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'NIK','New Ireland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'NPP','Northern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'NSA','North Solomons');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'SAN','Sandaun');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'SHM','Southern Highlands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'WBK','West New Britain');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'WHM','Western Highlands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (165,'WPD','Western');

INSERT INTO `toc_countries` VALUES (166,'Paraguay','PY','PRY','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'1','Concepción');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'2','San Pedro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'3','Cordillera');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'4','Guairá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'5','Caaguazú');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'6','Caazapá');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'7','Itapúa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'8','Misiones');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'9','Paraguarí');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'10','Alto Paraná');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'11','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'12','Ñeembucú');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'13','Amambay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'14','Canindeyú');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'15','Presidente Hayes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'16','Alto Paraguay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'19','Boquerón');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (166,'ASU','Asunción');

INSERT INTO `toc_countries` VALUES (167,'Peru','PE','PER','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'AMA','Amazonas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'ANC','Ancash');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'APU','Apurímac');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'ARE','Arequipa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'AYA','Ayacucho');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'CAJ','Cajamarca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'CAL','Callao');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'CUS','Cuzco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'HUC','Huánuco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'HUV','Huancavelica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'ICA','Ica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'JUN','Junín');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'LAL','La Libertad');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'LAM','Lambayeque');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'LIM','Lima');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'LOR','Loreto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'MDD','Madre de Dios');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'MOQ','Moquegua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'PAS','Pasco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'PIU','Piura');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'PUN','Puno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'SAM','San Martín');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'TAC','Tacna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'TUM','Tumbes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (167,'UCA','Ucayali');

INSERT INTO `toc_countries` VALUES (168,'Philippines','PH','PHL','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ABR','Abra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'AGN','Agusan del Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'AGS','Agusan del Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'AKL','Aklan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ALB','Albay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ANT','Antique');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'APA','Apayao');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'AUR','Aurora');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'BAN','Bataan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'BAS','Basilan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'BEN','Benguet');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'BIL','Biliran');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'BOH','Bohol');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'BTG','Batangas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'BTN','Batanes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'BUK','Bukidnon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'BUL','Bulacan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'CAG','Cagayan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'CAM','Camiguin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'CAN','Camarines Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'CAP','Capiz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'CAS','Camarines Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'CAT','Catanduanes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'CAV','Cavite');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'CEB','Cebu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'COM','Compostela Valley');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'DAO','Davao Oriental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'DAS','Davao del Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'DAV','Davao del Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'EAS','Eastern Samar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'GUI','Guimaras');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'IFU','Ifugao');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ILI','Iloilo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ILN','Ilocos Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ILS','Ilocos Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ISA','Isabela');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'KAL','Kalinga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'LAG','Laguna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'LAN','Lanao del Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'LAS','Lanao del Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'LEY','Leyte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'LUN','La Union');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'MAD','Marinduque');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'MAG','Maguindanao');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'MAS','Masbate');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'MDC','Mindoro Occidental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'MDR','Mindoro Oriental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'MOU','Mountain Province');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'MSC','Misamis Occidental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'MSR','Misamis Oriental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'NCO','Cotabato');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'NSA','Northern Samar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'NEC','Negros Occidental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'NER','Negros Oriental');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'NUE','Nueva Ecija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'NUV','Nueva Vizcaya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'PAM','Pampanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'PAN','Pangasinan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'PLW','Palawan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'QUE','Quezon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'QUI','Quirino');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'RIZ','Rizal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ROM','Romblon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'SAR','Sarangani');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'SCO','South Cotabato');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'SIG','Siquijor');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'SLE','Southern Leyte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'SLU','Sulu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'SOR','Sorsogon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'SUK','Sultan Kudarat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'SUN','Surigao del Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'SUR','Surigao del Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'TAR','Tarlac');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'TAW','Tawi-Tawi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'WSA','Samar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ZAN','Zamboanga del Norte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ZAS','Zamboanga del Sur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ZMB','Zambales');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (168,'ZSI','Zamboanga Sibugay');

INSERT INTO `toc_countries` VALUES (169,'Pitcairn','PN','PCN','');

INSERT INTO `toc_countries` VALUES (170,'Poland','PL','POL',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'DS','Dolnośląskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'KP','Kujawsko-Pomorskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'LU','Lubelskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'LB','Lubuskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'LD','Łódzkie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'MA','Małopolskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'MZ','Mazowieckie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'OP','Opolskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'PK','Podkarpackie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'PD','Podlaskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'PM','Pomorskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'SL','Śląskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'SK','Świętokrzyskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'WN','Warmińsko-Mazurskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'WP','Wielkopolskie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (170,'ZP','Zachodniopomorskie');

INSERT INTO `toc_countries` VALUES (171,'Portugal','PT','PRT',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'01','Aveiro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'02','Beja');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'03','Braga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'04','Bragança');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'05','Castelo Branco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'06','Coimbra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'07','Évora');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'08','Faro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'09','Guarda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'10','Leiria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'11','Lisboa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'12','Portalegre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'13','Porto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'14','Santarém');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'15','Setúbal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'16','Viana do Castelo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'17','Vila Real');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'18','Viseu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'20','Região Autónoma dos Açores');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (171,'30','Região Autónoma da Madeira');

INSERT INTO `toc_countries` VALUES (172,'Puerto Rico','PR','PRI','');

INSERT INTO `toc_countries` VALUES (173,'Qatar','QA','QAT','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'DA','الدوحة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'GH','الغويرية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'JB','جريان الباطنة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'JU','الجميلية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'KH','الخور');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'ME','مسيعيد');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'MS','الشمال');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'RA','الريان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'US','أم صلال');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (173,'WA','الوكرة');

INSERT INTO `toc_countries` VALUES (174,'Reunion','RE','REU','');

INSERT INTO `toc_countries` VALUES (175,'Romania','RO','ROM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'AB','Alba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'AG','Argeş');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'AR','Arad');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'B','Bucureşti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'BC','Bacău');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'BH','Bihor');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'BN','Bistriţa-Năsăud');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'BR','Brăila');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'BT','Botoşani');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'BV','Braşov');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'BZ','Buzău');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'CJ','Cluj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'CL','Călăraşi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'CS','Caraş-Severin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'CT','Constanţa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'CV','Covasna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'DB','Dâmboviţa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'DJ','Dolj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'GJ','Gorj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'GL','Galaţi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'GR','Giurgiu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'HD','Hunedoara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'HG','Harghita');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'IF','Ilfov');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'IL','Ialomiţa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'IS','Iaşi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'MH','Mehedinţi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'MM','Maramureş');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'MS','Mureş');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'NT','Neamţ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'OT','Olt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'PH','Prahova');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'SB','Sibiu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'SJ','Sălaj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'SM','Satu Mare');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'SV','Suceava');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'TL','Tulcea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'TM','Timiş');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'TR','Teleorman');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'VL','Vâlcea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'VN','Vrancea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (175,'VS','Vaslui');

INSERT INTO `toc_countries` VALUES (176,'Russia','RU','RUS',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'AD','Адыге́я Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'AGB','Аги́нский-Буря́тский автоно́мный о́круг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'AL','Алта́й Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'ALT','Алта́йский край');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'AMU','Аму́рская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'ARK','Арха́нгельская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'AST','Астраха́нская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'BA','Башкортоста́н Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'BEL','Белгоро́дская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'BRY','Бря́нская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'BU','Буря́тия Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'CE','Чече́нская Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'CHE','Челя́бинская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'CHI','Чити́нская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'CHU','Чуко́тский автоно́мный о́круг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'CU','Чува́шская Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'DA','Дагеста́н Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'EVE','Эвенки́йский автоно́мный о́круг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'IN','Ингуше́тия Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'IRK','Ирку́тская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'IVA','Ива́новская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KAM','Камча́тская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KB','Кабарди́но-Балка́рская Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KC','Карача́ево-Черке́сская Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KDA','Краснода́рский край');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KEM','Ке́меровская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KGD','Калинингра́дская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KGN','Курга́нская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KHA','Хаба́ровский край');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KHM','Ха́нты-Манси́йский автоно́мный о́круг—Югра́');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KIA','Красноя́рский край');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KIR','Ки́ровская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KK','Хака́сия');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KL','Калмы́кия Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KLU','Калу́жская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KO','Ко́ми Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KOR','Коря́кский автоно́мный о́круг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KOS','Костромска́я о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KR','Каре́лия Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'KRS','Ку́рская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'LEN','Ленингра́дская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'LIP','Ли́пецкая о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'MAG','Магада́нская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'ME','Мари́й Эл Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'MO','Мордо́вия Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'MOS','Моско́вская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'MOW','Москва́');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'MUR','Му́рманская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'NEN','Нене́цкий автоно́мный о́круг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'NGR','Новгоро́дская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'NIZ','Нижегоро́дская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'NVS','Новосиби́рская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'OMS','О́мская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'ORE','Оренбу́ргская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'ORL','Орло́вская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'PNZ','Пе́нзенская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'PRI','Примо́рский край');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'PSK','Пско́вская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'ROS','Росто́вская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'RYA','Ряза́нская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'SA','Саха́ (Яку́тия) Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'SAK','Сахали́нская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'SAM','Сама́рская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'SAR','Сара́товская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'SE','Се́верная Осе́тия–Ала́ния Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'SMO','Смол́енская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'SPE','Санкт-Петербу́рг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'STA','Ставропо́льский край');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'SVE','Свердло́вская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'TA','Респу́блика Татарста́н');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'TAM','Тамбо́вская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'TAY','Таймы́рский автоно́мный о́круг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'TOM','То́мская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'TUL','Ту́льская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'TVE','Тверска́я о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'TY','Тыва́ Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'TYU','Тюме́нская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'UD','Удму́ртская Респу́блика');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'ULY','Улья́новская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'UOB','Усть-Орды́нский Буря́тский автоно́мный о́круг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'VGG','Волгогра́дская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'VLA','Влади́мирская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'VLG','Волого́дская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'VOR','Воро́нежская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'XXX','Пе́рмский край');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'YAN','Яма́ло-Нене́цкий автоно́мный о́круг');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'YAR','Яросла́вская о́бласть');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (176,'YEV','Евре́йская автоно́мная о́бласть');

INSERT INTO `toc_countries` VALUES (177,'Rwanda','RW','RWA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (177,'N','Nord');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (177,'E','Est');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (177,'S','Sud');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (177,'O','Ouest');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (177,'K','Kigali');

INSERT INTO `toc_countries` VALUES (178,'Saint Kitts and Nevis','KN','KNA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (178,'K','Saint Kitts');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (178,'N','Nevis');

INSERT INTO `toc_countries` VALUES (179,'Saint Lucia','LC','LCA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'AR','Anse-la-Raye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'CA','Castries');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'CH','Choiseul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'DA','Dauphin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'DE','Dennery');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'GI','Gros-Islet');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'LA','Laborie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'MI','Micoud');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'PR','Praslin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'SO','Soufriere');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (179,'VF','Vieux-Fort');

INSERT INTO `toc_countries` VALUES (180,'Saint Vincent and the Grenadines','VC','VCT','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (180,'C','Charlotte');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (180,'R','Grenadines');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (180,'A','Saint Andrew');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (180,'D','Saint David');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (180,'G','Saint George');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (180,'P','Saint Patrick');

INSERT INTO `toc_countries` VALUES (181,'Samoa','WS','WSM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'AA','A\'ana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'AL','Aiga-i-le-Tai');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'AT','Atua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'FA','Fa\'asaleleaga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'GE','Gaga\'emauga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'GI','Gaga\'ifomauga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'PA','Palauli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'SA','Satupa\'itea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'TU','Tuamasaga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'VF','Va\'a-o-Fonoti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (181,'VS','Vaisigano');

INSERT INTO `toc_countries` VALUES (182,'San Marino','SM','SMR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (182,'AC','Acquaviva');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (182,'BM','Borgo Maggiore');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (182,'CH','Chiesanuova');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (182,'DO','Domagnano');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (182,'FA','Faetano');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (182,'FI','Fiorentino');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (182,'MO','Montegiardino');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (182,'SM','Citta di San Marino');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (182,'SE','Serravalle');

INSERT INTO `toc_countries` VALUES (183,'Sao Tome and Principe','ST','STP','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (183,'P','Príncipe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (183,'S','São Tomé');

INSERT INTO `toc_countries` VALUES (184,'Saudi Arabia','SA','SAU','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'01','الرياض');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'02','مكة المكرمة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'03','المدينه');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'04','الشرقية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'05','القصيم');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'06','حائل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'07','تبوك');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'08','الحدود الشمالية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'09','جيزان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'10','نجران');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'11','الباحة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'12','الجوف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (184,'14','عسير');

INSERT INTO `toc_countries` VALUES (185,'Senegal','SN','SEN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'DA','Dakar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'DI','Diourbel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'FA','Fatick');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'KA','Kaolack');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'KO','Kolda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'LO','Louga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'MA','Matam');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'SL','Saint-Louis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'TA','Tambacounda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'TH','Thies ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (185,'ZI','Ziguinchor');

INSERT INTO `toc_countries` VALUES (186,'Seychelles','SC','SYC','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'AP','Anse aux Pins');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'AB','Anse Boileau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'AE','Anse Etoile');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'AL','Anse Louis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'AR','Anse Royale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'BL','Baie Lazare');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'BS','Baie Sainte Anne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'BV','Beau Vallon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'BA','Bel Air');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'BO','Bel Ombre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'CA','Cascade');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'GL','Glacis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'GM','Grand\' Anse (on Mahe)');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'GP','Grand\' Anse (on Praslin)');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'DG','La Digue');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'RA','La Riviere Anglaise');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'MB','Mont Buxton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'MF','Mont Fleuri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'PL','Plaisance');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'PR','Pointe La Rue');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'PG','Port Glaud');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'SL','Saint Louis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (186,'TA','Takamaka');

INSERT INTO `toc_countries` VALUES (187,'Sierra Leone','SL','SLE','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (187,'E','Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (187,'N','Northern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (187,'S','Southern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (187,'W','Western');

INSERT INTO `toc_countries` VALUES (188,'Singapore','SG','SGP', ":name\n:street_address\n:city :postcode\n:country");

INSERT INTO `toc_countries` VALUES (189,'Slovakia','SK','SVK','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (189,'BC','Banskobystrický kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (189,'BL','Bratislavský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (189,'KI','Košický kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (189,'NJ','Nitrianský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (189,'PV','Prešovský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (189,'TA','Trnavský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (189,'TC','Trenčianský kraj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (189,'ZI','Žilinský kraj');

INSERT INTO `toc_countries` VALUES (190,'Slovenia','SI','SVN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'001','Ajdovščina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'002','Beltinci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'003','Bled');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'004','Bohinj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'005','Borovnica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'006','Bovec');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'007','Brda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'008','Brezovica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'009','Brežice');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'010','Tišina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'011','Celje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'012','Cerklje na Gorenjskem');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'013','Cerknica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'014','Cerkno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'015','Črenšovci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'016','Črna na Koroškem');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'017','Črnomelj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'018','Destrnik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'019','Divača');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'020','Dobrepolje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'021','Dobrova-Polhov Gradec');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'022','Dol pri Ljubljani');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'023','Domžale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'024','Dornava');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'025','Dravograd');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'026','Duplek');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'027','Gorenja vas-Poljane');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'028','Gorišnica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'029','Gornja Radgona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'030','Gornji Grad');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'031','Gornji Petrovci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'032','Grosuplje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'033','Šalovci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'034','Hrastnik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'035','Hrpelje-Kozina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'036','Idrija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'037','Ig');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'038','Ilirska Bistrica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'039','Ivančna Gorica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'040','Izola');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'041','Jesenice');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'042','Juršinci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'043','Kamnik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'044','Kanal ob Soči');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'045','Kidričevo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'046','Kobarid');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'047','Kobilje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'048','Kočevje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'049','Komen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'050','Koper');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'051','Kozje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'052','Kranj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'053','Kranjska Gora');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'054','Krško');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'055','Kungota');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'056','Kuzma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'057','Laško');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'058','Lenart');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'059','Lendava');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'060','Litija');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'061','Ljubljana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'062','Ljubno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'063','Ljutomer');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'064','Logatec');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'065','Loška Dolina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'066','Loški Potok');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'067','Luče');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'068','Lukovica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'069','Majšperk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'070','Maribor');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'071','Medvode');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'072','Mengeš');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'073','Metlika');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'074','Mežica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'075','Miren-Kostanjevica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'076','Mislinja');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'077','Moravče');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'078','Moravske Toplice');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'079','Mozirje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'080','Murska Sobota');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'081','Muta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'082','Naklo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'083','Nazarje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'084','Nova Gorica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'085','Novo mesto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'086','Odranci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'087','Ormož');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'088','Osilnica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'089','Pesnica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'090','Piran');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'091','Pivka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'092','Podčetrtek');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'093','Podvelka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'094','Postojna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'095','Preddvor');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'096','Ptuj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'097','Puconci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'098','Rače-Fram');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'099','Radeče');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'100','Radenci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'101','Radlje ob Dravi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'102','Radovljica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'103','Ravne na Koroškem');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'104','Ribnica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'106','Rogaška Slatina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'105','Rogašovci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'107','Rogatec');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'108','Ruše');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'109','Semič');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'110','Sevnica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'111','Sežana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'112','Slovenj Gradec');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'113','Slovenska Bistrica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'114','Slovenske Konjice');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'115','Starše');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'116','Sveti Jurij');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'117','Šenčur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'118','Šentilj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'119','Šentjernej');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'120','Šentjur pri Celju');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'121','Škocjan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'122','Škofja Loka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'123','Škofljica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'124','Šmarje pri Jelšah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'125','Šmartno ob Paki');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'126','Šoštanj');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'127','Štore');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'128','Tolmin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'129','Trbovlje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'130','Trebnje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'131','Tržič');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'132','Turnišče');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'133','Velenje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'134','Velike Lašče');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'135','Videm');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'136','Vipava');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'137','Vitanje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'138','Vodice');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'139','Vojnik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'140','Vrhnika');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'141','Vuzenica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'142','Zagorje ob Savi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'143','Zavrč');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'144','Zreče');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'146','Železniki');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'147','Žiri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'148','Benedikt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'149','Bistrica ob Sotli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'150','Bloke');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'151','Braslovče');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'152','Cankova');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'153','Cerkvenjak');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'154','Dobje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'155','Dobrna');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'156','Dobrovnik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'157','Dolenjske Toplice');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'158','Grad');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'159','Hajdina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'160','Hoče-Slivnica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'161','Hodoš');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'162','Horjul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'163','Jezersko');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'164','Komenda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'165','Kostel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'166','Križevci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'167','Lovrenc na Pohorju');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'168','Markovci');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'169','Miklavž na Dravskem polju');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'170','Mirna Peč');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'171','Oplotnica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'172','Podlehnik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'173','Polzela');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'174','Prebold');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'175','Prevalje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'176','Razkrižje');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'177','Ribnica na Pohorju');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'178','Selnica ob Dravi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'179','Sodražica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'180','Solčava');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'181','Sveta Ana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'182','Sveti Andraž v Slovenskih goricah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'183','Šempeter-Vrtojba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'184','Tabor');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'185','Trnovska vas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'186','Trzin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'187','Velika Polana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'188','Veržej');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'189','Vransko');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'190','Žalec');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'191','Žetale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'192','Žirovnica');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'193','Žužemberk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (190,'194','Šmartno pri Litiji');

INSERT INTO `toc_countries` VALUES (191,'Solomon Islands','SB','SLB','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'CE','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'CH','Choiseul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'GC','Guadalcanal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'HO','Honiara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'IS','Isabel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'MK','Makira');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'ML','Malaita');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'RB','Rennell and Bellona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'TM','Temotu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (191,'WE','Western');

INSERT INTO `toc_countries` VALUES (192,'Somalia','SO','SOM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'AD','Awdal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'BK','Bakool');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'BN','Banaadir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'BR','Bari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'BY','Bay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'GD','Gedo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'GG','Galguduud');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'HR','Hiiraan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'JD','Jubbada Dhexe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'JH','Jubbada Hoose');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'MD','Mudug');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'NG','Nugaal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'SD','Shabeellaha Dhexe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'SG','Sanaag');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'SH','Shabeellaha Hoose');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'SL','Sool');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'TG','Togdheer');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (192,'WG','Woqooyi Galbeed');

INSERT INTO `toc_countries` VALUES (193,'South Africa','ZA','ZAF',":name\n:street_address\n:suburb\n:city\n:postcode :country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (193,'EC','Eastern Cape');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (193,'FS','Free State');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (193,'GT','Gauteng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (193,'LP','Limpopo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (193,'MP','Mpumalanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (193,'NC','Northern Cape');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (193,'NL','KwaZulu-Natal');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (193,'NW','North-West');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (193,'WC','Western Cape');

INSERT INTO `toc_countries` VALUES (194,'South Georgia and the South Sandwich Islands','GS','SGS','');

INSERT INTO `toc_countries` VALUES (195,'Spain','ES','ESP',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'AN','Andalucía');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'AR','Aragón');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'A','Alicante');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'AB','Albacete');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'AL','Almería');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'AN','Andalucía');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'AV','Ávila');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'B','Barcelona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'BA','Badajoz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'BI','Vizcaya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'BU','Burgos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'C','A Coruña');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CA','Cádiz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CC','Cáceres');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CE','Ceuta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CL','Castilla y León');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CM','Castilla-La Mancha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CN','Islas Canarias');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CO','Córdoba');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CR','Ciudad Real');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CS','Castellón');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CT','Catalonia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'CU','Cuenca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'EX','Extremadura');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'GA','Galicia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'GC','Las Palmas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'GI','Girona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'GR','Granada');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'GU','Guadalajara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'H','Huelva');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'HU','Huesca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'IB','Islas Baleares');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'J','Jaén');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'L','Lleida');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'LE','León');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'LO','La Rioja');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'LU','Lugo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'M','Madrid');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'MA','Málaga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'ML','Melilla');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'MU','Murcia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'NA','Navarre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'O','Asturias');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'OR','Ourense');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'P','Palencia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'PM','Baleares');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'PO','Pontevedra');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'PV','Basque Euskadi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'S','Cantabria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'SA','Salamanca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'SE','Seville');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'SG','Segovia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'SO','Soria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'SS','Guipúzcoa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'T','Tarragona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'TE','Teruel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'TF','Santa Cruz De Tenerife');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'TO','Toledo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'V','Valencia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'VA','Valladolid');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'VI','Álava');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'Z','Zaragoza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (195,'ZA','Zamora');

INSERT INTO `toc_countries` VALUES (196,'Sri Lanka','LK','LKA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (196,'CE','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (196,'NC','North Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (196,'NO','North');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (196,'EA','Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (196,'NW','North Western');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (196,'SO','Southern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (196,'UV','Uva');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (196,'SA','Sabaragamuwa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (196,'WE','Western');

INSERT INTO `toc_countries` VALUES (197,'St. Helena','SH','SHN','');
INSERT INTO `toc_countries` VALUES (198,'St. Pierre and Miquelon','PM','SPM','');

INSERT INTO `toc_countries` VALUES (199,'Sudan','SD','SDN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'ANL','أعالي النيل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'BAM','البحر الأحمر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'BRT','البحيرات');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'JZR','ولاية الجزيرة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'KRT','الخرطوم');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'QDR','القضارف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'WDH','الوحدة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'ANB','النيل الأبيض');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'ANZ','النيل الأزرق');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'ASH','الشمالية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'BJA','الاستوائية الوسطى');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'GIS','غرب الاستوائية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'GBG','غرب بحر الغزال');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'GDA','غرب دارفور');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'GKU','غرب كردفان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'JDA','جنوب دارفور');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'JKU','جنوب كردفان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'JQL','جونقلي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'KSL','كسلا');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'NNL','ولاية نهر النيل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'SBG','شمال بحر الغزال');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'SDA','شمال دارفور');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'SKU','شمال كردفان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'SIS','شرق الاستوائية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'SNR','سنار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (199,'WRB','واراب');

INSERT INTO `toc_countries` VALUES (200,'Suriname','SR','SUR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'BR','Brokopondo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'CM','Commewijne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'CR','Coronie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'MA','Marowijne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'NI','Nickerie');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'PM','Paramaribo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'PR','Para');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'SA','Saramacca');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'SI','Sipaliwini');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (200,'WA','Wanica');

INSERT INTO `toc_countries` VALUES (201,'Svalbard and Jan Mayen Islands','SJ','SJM','');

INSERT INTO `toc_countries` VALUES (202,'Swaziland','SZ','SWZ','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (202,'HH','Hhohho');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (202,'LU','Lubombo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (202,'MA','Manzini');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (202,'SH','Shiselweni');

INSERT INTO `toc_countries` VALUES (203,'Sweden','SE','SWE',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'AB','Stockholms län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'C','Uppsala län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'D','Södermanlands län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'E','Östergötlands län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'F','Jönköpings län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'G','Kronobergs län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'H','Kalmar län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'I','Gotlands län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'K','Blekinge län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'M','Skåne län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'N','Hallands län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'O','Västra Götalands län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'S','Värmlands län;');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'T','Örebro län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'U','Västmanlands län;');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'W','Dalarnas län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'X','Gävleborgs län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'Y','Västernorrlands län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'Z','Jämtlands län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'AC','Västerbottens län');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (203,'BD','Norrbottens län');

INSERT INTO `toc_countries` VALUES (204,'Switzerland','CH','CHE',":name\n:street_address\n:postcode :city\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'ZH','Zürich');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'BE','Bern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'LU','Luzern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'UR','Uri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'SZ','Schwyz');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'OW','Obwalden');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'NW','Nidwalden');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'GL','Glasrus');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'ZG','Zug');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'FR','Fribourg');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'SO','Solothurn');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'BS','Basel-Stadt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'BL','Basel-Landschaft');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'SH','Schaffhausen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'AR','Appenzell Ausserrhoden');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'AI','Appenzell Innerrhoden');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'SG','Saint Gallen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'GR','Graubünden');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'AG','Aargau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'TG','Thurgau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'TI','Ticino');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'VD','Vaud');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'VS','Valais');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'NE','Nuechâtel');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'GE','Genève');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (204,'JU','Jura');

INSERT INTO `toc_countries` VALUES (205,'Syrian Arab Republic','SY','SYR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'DI','دمشق');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'DR','درعا');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'DZ','دير الزور');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'HA','الحسكة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'HI','حمص');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'HL','حلب');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'HM','حماه');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'ID','ادلب');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'LA','اللاذقية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'QU','القنيطرة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'RA','الرقة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'RD','ریف دمشق');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'SU','السويداء');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (205,'TA','طرطوس');

INSERT INTO `toc_countries` VALUES (206,'Taiwan','TW','TWN',":name\n:street_address\n:city :postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'CHA','彰化縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'CYI','嘉義市');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'CYQ','嘉義縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'HSQ','新竹縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'HSZ','新竹市');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'HUA','花蓮縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'ILA','宜蘭縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'KEE','基隆市');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'KHH','高雄市');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'KHQ','高雄縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'MIA','苗栗縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'NAN','南投縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'PEN','澎湖縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'PIF','屏東縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'TAO','桃源县');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'TNN','台南市');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'TNQ','台南縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'TPE','臺北市');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'TPQ','臺北縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'TTT','台東縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'TXG','台中市');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'TXQ','台中縣');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (206,'YUN','雲林縣');

INSERT INTO `toc_countries` VALUES (207,'Tajikistan','TJ','TJK','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (207,'GB','کوهستان بدخشان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (207,'KT','ختلان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (207,'SU','سغد');

INSERT INTO `toc_countries` VALUES (208,'Tanzania','TZ','TZA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'01','Arusha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'02','Dar es Salaam');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'03','Dodoma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'04','Iringa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'05','Kagera');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'06','Pemba Sever');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'07','Zanzibar Sever');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'08','Kigoma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'09','Kilimanjaro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'10','Pemba Jih');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'11','Zanzibar Jih');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'12','Lindi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'13','Mara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'14','Mbeya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'15','Zanzibar Západ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'16','Morogoro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'17','Mtwara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'18','Mwanza');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'19','Pwani');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'20','Rukwa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'21','Ruvuma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'22','Shinyanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'23','Singida');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'24','Tabora');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'25','Tanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (208,'26','Manyara');

INSERT INTO `toc_countries` VALUES (209,'Thailand','TH','THA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-10','กรุงเทพมหานคร');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-11','สมุทรปราการ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-12','นนทบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-13','ปทุมธานี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-14','พระนครศรีอยุธยา');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-15','อ่างทอง');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-16','ลพบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-17','สิงห์บุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-18','ชัยนาท');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-19','สระบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-20','ชลบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-21','ระยอง');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-22','จันทบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-23','ตราด');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-24','ฉะเชิงเทรา');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-25','ปราจีนบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-26','นครนายก');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-27','สระแก้ว');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-30','นครราชสีมา');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-31','บุรีรัมย์');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-32','สุรินทร์');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-33','ศรีสะเกษ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-34','อุบลราชธานี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-35','ยโสธร');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-36','ชัยภูมิ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-37','อำนาจเจริญ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-39','หนองบัวลำภู');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-40','ขอนแก่น');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-41','อุดรธานี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-42','เลย');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-43','หนองคาย');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-44','มหาสารคาม');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-45','ร้อยเอ็ด');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-46','กาฬสินธุ์');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-47','สกลนคร');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-48','นครพนม');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-49','มุกดาหาร');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-50','เชียงใหม่');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-51','ลำพูน');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-52','ลำปาง');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-53','อุตรดิตถ์');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-55','น่าน');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-56','พะเยา');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-57','เชียงราย');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-58','แม่ฮ่องสอน');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-60','นครสวรรค์');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-61','อุทัยธานี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-62','กำแพงเพชร');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-63','ตาก');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-64','สุโขทัย');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-66','ชุมพร');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-67','พิจิตร');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-70','ราชบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-71','กาญจนบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-72','สุพรรณบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-73','นครปฐม');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-74','สมุทรสาคร');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-75','สมุทรสงคราม');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-76','เพชรบุรี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-77','ประจวบคีรีขันธ์');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-80','นครศรีธรรมราช');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-81','กระบี่');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-82','พังงา');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-83','ภูเก็ต');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-84','สุราษฎร์ธานี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-85','ระนอง');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-86','ชุมพร');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-90','สงขลา');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-91','สตูล');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-92','ตรัง');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-93','พัทลุง');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-94','ปัตตานี');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-95','ยะลา');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (209,'TH-96','นราธิวาส');

INSERT INTO `toc_countries` VALUES (210,'Togo','TG','TGO','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (210,'C','Centrale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (210,'K','Kara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (210,'M','Maritime');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (210,'P','Plateaux');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (210,'S','Savanes');

INSERT INTO `toc_countries` VALUES (211,'Tokelau','TK','TKL','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (211,'A','Atafu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (211,'F','Fakaofo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (211,'N','Nukunonu');

INSERT INTO `toc_countries` VALUES (212,'Tonga','TO','TON','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (212,'H','Ha\'apai');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (212,'T','Tongatapu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (212,'V','Vava\'u');

INSERT INTO `toc_countries` VALUES (213,'Trinidad and Tobago','TT','TTO','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'ARI','Arima');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'CHA','Chaguanas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'CTT','Couva-Tabaquite-Talparo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'DMN','Diego Martin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'ETO','Eastern Tobago');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'RCM','Rio Claro-Mayaro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'PED','Penal-Debe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'PTF','Point Fortin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'POS','Port of Spain');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'PRT','Princes Town');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'SFO','San Fernando');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'SGE','Sangre Grande');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'SJL','San Juan-Laventille');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'SIP','Siparia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'TUP','Tunapuna-Piarco');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (213,'WTO','Western Tobago');

INSERT INTO `toc_countries` VALUES (214,'Tunisia','TN','TUN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'11','ولاية تونس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'12','ولاية أريانة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'13','ولاية بن عروس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'14','ولاية منوبة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'21','ولاية نابل');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'22','ولاية زغوان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'23','ولاية بنزرت');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'31','ولاية باجة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'32','ولاية جندوبة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'33','ولاية الكاف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'34','ولاية سليانة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'41','ولاية القيروان');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'42','ولاية القصرين');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'43','ولاية سيدي بوزيد');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'51','ولاية سوسة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'52','ولاية المنستير');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'53','ولاية المهدية');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'61','ولاية صفاقس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'71','ولاية قفصة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'72','ولاية توزر');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'73','ولاية قبلي');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'81','ولاية قابس');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'82','ولاية مدنين');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (214,'83','ولاية تطاوين');

INSERT INTO `toc_countries` VALUES (215,'Turkey','TR','TUR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'01','Adana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'02','Adıyaman');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'03','Afyonkarahisar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'04','Ağrı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'05','Amasya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'06','Ankara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'07','Antalya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'08','Artvin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'09','Aydın');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'10','Balıkesir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'11','Bilecik');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'12','Bingöl');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'13','Bitlis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'14','Bolu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'15','Burdur');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'16','Bursa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'17','Çanakkale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'18','Çankırı');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'19','Çorum');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'20','Denizli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'21','Diyarbakır');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'22','Edirne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'23','Elazığ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'24','Erzincan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'25','Erzurum');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'26','Eskişehir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'27','Gaziantep');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'28','Giresun');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'29','Gümüşhane');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'30','Hakkari');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'31','Hatay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'32','Isparta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'33','Mersin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'34','İstanbul');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'35','İzmir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'36','Kars');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'37','Kastamonu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'38','Kayseri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'39','Kırklareli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'40','Kırşehir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'41','Kocaeli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'42','Konya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'43','Kütahya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'44','Malatya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'45','Manisa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'46','Kahramanmaraş');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'47','Mardin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'48','Muğla');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'49','Muş');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'50','Nevşehir');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'51','Niğde');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'52','Ordu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'53','Rize');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'54','Sakarya');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'55','Samsun');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'56','Siirt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'57','Sinop');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'58','Sivas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'59','Tekirdağ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'60','Tokat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'61','Trabzon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'62','Tunceli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'63','Şanlıurfa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'64','Uşak');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'65','Van');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'66','Yozgat');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'67','Zonguldak');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'68','Aksaray');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'69','Bayburt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'70','Karaman');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'71','Kırıkkale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'72','Batman');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'73','Şırnak');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'74','Bartın');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'75','Ardahan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'76','Iğdır');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'77','Yalova');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'78','Karabük');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'79','Kilis');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'80','Osmaniye');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (215,'81','Düzce');

INSERT INTO `toc_countries` VALUES (216,'Turkmenistan','TM','TKM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (216,'A','Ahal welaýaty');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (216,'B','Balkan welaýaty');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (216,'D','Daşoguz welaýaty');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (216,'L','Lebap welaýaty');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (216,'M','Mary welaýaty');

INSERT INTO `toc_countries` VALUES (217,'Turks and Caicos Islands','TC','TCA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'AC','Ambergris Cays');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'DC','Dellis Cay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'FC','French Cay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'LW','Little Water Cay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'RC','Parrot Cay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'PN','Pine Cay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'SL','Salt Cay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'GT','Grand Turk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'SC','South Caicos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'EC','East Caicos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'MC','Middle Caicos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'NC','North Caicos');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'PR','Providenciales');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (217,'WC','West Caicos');

INSERT INTO `toc_countries` VALUES (218,'Tuvalu','TV','TUV','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (218,'FUN','Funafuti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (218,'NMA','Nanumea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (218,'NMG','Nanumanga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (218,'NIT','Niutao');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (218,'NIU','Nui');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (218,'NKF','Nukufetau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (218,'NKL','Nukulaelae');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (218,'VAI','Vaitupu');

INSERT INTO `toc_countries` VALUES (219,'Uganda','UG','UGA','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'101','Kalangala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'102','Kampala');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'103','Kiboga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'104','Luwero');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'105','Masaka');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'106','Mpigi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'107','Mubende');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'108','Mukono');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'109','Nakasongola');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'110','Rakai');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'111','Sembabule');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'112','Kayunga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'113','Wakiso');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'201','Bugiri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'202','Busia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'203','Iganga');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'204','Jinja');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'205','Kamuli');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'206','Kapchorwa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'207','Katakwi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'208','Kumi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'209','Mbale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'210','Pallisa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'211','Soroti');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'212','Tororo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'213','Kaberamaido');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'214','Mayuge');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'215','Sironko');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'301','Adjumani');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'302','Apac');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'303','Arua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'304','Gulu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'305','Kitgum');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'306','Kotido');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'307','Lira');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'308','Moroto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'309','Moyo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'310','Nebbi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'311','Nakapiripirit');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'312','Pader');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'313','Yumbe');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'401','Bundibugyo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'402','Bushenyi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'403','Hoima');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'404','Kabale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'405','Kabarole');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'406','Kasese');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'407','Kibale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'408','Kisoro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'409','Masindi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'410','Mbarara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'411','Ntungamo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'412','Rukungiri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'413','Kamwenge');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'414','Kanungu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (219,'415','Kyenjojo');

INSERT INTO `toc_countries` VALUES (220,'Ukraine','UA','UKR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'05','Вінницька область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'07','Волинська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'09','Луганська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'12','Дніпропетровська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'14','Донецька область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'18','Житомирська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'19','Рівненська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'21','Закарпатська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'23','Запорізька область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'26','Івано-Франківська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'30','Київ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'32','Київська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'35','Кіровоградська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'40','Севастополь');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'43','Автономная Республика Крым');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'46','Львівська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'48','Миколаївська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'51','Одеська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'53','Полтавська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'59','Сумська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'61','Тернопільська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'63','Харківська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'65','Херсонська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'68','Хмельницька область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'71','Черкаська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'74','Чернігівська область');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (220,'77','Чернівецька область');

INSERT INTO `toc_countries` VALUES (221,'United Arab Emirates','AE','ARE','');

INSERT INTO `toc_countries` VALUES (222,'United Kingdom','GB','GBR',":name\n:street_address\n:city\n:postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ABD','Aberdeenshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ABE','Aberdeen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'AGB','Argyll and Bute');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'AGY','Isle of Anglesey');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ANS','Angus');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ANT','Antrim');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ARD','Ards');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ARM','Armagh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BAS','Bath and North East Somerset');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BBD','Blackburn with Darwen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BDF','Bedfordshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BDG','Barking and Dagenham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BEN','Brent');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BEX','Bexley');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BFS','Belfast');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BGE','Bridgend');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BGW','Blaenau Gwent');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BIR','Birmingham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BKM','Buckinghamshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BLA','Ballymena');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BLY','Ballymoney');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BMH','Bournemouth');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BNB','Banbridge');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BNE','Barnet');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BNH','Brighton and Hove');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BNS','Barnsley');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BOL','Bolton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BPL','Blackpool');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BRC','Bracknell');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BRD','Bradford');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BRY','Bromley');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BST','Bristol');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'BUR','Bury');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CAM','Cambridgeshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CAY','Caerphilly');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CGN','Ceredigion');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CGV','Craigavon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CHS','Cheshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CKF','Carrickfergus');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CKT','Cookstown');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CLD','Calderdale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CLK','Clackmannanshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CLR','Coleraine');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CMA','Cumbria');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CMD','Camden');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CMN','Carmarthenshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CON','Cornwall');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'COV','Coventry');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CRF','Cardiff');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CRY','Croydon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CSR','Castlereagh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'CWY','Conwy');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DAL','Darlington');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DBY','Derbyshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DEN','Denbighshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DER','Derby');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DEV','Devon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DGN','Dungannon and South Tyrone');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DGY','Dumfries and Galloway');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DNC','Doncaster');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DND','Dundee');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DOR','Dorset');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DOW','Down');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DRY','Derry');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DUD','Dudley');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'DUR','Durham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'EAL','Ealing');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'EAY','East Ayrshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'EDH','Edinburgh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'EDU','East Dunbartonshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ELN','East Lothian');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ELS','Eilean Siar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ENF','Enfield');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ERW','East Renfrewshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ERY','East Riding of Yorkshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ESS','Essex');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ESX','East Sussex');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'FAL','Falkirk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'FER','Fermanagh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'FIF','Fife');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'FLN','Flintshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'GAT','Gateshead');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'GLG','Glasgow');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'GLS','Gloucestershire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'GRE','Greenwich');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'GSY','Guernsey');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'GWN','Gwynedd');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HAL','Halton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HAM','Hampshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HAV','Havering');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HCK','Hackney');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HEF','Herefordshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HIL','Hillingdon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HLD','Highland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HMF','Hammersmith and Fulham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HNS','Hounslow');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HPL','Hartlepool');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HRT','Hertfordshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HRW','Harrow');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'HRY','Haringey');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'IOS','Isles of Scilly');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'IOW','Isle of Wight');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ISL','Islington');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'IVC','Inverclyde');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'JSY','Jersey');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'KEC','Kensington and Chelsea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'KEN','Kent');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'KHL','Kingston upon Hull');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'KIR','Kirklees');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'KTT','Kingston upon Thames');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'KWL','Knowsley');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LAN','Lancashire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LBH','Lambeth');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LCE','Leicester');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LDS','Leeds');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LEC','Leicestershire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LEW','Lewisham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LIN','Lincolnshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LIV','Liverpool');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LMV','Limavady');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LND','London');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LRN','Larne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LSB','Lisburn');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'LUT','Luton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MAN','Manchester');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MDB','Middlesbrough');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MDW','Medway');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MFT','Magherafelt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MIK','Milton Keynes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MLN','Midlothian');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MON','Monmouthshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MRT','Merton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MRY','Moray');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MTY','Merthyr Tydfil');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'MYL','Moyle');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NAY','North Ayrshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NBL','Northumberland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NDN','North Down');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NEL','North East Lincolnshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NET','Newcastle upon Tyne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NFK','Norfolk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NGM','Nottingham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NLK','North Lanarkshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NLN','North Lincolnshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NSM','North Somerset');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NTA','Newtownabbey');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NTH','Northamptonshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NTL','Neath Port Talbot');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NTT','Nottinghamshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NTY','North Tyneside');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NWM','Newham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NWP','Newport');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NYK','North Yorkshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'NYM','Newry and Mourne');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'OLD','Oldham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'OMH','Omagh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ORK','Orkney Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'OXF','Oxfordshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'PEM','Pembrokeshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'PKN','Perth and Kinross');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'PLY','Plymouth');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'POL','Poole');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'POR','Portsmouth');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'POW','Powys');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'PTE','Peterborough');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'RCC','Redcar and Cleveland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'RCH','Rochdale');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'RCT','Rhondda Cynon Taf');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'RDB','Redbridge');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'RDG','Reading');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'RFW','Renfrewshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'RIC','Richmond upon Thames');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ROT','Rotherham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'RUT','Rutland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SAW','Sandwell');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SAY','South Ayrshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SCB','Scottish Borders');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SFK','Suffolk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SFT','Sefton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SGC','South Gloucestershire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SHF','Sheffield');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SHN','Saint Helens');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SHR','Shropshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SKP','Stockport');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SLF','Salford');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SLG','Slough');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SLK','South Lanarkshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SND','Sunderland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SOL','Solihull');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SOM','Somerset');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SOS','Southend-on-Sea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SRY','Surrey');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'STB','Strabane');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'STE','Stoke-on-Trent');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'STG','Stirling');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'STH','Southampton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'STN','Sutton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'STS','Staffordshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'STT','Stockton-on-Tees');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'STY','South Tyneside');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SWA','Swansea');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SWD','Swindon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'SWK','Southwark');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'TAM','Tameside');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'TFW','Telford and Wrekin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'THR','Thurrock');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'TOB','Torbay');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'TOF','Torfaen');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'TRF','Trafford');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'TWH','Tower Hamlets');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'VGL','Vale of Glamorgan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WAR','Warwickshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WBK','West Berkshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WDU','West Dunbartonshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WFT','Waltham Forest');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WGN','Wigan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WIL','Wiltshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WKF','Wakefield');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WLL','Walsall');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WLN','West Lothian');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WLV','Wolverhampton');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WNM','Windsor and Maidenhead');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WOK','Wokingham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WOR','Worcestershire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WRL','Wirral');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WRT','Warrington');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WRX','Wrexham');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WSM','Westminster');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'WSX','West Sussex');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'YOR','Yorkshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (222,'ZET','Shetland Islands');

INSERT INTO `toc_countries` VALUES (223,'United States of America','US','USA',":name\n:street_address\n:city :state_code :postcode\n:country");

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'AK','Alaska');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'AL','Alabama');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'AS','American Samoa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'AR','Arkansas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'AZ','Arizona');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'CA','California');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'CO','Colorado');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'CT','Connecticut');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'DC','District of Columbia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'DE','Delaware');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'FL','Florida');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'GA','Georgia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'GU','Guam');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'HI','Hawaii');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'IA','Iowa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'ID','Idaho');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'IL','Illinois');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'IN','Indiana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'KS','Kansas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'KY','Kentucky');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'LA','Louisiana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'MA','Massachusetts');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'MD','Maryland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'ME','Maine');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'MI','Michigan');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'MN','Minnesota');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'MO','Missouri');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'MS','Mississippi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'MT','Montana');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'NC','North Carolina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'ND','North Dakota');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'NE','Nebraska');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'NH','New Hampshire');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'NJ','New Jersey');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'NM','New Mexico');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'NV','Nevada');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'NY','New York');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'MP','Northern Mariana Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'OH','Ohio');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'OK','Oklahoma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'OR','Oregon');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'PA','Pennsylvania');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'PR','Puerto Rico');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'RI','Rhode Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'SC','South Carolina');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'SD','South Dakota');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'TN','Tennessee');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'TX','Texas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'UM','U.S. Minor Outlying Islands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'UT','Utah');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'VA','Virginia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'VI','Virgin Islands of the U.S.');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'VT','Vermont');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'WA','Washington');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'WI','Wisconsin');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'WV','West Virginia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (223,'WY','Wyoming');

INSERT INTO `toc_countries` VALUES (224,'United States Minor Outlying Islands','UM','UMI','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (224,'BI','Baker Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (224,'HI','Howland Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (224,'JI','Jarvis Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (224,'JA','Johnston Atoll');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (224,'KR','Kingman Reef');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (224,'MA','Midway Atoll');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (224,'NI','Navassa Island');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (224,'PA','Palmyra Atoll');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (224,'WI','Wake Island');

INSERT INTO `toc_countries` VALUES (225,'Uruguay','UY','URY','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'AR','Artigas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'CA','Canelones');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'CL','Cerro Largo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'CO','Colonia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'DU','Durazno');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'FD','Florida');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'FS','Flores');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'LA','Lavalleja');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'MA','Maldonado');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'MO','Montevideo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'PA','Paysandu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'RN','Río Negro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'RO','Rocha');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'RV','Rivera');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'SA','Salto');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'SJ','San José');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'SO','Soriano');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'TA','Tacuarembó');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (225,'TT','Treinta y Tres');

INSERT INTO `toc_countries` VALUES (226,'Uzbekistan','UZ','UZB','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'AN','Andijon viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'BU','Buxoro viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'FA','Farg\'ona viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'JI','Jizzax viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'NG','Namangan viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'NW','Navoiy viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'QA','Qashqadaryo viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'QR','Qoraqalpog\'iston Respublikasi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'SA','Samarqand viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'SI','Sirdaryo viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'SU','Surxondaryo viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'TK','Toshkent');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'TO','Toshkent viloyati');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (226,'XO','Xorazm viloyati');

INSERT INTO `toc_countries` VALUES (227,'Vanuatu','VU','VUT','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (227,'MAP','Malampa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (227,'PAM','Pénama');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (227,'SAM','Sanma');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (227,'SEE','Shéfa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (227,'TAE','Taféa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (227,'TOB','Torba');

INSERT INTO `toc_countries` VALUES (228,'Vatican City State (Holy See)','VA','VAT','');

INSERT INTO `toc_countries` VALUES (229,'Venezuela','VE','VEN','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'A','Distrito Capital');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'B','Anzoátegui');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'C','Apure');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'D','Aragua');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'E','Barinas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'F','Bolívar');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'G','Carabobo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'H','Cojedes');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'I','Falcón');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'J','Guárico');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'K','Lara');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'L','Mérida');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'M','Miranda');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'N','Monagas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'O','Nueva Esparta');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'P','Portuguesa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'R','Sucre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'S','Tachira');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'T','Trujillo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'U','Yaracuy');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'V','Zulia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'W','Capital Dependencia');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'X','Vargas');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'Y','Delta Amacuro');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (229,'Z','Amazonas');

INSERT INTO `toc_countries` VALUES (230,'Vietnam','VN','VNM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'01','Lai Châu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'02','Lào Cai');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'03','Hà Giang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'04','Cao Bằng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'05','Sơn La');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'06','Yên Bái');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'07','Tuyên Quang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'09','Lạng Sơn');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'13','Quảng Ninh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'14','Hòa Bình');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'15','Hà Tây');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'18','Ninh Bình');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'20','Thái Bình');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'21','Thanh Hóa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'22','Nghệ An');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'23','Hà Tĩnh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'24','Quảng Bình');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'25','Quảng Trị');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'26','Thừa Thiên-Huế');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'27','Quảng Nam');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'28','Kon Tum');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'29','Quảng Ngãi');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'30','Gia Lai');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'31','Bình Định');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'32','Phú Yên');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'33','Đắk Lắk');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'34','Khánh Hòa');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'35','Lâm Đồng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'36','Ninh Thuận');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'37','Tây Ninh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'39','Đồng Nai');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'40','Bình Thuận');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'41','Long An');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'43','Bà Rịa-Vũng Tàu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'44','An Giang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'45','Đồng Tháp');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'46','Tiền Giang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'47','Kiên Giang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'48','Cần Thơ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'49','Vĩnh Long');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'50','Bến Tre');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'51','Trà Vinh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'52','Sóc Trăng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'53','Bắc Kạn');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'54','Bắc Giang');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'55','Bạc Liêu');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'56','Bắc Ninh');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'57','Bình Dương');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'58','Bình Phước');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'59','Cà Mau');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'60','Đà Nẵng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'61','Hải Dương');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'62','Hải Phòng');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'63','Hà Nam');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'64','Hà Nội');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'65','Sài Gòn');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'66','Hưng Yên');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'67','Nam Định');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'68','Phú Thọ');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'69','Thái Nguyên');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'70','Vĩnh Phúc');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'71','Điện Biên');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'72','Đắk Nông');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (230,'73','Hậu Giang');

INSERT INTO `toc_countries` VALUES (231,'Virgin Islands (British)','VG','VGB','');
INSERT INTO `toc_countries` VALUES (232,'Virgin Islands (U.S.)','VI','VIR','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (232,'C','Saint Croix');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (232,'J','Saint John');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (232,'T','Saint Thomas');

INSERT INTO `toc_countries` VALUES (233,'Wallis and Futuna Islands','WF','WLF','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (233,'A','Alo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (233,'S','Sigave');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (233,'W','Wallis');

INSERT INTO `toc_countries` VALUES (234,'Western Sahara','EH','ESH','');
INSERT INTO `toc_countries` VALUES (235,'Yemen','YE','YEM','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'AB','أبين‎');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'AD','عدن');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'AM','عمران');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'BA','البيضاء');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'DA','الضالع');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'DH','ذمار');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'HD','حضرموت');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'HJ','حجة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'HU','الحديدة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'IB','إب');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'JA','الجوف');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'LA','لحج');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'MA','مأرب');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'MR','المهرة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'MW','المحويت');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'SD','صعدة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'SN','صنعاء');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'SH','شبوة');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (235,'TA','تعز');

INSERT INTO `toc_countries` VALUES (236,'Yugoslavia','YU','YUG','');
INSERT INTO `toc_countries` VALUES (237,'Zaire','ZR','ZAR','');

INSERT INTO `toc_countries` VALUES (238,'Zambia','ZM','ZMB','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (238,'01','Western');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (238,'02','Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (238,'03','Eastern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (238,'04','Luapula');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (238,'05','Northern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (238,'06','North-Western');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (238,'07','Southern');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (238,'08','Copperbelt');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (238,'09','Lusaka');

INSERT INTO `toc_countries` VALUES (239,'Zimbabwe','ZW','ZWE','');

INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (239,'MA','Manicaland');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (239,'MC','Mashonaland Central');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (239,'ME','Mashonaland East');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (239,'MI','Midlands');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (239,'MN','Matabeleland North');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (239,'MS','Matabeleland South');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (239,'MV','Masvingo');
INSERT INTO `toc_zones` (zone_country_id, zone_code, zone_name) VALUES (239,'MW','Mashonaland West');


INSERT INTO `toc_credit_cards` VALUES (1,'American Express','/^(34|37)\\d{13}$/','0','0');
INSERT INTO `toc_credit_cards` VALUES (2,'Diners Club','/^(30|36|38)\\d{12}$/','0','0');
INSERT INTO `toc_credit_cards` VALUES (3,'JCB','/^((2131|1800)\\d{11}|3[0135]\\d{14})$/','0','0');
INSERT INTO `toc_credit_cards` VALUES (4,'MasterCard','/^5[1-5]\\d{14}$/','1','0');
INSERT INTO `toc_credit_cards` VALUES (5,'Visa','/^4\\d{12}(\\d{3})?$/','1','0');
INSERT INTO `toc_credit_cards` VALUES (6,'Discover Card','/^6011\\d{12}$/','0','0');
INSERT INTO `toc_credit_cards` VALUES (7,'Solo','/^(63|67)\\d{14}(\\d{2,3})?$/','0','0');
INSERT INTO `toc_credit_cards` VALUES (8,'Switch','/^(49|56|63|67)\\d{14}(\\d{2,3})?$/','0','0');
INSERT INTO `toc_credit_cards` VALUES (9,'Australian Bankcard','/^5610\\d{12}$/','0','0');
INSERT INTO `toc_credit_cards` VALUES (10,'enRoute','/^(2014|2149)\\d{11}$/','0','0');
INSERT INTO `toc_credit_cards` VALUES (11,'Laser','/^6304\\d{12}(\\d{2,3})?$/','0','0');
INSERT INTO `toc_credit_cards` VALUES (12,'Maestro','/^(50|56|57|58|6)/','0','0');
INSERT INTO `toc_credit_cards` VALUES (13,'Saferpay Test Card','/^9451123100000004$/','0','0');


INSERT INTO `toc_currencies` VALUES (1,'US Dollar','USD','$','','2','1.0000', now());
INSERT INTO `toc_currencies` VALUES (2,'Euro','EUR','€','','2','0.73457497', now());
INSERT INTO `toc_currencies` VALUES (3,'British Pounds','GBP','￡','','2','0.65841001', now());
INSERT INTO `toc_currencies` VALUES (4,'人民币','CNY','￥','','2','6.2000000', now());


INSERT INTO `toc_languages` VALUES (1, 'English', 'en_US', 'en_US.UTF-8,en_US,english', 'utf-8', '%m/%d/%Y', '%A %d %B, %Y', '%H:%M:%S', 'ltr', 1, '.', ',', 0, 1);
INSERT INTO `toc_languages` VALUES (2, 'Chinese Simplified', 'zh_CN', 'zh_CN.UTF-8,zh_CN,simplified chinese', 'utf-8', '%Y-%m-%d', '%Y 年 %m 月 %d 日 %A', '%H:%M:%S', 'ltr', 1, '.', ',', 0, 1);


INSERT INTO `toc_orders_status` VALUES ( '1', '1', 'Pending', '1', '0', '0', '0');
INSERT INTO `toc_orders_status` VALUES ( '2', '1', 'Processing', '1', '0', '0', '0');
INSERT INTO `toc_orders_status` VALUES ( '3', '1', 'Preparing', '1', '0', '0', '0');
INSERT INTO `toc_orders_status` VALUES ( '4', '1', 'Partly Paid', '1', '0', '0', '0');
INSERT INTO `toc_orders_status` VALUES ( '5', '1', 'Paid', '1', '1', '0', '1');
INSERT INTO `toc_orders_status` VALUES ( '6', '1', 'Partly Delivered', '1', '1', '1', '1');
INSERT INTO `toc_orders_status` VALUES ( '7', '1', 'Delivered', '1', '1', '1', '1');
INSERT INTO `toc_orders_status` VALUES ( '8', '1', 'Cancelled', '1', '0', '0', '0');


INSERT INTO `toc_orders_status` VALUES ( '1', '2', '未处理', '1', '0', '0', '0');
INSERT INTO `toc_orders_status` VALUES ( '2', '2', '处理中', '1', '0', '0', '0');
INSERT INTO `toc_orders_status` VALUES ( '3', '2', '备货中', '1', '0', '0', '0');
INSERT INTO `toc_orders_status` VALUES ( '4', '2', '部分支付', '1', '0', '0', '0');
INSERT INTO `toc_orders_status` VALUES ( '5', '2', '已付款', '1', '1', '0', '1');
INSERT INTO `toc_orders_status` VALUES ( '6', '2', '部分发货', '1', '1', '1', '1');
INSERT INTO `toc_orders_status` VALUES ( '7', '2', '已发货', '1', '1', '1', '1');
INSERT INTO `toc_orders_status` VALUES ( '8', '2', '取消', '1', '0', '0', '0');


INSERT INTO `toc_orders_returns_status` VALUES
(1, 1, 'Pending'),
(2, 1, 'Confirmed'),
(3, 1, 'Received'),
(4, 1, 'Return Authorized'),
(5, 1, 'Return Refunded(Credit Slip)'),
(6, 1, 'Return Refunded(Store Credit)'),
(7, 1, 'Return Rejected');


INSERT INTO `toc_orders_returns_status` VALUES
(1, 2, '未处理'),
(2, 2, '确认'),
(3, 2, '已接收'),
(4, 2, '已被授权'),
(5, 2, '已返回退款（信用记录）'),
(6, 2, '已返回退款（信用积分）'),
(7, 2, '已被驳回');


INSERT INTO `toc_orders_transactions_status` VALUES ( '1', '1', 'Authorize');
INSERT INTO `toc_orders_transactions_status` VALUES ( '2', '1', 'Cancel');
INSERT INTO `toc_orders_transactions_status` VALUES ( '3', '1', 'Approve');
INSERT INTO `toc_orders_transactions_status` VALUES ( '4', '1', 'Inquiry');


INSERT INTO `toc_orders_transactions_status` VALUES ( '1', '2', '授权');
INSERT INTO `toc_orders_transactions_status` VALUES ( '2', '2', '取消');
INSERT INTO `toc_orders_transactions_status` VALUES ( '3', '2', '批准');
INSERT INTO `toc_orders_transactions_status` VALUES ( '4', '2', '调查中');


INSERT INTO `toc_products_images_groups` VALUES (1, 1, 'Originals', 'originals', 0, 0, 0);
INSERT INTO `toc_products_images_groups` VALUES (2, 1, 'Thumbnails', 'thumbnails', 140, 140, 0);
INSERT INTO `toc_products_images_groups` VALUES (3, 1, 'Product Information Page', 'product_info', 285, 255, 0);
INSERT INTO `toc_products_images_groups` VALUES (4, 1, 'Large', 'large', 480, 360, 0);
INSERT INTO `toc_products_images_groups` VALUES (5, 1, 'Mini', 'mini', 57, 57, 0);


INSERT INTO `toc_products_images_groups` VALUES (1, 2, '原图', 'originals', 0, 0, 0);
INSERT INTO `toc_products_images_groups` VALUES (2, 2, '略缩图', 'thumbnails', 140, 140, 0);
INSERT INTO `toc_products_images_groups` VALUES (3, 2, '产品信息页', 'product_info', 285, 255, 0);
INSERT INTO `toc_products_images_groups` VALUES (4, 2, '大图', 'large', 480, 360, 0);
INSERT INTO `toc_products_images_groups` VALUES (5, 2, '袖珍图', 'mini', 57, 57, 0);


INSERT INTO `toc_tax_class` VALUES (1, 'Taxable Goods', 'The following types of products are included non-food, services, etc', now(), now());


INSERT INTO `toc_quantity_unit_classes` VALUES
(1, 1, 'pcs'),
(2, 1, 'kg'),
(3, 1, 'liter'),
(4, 1, 'dozen');


INSERT INTO `toc_quantity_unit_classes` VALUES
(1, 2, '件'),
(2, 2, '千克'),
(3, 2, '升'),
(4, 2, '打');

INSERT INTO `toc_tax_rates` VALUES (1, 1, 1, 1, 7.0, 'FL TAX 7.0%', now(), now());
INSERT INTO `toc_geo_zones` VALUES (1,"Florida","Florida local sales tax zone",now(),now());
INSERT INTO `toc_zones_to_geo_zones` VALUES (1,223,4031,1,now(),now());


INSERT INTO `toc_templates` VALUES
(1, 'TomatoCart Default Template', 'default', 'TomatoCart', 'http://www.tomatocart.com', 'XHTML 1.0 Transitional', 1, 'Screen', '{"template_scheme":"green","logo_slogan":"TomatoCart Open Source Ecommerce Solution"}');


INSERT INTO `toc_templates_modules` VALUES
(1, 1, 'categories', 1, '*', 'left', 1, 0, '{"MODULE_CATEGORIES_SHOW_PRODUCT_COUNT":"1"}'),
(2, 1, 'manufacturers', 1, '*', 'left', 2, 0, '{"BOX_MANUFACTURERS_LIST_TYPE":"Image List","BOX_MANUFACTURERS_LIST_SIZE":"4"}'),
(3, 1, 'article_categories', 1, '*', 'left', 3, 0, '{"MODULE_ARTICLES_CATEGORIES_MAX_LIST":"4"}'),
(4, 1, 'popular_search_terms', 1, '*', 'left', 4, 0, '{"MODULE_POPULAR_SEARCH_TERM_CACHE":"60"}'),
(5, 1, 'shop_by_price', 1, '*', 'left', 5, 0, '{"MODULE_SHOP_BY_PRICE_USD":"50;100;200;500;1000","MODULE_SHOP_BY_PRICE_EUR":"50;100;200;500;1000","MODULE_SHOP_BY_PRICE_GBP":"50;100;200;500;1000"}'),
(6, 1, 'slideshows', 1, 'index/index', 'slideshow', 0, 0, '{"MODULE_SLIDESHOW_IMAGE_GROUPS":"home_slide","MODULE_SLIDESHOW_PLAY_INTERVAL":"3000","MODULE_SLIDESHOW_DISPLAY_CAROUSEL_CONTROL":"true","MODULE_SLIDESHOW_DISPLAY_SLIDE_INFO":"true"}'),
(7, 1, 'new_products', 1, '*', 'after', 0, 0, '{"MODULE_CONTENT_NEW_PRODUCTS_MAX_DISPLAY":"9","MODULE_NEW_PRODUCTS_CACHE":"60"}'),
(8, 1, 'feature_products', 1, '*', 'after', 0, 0, '{"MODULE_FEATURE_PRODUCTS_MAX_DISPLAY":"9"}'),
(9, 1, 'special_products', 1, '*', 'after', 0, 0, '{"MODULE_SPECIAL_MAX_DISPLAY":"9","MODULE_SPECIAL_CACHE":"60"}'),
(10, 1, 'information', 1, '*', 'footer-col-1', 0, 0, 'null'),
(11, 1, 'article_categories', 1, '*', 'footer-col-2', 0, 0, '{"MODULE_ARTICLES_CATEGORIES_MAX_LIST":"10"}'),
(12, 1, 'follow_us', 1, '*', 'footer-col-3', 0, 0, '{"MODULE_FOLLOW_US_FACEBOOK_LINK":"https://www.facebook.com/tomatocart","MODULE_FOLLOW_US_TWITTER_LINK":"https://twitter.com/tomatocart","MODULE_FOLLOW_US_GOOGLE_PLUS_LINK":"https://plus.google.com/109588253708268031594"}'),
(13, 1, 'contact_us', 1, '*', 'footer-col-4', 0, 0, 'null');


INSERT INTO `toc_extensions` VALUES
(1, 'order_total_subtotal_title', 'sub_total', '', '', 'order_total', '{"MODULE_ORDER_TOTAL_SUBTOTAL_STATUS":"true","MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER":"10"}'),
(2, 'order_total_shipping_title', 'shipping', '', '', 'order_total', '{"MODULE_ORDER_TOTAL_SHIPPING_STATUS":"true","MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER":"20"}'),
(3, 'order_total_tax_title', 'tax', '', '', 'order_total', '{"MODULE_ORDER_TOTAL_TAX_STATUS":"true","MODULE_ORDER_TOTAL_TAX_SORT_ORDER":"30"}'),
(4, 'order_total_total_title', 'total', '', '', 'order_total', '{"MODULE_ORDER_TOTAL_TOTAL_STATUS":"true","MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER":"40"}'),
(5, 'services_sef_title', 'sef', '', '', 'service', '{"SERVICES_KEYWORD_RICH_URLS":"1"}'),
(6, 'shipping_item_title', 'item', '', '', 'shipping', '{"MODULE_SHIPPING_ITEM_STATUS":"True","MODULE_SHIPPING_ITEM_COST":"2.50","MODULE_SHIPPING_ITEM_HANDLING":"1","MODULE_SHIPPING_ITEM_TAX_CLASS":"0","MODULE_SHIPPING_ITEM_ZONE":"0","MODULE_SHIPPING_ITEM_SORT_ORDER":"0"}'),
(7, 'shipping_flat_title', 'flat', '', '', 'shipping', '{"MODULE_SHIPPING_FLAT_STATUS":"True","MODULE_SHIPPING_FLAT_COST":"15.00","MODULE_SHIPPING_FLAT_TAX_CLASS":"0","MODULE_SHIPPING_FLAT_ZONE":"0","MODULE_SHIPPING_FLAT_SORT_ORDER":"2"}'),
(8, 'shipping_self_pickup_title', 'self_pickup', '', '', 'shipping', '{"MODULE_SHIPPING_SELF_PICKUP_STATUS":"True","MODULE_SHIPPING_SELF_PICKUP_ZONE":"0","MODULE_SHIPPING_SELF_PICKUP_SORT_ORDER":"0"}'),
(9, 'payment_cod_title', 'cod', '', '', 'payment', '{"MODULE_PAYMENT_COD_STATUS":"True","MODULE_PAYMENT_COD_ZONE":"0","MODULE_PAYMENT_COD_SORT_ORDER":"0","MODULE_PAYMENT_COD_ORDER_STATUS_ID":"0"}'),
(10, 'payment_bank_wire_title', 'bank_wire', '', '', 'payment', '{"MODULE_PAYMENT_BANK_WIRE_STATUS":"True","MODULE_PAYMENT_BANK_WIRE_ZONE":"0","MODULE_PAYMENT_BANK_WIRE_SORT_ORDER":"0","MODULE_PAYMENT_BANK_WIRE_ORDER_STATUS_ID":"6","MODULE_PAYMENT_BANK_WIRE_DETAIL":"2","MODULE_PAYMENT_BANK_WIRE_BANK_ADDRESS":"3"}'),
(11, 'payment_cheque_title', 'cheque', '', '', 'payment', '{"MODULE_PAYMENT_CHEQUE_STATUS":"True","MODULE_PAYMENT_CHEQUE_ZONE":"0","MODULE_PAYMENT_CHEQUE_SORT_ORDER":"0","MODULE_PAYMENT_CHEQUE_ORDER_STATUS_ID":"0","MODULE_PAYMENT_CHEQUE_ACCOUNT_OWNER":"asdfasdfasdfa","MODULE_PAYMENT_CHEQUE_ADDRESS":"sdfasfdasdfadfasdfasfdasdf"}');


INSERT INTO `toc_articles_categories` VALUES (1, 1, 1);
INSERT INTO `toc_articles_categories_description` VALUES (1, 1, 'Information', 'information', '', '' , '');
INSERT INTO `toc_articles_categories_description` VALUES (1, 2, '网店信息', 'information', '', '' , '');


INSERT INTO `toc_articles` VALUES
(1, 1, 1, 1, now(), now(), NULL),
(2, 1, 1, 2, now(), now(), NULL),
(3, 1, 1, 3, now(), now(), NULL),
(4, 1, 1, 4, now(), now(), NULL),
(5, 1, 1, 5, now(), now(), NULL);


INSERT INTO `toc_articles_description` VALUES
(1, 1, 'About Us', 'about-us', 'Put here the required information.', '', '', ''),
(2, 1, 'Shipping & Returns', 'shipping-returns', 'Put here the required information.', '', '', ''),
(3, 1, 'Privacy Notice', 'privacy-notice', 'Put here the required information.', '', '', ''),
(4, 1, 'Conditions of Use', 'conditions-of-use', 'Put here the required information.', '', '', ''),
(5, 1, 'Imprint', 'imprint', 'Put here the required information.', '', '', '');


INSERT INTO `toc_articles_description` VALUES
(1, 2, '关于我们', 'about-us', '请输入有关信息.', '', '', ''),
(2, 2, '运输 & 退货', 'shipping-returns', '请输入有关信息.', '', '', ''),
(3, 2, '隐私声明', 'privacy-notice', '请输入有关信息.', '', '', ''),
(4, 2, '使用条件', 'conditions-of-use', '请输入有关信息.', '', '', ''),
(5, 2, '版本说明', 'imprint', '请输入有关信息.', '', '', '');


INSERT INTO `toc_weight_classes` VALUES (1, 'g', 1, 'Gram(s)');
INSERT INTO `toc_weight_classes` VALUES (2, 'kg', 1, 'Kilogram(s)');
INSERT INTO `toc_weight_classes` VALUES (3, 'oz', 1, 'Ounce(s)');
INSERT INTO `toc_weight_classes` VALUES (4, 'lb', 1, 'Pound(s)');


INSERT INTO `toc_weight_classes` VALUES (1, 'g', 2, '克');
INSERT INTO `toc_weight_classes` VALUES (2, 'kg', 2, '千克');
INSERT INTO `toc_weight_classes` VALUES (3, 'oz', 2, '盎司');
INSERT INTO `toc_weight_classes` VALUES (4, 'lb', 2, '磅');


INSERT INTO `toc_weight_classes_rules` VALUES (1, 2, '0.0010');
INSERT INTO `toc_weight_classes_rules` VALUES (1, 3, '0.0352');
INSERT INTO `toc_weight_classes_rules` VALUES (1, 4, '0.0022');
INSERT INTO `toc_weight_classes_rules` VALUES (2, 1, '1000.0000');
INSERT INTO `toc_weight_classes_rules` VALUES (2, 3, '35.2739');
INSERT INTO `toc_weight_classes_rules` VALUES (2, 4, '2.2046');
INSERT INTO `toc_weight_classes_rules` VALUES (3, 1, '28.3495');
INSERT INTO `toc_weight_classes_rules` VALUES (3, 2, '0.0283');
INSERT INTO `toc_weight_classes_rules` VALUES (3, 4, '0.0625');
INSERT INTO `toc_weight_classes_rules` VALUES (4, 1, '453.5923');
INSERT INTO `toc_weight_classes_rules` VALUES (4, 2, '0.4535');
INSERT INTO `toc_weight_classes_rules` VALUES (4, 3, '16.0000');


INSERT INTO `toc_email_templates` VALUES
(1, 'create_account_email', 1),
(2, 'password_forgotten', 1),
(3, 'tell_a_friend', 1),
(4, 'new_order_created', 1),
(5, 'admin_order_status_updated', 1),
(6, 'admin_create_account_email', 1),
(7, 'abandoned_cart_inquiry', 1),
(8, 'send_coupon', 1),
(9, 'admin_customer_credits_change_notification', 1),
(10, 'share_wishlist', 1),
(11, 'active_gift_certificate', 1),
(12, 'active_downloadable_product', 1),
(13, 'admin_create_order_credit_slip', 1),
(14, 'admin_create_order_store_credit', 1),
(15, 'admin_password_forgotten', 1),
(16, 'out_of_stock_alerts', 1);


INSERT INTO `toc_email_templates_description` VALUES
(1, 1, 'Welcome to %%store_name%%', '<p>%%greeting_text%%</p><br /><br /><p>We welcome you to %%store_name%%!</p><br /><br /><p>You can now take part in the various services we have offer for you. Some of these services include:</p><br /><br /><ul><br /><li>Permanent Cart - Any products added to your online cart remain there until you remove them, or check them out.<br /><li>Address Book - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.<br /><li>Order History - View your history of purchases that you have made with us.<br /><li>Products Reviews - Share your opinions on products with our other customers.<br /></ul><br /><p>For help with any of our online services, please email the store-owner: %%store_owner_email_address%%.</p><br /><br />Note: This email address was given to us by one of our customers. If you did not signup to be a member, please send an email to the store owner.'),
(2, 1, 'Password Reminder to TomatoCart', 'A new password was requested from %%customer_ip_address%%.<br /><br />Your new password to %%store_name%% is:<br /><br />%%customer_password%%<br /><br />For help with any of our online services, please email the store-owner: %%store_owner_email_address%%.<br /><br />Note: If you did not request this action via our password forgotten page, please notify the store owner as soon as possible.'),
(3, 1, 'Your friend %%from_name%% has recommended this great product from %%store_name%%', 'Hi %%to_name%%!<br /><br />Your friend, %%from_name%%, thought that you would be interested in %%product_name%% from %%store_name%%.<br /><br />%%message%%<br /><br />To view the product click on the link below or copy and paste the link into your web browser:<br /><br />%%product_link%%<br /><br />Regards,<br /><br />%%store_name%% <br />%%store_address%%'),
(4, 1, 'Order Process', 'TomatoCart<br />------------------------------------------------------<br />Order Number: %%order_number%%<br />Detailed Invoice: %%invoice_link%%<br />Date Ordered: %%date_ordered%%<br /><br />%%order_details%%<br /><br />Delivery Address<br />------------------------------------------------------<br />%%delivery_address%%<br /><br />Billing Address<br />------------------------------------------------------<br />%%billing_address%%<br /><br />Order Status: %%order_status%%<br />------------------------------------------------------<br />%%order_comments%%'),
(5, 1, 'Order Update (%%store_name%%)', '%%store_name%%<br />------------------------------------------------------<br />Order Number: %%order_number%%<br />Detailed Invoice: %%invoice_link%%<br />Date Ordered: %%date_ordered%%<br /><br />Order Comment<br />------------------------------------------------------<br />%%order_comment%%<br /><br />Order Status<br />------------------------------------------------------<br />New Status: %%new_order_status%%<br /><br />Please reply to this e-mail if you have any questions regarding this order.'),
(6, 1, 'Welcome to %%store_name%%', '%%greeting_text%%<br /><br />We welcome you to %%store_name%%.<br /><br />You can now take part in the various services we have to offer you. Some of these services include:<br /><br />* Permanent Shopping Cart - Any products added to your online shopping cart remain there until you purchase or remove them<br />* Address Book - Products can be delivered to any addresses you define in your address book! This is perfect to send gifts directly to the customers.<br />* Order History - The previous orders you have made can be viewed online.<br />* Product Reviews -  Share opinions about products with other customers.<br /><br />For help with any of our online services please email us at: %%store_owner_email_address%%<br />Please note: This account has been created for you by the store owner. <br /><br />Please use your e-mail address with the following password as your login account: %%password%%'),
(7, 1, 'Inquiry from %%store_name%%', '%%greeting_text%%<br /><br />We noticed that during a visit to our store you placed the following item(s) in your shopping cart, but did not complete the transaction.<br /><br />Shopping Cart Contents:<br /><br />%%shopping_cart_contents%%<br /><br />Comment:<br /><br />%%comment%%<br /><br />We are always interested in knowing what happened and if there was a reason that you decided not to purchase at this time. If you could be so kind as to let us know if you had any issues or concerns, we would appreciate it. <br /><br />We are asking for feedback from you and others as to how we can help make your experience at %%store_name%% better.<br /><br />PLEASE NOTE:<br /><br />If you believe you completed your purchase and are wondering why it was not delivered, this email is an indication that your order was NOT completed, and that you have NOT been charged! Please return to the store in order to complete your order.<br /><br />Our apologies if you already completed your purchase, we try not to send these messages in those cases, but sometimes it is hard for us to tell depending on individual circumstances.<br /><br />Again, thank you for your time and consideration in helping us improve the %%store_name%%.<br /><br />Sincerely,<br />%%store_name%%'),
(8, 1, 'You have received a coupon from %%store_name%%', '%%greeting_text%%<br /><br />You have received a coupon from %%store_name%%. You can redeem this coupon during checkout. Just enter the code in the box provided, and click on the redeem button.<br /><br />The coupon code is: %%coupon_code%%<br /><br />Don''t lose the coupon code, make sure to keep the code safe so you can benefit from this special offer.<br /><br />%%addtional_message%%'),
(9, 1, 'You have receive a store credit from administrator', '%%greeting_text%% <p> The administrator has updated your store credits. Now you have %%customer_credits%% credits in your account. </p>'),
(10, 1, 'Your friend %%from_name%% want to share his wishlist from %%store_name%%', 'Hi!<br /><br />Your friend %%from_name%% want to share his wishlist from %%store_name%%.<br /><br />%%message%%<br /><br />To view the wishlist click on the link below or copy and paste the link into your web browser:<br /><br />%%wishlist_url%%<br /><br />Regards,<br /><br />%%store_name%% <br />%%store_address%%'),
(11, 1, 'You have received a gift certificate from %%recipient_name%%', 'Dear %%recipient_name%%,<br /><br />You have received a gift certificate from %%sender_name%%. You can redeem this gift certificate during checkout. Just enter the code in the box provided, and click on the redeem button.<br /><br />The gift certificate amount is %%gift_certificate_amount%% and  the gift certificate code is: %%gift_certificate_code%%<br /><br />Don''t lose the gift certificate code and make sure to keep the code safe.<br /><br /><b>%%gift_certificate_message%%</b><br /><br />Regards,<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(12, 1, 'The download link for %%downloadable_products%% is actived', 'Dear %%customer_name%%,<br /><br />The download link for the products you purchased from store %%store_name%%: <br /><br />%%downloadable_products%%<br /><br />is actived.<br /><br />Please go to the orders area of "My Account" and download the products.<br /><br />%%download_link%%<br /><br />Regards,<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(13, 1, 'A new credit slip is created for returned products', 'Dear %%customer_name%%,<br /><br />A new credit slip is created for following returned products:<br /><br /> %%returned_products%% <br /><br />from order %%order_number%%. The slip number is %%slip_number%% and the total amount is %%total_amount%%. You can print out the credit slip in the "My Acount" area. <br /><br />Regards,<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(14, 1, 'New store credit is created for returned products', 'Dear %%customer_name%%,<br /><br />New store credit is created for following returned products:<br /><br /> %%returned_products%% <br /><br />from order %%order_number%%. The total amount is %%total_amount%% and the store credit is made to your billing account so that it can be used for future purchases. <br /><br />Regards,<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(15, 1, 'Administrator Password Reminder to TomatoCart', 'A new password was requested from %%admin_ip_address%%.<br /><br />Your new password is:<br /><br />%%admin_password%%<br /><br />Regards,<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(16, 1, 'Product out of Stock', 'TomatoCart<br>---------------------------------------------------<br>%%products_name%% %%products_variants%% is out of stock.<br>---------------------------------------------------<br>Remaining stock: %%products_quantity%%. You are advised to turn <br>to the Products section in the admin panel to replenish the inventory.');


INSERT INTO `toc_email_templates_description` VALUES
(1, 2, '欢迎来到 %%store_name%%', '<p>%%greeting_text%%</p><br /><br /><p>欢迎您来到 %%store_name%%!</p><br /><br /><p>您现在可以参加我们为您提供的多种服务。这些服务包括：</p><br /><br /><ul><br /><li>永久购物车 - 您添加到购物车中的所有产品都将被保存，直到您删除它或提交该订单为止。</li><br /><li>地址簿 - 我们能将您的产品信息发送给除了您之外的任何地址，这对于将生日礼物发送给当天的寿星们是一件很完美的事。</li><br /><li>订单记录 - 查看您在我们系统中的采购历史记录。</li><br /><li>产品评论 - 和其他客户一起分享您对产品的评论和意见。</li><br /></ul><br /><p>如果您需要对于我们提供的任何在线服务的帮助信息， 请发送电子邮件给店主：  %%store_owner_email_address%% 。</p><br /><br />注意：该电子邮件地址是通过我们的客户给我们的。如果您没有注册成为会员，请发送电子邮件给店主。'),
(2, 2, 'TomatoCart 密码提示', '一个新的密码请求从%%customer_ip_address%%.<br /><br />您的 %%store_name%% 账号的新密码是:<br /><br />%%customer_password%%<br /><br />如果您对我们的在线服务有其他的请求，请通过邮件与店主: %%store_owner_email_address%%联系.<br /><br />注意: 如果您的请求不是通过密码忘记页面发送，请尽快与店主取得联系。'),
(3, 2, '您的朋友%%from_name%% 从 %%store_name%% 给您推荐这个优秀产品', '您好 %%to_name%%!<br /><br />您的朋友, %%from_name%%, 认为您可能对 %%product_name%% 感兴趣，该产品来至于 %%store_name%%.<br /><br />%%message%%<br /><br />点击查看产品链接或将该链接地址复制到浏览器地址栏:<br /><br />%%product_link%%<br /><br />问候,<br /><br />%%store_name%% <br />%%store_address%%'),
(4, 2, '订单更新 (%%store_name%%)', '%%store_name%%<br />------------------------------------------------------<br />订单编号: %%order_number%%<br />详细发票: %%invoice_link%%<br />订单日期: %%date_ordered%%<br /><br />订单评论<br />------------------------------------------------------<br />%%order_comment%%<br /><br />订单状态<br />------------------------------------------------------<br />新状态: %%new_order_status%%<br /><br />如果您对于这个订单有什么问题，请回复此邮件地址。'),
(5, 2, '订单更新 (%%store_name%%)', '%%store_name%%<br />------------------------------------------------------<br />订单编号: %%order_number%%<br />详细发票: %%invoice_link%%<br />订单日期: %%date_ordered%%<br /><br />订单评论<br />------------------------------------------------------<br />%%order_comment%%<br /><br />订单状态<br />------------------------------------------------------<br />新状态: %%new_order_status%%<br /><br />如果您对于这个订单有什么问题，请回复此邮件地址。'),
(6, 2, '欢迎来到 %%store_name%%', '%%greeting_text%%<br /><br />我们欢迎您来到 %%store_name%%.<br /><br />现在您可以参加我们为您提供的各种服务。其中一些服务包括：:<br /><br />* 永久购物车 - 任何添加到您的购物车的产品都将留在您的购物车里，直到您购买或删除它们。<br />* 地址簿 - 产品将送往您地址薄里的任何地址，对于将产品直接送达客户这太完美了<br />* 订单历史 - 以前定制的订单可以在线浏览。<br />* 产品评价 -  和其他客户一起分享产品评论。<br /><br />对于我们的在线服务有任何疑问，请发邮件至: %%store_owner_email_address%%<br />请注意: 店主已经为您创建了此账户， <br /><br />请使用您的E-Mail地址和以下密码: %%password%%登录网店系统。'),
(7, 2, '来自%%store_name%%的询问', '%%greeting_text%%<br /><br />我们注意到,您来到我们的网店并将以下产品放入购物车，但没有完成交易。<br /><br />Shopping Cart Contents:<br /><br />%%shopping_cart_contents%%<br />  <br />我们想知道，在那时发生了什么事情或者有什么原因使您决定不完成该交易。 如果您对我们的网店有什么问题或疑虑，请让我们知道，我们将不胜感激。 <br /><br />我们谢谢您宝贵的反馈意见，并且我们能帮助您在 %%store_name%% 获得更好的经验。<br /><br />请注意如下信息：<br /><br />如果您确信您完成了您的交易并想要知道为什么没有发货，这封电子邮件就是标明您的交易并没有完成，并且您并没有支付! 请返回网店，以便完成您的订单。<br /><br />如果您确实完成了您的订单，对此我们表示歉意。在这种情况下我们将避免发送这些信息给您，个别情况下我们也很难决定。<br /><br />再次感谢您，花费您宝贵的时间和意见来帮助我们改善 %%store_name%%。<br /><br />%%addtional_message%%<br /><br />真诚的,<br />%%store_name%%'),
(8, 2, '您收到了来自 %%store_name%% 的优惠券', '%%greeting_text%%<br /><br />您收到了一个来自 %%store_name%% 的优惠券。您可以在结算时兑换该优惠券。 您只需要将代码输入提供的的输入框中，并单击兑换按钮即可。<br /><br />优惠券代码是： %%coupon_code%%<br /><br />请不要丢失该优惠券代码，确保优惠券代码的安全，这样你可以从这些特价产品中获得优惠。<br /><br />%%addtional_message%%'),
(9, 2, '您已经收到商店管理员的信用积分', '%%greeting_text%% <p> 管理员更新了您的信用积分。现在您已经拥有 %%customer_credits%% 积分在您的账户里</p>'),
(10, 2, '您的朋友 %%from_name%% 想要分享他的来自 %%store_name%% 的收藏夹', '嗨!<br /><br />您的朋友 %%from_name%% 想要分享他的来自 %%store_name%% 的收藏夹。<br /><br />%%message%%<br /><br />要想展示该收藏夹，请单击如下连接，或复制该连接地址粘贴到您的网页浏览器中：<br /><br />%%wishlist_url%%<br /><br />问候,<br /><br />%%store_name%% <br />%%store_address%%'),
(11, 2, '您收到了一个来自 %%sender_name%% 的礼券', '亲爱的 %%recipient_name%%,<br /><br />您接收到了来自 %%sender_name%% 的一个礼券。 您可以在结账时兑换该礼券。 您只要在提供的输入框中输入礼券代码，并单击兑换按钮即可。<br /><br />礼券金额是： %%gift_certificate_amount%% 礼券代码是： %%gift_certificate_code%%<br /><br />请不要丢失礼券代码，并确保礼券代码的安全。<br /><br /><b>%%gift_certificate_message%%</b><br /><br />致此,<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(12, 2, '下载链接 %%downloadable_products%% 已经被激活', '亲爱的%%customer_name%%,<br /><br />您从 %%store_name%%购买的产品: <br /><br />%%downloadable_products%%<br /><br />的下载链接已经被激活。<br /><br />请去订单模块的“我的账户”页面，并下载产品。<br /><br />%%download_link%%<br /><br />问候,<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(13, 2, '一个新的用于退货的退款单被创建了', '亲爱的 %%customer_name%%,<br /><br />新的退款单包含以下退货产品：<br /><br /> %%returned_products%% <br /><br />来自于订单 %%order_number%%的退款单被创建了。 退货单编号是： %%slip_number%% ，总金额是： %%total_amount%%。您可以在“我的账号”区域打印出该退货单。 <br /><br />问候,<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(14, 2, '退货产品产生的新的网店信用积分已经创建', '亲爱的%%customer_name%%,<br /><br />关于以下退货产品：<br /><br /> %%returned_products%% <br /><br />来自订单 %%order_number%%的网店积分被创建了。 总金额是 %%total_amount%% 网店积分可以用于您的付费帐户，方便您以后的采购。 <br /><br />问候，<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(15, 2, 'TomatoCart 管理员密码提示信', '一个新的密码请求来自于%%admin_ip_address%%。<br /><br />您的新密码是：<br /><br />%%admin_password%%<br /><br />问候，<br /><br />%%store_name%% <br />%%store_owner_email_address%%'),
(16, 2, '产品缺货', 'TomatoCart<br />---------------------------------------------------<br />产品：%%products_name%% %%products_variants%% 缺货中。<br />---------------------------------------------------<br />库存量: %%products_quantity%%。<br />请及时补充货源。');