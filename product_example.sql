CREATE DATABASE assignment1;
use assignment1;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(10) unsigned DEFAULT NULL,
  `product_name` varchar(20) DEFAULT NULL,
  `unit_price` float(8,2) DEFAULT NULL,
  `unit_quantity` varchar(15) DEFAULT NULL,
  `in_stock` int(10) unsigned DEFAULT NULL
);

-- Original Records of products
INSERT INTO `products` VALUES (1000, 'Fish Fingers', 2.55, '500 gram', 1500);
INSERT INTO `products` VALUES (1001, 'Fish Fingers', 5.00, '1000 gram', 750);
INSERT INTO `products` VALUES (1002, 'Hamburger Patties', 2.35, 'Pack 10', 1200);
INSERT INTO `products` VALUES (1003, 'Shelled Prawns', 6.90, '250 gram', 300);
INSERT INTO `products` VALUES (1004, 'Tub Ice Cream', 1.80, 'I Litre', 800);
INSERT INTO `products` VALUES (1005, 'Tub Ice Cream', 3.40, '2 Litre', 1200);
INSERT INTO `products` VALUES (2002, 'Bath Soap', 2.60, 'Pack 6', 500);
INSERT INTO `products` VALUES (2003, 'Garbage Bags Small', 1.50, 'Pack 10', 500);
INSERT INTO `products` VALUES (2004, 'Garbage Bags Large', 5.00, 'Pack 50', 300);
INSERT INTO `products` VALUES (2005, 'Washing Powder', 4.00, '1000 gram', 800);
INSERT INTO `products` VALUES (2006, 'Laundry Bleach', 3.55, '2 Litre Bottle', 500);
INSERT INTO `products` VALUES (3000, 'Cheddar Cheese', 8.00, '500 gram', 1000);
INSERT INTO `products` VALUES (3001, 'Cheddar Cheese', 15.00, '1000 gram', 1000);
INSERT INTO `products` VALUES (3002, 'T Bone Steak', 7.00, '1000 gram', 200);
INSERT INTO `products` VALUES (3003, 'Navel Oranges', 3.99, 'Bag 20', 200);
INSERT INTO `products` VALUES (3004, 'Bananas', 1.49, 'Kilo', 400);
INSERT INTO `products` VALUES (3005, 'Peaches', 2.99, 'Kilo', 500);
INSERT INTO `products` VALUES (3006, 'Grapes', 3.50, 'Kilo', 200);
INSERT INTO `products` VALUES (3007, 'Apples', 1.99, 'Kilo', 500);
INSERT INTO `products` VALUES (4000, 'Earl Grey Tea Bags', 2.49, 'Pack 25', 1200);
INSERT INTO `products` VALUES (4001, 'Earl Grey Tea Bags', 7.25, 'Pack 100', 1200);
INSERT INTO `products` VALUES (4002, 'Earl Grey Tea Bags', 13.00, 'Pack 200', 800);
INSERT INTO `products` VALUES (4003, 'Instant Coffee', 2.89, '200 gram', 500);
INSERT INTO `products` VALUES (4004, 'Instant Coffee', 5.10, '500 gram', 500);
INSERT INTO `products` VALUES (4005, 'Chocolate Bar', 2.50, '500 gram', 300);
INSERT INTO `products` VALUES (5000, 'Dry Dog Food', 5.95, '5 kg Pack', 400);
INSERT INTO `products` VALUES (5001, 'Dry Dog Food', 1.95, '1 kg Pack', 400);
INSERT INTO `products` VALUES (5002, 'Bird Food', 3.99, '500g packet', 200);
INSERT INTO `products` VALUES (5003, 'Cat Food', 2.00, '500g tin', 200);
INSERT INTO `products` VALUES (5004, 'Fish Food', 3.00, '500g packet', 200);

-- New products - Medicine category (moved Panadol here)
INSERT INTO `products` VALUES (6000, 'Panadol', 3.00, 'Pack 24', 2000);
INSERT INTO `products` VALUES (6001, 'Panadol', 5.50, 'Bottle 50', 1000);
INSERT INTO `products` VALUES (6002, 'Aspirin', 2.99, 'Pack 20', 1500);
INSERT INTO `products` VALUES (6003, 'Ibuprofen', 4.25, 'Pack 16', 800);
INSERT INTO `products` VALUES (6004, 'Cough Syrup', 7.50, '250ml', 0);
INSERT INTO `products` VALUES (6005, 'Band-Aids', 3.45, 'Pack 50', 1200);
INSERT INTO `products` VALUES (6006, 'Antiseptic Cream', 5.99, '50g tube', 300);
INSERT INTO `products` VALUES (6007, 'Vitamin C', 8.99, 'Bottle 100', 0);

-- New additional products for existing categories
-- Frozen (1000 range)
INSERT INTO `products` VALUES (1006, 'Frozen Pizza', 6.99, 'Single', 850);
INSERT INTO `products` VALUES (1007, 'Frozen Vegetables', 3.29, '500g bag', 0);

-- Home (2000 range)
INSERT INTO `products` VALUES (2007, 'Dish Soap', 2.75, '500ml', 650);
INSERT INTO `products` VALUES (2008, 'Air Freshener', 3.99, 'Single', 0);
INSERT INTO `products` VALUES (2009, 'Sponges', 2.49, 'Pack 3', 420);

-- Fresh (3000 range)
INSERT INTO `products` VALUES (3008, 'Strawberries', 4.99, 'Punnet', 150);
INSERT INTO `products` VALUES (3009, 'Fresh Milk', 2.50, '1 Litre', 0);

-- Beverages (4000 range)
INSERT INTO `products` VALUES (4006, 'Orange Juice', 3.99, '1 Litre', 400);
INSERT INTO `products` VALUES (4007, 'Sparkling Water', 1.75, '750ml', 0);

-- Pet-Food (5000 range)
INSERT INTO `products` VALUES (5005, 'Premium Cat Food', 7.50, '1kg bag', 200);
INSERT INTO `products` VALUES (5006, 'Pet Treats', 4.25, '250g bag', 0);