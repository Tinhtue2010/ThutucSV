<script>
    const fields = {
        full_name: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        student_code: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        date_of_birth: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        lop_id: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        nien_khoa: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        ngay_nhap_hoc: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },

        status: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        note: {
            validators: {
                stringLength: {
                    max: 255,
                    message: "{{ __('Không được vượt quá 255 ký tự') }}"
                }
            }
        },
    }
</script>
