
define(["jquery-typeahead"], function (Typeahead) {
    var data = {
        
        news: [  "10 CÁCH TRỊ MỤN ĐẦU ĐEN HIỆU QUẢ TẠI NHÀ 100&#x25; THIÊN NHIÊN", "Bí quyết trị rạn da hiệu quả nhất ngay tại nhà", "CÁCH CHĂM SÓC DA MẶT ĐƠN GIẢN CHO MÙA ĐÔNG", "Chăm sóc sức khỏe và sắc đẹp&#33;", "Chương trình chuyển giao công nghệ tại GM Spa", "CTCP PTCN GM White cùng &quot;Ngày hội tình nguyện quốc gia 2017&quot;", "Da trắng mịn và trẻ ra 10 tuổi nhờ uống nước đậu đen", "Du lịch đồng đội", "Mẹo trị thâm quầng mắt", "Nguyên nhân gây da khô vào mùa đông", "Những thực phẩm tốt cho da", "Team build GM White", "Trị nám tự nhiên hiệu quả nhanh tại nhà", "Trị thâm môi hiệu quả bằng thiên nhiên", "Xông hơi làm đẹp da mặt với nguyên liệu thiên nhiên tại nhà.",],
        
        shops: [  "COMBO 1&#x3A; DẸP TAN NHỮNG VẾT NÁM TRÊN DA", "COMBO 2&#x3A; CÔNG THỨC HOÀN MỸ CHỐNG LÃO HÓA DA", "COMBO 3&#x3A; LIỆU TRÌNH SẠCH MỤN TỪ SÂU BÊN TRONG", "COMBO 4&#x3A; BÍ QUYẾT TRỊ NẺ CHO LÀN DA MỀM MẠI MÙA KHÔ HANH", "COMBO 5&#x3A; CÁC BƯỚC CỦA LÀN DA TỎA SÁNG NGÀY HÈ", "COMBO 6&#x3A; LIỆU TRÌNH DẸP TAN MỠ THỪA ĐỂ LẠI VÓC DÁNG NGỌC NGÀ", "Gel tẩy tế bào chết Radiant", "Gel tẩy tế bào chết Radiant cho body", "Gel tẩy tế bào chết Radiant cho da mụn", "Gel tẩy tế bào chết Radiant cho da nám", "Gel tẩy tế bào chết Radiant chống lão hóa", "Kem nền trang điểm - trắng da - chống nắng SPRING", "Kem tan mỡ hiệu quả cao CURVE", "Kem trị sạch nám cao cấp Superior", "Miracle - Chuyên gia điều trị tận gốc các loại mụn", "Serum VIGOR - Bí quyết của làn da không tuổi", "Silky Touch - công thức hoàn hảo dưỡng da nẻ", "Sữa rửa mặt hoàn hảo Glowing", "Sữa rửa mặt hoàn hảo Glowing cho da mụn", "Sữa rửa mặt hoàn hảo Glowing cho da nám",],
        
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
            
            'Tin Tức': {
                data: data.news
            },
            
            'Sản phẩm': {
                data: data.shops
            },
            
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
