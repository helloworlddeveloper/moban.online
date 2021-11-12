<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/jquery.typeahead.css" />
<script src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/require.min.js"></script>
    <script>
        requirejs.config({
            waitSeconds: 1,
            baseUrl: '',
            paths: {
                'jquery': '{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/jquery-2.2.0.min',
                'jquery-typeahead': '{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/jquery.typeahead.min'
            },
            shim: {
                'jquery-typeahead': {
                    deps: ['jquery']
                }
            },
            priority: [
                'jquery'
            ]
        });
        requirejs(["{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/data.js"]);
    </script>

    <div class="headerSearch typeahead__container">
        <div class="typeahead__field">
        <span class="typeahead__query">
            <input class="js-typeahead" placeholder="Tìm kiếm mọi thứ..." name="search_items" type="search" autofocus autocomplete="off" />
        </span>
        <span class=" typeahead__button">
            <button type="button" data-url="/seek/?q=" data-minlength="3" data-click="y">
                <span class="typeahead__search-icon"></span>
            </button>
        </span>
        </div>
    </div>

<!-- END: main -->
