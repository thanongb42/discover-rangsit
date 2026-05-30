# Database Schema — Discover Rangsit

Engine: MySQL / MariaDB
Charset: utf8mb4 / utf8mb4_unicode_ci
Access: PDO + prepared statements only

---

## STATUS KEY

- [LIVE] — ใช้งานอยู่ใน production แล้ว
- [PLANNED] — อยู่ใน roadmap vNext, ยังไม่ได้สร้าง

---

## CURRENT TABLES [LIVE]

---

### places

ตารางหลักสำหรับธุรกิจและสถานที่ทั้งหมด

```sql
places
- id              INT AUTO_INCREMENT PRIMARY KEY
- name            VARCHAR(255) NOT NULL
- slug            VARCHAR(255) UNIQUE NOT NULL
- place_type      ENUM('business','facility') DEFAULT 'business'
- category_id     INT NULL (FK → categories.id)
- description     TEXT NULL
- address         VARCHAR(500) NULL
- latitude        DECIMAL(10,8) NULL
- longitude       DECIMAL(11,8) NULL
- phone           VARCHAR(50) NULL
- website         VARCHAR(500) NULL
- facebook        VARCHAR(500) NULL
- line            VARCHAR(255) NULL
- line_qr         VARCHAR(255) NULL
- x               VARCHAR(255) NULL
- instagram       VARCHAR(255) NULL
- youtube         VARCHAR(500) NULL
- tiktok          VARCHAR(500) NULL
- cover_image     VARCHAR(255) NULL
- owner_user_id   INT NULL (FK → users.user_id)
- status          ENUM('pending','approved','rejected','trash') DEFAULT 'pending'
- views_count     INT DEFAULT 0
- rating_avg      DECIMAL(3,2) DEFAULT 0
- rating_count    INT DEFAULT 0
- created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
- updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```

Notes:
- `place_type = 'facility'` → ปักหมุดโดย admin ไม่มี owner, ไม่แสดงหน้าแรก
- `status = 'trash'` → soft delete, ยังอยู่ใน DB
- slug รองรับ Thai Unicode (ตั้งแต่ commit fd63f0c)

---

### categories

หมวดหมู่ธุรกิจ

```sql
categories
- id      INT AUTO_INCREMENT PRIMARY KEY
- name    VARCHAR(100) NOT NULL
- slug    VARCHAR(100) NULL UNIQUE
- icon    VARCHAR(100) NULL  (Font Awesome class เช่น fa-utensils)
- color   VARCHAR(20)  NULL  (hex เช่น #FF6B6B)
- created_at DATETIME DEFAULT CURRENT_TIMESTAMP
```

---

### users

ผู้ใช้งานระบบ

```sql
users
- user_id            INT AUTO_INCREMENT PRIMARY KEY
- username           VARCHAR(100) UNIQUE NOT NULL
- first_name         VARCHAR(100) NULL
- last_name          VARCHAR(100) NULL
- email              VARCHAR(255) UNIQUE NOT NULL
- password           VARCHAR(255) NULL  (bcrypt, NULL สำหรับ OAuth)
- phone              VARCHAR(50) NULL
- role               ENUM('admin','operator','member') NOT NULL DEFAULT 'member'
- status             ENUM('active','inactive','suspended') DEFAULT 'active'
- prefix_id          INT NULL (FK → prefixes.id)
- department_id      INT NULL (FK → departments.id)
- position           VARCHAR(255) NULL
- profile_image      VARCHAR(255) NULL
- line_user_id       VARCHAR(100) NULL UNIQUE
- line_display_name  VARCHAR(255) NULL
- line_picture_url   VARCHAR(500) NULL
- line_linked_at     DATETIME NULL
- google_id          VARCHAR(100) NULL UNIQUE
- google_picture_url VARCHAR(500) NULL
- last_login         DATETIME NULL
- created_at         DATETIME DEFAULT CURRENT_TIMESTAMP
- updated_at         DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```

Notes:
- role จากโค้ด AdminController: ใช้ `admin` / `operator` / (member อื่นๆ)
- User.php::getStats() นับ role = 'admin', 'staff', 'user' — ปัจจุบัน role อาจมีหลาย value ใน DB
- View `v_users_full` ถูกใช้แทน `users` ในหลาย query (JOIN prefix + department)

---

### v_users_full

VIEW (ไม่ใช่ตาราง) — JOIN users + prefixes + departments

ใช้ใน User model: `findById`, `findUserByEmail`, `findByUsername`, `findByLineId`, `getAllUsers`

---

### prefixes

คำนำหน้าชื่อ (นาย, นาง, นางสาว, ดร. ฯลฯ)

```sql
prefixes
- id            INT AUTO_INCREMENT PRIMARY KEY
- name          VARCHAR(50) NOT NULL
- display_order INT DEFAULT 0
- is_active     TINYINT(1) DEFAULT 1
```

---

### departments

แผนกงานของเจ้าหน้าที่เทศบาล

```sql
departments
- id              INT AUTO_INCREMENT PRIMARY KEY
- department_name VARCHAR(255) NOT NULL
- status          ENUM('active','inactive') DEFAULT 'active'
- created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
```

---

### ratings

รีวิวและคะแนนของแต่ละสถานที่

```sql
ratings
- id         INT AUTO_INCREMENT PRIMARY KEY
- place_id   INT NOT NULL (FK → places.id)
- user_id    INT NOT NULL (FK → users.user_id)
- rating     TINYINT NOT NULL (1–5)
- comment    TEXT NULL
- created_at DATETIME DEFAULT CURRENT_TIMESTAMP
```

Notes:
- เมื่อเพิ่ม rating → UPDATE places SET rating_avg, rating_count อัตโนมัติ

---

### place_images

รูปภาพ gallery ของสถานที่

```sql
place_images
- id          INT AUTO_INCREMENT PRIMARY KEY
- place_id    INT NOT NULL (FK → places.id)
- image_path  VARCHAR(255) NOT NULL
- created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
```

---

### place_views

บันทึกการเข้าชมแต่ละสถานที่ (สำหรับ analytics)

```sql
place_views
- id          INT AUTO_INCREMENT PRIMARY KEY
- place_id    INT NOT NULL (FK → places.id)
- user_id     INT NULL (FK → users.user_id)
- ip_address  VARCHAR(45) NULL
- viewed_at   DATETIME DEFAULT CURRENT_TIMESTAMP
```

Notes:
- ทุกครั้งที่ open place detail → INSERT + UPDATE places.views_count
- ใช้ใน: getEngagementByMonth, getViewsByPlace, getAlsoViewed, getHotNow, getPersonalizedScore

---

### place_likes

ระบบถูกใจสถานที่

```sql
place_likes
- id         INT AUTO_INCREMENT PRIMARY KEY
- place_id   INT NOT NULL (FK → places.id)
- user_id    INT NOT NULL (FK → users.user_id)
- created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
- UNIQUE KEY unique_like (place_id, user_id)
- KEY idx_place_id (place_id)
```

---

### place_delivery_links

ลิงก์ Delivery Platform ของแต่ละร้าน

```sql
place_delivery_links
- id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- place_id      INT UNSIGNED NOT NULL
- platform      VARCHAR(32) NOT NULL  (grabfood, foodpanda, lineman, shopeefood, robinhood, ...)
- url           VARCHAR(500) NOT NULL
- display_label VARCHAR(120) NULL
- is_active     TINYINT(1) DEFAULT 1
- sort_order    SMALLINT DEFAULT 0
- click_count   INT UNSIGNED DEFAULT 0
- created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
- updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
- UNIQUE KEY uniq_place_platform (place_id, platform)
```

---

### place_delivery_clicks

Log การคลิก Delivery link

```sql
place_delivery_clicks
- id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- place_id   INT UNSIGNED NOT NULL
- platform   VARCHAR(32) NOT NULL
- ip_hash    CHAR(64) NULL
- user_agent VARCHAR(255) NULL
- referrer   VARCHAR(500) NULL
- clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
- KEY idx_place_platform (place_id, platform)
- KEY idx_clicked_at (clicked_at)
```

---

### search_logs

บันทึกคำค้นหา (มีอยู่แล้วจาก Place::logSearch)

```sql
search_logs
- id           INT AUTO_INCREMENT PRIMARY KEY
- keyword      VARCHAR(255) NOT NULL
- result_count INT DEFAULT 0
- user_id      INT NULL
- ip_address   VARCHAR(45) NULL
- created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
```

Notes:
- `Place::logSearch()` INSERT ทุกครั้งที่มีการค้นหาผ่าน /api/search
- ยังไม่มี index บน keyword — ต้องเพิ่มเมื่อ volume สูง

---

### site_visits

บันทึก page view ระดับ site (Analytics model)

```sql
site_visits
- id          INT AUTO_INCREMENT PRIMARY KEY
- ip_address  VARCHAR(45) NOT NULL
- user_agent  VARCHAR(500) NULL
- page_url    VARCHAR(500) NOT NULL
- user_id     INT NULL
- visited_at  DATETIME DEFAULT CURRENT_TIMESTAMP
```

Notes:
- มี throttle: ไม่ log ซ้ำ IP + URL ภายใน 1 ชั่วโมง

---

### activity_logs

Log การกระทำในระบบ (admin audit trail)

```sql
activity_logs
- id          INT AUTO_INCREMENT PRIMARY KEY
- user_id     INT NULL (FK → users.user_id)
- action      VARCHAR(100) NOT NULL
- description TEXT NULL
- page_url    VARCHAR(500) NULL
- ip_address  VARCHAR(45) NULL
- user_agent  VARCHAR(500) NULL
- created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
```

---

### user_interests

คะแนนความสนใจของ user แต่ละหมวดหมู่ (personalization)

```sql
user_interests
- id          INT AUTO_INCREMENT PRIMARY KEY
- user_id     INT NOT NULL (FK → users.user_id)
- category_id INT NOT NULL (FK → categories.id)
- score       INT DEFAULT 1  (เพิ่มทุกครั้งที่เข้าชม place ในหมวดนั้น)
- updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
- UNIQUE KEY unique_user_cat (user_id, category_id)
```

---

## LIVE TABLES [vNext — deployed 2026-05-30]

---

### business_claim_requests [LIVE — Phase 1]

คำขอ claim ความเป็นเจ้าของร้าน

```sql
business_claim_requests
- id           INT AUTO_INCREMENT PRIMARY KEY
- place_id     INT NOT NULL (FK → places.id)
- user_id      INT NOT NULL (FK → users.user_id)
- contact_name VARCHAR(150) NOT NULL
- phone        VARCHAR(30) NOT NULL
- line_id      VARCHAR(100) NULL
- message      TEXT NULL
- status       ENUM('pending','approved','rejected') DEFAULT 'pending'
- reviewed_by  INT NULL (FK → users.user_id)
- reviewed_at  DATETIME NULL
- created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
- updated_at   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
- UNIQUE KEY uq_place_user (place_id, user_id)
```

Migration: `database/20260530_business_claim_requests.sql`
Model: `app/models/Claim.php`
Controller: `app/controllers/ClaimController.php`
Admin view: `app/views/admin/claims.php`

---

### place_events [LIVE — Phase 2A]

โปรโมชั่นและกิจกรรมของร้านค้า

```sql
place_events
- id          INT AUTO_INCREMENT PRIMARY KEY
- place_id    INT NOT NULL (FK → places.id)
- title       VARCHAR(255) NOT NULL
- description TEXT NULL
- start_date  DATE NOT NULL
- end_date    DATE NULL
- status      ENUM('active','inactive') DEFAULT 'active'
- created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
- updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
- KEY idx_place_id (place_id)
- KEY idx_end_date (end_date)
```

Migration: `database/20260530_place_events.sql`
Model: `app/models/PlaceEvent.php`
Controller: `app/controllers/EventController.php`
Owner view: `app/views/places/events.php`
Admin view: `app/views/admin/events.php`

## PLANNED TABLES [vNext Roadmap — future]

---

## RELATIONSHIPS SUMMARY

```
categories      1 ←→ N  places
users           1 ←→ N  places            (owner_user_id)
places          1 ←→ N  ratings
places          1 ←→ N  place_images
places          1 ←→ N  place_views
places          1 ←→ N  place_likes
places          1 ←→ N  place_delivery_links
places          1 ←→ N  place_delivery_clicks
users           N ←→ N  place_likes       (via place_likes)
users           N ←→ N  categories        (via user_interests)
users           1 ←→ N  ratings
users           1 ←→ N  business_claim_requests  [PLANNED]
places          1 ←→ N  business_claim_requests  [LIVE]
places          1 ←→ N  place_events             [LIVE]
```

---

## MIGRATION FILES

```
database/migrations/2026_delivery_links.sql   — place_delivery_links + place_delivery_clicks
database/migrate_category_slugs.sql           — เพิ่ม slug ให้ categories
database/migrate_place_type.sql               — เพิ่ม place_type ENUM ใน places
database/fix_bad_slugs.sql                    — แก้ slug ที่ผิดรูปแบบ
```

Planned:
```
database/20260530_business_claim_requests.sql  [PLANNED]
```

---

## NAMING CONVENTION

- ตาราง: snake_case plural (`place_views`, `activity_logs`)
- คอลัมน์: snake_case (`created_at`, `owner_user_id`)
- Migration file: `YYYYMMDD_feature_name.sql`
- ห้ามใช้ destructive migration (DROP TABLE, DROP COLUMN) บน production
