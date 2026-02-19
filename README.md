# ICS 499 – Software Engineering and Capstone Project Outline

## Project Title: Snacks Online – A Responsive E-Commerce Web Application with FAQ-Constrained AI Chatbot

**Prepared by:** Siva Jasthi  
**Course:** ICS 499 – Software Engineering and Capstone  
**Date:** February 18, 2026

---

## 1. Project Overview

**Snacks Online** is a modern, responsive e-commerce web application designed to provide a seamless online snack ordering experience. The system enables **role-based access control**, **item management**, **secure ordering**, and **guest checkout**. Additionally, the platform integrates an **AI-powered chatbot constrained to an FAQ knowledge base** to provide intelligent and safe customer support.

The project follows **software engineering best practices**, including structured requirements analysis, modular design, secure authentication, and scalable architecture.

---

## 2. Project Objectives

- Design and develop a **fully responsive e-commerce platform**.
- Implement **role-based access control (RBAC)**.
- Provide **admin-controlled item and FAQ management**.
- Support **guest and registered user ordering**.
- Integrate an **AI chatbot constrained strictly to FAQ knowledge**.
- Follow **secure coding, scalability, and usability best practices**.

---

## 3. System Scope

### In Scope

- User authentication and authorization
- Item management (CRUD for Admin only)
- Item browsing
- Shopping cart and checkout
- Guest checkout
- Order confirmation
- About page
- FAQ management
- FAQ-constrained chatbot

### Out of Scope (Future Enhancements)

- Online payment gateway integration
- Real-time order tracking
- Mobile application
- Vendor management

---

## 4. User Roles & Permissions

| Role          | Permissions                                                                    |
|---------------|--------------------------------------------------------------------------------|
| **Admin**     | Manage snack items (CRUD), manage FAQs, view orders, manage chatbot knowledge  |
| **Customer**  | Browse items, add to cart, place orders, view FAQs, use chatbot                |
| **Guest User**| Browse items, place orders, view FAQs, use chatbot                             |

---

## 5. System Features

### 5.1 Role-Based Access Control (RBAC)

- Admin-only access to item and FAQ management.
- Customers and guests limited to viewing and ordering.
- Secure authentication and authorization enforcement.

---

### 5.2 Item Management (Admin Only)

Admins can:

- Create snack items
- Read and list snack items
- Update item details
- Delete items

Each item includes:

- Item ID
- Name
- Description
- Price
- Image

---

### 5.3 Item Browsing

- All users can browse items.
- Clean UI with category filtering and search.
- Responsive layout for mobile, tablet, and desktop.

---

### 5.4 Order Processing System

- Add-to-cart functionality
- Cart modification
- Guest checkout
- Registered user checkout
- Order confirmation page

---

### 5.5 Informational Pages

#### About Page

- Application overview
- Organization background
- Mission statement
- Contact information

#### FAQ Page

- Ordering
- Payments
- Shipping
- Returns
- Technical support

Admin can manage FAQ entries.

---

### 5.6 FAQ-Constrained AI Chatbot

#### Purpose

Provide intelligent customer support using **only verified FAQ data**, preventing hallucinated responses.

#### Features

- Natural language query processing
- Semantic FAQ matching
- Real-time response generation
- Safe fallback responses

#### Constraints

- Answers strictly limited to FAQ database
- No external knowledge or hallucination

---

## 6. Functional Requirements Summary

- Role-based authentication system
- Admin-only CRUD operations on items
- Secure shopping cart
- Guest checkout
- Order confirmation
- Admin-controlled FAQ management
- FAQ-constrained chatbot responses

---

## 7. Non-Functional Requirements

| Category    | Requirement                                                           |
|-------------|-----------------------------------------------------------------------|
| Usability   | Responsive, intuitive UI                                              |
| Performance | Page load < 3 seconds                                                 |
| Scalability | Support 100+ concurrent users                                         |
| Security    | Encrypted passwords, SQL injection protection, XSS & CSRF protection  |
| Reliability | 99% uptime                                                            |
| Chatbot     | < 2 sec response, 90%+ accuracy                                       |

---

## 8. System Architecture Overview

### High-Level Architecture

```
Client (Browser / Mobile)
        |
Frontend (React / HTML / CSS / JS)
        |
Backend API (Node.js / Django / Flask / PHP)
        |
Database (MySQL / PostgreSQL / MongoDB)
        |
FAQ Knowledge Base + Chatbot Engine
```

---

## 9. Technology Stack (Suggested)

| Layer          | Technologies                                 |
|----------------|----------------------------------------------|
| Frontend       | React / HTML5 / CSS3 / Bootstrap / Tailwind  |
| Backend        | Node.js + Express / Django / Flask           |
| Database       | MySQL / PostgreSQL / MongoDB                 |
| Authentication | JWT / OAuth                                  |
| AI Chatbot     | OpenAI API / Vector DB (FAISS / Pinecone)    |
| Hosting        | AWS / Azure / Localhost                      |

---

## 10. Database Design Overview

### Core Tables

- **Users** (id, name, email, password, role)
- **Items** (id, name, description, price, image_url)
- **Orders** (id, user_id, total, status, timestamp)
- **Order_Items** (order_id, item_id, quantity)
- **FAQs** (id, question, answer)

---

## 11. Security Design

- Password hashing using bcrypt
- JWT-based authentication
- Role-based authorization
- Input validation
- SQL injection protection
- XSS & CSRF protection

---

## 12. Testing Strategy

### Test Types

- Unit testing
- Integration testing
- System testing
- Security testing
- Usability testing

---

## 13. Project Deliverables

- Software Requirements Specification (SRS)
- System Architecture Diagram
- Database ER Diagram
- UI Mockups
- Source Code (GitHub)
- Test Plan & Test Report
- Final Project Report
- Project Presentation

---

## 14. Future Enhancements

- Payment gateway integration
- Order tracking
- Email & SMS notifications
- Recommendation system
- Mobile application

---

## 15. Conclusion

The **Snacks Online** capstone project demonstrates strong application of **software engineering principles**, **secure system design**, **AI integration**, and **full-stack development**, making it a **high-impact ICS 499 Capstone project**.
