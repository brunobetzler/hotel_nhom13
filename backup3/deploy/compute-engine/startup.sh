#!/bin/bash

echo "=== Hotel2 Deployment Startup Script ==="

# Cập nhật hệ thống
apt-get update -y
apt-get upgrade -y

# Cài Docker + Docker Compose
apt-get install -y docker.io docker-compose git curl

# Khởi động Docker
systemctl start docker
systemctl enable docker

# Tạo thư mục dự án
mkdir -p /opt/hotel2
cd /opt/hotel2

echo "Đang copy code vào server..."

# === PHẦN QUAN TRỌNG: Copy code từ local lên VM ===
# Cách 1: Dùng gsutil (nếu bạn upload code lên Cloud Storage trước)
# gsutil -m cp -r gs://your-bucket-name/hotel2/* /opt/hotel2/

# Cách 2: Dùng git (khuyến nghị)
# git clone https://github.com/username/hotel2.git .
# cp -r /path/to/code/* .

echo "Đang chạy Docker Compose..."
docker-compose -f docker-compose.prod.yml up -d --build

echo "=== Deployment Hoàn tất! ==="
echo "IP Public: $(curl -s -H "Metadata-Flavor: Google" http://metadata.google.internal/computeMetadata/v1/instance/network-interfaces/0/access-configs/0/external-ip)"