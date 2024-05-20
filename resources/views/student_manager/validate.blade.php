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
        lop_id: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        school_year: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        sum_point: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
                numeric: {
                    message: '{{ __('Please enter number.') }}'
                },
                regexp: {
                    regexp: /^[0-9]+$/,
                    message: "{{ __('Vui lòng chỉ nhập số') }}"
                }
            }
        },
        he_tuyen_sinh: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        nganh_tuyen_sinh: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        trinh_do: {
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
        gv_tiep_nhan: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },

        gv_thu_tien: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
            }
        },
        so_tien: {
            validators: {
                notEmpty: {
                    message: '{{ __('Vui lòng không để trống mục này') }}'
                },
                numeric: {
                    message: '{{ __('Please enter number.') }}'
                },
                regexp: {
                    regexp: /^[0-9]+$/,
                    message: "{{ __('Vui lòng chỉ nhập số') }}"
                }
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
