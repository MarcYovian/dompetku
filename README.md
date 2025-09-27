# Dompetku - Personal Finance Management Application

![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)

[MIT License](https://opensource.org/licenses/MIT)

Dompetku is a modern web application for personal finance management designed to help users easily track income, expenses, and fund transfers between accounts. Built with the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire), it provides a fast, reactive user experience similar to a SPA.

---

## Table of Contents

1. [Key Features](#-key-features)
2. [Technical Requirements](#technical-requirements)
3. [Installation](#️-installation)
4. [Usage](#usage)
5. [Project Structure](#-project-structure)
6. [Contributing](#-contributing)
7. [License](#-license)
8. [Contact](#-contact)

---

## ✨ Key Features

-   **Interactive Dashboard**: Visualize financial summaries, monthly income & expense trends, and balances across all fund sources in a single view.
-   **Transaction Management (CRUD)**: Easily add, edit, and delete income and expense records.
-   **Hierarchical Categories**: Organize transactions into categories and subcategories for better grouping.
-   **Fund Sources**: Define various fund sources such as bank accounts, e-wallets, or cash.
-   **Fund Transfers**: Record money transfers between accounts, complete with optional admin fees.
-   **Dynamic Reports**: Filter and view financial history by date range, transaction type, or fund source.
-   **Responsive & Mobile-First Design**: Optimized UI for all devices, from mobile to desktop.
-   **Secure Authentication**: Safe registration and login system to protect your financial data.

---

## Technical Requirements

Make sure your server or local environment meets the following requirements:

-   **PHP 8.2** or higher
-   **Composer v2**
-   **Node.js & NPM** (for frontend asset management)
-   **Database Server** (MySQL / MariaDB recommended)

---

## ️ Installation

Follow these steps to set up the project locally:

1. **Clone the Repository**

    ```bash
    git clone https://github.com/marcyovian/dompetku.git
    cd dompetku
    ```

2. **Install PHP Dependencies**

    ```bash
    composer install
    ```

3. **Install JavaScript Dependencies**

    ```bash
    npm install
    ```

4. **Set Up Environment File**
   Copy `.env.example` to `.env` and configure your database:

    ```bash
    cp .env.example .env
    ```

    Example configuration:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=dompetku
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

6. **Run Database Migrations & Seeders**
   This creates the necessary tables and seeds initial data (e.g., categories).

    ```bash
    php artisan migrate --seed
    ```

7. **Compile Frontend Assets**

    ```bash
    npm run dev
    ```

    Keep this running in your terminal.

8. **Run Development Server**

    ```bash
    php artisan serve
    ```

    The app will be available at `http://127.0.0.1:8000`.

---

## Usage

Once installed, you can start using the app:

-   **Register a New Account**: Sign up via the registration page.
-   **Login with Demo Account** (if seeders were run):

    -   **Email**: `user@example.com`
    -   **Password**: `password`

After logging in, try:

1. Adding **Fund Sources** (e.g., Bank, E-Wallet, Cash).
2. Recording new **Income or Expense Transactions**.
3. Testing the **Fund Transfer** feature between sources.

---

## 🏗️ Project Structure

This project follows Laravel’s standard structure with emphasis on the **Service-Repository Pattern** for better code organization:

-   `app/Http/Livewire/Pages`: Main Livewire components representing pages.
-   `app/Services`: Business logic (e.g., `TransactionService` handles balance updates).
-   `app/Repositories`: Database interaction logic using Eloquent.
-   `app/Livewire/Traits`: Reusable Livewire traits (e.g., `WithDeleteConfirmation`).
-   `database/migrations`: Database schema.
-   `resources/views`: Blade files for Livewire components and layouts.

---

## 🤝 Contributing

Contributions are welcome! To contribute:

1. **Fork** the repository.
2. Create a new branch:

    ```bash
    git checkout -b feature/awesome-feature
    ```

3. **Follow Coding Standards**: Keep logic separated in Services & Repositories.
4. Submit a **Pull Request** to the `main` branch.
5. Clearly describe your changes in the PR.

---

## 📜 License

This project is licensed under the **MIT License**. See `LICENSE.md` for details.

---

## 📧 Contact

For questions, ideas, or collaboration opportunities:

-   **GitHub**: [marcyovian](https://github.com/marcyovian)
-   **Email**: `marcel.yovian@gmail.com`
