@push('js')
    <script>
        $('#import-file-ktx input[type="file"]').click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Thông báo",
                text: "Bạn cần phải cập nhật tất cả các sinh viên thuộc diện chế độ chính sách trước. Nếu đã cập nhật, hãy bỏ qua thông báo này.",
                showCancelButton: true,
                confirmButtonText: 'Đã cập nhật tất cả thông tin',
                cancelButtonText: 'Chưa cập nhật đủ',
                dangerMode: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).off('click').click();
                }
            });
        })
        $('#import-file-ktx input[type="file"]').change(function(e) {

            var file = e.target.files[0];
            var fileName = file.name;
            var formData = new FormData();

            formData.append('csv_file', file);
            formData.append('_token', '{{ csrf_token() }}');
            var swalWithProgressBar = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                didOpen: () => {
                    Swal.showLoading();
                    $('#swal-progress-container').show();
                },
                willClose: () => {
                    $('#swal-progress-container').hide();
                }
            });

            swalWithProgressBar.fire({
                title: 'Tải lên vui lòng không đóng trình duyệt!!',
                html: '<div id="swal-progress-container"><div id="swal-progress-bar" class="swal-progress-bar"></div></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
            });

            $.ajax({
                url: '{{ route('PhongDaoTao.CheDoChinhSach.importFileKTX') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            $('#swal-progress-bar').css('width', percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    mess_success('Thông báo',
                        "Tải lên thành công")
                    Datatable.loadData();
                },
                error: function(xhr, status, error) {
                    mess_error("Cảnh báo",
                        "{{ __('Có lỗi xảy ra bạn hãy kiểm tra lại file') }}"
                    )
                }
            });

        });
    </script>
@endpush
