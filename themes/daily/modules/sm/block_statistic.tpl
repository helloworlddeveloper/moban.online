<!-- BEGIN: main -->
<style>
    .tableUsers .userRow{
        -moz-box-align:center;
        align-items:center;
        border-top:1px solid #fafbfb;
        display:flex;
        padding:0px 3px
    }
    .tableUsers .userRow .score{
        -moz-box-flex:1;
        flex:1 1 0;
        font-weight:600
    }
    .userRow .number{
        padding-right:10px;
        font-weight:bold;
        font-size:16px
    }
    .user-avatar-component{
        -moz-box-align:center;
        align-items:center;
        display:flex
    }
    .user-avatar-component .avatar{
        width:50px
    }
    .user-avatar-component .avatar span{
        -moz-box-align:center;
        -moz-box-pack:center;
        align-items:center;
        display:flex;
        height:100%;
        justify-content:center;
        width:100%
    }
    .user-avatar-component .information{
        margin-left:10px
    }
    .name-label{
        font-size:11px;
        color:#bbb
    }
    .user-avatar-component img{
        border-radius:50%;
        height:50px
    }
    .text-red{
        font-size: 16px;
        color: #d00078;
    }
    .text-blue{
        color: #0FA015;
    }
    .text-header{
        font-size: 18px;
        font-weight: bold;
        padding: 20px;
    }
</style>

<div class="row">
    <div class="text-header text-center">VINH DANH DOANH SỐ CÁC NPP
        <br><span style="color:#f00">BEST SELLER THÁNG {DATE}</span></div>
    <!-- BEGIN: showmain -->
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="items">
            <div class="col-md-4 col-sm-4 col-xs-4">
                <img class="img-thumbnail" title="{ROW.title}" alt="{ROW.title}" src="{ROW.photo}">
            </div>
            <div class="col-md-8 col-sm-8 col-xs-8">
                <div class="name">{ROW.fullname}</div>
                <div>Khu vực: {ROW.province}</div>
                <div>Mã: {ROW.code}</div>
                <div>SĐT: {ROW.mobile}</div>
                <div><strong class="text-red">Doanh số: {ROW.total_price}</strong></div>
            </div>
        </div>
    </div>
    <!-- END: showmain  -->
    <!-- BEGIN: showsub -->
    <div class="tableUsers col-md-6 col-sm-6 col-xs-12" style="padding-top: 10px">
        <!-- BEGIN: loop -->
        <div class="userRow">
            <div class="member" style="flex: 2 1 0%;">
                <div>
                    <div class="user-avatar-component text-none">
                        <div class="avatar avatar-component"><img title="{ROW.fullname}" alt="{ROW.fullname}" src="{ROW.photo}"></div>
                        <div class="information">
                            <div class="name">{ROW.fullname}</div>
                            <div class="name-label">Khu vực: {ROW.province} - Mã: {ROW.code} </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="score" style="text-align: right;">
                <span class="text-red">Doanh số: {ROW.total_price}</span>
            </div>
        </div>

        <!-- END: loop -->
    </div>
    <!-- END: showsub  -->
</div>
<!-- END: main -->