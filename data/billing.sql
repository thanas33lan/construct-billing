-- phpMyAdmin SQL Dump
-- version 4.9.5deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 13, 2020 at 01:24 PM
-- Server version: 8.0.20-0ubuntu0.19.10.1
-- PHP Version: 7.3.11-0ubuntu0.19.10.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ardesignertiles`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent_details`
--

CREATE TABLE `agent_details` (
  `agent_id` int NOT NULL,
  `agent_name` varchar(255) DEFAULT NULL,
  `agent_phone` varchar(255) DEFAULT NULL,
  `alter_phone_number` varchar(255) DEFAULT NULL,
  `agent_email` varchar(255) DEFAULT NULL,
  `agent_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agent_details`
--

INSERT INTO `agent_details` (`agent_id`, `agent_name`, `agent_phone`, `alter_phone_number`, `agent_email`, `agent_status`) VALUES
(1, 'AR Designer Tiles,pavers and Hollow Blocks  ', '9488944040', '9488954040', 'ardesignertiles@gmail.com', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `bill_details`
--

CREATE TABLE `bill_details` (
  `bill_id` int NOT NULL,
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
  `bill_added_by` int NOT NULL,
  `bill_added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bill_details`
--

INSERT INTO `bill_details` (`bill_id`, `invoice_no`, `invoice_date`, `invoice_due_date`, `client_name`, `billing_address`, `shipping_address`, `delivery_note`, `term_payment`, `supplier_ref`, `other_ref`, `buyer_order_no`, `buyer_date`, `dispatch_doc_no`, `delivery_note_date`, `dispatch_through`, `destination`, `term_delivery`, `total_amount`, `paid_amount`, `bill_status`, `bill_added_by`, `bill_added_on`) VALUES
(1, 'ARD0001/2020-21', '2020-04-24', NULL, 'MINERVA PUBLIC SCHOOL ', '5/112 MANGULAM \r\nARUPPUKOTTAI \r\nVIRUDHUNAGAR DISTRICT ', '5/112 MANGULAM \r\nARUPPUKOTTAI \r\nVIRUDHUNAGAR DISTRICT ', '', '', '', '', 'ORDER0001', '', 'DOCX0001', '', '', '', '', '399.00', 399, 'pending', 1, '2020-04-24 11:03:38'),
(2, 'ARD0002/2020-21', '2020-04-24', '2020-04-30', 'MINERVA PUBLIC SCHOOL ', '5/112 MANGULAM \r\nARUPPUKOTTAI \r\nVIRUDHUNAGAR DISTRICT ', '5/112 MANGULAM \r\nARUPPUKOTTAI \r\nVIRUDHUNAGAR DISTRICT ', 'fine', 'This is the term for payment', 'ref45878', 'serial4578', 'ORDER0002', '25-Apr-2020', 'DOCX0002', '27-Apr-2020', 'Container', 'Surandai', 'Term of delivery is a model for creating', '293.00', 293, 'pending', 1, '2020-04-24 16:38:04'),
(3, 'ARD0003/2020-21', '2020-05-08', '2013-04-19', 'Arun Enterprises ', 'Tenkasi', 'Tenkasi', 'Quisquam itaque quas', 'Cillum et obcaecati ', 'Sed sint laudantium', 'Aspernatur velit vel', 'Et a consequatur cum', '', 'Dolor aperiam except', '', 'Exercitation et quo ', 'Voluptates et numqua', 'Nam itaque ad eum hi', '76995.00', 9990, 'pending', 1, '2020-05-08 19:43:16'),
(4, 'TSM0004/2020-21', '2020-06-30', '1971-06-12', 'Arun Enterprises ', 'Tenkasi', 'SVKARAI', 'Irure corporis qui v', 'Eos quod sint tenet', 'Iusto exercitationem', 'Eiusmod quidem ut ea', 'Aut nobis veritatis ', '', 'Aperiam in excepturi', '', 'Rerum mollit quaerat', 'Omnis est cupidatat', 'Veniam nostrud aspe', '218.00', 200, 'pending', 1, '2020-06-30 21:27:29');

-- --------------------------------------------------------

--
-- Table structure for table `bill_product_details`
--

CREATE TABLE `bill_product_details` (
  `bill_p_id` int NOT NULL,
  `bill_id` int NOT NULL,
  `product_name` int NOT NULL,
  `sqft` varchar(50) DEFAULT NULL,
  `hsn_code` varchar(255) DEFAULT NULL,
  `sold_qty` int NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `tax` varchar(255) DEFAULT NULL,
  `cgst_rate` decimal(10,2) DEFAULT NULL,
  `cgst_amount` decimal(10,2) DEFAULT NULL,
  `sgst_rate` decimal(10,2) DEFAULT NULL,
  `sgst_amount` decimal(10,2) DEFAULT NULL,
  `igst_rate` decimal(10,2) DEFAULT NULL,
  `igst_amount` decimal(10,2) DEFAULT NULL,
  `discount` int DEFAULT NULL,
  `net_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bill_product_details`
--

INSERT INTO `bill_product_details` (`bill_p_id`, `bill_id`, `product_name`, `sqft`, `hsn_code`, `sold_qty`, `rate`, `tax`, `cgst_rate`, `cgst_amount`, `sgst_rate`, `sgst_amount`, `igst_rate`, `igst_amount`, `discount`, `net_amount`) VALUES
(1, 1, 2, NULL, NULL, 1, '98.00', '18', '9.00', '8.82', '9.00', '8.82', NULL, NULL, NULL, '115.64'),
(2, 1, 3, NULL, NULL, 1, '90.00', '18', '9.00', '8.10', '9.00', '8.10', NULL, NULL, NULL, '106.20'),
(3, 1, 1, NULL, NULL, 1, '65.00', '18', '9.00', '5.85', '9.00', '5.85', NULL, NULL, NULL, '76.70'),
(4, 1, 6, NULL, NULL, 1, '85.00', '18', '9.00', '7.65', '9.00', '7.65', NULL, NULL, NULL, '100.30'),
(5, 2, 2, '1400', '', 1, '98.00', '18', '9.00', '8.82', '9.00', '8.82', NULL, NULL, 5, '109.86'),
(6, 2, 3, '1500', '', 1, '90.00', '18', NULL, NULL, NULL, NULL, '18.00', '16.20', NULL, '106.20'),
(7, 2, 1, '1300', '', 1, '65.00', '18', '9.00', '5.85', '9.00', '5.85', NULL, NULL, NULL, '76.70'),
(8, 3, 1, '1300', '', 350, '65.00', '18', '9.00', '2047.50', '9.00', '2047.50', NULL, NULL, NULL, '26845.00'),
(9, 3, 6, '2000', '', 500, '85.00', '18', '9.00', '3825.00', '9.00', '3825.00', NULL, NULL, NULL, '50150.00'),
(10, 4, 2, '20', '', 1, '100.00', '18', NULL, NULL, NULL, NULL, '18.00', '18.00', NULL, '118.00'),
(11, 4, 6, '05', '', 1, '85.00', '18', '9.00', '7.65', '9.00', '7.65', NULL, NULL, NULL, '100.30');

-- --------------------------------------------------------

--
-- Table structure for table `client_details`
--

CREATE TABLE `client_details` (
  `client_id` int NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_mobile_no` varchar(255) DEFAULT NULL,
  `alter_phone_number` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `client_email_id` varchar(255) DEFAULT NULL,
  `client_address` mediumtext,
  `client_shipping_address` mediumtext,
  `client_status` varchar(255) DEFAULT NULL,
  `client_added_by` int NOT NULL,
  `client_added_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_details`
--

INSERT INTO `client_details` (`client_id`, `client_name`, `client_mobile_no`, `alter_phone_number`, `gst_no`, `client_email_id`, `client_address`, `client_shipping_address`, `client_status`, `client_added_by`, `client_added_on`) VALUES
(1, 'cambridge school', NULL, NULL, NULL, NULL, 'agasthiyarpatti\r\nambasamuthiram', NULL, 'active', 2, '2019-11-17 01:51:22'),
(2, 'Arun Enterprises ', NULL, NULL, NULL, NULL, 'Tenkasi', NULL, 'active', 2, '2019-12-16 03:42:34'),
(3, 'MINERVA PUBLIC SCHOOL ', NULL, NULL, NULL, NULL, '5/112 MANGULAM \r\nARUPPUKOTTAI \r\nVIRUDHUNAGAR DISTRICT ', NULL, 'active', 2, '2019-12-28 12:28:04'),
(4, 'wonder school ', NULL, NULL, NULL, NULL, 'Ambai to tenkasi road\r\nThiraviyanagar', NULL, 'active', 2, '2020-01-11 16:59:41'),
(5, 'Rajesh', NULL, NULL, NULL, NULL, 'SVkarai', NULL, 'active', 1, '2020-05-08 19:36:07');

-- --------------------------------------------------------

--
-- Table structure for table `company_profile`
--

CREATE TABLE `company_profile` (
  `company_id` int NOT NULL,
  `company_code` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `lat` varchar(50) DEFAULT NULL,
  `lng` varchar(50) DEFAULT NULL,
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

INSERT INTO `company_profile` (`company_id`, `company_code`, `company_name`, `company_email`, `website`, `lat`, `lng`, `company_phone`, `alt_number`, `company_alter_phone`, `company_logo`, `gst_number`, `address_line_one`, `address_line_two`, `accounter_name`, `accounte_branch`, `accounte_no`, `accounte_ifsc`, `declaration`) VALUES
(1, 'TSM', 'Tiles Shop', 'contact@tilesshop.com', 'www.tilesshop.com', '10.0258368', '75.4738309', '9512368740', '9874563210', NULL, 'logo.png', 'XXXXXXXXXXXXX', 'Tiles shop,\r\n', 'North street, Chennai, Tamilnadu - 600008', 'XXXXXX', 'Tenkasi', '55236674119821', 'XXXX0257989', 'Here is the declaration of the text loaded to the invoice terms and conditions.');

-- --------------------------------------------------------

--
-- Table structure for table `expense_details`
--

CREATE TABLE `expense_details` (
  `expense_id` int NOT NULL,
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
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `option_name` varchar(255) DEFAULT NULL,
  `option_value` varchar(255) DEFAULT NULL,
  `option_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `product_id`, `option_name`, `option_value`, `option_status`) VALUES
(2, 8, 'Model', 'XEW3H', 'active'),
(3, 8, 'Color', 'Gray', 'active'),
(5, 8, 'Sunlight', 'Yes', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `paid_details`
--

CREATE TABLE `paid_details` (
  `paid_id` int NOT NULL,
  `bill_id` int NOT NULL,
  `paid_on` date DEFAULT NULL,
  `pay_option` varchar(255) DEFAULT NULL,
  `pay_details` text,
  `paid_amount` float DEFAULT NULL,
  `agent_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paid_details`
--

INSERT INTO `paid_details` (`paid_id`, `bill_id`, `paid_on`, `pay_option`, `pay_details`, `paid_amount`, `agent_id`) VALUES
(1, 1, '2020-04-24', 'cash', '', 399, NULL),
(2, 2, '2020-04-24', 'cash', '', 293, NULL),
(6, 3, '2020-05-08', 'cash', 'Aute ea laboriosam ', 6995, NULL),
(7, 3, '2020-05-09', 'cash', 'buy details manual', 2000, NULL),
(8, 3, '2020-05-10', 'cash', 'Some of the details', 995, NULL),
(9, 4, '2020-06-30', 'cash', 'Laudantium suscipit', 200, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_details`
--

CREATE TABLE `product_details` (
  `product_id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `hsn_code` varchar(255) DEFAULT NULL,
  `qty_available` varchar(255) DEFAULT NULL,
  `minimum_qty` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,0) NOT NULL,
  `product_tax` decimal(10,0) DEFAULT NULL,
  `options` text,
  `product_status` varchar(255) DEFAULT NULL,
  `last_paid` datetime DEFAULT NULL,
  `product_added_on` datetime DEFAULT NULL,
  `product_added_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_details`
--

INSERT INTO `product_details` (`product_id`, `supplier_id`, `product_name`, `product_description`, `hsn_code`, `qty_available`, `minimum_qty`, `product_price`, `product_tax`, `options`, `product_status`, `last_paid`, `product_added_on`, `product_added_by`) VALUES
(1, 1, 'jelly and side packing', 'cement paving block including paving full work ( using 1/4 inch jelly and side packing of block) First quality brick(using waterproof not using color polish)including loading and unloading and transport and laying charge', 'HSN0001', '1007', '100', '65', '18', NULL, 'active', NULL, '2019-11-17 01:48:55', 2),
(2, 1, 'mix and  roller', 'cement paving block including wet mix and  roller work and paving work', 'HSN7016', '2025', '100', '98', '18', NULL, 'active', NULL, '2019-11-17 02:06:50', 2),
(3, 1, 'Pavers', 'This is the sample pavers', 'HSNAR4578', '6', '10', '90', '18', NULL, 'active', NULL, '2020-04-22 09:41:28', 1),
(6, 1, 'Cement ceramics', 'This is the cement ceramics', 'HSN4578', '6', '10', '85', '18', NULL, 'active', NULL, '2020-04-24 11:03:39', 1),
(7, 1, 'Ceramic 345', 'This is the sample description for ceramic 345', 'TSM0007', NULL, '10', '215', '18', NULL, 'active', NULL, '2020-07-01 09:17:23', 1),
(8, 1, 'Hellow Bloks', 'This is the latest hellow blocks', 'TSM0008', '220', '100', '120', '18', NULL, 'active', NULL, '2020-07-12 20:31:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE `purchase_details` (
  `purchase_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `purchase_no` varchar(255) NOT NULL,
  `purchase_on` date DEFAULT NULL,
  `purchase_amount` float DEFAULT NULL,
  `added_by` int DEFAULT NULL,
  `added_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_details`
--

INSERT INTO `purchase_details` (`purchase_id`, `supplier_id`, `purchase_no`, `purchase_on`, `purchase_amount`, `added_by`, `added_on`) VALUES
(1, 1, 'PUR0001', '2020-04-16', 800, 1, '2020-04-24 16:54:29'),
(2, 2, 'PUR0002', '2020-04-15', 2960, 1, '2020-04-24 16:59:19'),
(3, 1, 'PUR0003', '2020-07-13', 14400, 1, '2020-07-13 13:14:44');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_product_details`
--

CREATE TABLE `purchase_product_details` (
  `purchase_sub_id` int NOT NULL,
  `purchase_id` int NOT NULL,
  `product_id` int NOT NULL,
  `purchase_qty` int NOT NULL,
  `purchase_prd_amount` float DEFAULT NULL,
  `purchase_line_total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_product_details`
--

INSERT INTO `purchase_product_details` (`purchase_sub_id`, `purchase_id`, `product_id`, `purchase_qty`, `purchase_prd_amount`, `purchase_line_total`) VALUES
(3, 1, 1, 2, 65, 130),
(4, 1, 3, 2, 90, 180),
(5, 1, 2, 5, 98, 490),
(9, 2, 1, 5, 60, 300),
(10, 2, 3, 4, 95, 380),
(11, 2, 6, 6, 80, 480),
(12, 2, 2, 20, 90, 1800),
(13, 3, 8, 120, 120, 14400);

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `q_id` int NOT NULL,
  `q_customer` int DEFAULT NULL,
  `q_code` varchar(255) DEFAULT NULL,
  `enquiry_date` date DEFAULT NULL,
  `q_date` date DEFAULT NULL,
  `grand_total` varchar(255) DEFAULT NULL,
  `q_added_by` int DEFAULT NULL,
  `q_added_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`q_id`, `q_customer`, `q_code`, `enquiry_date`, `q_date`, `grand_total`, `q_added_by`, `q_added_on`) VALUES
(1, 1, 'ARD/QTN0001/2019-20', '2019-11-17', '2019-11-17', '65.00', 2, '2019-11-17 01:51:22'),
(2, 1, 'ARD/QTN0002/2019-20', '0000-00-00', '2019-11-17', '98.00', 2, '2019-11-17 02:26:26'),
(4, 2, 'ARD/QTN0003/2019-20', '0000-00-00', '2019-12-16', '54000.00', 2, '2019-12-16 03:44:19'),
(5, 2, 'ARD/QTN0005/2019-20', '2019-12-16', '2019-12-16', '21000.00', 2, '2019-12-16 03:47:25'),
(7, 4, 'ARD/QTN0006/2019-20', '0000-00-00', '2020-01-11', '65.00', 2, '2020-01-11 17:03:50'),
(8, 4, 'ARD/QTN0008/2019-20', '0000-00-00', '2020-01-12', '65.00', 2, '2020-01-12 14:32:33'),
(9, 1, 'ARD/QTN0009/2020-21', '2020-04-21', '2020-04-22', '353.00', NULL, '2020-04-22 09:40:16'),
(10, 1, 'ARD/QTN0010/2020-21', '2020-04-21', '2020-04-22', '441.00', 1, '2020-04-22 09:41:28'),
(11, 5, 'ARD/QTN0011/2020-21', '2020-05-08', '2020-05-08', '27760.00', 1, '2020-05-08 19:36:08');

-- --------------------------------------------------------

--
-- Table structure for table `quotations_products_map`
--

CREATE TABLE `quotations_products_map` (
  `qpm_id` int NOT NULL,
  `q_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `sqft` varchar(255) DEFAULT NULL,
  `p_price` int DEFAULT NULL,
  `p_qty` varchar(255) DEFAULT NULL,
  `discount` varchar(255) DEFAULT NULL,
  `line_total` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotations_products_map`
--

INSERT INTO `quotations_products_map` (`qpm_id`, `q_id`, `product_id`, `sqft`, `p_price`, `p_qty`, `discount`, `line_total`) VALUES
(1, 1, 1, NULL, 65, '1sq ft', NULL, '65'),
(2, 2, 2, NULL, 98, '1', NULL, '98'),
(4, 4, 1, NULL, 60, '900sqft ', NULL, '54000'),
(5, 5, 1, NULL, 60, '350', NULL, '21000'),
(7, 7, 1, '', 65, '1', NULL, '65'),
(8, 8, 1, '', 65, '1', NULL, '65'),
(9, 9, 1, '1100', 65, '1', NULL, '65'),
(10, 9, 2, '1300', 98, '1', NULL, '98'),
(11, 9, 10, '1400', 95, '2', NULL, '190'),
(12, 10, 1, '1600', 65, '1', NULL, '65'),
(13, 10, 3, '1200', 90, '2', NULL, '180'),
(14, 10, 2, '1400', 98, '2', NULL, '196'),
(15, 11, 1, '700', 65, '50', NULL, '3250'),
(16, 11, 2, '900', 98, '120', NULL, '11760'),
(17, 11, 6, '1200', 85, '150', NULL, '12750');

-- --------------------------------------------------------

--
-- Table structure for table `stock_details`
--

CREATE TABLE `stock_details` (
  `stock_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `actual_qty` varchar(255) DEFAULT NULL,
  `minimum_qty` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `actual_price` varchar(255) DEFAULT NULL,
  `stock_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_details`
--

INSERT INTO `stock_details` (`stock_id`, `product_id`, `actual_qty`, `minimum_qty`, `quantity`, `actual_price`, `stock_status`) VALUES
(1, 1, '1007', '100', '350', '60', 'active'),
(2, 2, '2025', '100', '1', '90', 'active'),
(3, 3, '6', '10', NULL, '90', 'active'),
(4, 6, '6', '10', '1', '85', 'active'),
(7, 7, NULL, '10', NULL, '215', 'active'),
(8, 8, '120', '100', NULL, '120', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_details`
--

CREATE TABLE `supplier_details` (
  `supplier_id` int NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `supplier_address` text,
  `supplier_email` varchar(255) DEFAULT NULL,
  `supplier_phone` varchar(255) DEFAULT NULL,
  `alter_phone_number` varchar(255) DEFAULT NULL,
  `supplier_status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier_details`
--

INSERT INTO `supplier_details` (`supplier_id`, `supplier_name`, `supplier_address`, `supplier_email`, `supplier_phone`, `alter_phone_number`, `supplier_status`) VALUES
(1, 'AR Designer and Tiles ', 'Avudaikan Nagar\r\nRailway gate - Meelapavoor road Kurumbalaperi, Tenkasi Tirunelveli - 627806', 'ardesignertiles@gmail.com', '94488944040', '94422 76244', 'active'),
(2, 'AK Stone', 'Sokkampatti, Madurai', 'akstone@gmail.com', '9512368740', '9514872630', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_id` int NOT NULL,
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
(1, 'tile-admin', 'tile-admin', '976667565cdc52238ced70661b757bc4dee172ab', '9944514911', 'active', '2018-11-18 06:00:00'),
(2, 'Anbarasan', 'anbu', '340257a7b31f401b2174e8ed51bf87385d8a6d16', '9442276244', 'active', NULL);

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
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `product_name` (`product_name`);

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
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `agent_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bill_details`
--
ALTER TABLE `bill_details`
  MODIFY `bill_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bill_product_details`
--
ALTER TABLE `bill_product_details`
  MODIFY `bill_p_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `client_details`
--
ALTER TABLE `client_details`
  MODIFY `client_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `company_profile`
--
ALTER TABLE `company_profile`
  MODIFY `company_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expense_details`
--
ALTER TABLE `expense_details`
  MODIFY `expense_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `paid_details`
--
ALTER TABLE `paid_details`
  MODIFY `paid_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_details`
--
ALTER TABLE `product_details`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `purchase_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_product_details`
--
ALTER TABLE `purchase_product_details`
  MODIFY `purchase_sub_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `q_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `quotations_products_map`
--
ALTER TABLE `quotations_products_map`
  MODIFY `qpm_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `stock_details`
--
ALTER TABLE `stock_details`
  MODIFY `stock_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `supplier_details`
--
ALTER TABLE `supplier_details`
  MODIFY `supplier_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill_product_details`
--
ALTER TABLE `bill_product_details`
  ADD CONSTRAINT `bill_product_details_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bill_details` (`bill_id`),
  ADD CONSTRAINT `bill_product_details_ibfk_2` FOREIGN KEY (`product_name`) REFERENCES `product_details` (`product_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
