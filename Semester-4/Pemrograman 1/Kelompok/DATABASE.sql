USE p13_users;

ALTER TABLE users MODIFY hak_akses ENUM('Admin', 'User', 'Pegawai') NULL;

INSERT INTO users (username, password, hak_akses) VALUES ('rafli', 'password', 'Pegawai');

CREATE TABLE pegawai (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50) NOT NULL
);

ALTER TABLE pegawai
ADD COLUMN nik VARCHAR(20) UNIQUE NOT NULL,
ADD COLUMN jenis_kelamin VARCHAR(10) NULL,
ADD COLUMN alamat TEXT NULL,
ADD COLUMN no_hp VARCHAR(20) NULL,
ADD COLUMN email VARCHAR(100) NULL;
