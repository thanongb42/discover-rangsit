-- Drop Facebook columns from users table
ALTER TABLE users
    DROP INDEX idx_facebook_id,
    DROP COLUMN facebook_id,
    DROP COLUMN facebook_picture_url;
