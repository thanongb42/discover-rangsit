-- DISCOVER RANGSIT - CLEAN UP SCRIPT
-- ใช้สำหรับล้างข้อมูลรีวิว คะแนนดาว และสถิติต่างๆ เพื่อเริ่มต้นใช้งานจริง

SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;

-- 1. ลบรายการรีวิวและคะแนนดาวทั้งหมด
DELETE FROM `ratings`;

-- 2. รีเซ็ตค่าเฉลี่ยและจำนวนรีวิวในตารางสถานที่ให้เป็น 0
UPDATE `places` SET `rating_avg` = 0.00, `rating_count` = 0;

-- 3. ลบประวัติการเข้าชมสถานที่ (รายครั้ง)
DELETE FROM `place_views`;

-- 4. รีเซ็ตยอดเข้าชมรวมของแต่ละสถานที่ให้เป็น 0
UPDATE `places` SET `views_count` = 0;

-- 5. ลบสถิติผู้เข้าชมเว็บไซต์ทั้งหมด (Visitor Counter)
DELETE FROM `site_visits`;

-- 6. ลบประวัติการค้นหา (Search Logs)
DELETE FROM `search_logs`;

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;

-- สิ้นสุดการล้างข้อมูล --
