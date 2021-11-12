<!-- BEGIN: main -->
<div class="widget-content">
    <!-- BEGIN: khoahoc -->
    <h2 class="margin-bottom-lg margin-top-lg">{LANG.khoahocdamua}</h2>
    <div class=" panel panel-default">
        <div class="panel-body bg-lavender">
            <!-- BEGIN: loop -->
            <div class="col-xs-24 col-sm-5 col-md-3">
                <a href="{KHOAHOC.link}" title="{KHOAHOC.title}"><img src="{KHOAHOC.image}" title="{KHOAHOC.title}" class="img-thumbnail"></a>
            </div>
            <div class="col-xs-24 col-sm-19 col-md-21">
                <h3><a href="{KHOAHOC.link}" title="{KHOAHOC.title}">{KHOAHOC.title}</a></h3>
                <p>{KHOAHOC.hometext}</p>
            </div>
            <!-- END: loop -->
        </div>
    </div>
    <!-- END: khoahoc -->
    <!-- BEGIN: baihoc -->
    <h2 class="margin-bottom-lg margin-top-lg">{LANG.baigiangdamua}</h2>
    <div class=" panel panel-default">
        <div class="panel-body">
            <ul class="baihoc">
            <!-- BEGIN: loop -->
            <li>
                <a href="{BAIHOC.link}" title="{BAIHOC.title}">{BAIHOC.title}</a>
            </li>
            <!-- END: loop -->
            </ul>
        </div>
    </div>
    <!-- END: baihoc -->
</div>
<!-- END: main -->