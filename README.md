# Laravel 12 Admin Boilerplate

<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 🚀 About The Project

This is a modern, robust **Laravel 12 Boilerplate** designed to kickstart your admin panel development. It comes pre-configured with the latest technologies and essential features, ensuring a smooth and efficient development workflow.

### ✨ Key Features

-   **Laravel 12 (Dev)**: Built on the bleeding edge of the PHP framework.
-   **Vite 7 & Tailwind CSS 4**: Ultra-fast build tool and utility-first CSS framework for modern styling.
-   **AdminLTE 4 (RC3)**: A fully responsive and customizable admin dashboard template.
-   **Role & Permission Management**: Integrated with `spatie/laravel-permission` for granular access control.
-   **Dynamic Menu Management**: Manage sidebar menus directly from the database with a dedicated UI.
-   **Service Pattern Architecture**: Clean code structure separating business logic from controllers.
-   **Interactive DataTables**: Server-side rendering with jQuery DataTables for handling large datasets efficiently.
-   **Authentication**: Secure login, logout, and session management.

## 🛠️ Tech Stack

-   **Backend**: Laravel 12, PHP 8.2+
-   **Frontend**: Blade, Vite 7, Tailwind CSS 4, Bootstrap 5 (via AdminLTE)
-   **Database**: MySQL / MariaDB / PostgreSQL
-   **Packages**:
    -   `spatie/laravel-permission`
    -   `yajra/laravel-datatables-oracle`
    -   `almasaeed2010/adminlte`

## ⚙️ Requirements

Ensure your server meets the following requirements:

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   Database Server (MySQL, PostgreSQL, etc.)

## 📦 Installation

Follow these steps to set up the project locally:

1.  **Clone the repository**
    ```bash
    git clone https://github.com/NaufalAjaSih/boilerplate.git
    cd boilerplate
    ```

2.  **Install PHP dependencies**
    ```bash
    composer install
    ```

3.  **Install Node.js dependencies**
    ```bash
    npm install
    ```

4.  **Environment Setup**
    Copy the `.env.example` file to `.env` and configure your database credentials.
    ```bash
    cp .env.example .env
    ```

5.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6.  **Run Migrations and Seeders**
    This will set up the database structure and populate it with default roles, permissions, and users.
    ```bash
    php artisan migrate --seed
    ```

7.  **Start Development Server**
    Run the Vite development server and Laravel server concurrently (if configured) or separately.
    ```bash
    npm run dev
    # In a separate terminal
    php artisan serve
    ```

## 🔑 Default Credentials

The project comes with pre-seeded users for testing:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@example.com` | `password` |
| **Admin** | `admin@example.com` | `password` |

> **Note:** Please change these credentials immediately after deployment.

## 📂 Project Structure

-   `app/Services`: Contains business logic services (e.g., `MenuService`, `SidebarService`).
-   `app/Http/Controllers/Setting`: Controllers for managing system settings like Menus, Roles, and Permissions.
-   `resources/views/admin`: Admin panel views.
-   `resources/views/layouts`: Master layouts using AdminLTE.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
