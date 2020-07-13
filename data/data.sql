-- Thanaseelan 25-Mar-2019 Added by purchase details
ALTER TABLE `purchase_details` ADD `added_by` INT(11) NULL DEFAULT NULL AFTER `purchase_amount`;