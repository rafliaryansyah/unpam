# Web CRUD Cloud - Aplikasi PHP untuk Google Cloud

Aplikasi CRUD sederhana untuk mengelola data mahasiswa yang dapat di-deploy ke Google Cloud Platform menggunakan **Compute Engine** atau App Engine dengan Cloud SQL.

## Fitur
- ✅ Tambah data mahasiswa
- ✅ Lihat daftar mahasiswa  
- ✅ Edit data mahasiswa
- ✅ Hapus data mahasiswa
- ✅ Support deployment ke Google Cloud Compute Engine
- ✅ Support deployment ke Google Cloud App Engine (alternatif)
- ✅ Kompatibel dengan Cloud SQL dan database lokal

## Struktur Database

```sql
CREATE DATABASE cloud_db;
USE cloud_db;

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🚀 Quick Start - Deploy ke Compute Engine

```bash
# 1. Setup Cloud SQL
gcloud sql instances create your-instance --database-version=MYSQL_8_0 --tier=db-f1-micro --region=asia-southeast2 --authorized-networks=0.0.0.0/0
gcloud sql databases create cloud_db --instance=your-instance
gcloud sql users set-password root --host=% --instance=your-instance --password=YOUR_PASSWORD

# 2. Buat VM dengan LAMP stack otomatis
gcloud compute instances create web-crud-server --zone=asia-southeast2-a --machine-type=e2-micro --tags=http-server,https-server --metadata-from-file startup-script=startup-script.sh

# 3. Upload & Deploy
gcloud compute scp --recurse . web-crud-server:/tmp/web-crud-app --zone=asia-southeast2-a
gcloud compute ssh web-crud-server --zone=asia-southeast2-a

# 4. Di server VM
./deploy.sh
./setup-db.sh CLOUD_SQL_IP root YOUR_PASSWORD
```

**Aplikasi siap diakses di:** `http://EXTERNAL_IP`

## Development Lokal

1. **Setup Database**
   - Buat database MySQL dengan nama `cloud_db`
   - Import struktur tabel dari file `DATABASE.SQL`

2. **Jalankan Aplikasi**
   ```bash
   php -S localhost:8000
   ```

3. **Akses Aplikasi**
   - Buka browser: `http://localhost:8000`

## 🚀 Deployment ke Google Cloud Compute Engine (Recommended)

### Persiapan

1. **Install Google Cloud SDK**
   ```bash
   # macOS
   brew install google-cloud-sdk
   
   # atau download dari: https://cloud.google.com/sdk/docs/install
   ```

2. **Login ke Google Cloud**
   ```bash
   gcloud auth login
   gcloud config set project YOUR_PROJECT_ID
   ```

### Setup Cloud SQL Instance

1. **Buat Cloud SQL Instance**
   ```bash
   gcloud sql instances create your-instance-name \
     --database-version=MYSQL_8_0 \
     --tier=db-f1-micro \
     --region=asia-southeast2 \
     --authorized-networks=0.0.0.0/0
   ```

2. **Buat Database**
   ```bash
   gcloud sql databases create cloud_db --instance=your-instance-name
   ```

3. **Set Password untuk Root User**
   ```bash
   gcloud sql users set-password root \
     --host=% \
     --instance=your-instance-name \
     --password=YOUR_STRONG_PASSWORD
   ```

4. **Get Public IP Address**
   ```bash
   gcloud sql instances describe your-instance-name --format="value(ipAddresses[0].ipAddress)"
   ```

### Setup Compute Engine Instance

1. **Buat VM Instance dengan Automated Setup**
   ```bash
   # Option 1: Menggunakan startup script (RECOMMENDED)
   gcloud compute instances create web-crud-server \
     --zone=asia-southeast2-a \
     --machine-type=e2-micro \
     --network-tier=PREMIUM \
     --maintenance-policy=MIGRATE \
     --image=ubuntu-2004-focal-v20231213 \
     --image-project=ubuntu-os-cloud \
     --boot-disk-size=10GB \
     --boot-disk-type=pd-standard \
     --tags=http-server,https-server \
     --metadata-from-file startup-script=startup-script.sh
   
   # Option 2: Manual setup (jika tidak menggunakan startup script)
   gcloud compute instances create web-crud-server \
     --zone=asia-southeast2-a \
     --machine-type=e2-micro \
     --network-tier=PREMIUM \
     --maintenance-policy=MIGRATE \
     --image=ubuntu-2004-focal-v20231213 \
     --image-project=ubuntu-os-cloud \
     --boot-disk-size=10GB \
     --boot-disk-type=pd-standard \
     --tags=http-server,https-server
   ```

2. **Konfigurasi Firewall Rules**
   ```bash
   gcloud compute firewall-rules create allow-http-80 \
     --allow tcp:80 \
     --source-ranges 0.0.0.0/0 \
     --target-tags http-server

   gcloud compute firewall-rules create allow-https-443 \
     --allow tcp:443 \
     --source-ranges 0.0.0.0/0 \
     --target-tags https-server
   ```

### Deploy Aplikasi ke Compute Engine

#### 🚀 Jika Menggunakan Startup Script (Automated)

1. **Tunggu Instalasi LAMP Stack Selesai**
   ```bash
   # Check status instalasi (biasanya 3-5 menit)
   gcloud compute ssh web-crud-server --zone=asia-southeast2-a --command="tail -f /var/log/startup-script.log"
   ```

2. **Upload Aplikasi ke Server**
   ```bash
   # Dari local machine, upload files
   gcloud compute scp --recurse . web-crud-server:/tmp/web-crud-app --zone=asia-southeast2-a
   ```

3. **Deploy dan Setup Database**
   ```bash
   # Connect ke server
   gcloud compute ssh web-crud-server --zone=asia-southeast2-a
   
   # Deploy aplikasi (menggunakan helper script)
   ./deploy.sh
   
   # Setup database connection
   ./setup-db.sh YOUR_CLOUD_SQL_PUBLIC_IP root YOUR_PASSWORD
   
   # Check status
   ./status.sh
   ```

4. **Test Aplikasi**
   - Akses aplikasi: `http://EXTERNAL_IP`
   - Info server: `http://EXTERNAL_IP/info.php`

#### 🔧 Jika Manual Setup (Tanpa Startup Script)

1. **Connect ke VM Instance**
   ```bash
   gcloud compute ssh web-crud-server --zone=asia-southeast2-a
   ```

2. **Install LAMP Stack**
   ```bash
   # Update package list
   sudo apt update && sudo apt upgrade -y
   
   # Install Apache
   sudo apt install apache2 -y
   
   # Install PHP dan ekstensi yang diperlukan
   sudo apt install php libapache2-mod-php php-mysql php-cli php-curl php-json -y
   
   # Install MySQL client
   sudo apt install mysql-client -y
   
   # Enable Apache modules
   sudo a2enmod rewrite
   sudo systemctl restart apache2
   ```

3. **Upload dan Deploy Aplikasi**
   ```bash
   # Dari local machine, upload files
   gcloud compute scp --recurse . web-crud-server:/tmp/web-crud-app --zone=asia-southeast2-a
   
   # Di server, deploy ke web directory
   sudo rm -rf /var/www/html/*
   sudo cp -r /tmp/web-crud-app/* /var/www/html/
   sudo chown -R www-data:www-data /var/www/html/
   sudo chmod -R 755 /var/www/html/
   ```

4. **Konfigurasi Database Connection**
   ```bash
   # Edit file db.php di server
   sudo nano /var/www/html/db.php
   ```
   
   Update konfigurasi di `db.php`:
   ```php
   // Untuk Compute Engine dengan Cloud SQL (Public IP)
   $host = 'YOUR_CLOUD_SQL_PUBLIC_IP';
   $dbname = 'cloud_db';
   $username = 'root';
   $password = 'YOUR_STRONG_PASSWORD';
   ```

5. **Import Database Structure**
   ```bash
   # Di VM instance, connect ke Cloud SQL dan import
   mysql -h YOUR_CLOUD_SQL_PUBLIC_IP -u root -p cloud_db < /var/www/html/DATABASE.SQL
   ```

6. **Test Aplikasi**
   ```bash
   # Get external IP VM
   gcloud compute instances describe web-crud-server \
     --zone=asia-southeast2-a \
     --format='get(networkInterfaces[0].accessConfigs[0].natIP)'
   ```
   
   Akses aplikasi di browser: `http://EXTERNAL_IP`

### Konfigurasi SSL Certificate (Opsional)

1. **Install Certbot**
   ```bash
   sudo apt install certbot python3-certbot-apache -y
   ```

2. **Setup Domain (jika ada)**
   ```bash
   # Pastikan domain sudah pointing ke External IP
   sudo certbot --apache -d yourdomain.com
   ```

## Deployment ke Google Cloud App Engine (Alternative)

### Setup Cloud SQL untuk App Engine

1. **Buat Cloud SQL Instance**
   ```bash
   gcloud sql instances create your-instance-name \
     --database-version=MYSQL_8_0 \
     --tier=db-f1-micro \
     --region=asia-southeast2
   ```

2. **Buat Database**
   ```bash
   gcloud sql databases create cloud_db --instance=your-instance-name
   ```

3. **Set Password untuk Root User**
   ```bash
   gcloud sql users set-password root \
     --host=% \
     --instance=your-instance-name \
     --password=YOUR_PASSWORD
   ```

4. **Get Connection Name**
   ```bash
   gcloud sql instances describe your-instance-name
   ```
   Copy nilai `connectionName` (format: `project-id:region:instance-name`)

### Konfigurasi App Engine

1. **Update app.yaml**
   Edit file `app.yaml` dan ganti:
   ```yaml
   env_variables:
     DB_CONNECTION_STRING: "mysql:unix_socket=/cloudsql/YOUR_PROJECT_ID:REGION:INSTANCE_NAME;dbname=cloud_db"
     DB_USER: "root"
     DB_PASS: "YOUR_PASSWORD"
   ```

2. **Deploy Aplikasi**
   ```bash
   gcloud app deploy
   ```

## Environment Variables

### Compute Engine
Aplikasi menggunakan konfigurasi database langsung di file `db.php`:

```php
$host = 'localhost';        // atau Cloud SQL Public IP
$dbname = 'cloud_db';
$username = 'root';
$password = 'your_password';
```

### App Engine  
Environment variables diambil dari `app.yaml`:

- `DB_CONNECTION_STRING`: String koneksi database
- `DB_USER`: Username database  
- `DB_PASS`: Password database

## Keamanan

⚠️ **Peringatan Keamanan untuk Produksi:**

### Untuk Compute Engine:
1. **Gunakan SSL Certificate** untuk HTTPS
2. **Restrict database access** - jangan gunakan `0.0.0.0/0` untuk authorized networks
3. **Update sistem secara berkala**
4. **Setup firewall rules** yang spesifik
5. **Gunakan strong passwords**

### Untuk App Engine:
1. **Jangan hardcode password** di `app.yaml`
2. **Gunakan Secret Manager** untuk menyimpan credentials

## Monitoring & Logs

### Compute Engine Logs
```bash
# Apache access logs
sudo tail -f /var/log/apache2/access.log

# Apache error logs  
sudo tail -f /var/log/apache2/error.log

# PHP error logs
sudo tail -f /var/log/apache2/error.log
```

### Performance Monitoring
```bash
# Check system resources
htop
df -h
free -m
```

## Troubleshooting

### Compute Engine Issues

**Error: Connection refused**
- Check if Apache is running: `sudo systemctl status apache2`
- Check firewall rules: `gcloud compute firewall-rules list`
- Verify VM external IP: `gcloud compute instances list`

**Error: Database connection failed**
- Test Cloud SQL connection: `mysql -h CLOUD_SQL_IP -u root -p`
- Check authorized networks in Cloud SQL
- Verify credentials in `db.php`

**Error: Permission denied**
- Set proper file permissions: `sudo chown -R www-data:www-data /var/www/html/`
- Check Apache error logs: `sudo tail -f /var/log/apache2/error.log`

### App Engine Issues
- Pastikan Cloud SQL instance sudah running
- Verifikasi connection string format dengan benar
- Check kredentials database

## File Structure

```
web-crud-cloud/
├── README.md                  # 📖 Panduan lengkap deployment
├── DEPLOYMENT_CHECKLIST.md   # ✅ Checklist step-by-step deployment
├── startup-script.sh          # 🚀 Script instalasi LAMP untuk Compute Engine
├── app.yaml                   # ☁️ Konfigurasi App Engine (alternatif)
├── DATABASE.SQL               # 🗄️ Struktur database
├── db.php                     # 🔌 Koneksi database
├── index.php                  # 🏠 Halaman utama (daftar mahasiswa)
├── tambah.php                 # ➕ Form tambah data mahasiswa
├── edit.php                   # ✏️ Form edit data mahasiswa
├── hapus.php                  # 🗑️ Script hapus data mahasiswa
└── style.css                  # 🎨 Styling aplikasi
```

## Cost Estimation (Monthly)

### Compute Engine (e2-micro + Cloud SQL db-f1-micro)
- VM Instance: ~$5-7
- Cloud SQL: ~$7-10  
- **Total: ~$12-17/month**

### App Engine + Cloud SQL
- App Engine: Pay per request (lebih murah untuk traffic rendah)
- Cloud SQL: ~$7-10
- **Total: ~$7-15/month** (tergantung traffic)

## Support

Jika mengalami masalah, pastikan:
1. Google Cloud SDK sudah terinstall
2. Project ID sudah benar
3. VM instance atau App Engine sudah running
4. Database connection sudah dikonfigurasi
5. Firewall rules sudah disetup (untuk Compute Engine)
6. File permissions sudah benar (untuk Compute Engine) 