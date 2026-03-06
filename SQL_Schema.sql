CREATE DATABASE discover_rangsit CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE discover_rangsit;

-- ROLES
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL
);

INSERT INTO roles (role_name) VALUES
('admin'),
('operator'),
('member');

-- USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255),
    phone VARCHAR(50),
    avatar VARCHAR(255),
    role_id INT,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- CATEGORIES
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    icon VARCHAR(100),
    color VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PLACES
CREATE TABLE places (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    slug VARCHAR(255),
    description TEXT,
    category_id INT,
    address TEXT,
    district VARCHAR(100),
    province VARCHAR(100),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    phone VARCHAR(50),
    website VARCHAR(255),
    facebook VARCHAR(255),
    line VARCHAR(100),
    cover_image VARCHAR(255),
    owner_user_id INT,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    views_count INT DEFAULT 0,
    rating_avg FLOAT DEFAULT 0,
    rating_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (owner_user_id) REFERENCES users(id)
);

-- PLACE IMAGES
CREATE TABLE place_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    place_id INT,
    image_path VARCHAR(255),
    is_cover TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (place_id) REFERENCES places(id)
);

-- RATINGS
CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    place_id INT,
    user_id INT,
    rating INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (place_id) REFERENCES places(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- PLACE VIEWS
CREATE TABLE place_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    place_id INT,
    user_id INT NULL,
    ip_address VARCHAR(50),
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (place_id) REFERENCES places(id)
);

-- SEARCH LOGS
CREATE TABLE search_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(255),
    result_count INT,
    ip_address VARCHAR(50),
    searched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TAGS
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

CREATE TABLE place_tags (
    place_id INT,
    tag_id INT,
    PRIMARY KEY(place_id,tag_id),
    FOREIGN KEY(place_id) REFERENCES places(id),
    FOREIGN KEY(tag_id) REFERENCES tags(id)
);