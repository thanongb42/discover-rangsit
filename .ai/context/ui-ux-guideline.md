# UI/UX Guideline — Discover Rangsit

Platform: City Directory / Smart City Platform
Audience: ประชาชน, ผู้ประกอบการ, เจ้าหน้าที่เทศบาล, ผู้บริหาร
Language: ภาษาไทยหลัก, ภาษาอังกฤษรอง (i18n ด้วย t('key'))

---

## STATUS KEY

- [LIVE] — ใช้งานอยู่ใน production แล้ว
- [PLANNED] — อยู่ใน roadmap vNext, ยังไม่ได้สร้าง

---

## BRAND

### Primary Colors

```
#0088CC  — Primary Blue (brand color หลัก)
#005A8E  — Dark Blue (hover state, secondary)
#33AADC  — Light Blue (gradient, highlight)
#006BA8  — Mid Blue (button hover)
```

### Gradient (ใช้บ่อย)

```css
/* Hero background */
background: linear-gradient(135deg, #33AADC, #005A8E);

/* KPI card top bar */
background: linear-gradient(to right, from-blue-600, to-blue-400);

/* Admin header brand icon */
background: linear-gradient(135deg, #1e3a8a, #0088CC);
```

### Text Colors (Tailwind)

```
text-gray-800   — หัวข้อหลัก
text-gray-600   — body text
text-gray-400   — subtext / label ย่อย
text-slate-800  — เนื้อหาบนพื้นขาว
text-white      — บน background สี
```

---

## CSS FRAMEWORK

Tailwind CSS (utility-first) — ไม่มี custom component library
ใช้ class โดยตรงใน PHP view ทุก file

ห้าม:
- เพิ่ม Bootstrap
- เพิ่ม CSS framework ใหม่
- สร้าง .css file ใหม่โดยไม่จำเป็น

---

## ICON SYSTEM

Font Awesome 6 Free
Prefix: `fas` (solid), `far` (regular), `fab` (brand)

ตัวอย่างที่ใช้บ่อย:
```
fa-store, fa-map-marked-alt, fa-search, fa-star, fa-heart,
fa-city, fa-chart-bar, fa-chart-line, fa-users, fa-cog,
fa-qrcode, fa-check-circle, fa-times-circle, fa-clock
```

---

## JAVASCRIPT LIBRARIES

| Library | Version | ใช้สำหรับ |
|---------|---------|----------|
| jQuery | CDN | DOM, AJAX |
| SweetAlert2 | CDN | Modal, Alert, Confirm |
| Chart.js | CDN | กราฟใน admin dashboard |
| Leaflet.js | CDN | แผนที่ |

---

## LAYOUT SYSTEM [LIVE]

### Public Layout

```
header.php → content → footer.php
```

File: `app/views/layouts/header.php` + `app/views/layouts/footer.php`

- Responsive: mobile-first
- Navbar สีขาว, sticky top
- Logo: rangsit-logo.png

### Admin Layout

```
admin_header.php → content → admin_footer.php
```

File: `app/views/layouts/admin_header.php` + `app/views/layouts/admin_footer.php`

- Sidebar navigation (desktop), hamburger (mobile)
- Sidebar มี group section: main / จัดการระบบ (admin only) / ธุรกิจของฉัน
- Active page highlight ด้วย `current_page` key ใน data
- Badge แสดงจำนวน pending บน menu item

### View Usage

```php
// Public
require_once APP_ROOT . '/app/views/layouts/header.php';
// ... content ...
require_once APP_ROOT . '/app/views/layouts/footer.php';

// Admin
require_once APP_ROOT . '/app/views/layouts/admin_header.php';
// ... content ...
require_once APP_ROOT . '/app/views/layouts/admin_footer.php';
```

ห้ามใช้ `BASE_PATH` — ต้องใช้ `APP_ROOT` เสมอ

---

## COMPONENTS [LIVE]

### KPI Card (Admin Dashboard)

Pattern จาก city_dashboard.php:

```html
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="h-1.5 bg-gradient-to-r from-blue-600 to-blue-400"></div>
    <div class="p-4">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center mb-3">
            <i class="fas fa-store text-white text-sm"></i>
        </div>
        <p class="text-2xl font-black text-gray-800 leading-none mb-1">1,234</p>
        <p class="text-xs font-bold text-gray-600">ธุรกิจทั้งหมด</p>
        <p class="text-[10px] text-gray-400 mt-0.5">1,100 อนุมัติแล้ว</p>
    </div>
</div>
```

Gradient options ที่ใช้:
```
from-blue-600 to-blue-400
from-violet-600 to-violet-400
from-sky-600 to-sky-400
from-rose-600 to-rose-400
from-amber-500 to-yellow-400
from-green-600 to-emerald-400
```

---

### Chart Container (Admin)

```html
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
        <i class="fas fa-chart-bar text-blue-500"></i> หัวข้อ
    </h3>
    <p class="text-xs text-gray-400 mb-4">คำอธิบาย</p>
    <canvas id="myChart" height="220"></canvas>
</div>
```

---

### Smart Economy KPI Banner

```html
<div class="mb-8 rounded-2xl p-6 border border-blue-100"
     style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
    <div class="flex items-center gap-2 mb-4">
        <i class="fas fa-trophy text-blue-600"></i>
        <h3 class="font-black text-blue-900 text-sm uppercase tracking-wider">หัวข้อ KPI</h3>
    </div>
    <!-- grid 4 cols -->
</div>
```

---

### Search Bar (Public)

Pill shape, rounded-[2rem] / rounded-[4rem]
Background: white, shadow-xl
Input: no border, no ring, text-slate-800 font-bold
Button: bg-[#006BA8] hover:bg-[#005A8E] text-white rounded-[3.5rem]

---

### Mood Chip (Search Quick Tag)

```html
<button class="flex items-center gap-1.5 bg-white/20 hover:bg-white/30
               text-white text-xs font-bold px-3 py-1.5 rounded-full
               border border-white/30 backdrop-blur transition">
    <i class="fas fa-coffee text-xs"></i> คาเฟ่
</button>
```

---

### Place Card (Public)

- rounded-2xl, shadow-sm, bg-white
- Cover image: aspect ratio สม่ำเสมอ
- Category badge: สี hex จาก categories.color
- Rating: ดาว Font Awesome + ตัวเลข
- Views: fa-eye + number_format

---

### SweetAlert2 Usage (LIVE)

```js
// Confirm dialog
Swal.fire({
    title: 'ยืนยัน?',
    text: 'คุณต้องการดำเนินการนี้หรือไม่',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#0088CC',
    cancelButtonColor: '#d33',
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก'
})

// Success toast
Swal.fire({ icon: 'success', title: 'สำเร็จ', timer: 1500, showConfirmButton: false })

// Error
Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: message })
```

---

### Admin Sidebar Menu Item

```php
// เพิ่ม item ใหม่ใน admin_header.php → $menu_groups['manage']['items']
['id' => 'my_feature', 'icon' => 'fa-qrcode', 'label' => 'ชื่อเมนู', 'url' => BASE_URL . '/admin/my-feature']

// Item ที่มี badge
['id' => 'claims', 'icon' => 'fa-handshake', 'label' => 'Claim ร้านค้า', 'url' => BASE_URL . '/admin/claims', 'badge' => $claim_count]
```

---

## PAGE STRUCTURE PATTERNS [LIVE]

### Admin Page Header

```html
<div class="mb-8">
    <div class="flex items-center gap-3 mb-1">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
             style="background:linear-gradient(135deg,#1e3a8a,#0088CC)">
            <i class="fas fa-icon text-white"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ชื่อหน้า</h1>
            <p class="text-gray-400 text-xs">คำอธิบาย · อัปเดต <?= date('d M Y H:i') ?></p>
        </div>
    </div>
</div>
```

---

### Data Table (Admin)

```html
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-4 py-3 text-left font-bold text-gray-600">คอลัมน์</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-gray-800">ข้อมูล</td>
            </tr>
        </tbody>
    </table>
</div>
```

---

### Status Badge

```html
<!-- approved / active -->
<span class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">อนุมัติ</span>

<!-- pending -->
<span class="px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">รอตรวจสอบ</span>

<!-- rejected -->
<span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">ไม่อนุมัติ</span>
```

---

### Form Input (Admin)

```html
<label class="block text-xs font-bold text-gray-600 mb-1">ชื่อฟิลด์</label>
<input type="text"
       class="w-full border border-gray-200 rounded-xl px-4 py-2.5
              text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-400">
```

---

## RESPONSIVE BREAKPOINTS

ใช้ Tailwind default:
```
sm  — 640px+
md  — 768px+
lg  — 1024px+
xl  — 1280px+
```

Grid pattern ที่ใช้บ่อย:
```html
grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6   <!-- KPI cards -->
grid grid-cols-1 lg:grid-cols-2                  <!-- Charts row -->
grid grid-cols-1 lg:grid-cols-3                  <!-- Charts 3-col -->
```

---

## CHART.JS PATTERNS [LIVE]

### Bar Chart

```js
new Chart(document.getElementById('myChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'จำนวน',
            data: <?= json_encode($counts) ?>,
            backgroundColor: 'rgba(0,136,204,0.7)',
            borderColor: '#0088CC',
            borderWidth: 0,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
```

### Doughnut / Pie Chart

```js
new Chart(document.getElementById('myChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($catLabels) ?>,
        datasets: [{ data: <?= json_encode($catCounts) ?>, backgroundColor: <?= json_encode($catColors) ?> }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { font: { size: 10 } } } }
    }
});
```

---

## WRITING RULES

- ใช้ภาษาไทยใน label, placeholder, button สำหรับ admin/operator
- ใช้ `t('key')` สำหรับ public-facing text (i18n)
- ห้ามใช้ emoji ในโค้ด
- ห้าม hardcode domain ใดๆ — ใช้ `BASE_URL` และ `APP_ROOT` เสมอ
- Output ผ่าน `htmlspecialchars()` ทุกครั้ง
- ใช้ `number_format()` กับตัวเลขที่แสดงผล

---

## LIVE UI PATTERNS [vNext — deployed 2026-05-30]

---

### QR Code Modal [LIVE — Phase 1]

`app/views/places/detail.php`, `app/views/user/my_businesses.php`

```
- Library: public/js/qrcode.min.js (qrcodejs 1.0.0, local file)
- API: new QRCode(element, { text, width:220, height:220, colorDark, colorLight })
- Button: "QR Code ร้านนี้"  icon: fa-qrcode  (sidebar info card)
- Modal: custom div z-[3500], backdrop click to close
- Actions: ดาวน์โหลด PNG, คัดลอก URL (feedback 2s)
- QR encodes: BASE_URL/place/{slug}
- my_businesses: shared modal per page, openBusinessQR(url, name)
```

---

### Business Claim [LIVE — Phase 1]

`app/views/places/detail.php`, `app/views/admin/claims.php`

```
- แสดงเมื่อ: logged in + place.owner_user_id IS NULL + claim_status = null
- Button: "ขอเป็นเจ้าของร้านนี้"  bg-blue-50 border-blue-200 text-[#0088CC]
- Pending badge: "อยู่ระหว่างรอการตรวจสอบ"  bg-amber-50 border-amber-200
- Rejected badge: "คำขอถูกปฏิเสธ"  bg-red-50 border-red-200
- Form modal z-[3500]: contact_name, phone, line_id, message
- AJAX POST /place/claim → JSON → Swal success/error → reload
- Admin: /admin/claims table with approve/reject + Swal confirm
```

---

### Executive Dashboard [LIVE — Phase 1]

URL: `/admin/dashboard/economic`  `app/views/admin/executive_dashboard.php`

```
- KPI 4 ช่อง: ธุรกิจใหม่เดือนนี้ (+% growth), อนุมัติ, รอ, สมาชิก
- Data completeness bars: cover/desc/phone/GPS/social/delivery (6 รายการ)
- Top 10 search keywords (30 วัน จาก search_logs)
- Top 10 places by views (thumbnail + category)
- Bar chart: ธุรกิจใหม่ 6 เดือน
- Doughnut: หมวดหมู่
- ใช้ try/catch ทุก query — fallback gracefully
```

---

### Place Events / Promotions [LIVE — Phase 2A]

`app/views/places/events.php` (owner), `app/views/places/detail.php` (public)

```
- Owner URL: /dashboard/events/{id}  (EventController::index, param $id)
- Section บน detail page: เหนือ Reviews, bg-orange-50 rounded-2xl
- แสดงเมื่อ: status=active AND (end_date IS NULL OR end_date >= TODAY)
- Badge NEW: ขึ้นเมื่อ start_date ภายใน 7 วัน
- Owner form modal: title, description, start_date, end_date, status
- AJAX POST /api/events/save + /api/events/delete
- Admin: /admin/events read-only table
- ปุ่มใน my_businesses: สีส้ม "โปรโมชั่น / กิจกรรม"
```

---

### Heatmap Analytics [LIVE — Phase 2B]

URL: `/admin/heatmap`  `app/views/admin/heatmap.php`

```
- Leaflet 1.9.4 + leaflet.heat 0.2.0 โหลดในหน้าเอง (admin layout ไม่มี)
- L.heatLayer gradient: blue→lime→yellow→red
- Time filter buttons: 7d / 30d / 90d / ทั้งหมด (AJAX reload)
- API: GET /api/admin/heatmap-data?days=N → { points: [[lat,lng,intensity]], stats }
- KPI: total_views, active_places, peak_hour
- Map center: [14.0, 100.75] zoom 13 (Rangsit)
```

---

### Dashboard Link in Navbar [LIVE]

`app/views/layouts/header.php` — แสดงเมื่อ login แล้วเท่านั้น

```
- admin/operator → /admin/dashboard/economic
- member → /dashboard
- icon: fa-chart-pie
- ปรากฏทั้ง desktop navbar และ user avatar dropdown
```

---

## IMAGE PATHS

| Context | Pattern |
|---------|---------|
| Public view | `BASE_URL . '/uploads/covers/' . $filename` |
| Admin view | `BASE_URL . '/../uploads/covers/' . $filename` |
| Gallery | `/uploads/gallery/` |
| Avatar | `/uploads/avatars/` |

Upload disk path:
```
public/uploads/covers/
public/uploads/gallery/
public/uploads/avatars/
```
