<div class="modal fade" id="kt_modal_chu_ky_target" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông báo</h5>
                <div class="btn btn-sm btn-icon btn-active-color-primary close" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body">
                <div class="form" id="form_chu_ky">
                    <h4 class="text-center">Bạn hãy nhập mã xác nhận được gửi vào gmail "{{ Auth::user()->gmail() }}", mã xác nhận chỉ có tác dụng trong 30s</h4>
                    <div class="card-body mt-10">
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                <span class="required">Mã xác nhận</span>
                            </label>
                            <!--end::Label-->
                            <input type="text" class="form-control" name="verification_code" />
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success mr-2">{{ __('Xác nhận') }}</button>
                        <button class="btn btn-warning mr-2">{{ __('Gửi lại mã xác nhận') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('Hủy') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $('.modal .btn-warning').click(function() {
            axios({
                method: 'GET',
                url: "{{ route('otp.createOtpChuKy') }}",
            }).then((response) => {
                mess_success('Thông báo', "Đã gửi lại mã OTP")
            }).error((e) => {
                mess_error("Cảnh báo", "Gửi mã OTP thất bại")
            })
        });

        async function checkMaXacNhan() {
            var modalEl = document.querySelector('#kt_modal_chu_ky_target');
            var modelchu_ky = new bootstrap.Modal(modalEl);
            modelchu_ky.show();
            var otp = null;

            return new Promise((resolve, reject) => {
                if ("{{ Auth::user()->getUrlChuKy() }}" == "") {
                    modelchu_ky.hide();
                    Swal.fire({
                        text: "Cảnh báo bạn chưa cập nhật thông tin về chữ ký",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Oke",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function(res){
                        // chưa cập nhật thông tin giáo viên
                        location.href = "{{route("student.info")}}";
                    });
                }
                axios({
                    method: 'GET',
                    url: "{{ route('otp.createOtpChuKy') }}",
                }).then((response) => {
                    $('.modal .close').click(function() {
                        modelchu_ky.hide();
                        resolve(false);
                    });
                    $('.modal .btn-secondary').click(function() {
                        modelchu_ky.hide();
                        resolve(false);
                    });
                    $('.modal .btn-success').click(function() {
                        var enteredCode = document.querySelector('input[name="verification_code"]').value;
                        axios({
                            method: 'GET',
                            url: "{{ route('otp.checkOtpChuKy') }}/" + enteredCode,
                        }).then((response) => {
                            if (response.data == true) {
                                modelchu_ky.hide();
                                mess_success('Thông báo', "OTP trùng khớp")
                                otp = enteredCode;
                                setTimeout(function() {
                                    resolve(enteredCode);
                                }, 1000);

                            } else
                                mess_error("Cảnh báo", "OTP không trùng khớp hãy kiểm tra lại")

                        }).catch((e) => {
                            mess_error("Cảnh báo", "OTP không trùng khớp hãy kiểm tra lại")
                        })

                    });
                    $(modalEl).on('hidden.bs.modal', function() {
                        if (otp == null) {
                            resolve(false);
                        }
                    });
                }).catch(function(error) {
                    resolve(false);
                });

            });
        }
    </script>
@endpush
