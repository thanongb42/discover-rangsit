# Discover Rangsit Cutover Memory - 2026-06-24

บันทึกนี้เป็น handoff สำหรับงานย้ายระบบ `discover_rangsit` ขึ้น VM1 และเปิดใช้งาน domain จริงผ่าน HTTPS

## สถานะสำเร็จล่าสุด

- DNS ของ `discover.rangsitcity.go.th` และ `www.discover.rangsitcity.go.th` ชี้ไป VM1 แล้ว
- HTTP ใช้งานได้
- Let's Encrypt certificate ออกสำเร็จแล้ว
- HTTPS ใช้งานได้ทั้ง root, `www`, route ภายใน และไฟล์ upload
- Nginx config ถูก rename จาก `discovery.conf` เป็น `discover.conf` เพื่อลดความสับสน

## Infra

- VM1 IP: `112.121.157.74`
- SSH user: `rssc`
- Web root on host: `/home/rssc/web-server`
- Actual storage after disk migration: `/data/rssc/web-server`
- Project path on host: `/home/rssc/web-server/project-php/discover_rangsit`
- PHP container: `php_app`
- Nginx reverse proxy container: `reverse_proxy`
- Database container: `id_booking_db`
- Database name: `discoverdb`
- Database user: `discover_user`
- Passwords/secrets are intentionally not recorded here.

## Final URLs Tested

```text
https://discover.rangsitcity.go.th/                              200
https://discover.rangsitcity.go.th/city-map                     200
https://www.discover.rangsitcity.go.th/                         200
https://discover.rangsitcity.go.th/uploads/covers/1774295712_cover.jpeg 200
```

## Important Findings

- `curl -I` sends `HEAD`, but the PHP router only has `GET`/`POST` routes, so `curl -I` can show `404` even when browser/GET works.
- Use this form for route tests:

```bash
curl -s -o /dev/null -w "%{http_code}\n" https://discover.rangsitcity.go.th/city-map
```

- The project in the PHP container is under `/var/www/html/discover_rangsit`, not `/var/www/html`.
- Nginx must proxy dynamic routes to:

```text
http://php-app:80/discover_rangsit/public/index.php
```

- Static files must proxy to their matching paths under:

```text
http://php-app:80/discover_rangsit/public/
```

- The earlier upstream `php-apache` was wrong; Docker service name is `php-app`.

## Final Nginx Config File

```text
~/web-server/nginx/conf.d/discover.conf
```

## SSL Certificate

```text
Certificate: /etc/letsencrypt/live/discover.rangsitcity.go.th/fullchain.pem
Key:         /etc/letsencrypt/live/discover.rangsitcity.go.th/privkey.pem
Expires:     2026-09-22
```

Do not print or copy the private key content.

## Safe Follow-up

- Keep old cPanel folder/domain entry for at least 1-2 days after cutover.
- Consider redirecting HTTP to HTTPS after confirming all admin/login/social callback flows.
- If changing the Nginx config, always run:

```bash
sudo docker compose exec reverse-proxy nginx -t
sudo docker compose exec reverse-proxy nginx -s reload
```

