# 🛒 SnacksOnline

**ICS499 Capstone Project — Metro State University**  
Instructor: Siva Jasthi | February 2026

A responsive e-commerce web application with FAQ-constrained AI chatbot.

---

## Tech Stack

| Layer    | Technology                        |
|----------|-----------------------------------|
| Frontend | HTML / CSS / JavaScript / jQuery  |
| UI       | Bootstrap 5.3                     |
| Backend  | PHP 8.2                           |
| Database | MySQL 8 (via PDO)                 |
| Hosting  | XAMPP (local) / cPanel (production) |

---

## 📁 File Structure

```
snacksonline/
├── index.php             ← Storefront (search + category filter)
├── item.php              ← Single item detail + related items
├── cart.php              ← Shopping cart (add / update / remove)
├── checkout.php          ← Guest + registered checkout
├── confirmation.php      ← Order confirmation
├── orders.php            ← My order history (registered users)
├── order_detail.php      ← Single order detail (registered users)
├── faq.php               ← FAQ accordion + chatbot link
├── about.php             ← About page
├── chat.php              ← Chatbot backend (FAQ-constrained AJAX)
├── login.php             ← Login (Admin + Customer)
├── register.php          ← Customer registration
├── logout.php            ← Destroy session
├── 404.php               ← Custom 404 page
│
├── admin/
│   ├── dashboard.php     ← Admin dashboard (stats + recent orders)
│   ├── items.php         ← Item list
│   ├── item_form.php     ← Add / Edit item (with photo upload)
│   ├── item_delete.php   ← Delete item
│   ├── orders.php        ← All orders list
│   ├── order_detail.php  ← Order detail + status update
│   ├── faqs.php          ← FAQ list
│   ├── faq_form.php      ← Add / Edit FAQ
│   └── faq_delete.php    ← Delete FAQ
│
├── includes/
│   ├── db.php            ← MySQL PDO connection
│   ├── auth.php          ← Session helpers + RBAC
│   ├── navbar.php        ← Shared navbar (all public pages)
│   ├── footer.php        ← Shared footer + chatbot widget
│   ├── admin_navbar.php  ← Admin sidebar layout (open)
│   └── admin_footer.php  ← Admin layout (close)
│
├── assets/
│   ├── css/style.css     ← Custom brand styles
│   ├── js/main.js        ← jQuery: chatbot, cart, interactions
│   └── uploads/          ← Product photo uploads (writable)
│
└── sql/
    └── schema.sql        ← Full database schema + seed data
```

---

## 🖥️ Run Locally with XAMPP (Windows)

### Step 1 — Install XAMPP
Download from **apachefriends.org** and install.  
Open XAMPP Control Panel → Start **Apache** and **MySQL**.

### Step 2 — Copy Project Files
Copy the entire `snacksonline` folder to:
```
C:\xampp\htdocs\snacksonline\
```

### Step 3 — Create the Database
Open your browser and go to:
```
http://localhost/phpmyadmin
```
- Click **New** → name it `snacksonline` → click **Create**
- Click the `snacksonline` database → click **Import**
- Choose `sql/schema.sql` → click **Go**

This creates all 5 tables and seeds 5 demo snacks + 7 FAQs automatically.

### Step 4 — Run the App
Open your browser:
```
http://localhost/snacksonline/
```

---

## 🔑 Default Accounts

| Role     | Email                       | Password    |
|----------|-----------------------------|-------------|
| Admin    | admin@snacksonline.com      | `password`  |
| Customer | *(register via /register.php)* | —        |

> ⚠️ **Change the admin password immediately after first login!**
> 
> Generate a new hash in phpMyAdmin terminal:
> ```sql
> UPDATE users SET password = '$2y$10$...' WHERE email = 'admin@snacksonline.com';
> ```
> Or generate the hash using PHP:
> ```php
> echo password_hash('YourNewPassword', PASSWORD_BCRYPT);
> ```

---

## 🤖 FAQ-Constrained AI Chatbot

### Overview
The chatbot provides intelligent customer support using **only verified FAQ data**, preventing hallucinated responses.

### How It Works

**Frontend** (`assets/js/main.js`):
- jQuery AJAX widget sends user messages to `chat.php`
- Displays typing indicator while processing
- Shows bot responses in chat bubbles

**Backend** (`chat.php`):
1. Receives user message via POST
2. Splits message into keywords (3+ characters)
3. Searches `faqs` table for matching questions/answers using keyword matching
4. Scores results by keyword frequency
5. Returns the highest-scoring FAQ answer
6. If no match found, returns safe fallback response

### Features
- ✅ **Semantic FAQ Matching** — keyword-based similarity scoring
- ✅ **No Hallucinations** — strictly limited to FAQ database
- ✅ **Safe Fallback** — directs users to contact support if no match
- ✅ **Real-time AJAX** — instant responses without page reload
- ✅ **Admin Controlled** — FAQ content managed via admin panel

### Admin Management
Admins can add/edit/delete FAQs at `/admin/faqs.php`:
- Each FAQ has `question`, `answer`, and `category`
- Changes are **immediately available** to chatbot
- No code modifications required

### Testing the Chatbot
1. Open any page on the site
2. Click the **Chat button** (bottom-right)
3. Ask a question related to your FAQs
4. Bot responds with exact FAQ data or helpful fallback

---

## 🌐 Deploy to cPanel (Production)

1. Open **cPanel → MySQL Databases** → create database `snacksonline`
2. Create a MySQL user, assign all privileges to the database
3. Open **phpMyAdmin** → import `sql/schema.sql`
4. Edit `includes/db.php` — update `DB_USER`, `DB_PASS`, `DB_HOST`
5. Upload all files via **FileZilla FTP** to `public_html/snacksonline/`
6. Update all `/snacksonline/` URL prefixes in `includes/auth.php` if your domain path differs
7. Visit `yourdomain.com/snacksonline/`

---

## ✅ Features

- **Storefront** — item grid with live search and category filter
- **Item Detail** — photo, description, related items, Add to Cart
- **Session Cart** — add, update quantities, remove items
- **Guest Checkout** — no login required, name + email + address
- **Registered Checkout** — email pre-filled, order saved to history
- **Order Confirmation** — full summary with order ID and status
- **Order History** — registered users see all past orders
- **FAQ Page** — Bootstrap accordion grouped by category
- **AI Chatbot** — jQuery AJAX widget, answers strictly from FAQ table
- **Admin Dashboard** — stats, recent orders
- **Admin Item CRUD** — create/edit/delete with photo upload
- **Admin Order Management** — view all orders, update status
- **Admin FAQ Management** — full CRUD for chatbot knowledge base
- **Role-Based Access Control** — admin vs customer vs guest
- **Password Security** — bcrypt hashing via `password_hash()`
- **SQL Injection Protection** — PDO prepared statements throughout