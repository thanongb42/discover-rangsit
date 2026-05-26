# Design System — Discover Rangsit

## Brand Colors
| ชื่อ | Hex | ใช้ที่ |
|------|-----|--------|
| Primary Blue | `#0088CC` | ปุ่มหลัก, hero, accent |
| Primary Dark | `#006BA8` | hover state ปุ่ม |
| Darkest Blue | `#005A8E` | active/pressed |
| Background | `#F8FAFC` (slate-50) | page background |
| Text Main | `#1E293B` (slate-800) | หัวข้อหลัก |
| Text Sub | `#64748B` (slate-500) | text รอง |
| Border | `#E2E8F0` (slate-200) | card border |

## Typography
- **Font:** system-ui / Tailwind default (ไม่ใช้ Google Fonts พิเศษ)
- หัวข้อใหญ่: `text-3xl font-black`
- หัวข้อรอง: `text-xl font-bold`
- Body: `text-sm` หรือ `text-base`
- Caption/label: `text-xs font-bold uppercase tracking-widest`

## Layout
- Container: `container mx-auto px-4`
- Max width: Tailwind default container
- Grid cards: `grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4`
- Section padding: `py-10` หรือ `py-12`

## Components

### Button หลัก
```html
<button class="bg-[#0088CC] hover:bg-[#006BA8] text-white font-bold px-6 py-3 rounded-xl transition">
    Label
</button>
```

### Button รอง (outline)
```html
<button class="border border-slate-200 hover:border-slate-300 text-slate-700 font-bold px-4 py-2 rounded-xl transition">
    Label
</button>
```

### Card (Place)
```html
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-blue-100 transition-all duration-300 overflow-hidden">
    <!-- cover image h-32 -->
    <!-- content p-3 -->
</div>
```

### Badge / Tag
```html
<span class="inline-flex items-center gap-1 bg-blue-50 text-[#0088CC] text-xs font-bold px-3 py-1 rounded-full">
    Label
</span>
```

### Alert (SweetAlert2)
```js
Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'ข้อความ', timer: 2000 });
Swal.fire({ icon: 'error',   title: 'เกิดข้อผิดพลาด', text: 'ข้อความ' });
```

### Confirm Dialog
```js
const result = await Swal.fire({
    title: 'ยืนยัน?',
    text: 'ข้อความ',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ยืนยัน',
    cancelButtonText: 'ยกเลิก',
    confirmButtonColor: '#0088CC',
});
if (result.isConfirmed) { /* ... */ }
```

## Icons (Font Awesome 6)
- ใช้ class `fas fa-xxx` (solid) หรือ `far fa-xxx` (regular)
- ตัวอย่างที่ใช้บ่อย: `fa-search`, `fa-star`, `fa-map-marker-alt`, `fa-store`, `fa-plus`, `fa-edit`, `fa-trash`

## Hero Section
- Background: `bg-[#0088CC] text-white`
- Gradient overlay: `bg-gradient-to-br from-[#33AADC] to-[#005A8E] opacity-20`
- Padding: `py-20 px-4`

## Admin Panel
- Layout: sidebar + main content
- Header: `admin_header.php` (รวม sidebar)
- Footer: `admin_footer.php`
- Sidebar bg: dark (slate-900 / blue-900)

## SEO Metadata (ทุกหน้าต้องมี)
Controller ส่งใน `$data` array:
```php
'title'       => 'หัวข้อหน้า — Discover Rangsit',
'description' => 'คำอธิบาย 150-160 ตัวอักษร',
'keywords'    => 'keyword1, keyword2, ...',
'og_url'      => BASE_URL . '/path',
```
Header.php จะ render เป็น `<meta>` + OG + Twitter Card + canonical URL อัตโนมัติ

## Responsive Breakpoints (Tailwind)
| Prefix | ขนาด |
|--------|------|
| (none) | mobile first |
| `sm:` | 640px+ |
| `md:` | 768px+ |
| `lg:` | 1024px+ |
| `xl:` | 1280px+ |
