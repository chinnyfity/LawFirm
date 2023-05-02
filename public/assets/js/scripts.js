"use strict";

var page_name = $('#page_names').val();
var token = $('#txt_token').val();
var site_urls = $('#txtsite_url').val()+"/";
var user_email = $('#user_email').val();
var user = $('#user').val();
var isValidated = true;

var loads = "<div class='auto-load text-center mt-50 mb-40'><svg version='1.1' id='L9' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' height='60' viewBox='0 0 100 100' enable-background='new 0 0 0 0' xml:space='preserve'><path fill='#000' d='M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50'><animateTransform attributeName='transform' attributeType='XML' type='rotate' dur='1s' from='0 50 50' to='360 50 50' repeatCount='indefinite' /></path></svg><span style='margin-left:-10px'>Loading...</span></div>";
  


$(document).ready(function () {

  
  // Start sticky header
  $(window).on('scroll', function () {
    if ($(window).scrollTop() >= 150) {
        $('#sticky-header').addClass('sticky-menu');
    } else {
        $('#sticky-header').removeClass('sticky-menu');
    }
  });

  
  // setTimeout(function(){
  //   $("#outerContainer #mainContainer .toolbar").css("display", "none");
  // },800);


  var uname = retrieve_cookie('uname');
  var pass1 = retrieve_cookie('passwords');
  var isChecked = retrieve_cookie('isChecked');

  if(uname!="") $(".txtemail").val(uname);
  if(pass1!="") $(".txtpass").val(pass1);
  if(isChecked!="") $("#remember").prop('checked', true);

  $('body').on('click', '#remember', function () {
    var isChecked = $(this).is(":checked");
    if(isChecked == true){
      create_cookie('isChecked', isChecked);
    }else{
      create_cookie('isChecked', "");
    }
  });

  
  $(document).on("click", ".cmd_back", function (e) {
    $('.first_form').fadeIn('fast');
    $('.contact_form').hide();
    $('.faq_form').hide();

    setTimeout(function(){
      $("html, body").animate({ scrollTop: 100 }, "fast");
    },200);
  });

  

  $(document).on("click", ".goBack", function (e) {
    $('.cmd_next').attr('page', 1);
    $('.cmd_next_swap').attr('page', 1);
    $('.err').hide();
    $(this).addClass('cmd_close_modal').removeClass('goBack').html('Close').attr('data-dismiss', 'modal');
    $('.second_div').hide();
    $('.first_div').fadeIn('fast');
  });


  function paymentAPI(user_email, amount, self, user, token, query, payment_mthd, button_caption){
    var results = '';

    if(payment_mthd == "flutterwave"){
      //flutterwave api
    }

    if(payment_mthd == "paystack"){
      var datastring='user_id='+user
      +'&amount='+amount
      +'&pay_mthd='+payment_mthd
      +'&response='
      +'&_token='+token; 

      $.ajax({
        type: "POST",
        url : site_urls + "dashboard/validate_"+query,
        data: datastring,
        cache: false,
        timeout: 30000, // 30 second timeout
        success : function(data){
          results += data.message;
          if(data.status == "success"){

            var handler = PaystackPop.setup({
              key: PAYSKey,
              email: user_email,
              amount: amount * 100,
              currency: "NGN",
              ref: ''+Math.floor((Math.random() * 1000000000) + 1),
              callback: function(response){
                var datastring='user_id='+user
                +'&amount='+amount
                +'&pay_mthd='+payment_mthd
                +'&response='+response.reference
                +'&_token='+token; 
      
                $.ajax({
                  type: "POST",
                  url : site_urls + "dashboard/"+query,
                  data: datastring,
                  cache: false,
                  timeout: 30000, // 30 second timeout
                  success : function(data){
                    results += data.message;
                    if(data.status == "success"){
    
                      Swal.fire("Successful", "Your payment was successful", "success");
    
                      if(query == "fund_wallet"){
                        $(".upload_proof")[0].reset();
                        $(".form_deposit")[0].reset();
                        
                        $('.txtEnterAmt').val('');
                        $('.txtamts').val('1000');
                        $('.selectedAmt').html('&#8358;1,000');
                        var newAmt = parseFloat(data.data).toLocaleString();
                        $('.wallet_balance').html('&#8358;' + newAmt);
                        $('.chooseAmounts .selAmts').removeClass('active');
                        $('.chooseAmounts .active1').addClass('active');
                      }
                      
                      $(self).removeAttr('disabled').css({'opacity': '1'}).html(button_caption);
    
                    }else{
                      errorAlertDanger(results);
                      alertMsg(5000);
                      $(self).removeAttr('disabled').css({'opacity': '1'}).html(button_caption);
                    }
    
                  },error : function(data, timeouts){
                    $(self).removeAttr('disabled').css({'opacity': '1'}).html(button_caption);
                    errorAlertDanger(results);
                    alertMsg(5000);
                  }
                });
              },
              onclose: function() {
                $(self).removeAttr('disabled').css({'opacity': '1'}).html(button_caption);
              },
            });
            handler.openIframe();
            $(".close").click();

          }else{
            errorAlertDanger(results);
            alertMsg(5000);
            $(self).removeAttr('disabled').css({'opacity': '1'}).html(button_caption);
          }

        },error : function(data, timeouts){
          $(self).removeAttr('disabled').css({'opacity': '1'}).html(button_caption);
          errorAlertDanger(results);
          alertMsg(5000);
        }
      });
    }
  }


  $(document).on("click", 'body', function (e) {
    e.stopPropagation();
    $('.bank_details').slideUp('fast');
    $('.doc_sample').slideUp('fast');
  });


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

  $('body').on('click', '.overlay1', function (e) {
    $('.overlay1').fadeOut('fast');
    $('.alert1').fadeOut('fast');
  });

  $('body').on('click', '.alert1', function (e) {
    $('.overlay1').fadeOut('fast');
    $('.alert1').fadeOut('fast');
  });


 
  $('body').on('click', '.cmd_contd', function(e) {
    $('.intro_div').slideUp('fast');
    $('.main_div').slideDown('fast');
    setTimeout(function(){
      $("html, body").animate({ scrollTop: 30 }, 200);
    },200);
  });

  $('body').on('click', '.cmd_contd1', function(e) {
    $('.main_div').slideUp('fast');
    $('.second_div').slideDown('fast');
    setTimeout(function(){
      $("html, body").animate({ scrollTop: 30 }, 200);
    },200);
  });

  
  $('body').on('click', '.back_main_div', function(e) {
    $('.third_div').hide();
    $('.main_div').fadeIn('fast');
    setTimeout(function(){
      $("html, body").animate({ scrollTop: 30 }, 200);
    },200);
  });

  $(document).on("click", ".back_to_intro", function (e) {
    $('.main_div').hide();
    $('.intro_div').fadeIn('fast');
    setTimeout(function(){
      $("html, body").animate({ scrollTop: 20 }, 200);
    },200);
  });

  $(document).on("click", ".back_to_main", function (e) {
    $('.second_div').hide();
    $('.main_div').fadeIn('fast');
    setTimeout(function(){
      $("html, body").animate({ scrollTop: 20 }, 200);
    },200);
  });

  

  $('body').on('mousedown', '.chooseAmounts .selAmts', function(e) {
    $('.chooseAmounts .selAmts').removeClass('active');
    $(this).addClass('active');
    var amts = $(this).text();
    var amounts = parseFloat(amts.replace(/[^0-9.]/g, ""));
    
    $('.txtamts').val(amounts);
    amounts = parseFloat(amounts).toLocaleString();
    $('.txtEnterAmt').val('');
    $('.selectedAmt').html('&#8358;' + amounts);
  });


  function toFixedTrunc(x, n) {
    const v = (typeof x === 'string' ? x : x.toString()).split('.');
    if (n <= 0) return v[0];
    let f = v[1] || '';
    if (f.length > n) return `${v[0]}.${f.substr(0,n)}`;
    while (f.length < n) f += '0';
    return `${v[0]}.${f}`
  }


  $('body').on('mousedown', '.chooseAmounts1 .selAmts', function(e) {
    $('.chooseAmounts1 .selAmts').removeClass('active');
    $(this).addClass('active');
    var amts = $(this).text();
    var amounts = parseFloat(amts.replace(/[^0-9.]/g, ""));
    var litres = $('.litres').val();
    
    $('.txtamts').val(amounts);
    litres = parseFloat(amounts) / parseFloat(litres);
    amounts = parseFloat(amounts).toLocaleString();

    $('.no_of_litres').val(toFixedTrunc(litres, 1));
    $('.txtEnterAmt1').val('');
  });


  
  $('body').on('keyup change', '.txtEnterAmt', function(e) {
    var amts = $(this).val();
    isValidated = true;
    $('.amount_error').hide();

    if(amts == ""){
      $('.txtamts').val(0);
      $('.selectedAmt').html('&#8358;0');
      isValidated = false;
      return;
    }
    
    if(parseFloat(amts) < 500){
      $('.amount_error').show();
      isValidated = false;
      return;
    }

    var amounts = parseFloat(amts.replace(/[^0-9.]/g, ""));
    $('.chooseAmounts .selAmts').removeClass('active');
    
    $('.txtamts').val(amounts);
    amounts = parseFloat(amounts).toLocaleString();
    $('.selectedAmt').html('&#8358;' + amounts);
  });


  $('body').on('keyup change', '.txtEnterAmt1', function(e) {
    var amts = $(this).val();
    var litres = $('.litres').val();
    isValidated = true;
    $('.amount_error').hide();
    $('.no_of_litres').val('');
    
    if(amts == ""){
      $('.txtamts').val(0);
      $('.no_of_litres').val('');
      $('.selectedAmt').html('&#8358;0');
      isValidated = false;
      return;
    }
    
    if(parseFloat(amts) < parseFloat(litres)){
      $('.amount_error').show();
      isValidated = false;
      return;
    }

    var amounts = parseFloat(amts.replace(/[^0-9.]/g, ""));
    $('.chooseAmounts .selAmts').removeClass('active');
    
    $('.txtamts').val(amounts);
    litres = parseFloat(amounts) / parseFloat(litres);
    amounts = parseFloat(amounts).toLocaleString();

    $('.selectedAmt').html('&#8358;' + amounts);
    $('.no_of_litres').val(toFixedTrunc(litres, 1));
  });


  $('body').on('keyup change', '.no_of_litres', function(e) {
    var no_of_litres = $(this).val();
    var litres = $('.litres').val();

    if(no_of_litres == "" || no_of_litres <= 0){
      $('.txtEnterAmt1, .txtamts').val('');
      return;
    }
    $('.chooseAmounts1 .selAmts').removeClass('active');
    var amount = parseFloat(no_of_litres) * parseFloat(litres);
    $('.txtEnterAmt1, .txtamts').val(amount);
  });


  $('body').on('change', '.payments', function(e) {
    var mthd = $(this).find(':selected').data('value1');
    var payments = $(this).val();
    $('.txtcur').val(payments);
    $('.payment_selected').html(mthd);
  });
  

  $('body').on('click', '.cmdFundWallet', function(e) {
    var payments = $('.payments').val();
    var txtamts = $('.txtamts').val();
    var self = this;
    isValidated = true;

    if(txtamts == ""){
      errorAlertDanger("Error! Invalid amount selected or entered...");
      alertMsg(4000);
      isValidated = false;
    }
    if(txtamts < 100){
      isValidated = false;
      errorAlertDanger("Error! Select or enter amount greater than N100");
      alertMsg(4000);
    }
    if(payments == ""){
      isValidated = false;
      errorAlertDanger("Error! Select a payment method to continue");
      alertMsg(4000);
    }

    if(isValidated == true){
      $(".cmdFundWallet").attr('disabled', true).css({'opacity': '0.4'});
      var newAmts = parseFloat(txtamts).toLocaleString();

      Swal.fire({
        title: "Continue?",
        html: `Use <b>${payments}</b> to fund your wallet with <b>NGN${newAmts}</b>?`,
        icon: 'question',
        iconHtml: '?',
        showCancelButton: true,
        confirmButtonColor: '#027937',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed'
      }).then((result) => {
        if (result.isConfirmed) {  
          $(self).attr('disabled', true).css({'opacity': '0.5'}).html('Wait...');

          if(payments == "paystack"){
            paymentAPI(user_email, txtamts, self, user, token, 'fund_wallet', 'paystack', 'Fund Wallet');
          }
          
          if(payments == "deposit"){
            $('.first_div').hide();
            $('.sec_div').fadeIn('fast');
            $('.showAmt').html(`NGN${newAmts}`);
            $("html, body").animate({ scrollTop: 0 }, 200);
          }

        }else{
          $(self).removeAttr('disabled').css({'opacity': '1'}).html('Fund Wallet');
        }
      });

    }
  });

  
  $('body').on('change', '.pay_now', function(e) {
    var pay_now = $(this).val();
    $('.div_litres').removeClass('col-sm-3').addClass('col-sm-5');
    $('.div_paynow').removeClass('col-sm-4').addClass('col-sm-6');
      $('.div_date').hide();
    if(pay_now == "pay later"){
      $('.div_litres').removeClass('col-sm-5').addClass('col-sm-3');
      $('.div_paynow').removeClass('col-sm-6').addClass('col-sm-4');
      $('.div_date').show();
    }
  });


  $('body').on('click', '.buyFuel', function(e) {
    var litres = $('.litres').val();
    var txtamts = $('.txtamts').val();
    var pay_now = $('.pay_now').val();
    var wallet = $('.wallet').val();
    var no_of_litres = $('.no_of_litres').val();
    var next_coming = $('.next_coming').val();
    var debts = $('.debts').val();
    
    var results = "";
    
    var self = this;
    isValidated = true;

    if(txtamts == ""){
      errorAlertDanger("Error! Invalid amount selected or entered...");
      alertMsg(4000);
      isValidated = false;
    }
    if(parseFloat(txtamts) < parseFloat(litres)){
      isValidated = false;
      errorAlertDanger(`Error! Select or enter amount greater than N${litres}`);
      alertMsg(4000);
    }

    if(parseFloat(txtamts) > parseFloat(wallet)){
      isValidated = false;
      errorAlertDanger(`Error! You have insufficient balance to continue`);
      alertMsg(4000);
    }
    if(parseFloat(no_of_litres) <= 0 || no_of_litres == ""){
      isValidated = false;
      errorAlertDanger(`Error! Number of litres is missing`);
      alertMsg(4000);
    }

    var newAmts = parseFloat(txtamts).toLocaleString();
    var html = `Buy <b>${no_of_litres} litres</b> for <b>NGN${newAmts}</b> with your wallet?`;
    var debt_text = "";
    
    if(pay_now == "pay later" && $(".next_coming").is(":checked") == true){
      html = `You have chosen to pay <b>NGN${newAmts}</b> later for <b>${no_of_litres} litres</b> on your next patronage`;
    }

    if(pay_now == "pay later" && $(".next_coming").is(":checked") !== true){
      html = `You have chosen to pay <b>NGN${newAmts}</b> later for <b>${no_of_litres} litres</b> on the date you specified`;
    }

    if(debts > 0){
      debt_text = ` Your pending balance of <b>NGN${parseFloat(debts).toLocaleString()}</b> will also be added`;
    }

    var next_coming1 = 0;
    if($(".next_coming").is(":checked") == true){
      next_coming1 = 1;
    }

    if(isValidated == true){
      $(self).attr('disabled', true).css({'opacity': '0.4'});
      
      Swal.fire({
        title: "Continue?",
        html: html+debt_text,
        icon: 'question',
        iconHtml: '?',
        showCancelButton: true,
        confirmButtonColor: '#027937',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed'
      }).then((result) => {
        if (result.isConfirmed) {  
          $(self).attr('disabled', true).css({'opacity': '0.5'}).html('Wait...');

          var datastring='user_id='+user
          +'&amount='+txtamts
          +'&debts='+debts
          +'&pay_mthd=wallet'
          +'&no_of_litres='+no_of_litres
          +'&next_coming='+next_coming1
          +'&pay_now='+pay_now
          +'&_token='+token; 

          Swal.fire({
            title: 'Processing...',
            text: "Please wait a second for a response...",
            icon: 'success',
            showConfirmButton: false,
            confirmButtonColor: '#027937',
            cancelButtonColor: '#d33',
          });

          $.ajax({
            type: "POST",
            url : site_urls + "dashboard/buy",
            data: datastring,
            cache: false,
            timeout: 30000, // 30 second timeout
            success : function(data){
              results += data.message;
              if(data.status == "success"){

                if(pay_now == "pay later"){
                  Swal.fire("Successful", html, "success");
                }else{
                  Swal.fire("Successful", "Your payment was successful", "success");
                }

                var fuel_price = 300;
                var litres = parseFloat(1000) / parseFloat(fuel_price);                
                $('.no_of_litres').val(toFixedTrunc(litres, 1));
                $('.txtEnterAmt1').val('');
                $('.txtamts').val('1000');
                $('.wallet_balance').html('&#8358;' + parseFloat(data.data.data).toLocaleString());
                $('.chooseAmounts1 .selAmts').removeClass('active');
                $('.chooseAmounts1 .active1').addClass('active');
                $('.wallet').val(data.data.data);
                $('.debts').val(data.data.owing);
                
                $(self).removeAttr('disabled').css({'opacity': '1'}).html('Buy Fuel');

              }else{
                Swal.fire("Error", results, "error");
                $(self).removeAttr('disabled').css({'opacity': '1'}).html('Buy Fuel');
              }

            },error : function(data, timeouts){
              $(self).removeAttr('disabled').css({'opacity': '1'}).html('Buy Fuel');
              errorAlertDanger(results);
              alertMsg(5000);
            }
          });

        }else{
          $(self).removeAttr('disabled').css({'opacity': '1'}).html('Fund Wallet');
        }
      });

    }
  });



  $(".upload_proof").on('submit',(function(e) {
    e.preventDefault();
    $(".alert1, .overlay1").hide();
    var results = "";
  
    var self = this;
    $(".cmdDeposited").attr('disabled', true).css({'opacity': '0.4'});
  
    $.ajax({
      type : "POST",
      data: new FormData(self),
      contentType: false,
      cache: false,
      processData:false,
      dataType: 'json',
      url : site_urls + "dashboard/fund_wallet_deposit",
      success : function(data){
        results += data.message;
  
        if(data.status == 'success'){
          $(".cmdDeposited").removeAttr('disabled').css({'opacity': '1'});
          $(".cmdFundWallet").removeAttr('disabled').css({'opacity': '1'}).html('Fund Wallet');
          Swal.fire("Successful", "Your proof of payment has been sent. We will fund your wallet as soon as we authenticate it, thank you.", "success");
          $(".upload_proof")[0].reset();
          $(".form_deposit")[0].reset();
          
          $('.txtEnterAmt, .txtamts').val('');
          $('.selectedAmt').html('&#8358;1,000');
          
          $('.chooseAmounts .selAmts').removeClass('active');
          $('.chooseAmounts .active1').addClass('active');

          $('.sec_div').hide();
          $('.first_div').fadeIn('fast');
          
        }else{          
          errorAlertDanger(results);
          alertMsg(5000);
  
          $(".cmdDeposited").removeAttr('disabled').css({'opacity': '1'});
          $(".cmdFundWallet").removeAttr('disabled').css({'opacity': '1'}).html('Fund Wallet');
        }
      },error : function(data){
        $(".cmdDeposited").removeAttr('disabled').css({'opacity': '1'});
        $(".cmdFundWallet").removeAttr('disabled').css({'opacity': '1'}).html('Fund Wallet');
        errorAlertDanger('Poor Network Connection!');
        alertMsg(5000);
      }
    });
  }));


  $('body').on('click', '.gotoFirst_div', function(e) {
    $('.sec_div').hide();
    $('.first_div').fadeIn('fast');
    $('.cmdFundWallet').removeAttr('disabled').css({'opacity': '1'}).html('Fund Wallet');
  });


  
  function create_cookie(name, value, days2expire, path) {
    var date = new Date();
    date.setTime(date.getTime() + (days2expire * 24 * 60 * 60 * 1000));
    var expires = date.toUTCString();
    document.cookie = name + '=' + value + ';' +
                    'expires=' + expires + ';' +
                    'path=' + path + ';';
  }


  function retrieve_cookie(name) {
    var cookie_value = "",
      current_cookie = "",
      name_expr = name + "=",
      all_cookies = document.cookie.split(';'),
      n = all_cookies.length;
  
    for(var i = 0; i < n; i++) {
      current_cookie = all_cookies[i].trim();
      if(current_cookie.indexOf(name_expr) == 0) {
        cookie_value = current_cookie.substring(name_expr.length, current_cookie.length);
        break;
      }
    }
    return cookie_value;
  }





  let question = document.querySelectorAll(".question");
  question.forEach(question => {
    question.addEventListener("click", event => {
      const active = document.querySelector(".question.active");
      if(active && active !== question ) {
        active.classList.toggle("active");
        active.nextElementSibling.style.maxHeight = 0;
      }
      question.classList.toggle("active");
      const answer = question.nextElementSibling;
      if(question.classList.contains("active")){
        answer.style.maxHeight = answer.scrollHeight + "px";
      } else {
        answer.style.maxHeight = 0;
      }
    })
  })


});