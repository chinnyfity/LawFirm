var site_url = $('#site_url').val();
var page_name = $('#page_name').val();
var token = $('#txt_token').val();


$(document).ready(function () {

  $(".form_clients").on('submit',(function(e) {
    e.preventDefault();
    $(".alert1, .overlay1").hide();
    var client_id = $(".client_id").val();
    var results = "";  
    var self = this;
    var caption = "created";
    $(".add_clients").attr('disabled', true).css({'opacity': '0.4'});
  
    $.ajax({
      type : "POST",
      data: new FormData(self),
      contentType: false,
      cache: false,
      processData:false,
      dataType: 'json',
      url: site_url + "/dashboard/add-clients",
      success : function(data){
        results += data.message;

        if(client_id != "") caption = "updated";
  
        if(data.status == 'success'){
          $(".add_clients").removeAttr('disabled').css({'opacity': '1'});
          Swal.fire("Successful", `Client's profile has been ${caption}`, "success");

          if(client_id == ""){
            $(".form_clients")[0].reset();
            var img_photo = $('#img_photo').attr('src1');
            $('#img_photo').attr('src', img_photo);
          }
          
        }else{          
          errorAlertDanger(results);
          alertMsg(5000);
  
          $(".add_clients").removeAttr('disabled').css({'opacity': '1'});
        }
      },error : function(data){
        $(".add_clients").removeAttr('disabled').css({'opacity': '1'});
        errorAlertDanger('Poor Network Connection!');
        alertMsg(5000);
      }
    });
  }));


  $(document).on("click", ".btn_delete", function (e) {
    var ids = $(this).attr('ids');
    var table = $(this).attr('table');
    var table_name = $(this).attr('table_name');
    var column = $(this).attr('column');
    
    Swal.fire({
      title: `Confirm action?`,
      html: `Are you sure you want to delete this client?`,
      icon: 'question',
      iconHtml: '?',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#999',
      confirmButtonText: 'Yes, delete'
    }).then((result) => {
      if (result.isConfirmed) {  
        Swal.fire({
          title: 'Deleting...',
          text: "Please wait a second for a response...",
          icon: 'success',
          showConfirmButton: false,
          confirmButtonColor: '#027937',
          cancelButtonColor: '#d33',
        });
  
        var datastring='ids='+ids
        +'&column='+column
        +'&_token='+token
        +'&table='+table
        +'&table_name='+table_name;
  
        $.ajax({
          type : "POST",
          // url : site_urls + "dashboard/delete_records",
          url: site_url + "/dashboard/delete-records",
          data : datastring,
          cache : false,
          success : function(data){
            $('#view_clients').DataTable().ajax.reload();
            Swal.fire({
              title: "Success",
              text: "A client has been deleted",
              icon: 'success',
              timer: 2000
            });
          }
        });
        return;
      }
    });
  });


  $(document).on("click", '.edit_client', function(e){
    var ids = $(this).attr("id");
    var mypage = $(this).attr("mypage")+"/";
    window.location = site_url + "/dashboard/" + mypage  + ids + "/";
  });



  $('body').on('click', '#img_preview span', function(e) {
    var srcs = $('#img_photo').attr('src1');
    $("#img_preview").html("<span>remove</span><img src='"+srcs+"' src1='"+srcs+"' id='img_photo'>");
    $(this).hide();
  });

  $('body').on('click', '#img_photo', function(e) {
    $("#img_picture").click();
  });

  $('body').on('change', '#img_picture', function(e) {
    var imgg = $("#img_photo");
    var img_preview = $("#img_preview");
    var fls = $("#img_picture").val();
    
    var fileExtension = ['jpeg', 'jpg', 'png'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
      alert("Formats allowed are : "+fileExtension.join(', '));
      $("#img_picture").val('');
      return false;
    }
    if(fls!=""){
      $(img_preview).show();
      readURL(this, imgg);
      $("#isPhoto").val(1);
    }else if(fls.length <= 1){
      $(imgg).hide();
    }
  });


  $('body').on('click', '.overlay1', function (e) {
    $('.overlay1').fadeOut('fast');
    $('.alert1').fadeOut('fast');
  });

  $('body').on('click', '.alert1', function (e) {
    $('.overlay1').fadeOut('fast');
    $('.alert1').fadeOut('fast');
  });

  $('body').on('click', '#hide_basic_uploader', function(e) {
    setTimeout(function(){
    $('#hide_basic_uploader').hide();
    },200);

    $('#img_preview img').slideDown('fast');
    $('.basic_uploader').slideDown('fast');
    $('#img_picture').hide();
  });

  $('body').on('click', '.basic_uploader', function(e) {
    $('.basic_uploader').slideUp('fast');
    $('#img_preview img').slideUp('fast');
    $('#img_picture').show();
    $('#hide_basic_uploader').show();
  });


  function readURL(input, idf){
    if(input.files && input.files[0]){
      var reader = new FileReader();
      reader.onload=function(e){
        $(idf).attr('src',e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }


  function alertMsg(milisec){
    setTimeout(function(){
      $(".alert1, .overlay1").fadeOut('fast');
    },milisec);
    return;
  }

  function errorAlertDanger(msg){
    var msg1 = msg.replace(/,/g, "<br>");
    $(".alert1").removeClass('alert-success').addClass('alert-danger').show().html(msg1);
    $(".overlay1").show();
  }

});