<!-- BEGIN: main -->
Form Đăng ký cộng tác viên

<div class="bg">
            <div class="container">
                <form style="text-align: left" class="well form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate<!-- BEGIN: reg_recaptcha3 --> data-recaptcha3="1"<!-- END: reg_recaptcha3 --> action="{USER_REGISTER}" method="post" id="contact_form">
                    <fieldset>
                        <!-- Form Name -->
                        <legend>
                            <center>
                                <h2><b>Nhập thông tin đăng kí</b></h2>
                            </center>
                        </legend>
                        <br />

                        <!-- Text input-->

                        <div class="form-group">
                            <label class="col-md-8 control-label">Họ </label>
                            <div class="col-md-16 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">*</span>
                                    <input
                                        name="last_name"
                                        placeholder="Nhập họ "
                                        class="form-control"
                                        type="text"
                                    />
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-8 control-label">Tên </label>
                            <div class="col-md-16 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">*</span>
                                    <input
                                        name="first_name"
                                        placeholder="Nhập tên chính xác"
                                        class="form-control"
                                        type="text"
                                    />
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-md-8 control-label">Tài Khoản </label>
                            <div class="col-md-16 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">*</span>
                                    <input
                                        name="username"
                                        placeholder="Nhập tên tài khoản"
                                        class="form-control"
                                        type="text"
                                    />
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<label class="col-md-8 control-label">Email</label>
							<div class="col-md-16 inputGroupContainer">
								<div class="input-group">
                                    <span class="input-group-addon">*</span>
									<input type="email" class="required form-control" placeholder="{LANG.email}" value="" name="email" maxlength="100" onkeypress="validErrorHidden(this);" data-mess="{GLANG.email_empty}">
							</div></div>
						</div>

						<div class="form-group">
							<label class="col-md-8 control-label">Mật khẩu</label>
							<div class="col-md-16 inputGroupContainer">
								<div class="input-group">
                                    <span class="input-group-addon">*</span>
								<input type="password" autocomplete="off" class="password required form-control" placeholder="{LANG.password}" value="" name="password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{PASSWORD_RULE}">
							</div></div>
						</div>

						<div class="form-group">
							<label class="col-md-8 control-label">Nhập lại mật khẩu</label>
							<div class="col-md-16 inputGroupContainer">
								<div class="input-group">
                                    <span class="input-group-addon">*</span>
								<input type="password" autocomplete="off" class="re-password required form-control" placeholder="{LANG.re_password}" value="" name="re_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){1,}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.re_password_empty}">
							</div></div>
						</div>
                        <div class="form-group">
                            <label class="col-md-8 control-label">Giới tính</label>
                            <div class="col-md-16 selectContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">*</span>
                                    <select class="form-control selectpicker" name="" id="gender">
                                        <option value="male">Nam</option>
                                        <option value="female">Nữ</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-8 control-label">Mã giới thiệu</label>
                            <div class="col-md-16 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">*</span>
                                    <input
                                        name="address"
                                        placeholder=""
                                        class="form-control"
                                        type="text"
										
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-8 control-label">Số điện thoại</label>

                            <div class="col-md-16 inputGroupContainer">
                                <div class="input-group">
                                    <span class="input-group-addon">*</span>
                                    <input name="contact" placeholder="(+84)" class="form-control" type="text" />
                                </div>
                            </div>
                        </div>

                        <!-- Text input-->
                        

                        <!-- Success message -->
                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-8 control-label"></label>
                            <div class="col-md-8">
                                <br />
                                <button type="submit" class="btn-register">Đăng ký</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#contact_form')
                .bootstrapValidator({
                    // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
                    feedbackIcons: {
                        valid: 'glyphicon glyphicon-ok',
                        invalid: 'glyphicon glyphicon-remove',
                        validating: 'glyphicon glyphicon-refresh',
                    },
                    fields: {
                        name: {
                            validators: {
                                stringLength: {
                                    min: 6,
                                    message: 'Vui lòng nhập chính xác',
                                },
                                notEmpty: {
                                    message: 'Vui lòng nhập họ và tên',
                                },
                            },
                        },
                        address: {
                            validators: {
                                notEmpty: {
                                    message: 'Vui lòng nhập địa chỉ',
                                },
                            },
                        },
                        addressmain: {
                            validators: {
                                notEmpty: {
                                    message: 'Vui lòng nhập địa chỉ',
                                },
                            },
                        },
                        contact: {
                            validators: {
                                stringLength: {
                                    min: 8,
                                    message: 'Vui lòng số điện thoại chính xác',
                                },
                                notEmpty: {
                                    message: 'Vui lòng nhập số điện thoại',
                                },
                            },
                        },
                        example: {
                            validators: {
                                notEmpty: {
                                    message: 'Vui lòng chọn trường này',
                                },
                            },
                        },

                        cmnddate: {
                            validators: {
                                notEmpty: {
                                    message: 'Vui lòng nhập đầy đủ thông tin này',
                                },
                            },
                        },
                    },
                })
                .on('success.form.bv', function (e) {
                    $('#success_message').slideDown({ opacity: 'show' }, 'slow'); // Do something ...
                    $('#contact_form').data('bootstrapValidator').resetForm();

                    // Prevent form submission
                    e.preventDefault();

                    // Get the form instance
                    var $form = $(e.target);

                    // Get the BootstrapValidator instance
                    var bv = $form.data('bootstrapValidator');

                    // Use Ajax to submit form data
                    $.post(
                        $form.attr('action'),
                        $form.serialize(),
                        function (result) {
                            console.log(result);
                        },
                        'json'
                    );
                });
        });
    </script>


<!-- END: main -->