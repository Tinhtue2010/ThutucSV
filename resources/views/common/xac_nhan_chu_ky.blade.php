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
                    <h4 class="text-center">Yêu cầu ký số của bạn đã được gửi tới ứng dụng VNPT SmartCA của bạn.</h4>
                    <div class="d-flex">
                        <p class="mt-auto mb-0">Thời gian xác nhận còn lại: </p>
                        <h1 id="demnguoc" class="mb-0 ms-2">05:00</h1>
                    </div>
                    <div class="card-footer mt-5">
                        <button class="btn btn-success mr-2">{{ __('Đã xác nhận') }}</button>
                        <button class="btn btn-warning mr-2">{{ __('Gửi lại xác nhận') }}</button>
                        <button type="reset" class="btn btn-secondary">{{ __('Hủy') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        function checkChuKy() {
            if ("{{ Auth::user()->getUrlChuKy() }}" == "") {
                var modalEl = document.querySelector('#kt_modal_chu_ky_target');
                var modelchu_ky = new bootstrap.Modal(modalEl);
                modelchu_ky.hide();
                Swal.fire({
                    text: "Cảnh báo bạn chưa cập nhật thông tin về chữ ký",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Oke",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                }).then(function(res) {
                    // chưa cập nhật thông tin chữ ký và các thông tin khác
                    // location.href = "{{ route('student.info') }}";
                });
            }
        }
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
        let countdownInterval;

        function startCountdown(duration, elementId) {
            let timer = duration,
                minutes, seconds;
            let countdownElement = document.getElementById(elementId);

            if (countdownInterval) {
                clearInterval(countdownInterval);
            }

            countdownInterval = setInterval(function() {
                minutes = Math.floor(timer / 60);
                seconds = timer % 60;

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                countdownElement.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(countdownInterval);
                    countdownElement.textContent = "Hết giờ!";
                }
            }, 1000);
        }

        async function checkMaXacNhan(formData = null, $data, $url = "{{ route('StopStudy.CreateViewPdf') }}", id = null,
            note = null) {
                
            checkChuKy();
            startCountdown(300, "demnguoc");
            var modalEl = document.querySelector('#kt_modal_chu_ky_target');
            var modelchu_ky = new bootstrap.Modal(modalEl);
            modelchu_ky.show();
            var otp = null;

            $('.modal .close').click(function() {
                modelchu_ky.hide();
                resolve(false);
            });
            $('.modal .btn-secondary').click(function() {
                modelchu_ky.hide();
                resolve(false);
            });

            $('.modal .btn-success').click(function() {
                if (!(formData instanceof FormData)) {
                    formData = new FormData();
                }
                formData.append('fileId', $data[0]);
                formData.append('tranId', $data[1]);
                formData.append('transIDHash', $data[2]);
                formData.append('id', id);
                formData.append('note', note);

                axios({
                    method: 'POST',
                    url: $url,
                    data: formData,
                    headers: {
                        "Content-Type": "multipart/form-data",
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                }).then((response) => {
                    console.log(response.data);
                    if (response.data === 0) {
                        mess_error("Cảnh báo",
                            "Chữ ký số chưa được xác nhận hãy xác nhận trên ứng dụng SmartCA")
                    } else {
                        mess_success('Thông báo',
                            "Đã xác nhận thành công")
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }

                })
            });
        }
    </script>
@endpush
