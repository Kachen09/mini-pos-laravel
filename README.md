# POS Mini Project

เว็บแอปพลิเคชันระบบขายหน้าร้าน (Point of Sale) พัฒนาด้วย Laravel สำหรับจัดการสินค้า การขาย ลูกค้า และรายงานสรุปการขาย

## ความสามารถหลัก

- สมัครสมาชิก เข้าสู่ระบบ และออกจากระบบ
- จัดการรายการสินค้า ราคา จำนวนคงเหลือ และรูปภาพสินค้า
- บันทึกการขาย คำนวณ VAT 7% และแสดงใบเสร็จ
- จัดเก็บข้อมูลลูกค้าและประวัติการซื้อ พร้อมระบบสะสมแต้ม
- ดูแดชบอร์ดยอดขาย รายงานการขาย สินค้าขายดี และสินค้าใกล้หมด

## เทคโนโลยี

- PHP 8.2 ขึ้นไป
- Laravel 11
- SQLite (ค่าตั้งต้น)
- Blade, JavaScript และ CSS

## การติดตั้ง

### สิ่งที่ต้องเตรียม

- PHP 8.2 ขึ้นไป พร้อมส่วนขยายที่ Laravel ต้องใช้
- Composer

### ขั้นตอน

1. โคลนโปรเจกต์และเข้าไปยังโฟลเดอร์

   ```bash
   git clone https://github.com/Kachen09/mini-pos-laravel.git
   cd mini-pos-laravel
   ```

2. ติดตั้ง PHP dependencies

   ```bash
   composer install
   ```

3. สร้างไฟล์ตั้งค่า `.env` จากตัวอย่าง

   **Windows PowerShell**

   ```powershell
   Copy-Item .env.example .env
   ```

   **macOS / Linux**

   ```bash
   cp .env.example .env
   ```

4. สร้างไฟล์ฐานข้อมูล SQLite และสร้าง application key

   **Windows PowerShell**

   ```powershell
   New-Item -ItemType File -Path database/database.sqlite -Force
   php artisan key:generate
   ```

   **macOS / Linux**

   ```bash
   touch database/database.sqlite
   php artisan key:generate
   ```

5. สร้างตารางในฐานข้อมูล

   ```bash
   php artisan migrate
   ```

6. เริ่มเซิร์ฟเวอร์สำหรับพัฒนา

   ```bash
   php artisan serve
   ```

เปิดเว็บไซต์ที่ <http://127.0.0.1:8000> แล้วสมัครสมาชิกจากหน้า **สมัครสมาชิก** เพื่อเริ่มใช้งาน

## การตั้งค่าฐานข้อมูล

โปรเจกต์ตั้งค่าให้ใช้ SQLite โดยค่าเริ่มต้นใน `.env.example` หากต้องการใช้ฐานข้อมูลชนิดอื่น ให้แก้ค่าการเชื่อมต่อ `DB_*` ใน `.env` แล้วรัน migration อีกครั้ง

## หมายเหตุ

- ไฟล์ `.env`, ฐานข้อมูลในเครื่อง, `vendor` และ `node_modules` ไม่ได้เก็บไว้ใน Git คำสั่งติดตั้งด้านบนจะสร้างไฟล์ที่จำเป็นในเครื่อง
- อย่านำข้อมูลลูกค้าจริง รหัสผ่าน หรือกุญแจสำหรับใช้งานจริงไปใส่ใน repository สาธารณะ

## ผู้พัฒนา

โปรเจกต์สำหรับการศึกษา: **POS Mini Project**
