# Discover Rangsit — Feature Summary
# เทศบาลนครรังสิต · Smart City Economic Platform
# อัปเดต: 2026-05-31 | Branch: feature/discover-vnext-smart-city

---

# Roadmap Status Memory — 2026-05-31

## Completed / Implemented

| Feature | Status | Files / Notes |
|---|---|---|
| 3D Map Page | Done | Added `/3dmap`, `app/views/map/three_d.php`, `public/js/map-3d.js`, mobile layer toggle, map icon popup behavior, 2D/3D menu toggle. |
| 3D Map Prompt Docs | Done | `.ai/3d.md`, `.ai/cdp-3dmap-nextjs-prompt.md` for future Next.js/CDP map work. |
| Tourism Page | Done | Added `/tourism` via `CivicController::tourism()`, `app/views/civic/tourism.php`, header nav, sitemap. |
| Events Calendar | Done | Added `/events` via `CivicController::events()`, `app/views/civic/events.php`, uses public upcoming events from `place_events`. |
| Open Data | Done | Added `/open-data`, `/open-data/places.json`, `/open-data/places.csv`; stats and export methods added to `Place`/`PlaceEvent`. |
| Refill City Page | Done | Added `/refill-city`, Leaflet + MarkerCluster map, search/list/sidebar, popup QR image support. |
| Refill City DB Sync | Done | Added `WaterKiosk` model, `database/import_water_kiosks_facility_points.sql`, `database/update_refill_city_after_import.sql`. Production should use update-only SQL because data was already imported once. |
| Facility Map/Search Support | Done | `Place::getMapApproved()` and `/api/places` now include `facility` with `business` for map/search use. |
| QR Code Handling | Done | Refill City uses `places.line_qr` / `water_kiosks.qrcode_img` filename convention and looks in `public/uploads/gallery/`; fallback reads `RSCxxxx` from slug/name/line_qr/address. |

## Production Update Checklist

| Item | Status | Notes |
|---|---|---|
| Upload Civic pages/controllers/models | To verify on production | Upload changed app files when deploying roadmap pages. |
| Import Refill City update SQL | Needed if not yet run | Use `database/update_refill_city_after_import.sql` only. Do not re-import `database/import_water_kiosks_facility_points.sql` unless installing fresh. |
| QR images | Done by user / verify | Files moved to `public/uploads/gallery/qrcode_RSC0001.png ... qrcode_RSC0030.png`. |
| Cover images | Deferred | User will update cover images manually later. |

## Deferred / Not Yet Done

| Feature | Status | Reason / Next Step |
|---|---|---|
| Complaint System | Deferred | Must connect with Rangsit City App. Waiting for their API before integration. |
| QGIS + DEM + OSM 3D Terrain | Later | Better free path than TopoExport, but intentionally postponed. |
| TopoExport-like Export | Later | TopoExport is not free; revisit after QGIS/DEM/OSM workflow is chosen. |
| Refill City Admin Workflow | Not done | Current work imports/syncs data and public map display. Future work could add admin CRUD/import UI. |
| Production QA | Pending | Verify routes, QR popup image, search, sitemap, and `/api/places` after FTP/import. |

## TAGLINE

> "ค้นพบของดีรังสิต — แพลตฟอร์มเศรษฐกิจดิจิทัลของเมืองรังสิต"

---

## สำหรับประชาชน และนักท่องเที่ยว

| Feature | รายละเอียด |
|---------|-----------|
| ค้นหาธุรกิจ | ค้นหาร้านอาหาร คาเฟ่ สถานที่ท่องเที่ยว และบริการในรังสิตได้ทันที |
| หมวดหมู่ธุรกิจ | กรองตามประเภท เช่น อาหาร คาเฟ่ บริการ สุขภาพ ช้อปปิ้ง |
| แผนที่เมือง | แผนที่ interactive ด้วย Leaflet แสดงตำแหน่งธุรกิจทั้งหมดพร้อม cluster |
| Heatmap แผนที่ | แสดงจุดธุรกิจยอดนิยมบนแผนที่แบบ heatmap |
| ค้นหาตามอารมณ์ | Mood Chips เช่น คาเฟ่ อาหาร วัด ธรรมชาติ ครอบครัว ช้อปปิ้ง |
| ค้นหาใกล้ฉัน | ระบุ radius แล้วหาธุรกิจในรัศมีที่เลือก (GPS) |
| ธุรกิจยอดนิยม | ดูอันดับธุรกิจ trending ประจำสัปดาห์ |
| ข้อมูลคุณภาพอากาศ | PM2.5 real-time จากสถานีวัดในพื้นที่รังสิต |
| พยากรณ์อากาศ | อุณหภูมิและความชื้นปัจจุบันในเมืองรังสิต |
| รีวิวและให้คะแนน | ให้ดาว 1–5 และเขียนรีวิวธุรกิจที่เคยใช้บริการ |
| กดถูกใจ | Like ร้านที่ชอบ ดูว่าใครกดถูกใจบ้าง |
| แนะนำให้คุณ | AI แนะนำสถานที่ตามพฤติกรรมการค้นหาของคุณ |
| คนดูด้วยกัน | ดูร้านที่คนอื่นเข้าชมพร้อมกันกับคุณ |
| ร้านในหมวดเดียวกัน | แนะนำร้านประเภทเดียวกันใกล้เคียง |
| สั่งอาหาร Delivery | ลิงก์สั่งตรงผ่าน GrabFood, Foodpanda, LINE MAN, Shopee Food |
| นำทาง | เปิด Google Maps นำทางไปยังสถานที่ได้ทันที |
| LINE QR ร้าน | สแกน QR เพื่อเพิ่มเพื่อน LINE ของร้านค้า |
| QR Code ร้านค้า | สแกน QR เพื่อเข้าดูหน้าร้านบนมือถือ / แชร์ต่อ |
| โปรโมชั่นและกิจกรรม | ดูโปรโมชั่นปัจจุบันของร้านค้าที่กำลัง active |
| รองรับ 2 ภาษา | ไทย และ English สลับได้ทันที |
| SEO-ready | มี Canonical, Sitemap, Structured Data ทุกหน้า |

---

## สำหรับผู้ประกอบการ

| Feature | รายละเอียด |
|---------|-----------|
| ลงทะเบียนร้านฟรี | เพิ่มธุรกิจของคุณได้ฟรี ไม่มีค่าใช้จ่าย |
| Login หลายช่องทาง | Email, LINE Login, Google Login |
| จัดการข้อมูลร้าน | แก้ไขชื่อ, ที่อยู่, เบอร์โทร, เว็บไซต์, Social Media ได้เอง |
| อัปโหลดรูปภาพ | รูปปก + คลังภาพสูงสุดไม่จำกัด |
| เชื่อม Delivery Platform | เพิ่มลิงก์ GrabFood, Foodpanda, LINE MAN ฯลฯ |
| Analytics ส่วนตัว | ดูกราฟ Views, Likes, Ratings ของร้านตัวเองรายวัน/รายเดือน |
| QR Code ดาวน์โหลด | ดาวน์โหลด QR Code ร้าน พิมพ์ติดหน้าร้านหรือสื่อโปรโมท |
| โปรโมชั่นและกิจกรรม | ลงโปรโมชั่นส่วนลด กิจกรรม หรือ event พิเศษได้เอง |
| Claim ร้านค้า | ร้านที่มีข้อมูลอยู่แล้วสามารถยื่นขอเป็นเจ้าของได้ |
| ธุรกิจของฉัน | จัดการร้านทุกสาขาในที่เดียว |

---

## สำหรับเจ้าหน้าที่เทศบาล (Admin / Operator)

| Feature | รายละเอียด |
|---------|-----------|
| Executive Dashboard | KPI ภาพรวมเศรษฐกิจเมือง: ธุรกิจใหม่, growth rate, data completeness |
| City Dashboard | สถิติรวม: views, likes, reviews, delivery adoption, engagement trend |
| Heatmap Analytics | แผนที่ความหนาแน่นการเข้าชม แยกตาม 7/30/90 วัน หรือทั้งหมด |
| คำค้นหายอดนิยม | วิเคราะห์ว่าประชาชนกำลังค้นหาอะไรในเมือง |
| ความครบถ้วนข้อมูล | % ร้านที่มีรูป / GPS / โทร / Social / Delivery App |
| อนุมัติ / ปฏิเสธ ธุรกิจ | ตรวจสอบและควบคุมคุณภาพข้อมูลก่อนแสดงผล |
| Business Claim Review | ตรวจสอบและอนุมัติคำขอ claim ร้านค้าจากผู้ประกอบการ |
| จัดการหมวดหมู่ | เพิ่ม / แก้ไข / ลบ หมวดหมู่ธุรกิจพร้อมไอคอนและสี |
| จัดการผู้ใช้งาน | เพิ่ม / แก้ไข / ระงับ บัญชีผู้ใช้ทั้งหมด |
| ตั้งค่าแผนที่ | กำหนด center, zoom, layer ของแผนที่เมือง |
| Activity Logs | บันทึก audit trail ทุกการกระทำในระบบ |
| Delivery ครบ | ดูภาพรวมการใช้งาน Delivery platform ของร้านทั้งหมด |
| สำรอง / กู้คืน DB | Admin สามารถ backup/restore ฐานข้อมูลได้ |

---

## Smart City Capabilities

| ความสามารถ | รายละเอียด |
|-----------|-----------|
| เศรษฐกิจฐานราก | ส่งเสริม SME และร้านค้าชุมชนในรังสิตให้เข้าถึงดิจิทัล |
| Data-driven Policy | Dashboard ช่วยผู้บริหารเทศบาลตัดสินใจเชิงนโยบายจากข้อมูลจริง |
| GIS-ready | ทุกธุรกิจมีพิกัด GPS ใช้ต่อยอด Spatial Analysis ได้ทันที |
| PM2.5 Integration | ข้อมูลคุณภาพอากาศ real-time บนหน้าแรกและแผนที่เมือง |
| Search Analytics | วิเคราะห์ความต้องการของประชาชนจาก search behavior |
| Personalization AI | ระบบแนะนำสถานที่แบบ Collaborative Filtering + Interest Scoring |
| Open Platform | รองรับการขยายเพิ่ม Complaint System, Tourism, Open Data ในอนาคต |

---

## ตัวเลขสำคัญ (สำหรับ Poster)

```
ระบบรองรับ
- ธุรกิจและสถานที่ในเมืองรังสิต
- หมวดหมู่ธุรกิจหลายประเภท
- Delivery Platform 6+ แพลตฟอร์ม
- แผนที่ interactive ครอบคลุมพื้นที่เทศบาลนครรังสิต
- รองรับ 2 ภาษา: ไทย / อังกฤษ
- Login 3 ช่องทาง: Email / LINE / Google
```

---

## สรุปสั้น (สำหรับ Headline Poster)

```
DISCOVER RANGSIT
แพลตฟอร์มเศรษฐกิจดิจิทัลเมืองรังสิต

ค้นหาร้านง่าย · ลงทะเบียนฟรี · วิเคราะห์เมืองได้
โดยเทศบาลนครรังสิต
discover.rangsitcity.go.th
```

---

## Feature Groups (สำหรับ Infographic)

### กลุ่มที่ 1 — ค้นหาและสำรวจ
ค้นหาธุรกิจ · แผนที่เมือง · Heatmap · ค้นหาใกล้ฉัน · Trending · หมวดหมู่

### กลุ่มที่ 2 — ข้อมูลธุรกิจ
รีวิว · ถูกใจ · แกลเลอรี · Delivery Links · นำทาง · LINE QR · QR Code · โปรโมชั่น

### กลุ่มที่ 3 — สำหรับผู้ประกอบการ
ลงทะเบียนร้านฟรี · จัดการข้อมูล · Analytics · Claim ร้าน · QR Download

### กลุ่มที่ 4 — Smart City Analytics
Executive Dashboard · City Dashboard · Heatmap Analytics · Search Trends · Data Completeness

### กลุ่มที่ 5 — สิ่งแวดล้อมเมือง
PM2.5 Real-time · พยากรณ์อากาศ · GIS-ready

---

## BACKLOG — Feature ที่ยังไม่ได้ทำ (มาต่อพรุ่งนี้)

### Phase 2 — เหลือ 1 feature

| Feature | รายละเอียด | Priority |
|---------|-----------|---------|
| Feature 5 — AI Search Ready | เตรียม architecture: SearchService, SemanticSearchService, RecommendationService — ยังไม่ integrate AI จริง แค่วาง structure | ต่ำ |

---

### Phase 3 — Smart City Platform (ยังไม่เริ่ม)

| Feature | รายละเอียด | Priority |
|---------|-----------|---------|
| Complaint System | ระบบรับเรื่องร้องเรียนจากประชาชน | สูง |
| Tourism Page | หน้ารวมสถานที่ท่องเที่ยวในเมืองรังสิต | กลาง |
| Events Calendar | ปฏิทินกิจกรรมเมือง (ต่อยอดจาก place_events) | กลาง |
| Open Data | เปิดข้อมูลสถิติเมืองให้สาธารณะ | ต่ำ |
| Refill City | จุดเติมน้ำสะอาดในเมือง | ต่ำ |
| Smart Water | ข้อมูลคุณภาพน้ำ | ต่ำ |

---

### Housekeeping (ต้องทำก่อน merge)

| งาน | หมายเหตุ |
|-----|---------|
| Merge branch `feature/discover-vnext-smart-city` → `Version-Beta01` | ยังไม่ได้ merge |
| Push to GitHub | backup code ขึ้น remote |
# Backlog Update — 2026-05-31

## Features To Keep In Next Phase

| Feature | Status | Note |
|---------|--------|------|
| Tourism Page | Todo | หน้ารวมสถานที่ท่องเที่ยวในเมืองรังสิต |
| Events Calendar | Todo | ปฏิทินกิจกรรมเมือง ต่อยอดจากข้อมูล `place_events` |
| Open Data | Todo | เปิดข้อมูลสถิติ/ข้อมูลเมืองให้ประชาชนและหน่วยงานนำไปใช้ต่อ |
| Refill City | Todo | จุดเติมน้ำสะอาดหรือจุดบริการสาธารณะในเมือง |

## Deferred / Waiting For External API

| Feature | Status | Note |
|---------|--------|------|
| Complaint System | Deferred | ต้องเชื่อมกับ Rangsit City App และรอทีมที่เกี่ยวข้องเขียน API ให้ก่อน จึงค่อยเริ่ม integrate |

## Refill City Import Notes — 2026-05-31

- ใช้ข้อมูลจาก `water_kiosks` ของระบบ iService
- SQL สำหรับ production: `database/import_water_kiosks_facility_points.sql`
- Sync เป็น `places.place_type = facility`
- Category ที่ใช้: `refill-city` / `Refill City`

---
