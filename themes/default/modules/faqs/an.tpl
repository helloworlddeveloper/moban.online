<!-- BEGIN: main -->
<span style =" margin-bottom: 10px; color: #1E84D3; font-weight: bold;">{LANG.numan} {most}</span><br /><br />
<div class="box670">
  
<div class="box655">
 <div class="shortqa">
  <h2>
   <a>{LANG.hoidap}</a>
  </h2>
  <a title="{LANG.book_question}" href="{LINK}" class="dangcauhoi">{LANG.book_question}</a>
 </div>
 <div class="b655cnt">
  
  <!-- BEGIN: an -->
 	<h4> <span style="margin-bottom: 0px;font-weight: bold; color: #f00; margin-left: 10px; ">{LANG.title} :</span> <a href="{LOOP.link}">{LOOP.title}</a></h4> 
 	<ul class="answer">
      	<!-- BEGIN: loop -->
      	<li>
       <h4>
       <a>{LOOP.cus_name}
        <span>&nbsp;-&nbsp;{LOOP.cus_email} | {LOOP.addtime}</span>
       </h4>
       <p>
        {LOOP.answer}
       </p>
       <p>
       <!-- BEGIN: file -->
       <span style="color: #000; font-weight: bold;">	{LANG.file}: </span><a id="myfile{LOOP.id}" href="{LOOP.links}" onclick="nv_download_files('{LOOP.links}');return false;">{LOOP.titles}</a>        
       </p>  
       <!-- END: file -->
       </li>    
      <!-- END: loop -->
      
   </ul>
<!-- END: an -->
<!-- BEGIN: page -->
  	<div class="phan_trang">
  	<span>{PAGE}</span>
  	</div>
  <!-- END: page -->
<br />
<div class="clear"></div>




 </div>
</div>
 </div>
 

<!-- END: main -->