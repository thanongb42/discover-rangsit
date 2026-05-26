# Skill Stack — Discover Rangsit

## Backend
- **PHP 8+** — custom MVC framework (no Laravel/Symfony)
- **MySQL** via PDO — `Database` class ใน `app/core/Database.php`
- **XAMPP** สำหรับ local dev, shared hosting สำหรับ production

## Frontend
- **Tailwind CSS** (CDN) — utility-first styling
- **Font Awesome 6** (CDN) — icons ทั้งหมด
- **SweetAlert2 (Swal)** — modal/alert/confirm dialogs
- **Vanilla JS** — ไม่ใช้ framework (React/Vue)
- **Fetch API** — AJAX calls ทั้งหมด

## Auth
- **LINE Login** (OAuth2) — primary login สำหรับ user
- **Google OAuth2** — secondary login
- **Session PHP** — จัดการ login state

## SEO & Analytics
- **Google Analytics 4** (`G-99N779E4B7`) — tracking
- **Google Search Console** — indexing monitoring
- **Sitemap** — `public/sitemap.php` (bypass router)
- **JSON-LD Structured Data** — Schema.org markup ในทุกหน้า
- **Open Graph / Twitter Card** — social sharing meta tags

## External APIs
- **LINE Messaging API** — LINE Login callback
- **Google OAuth** — Google Login callback

## DevOps / Deploy
- **Git** — version control, branch `Version-Beta01`
- **GitHub** — `https://github.com/thanongb42/discover-rangsit.git`
- **FTP** — deploy ไฟล์ขึ้น production ด้วยมือ (ไม่มี CI/CD)
- **phpMyAdmin** — รัน SQL migration บน production

## Database Migration
- เขียน SQL ไว้ใน `database/*.sql`
- ต้องรันผ่าน phpMyAdmin บน production ด้วยมือ
- ไม่มี migration runner อัตโนมัติ
