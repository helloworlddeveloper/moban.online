<!-- BEGIN: main -->

<!-- BEGIN: catbox -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a title="{catbox.title}" href="{catbox.link}">{catbox.title}</a>
            <!-- BEGIN: subcatbox -->
            <!-- BEGIN: listsubcat -->
            <span class="divider">></span> <a title="{listsubcat.title}" href="{listsubcat.link}">{listsubcat.title}</a>
            <!-- END: listsubcat -->
            <!-- BEGIN: more -->
            <em class="pull-right"><small><a title="{LANG.categories_viewall}" href="{MORE}"><em class="fa fa-search fa-lg">&nbsp;</em>{LANG.categories_viewall}</a></small></em>
            <!-- END: more -->
            <!-- END: subcatbox -->
            <!-- BEGIN: is_addfile_allow -->
            <em class="pull-right"><small><a title="{LANG.upload_to}" href="{catbox.uploadurl}"><em class="fa fa-upload fa-lg">&nbsp;</em>{LANG.upload_to}&nbsp;&nbsp;&nbsp;</a></small></em>
            <!-- END: is_addfile_allow -->
        </h4>
    </div>
    <ul class="list-group">
        <!-- BEGIN: itemcat -->
        <li class="list-group-item"><a title="{itemcat.title}" href="{itemcat.more_link}">{itemcat.title}</a></li>
        <!-- END: itemcat -->
    <!-- BEGIN: related -->
        <!-- BEGIN: loop -->
        <li class="list-group-item"><a title="{loop.title}" href="{loop.more_link}">{loop.title}</a></li>
        <!-- END: loop -->
    <!-- END: related -->
    </ul>
</div>
<!-- END: catbox -->

<!-- BEGIN: filelist -->
<h2 class="m-bottom pull-left">{CAT_TITLE}</h2>
{FILE_LIST}
<!-- END: filelist -->

<!-- END: main -->