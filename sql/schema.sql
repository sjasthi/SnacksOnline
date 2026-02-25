-- SnacksOnline Database Schema
-- Run this in phpMyAdmin or MySQL after creating a database named: snacksonline

CREATE DATABASE IF NOT EXISTS snacksonline CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE snacksonline;

-- ── Users ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    user_id    INT          AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── Items ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS items (
    item_id     VARCHAR(20)  PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    description TEXT,
    price       DECIMAL(8,2) NOT NULL,
    image       VARCHAR(255) DEFAULT NULL,
    category    VARCHAR(80)  DEFAULT 'General',
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── Orders ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS orders (
    order_id         INT          AUTO_INCREMENT PRIMARY KEY,
    user_id          INT          DEFAULT NULL,
    customer_name    VARCHAR(100) NOT NULL,
    customer_email   VARCHAR(150) NOT NULL,
    delivery_address TEXT         NOT NULL,
    total_price      DECIMAL(8,2) NOT NULL,
    status           ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- ── Order Items ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT          AUTO_INCREMENT PRIMARY KEY,
    order_id      INT          NOT NULL,
    item_id       VARCHAR(20)  NOT NULL,
    quantity      INT          NOT NULL,
    unit_price    DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id)  REFERENCES items(item_id)
);

-- ── FAQs ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS faqs (
    faq_id     INT          AUTO_INCREMENT PRIMARY KEY,
    category   VARCHAR(80)  NOT NULL,
    question   VARCHAR(255) NOT NULL,
    answer     TEXT         NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── Seed Admin User (password: Admin1234!) ───────────────────
INSERT IGNORE INTO users (name, email, password, role)
VALUES ('Admin', 'admin@snacksonline.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Note: The hash above is for 'password' — change it!
-- Generate a real hash: php -r "echo password_hash('Admin1234!', PASSWORD_BCRYPT);"

-- ── Seed Demo Items ──────────────────────────────────────────
INSERT IGNORE INTO items (item_id, name, description, price, category) VALUES
('SNK-001', 'Spicy Plantain Chips',     'Crispy golden plantain chips in bold chilli-lime seasoning.',         3.50, 'Chips'),
('SNK-002', 'Honey Roasted Cashews',    'Premium cashews slow-roasted with wildflower honey and sea salt.',   5.99, 'Nuts'),
('SNK-003', 'Dark Chocolate Almonds',   'Belgian dark chocolate wrapped around whole California almonds.',    4.75, 'Chocolate'),
('SNK-004', 'Biltong Beef Strips',      'Air-dried spiced beef — high protein, zero sugar, bold flavour.',   6.50, 'Meat Snacks'),
('SNK-005', 'BBQ & Cheddar Popcorn Mix','Two classic flavours in one bag. Movie night essential.',           2.99, 'Popcorn');

-- ── Seed Demo FAQs ───────────────────────────────────────────
INSERT IGNORE INTO faqs (category, question, answer) VALUES
('Ordering',  'How do I place an order?',              'Browse our items, add them to your cart, and proceed to checkout. You can order as a guest or create an account.'),
('Ordering',  'Can I order without creating an account?', 'Yes! We support guest checkout. Just fill in your name, email, and delivery address at checkout.'),
('Payments',  'What payment methods do you accept?',   'We currently accept cash on delivery. Online payment is coming soon.'),
('Shipping',  'How long does delivery take?',          'Orders are dispatched within 24 hours and typically delivered within 3–5 business days.'),
('Shipping',  'Do you deliver internationally?',       'We currently deliver within Kenya. International shipping is planned for a future update.'),
('Returns',   'Can I return an item?',                 'Yes. Returns are accepted within 7 days of delivery for unopened items. Contact us at support@snacksonline.com.'),
('Technical', 'I forgot my password. What do I do?',  'Click "Log in" and use the forgot password link, or contact our support team at support@snacksonline.com.');
