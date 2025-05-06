-- Thana 02-May-2025
ALTER TABLE `purchase_details` ADD `invoice_no` VARCHAR(2566) NULL DEFAULT NULL AFTER `supplier_id`;

-- Thana 06-May-2025
ALTER TABLE `supplier_details` ADD `gstin` VARCHAR(50) NULL DEFAULT NULL AFTER `supplier_name`;
