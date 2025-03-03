// next prev
var divs = $('.show-section section');
var now = 0; // currently shown div
divs.hide().first().show(); // hide all divs except first

//show active step
function showActiveStep()
{
    if ($('#step1').is(':visible'))
    {
        $(".step-bar .bar").eq(now).addClass('active');
        $("#activeStep").html('1');
    }
    else if ($('#step2').is(':visible'))
    {
        $(".step-bar .bar").eq(now).addClass('active');
        $("#activeStep").html('2');
    }
    else if ($('#step3').is(':visible'))
    {
        $(".step-bar .bar").eq(now).addClass('active');
        $("#activeStep").html('3');
    }
    else if ($('#step4').is(':visible'))
    {
        $(".step-bar .bar").eq(now).addClass('active');
        $("#activeStep").html('4');
    }
    else if ($('#step5').is(':visible'))
    {
        $(".step-bar .bar").eq(now).addClass('active');
        $("#activeStep").html('5');
    }
    else
    {
    console.log("error");
    }
}


function next()
{
    divs.eq(now).hide();
    now = (now + 1 < divs.length) ? now + 1 : 0;
    divs.eq(now).show(); // show next
    console.log(now);

    showActiveStep();
}

var totalsteps = $('section').length;



// show next step on input checked
function nextonclick(nextstep)
{
    $('#step'+nextstep+' .radio-field input').on('click',function()
    {

        if(nextstep < totalsteps)
        {
            $('#step'+nextstep+ ' .radio-field').removeClass('bounce-left');
            $('#step'+nextstep+ ' .radio-field').addClass('bounce-right');

            setTimeout(function()
            {  
                next();
    
            }, 900)
        }

        else
        {
            showresult();
        }

    })
}

for(i = 1; i<=totalsteps; i++)
{
    nextonclick(i);
}









// $(".prev").on('click', function()
// {

//     $('.radio-field').addClass('bounce-left');
//     $('.radio-field').removeClass('bounce-right');
//         $(".step-bar .bar").eq(now).removeClass('active');
//     divs.eq(now).hide();
//     now = (now > 0) ? now - 1 : divs.length - 1;
//     divs.eq(now).show(); // show previous
//     console.log(now);

//     showActiveStep();

// })
// countdown

var countDownDate = new Date("Feb 28, 2023").getTime();

var x = setInterval(function() 
{

  var now = new Date().getTime();
  var distance = countDownDate - now;
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  document.getElementById("countdown").innerHTML = "<div class='timer'>" + minutes + "</div> : " + "<div class='timer'>" +  seconds + "</div>";
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("countdown").innerHTML = "EXPIRED";
  }
}, 1000);

// // quiz validation
// var checkedradio = false;

// function radiovalidate(stepnumber)
// {
//     var checkradio = $("#step"+stepnumber+" input").map(function()
//     {
//     if($(this).is(':checked'))
//     {
//         return true;
//     }
//     else
//     {
//         return false;
//     }
//     }).get();

//     checkedradio = checkradio.some(Boolean);
// }




// // form validation
// $(document).ready(function()
// {

//     // check step1
//     $("#step1btn").on('click', function()
//     {
//         radiovalidate(1);

//         if(checkedradio == false)
//         {
            
//         (function (el) {
//             setTimeout(function () {
//                 el.children().remove('.reveal');
//             }, 3000);
//             }($('#error').append('<div class="reveal alert alert-danger">Choose an option!</div>')));
            
//             radiovalidate(1);

//         }
        
//         else
//         {
//             $('#step1 .radio-field').removeClass('bounce-left');
//             $('#step1 .radio-field').addClass('bounce-right');
//             setTimeout(function()
//             {
//                 next();
//             }, 900)

//         }


//     })

//     // check step2
//     $("#step2btn").on('click', function()
//     {
//         radiovalidate(2);

//         if(checkedradio == false)
//         {
            
//         (function (el) {
//             setTimeout(function () {
//                 el.children().remove('.reveal');
//             }, 3000);
//             }($('#error').append('<div class="reveal alert alert-danger">Choose an option!</div>')));
            
//             radiovalidate(2);

//         }

//         else
//         {
//             $('#step2 .radio-field').removeClass('bounce-left');
//             $('#step2 .radio-field').addClass('bounce-right');
//             setTimeout(function()
//             {
//                 next();
//             }, 900)

//         }
//     })

//     // check step3
//     $("#step3btn").on('click', function()
//     {
//         radiovalidate(3);

//         if(checkedradio == false)
//         {
            
//         (function (el) {
//             setTimeout(function () {
//                 el.children().remove('.reveal');
//             }, 3000);
//             }($('#error').append('<div class="reveal alert alert-danger">Choose an option!</div>')));
            
//             radiovalidate(3);

//         }

//         else
//         {
//             $('#step3 .radio-field').removeClass('bounce-left');
//             $('#step3 .radio-field').addClass('bounce-right');
//             setTimeout(function()
//             {
//                 next();
//             }, 900)

//         }
//     })


//     // check last step
//     $("#sub").on('click', function()
//     {
//         radiovalidate(4);

//         if(checkedradio == false)
//         {
            
//         (function (el) {
//             setTimeout(function () {
//                 el.children().remove('.reveal');
//             }, 3000);
//             }($('#error').append('<div class="reveal alert alert-danger">Choose an option!</div>')));
            
//             radiovalidate(4);

//         }

//         else
//         {
//             showresult()
//             $("#sub").html('done');

//         }
//     })
// })