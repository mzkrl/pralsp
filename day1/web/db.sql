create DATABASE if NOT EXISTS  berita;
use berita;
CREATE Table  if NOT EXISTS user(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

CREATE Table if NOT EXISTS  kategori (
    id INT auto_increment PRIMARY KEY,
    nama_kategori VARCHAR(255)
);

CREATE Table if NOT EXISTS berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    isi TEXT,
    kategori_id INT,
    tanggal DATE
)
insert into user (username, password) VALUES('admin', MD5('1234'));