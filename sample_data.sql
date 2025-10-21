-- Sample data for Water Refilling Station Management System
USE `water_refilling_db`;

-- Insert sample admin user
INSERT INTO `admins` (`username`, `password`, `full_name`, `email`, `status`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@waterstation.com', 'active');
-- Password: password

-- Insert sample products
INSERT INTO `products` (`name`, `description`, `category`, `unit_price`, `status`) VALUES
('5 Gallon Water Jug', 'Purified drinking water in 5-gallon container', 'Water', 25.00, 'active'),
('1 Gallon Water Bottle', 'Purified drinking water in 1-gallon bottle', 'Water', 8.00, 'active'),
('500ml Water Bottle', 'Purified drinking water in 500ml bottle', 'Water', 3.00, 'active'),
('Water Dispenser Rental', 'Monthly rental for water dispenser', 'Equipment', 150.00, 'active'),
('Delivery Service', 'Home delivery service fee', 'Service', 20.00, 'active');

-- Insert inventory records
INSERT INTO `inventory` (`product_id`, `current_stock`, `minimum_stock`) VALUES
(1, 100, 20),
(2, 50, 10),
(3, 200, 50),
(4, 15, 5),
(5, 999, 1);

-- Insert sample customers
INSERT INTO `customers` (`name`, `phone`, `address`, `email`, `status`) VALUES
('Juan Dela Cruz', '09123456789', '123 Main St, Quezon City', 'juan@email.com', 'active'),
('Maria Santos', '09987654321', '456 Oak Ave, Makati City', 'maria@email.com', 'active'),
('Pedro Garcia', '09111222333', '789 Pine Rd, Pasig City', 'pedro@email.com', 'active'),
('Ana Reyes', '09444555666', '321 Elm St, Mandaluyong City', 'ana@email.com', 'active'),
('Carlos Lopez', '09777888999', '654 Maple Dr, Taguig City', 'carlos@email.com', 'active');

-- Insert sample settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES
('company_name', 'Crystal Clear Water Station', 'Company name'),
('company_address', '123 Business St, Metro Manila', 'Company address'),
('company_phone', '(02) 123-4567', 'Company phone number'),
('company_email', 'info@crystalclear.com', 'Company email'),
('tax_rate', '12', 'Tax rate percentage'),
('delivery_fee', '20', 'Standard delivery fee'),
('minimum_order', '100', 'Minimum order amount'),
('business_hours', '8:00 AM - 6:00 PM', 'Business operating hours');

-- Insert sample orders
INSERT INTO `orders` (`order_id`, `customer_id`, `total_amount`, `status`, `delivery_address`, `delivery_date`, `notes`) VALUES
('ORD-20241021-001', 1, 75.00, 'delivered', '123 Main St, Quezon City', '2024-10-21', 'Regular customer'),
('ORD-20241021-002', 2, 41.00, 'processing', '456 Oak Ave, Makati City', '2024-10-22', 'First time customer'),
('ORD-20241021-003', 3, 170.00, 'pending', '789 Pine Rd, Pasig City', '2024-10-23', 'Monthly subscription');

-- Insert sample order items
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 2, 25.00, 50.00),
(1, 5, 1, 20.00, 20.00),
(1, 3, 1, 3.00, 3.00),
(2, 2, 3, 8.00, 24.00),
(2, 5, 1, 20.00, 20.00),
(3, 4, 1, 150.00, 150.00),
(3, 5, 1, 20.00, 20.00);

-- Insert sample payments
INSERT INTO `payments` (`order_id`, `amount`, `payment_method`, `reference_number`, `status`, `verified_by`, `verified_at`) VALUES
(1, 75.00, 'cash', NULL, 'verified', 1, '2024-10-21 10:30:00'),
(2, 41.00, 'gcash', 'GC123456789', 'pending', NULL, NULL);

-- Insert sample stock movements
INSERT INTO `stock_movements` (`product_id`, `movement_type`, `quantity`, `reference_type`, `reference_id`, `notes`, `created_by`) VALUES
(1, 'out', 2, 'sale', 1, 'Order ORD-20241021-001', 1),
(3, 'out', 1, 'sale', 1, 'Order ORD-20241021-001', 1),
(2, 'out', 3, 'sale', 2, 'Order ORD-20241021-002', 1),
(1, 'in', 50, 'purchase', NULL, 'Weekly stock replenishment', 1),
(2, 'in', 30, 'purchase', NULL, 'Weekly stock replenishment', 1);
