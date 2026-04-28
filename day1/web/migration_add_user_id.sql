-- Migration: Tambah kolom user_id ke tabel berita dan komentar
-- Jalankan ini jika database sudah ada dan perlu di-update

-- Tambah kolom user_id ke berita
ALTER TABLE berita ADD COLUMN user_id INT AFTER kategori_id;
ALTER TABLE berita ADD FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE;

-- Tambah kolom user_id ke komentar 
ALTER TABLE komentar ADD COLUMN user_id INT AFTER berita_id;
ALTER TABLE komentar ADD FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE;

-- Isi user_id dari data yang sudah ada (berdasarkan kolom uploader/username)
UPDATE berita SET user_id = (SELECT id FROM user WHERE user.username = berita.uploader) WHERE user_id IS NULL;
UPDATE komentar SET user_id = (SELECT id FROM user WHERE user.username = komentar.username) WHERE user_id IS NULL;
