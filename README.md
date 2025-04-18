## Installation Instructions

Follow these steps to set up and run the application:

1. **Install Composer**:  
    Ensure that Composer is installed on your system.  

    - For Windows users: Download and run the Composer installer binary from [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe).  
    - For macOS/Linux users: Follow the installation instructions at [getcomposer.org](https://getcomposer.org/).

2. **Clone the Repository**:  
    Clone the project repository to your local machine:
    ```bash
    git clone https://github.com/ahmadrivaldi-arv/karyaone.git
    cd karyaone
    ```

    For Windows Command Prompt:
    ```cmd
    git clone https://github.com/ahmadrivaldi-arv/karyaone.git
    cd karyaone
    ```

3. **Set Up Environment File**:  
    Copy the `.env.example` file to `.env` and configure the environment variables as needed:
    ```bash
    cp .env.example .env
    ```

    For Windows Command Prompt:
    ```cmd
    copy .env.example .env
    ```

    Edit the `.env` file to configure your database connection. Update the following variables with your database details:
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=karyaone
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4. **Install Dependencies**:  
    Install all required dependencies using Composer:
    ```bash
    composer install
    ```

5. **Generate Application Key**:  
    Generate the application encryption key:
    ```bash
    php artisan key:generate
    ```

6. **Set Up Database**:  
    Ensure your database is running and update the `.env` file with your database credentials. Then, run the migrations to set up the database schema:
    ```bash
    php artisan migrate
    ```

7. **Seed the Database (Optional)**:  
    If your application includes seeders, you can populate the database with initial data:
    ```bash
    php artisan db:seed
    ```

8. **Serve the Application**:  
    Start the Laravel development server:
    ```bash
    php artisan serve
    ```

Your application should now be up and running. Open your browser and navigate to `http://localhost:8000` to access it.

### Default Login Credentials

Use the following credentials to log in (make sure you run seeder command see point 7):

- **Email**: `superadmin@admin.com`
- **Password**: `admin`


