USE discover_rangsit;

INSERT INTO roles (role_name) VALUES ('admin'), ('operator'), ('member') ON DUPLICATE KEY UPDATE role_name=role_name;

INSERT INTO categories (name, icon, color) VALUES 
('Restaurant', 'fa-utensils', '#ff5722'),
('Cafe', 'fa-coffee', '#795548'),
('Service', 'fa-concierge-bell', '#2196f3'),
('Tourism', 'fa-map-marked-alt', '#4caf50')
ON DUPLICATE KEY UPDATE name=name;

-- Note: In a real scenario, users should be created first, but for dummy places we might just use NULL for owner_user_id if allowed, or insert a dummy user.
-- Default admin password is 'admin123'
INSERT INTO users (name, email, password, role_id) VALUES 
('Admin User', 'admin@rangsit.go.th', '$2y$10$8.Z.vQ.DPtm.vEqXLoYUp.syZsqXidIdZghEpIsfSpAnmxIDclZye', 1) ON DUPLICATE KEY UPDATE email=email;

INSERT INTO places (name, slug, description, category_id, address, latitude, longitude, status, views_count, rating_avg, rating_count) VALUES 
('Rangsit Boat Noodle', 'rangsit-boat-noodle', 'Famous authentic boat noodle near the canal.', 1, 'Rangsit Canal, Pathum Thani', 13.9850, 100.6130, 'approved', 1500, 4.8, 120),
('Future Park Rangsit', 'future-park-rangsit', 'One of the largest shopping malls in Asia.', 4, 'Phahonyothin Road, Rangsit', 13.9892, 100.6177, 'approved', 5000, 4.5, 300),
('Cozy Coffee Rangsit', 'cozy-coffee-rangsit', 'A quiet place for students and freelancers to work.', 2, 'Near Rangsit University', 13.9644, 100.5866, 'approved', 800, 4.2, 50),
('Dream World', 'dream-world-thailand', 'Amusement park with various rides and snow town.', 4, 'Rangsit-Ongkharak Road', 13.9875, 100.6750, 'approved', 3500, 4.6, 250),
('Rangsit Car Repair', 'rangsit-car-repair', 'Reliable auto repair services.', 3, 'Rangsit Soi 12', 13.9750, 100.6100, 'approved', 200, 3.8, 15);
