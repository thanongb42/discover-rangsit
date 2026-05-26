# Engineering Standards — Discover Rangsit

## Project Structure
```
app/
  controllers/   — XxxController.php (extends Controller)
  models/        — Xxx.php (extends Model)
  views/
    layouts/     — header.php, footer.php, admin_header.php, admin_footer.php
    home/        — public pages
    places/      — place-related views
    admin/       — admin panel views
    user/        — user dashboard views
    auth/        — login/register views
  core/          — Router, Controller, Model, Database
  helpers/       — lang_helper.php, DeliveryPlatforms.php
config/
  config.php     — constants, DB, BASE_URL (gitignored)
  lang/th.php    — Thai strings
  lang/en.php    — English strings
public/          — web root (.htaccess routes all to index.php)
routes.php       — all route definitions
```

## Routing
- ทุก route นิยามใน `routes.php` ผ่าน `$router->get()` / `$router->post()`
- URL params ใช้ `{slug}` syntax: `$router->get('/category/{slug}', 'CategoryController', 'index')`
- Controller method รับ param ตามชื่อ: `public function index($slug)`

## Controller Pattern
```php
class XxxController extends Controller {
    public function index($param = null) {
        $model = $this->model('ModelName');
        $data  = $model->getAll();
        $this->view('folder/viewname', [
            'title'       => 'Page Title',
            'description' => 'SEO description',
            'key'         => $data,
        ]);
    }
}
```

## View Pattern
```php
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<!-- content -->
<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
```
- **ใช้ `APP_ROOT` เสมอ** — ห้ามใช้ `BASE_PATH` (ไม่มีในโปรเจคนี้)
- Admin views ใช้ `admin_header.php` + `admin_footer.php`
- `$data` array จาก controller เข้าถึงใน view ได้ทันที

## Constants (config.php)
| Constant | ค่า |
|----------|-----|
| `APP_ROOT` | absolute path ของ project root |
| `BASE_URL` | URL ของ site (ไม่มี trailing slash) |
| `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME` | database credentials |

## Image URL Pattern
- **Public views:** `BASE_URL . '/uploads/covers/filename'`
- **Admin views:** `BASE_URL . '/../uploads/covers/filename'`

## Language / i18n
- ใช้ `t('key.subkey')` helper สำหรับ text ทั้งหมด
- Keys นิยามใน `config/lang/th.php` และ `config/lang/en.php`
- ตรวจภาษาปัจจุบัน: `currentLang()`, `isLang('th')`

## API Endpoints (AJAX)
- ทุก API อยู่ใน `ApiController.php`
- GET endpoints: `/api/places`, `/api/search`, `/api/categories`
- POST endpoints ส่ง JSON response: `['success' => true, 'data' => ...]`
- Frontend ใช้ `fetch()` + `JSON.parse()`

## User Roles
| Role | สิทธิ์ |
|------|--------|
| `admin` | จัดการทุกอย่าง |
| `operator` | จัดการ places |
| `member` | เพิ่ม/แก้ร้านตัวเอง |

## Deploy Checklist
1. `git add` + `git commit` + `git push`
2. FTP ไฟล์ PHP ที่เปลี่ยนขึ้น production
3. FTP `config/config.php` (gitignored — ทุกครั้ง)
4. รัน SQL migration ผ่าน phpMyAdmin ถ้ามี schema เปลี่ยน

## SQL Migration
- เขียนไฟล์ใหม่ใน `database/migrate_xxx.sql`
- รันบน production ผ่าน phpMyAdmin import
- ต้องทดสอบบน local ก่อนเสมอ

## Git Convention
- Branch หลัก: `Version-Beta01`
- Main branch: `main`
- Commit message: ภาษาอังกฤษ format `Type: short description`
  - `Feature:`, `Fix:`, `SEO:`, `Refactor:`, `Docs:`
