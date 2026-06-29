# FROM nginx:alpine

# # Tạo thư mục cloud-storage
# RUN mkdir -p /usr/share/nginx/html/cloud-storage

# # Copy frontend
# COPY . /usr/share/nginx/html

# EXPOSE 80


# ==============================
FROM nginx:alpine

# Copy frontend files
COPY . /usr/share/nginx/html/

# Tạo thư mục cho ảnh
RUN mkdir -p /usr/share/nginx/html/cloud-storage

EXPOSE 80