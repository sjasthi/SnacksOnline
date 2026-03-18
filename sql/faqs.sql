-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2026 at 12:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snacksonline`
--

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int(11) NOT NULL,
  `category` varchar(80) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faq_id`, `category`, `question`, `answer`, `created_at`) VALUES
(1, 'Ordering', 'How do I place an order?', 'Browse our items, add them to your cart, and proceed to checkout. You can order as a guest or create an account.', '2026-02-25 17:07:13'),
(2, 'Ordering', 'Can I order without creating an account?', 'Yes! We support guest checkout. Just fill in your name, email, and delivery address at checkout.', '2026-02-25 17:07:13'),
(3, 'Payments', 'What payment methods do you accept?', 'We currently accept cash on delivery. Online payment is coming soon.', '2026-02-25 17:07:13'),
(4, 'Shipping', 'How long does delivery take?', 'Orders are dispatched within 24 hours and typically delivered within 3–5 business days.', '2026-02-25 17:07:13'),
(5, 'Shipping', 'Do you deliver internationally?', 'We currently deliver within the Twin Cities. International shipping is planned for a future update.', '2026-02-25 17:07:13'),
(6, 'Returns', 'Can I return an item?', 'Yes. Returns are accepted within 7 days of delivery for unopened items. Contact us at support@snacksonline.com.', '2026-02-25 17:07:13'),
(7, 'Technical Assistance', 'I forgot my password. What do I do?', 'Click \"Log in\" and use the forgot password link, or contact our support team at support@snacksonline.com.', '2026-02-25 17:07:13'),
(8, 'Shipping', 'What happens after I place my order?', 'After placing your order, you\'ll receive a confirmation email within minutes. Your order is then prepared and dispatched within 24 hours. You can track your order status in your account dashboard.', '2026-03-05 00:22:21'),
(9, 'Shipping', 'What next after ordering?', 'After placing your order, you\'ll receive a confirmation email within minutes. Your order is then prepared and dispatched within 24 hours. You can track your order status in your account dashboard.', '2026-03-05 00:22:21'),
(10, 'Returns', 'Do you accept returns?', 'Yes. Returns are accepted within 7 days of delivery for unopened items. Contact us at support@snacksonline.com or using our Phone number with your order number to initiate a return.', '2026-03-05 00:22:21'),
(11, 'Ordering', 'How do I track my order?', 'You can track your order status by logging into your account and viewing your order history. You\'ll also receive status updates via email as your order progresses.', '2026-03-05 00:22:21'),
(12, 'Payments', 'When do I pay if I choose cash on delivery?', 'With cash on delivery, you pay the full amount when your order arrives at your doorstep. Our delivery agent will collect payment in cash.', '2026-03-05 00:22:21'),
(13, 'Customer Service', 'How can I contact support?', 'You can reach our support team at support@snacksonline.com or use the chat feature on our website. We typically respond within 24 hours.', '2026-03-05 00:22:21'),
(14, 'Ordering', 'Can I cancel an order after placing it?', 'Yes, you can cancel an order within 2 hours of placing it by visiting your order history page. After this window, the order may already be processed, but you can still contact support to check if cancellation is possible.', '2026-03-10 19:04:13'),
(15, 'Ordering', 'Can I change my delivery address after checkout?', 'If your order has not yet been dispatched, you can update your delivery address by contacting support. Once the package is shipped, address changes are no longer possible.', '2026-03-10 19:04:13'),
(16, 'Ordering', 'Can I order items in bulk?', 'Absolutely! We support bulk orders for parties, events, or businesses. Simply add the desired quantity to your cart, and if you need more than 50 units of an item, contact support for special arrangements.', '2026-03-10 19:04:13'),
(17, 'Ordering', 'Do you offer order confirmations?', 'Yes, every order generates a confirmation email within minutes. This email includes your order number, item details, and estimated delivery time.', '2026-03-10 19:04:13'),
(18, 'Ordering', 'Can I reorder past purchases easily?', 'Registered users can view their order history and reorder with a single click. Guests will need to manually add items again, but confirmation emails can help track past purchases.', '2026-03-10 19:04:13'),
(19, 'Customer Service', 'What are your support hours?', 'Our support team is available Monday through Friday from 9 AM to 6 PM CST. We also monitor urgent requests on weekends, though response times may be slower.', '2026-03-10 19:04:13'),
(20, 'Customer Service', 'Do you offer live chat support?', 'Yes, our chatbot is available 24/7 for FAQ-related queries. For complex issues, you can escalate to a live agent during business hours.', '2026-03-10 19:04:13'),
(21, 'Customer Service', 'How quickly does support respond?', 'We aim to respond to all inquiries within 24 hours. During peak seasons, responses may take up to 48 hours, but urgent issues are prioritized.', '2026-03-10 19:04:13'),
(22, 'Customer Service', 'Can I contact support by phone?', 'Yes, we provide a customer service hotline listed on our website. Phone support is available during business hours for urgent matters.', '2026-03-10 19:04:13'),
(23, 'Customer Service', 'Do you offer multilingual support?', 'Currently, we provide support in English and Spanish. We are working on expanding to more languages to serve our diverse customer base.', '2026-03-10 19:04:13'),
(24, 'Payments', 'Is my payment information secure?', 'Yes, we use industry-standard encryption and secure payment gateways to protect your information. Your card details are never stored on our servers.', '2026-03-10 19:04:13'),
(25, 'Payments', 'Do you offer discounts for bulk orders?', 'Yes, bulk orders may qualify for discounts depending on the quantity. Contact support for a custom quote if you’re ordering in large volumes.', '2026-03-10 19:04:13'),
(26, 'Payments', 'Can I use gift cards?', 'At present, we do not support gift cards. However, we are exploring partnerships to introduce this option in the near future.', '2026-03-10 19:04:13'),
(27, 'Payments', 'Do you charge extra fees?', 'No hidden fees are applied. The only charges are item prices, applicable taxes, and delivery fees, which are clearly shown at checkout.', '2026-03-10 19:04:13'),
(28, 'Payments', 'Can I split payments across methods?', 'Currently, we only support one payment method per order. We plan to add split payments in future updates.', '2026-03-10 19:04:13'),
(29, 'Returns', 'What if my item arrives damaged?', 'If your item arrives damaged, contact support within 48 hours with photos of the product. We will arrange a replacement or refund promptly.', '2026-03-10 19:04:13'),
(30, 'Returns', 'How do I get a refund?', 'Refunds are processed once the returned item is received and inspected. The amount will be credited back to your original payment method within 5–7 business days.', '2026-03-10 19:04:13'),
(31, 'Returns', 'Do you cover return shipping costs?', 'Yes, if the return is due to damage or an error on our part, we cover shipping costs. For other reasons, customers are responsible for return shipping.', '2026-03-10 19:04:13'),
(32, 'Returns', 'Can I exchange an item instead of returning it?', 'Yes, exchanges are possible if the item is in stock. Contact support to initiate an exchange request.', '2026-03-10 19:04:13'),
(33, 'Returns', 'Are there items that cannot be returned?', 'For safety and hygiene reasons, opened food items cannot be returned. Please review our return policy for full details.', '2026-03-10 19:04:13'),
(34, 'Shipping', 'Can I schedule a delivery time?', 'Yes, registered users can select preferred delivery windows during checkout. Availability depends on your location and courier schedules.', '2026-03-10 19:04:13'),
(35, 'Shipping', 'Do you offer same-day delivery?', 'Same-day delivery is available in select areas for orders placed before noon. Check your zip code during checkout to see if this option is offered.', '2026-03-10 19:04:13'),
(36, 'Shipping', 'Can I track my delivery in real time?', 'Yes, once your order is dispatched, you’ll receive a tracking link. This allows you to monitor your package’s journey until it arrives.', '2026-03-10 19:04:13'),
(37, 'Shipping', 'Do you deliver to offices or workplaces?', 'Yes, we deliver to both residential and commercial addresses. Ensure someone is available to receive the package during delivery hours.', '2026-03-10 19:04:13'),
(38, 'Shipping', 'What happens if I miss a delivery?', 'If you miss a delivery, the courier will attempt again the next business day. Alternatively, you can reschedule or pick up the package at a local depot.', '2026-03-10 19:04:13'),
(39, 'Technical Assistance', 'How do I update my account details?', 'Log in to your account and navigate to the profile settings page. You can update your name, email, password, and delivery address there.', '2026-03-10 19:04:13'),
(40, 'Technical Assistance', 'Why am I not receiving confirmation emails?', 'First, check your spam or promotions folder. If emails are still missing, verify that your email address is correct in your account settings.', '2026-03-10 19:04:13'),
(41, 'Technical Assistance', 'Can I reset my password if I forget it?', 'Yes, click the “Forgot Password” link on the login page. You’ll receive an email with instructions to reset your password securely.', '2026-03-10 19:04:13'),
(42, 'Technical Assistance', 'How do I delete my account?', 'Contact support to request account deletion. Once confirmed, your account and order history will be permanently removed.', '2026-03-10 19:04:13'),
(43, 'Technical Assistance', 'Can I use the site on mobile devices?', 'Yes, our website is fully mobile-friendly. You can browse, order, and track deliveries seamlessly from your smartphone or tablet.', '2026-03-10 19:04:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
