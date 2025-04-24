@isset($don_parent)
@php
    $noti_class_name = 'primary';
    $noti_title = 'Thông báo';

    if ($don_parent->status < 0) {
        $noti_class_name = 'warning';
        $noti_title = 'Cảnh báo';
    }
@endphp
@if ($don_parent->status == -1)
    <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-7">
        <i class="ki-outline ki-snapchat text-primary fs-1 me-5"></i>
        <div class="flex-grow-1 me-2">
            <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">Cảnh báo</a>
            <span class="fs-5 fw-semibold d-block">
                Đơn của bạn đã bị từ chối bởi giáo viên chủ nhiệm hãy kiểm tra lại thông tin
            </span>
        </div>
    </div>
@endif
@if ($don_parent->is_update == 1)
    <div class="d-flex align-items-center bg-light-primary rounded p-5 mb-7">
        <i class="ki-outline ki-snapchat text-primary fs-1 me-5"></i>
        <div class="flex-grow-1 me-2">
            <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">Thông báo</a>
            <span class="fs-5 fw-semibold d-block">
                Bạn đã bổ sung hồ sơ thành công vui lòng chờ thông báo tiếp theo
            </span>
        </div>
    </div>
@else
    <div class="d-flex align-items-center bg-light-{{ $noti_class_name }} rounded p-5 mb-7">
        <i class="ki-outline ki-snapchat text-{{ $noti_class_name }} fs-1 me-5"></i>
        <div class="flex-grow-1 me-2">
            <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">{{ $noti_title }}</a>
            <span class="fs-5 fw-semibold d-block">
                @if ($don_parent->status == 0)
                    {{ __('Đơn của bạn đã được gửi đi hãy chờ thông báo tiếp theo') }}
                @else
                    {{ $don->note }}
                @endif

            </span>
        </div>
        @isset($don->file_name)
            <a href="/storage/{{ $don->file_name }}" target="_blank" class="btn btn-primary">Xem phiếu</a>
        @endisset
        @isset($don_parent->file_name)
            <a href="/storage/{{ $don_parent->file_name }}" target="_blank" class="btn btn-primary">Xem phiếu</a>
        @endisset
        @if ($don_parent->status == 6)
            <a href="{{ route('phieu.giaQuyetCongViec', ['id' => $don_parent->id]) }}" target="_blank"
                class="btn btn-primary">Phiếu giải quyết công việc</a>
        @endif

    </div>
@endif

@endisset