## 🏥 Medical Records System

A Laravel-based application for managing patient examination records. Doctors can create, read, update, and delete patient examination notes including diagnosis, prescriptions, and additional notes.

---

## 📦 Installation Steps

1. **Clone the repository**

    ```bash
    git clone https://github.com/mwyzer/medical-records.git
    cd medical-records
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install && npm run dev
    ```

3. **Copy `.env` file and configure**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Set up your database in `.env`**

    ```env
    DB_DATABASE=medical_records
    DB_USERNAME=root
    DB_PASSWORD=root
    ```

5. **Run migrations and seeders**

    ```bash
    php artisan migrate --seed
    ```

6. **Start the local development server**

    ```bash
    php artisan serve
    ```

---

<!--
## 🗂️ Database Structure

### 🔧 SQL File

You can import `database/structure.sql` to create the schema manually if preferred.

### 📊 ERD (Entity Relationship Diagram)

> The diagram shows:
>
> -   `users` (role-based: admin, doctor, patient)
> -   `doctors` → `specialists`, `hospitals`
> -   `medical_records` → linked to `doctors`

![ERD Example](public/erd-diagram.png)

> _Make sure the ERD image is saved in `public/erd-diagram.png` or adjust the path._

---

## 🔐 Dummy Login Accounts

| Role    | Email                                             | Password |
| ------- | ------------------------------------------------- | -------- |
| Admin   | [admin@example.com](mailto:admin@example.com)     | password |
| Doctor  | [doctor@example.com](mailto:doctor@example.com)   | password |
| Patient | [patient@example.com](mailto:patient@example.com) | password |

These users are created automatically via seeder (`DatabaseSeeder.php`).

---

## 💡 Features

-   �튺 Doctors can manage patient medical records.
-   👥 Admin can manage users (doctors & patients).
-   🏥 Records include patient name, examination date, diagnosis, prescription, and additional notes. -->
