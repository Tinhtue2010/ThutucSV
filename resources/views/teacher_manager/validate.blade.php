<script>
    const fields = {
        full_name: {
            validators: {
                notEmpty: {
                    message: '{{ __("Vui lòng không để trống mục này") }}'
                },
            }
        },
        sdt: {
            validators: {
                notEmpty: {
                    message: '{{ __("Vui lòng không để trống mục này") }}'
                },
                stringLength: {
                    max: 12,
                    message: "{{ __('Không được vượt quá 12 ký tự') }}"
                },
                regexp: {
                    regexp: /^[0-9]+$/,
                    message: "{{ __('Vui lòng chỉ nhập số') }}"
                }
            }
        },
        email: {
            validators: {
                notEmpty: {
                    message: '{{ __("Vui lòng không để trống mục này") }}'
                },
                emailAddress: {
                    message: '{{ __("Email không hợp lệ.") }}'
                },
            }
        },
        khoa_id: {
            validators: {
                notEmpty: {
                    message: '{{ __("Vui lòng không để trống mục này") }}'
                },
            }
        },
        chuc_danh: {
            validators: {
                notEmpty: {
                    message: '{{ __("Vui lòng không để trống mục này") }}'
                },
            }
        }
    }
</script>
