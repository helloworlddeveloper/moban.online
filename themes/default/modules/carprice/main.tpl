<!-- BEGIN: main -->
<link href="https://s.vnecdn.net/vnexpress/j/v11/event/dulieuxe/css/jquery.jscrollpane.css" rel="stylesheet" type="text/css" />
<link href="https://s.vnecdn.net/vnexpress/j/v11/event/dulieuxe/css/rangeslider.css" rel="stylesheet" type="text/css" />
<link href="https://s.vnecdn.net/vnexpress/j/v11/event/dulieuxe/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="https://s.vnecdn.net/vnexpress/j/v11/event/dulieuxe/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="https://s.vnecdn.net/vnexpress/j/v11/event/dulieuxe/js/jquery.jscrollpane.js"></script>
<script type="text/javascript" src="https://s.vnecdn.net/vnexpress/j/v11/event/dulieuxe/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="https://s.vnecdn.net/vnexpress/restruct/j/v213/event/dulieuxe/js/jquery-ui.min.js"></script>

<div id="ads">
	<div id="wrap">
		<div id="content" class="du_doan_chi_phi">
			<div class="col-md-8">
				<div class="form_select">
					<p>
						<span><strong>{LANG.hangxe}</strong></span>
						<select id="brand" onchange="changeBrand()">
							<option value="all">{LANG.hangxe}</option>
							<!-- BEGIN: procuder -->
							<option value="{PROCUDER.id}">{PROCUDER.title}</option>
							<!-- END: procuder -->
						</select>
					</p>
					<p>
						<span><strong>{LANG.mauxe}</strong></span>
						<select id="car_model" onchange="showResult()">
							<option value="all">{LANG.mauxe}</option>
						</select>
					</p>
					<p>
						<span><strong>{LANG.noidangky}</strong></span>
						<select name="location" id="location" onchange="showResult();">
							<option value="0">{LANG.noidangky}</option>
							<!-- BEGIN: location -->
							<option value="{LOCATION.provinceid}">{LOCATION.title}</option>
							<!-- END: location -->
						</select>
					</p>
				</div>
				<div id='show_price'></div>
				<p class="mota">{LANG.note_chiphi}</p>
			</div>
			<div class="col-md-16">
				<div class="col-md-16">
					<h2 class="tieude">{LANG.muatragop}</h2>
					<div role="tabpanel" id="container_tabs" class="tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
								<a href="#tragop" aria-controls="tragop" role="tab" data-toggle="tab">
									<em class="fa fa-bars">&nbsp;</em>
									<span>{LANG.uoctinhsotientra}</span>
								</a>
							</li>
							<li role="presentation">
								<a href="#sotiencothevay" aria-controls="sotiencothevay" role="tab" data-toggle="tab">
									<em class="fa fa-bars">&nbsp;</em>
									<span>{LANG.sotiencothevay}</span>
								</a>
							</li>
						</ul>

						<div id="content_tabs" class="tab-content">
							<div role="tabpanel" class="tab-pane fade active in" id="tragop">
								<div class="width_common">
									<div class="col_left">
										<p>{LANG.sotienvay}</p>
										<input id="money_loan" class="number-slider" type="text" type="number" min="0" max="5000000000" data-value="0" data-slider="slider" data-call-back="caculatorInterest" placeholder="0 {LANG.vnd}" />
									</div>
									<div class="col_right">
										<div class="process_bar">
											<div class="price-slider">
												<div id="slider" class="slider-custom" data-input="money_loan" data-max='5000000000' data-step="1000000" data-call-back="caculatorInterest" ></div>
											</div>
										</div>
										<p class="min">0 {LANG.vnd}</p>
										<p class="max">5.000.000.000 {LANG.vnd}</p>
									</div>
								</div>
								<div class="width_common">
									<div class="col_left">
										<p>{LANG.kyhanvay}</p>
										<input id="term_loan" class="number-slider" type="number" min="0" max="25" step="1" data-slider="slider_year" data-call-back="caculatorInterest" placeholder="0 năm" />
									</div>
									<div class="col_right">
										<div class="process_bar">
											<div id="slider_year" class="slider-custom" data-input="term_loan" data-max='25' data-step="1" data-call-back="caculatorInterest"></div>
										</div>
										<p class="min">0 {LANG.year}</p>
										<p class="max">25 {LANG.year}</p>
									</div>
								</div>
								<div class="width_common">
									<div class="col_left">
										<p>{LANG.laixuat}</p>
										<input id="rate" class="number-formart" type="number" min="0" max="20" step="0.1" data-call-back="caculatorInterest" placeholder="0% /năm" />
									</div>
								</div>
								<i class="note"><span>*</span>{LANG.note_tinh_lai_vay}</i>
							</div>
							<div role="sotiencothevay" class="tab-pane fade in" id="sotiencothevay">

								<div class="width_common">
									<div class="col_left">
										<p>{LANG.thunhap}</p>
										<input id="money_income" class="number-slider" type="text" type="number" min="0" max="300000000" data-value="0" data-slider="slider_income" data-call-back="caculatorBorrowed" placeholder="0 {LANG.vnd}" />
									</div>
									<div class="col_right">
										<div class="process_bar">
											<div class="price-slider">
												<div id="slider_income" class="slider-custom" data-input="money_income" data-max='300000000' data-step="500000" data-call-back="caculatorBorrowed" ></div>
											</div>
										</div>
										<p class="min">0 {LANG.vnd}</p>
										<p class="max">300.000.000 {LANG.vnd}</p>
									</div>
								</div>

								<div class="width_common">
									<div class="col_left">
										<p>{LANG.chitieu}</p>
										<input id="money_pay" class="number-slider" type="text" type="number" min="0" max="300000000" data-value="0" data-slider="slider_pay" data-call-back="caculatorBorrowed" placeholder="0 VNĐ" />
									</div>
									<div class="col_right">
										<div class="process_bar">
											<div class="price-slider">
												<div id="slider_pay" class="slider-custom" data-input="money_pay" data-max='300000000' data-step="1000000" data-call-back="caculatorBorrowed" ></div>
											</div>
										</div>
										<p class="min">0 {LANG.vnd}</p>
										<p class="max">300.000.000 {LANG.vnd}</p>
									</div>
								</div>

								<div class="width_common">
									<div class="col_left">
										<p>{LANG.kyhanvay}</p>
										<input id="time_tax" class="number-slider" type="number" min="0" max="25" data-value="0" data-slider="slider_time_tax" data-call-back="caculatorBorrowed" placeholder="0 {LANG.year}" />
									</div>
									<div class="col_right">
										<div class="process_bar">
											<div class="price-slider">
												<div id="slider_time_tax" class="slider-custom" data-input="time_tax" data-max='25' data-step="1" data-call-back="caculatorBorrowed" ></div>
											</div>
										</div>
										<p class="min">0 {LANG.year}</p>
										<p class="max">25 {LANG.year}</p>
									</div>
								</div>
								<div class="width_common">
									<div class="col_left">
										<p>{LANG.laixuat}</p>
										<input id="rate_tax" class="number-formart" type="number" min="0" max="20" step="0.1" data-call-back="caculatorBorrowed" placeholder="0% /năm" />
									</div>
								</div>
								<i class="note"><span>*</span> {LANG.note_tinh_lai_vay}</i>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div id="result" class="ketqua"></div>
				</div>
		</div>
		<script type="text/javascript">
			var list_brand = {LIST_BRAND};
			var tax_location = {LIST_LOCATION};

            var list_cars 		= {};
            $(document).ready(function() {
                $(".open_lightbox3").fancybox({
                    width 		: 400,
                    height 		: 230,
                    autoSize    : false,
                    closeClick  : false,
                    openEffect  : 'none',
                    closeEffect : 'none'
                });

                $(".number-slider").bind('keyup mouseup', function () {
                    var number = parseInt($(this).val().replace(/\D+/g, '')) || 0;
                    var call_back = $(this).attr("data-call-back");
                    if(number > parseInt($(this).attr('max'))) number = parseInt($(this).attr('max'));
                    $(this).attr('data-value',number);
                    $(this).val(FormatNumber(number));
                    var slider = $(this).attr('data-slider');
                    $("#" + slider).slider('value',number);
                    var cb = window[call_back];
                    if(typeof cb === 'function') { cb(); }
                });

                $(".slider-custom").each(function(index){
                    var max = $(this).attr('data-max');
                    var step = $(this).attr("step");
                    var input = $(this).attr("data-input");
                    var call_back = $(this).attr("data-call-back");
                    $(this).slider({
                        range: "min",
                        animate	: true,
                        min:0,
                        max: max,
                        step: step,
                        slide: function(event, ui) {
                            $('#'+input).attr('data-value',ui.value);
                            var money = FormatNumber(ui.value);
                            $('#'+input).val(money);
                            var cb = window[call_back];
                            if(typeof cb === 'function') cb();
                        }
                    });
                })
            });

            $(".number-formart").bind('keyup mouseup', function () {
                var call_back = $(this).attr("data-call-back");
                if(parseInt($(this).val()) > parseInt($(this).attr('max'))) $(this).val($(this).attr('max'));
                var cb = window[call_back];
                if(typeof cb === 'function') cb();
            });

            $("#view-detail-car").click(function(){
                var car_id = $("#car_model").val(); if(!car_id) return false;
                window.location.assign("/interactive/2016/bang-gia-xe/chi-tiet-" + car_id + ".html");
            })
            $("#view-detail-car-1").click(function(){
                var car_id = $("#car_model").val(); if(!car_id) return false;
                window.location.assign("/interactive/2016/bang-gia-xe/chi-tiet-" + car_id + ".html");
            })


            function caculatorInterest(){
                var money_loan = parseInt($('#money_loan').attr('data-value')); if(!money_loan) return false; 	// tiền vay
                var term_loan  = parseInt($('#term_loan').attr('data-value')); if(!term_loan) return false; // thời gian vay
                var rate 	   = parseFloat($('#rate').val()) || 0; // lãi xuất
                term_loan = term_loan * 12;
                // tiền phải trả hàng tháng
                var pay_by_month = parseInt(money_loan / term_loan);
                // tiền phải trả tháng đầu
                //Số tien trả tháng đầu tiên = (Số tiên ban đầu vay / số tháng vay) + (((số tiền ban đầu vay) *(lai suat/12)) / 100)
                var fist_pay =  (money_loan / term_loan) + ( ( money_loan * ( rate / 12 ) ) / 100 );
                fist_pay = parseInt(fist_pay);

                var sum 			= 0; // tổng lãi cộng gốc
                var interest 		= 0; // lãi
                var sum_interest 	= 0; // tổng lãi
                var tmp_money_loan 	= money_loan;

                for(var i=0; i < term_loan; i++){
                    interest = parseInt(tmp_money_loan / 12 * rate / 100); // tính lãi
                    sum_interest = sum_interest + interest;
                    tmp_money_loan = tmp_money_loan - pay_by_month; // gốc còn lại theo từng tháng
                }

                sum 			= money_loan + sum_interest;
                sum 			= FormatNumber(sum);
                sum_interest 	= FormatNumber(sum_interest); // tổng lãi
                fist_pay 		= FormatNumber(fist_pay); // số tiền phải trả lần đầu

                var html = '<p><strong>Số tiền trả tháng đầu</strong> <br><span class="money"> '+ fist_pay +' VND</span></p>' +
                    '<p><strong>Tổng phải trả</strong> <br><span class="money"> '+ sum +' VND</span></p>' +
                    '<p><strong>Tổng lãi</strong><br><span class="money"> '+ sum_interest +' VND</span></p>'
                ;
                $("#result").html(html);
            }

            function caculatorBorrowed(){
                var money_income = parseInt($('#money_income').attr('data-value')); if(!money_income) return false; // thu nhập
                var money_pay 	 = parseInt($('#money_pay').attr('data-value')) || 0 ;								// Chi tiêu
                var time_tax 	 = parseInt($('#time_tax').attr('data-value')); if(!time_tax) return false;			// thời hạn vay
                var rate_tax     = parseFloat($('#rate_tax').val()) || 0;											// lãi suất
                time_tax = time_tax * 12;
                var money_borrowed = (0.9 * money_income - money_pay ) / (1 / time_tax + (rate_tax / 12 / 100));
                money_borrowed = parseInt(money_borrowed) > 0  ? parseInt(money_borrowed) : 0;
                money_borrowed = FormatNumber( money_borrowed );
                var html = '<p><strong>Số tiền có thể vay</strong><br><span class="money"> '+ money_borrowed +' VND</span></p>';
                $("#result").html(html);
            }

            function changeBrand(){
                var brand_value = $('#brand').val();
                $('#car_model').html('<option value="all">Mẫu xe</option>');
                $('#show_price').html("");
                if( brand_value != 'all' ){
                    list_cars = list_brand[brand_value]['cars'];
                    $.each(list_cars, function(k, v){
                        if(typeof(v.car_name) != 'undefined'){
                            $('#car_model').append('<option value="' + k + '">' + v.car_name + '</option>');
                        }
                    });
                }
                showResult();
            }

            function changeBrandCompare(){
                var brand_value = $('#brand_compare').val();
                $('#car_model_compare').html('<option value="all">Mẫu xe</option>');
                if( brand_value != 'all' ){
                    list_cars = list_brand[brand_value]['cars'];
                    $.each(list_cars, function(k, v){
                        if(typeof(v.car_name) != 'undefined'){
                            $('#car_model_compare').append('<option value="' + k + '">' + v.car_name + '</option>');
                        }
                    });
                }
            }

            function showResult(){
                $('#show_price').html("");
                $("#box-compare").hide();
                var brand  				= $('#brand').val(); 		if(brand == 'all') 		return false;
                var car_id 				= $('#car_model').val();	if(car_id == 'all')		return false;
                var location 			= $('#location').val();		if(location == 0)	    return false;

                var car_detail 			= list_brand[brand]['cars'][car_id];

                var phi_duong_bo 		= {CONFIG.road_use};
                var phi_dang_kiem 		= {CONFIG.registration};
                var image 				= car_detail.car_image;

                var car_price 			= parseInt(car_detail.car_price) * 1000000;
                var gia_dam_pham   		= parseInt(car_detail.negotation_price);
                var phi_bien_so  		= parseInt(tax_location[location][car_detail.car_type]['license_plate_fee']);
                var phi_truoc_ba  		= parseInt(tax_location[location][car_detail.car_type]['registration_fee']);
                var phantram_truocba    = "(" + phi_truoc_ba + "%)";
                phi_truoc_ba 			= (car_price * phi_truoc_ba) / 100
                var phi_bao_hiem		= 0;
                if( car_detail.car_seats == 4 ){
                    phi_bao_hiem		= {CONFIG.civil_insurance_4};
                }else if( car_detail.car_seats == 5 ){
                    phi_bao_hiem		= {CONFIG.civil_insurance_5};
                }else if( car_detail.car_seats == 6 ){
                    phi_bao_hiem		= {CONFIG.civil_insurance_6};
                }else if( car_detail.car_seats == 7 ){
                    phi_bao_hiem		= {CONFIG.civil_insurance_7};
                }



                phi_truoc_ba = parseInt(phi_truoc_ba);
                phi_bao_hiem = parseInt(phi_bao_hiem);

                var tong_chi_phi  = gia_dam_pham + phi_truoc_ba + phi_duong_bo + phi_bao_hiem + phi_bien_so + phi_dang_kiem;

                gia_dam_pham = FormatNumber(gia_dam_pham ) 	+ " ₫";
                phi_truoc_ba = FormatNumber(phi_truoc_ba ) 	+ " ₫";
                phi_duong_bo = FormatNumber(phi_duong_bo ) 	+ " ₫";
                phi_bao_hiem = FormatNumber(phi_bao_hiem ) 	+ " ₫";
                phi_bien_so = FormatNumber(phi_bien_so ) 	+ " ₫";
                phi_dang_kiem = FormatNumber(phi_dang_kiem ) 	+ " ₫";
                tong_chi_phi = FormatNumber(tong_chi_phi ) 	+ " ₫";

                var html = '<ul class="thongtin">' +
                    '<li>{LANG.price_negotiate}<span class="text_bold">' + gia_dam_pham + '</span></li>' +
                    '<li>{LANG.phitruocba} ' + phantram_truocba + '<span>' + phi_truoc_ba + '</span></li>' +
                    '<li>{LANG.road_use}<span>' + phi_duong_bo + '</span></li>' +
                    '<li>{LANG.civil_insurance}<span>' + phi_bao_hiem + '</span></li>' +
                    '<li>{LANG.license_plate_fee}<span>' + phi_bien_so + '</span></li>' +
                    '<li>{LANG.registration}<span>' + phi_dang_kiem + '</span></li>' +
                    '<li class="text_bold">{LANG.tongcong}<span style="color:#a0214e;">' + tong_chi_phi + '</span></li>' +
                    '</ul>' +
                    '<p class="text_center"><img src="' + image + '" width="359" height="216" alt=""></p>'
                ;
                $('#show_price').html(html);

            }
            function compare_price(){
                var brand  				= $('#brand').val(); 		if(brand == 'all') 			return false;
                var car_model 			= $('#car_model').val();	if(car_model == 'all')		return false;
                var location 			= $('#location').val();		if(location == 0)	    	return false;

                var brand_compare  				= $('#brand_compare').val(); 		if(brand_compare == 'all') 			return false;
                var car_model_compare 			= $('#car_model_compare').val();	if(car_model_compare == 'all')		return false;
                var location_compare 			= $('#location_compare').val();		if(location_compare == 0)	    	return false;
            }

		</script>

	</div>
</div>
<!-- END: main -->