CREATE DATABASE User;
USE User;

CREATE TABLE Students (
    Id INT AUTO_INCREMENT PRIMARY KEY,  
    first_name VARCHAR(255) NOT NULL,   
    grade  VARCHAR (200),  
    course INT DEFAULT 1,              
    program VARCHAR(255),                 
    year VARCHAR(255)                   
);

CREATE TABLE user (
    ID INT Primary Key,
    email VARCHAR(255) unique ,
    password VARCHAR(255) 
);
INSERT INTO user (ID, email, password) VALUES (1, 'example@email.com', 'yourpassword');
INSERT INTO Students (first_name, grade, course, program, year)
VALUES ('John Doe', 'A', 2, 'Computer Science', '2024');

select * from user;
drop table User;

drop Database user;