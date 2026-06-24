# VM1 Current State Handoff - Discover Rangsit

Last updated: 2026-06-24

ไฟล์นี้เป็น handoff สั้นสำหรับ AI/ผู้ดูแลระบบรอบถัดไป ให้อ่านก่อนทำงานต่อกับ VM1

## SSH

```bash
ssh rssc@112.121.157.74
```

Prompt ที่เจอ:

```text
rssc@rssc-webapp:~$
```

## Project

```text
Project: discover_rangsit
VM: webapp-svr
VM1 IP: 112.121.157.74
Host workspace: /home/rssc/web-server
Actual storage behind symlink: /data/rssc/web-server
Project path on VM1: /home/rssc/web-server/project-php/discover_rangsit
Nginx config: /home/rssc/web-server/nginx/conf.d/discover.conf
```

## Current Web Status

Cutover สำเร็จแล้ว

```text
https://discover.rangsitcity.go.th/                              200
https://discover.rangsitcity.go.th/city-map                     200
https://www.discover.rangsitcity.go.th/                         200
https://discover.rangsitcity.go.th/uploads/covers/1774295712_cover.jpeg 200
```

SSL certificate:

```text
/etc/letsencrypt/live/discover.rangsitcity.go.th/fullchain.pem
/etc/letsencrypt/live/discover.rangsitcity.go.th/privkey.pem
Expires: 2026-09-22
```

Do not print private key contents.

## Docker/Service Facts

Important service/container names:

```text
reverse-proxy / reverse_proxy
php-app / php_app
id-booking-db / id_booking_db
```

Important discovery:

```text
PHP container document root: /var/www/html
Project inside container: /var/www/html/discover_rangsit
Dynamic route proxy target:
http://php-app:80/discover_rangsit/public/index.php
```

Do not use `php-apache`; the correct upstream service is `php-app`.

## Disk State

Current layout after adding the new 200 GiB volume:

```text
NAME    SIZE FSTYPE TYPE MOUNTPOINTS
sda      20G        disk
├─sda1  200M vfat   part /boot/efi
├─sda2  512M ext4   part /boot
└─sda3 19.3G xfs    part /
sdb     100G ext4   disk /data
sdc     200G ext4   disk /data2
```

Current space:

```text
Filesystem      Size  Used Avail Use% Mounted on
/dev/sda3        20G   18G  1.6G  92% /
/dev/sdb         98G  1.4G   92G   2% /data
/dev/sdc        196G   28K  186G   1% /data2
```

## New Volume Added Today

```text
UI volume: webapp-svr-pvc-2
Capacity: 200 GiB
Linux device: /dev/sdc
Filesystem: ext4
Label: data2
Mount point: /data2
UUID: 208fbfd1-e391-412e-a1ae-c3d7f58044ca
```

fstab entry added:

```text
UUID=208fbfd1-e391-412e-a1ae-c3d7f58044ca /data2 ext4 defaults,nofail 0 2
```

Commands already completed:

```bash
sudo mkfs.ext4 -L data2 /dev/sdc
sudo mkdir -p /data2
sudo mount /dev/sdc /data2
sudo cp /etc/fstab /etc/fstab.bak-$(date +%F-%H%M%S)
echo 'UUID=208fbfd1-e391-412e-a1ae-c3d7f58044ca /data2 ext4 defaults,nofail 0 2' | sudo tee -a /etc/fstab
sudo systemctl daemon-reload
sudo mount -a
```

## Disk Findings

Root `/` is still almost full at 92%. Adding `/data2` added capacity but did not reduce root usage.

Large areas found:

```text
/var/lib/containerd 5.2G
/var/lib/docker     3.6G
/var/lib/registry   2.0G
/var/log            623M
```

Docker images were all `U` / In Use. Do not run `docker system prune -a` casually.

`/var/lib/registry` is used by a real service:

```text
registry.service loaded active running
/usr/bin/registry serve /etc/docker/registry/config.yaml
rootdirectory: /var/lib/registry
readonly: true
delete: enabled
```

Do not delete `/var/lib/docker`, `/var/lib/containerd`, or `/var/lib/registry` manually.

## Useful Guides

Read these first:

```text
.ai/discover-rangsit-installation-runbook-2026-06-24.html
.ai/vm1-disk-space-check-guide.html
.ai/discover-rangsit-cutover-memory-2026-06-24.md
```

## Suggested Next Work

Next safe work is planning a controlled migration of Docker/containerd/registry storage to `/data2` or expanding root disk. Do not improvise deletes under `/var/lib`.

