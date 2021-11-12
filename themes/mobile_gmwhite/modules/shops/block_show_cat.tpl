<!-- BEGIN: main -->
<div class="row">
    <div class="sanpham">
        <!-- BEGIN: loop -->
        <div class="col-md-8 col-sm-8 col-xs-12 product">
            <div class="item wow slideInLeft  animated animated" style="visibility: visible; animation-name: slideInLeft;">
                <a title="{DATA.title}" href="{DATA.link}">
                    <img alt="{DATA.title}" class="img-thumbnail img-cat-product" data-hover="{DATA.image_hover}" data-image="{DATA.image}"  src="{DATA.image}">
                    <div class="details">
                        <h2>{DATA.title_clean}</h2>
                    </div>
                </a>
            </div>
        </div>
        <!-- end: loop -->
    </div>
</div>
<script type="text/javascript">
    $('.product').hover(function(){
        var obj = $(this).find('img');
        obj.attr('src', obj.attr('data-hover'));
    });
    $('.product').mouseout(function(){
        var obj = $(this).find('img');
        obj.attr('src', obj.attr('data-image'));
    });
    $('.details').hover(function(){
        var obj = $(this).parent().find('img');
        obj.attr('src', obj.attr('data-hover'));
    });
    $('.details').mouseout(function(){
        var obj = $(this).parent().find('img');
        obj.attr('src', obj.attr('data-image'));
    });
</script>
<!-- END: main -->