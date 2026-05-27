-- Migration: Add place_type to places table
-- Run via phpMyAdmin on production

ALTER TABLE places
ADD COLUMN place_type ENUM('business', 'facility') NOT NULL DEFAULT 'business' AFTER status;

-- Batch update existing places that belong to facility-type categories
UPDATE places p
JOIN categories c ON p.category_id = c.id
SET p.place_type = 'facility'
WHERE c.slug IN (
    'water-dispenser',
    'smart-bus-stop',
    'motorcycle-taxi',
    'gas-station',
    'ev-charger',
    'lpg-station',
    'ngv-station'
);

-- Preview: ดูว่ามีกี่แห่งที่ถูก set เป็น facility
SELECT place_type, COUNT(*) as total FROM places GROUP BY place_type;
