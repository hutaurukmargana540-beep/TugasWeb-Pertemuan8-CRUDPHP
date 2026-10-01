# Tugas Rutin 8 — CRUD Inventaris

Project PHP Native + PDO + MySQL untuk Laragon.

## Sudah dipenuhi
- Database `inventaris_db` dengan 3 tabel: products, categories, suppliers.
- Foreign key products -> categories dan products -> suppliers.
- Seed minimal 5 data per tabel (products 7 data).
- List produk memakai JOIN 2 tabel.
- Create dengan dropdown kategori dan supplier.
- Read/list.
- Update dengan form pre-filled.
- Delete dengan konfirmasi dan transaction.
- Semua query input menggunakan PDO prepared statements.
- Output HTML menggunakan htmlspecialchars().
- Flash message + redirect pattern.
- UI responsive.
- Bonus: search, pagination, export CSV.

## Instalasi Laragon
1. Extract folder `inventaris-crud` ke `C:\laragon\www\`
2. Start All pada Laragon.
3. Buka `http://localhost/phpmyadmin/`.
4. Import `database/inventaris_db.sql`.
5. Buka `http://localhost/inventaris-crud/`.

Default koneksi Laragon:
host 127.0.0.1, database inventaris_db, user root, password kosong.
Jika password MySQL berbeda, edit `config/Database.php`.
