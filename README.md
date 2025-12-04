# Water Refilling Station Management System

A comprehensive web-based application designed to streamline the daily operations of a water refilling station. This system manages customers, orders, inventory, payments, and generates insightful reports, all wrapped in a modern, minimalist, and user-friendly interface.

## 🚀 Features

-   **Dashboard**: Get a real-time overview of your business performance, including total sales, active customers, and pending orders.
-   **Customer Management**: Easily add, view, edit, and delete customer records. Keep track of customer details and history.
-   **Order Management**: Create new orders, track their status (Pending, Processing, Delivered, etc.), and manage order details.
-   **Inventory Management**: Monitor stock levels of water containers and other products. Receive low stock alerts (visual indicators).
-   **Payment Management**: Record payments, track outstanding balances, and view payment history.
-   **Reports & Analytics**: Generate detailed reports on sales, inventory usage, and customer activity to make informed business decisions.
-   **Database Backup & Restore**: Secure your data with built-in backup and restore functionality.
-   **Modern UI/UX**: Features a "Minimalist & Chill" design philosophy with a clean layout, soft colors, and responsive elements.

## 🛠️ Technologies Used

-   **Frontend**: HTML5, CSS3 (Custom "Minimalist & Chill" Design), JavaScript (Vanilla + Bootstrap Icons).
-   **Backend**: PHP (Native).
-   **Database**: MySQL.
-   **Server Environment**: WAMP/XAMPP (Apache, MySQL, PHP).

## 📦 Installation & Setup

1.  **Clone or Download**:
    -   Clone this repository or download the ZIP file.
    -   Extract the contents into your web server's root directory (e.g., `C:\wamp64\www\Water-Refilling-Station-Management-System` for WampServer).

2.  **Database Setup**:
    -   Open phpMyAdmin (usually at `http://localhost/phpmyadmin`).
    -   Create a new database named `water_refilling_db` (or any name you prefer).
    -   Import the provided SQL file: `water_refilling_db.sql` located in the project root.

3.  **Configuration**:
    -   Navigate to `backend/config/db.php`.
    -   Open the file and update the database credentials if necessary:
        ```php
        $host = 'localhost';
        $db_name = 'water_refilling_db'; // Make sure this matches your database name
        $username = 'root';              // Default WAMP/XAMPP username
        $password = '';                  // Default WAMP/XAMPP password (usually empty)
        ```

4.  **Run the Application**:
    -   Open your web browser and go to `http://localhost/Water-Refilling-Station-Management-System`.

## 🔑 Usage

-   **Login**: Use the admin credentials to log in.
    -   *Default Admin Credentials (if applicable, otherwise create one in the database)*:
        -   Username: `admin`
        -   Password: `admin` (or check the `users` table hash)

## 🎨 Design Philosophy

The user interface is built with a "Minimalist & Chill" approach, focusing on:
-   **Clarity**: High contrast text and clean typography.
-   **Simplicity**: Uncluttered layouts and intuitive navigation.
-   **Aesthetics**: Soft color palettes (Blues, Whites, Grays) to reduce eye strain and create a professional look.

## 📝 License

This project is open-source and available for educational and personal use.