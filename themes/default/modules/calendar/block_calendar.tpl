<!-- BEGIN: main -->
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/{mod_file}.css" rel="stylesheet" />
<!-- BEGIN: desktop -->
<div id="calendar" class="fc fc-ltr">
	<table class="fc-header" style="width:100%">
		<tbody>
		<tr>
			<td class="fc-header-left">
				<div style="padding-bottom: 10px">
					<button style="width: 100px;overflow: hidden;" type="button" class="btn btn-success">Buổi sáng</button>
					<button style="width: 100px;overflow: hidden;" type="button" class="btn btn-primary">Buổi chiều</button>
					<button style="width: 100px;overflow: hidden;" type="button" class="btn btn-danger">Buổi tối</button>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
	<div role="tabpanel" class="tabs">
		<ul class="nav nav-tabs" role="tablist">
			<!-- BEGIN: tabs_title -->
			<li role="presentation" <!-- BEGIN: active -->class="active"<!-- END: active -->>
			<a href="#tab{TABS_TITLE.id}" aria-controls="tab{TABS_TITLE.id}" role="tab" data-toggle="tab">
				<span>{TABS_TITLE.title}</span>
			</a>
			</li>
			<!-- END: tabs_title -->
		</ul>
		<div class="tab-content" style="padding-top: 10px">
			<!-- BEGIN: tabs_content -->
			<div role="tabpanel" class="tab-pane fade <!-- BEGIN: active -->active in<!-- END: active -->" id="tab{TABS_TITLE.id}">
				<div class="fc-content" style="position: relative;">
					<span class="text-center">
                  <h2 style="text-transform: uppercase">{TABS_TITLE.title} THÁNG {DATE_YEAR}</h2>
				   <p>Chịu trách nhiệm nội dung: P.TGĐ Lê Trọng Tấn</p>
               </span>
					<div class="fc-view fc-view-month fc-grid" style="position:relative" unselectable="on">
						<table class="fc-border-separate" style="width:100%" cellspacing="0">
							<thead>
							<tr class="fc-first fc-last">
								<th class="fc-day-header fc-mon fc-widget-header fc-first" style="width: 170px;">Thứ 2</th>
								<th class="fc-day-header fc-tue fc-widget-header" style="width: 160px;">Thứ 3</th>
								<th class="fc-day-header fc-wed fc-widget-header" style="width: 160px;">Thứ 4</th>
								<th class="fc-day-header fc-thu fc-widget-header" style="width: 160px;">Thứ 5</th>
								<th class="fc-day-header fc-fri fc-widget-header" style="width: 160px;">Thứ 6</th>
								<th class="fc-day-header fc-sat fc-widget-header" style="width: 160px;">Thứ 7</th>
								<th class="fc-day-header fc-sun fc-widget-header fc-last">Chủ nhật</th>
							</tr>
							</thead>
							<tbody>
							<!-- BEGIN: rows -->
							<tr class="fc-week">
								<!-- BEGIN: loop -->
								<td class="fc-day fc-mon fc-widget-content fc-past{DATA.currentday}">
									<div style="min-height: 106px;">
										<div class="fc-day-number">{DATA.day}</div>
										<div class="fc-day-content">
											<ul class="items">
												<!-- BEGIN: data -->
												<li class="item">
													<div class="{CONTENT.class} btn-danger">
														<span><i class="fa fa-clock-o">&nbsp;</i>{CONTENT.timeevent_begin} - {CONTENT.timeevent_end}</span>: <strong>{CONTENT.title}</strong>
														<br><i class="fa fa-user">&nbsp;</i>{CONTENT.moderator}
														<br><i class="fa fa-map-marker">&nbsp;</i>{CONTENT.addressevent}
														<p>{CONTENT.description}</p>
													</div>
												</li>
												<!-- END: data -->
											</ul>
										</div>
									</div>
								</td>
								<!-- END: loop -->
							</tr>
							<!-- END: rows -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- END: tabs_content -->
		</div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {$("[data-rel='block_tooltip'][data-content!='']").tooltip({
			placement: "top",
			html: true,
			title: function(){return  '<div><h3>' + $(this).data('title')+ '</h3>'+
			'<div><i class="fa fa-clock-o">&nbsp;</i><strong>Thời gian:</strong> ' + $(this).data('time')+ '</div>'+
			'<div><i class="fa fa-user">&nbsp;</i><strong>Chủ trì:</strong> ' + $(this).data('moderator')+ '</div>'+
			'<div><i class="fa fa-map-marker">&nbsp;</i><strong>Địa điểm:</strong> ' + $(this).data('addressevent')+ '</div>'+
			'<div><i class="fa fa-eye">&nbsp;</i><strong>Thành phần tham gia:</strong> ' + $(this).data('participants')+ '</div>'+
			'<div><strong>Nội dung:</strong> ' + $(this).data('description')+ '</div>'+
			'</div>';}
			});});
</script>
<!-- END: desktop -->
<!-- BEGIN: mobile -->
<div id="calendar" class="fc fc-ltr">
	<table class="fc-header" style="width:100%">
		<tbody>
		<tr>
			<td class="fc-header-left">
				<div style="padding-bottom: 10px">
					<h2 class="text-center">LỊCH LÀM VIỆC THÁNG 10/2018</h2>
					<button style="width: 100px;overflow: hidden;" type="button" class="btn btn-success">Buổi sáng</button>
					<button style="width: 100px;overflow: hidden;" type="button" class="btn btn-primary">Buổi chiều</button>
					<button style="width: 100px;overflow: hidden;" type="button" class="btn btn-danger">Buổi tối</button>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
	<div role="tabpanel" class="tabs">
		<ul class="nav nav-tabs" role="tablist">
			<!-- BEGIN: tabs_title -->
			<li role="presentation" <!-- BEGIN: active -->class="active"<!-- END: active -->>
			<a href="#tab{TABS_TITLE.id}" aria-controls="tab{TABS_TITLE.id}" role="tab" data-toggle="tab">
				<span>{TABS_TITLE.title}</span>
			</a>
			</li>
			<!-- END: tabs_title -->
		</ul>
		<div class="tab-content" style="padding-top: 10px">
			<!-- BEGIN: tabs_content -->
			<div role="tabpanel" class="tab-pane fade <!-- BEGIN: active -->active in<!-- END: active -->" id="tab{TABS_TITLE.id}">
				<div class="fc-content" style="position: relative;">
					<span class="text-center">
                  <h2 style="text-transform: uppercase">{TABS_TITLE.title} THÁNG {DATE_YEAR}</h2>
				   <p>Chịu trách nhiệm nội dung: P.TGĐ Lê Trọng Tấn</p>
               </span>
					<div class="fc-view fc-view-month fc-grid" style="position:relative" unselectable="on">
						<div class="fc-border-separate" style="width:100%" cellspacing="0">
							<!-- BEGIN: rows -->
							<div class="fc-week" style="border-bottom: 1px solid #ccc;">
								<!-- BEGIN: loop -->
								<div style="border-bottom: 1px solid #ccc; clear:both" class="fc-day fc-mon fc-past{DATA.currentday}">
									<div style="margin: 10px 0 0;" class="clearfix">
										<div class="col-xs-2 text-center" style="color: #0FA015;font-weight: bold;">{CONTENT.timeevent_mobile}</div>
										<div class="col-xs-10">
											<ul class="items" style="padding: 0;list-style: none">
												<!-- BEGIN: data -->
												<li class="item">
													<div class="{CONTENT.class} btn-danger">
														<span><i class="fa fa-clock-o">&nbsp;</i>{CONTENT.timeevent_begin} - {CONTENT.timeevent_end}</span>: <strong>{CONTENT.title}</strong>
														<br><i class="fa fa-user">&nbsp;</i>{CONTENT.moderator}
														<br><i class="fa fa-map-marker">&nbsp;</i>{CONTENT.addressevent}
														<p>{CONTENT.description}</p>
													</div>
												</li>
												<!-- END: data -->
											</ul>
										</div>
									</div>
								</div>
								<!-- END: loop -->
							</div>
							<!-- END: rows -->
						</div>
					</div>
				</div>
			</div>
			<!-- END: tabs_content -->
		</div>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {$("[data-rel='block_tooltip'][data-content!='']").tooltip({
			placement: "top",
			html: true,
			title: function(){return  '<div><h3>' + $(this).data('title')+ '</h3>'+
			'<div><i class="fa fa-clock-o">&nbsp;</i><strong>Thời gian:</strong> ' + $(this).data('time')+ '</div>'+
			'<div><i class="fa fa-user">&nbsp;</i><strong>Chủ trì:</strong> ' + $(this).data('moderator')+ '</div>'+
			'<div><i class="fa fa-map-marker">&nbsp;</i><strong>Địa điểm:</strong> ' + $(this).data('addressevent')+ '</div>'+
			'<div><i class="fa fa-eye">&nbsp;</i><strong>Thành phần tham gia:</strong> ' + $(this).data('participants')+ '</div>'+
			'<div><strong>Nội dung:</strong> ' + $(this).data('description')+ '</div>'+
			'</div>';}
			});});
</script>
<!-- END: mobile -->
<!-- END: main -->