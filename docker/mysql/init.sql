DROP DATABASE IF EXISTS  posse;

CREATE DATABASE posse;

USE posse;

DROP TABLE IF EXISTS todos;
CREATE TABLE todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content VARCHAR(255) NOT NULL,
    completed BOOLEAN DEFAULT FALSE
);

INSERT INTO todos(user_id,content)
VALUES
(1,"歯磨き"),
(2,"水を飲みすぎてしまおうか！");

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255)
);;

INSERT INTO users (email, password) VALUES ("hirofumin55@gmail.com","$2y$10$uI5FhMcxbwZlpycJKL0tdurQNfFh4iI808AUwwXa2F127JB5gjY72");