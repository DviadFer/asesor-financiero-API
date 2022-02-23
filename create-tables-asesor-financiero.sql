CREATE DATABASE IF NOT EXISTS asesordb;

USE asesordb;

CREATE TABLE tUser (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  surname VARCHAR(100) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  encrypted_password VARCHAR(100) NOT NULL,
  active_session_token CHAR(20)
);

CREATE TABLE tCategory (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(15) NOT NULL,
  author_id INTEGER NOT NULL,
  
  FOREIGN KEY (author_id) REFERENCES tUser(id)
);

CREATE TABLE tTransaction (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  datetime DATETIME NOT NULL DEFAULT NOW(),
  amount INTEGER NOT NULL,
  category_id INTEGER NOT NULL,
  type VARCHAR(20) NOT NULL,
  
  FOREIGN KEY (category_id) REFERENCES tCategory(id)
);
