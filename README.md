# POS & Inventory System for Aluminium & Thai Products (Bangladesh)

Fresh Laravel backend setup with Docker development environment powered by **Laravel Sail**, **MySQL 8.4 LTS**, **phpMyAdmin**, and **Mailpit**.

---

## 🛠️ Stack & Service Overview

| Service | Version / Image | Host Port | URL / Access | Description |
|---|---|---|---|---|
| **Laravel Backend** | Latest (Laravel 13 / PHP 8.5) | `8000` | [http://localhost:8000](http://localhost:8000) | Main Application Backend |
| **MySQL Database** | `mysql:8.4` (LTS) | `3307` | `localhost:3307` | Database server (Internal port 3306) |
| **phpMyAdmin** | `phpmyadmin:latest` | `8080` | [http://localhost:8080](http://localhost:8080) | Web-based DB Management |
| **Mailpit Web UI** | `axllent/mailpit:latest` | `8025` | [http://localhost:8025](http://localhost:8025) | Local email testing dashboard |
| **Mailpit SMTP** | `axllent/mailpit:latest` | `1025` | `localhost:1025` | Local SMTP relay |

> **Note on MySQL Port:** Host port is mapped to `3307` to avoid port collision with any native MySQL service running on port `3306` on the host machine. Inside Docker containers, the database is accessed via hostname `mysql` on port `3306`.

---

## 🔑 Database Credentials

- **Host (Inside Docker):** `mysql`
- **Host (From your PC / DBeaver / Navicat):** `127.0.0.1` (Port `3307`)
- **Database:** `pos_inventory_thai`
- **Username:** `sail`
- **Password:** `password`
- **Root Password:** `password`

---

## 🚀 Running the Project

### 1. Start all Docker containers:
```bash
./vendor/bin/sail up -d
```
*(Or standard Docker Compose: `docker compose up -d`)*

### 2. Stop all Docker containers:
```bash
./vendor/bin/sail stop
```

### 3. Check Container Status:
```bash
./vendor/bin/sail ps
```

---

## 💻 Common Sail Commands

- **Run Artisan commands:**
  ```bash
  ./vendor/bin/sail artisan <command>
  # Example:
  ./vendor/bin/sail artisan migrate
  ```

- **Run Composer commands:**
  ```bash
  ./vendor/bin/sail composer <command>
  ```

- **Run Node / NPM commands:**
  ```bash
  ./vendor/bin/sail npm run dev
  ```

- **Access Container Bash:**
  ```bash
  ./vendor/bin/sail bash
  ```

- **Open MySQL CLI:**
  ```bash
  ./vendor/bin/sail mysql
  ```

---

## 📁 File Structure Configuration

- [docker-compose.yml](file:///home/borhan/project/pos_inventory_thai/docker-compose.yml): Complete multi-container Sail configuration.
- [.env](file:///home/borhan/project/pos_inventory_thai/.env): Local environment variables for database, mailpit, and ports.
- [.env.example](file:///home/borhan/project/pos_inventory_thai/.env.example): Environment template for team members.
