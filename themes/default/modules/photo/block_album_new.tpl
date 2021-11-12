<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}modules/{mod_file}/lib-slider/responsiveslides.css" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}modules/{mod_file}/lib-slider/style.css" />
<script src="{NV_BASE_SITEURL}modules/{mod_file}/lib-slider/responsiveslides.min.js"></script>
<script type="text/javascript">
$(function () {
  // Slideshow 4
  $("#slider4").responsiveSlides({
    auto: false,
    pager: false,
    nav: true,
    speed: 500,
    namespace: "callbacks",
    before: function () {
      $('.events').append("<li>before event fired.</li>");
    },
    after: function () {
      $('.events').append("<li>after event fired.</li>");
    }
  });
});
</script>

<div class="callbacks_container">
  <ul class="rslides" id="slider4">
    <!-- BEGIN: loop_album -->
    <li>
      <a href="{ALBUM.link}" title="{ALBUM.name}"><img src="{ALBUM.thumb}" alt="{ALBUM.name}" /></a>
      <p class="caption">{ALBUM.name}</p>
    </li>
    <!-- END: loop_album -->
  </ul>
</div>
<!-- END: main -->