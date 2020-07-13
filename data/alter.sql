-- 21 Apr, 2020
ALTER TABLE `product_details` ADD `product_description` TEXT NULL DEFAULT NULL AFTER `product_name`;
-- ALTER TABLE `product_details` CHANGE `product_description` `product_description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `product_name`;
-- 21 Apr, 2020
ALTER TABLE `company_profile` ADD `website` VARCHAR(255) NULL DEFAULT NULL AFTER `company_email`, ADD `lat` VARCHAR(50) NULL DEFAULT NULL AFTER `website`, ADD `lng` VARCHAR(50) NULL DEFAULT NULL AFTER `lat`;
ALTER TABLE `bill_product_details` CHANGE `product_name` `product_name` INT(11) NOT NULL;
ALTER TABLE `bill_product_details` ADD FOREIGN KEY (`product_name`) REFERENCES `product_details`(`product_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
-- 24 Apr, 2020
ALTER TABLE `bill_product_details` ADD `sqft` VARCHAR(50) NULL DEFAULT NULL AFTER `product_name`;
-- 1 Jul, 2020
ALTER TABLE `product_details` ADD `options` TEXT NULL DEFAULT NULL AFTER `product_tax`;