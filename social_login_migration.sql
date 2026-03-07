-- Social Login Migration: Add Google OAuth columns only
-- Run this on both local and production database

ALTER TABLE users
    ADD COLUMN google_id VARCHAR(100) NULL DEFAULT NULL AFTER line_linked_at,
    ADD COLUMN google_picture_url VARCHAR(500) NULL DEFAULT NULL AFTER google_id,
    ADD UNIQUE INDEX idx_google_id (google_id);

-- If you already ran the previous migration (with facebook columns), run this to remove them:
-- ALTER TABLE users
--     DROP COLUMN facebook_id,
--     DROP COLUMN facebook_picture_url,
--     DROP INDEX idx_facebook_id;
