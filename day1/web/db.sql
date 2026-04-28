create DATABASE if NOT EXISTS  berita;
use berita;
CREATE Table  if NOT EXISTS user(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    bio VARCHAR(255) DEFAULT null,
    gambar BLOB DEFAULT NULL,
    gambar_type VARCHAR(50)
);

CREATE Table if NOT EXISTS  kategori (
    id INT auto_increment PRIMARY KEY,
    nama_kategori VARCHAR(255)
);

CREATE Table if NOT EXISTS berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    isi VARCHAR(250),
    kategori_id INT,
    user_id INT,
    uploader VARCHAR(50),
    tanggal DATETIME,
    gambar BLOB DEFAULT NULL,
    gambar_type VARCHAR(50),
    file_blob BLOB DEFAULT NULL,
    file_name VARCHAR(255),
    file_type VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
);

CREATE Table if NOT EXISTS komentar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    berita_id INT,
    user_id INT,
    username VARCHAR(50),
    isi VARCHAR(250),
    tanggal DATETIME,
    gambar BLOB DEFAULT NULL,
    gambar_type VARCHAR(50),
    file_blob BLOB DEFAULT NULL,
    file_name VARCHAR(255),
    file_type VARCHAR(100),
    FOREIGN KEY (berita_id) REFERENCES berita(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);
insert into user (username, password) VALUES('admin', MD5('1234'));
insert into user (username, password) VALUES('admin2', MD5('password'));