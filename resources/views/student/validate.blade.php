<script>
    const fields = {
        full_name: {
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
        cmnd: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
                stringLength: {
                    min: 12,
                    max: 12,
                    message: "{{ __('Vui lòng nhập số thẻ căn cước hợp lệ') }}"
                },
                regexp: {
                    regexp: /^[0-9]+$/,
                    message: "{{ __('Vui lòng chỉ nhập số') }}"
                }
            }
        },
        date_range_cmnd: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
    }
</script>
