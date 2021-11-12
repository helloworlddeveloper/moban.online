
$(function(){
  var currencies = [
    
    { value: '10 CÁCH TRỊ MỤN ĐẦU ĐEN HIỆU QUẢ TẠI NHÀ 100&#x25; THIÊN NHIÊN', data: 'abc' },
    
    { value: 'Chăm sóc sức khỏe và sắc đẹp&#33;', data: 'abc' },
    
    { value: 'Chia sẻ kinh nghiệm làm đẹp', data: 'abc' },
    
    { value: 'Chương trình chuyển giao công nghệ tại GM Spa', data: 'abc' },
    
    { value: 'CTCP PTCN GM White cùng &quot;Ngày hội tình nguyện quốc gia 2017&quot;', data: 'abc' },
    
    { value: 'Da trắng mịn và trẻ ra 10 tuổi nhờ uống nước đậu đen', data: 'abc' },
    
    { value: 'Du lịch đồng đội', data: 'abc' },
    
    { value: 'Team build GM White', data: 'abc' },
    
    { value: 'Tin khuyễn mãi', data: 'abc' },
    
    { value: 'Tin tức', data: 'abc' },
    
    { value: 'Trị nám tự nhiên hiệu quả nhanh tại nhà', data: 'abc' },
    
    { value: 'Tuyển dụng', data: 'abc' },
    
    { value: 'Video clip', data: 'abc' },
    
    { value: 'Xông hơi làm đẹp da mặt với nguyên liệu thiên nhiên tại nhà.', data: 'abc' },
    
  ];
  // setup autocomplete function pulling from currencies[] array
  $('#autocomplete').autocomplete({
    lookup: currencies,
    onSelect: function (suggestion) {
      var thehtml = '<strong>Currency Name:</strong> ' + suggestion.value + ' <br> <strong>Symbol:</strong> ' + suggestion.data;
      $('#outputcontent').html(thehtml);
    }
  });
  
});
