#!/bin/bash

# Log cho việc chạy script
echo "$(date) [INFO] Starting MySQL import process..."

# Đảm bảo nhập password không trực tiếp trong câu lệnh (password được nhập trong terminal)
echo "$(date) [DEBUG] Executing: mysql -h 127.0.0.1 -P 3306 -u root -p'123456' --default-character-set=utf8 nckh_huyen <'/Users/admin/Downloads/itdaihw10s6a_ctsv.sql'"

# Chạy lệnh MySQL import
mysql -h 127.0.0.1 -P 3306 -u root -p'123456' --default-character-set=utf8 nckh_huyen <'/Users/admin/Downloads/itdaihw10s6a_ctsv.sql'

# Kiểm tra trạng thái lệnh vừa chạy
if [ $? -eq 0 ]; then
  echo "$(date) [INFO] Import SQL completed successfully."
else
  echo "$(date) [ERROR] Import SQL failed!"
fi

php artisan migrate