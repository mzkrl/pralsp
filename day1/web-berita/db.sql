create DATABASE berita;
use berita;
CREATE Table user(
    id INT auto_inrement PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

insert into user VALUES(1, "user" ,"1234");

CREATE Table kategori (
    id INT auto_increment PRIMARY KEY,
    nama_kategori VARCHAR(255)
);

CREATE Table berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    isi TEXT,
    kategori_id INT,
    tanggal DATE
)