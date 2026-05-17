# Farmora 🌱 - Precision Nature.

Farmora is a modern, eco-futurist agricultural marketplace built on Laravel 12. It connects farmers directly with consumers through a stunning, glassmorphic interface. The platform supports standard e-commerce purchases as well as an advanced live-bidding auction system for high-demand agricultural yields.

---

## ✨ Key Features

- **Multi-Role Ecosystem**: Distinct dashboards and flows for **Admins**, **Farmers**, and **Consumers**.
- **Live Bidding System**: Real-time countdown timers and dynamic bid tracking for auction-based produce.
- **Farmer Verification**: Secure government ID uploads subject to administrative approval before farmers can list products.
- **Glassmorphic UI**: High-fidelity, premium "Eco-Futurist" design built strictly with Tailwind CSS v4.
- **Responsive Design**: Flawless experience across mobile and desktop environments.

---

## 🛠 Prerequisites

Before installing Farmora, ensure your local development environment meets the following requirements:

- **PHP**: `^8.2`
- **Composer**: Latest version
- **Node.js**: `v18+` (with npm)
- **Database**: MySQL `8.0+` (or MariaDB equivalent)

### ⚠️ Important PHP Configuration

Farmora allows farmers to upload high-quality product images and large Government ID documents (up to 20MB). To prevent server errors during file uploads, you **must** update your PHP configuration file.

**File to Edit**: `php.ini`
*(Typical locations: `C:\xampp\php\php.ini`, `/etc/php/8.2/cli/php.ini`, or use `php --ini` in terminal to find your loaded configuration file).*

Find and modify the following lines in your `php.ini`:

```ini
; Maximum allowed size for uploaded files.
upload_max_filesize = 20M

; Must be greater than or equal to upload_max_filesize
post_max_size = 25M

; Maximum execution time of each script, in seconds
max_execution_time = 120
```
> **Action Required**: Restart your local server (Apache/Nginx/XAMPP/Herd) after saving changes to `php.ini`.

---

## 🚀 Setup Guide & Commands

Follow these exact terminal commands from start to finish to get Farmora running on your local machine.

**1. Clone the repository**
```bash
git clone https://github.com/your-username/Farmora.git
cd Farmora
```

**2. Install PHP Dependencies**
```bash
composer install
```

**3. Environment Setup**
Create your environment file by copying the example:
```bash
cp .env.example .env
```

**File to Edit**: `.env` (Located in the root `Farmora/` directory)
Open the `.env` file and update your database credentials. Look for the `DB_` section and change it to match your local MySQL setup (e.g., your XAMPP/WAMP username and password):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmora
DB_USERNAME=root
DB_PASSWORD=
```
*(You do not need to manually create this database! Laravel will offer to create it automatically in step 6).*

**4. Generate Application Key**
```bash
php artisan key:generate
```

**5. Link Local Storage**
Because Farmora handles image and document uploads, you must link the public storage directory so images are visible to the web:
```bash
php artisan storage:link
```

**6. Run Database Migrations**
This command builds the database tables:
```bash
php artisan migrate
```
*(If the `farmora` database does not exist yet, your terminal will ask: "Would you like to create it?" — simply type **`yes`** and press enter. To also insert demo data, run `php artisan migrate --seed` instead).*

**7. Install & Build Frontend Assets**
Farmora uses Vite to compile Tailwind CSS v4 and JavaScript logic.
```bash
npm install
npm run build
```

**8. Start the Application**
Start the Laravel backend development server:
```bash
php artisan serve
```
The site will now be accessible at `http://127.0.0.1:8000`.

*(Optional: If you are actively modifying the CSS or JavaScript files, open a second terminal tab, navigate to the `Farmora` folder, and run the frontend build watcher)*:
```bash
npm run dev
```

---

## 🔐 Demo Accounts & Flow

If you ran the database seeders (`php artisan migrate --seed`), you can log in immediately using the default Administrator account:
- **Email:** `admin@farmora.com`
- **Password:** `password`

By default, new users can register via the `/register` page in the browser.
- **Consumers** are auto-approved instantly.
- **Farmers** must be approved by an Admin.

To manage users:
1. Log in to the application as an Admin.
2. Navigate to the **Dashboard** -> **Pending Approvals** or the **User Management** tab.
3. Click **View ID** to inspect their uploaded Government ID.
4. Click **Approve** to grant them access to the platform.

---

## 🏗 Built With
- [Laravel 12](https://laravel.com) - The PHP Framework for Web Artisans
- [Tailwind CSS v4](https://tailwindcss.com) - A utility-first CSS framework
- [Vite](https://vitejs.dev/) - Next Generation Frontend Tooling
