{
    data: 'student_code'
},
{
    data: 'full_name'
},
{
    data: 'lop_name'
},
{
    data: 'type',
    render: function(data, type, row) {
        if (data == 0)
            return "Đơn xin rút hồ sơ"
        if (data == 1)
            return "Đơn xin miễn giảm học phí"
        if (data == 2)
            return "Đơn xin trợ cấp xã hội"
        if (data == 3)
            return "Đơn xin chế độ chính sách"
        return ""
    }
},
{
    data: 'status',
    render: function(data, type, row) {
        if (data == -1) {
            return `<span class="badge badge-warning">GV chủ nhiệm từ chối</span>`;
        }
        if (data == -2) {
            return `<span class="badge badge-warning">Khoa từ chối</span>`;
        }
        if (data == -3) {
            return `<span class="badge badge-warning">Cần bổ xung hồ sơ</span>`;
        }
        if (data == -4) {
            return `<span class="badge badge-warning">Từ chối giải quết hồ sơ</span>`;
        }
        if (data == 0) {
            return `<span class="badge badge-secondary">Chưa được xác nhận</span>`;
        }
        if (data == 1) {
            return `<span class="badge badge-success">GV chủ nhiệm đã xác nhận</span>`;
        }
        if (data == 2) {
            return `<span class="badge badge-success">Khoa đã xác nhận</span>`;
        }
        if (data == 3) {
            return `<span class="badge badge-success">CTHSSV đã xác nhận</span>`;
        }
        if (data == 4) {
            return `<span class="badge badge-success">Đang xác nhận kinh phí</span>`;
        }
        if (data == 5) {
            return `<span class="badge badge-success">Lãnh đạo CTHSSV đã xác nhận</span>`;
        }
        if (data == 5) {
            return `<span class="badge badge-success">Lãnh đạo trường đã xác nhận</span>`;
        }
        return '';
    }
},