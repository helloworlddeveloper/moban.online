<!-- BEGIN: main -->
define(["jquery-typeahead"], function (Typeahead) {
    var data = {
        <!-- BEGIN: module -->
        {module_name}: [ <!-- BEGIN: loop --> "{KEYWORD}",<!-- END: loop -->],
        <!-- END: module -->
    };

    typeof $.typeahead === 'function' && $.typeahead({
        input: ".js-typeahead",
        minLength: 1,
        order: "asc",
        group: true,
        offset: true,
        maxItem: 10,
        maxItemPerGroup: 3,
        groupOrder: function (node, query, result, resultCount, resultCountPerGroup) {

            var scope = this,
                sortGroup = [];

            for (var i in result) {
                sortGroup.push({
                    group: i,
                    length: result[i].length
                });
            }

            sortGroup.sort(
                scope.helper.sort(
                    ["length"],
                    false, // false = desc, the most results on top
                    function (a) {
                        return a.toString().toUpperCase()
                    }
                )
            );

            return $.map(sortGroup, function (val, i) {
                return val.group
            });
        },
        hint: true,
        dropdownFilter: "Tất cả",
        href: nv_base_siteurl + 'seek/?q={{display}}',
        template: "{{display}}",
        source: {
            <!-- BEGIN: module_show -->
            '{custom_title}': {
                data: data.{module_name}
            },
            <!-- END: module_show -->
        },
        callback: {
            onClickAfter: function (node, a, item, event) {
                window.location.href=item.href;

            },
            onResult: function (node, query, obj, objCount) {
                var text = "";
                if (query !== "") {
                    text = objCount + ' elements matching "' + query + '"';
                }
            }
        },
        debug: true
    });


});
<!-- END: main -->
<!-- BEGIN: main1 -->
$(function(){
  var currencies = [
    <!-- BEGIN: data -->{ value: '{data}' },<!-- END: data -->
  ];
  // setup autocomplete function pulling from currencies[] array
  $('.autocomplete').autocomplete({
    lookup: currencies,
    onSelect: function (suggestion) {
      window.location.href = nv_base_siteurl + 'seek/?q=' + suggestion.value;
    }
  });
  
});
<!-- END: main1 -->