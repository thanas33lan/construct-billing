-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 22, 2019 at 06:29 PM
-- Server version: 5.7.26-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billing`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent_details`
--

CREATE TABLE `agent_details` (
  `agent_id` int(11) NOT NULL,
  `agent_name` varchar(255) DEFAULT NULL,
  `agent_phone` varchar(255) DEFAULT NULL,
  `alter_phone_number` varchar(255) DEFAULT NULL,
  `agent_email` varchar(255) DEFAULT NULL,
  `agent_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_details`
--

CREATE TABLE `bill_details` (
  `bill_id` int(11) NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_due_date` date DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `billing_address` mediumtext,
  `shipping_address` mediumtext,
  `delivery_note` varchar(255) DEFAULT NULL,
  `term_payment` varchar(255) DEFAULT NULL,
  `supplier_ref` varchar(255) DEFAULT NULL,
  `other_ref` varchar(255) DEFAULT NULL,
  `buyer_order_no` varchar(255) DEFAULT NULL,
  `buyer_date` varchar(255) DEFAULT NULL,
  `dispatch_doc_no` varchar(255) DEFAULT NULL,
  `delivery_note_date` varchar(255) DEFAULT NULL,
  `dispatch_through` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `term_delivery` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `paid_amount` float DEFAULT NULL,
  `bill_status` varchar(255) DEFAULT NULL,
  `bill_added_by` int(255) NOT NULL,
  `bill_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_product_details`
--

CREATE TABLE `bill_product_details` (
  `bill_p_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `hsn_code` varchar(255) DEFAULT NULL,
  `sold_qty` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `tax` varchar(255) DEFAULT NULL,
  `cgst_rate` decimal(10,2) DEFAULT NULL,
  `cgst_amount` decimal(10,2) DEFAULT NULL,
  `sgst_rate` decimal(10,2) DEFAULT NULL,
  `sgst_amount` decimal(10,2) DEFAULT NULL,
  `igst_rate` decimal(10,2) DEFAULT NULL,
  `igst_amount` decimal(10,2) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `net_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_details`
--

CREATE TABLE `client_details` (
  `client_id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_mobile_no` varchar(255) DEFAULT NULL,
  `alter_phone_number` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `client_email_id` varchar(255) DEFAULT NULL,
  `client_address` mediumtext,
  `client_shipping_address` mediumtext,
  `client_status` varchar(255) DEFAULT NULL,
  `client_added_by` int(11) NOT NULL,
  `client_added_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `company_profile`
--

CREATE TABLE `company_profile` (
  `company_id` int(11) NOT NULL,
  `company_code` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_phone` varchar(255) DEFAULT NULL,
  `alt_number` varchar(255) DEFAULT NULL,
  `company_alter_phone` varchar(255) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `address_line_one` mediumtext,
  `address_line_two` mediumtext,
  `accounter_name` varchar(255) DEFAULT NULL,
  `accounte_branch` varchar(255) DEFAULT NULL,
  `accounte_no` varchar(255) DEFAULT NULL,
  `accounte_ifsc` varchar(255) DEFAULT NULL,
  `declaration` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_profile`
--

INSERT INTO `company_profile` (`company_id`, `company_code`, `company_name`, `company_email`, `company_phone`, `alt_number`, `company_alter_phone`, `company_logo`, `gst_number`, `address_line_one`, `address_line_two`, `accounter_name`, `accounte_branch`, `accounte_no`, `accounte_ifsc`, `declaration`) VALUES
(1, 'SAM', 'Samwin Infotech', 'info@samwintech.com', '987678678', '9632587410', NULL, 'logo.png', '86578567856', '14 Tottenham Court Road,london,', 'England', 'Samwin InfoTech', 'SBI Surandai', '12354712589601456', 'SBI10001165', 'Here is the declaration of the text loaded to the invoice terms and conditions.');

-- --------------------------------------------------------

--
-- Table structure for table `expense_details`
--

CREATE TABLE `expense_details` (
  `expense_id` int(11) NOT NULL,
  `expense_date` date DEFAULT NULL,
  `particulars` varchar(255) DEFAULT NULL,
  `purchased_from` varchar(255) DEFAULT NULL,
  `purchased_by` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `remarks` text,
  `payment_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `paid_details`
--

CREATE TABLE `paid_details` (
  `paid_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `paid_on` date DEFAULT NULL,
  `pay_option` varchar(255) DEFAULT NULL,
  `pay_details` text,
  `paid_amount` float DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `hsn_code` varchar(255) DEFAULT NULL,
  `qty_available` varchar(255) DEFAULT NULL,
  `minimum_qty` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,0) NOT NULL,
  `product_tax` decimal(10,0) DEFAULT NULL,
  `product_status` varchar(255) DEFAULT NULL,
  `last_paid` datetime DEFAULT NULL,
  `product_added_on` datetime DEFAULT NULL,
  `product_added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE `purchase_details` (
  `purchase_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `purchase_no` varchar(255) NOT NULL,
  `purchase_on` date DEFAULT NULL,
  `purchase_amount` float DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_product_details`
--

CREATE TABLE `purchase_product_details` (
  `purchase_sub_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_qty` int(11) NOT NULL,
  `purchase_prd_amount` float DEFAULT NULL,
  `purchase_line_total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `q_id` int(11) NOT NULL,
  `q_customer` int(11) DEFAULT NULL,
  `q_code` varchar(255) DEFAULT NULL,
  `enquiry_date` date DEFAULT NULL,
  `q_date` date DEFAULT NULL,
  `grand_total` varchar(255) DEFAULT NULL,
  `q_added_by` int(11) DEFAULT NULL,
  `q_added_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quotations_products_map`
--

CREATE TABLE `quotations_products_map` (
  `qpm_id` int(11) NOT NULL,
  `q_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `p_price` int(11) DEFAULT NULL,
  `p_qty` varchar(255) DEFAULT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `line_total` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_details`
--

CREATE TABLE `stock_details` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `actual_qty` varchar(255) DEFAULT NULL,
  `minimum_qty` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `actual_price` varchar(255) DEFAULT NULL,
  `stock_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_details`
--

CREATE TABLE `supplier_details` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `supplier_address` text,
  `supplier_email` varchar(255) DEFAULT NULL,
  `supplier_phone` varchar(255) DEFAULT NULL,
  `alter_phone_number` varchar(255) DEFAULT NULL,
  `supplier_status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `login_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_phone` varchar(255) DEFAULT NULL,
  `user_status` varchar(255) NOT NULL,
  `user_added_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_id`, `user_name`, `login_id`, `password`, `user_phone`, `user_status`, `user_added_on`) VALUES
(1, 'Merlin', 'merlin', '976667565cdc52238ced70661b757bc4dee172ab', '9944514911', 'active', '2018-11-18 06:00:00'),
(2, 'topaqa', 'user', '340257a7b31f401b2174e8ed51bf87385d8a6d16', '9994027557', 'active', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agent_details`
--
ALTER TABLE `agent_details`
  ADD PRIMARY KEY (`agent_id`);

--
-- Indexes for table `bill_details`
--
ALTER TABLE `bill_details`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `bill_added_by` (`bill_added_by`);

--
-- Indexes for table `bill_product_details`
--
ALTER TABLE `bill_product_details`
  ADD PRIMARY KEY (`bill_p_id`),
  ADD KEY `bill_id` (`bill_id`);

--
-- Indexes for table `client_details`
--
ALTER TABLE `client_details`
  ADD PRIMARY KEY (`client_id`),
  ADD KEY `client_added_by` (`client_added_by`);

--
-- Indexes for table `company_profile`
--
ALTER TABLE `company_profile`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `expense_details`
--
ALTER TABLE `expense_details`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `paid_details`
--
ALTER TABLE `paid_details`
  ADD PRIMARY KEY (`paid_id`);

--
-- Indexes for table `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_added_by` (`product_added_by`);

--
-- Indexes for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `purchase_product_details`
--
ALTER TABLE `purchase_product_details`
  ADD PRIMARY KEY (`purchase_sub_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`q_id`),
  ADD KEY `q_customer` (`q_customer`),
  ADD KEY `q_added_by` (`q_added_by`);

--
-- Indexes for table `quotations_products_map`
--
ALTER TABLE `quotations_products_map`
  ADD PRIMARY KEY (`qpm_id`);

--
-- Indexes for table `stock_details`
--
ALTER TABLE `stock_details`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `supplier_details`
--
ALTER TABLE `supplier_details`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent_details`
--
ALTER TABLE `agent_details`
  MODIFY `agent_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bill_details`
--
ALTER TABLE `bill_details`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bill_product_details`
--
ALTER TABLE `bill_product_details`
  MODIFY `bill_p_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `client_details`
--
ALTER TABLE `client_details`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `company_profile`
--
ALTER TABLE `company_profile`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `expense_details`
--
ALTER TABLE `expense_details`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `paid_details`
--
ALTER TABLE `paid_details`
  MODIFY `paid_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_product_details`
--
ALTER TABLE `purchase_product_details`
  MODIFY `purchase_sub_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quotations_products_map`
--
ALTER TABLE `quotations_products_map`
  MODIFY `qpm_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stock_details`
--
ALTER TABLE `stock_details`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplier_details`
--
ALTER TABLE `supplier_details`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill_product_details`
--
ALTER TABLE `bill_product_details`
  ADD CONSTRAINT `bill_product_details_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bill_details` (`bill_id`);

--
-- Constraints for table `client_details`
--
ALTER TABLE `client_details`
  ADD CONSTRAINT `client_details_ibfk_1` FOREIGN KEY (`client_added_by`) REFERENCES `user_details` (`user_id`);

--
-- Constraints for table `product_details`
--
ALTER TABLE `product_details`
  ADD CONSTRAINT `product_details_ibfk_1` FOREIGN KEY (`product_added_by`) REFERENCES `user_details` (`user_id`);

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`q_customer`) REFERENCES `client_details` (`client_id`),
  ADD CONSTRAINT `quotations_ibfk_2` FOREIGN KEY (`q_added_by`) REFERENCES `user_details` (`user_id`);

--
-- Constraints for table `stock_details`
--
ALTER TABLE `stock_details`
  ADD CONSTRAINT `stock_details_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product_details` (`product_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
