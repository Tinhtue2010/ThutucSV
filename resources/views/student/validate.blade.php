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
                    max: 14,
                    message: "{{ __('Không được vượt quá 14 ký tự') }}"
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
