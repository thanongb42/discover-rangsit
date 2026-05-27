-- Fix: Regenerate bad place slugs
-- Bad slugs = numeric only, dashes only, auto-generated timestamp, or too short (< 3 chars)
-- Run via phpMyAdmin on production

-- Step 1: Preview ก่อน (SELECT เพื่อดูว่ามีกี่แถว)
SELECT id, name, slug
FROM places
WHERE
    slug REGEXP '^[0-9]+$'
    OR slug REGEXP '^-+[0-9]*-*$'
    OR slug REGEXP '^place-[0-9]{9,}-[0-9]+$'
    OR CHAR_LENGTH(slug) < 3;

-- Step 2: Fix โดยใช้ place-{id} (safe, unique, ไม่ duplicate)
UPDATE places
SET slug = CONCAT('place-', id)
WHERE
    slug REGEXP '^[0-9]+$'
    OR slug REGEXP '^-+[0-9]*-*$'
    OR slug REGEXP '^place-[0-9]{9,}-[0-9]+$'
    OR CHAR_LENGTH(slug) < 3;

-- หลัง run แล้ว ให้ admin เข้าไปแก้ชื่อ slug ผ่าน admin panel ทีละแห่ง
