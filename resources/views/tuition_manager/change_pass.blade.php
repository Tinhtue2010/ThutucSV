@push('js')
    <script type="text/javascript">
        function btnChangePass(data) {
            Swal.fire({
                text: "Bạn muốn cài lại mật khẩu mới chứ",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Đồng ý",
                cancelButtonText: "Hủy",
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-secondary ml-2"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        method: 'GET',
                        url: "{{ route('studentManager.resetPass') }}/" + data,
                    }).then((response) => {
                        mess_success('Thông báo', "Mật khẩu đã được cài lại");
                        Datatable.loadData();
                    }).catch(function(error) {
                        mess_error("Cảnh báo", "Có lỗi xảy ra");
                    });
                }
            });;
        }
    </script>
@endpush
