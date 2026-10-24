# 🛠️ QuickFix — Home Services Booking System

**QuickFix** is a full-stack home services booking web application designed to simplify service booking and provider management. Built with **Laravel 12**, **PHP**, and **MySQL**, QuickFix connects customers with service providers through booking management, payment verification, customer-provider communication, and administrator-controlled provider verification.

---

## ✨ Key Features

- **👤 Customer Account Management**
  - Customers can register, log in, and manage their profiles.
  - Browse available home services and service providers.
  - Create service bookings and track booking status.
  - Communicate with service providers through the chat system.

- **🛠️ Service Provider Management**
  - Providers can register and manage their profiles.
  - Submit NRC front and back images for identity verification.
  - View submitted NRC documents in a secure, read-only profile section.
  - Accept or reject booking requests and update service progress.

- **📅 Booking & Status Tracking**
  - Manage bookings from creation to completion.
  - Track statuses including Pending, Accepted, On the Way, Arrived, In Progress, and Completed.
  - Handle rejected, cancelled, and expired bookings.
  - Maintain booking status history for tracking updates.

- **💳 Payment & Refund Management**
  - Manage service amounts, prepayments, and remaining balances.
  - Support payment submission and verification workflows.
  - Track payment statuses and related booking information.
  - Support refund submission and verification workflows.

- **💬 Customer–Provider Communication**
  - Database-backed chat between customers and service providers.
  - Send and retrieve messages through asynchronous requests.
  - Support communication throughout the booking process.

- **⭐ Reviews & Ratings**
  - Customers can submit reviews and ratings for service providers.
  - Providers can view customer feedback.
  - Support provider review management.

- **🛡️ Administrator Management**
  - Manage customer and provider accounts.
  - Review provider registration details and NRC documents.
  - Approve, reject, or suspend provider accounts.
  - Monitor booking and provider information.

---

## 🛠️ Technology Stack

- **Backend Framework:** PHP 8.2+, Laravel 12
- **Frontend Engine:** HTML5, CSS3, JavaScript (ES6+), Bootstrap
- **Database Management:** MySQL / MariaDB
- **ORM:** Laravel Eloquent ORM
- **Authentication & Authorization:** Laravel Authentication and Role-Based Access Control
- **Asynchronous Requests:** AJAX
- **File Storage:** Laravel Filesystem with private storage for NRC documents
- **Development Environment:** XAMPP
- **Version Control:** Git and GitHub
