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
    data: 'created_at',
    render: function(data, type, row) {
        return moment(data).format('DD/MM/YYYY HH:mm');
    }
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
            return "Đơn xin trợ cấp học phí"
        if (data == 4)
            return "Đơn xin chế độ chính sách"
        if (data == 5)
            return "Đơn xin chế độ chính sách"
        if (data == 6)
            return "Đơn xin chế độ chính sách"
        return ""
    }
},
{
    data: 'status',
    render: function(data, type, row) {
        var res = '';
        if (data == 0) {
            res = `<span class="mt-1 badge badge-secondary">Chưa được xác nhận</span>`;
        }
        if(row['type'] == 0)
        {
            if (data == -1) {
                res = `<span class="mt-1 badge badge-warning">GV chủ nhiệm từ chối</span>`;
            }
            if (data == -2) {
                res = `<span class="mt-1 badge badge-warning">Khoa từ chối</span>`;
            }
            if (data == -3) {
                res = `<span class="mt-1 badge badge-warning">Cần bổ sung hồ sơ</span>`;
            }
            if (data == -4) {
                res = `<span class="mt-1 badge badge-warning">Phòng CTSV từ chối giải quết hồ sơ</span>`;
            }
            if (data == -5) {
                res = `<span class="mt-1 badge badge-warning">Lãnh đạo phòng CTSV từ chối giải quyết</span>`;
            }
            if (data == -6) {
                res = `<span class="mt-1 badge badge-warning">Lãnh đạo trường từ chối giải quyết</span>`;
            }

            if (data == 1) {
                res = `<span class="mt-1 badge badge-success">GV chủ nhiệm đã xác nhận</span>`;
            }
            if (data == 2) {
                res = `<span class="mt-1 badge badge-success">Khoa đã xác nhận</span>`;
            }
            if (data == 3) {
                res = `<span class="mt-1 badge badge-success">Phòng CTSV đã tiếp nhận</span>`;
            }
            if (data == 4) {
                res = `<span class="mt-1 badge badge-success">Đang chờ cán bộ phòng CTSV xác nhận</span>`;
            }
            
            if (data == 5) {
                res = `<span class="mt-1 badge badge-success">Cán bộ phòng CTSV đã xác nhận</span>`;
            }
            if (data == 6) {
                res = `<span class="mt-1 badge badge-success">Lãnh đạo trường đã xác nhận</span>`;
            }


            if(row['is_pay'] == 0 && row['type'] == 0)
            {
                res += `<span class="mt-1 badge badge-secondary">Chưa xác nhận kinh phí</span>`;
            }
            if(row['is_pay'] == 1 && row['type'] == 0)
            {
                res += `<span class="mt-1 badge badge-primary">Đã hoàn tất thanh toán</span>`;
            }
            if(row['is_pay'] == 2 && row['type'] == 0)
            {
                res += `<span class="mt-1 badge badge-dark">Chưa hoàn tất thanh toán</span>`;
            }
        }

        if(row['type'] == 1 || row['type'] == 2 || row['type'] == 3 || row['type'] == 4 || row['type'] == 5 || row['type'] == 6)
        {
            @foreach (config('doituong.statusmiengiamhp') as $index => $item)
            if(data == {{$item[0]}})
            {
                res = `<span class="mt-1 badge badge-<?php if($item[0] < 0) echo "warning"; if($item[0] == 0) echo "secondary";  if($item[0] > 0) echo "success"; ?>">{{$item[1]}}</span>`;
                
            }
            @endforeach
        }

        if(data < 0 && row['is_update'] == 1 && data != -4)
        {
            res += `<span class="mt-1 badge badge-primary">Sinh viên đã bổ sung hồ sơ</span>`;
        }
        if(!(row['type'] == 1 || row['type'] == 2 || row['type'] == 3 || row['type'] == 4|| row['type'] == 5|| row['type'] == 6))
        {

            if(data < 0 && row['is_update'] == 0 && data != -4)
            {
                res += `<span class="mt-1 badge badge-danger">Sinh viên chưa bổ sung hồ sơ</span>`;
            }
        }

        res = '<div class="d-flex flex-column">'+res+`</div>`;
        return res;
    }
},