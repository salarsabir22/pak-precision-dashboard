# Submission Inbox

Laravel staff dashboard for contact messages, sourcing requests, and quote baskets from the Vercel storefronts.

## What it does

- Login and signup for staff
- Read submissions from every storefront (filter by site, type, and status)
- Edit status and staff notes only — submissions are created by the sites, not the dashboard
- Public ingest API that the Next.js sites call after a form is validated

## Run locally

PHP 8.0+, MySQL, and Composer are required. This project uses the XAMPP PHP/MySQL already on this machine.

Start **Apache** and **MySQL** in XAMPP, then:

```bash
cd dashboard
copy .env.example .env
php artisan key:generate
mysql -u root -e "CREATE DATABASE IF NOT EXISTS dashboard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed
php artisan serve
```

`.env` is already set for XAMPP MySQL (`root` / empty password, database `dashboard`). Change `DB_PASSWORD` if your MySQL user has one.

Open [http://127.0.0.1:8000](http://127.0.0.1:8000), create the first staff account, then use the inbox.

The seeder already adds the three sites in this workspace:

| Site | Slug | Local API key |
|---|---|---|
| Precision Core Supply | `precision-core-supply` | `site_pcs_local_dev_key_change_me` |
| PAK Precision Tools | `pkpt` | `site_pkpt_local_dev_key_change_me` |
| Indus Calibrations | `indus-calibrations` | `site_indus_local_dev_key_change_me` |

Add more storefronts with `php artisan db:seed --class=SiteSeeder` after putting their keys in `.env`. The dashboard does not create or delete sites.

## Connect a Vercel site

Each Next.js app should `POST` to:

```
https://YOUR-LARAVEL-HOST/api/v1/submissions
```

Header:

```
X-Site-Key: the key generated for that site
```

Body can be `multipart/form-data` or JSON:

```
type=contact|sourcing|inquiry
name, email, company, phone, country
subject, message                  # contact
part_name, brand, part_number, quantity, deadline, details
items=[{"name":"...","mpn":"...","quantity":1}]
files[]                           # optional attachments
```

On the Vercel project set:

```
INBOX_URL=https://YOUR-LARAVEL-HOST/api/v1/submissions
INBOX_SITE_KEY=the-key-for-that-site
```

Laravel itself cannot run on Vercel. Host this app on a PHP server (a small VPS, Forge, Laravel Cloud, or similar) and keep the storefronts on Vercel.

## Change local keys

Edit `SITE_KEY_*` in `.env`, then run `php artisan db:seed --class=SiteSeeder`.
