# HamroPasal

**HamroPasal** is a web-based inventory management system designed to
help small retail (kirana) shops in Nepal digitize and simplify their
daily operations. It replaces manual stock books and spreadsheets with a
centralized platform for managing products, categories, suppliers,
sales, and inventory.

The application is built with the **Laravel PHP framework** and
**MySQL**, following the Model--View--Controller (MVC) architecture. It
is designed with small shopkeepers in mind, with a straightforward
interface, role-based access, and localization for Nepali users.

## Features

-   **Product management** --- create, view, update, and delete
    products, including pricing and stock details.
-   **Category management** --- organize products into categories and
    subcategories.
-   **Inventory tracking** --- monitor current stock quantities and
    identify low-stock items.
-   **Sales recording** --- record sales and automatically deduct sold
    quantities from inventory.
-   **Supplier management** --- maintain supplier information and track
    purchase-related records.
-   **Dashboard** --- view an overview of inventory, sales trends, and
    important alerts.
-   **Role-based access** --- separate access for Shopkeepers and
    Administrators.
-   **User administration** --- allow administrators to manage
    shopkeeper accounts and permissions.
-   **Sales forecasting** --- use an Exponential Moving Average
    (EMA)-based approach to estimate short-term sales trends.
-   **Localization** --- designed for the Nepali retail context,
    including NPR currency and Nepali-language support.

## Technology Stack

  Area                            Technology
  ------------------------------- ------------------------------------
  Backend                         PHP, Laravel
  Database                        MySQL
  Frontend                        HTML, CSS, JavaScript/jQuery
  Architecture                    Laravel MVC / layered architecture
  Local development environment   XAMPP
  Development tools               Visual Studio Code, Draw.io

## User Roles

### Shopkeeper

-   Manage products and categories.
-   Monitor inventory and low-stock items.
-   Record sales and track stock changes.
-   View the dashboard and business information.

### Administrator

-   Manage shopkeeper accounts.
-   Configure user permissions.
-   Manage system settings and alert preferences.

## Getting Started

The following instructions describe a typical local setup for a Laravel
application. The exact commands may vary depending on the Laravel
version and the files included in your repository.

### Prerequisites

Install the following before setting up the project:

-   PHP (a version compatible with the Laravel version used by this
    project)
-   Composer
-   MySQL
-   A web server or the Laravel development server
-   Node.js and npm, if the repository includes frontend assets that
    need compiling

You can use **XAMPP** to run Apache and MySQL locally.

### Installation

1.  **Clone the repository**

    ``` bash
    git clone <repository-url>
    ```

2.  **Move into the project directory**

    ``` bash
    cd <project-folder>
    ```

3.  **Install PHP dependencies**

    ``` bash
    composer install
    ```

4.  **Create your environment file**

    ``` bash
    cp .env.example .env
    ```

    On Windows Command Prompt, you can use:

    ``` cmd
    copy .env.example .env
    ```

5.  **Generate the application key**

    ``` bash
    php artisan key:generate
    ```

6.  **Configure the database**

    Create a MySQL database for the project, then update the database
    settings in `.env`:

    ``` env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

7.  **Run database migrations**

    ``` bash
    php artisan migrate
    ```

    If the repository includes seeders and you want to populate the
    database with sample data, run:

    ``` bash
    php artisan db:seed
    ```

8.  **Install and build frontend dependencies (if applicable)**

    ``` bash
    npm install
    npm run build
    ```

9.  **Start the development server**

    ``` bash
    php artisan serve
    ```

10. Open the local URL shown by Laravel in your browser (typically
    `http://127.0.0.1:8000`).

> **Note:** These setup steps assume the repository contains a standard
> Laravel structure and an `.env.example` file. Check the project's
> actual Laravel/PHP version and repository configuration if a command
> or dependency differs.

## How It Works

1.  A shopkeeper signs in to the application.
2.  Products and categories are maintained in the product catalog.
3.  The shopkeeper records a sale.
4.  The system stores the transaction and updates the relevant stock
    quantity.
5.  Inventory information and low-stock alerts help the shopkeeper plan
    replenishment.
6.  The dashboard presents an overview of available business
    information.

## Sales Forecasting

HamroPasal's report describes an **Exponential Moving Average (EMA)**
approach for short-term sales forecasting. The method gives more weight
to recent sales data while smoothing daily fluctuations.

The documented workflow is:

1.  Collect historical sales data for each product.
2.  Calculate EMA values for sales quantity and revenue.
3.  Estimate the short-term trend from recent EMA values.
4.  Generate forecasts for the following seven days.
5.  Use forecasted demand to support stock planning and low-stock
    alerts.

## Project Scope and Limitations

The project focuses on core inventory and sales workflows for small
retail shops. The documented limitations include:

-   Full functionality depends on a stable internet connection.
-   The current scope emphasizes core inventory management and basic
    reporting rather than advanced analytics.
-   Multi-location shop management is outside the current scope.
-   Users with limited technical experience may need initial guidance or
    training.

Offline support, expanded analytics, and improved support for growing
businesses are identified as possible future enhancements.

## Project Team

-   **Anish Nepal**
-   **Bigyan Khatri**
-   **Unique Oli**

## Acknowledgements

We would like to thank our project supervisor, **Mr. Tekendra Nath
Yogi**, project coordinator **Mr. Santosh Sharma**, and the faculty and
staff of CAB-Tech for their guidance and support throughout the project.

## Contributing

This project was developed as a final-year academic project. If you
would like to suggest an improvement or report an issue, please open a
GitHub issue or submit a pull request with a clear description of the
proposed change.

## License

No license information was specified in the project report. Add a
license file and update this section before distributing or reusing the
project.
