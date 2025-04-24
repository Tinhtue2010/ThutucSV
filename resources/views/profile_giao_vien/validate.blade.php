<script>
    const fields = {
        full_name: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        dia_chi: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        phone: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
                stringLength: {
                    min: 9,
                    max: 12,
                    message: "{{ __('Số điện thoại không hợp lệ') }}"
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
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
                emailAddress: {
                    message: '{{ __('The email address is invalid.') }}'
                },
            }
        },
        cccd: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
    }
</script>
