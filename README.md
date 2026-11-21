# POS Mini Project

เว็บแอปพลิเคชันระบบขายหน้าร้านพัฒนาด้วย Laravel สำหรับจัดการสินค้า การขาย ลูกค้า และรายงานสรุปการขาย ใช้ MySQL จาก XAMPP เป็นฐานข้อมูล

## ความสามารถหลัก

- สมัครสมาชิก เข้าสู่ระบบ และออกจากระบบ
- จัดการรายการสินค้า ราคา จำนวนคงเหลือ และรูปภาพสินค้า
- บันทึกการขาย คำนวณ VAT 7% และแสดงใบเสร็จ
- จัดเก็บข้อมูลลูกค้าและประวัติการซื้อ พร้อมระบบสะสมแต้ม
- ดูแดชบอร์ดยอดขาย รายงานการขาย สินค้าขายดี และสินค้าใกล้หมด

## เทคโนโลยี

- PHP 8.2 ขึ้นไป
- Laravel 11
- MySQL ผ่าน XAMPP
- Blade, JavaScript และ CSS

## สิ่งที่ต้องเตรียม

- XAMPP ที่มี Apache และ MySQL
- PHP 8.2 ขึ้นไป และ Composer

## การติดตั้ง

1. เปิด XAMPP Control Panel แล้ว Start **Apache** และ **MySQL**

2. โคลนโปรเจกต์และเข้าไปยังโฟลเดอร์

   ```bash
   git clone https://github.com/Kachen09/mini-pos-laravel.git
   cd mini-pos-laravel
   ```

3. ติดตั้ง PHP dependencies

   ```bash
   composer install
   ```

4. สร้างไฟล์ตั้งค่า `.env` จากตัวอย่าง

   **Windows PowerShell**

   ```powershell
   Copy-Item .env.example .env
   ```

   **macOS / Linux**

   ```bash
   cp .env.example .env
   ```

5. สร้างฐานข้อมูล `pos_db2` ใน phpMyAdmin ที่ <http://localhost/phpmyadmin>

   ตั้งค่า collation เป็น `utf8mb4_unicode_ci`

6. ตรวจสอบค่าฐานข้อมูลใน `.env` ให้ตรงกับ XAMPP ของเครื่อง

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pos_db2
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   ค่าตั้งต้นนี้ใช้กับ XAMPP ที่ตั้ง MySQL `root` โดยไม่มีรหัสผ่าน หากกำหนดรหัสผ่านไว้ ให้ใส่ใน `DB_PASSWORD`

7. เริ่มเซิร์ฟเวอร์สำหรับพัฒนา

   ```bash
   php artisan serve
   ```

เปิดเว็บไซต์ที่ <http://127.0.0.1:8000> หากนำเข้าฐานข้อมูลที่มีบัญชีผู้ใช้แล้ว ให้เข้าสู่ระบบด้วยบัญชีเดิม หากเริ่มจากฐานข้อมูลว่าง ให้สมัครสมาชิกจากหน้า **สมัครสมาชิก**
