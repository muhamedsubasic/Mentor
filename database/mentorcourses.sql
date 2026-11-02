SET NAMES utf8mb4;
DROP DATABASE IF EXISTS mentorcourses;
CREATE DATABASE mentorcourses CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mentorcourses;

-- USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'trainer', 'admin') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- TRAINERS
CREATE TABLE trainers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialty VARCHAR(100),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- CATEGORIES
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- COURSES
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    trainer_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_courses_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    CONSTRAINT fk_courses_trainer FOREIGN KEY (trainer_id)  REFERENCES trainers(id)  ON DELETE RESTRICT,
    CONSTRAINT chk_courses_price CHECK (price >= 0.00)
);

-- REVIEWS
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    comment TEXT,
    rating TINYINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    CONSTRAINT uq_reviews_user_course UNIQUE (user_id, course_id), -- one review per course per student
    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5)
);

-- PURCHASES
CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    purchased_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchases_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchases_course FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    CONSTRAINT chk_purchases_amount CHECK (amount >= 0.00)
);

-- Sample Data

-- USERS
INSERT INTO users (id, first_name, last_name, email, password, role, created_at) VALUES
(1, 'Niko', 'Niković', 'admin@mentorcourses.ba', '$2y$10$0Hge22rw38Y05WNSVUtghO.6HmmiOeHLTQFdBesqg33d5z3feOvBG', 'admin', '2026-09-01 09:00:00'),
(2, 'Lana', 'Kovač',  'ema@mentorcourses.ba', '$2y$10$TxLK/Oju/TDbgoGjSYisyeJl1vBJlpwilOPqh5Sm.XMuec4FZp6ni', 'student', '2026-09-03 10:15:00'),
(3, 'Tarik', 'Begić', 'tarik@mentorcourses.ba', '$2y$10$TxLK/Oju/TDbgoGjSYisyeJl1vBJlpwilOPqh5Sm.XMuec4FZp6ni', 'student', '2026-09-05 12:30:00'),
(4, 'Robert', 'Brown', 'robert@mentorcourses.ba', '$2y$10$8K9zD5UvJ2wX1yA0bC3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v', 'trainer', '2026-09-01 08:00:00');

-- TRAINERS
INSERT INTO trainers (id, first_name, last_name, specialty) VALUES
(1, 'Robert', 'Brown', 'Web Development'),
(2, 'Emily', 'Davis', 'Data Science & AI'),
(3, 'Marko', 'Marković', 'Cloud Architecture');

-- CATEGORIES
INSERT INTO categories (id, name) VALUES
(1, 'Programming'),
(2, 'Data & Analytics'),
(3, 'Design');

-- COURSES
INSERT INTO courses (id, category_id, trainer_id, name, price) VALUES
(1, 1, 1, 'Full-Stack Web Development', 199.99),
(2, 2, 2, 'Python for Data Science',    149.50),
(3, 3, 1, 'UI/UX Design Fundamentals',  99.00),
(4, 1, 3, 'Docker & Kubernetes Basics', 129.00);

-- REVIEWS
INSERT INTO reviews (user_id, course_id, rating, comment) VALUES
(2, 1, 5, 'Excellent course! The trainer was very engaging.'),
(3, 2, 4, 'Good introduction to Python, but could use more practical exercises.'),
(2, 3, 5, 'Loved the design principles taught here. Highly recommended!');

-- PURCHASES
INSERT INTO purchases (user_id, course_id, amount, purchased_at) VALUES
(2, 1, 199.99, NOW() - INTERVAL 15 DAY),
(3, 2, 149.50, NOW() - INTERVAL 10 DAY),
(2, 3,  99.00, NOW() - INTERVAL 5 DAY),
(3, 4, 129.00, NOW() - INTERVAL 2 DAY);