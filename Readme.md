```
Install XAMPP

This gives you:

Apache (server)
MySQL (database)
PHP
🔧 Steps:
Download XAMPP
Install it
Open XAMPP Control Panel
Start:
Apache ✅
MySQL ✅


put this repo in 
C:\xampp\htdocs\

open 
    http://localhost/phpmyadmin
create database 
    lms_db
CREATE DATABASE lms_db;
USE lms_db;

make tables 

    CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('student', 'instructor', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    instructor_id INT,
    thumbnail VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    course_id INT,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255),
    video_url VARCHAR(255),
    content TEXT,
    lesson_order INT
);





CREATE TABLE progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    lesson_id INT,
    completed TINYINT(1) DEFAULT 1
);
CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    title VARCHAR(255)
);
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    question TEXT,
    option_a VARCHAR(255),
    option_b VARCHAR(255),
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_option CHAR(1)
);

CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    quiz_id INT,
    score INT
);



```

```
GO TO 
    http://localhost/lms/frontend/login.html
```


```
dummy data 

INSERT INTO users (name, email, password, role) VALUES
('Aarav Sharma', 'aarav.sharma@gmail.com', 'hashed_pass_1', 'student'),
('Priya Verma', 'priya.verma@gmail.com', 'hashed_pass_2', 'student'),
('Rohit Mehta', 'rohit.mehta@gmail.com', 'hashed_pass_3', 'student'),
('Neha Kapoor', 'neha.kapoor@gmail.com', 'hashed_pass_4', 'student'),
('Karan Malhotra', 'karan.m@gmail.com', 'hashed_pass_5', 'student'),

('Dr. Ankit Gupta', 'ankit.gupta@edu.com', 'hashed_pass_6', 'instructor'),
('Prof. Sneha Iyer', 'sneha.iyer@edu.com', 'hashed_pass_7', 'instructor'),

('Admin User', 'admin@platform.com', 'hashed_admin', 'admin');


INSERT INTO courses (title, description, instructor_id, thumbnail) VALUES
('Full Stack Web Development', 'Learn HTML, CSS, JS, Node.js, and databases.', 6, 'webdev.jpg'),
('Python for Data Science', 'Master Python, Pandas, NumPy, and ML basics.', 7, 'python.jpg'),
('UI/UX Design Fundamentals', 'Design beautiful and user-friendly interfaces.', 7, 'uiux.jpg');

INSERT INTO enrollments (user_id, course_id) VALUES
(1,1),(1,2),
(2,1),
(3,2),(3,3),
(4,1),(4,3),
(5,2);


INSERT INTO lessons (id, course_id, title, video_url, content, lesson_order) VALUES
(1, 1, 'Introduction to Web Development', 'https://youtu.be/LzMnsfqjzkA?si=LfASKV0MjaUnZCx0', 'Overview of web development, including frontend and backend concepts.', 1),

(2, 1, 'HTML Basics', 'https://youtu.be/G3e-cpL7ofc?si=2LzLPZcUBhiSNawg', 'Learn HTML structure, tags, elements, and semantic layout.', 2),

(3, 1, 'CSS Styling', 'https://youtu.be/G3e-cpL7ofc?si=rURDkeW9zGqTmYUW', 'Styling websites using CSS, flexbox, grid, and responsiveness.', 3),

(4, 1, 'JavaScript Basics', 'https://youtu.be/EerdGm-ehJQ?si=Egythrs_7E5VB5WZ', 'JavaScript fundamentals including variables, functions, and DOM.', 4),

(5, 2, 'Python Introduction', 'https://youtu.be/nLRL_NcnK-4?si=uc8xP9JgqDFHQpC7', 'Introduction to Python syntax, variables, and basic operations.', 1),

(6, 2, 'NumPy Basics', 'https://youtu.be/VXU4LSAQDSc?si=2KETnatVqtj9oJnQ', 'Working with arrays, mathematical operations using NumPy.', 2),

(7, 2, 'Pandas for Data Analysis', 'https://youtu.be/4NJlUribp3c?si=AeU69pEqEdCvkd-D', 'Data manipulation, cleaning, and analysis using Pandas.', 3),

(8, 3, 'Intro to UX', 'https://youtu.be/ODpB9-MCa5s?si=83vdbD9OTzWPO8ek', 'Understanding user experience design principles.', 1),

(9, 3, 'Wireframing', 'https://youtu.be/hOT8NEQOkbQ?si=rcg8dIwuoLtK_9zD', 'Creating wireframes for layout planning and UX flow.', 2),

(10, 3, 'Prototyping Tools', 'https://youtu.be/nNzH2rlEH6A?si=-pD1Q0NxaHSp-QjD', 'Using tools like Figma to build interactive prototypes.', 3);

INSERT INTO progress (user_id, lesson_id, completed) VALUES
(1,1,1),(1,2,1),(1,3,0),
(2,1,1),(2,2,0),
(3,5,1),(3,6,1),(3,7,0),
(4,1,1),(4,8,1),
(5,5,1);

INSERT INTO quizzes (course_id, title) VALUES
(1, 'Web Dev Basics Quiz'),
(2, 'Python Fundamentals Quiz'),
(3, 'UX Concepts Quiz');

INSERT INTO questions 
(quiz_id, question, option_a, option_b, option_c, option_d, correct_option) VALUES

-- Web Dev Quiz
(1, 'What does HTML stand for?', 
 'Hyper Text Markup Language', 'High Text Machine Language', 'Hyperlinks Text Mark Language', 'None', 'A'),

(1, 'Which language styles web pages?', 
 'HTML', 'CSS', 'Python', 'C++', 'B'),

-- Python Quiz
(2, 'Which library is used for data analysis?', 
 'NumPy', 'Pandas', 'TensorFlow', 'Flask', 'B'),

(2, 'Python is a ___ language?', 
 'Compiled', 'Markup', 'Interpreted', 'Binary', 'C'),

-- UX Quiz
(3, 'What is wireframing?', 
 'Coding', 'Design layout', 'Testing', 'Deployment', 'B'),

(3, 'Best tool for UI design?', 
 'VS Code', 'Figma', 'MySQL', 'Git', 'B');


 INSERT INTO results (user_id, quiz_id, score) VALUES
(1,1,85),
(1,2,78),
(2,1,65),
(3,2,90),
(3,3,88),
(4,3,70),
(5,2,60);


```