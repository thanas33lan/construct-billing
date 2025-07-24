-- Thana 02-May-2025
ALTER TABLE `purchase_details` ADD `invoice_no` VARCHAR(2566) NULL DEFAULT NULL AFTER `supplier_id`;
-- Thana 06-May-2025
ALTER TABLE `supplier_details` ADD `gstin` VARCHAR(50) NULL DEFAULT NULL AFTER `supplier_name`;
-- Thana 07-May-2025
ALTER TABLE `paid_details` ADD `client_id` INT NULL DEFAULT NULL AFTER `paid_id`;
ALTER TABLE `paid_details` CHANGE `bill_id` `bill_id` INT NULL DEFAULT NULL;
ALTER TABLE `bill_details` ADD `client_id` INT NULL DEFAULT NULL AFTER `invoice_due_date`;
-- Thana 25-Jul-2025
ALTER TABLE `quotations` ADD `additional_charges_reason` VARCHAR(256) NULL DEFAULT NULL AFTER `grand_total`, ADD `additional_charges` INT NULL DEFAULT NULL AFTER `additional_charges_reason`;
