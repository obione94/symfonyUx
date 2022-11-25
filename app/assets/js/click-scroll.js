//jquery-click-scroll
//by syamsul'isul' Arifin

var sectionArray = [1, 2, 3];

$.each(sectionArray, function(index, value){
          
     $(document).scroll(function(){
         var offsetSection = $('#' + 'section_' + value).offset().top;
         var docScroll = $(document).scrollTop();
         var docScroll1 = docScroll + 1;
         
        
         if ( docScroll1 >= offsetSection ){
             $('.navbar-nav .nav-item .nav-link').removeClass('active');
             $('.navbar-nav .nav-item .nav-link:link').addClass('inactive');  
             $('.navbar-nav .nav-item .nav-link').eq(index).addClass('active');
             $('.navbar-nav .nav-item .nav-link').eq(index).removeClass('inactive');
         }
         
     });
    
    $('.click-scroll').eq(index).click(function(e){
        var offsetClick = $('#' + 'section_' + value).offset().top ;
        e.preventDefault();
        $('html, body').animate({
            'scrollTop':offsetClick
        }, 300)
    });
    
});

$(document).ready(function(){
    $('.navbar-nav .nav-item .nav-link:link').addClass('inactive');    
    $('.navbar-nav .nav-item .nav-link').eq(0).addClass('active');
    $('.navbar-nav .nav-item .nav-link:link').eq(0).removeClass('inactive');
});

$(document).ready(function(){
    function showValues() {
        let str = $( ".f1" ).serializeArray();
        //HP: 0.1841 HC: 0.147
        let chp = (str["0"].value)*(str["1"].value)*(str["2"].value/100)*0.1841;
        let chc = (str["0"].value)*(str["1"].value)*(str["2"].value/100)*0.147;

        chp = Math.round(chp*100)/100;
        chc =  Math.round(chc*100)/100;
        $(".p1hp").text(chp+'€');
        $(".p1hc").text(chc+'€');
        return false;
    }
    $( ".r1").on( "click", showValues );
});

$(document).ready(function(){
    function showValues() {
        let str = $( ".f2" ).serializeArray();
        //HP: 0.1841 HC: 0.147
        let paaa = (str["0"].value*0.82)*0.1841;
        let pa = (str["0"].value* 1.33)*0.1841;

        paaa = Math.round(paaa*100)/100;
        pa =  Math.round(pa*100)/100;

        $(".paaa").text(paaa+'€');
        $(".pa").text(pa+'€');
        return false;
    }
    $( ".r2").on( "click", showValues );
});

$(document).ready(function(){
    function showValues() {
        let str = $( ".f3" ).serializeArray();
        //HP: 0.1841 HC: 0.147
        let p3 = str["1"].value-((str["0"].value*7) *(str["1"].value/100));
        $(".p3").text(p3+'€');
        return false;
    }
    $( ".r3").on( "click", showValues );
});
