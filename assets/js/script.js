$(function(){

    var inputFile = document.createElement('input');
    inputFile.setAttribute("type", "file");

    $(inputFile).on("change", function(){
        var a = inputFile.files[0];
        var b = /text.*/;

        if (!a.type.match(b)){
            swal('HATA','Sadece TXT Dosyaları','error');
            return;
        }

        var c = new FileReader();
        c.onload = function(){$("#input-list").val(c.result);}
        c.readAsText(a);
    });

    $("#btn-load").click(function(){
        inputFile.click();
    });
    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    $("#btn-draw").click(function(){
        var rid = getParameterByName('cid');
        //console.log("sdesd")
        $(".row-prin").addClass("d-none");
        $(".row-repl").addClass("d-none");
        $(".badge-prin-list").empty();
        $(".badge-repl-list").empty();
        $.ajax({
            type: "POST",
            url: "ajax/draw.php",
            dataType: "json",
            data: {"action": "draw", "principal": $("#select-range-1").val(), "replacement": $("#select-range-2").val(), "list": $("#input-list").val()},
            beforeSend: function() {
                // setting a timeout
                $("#input-list").hide();
                for (let index = 0; index < 7; index++) {
                    $(".badge-prin-list").append("<span class='badge badge-primary badge-list mr-1' id='t"+index+"' style='display:none;'> </span>");  
                }
            },
            success: function(rt){
                console.log(rt)
                if (rt.a != 200){
                    swal(rt.b, rt.c, rt.d);
                }else{
                    if (rt.e){
                        $(".row-prin").removeClass("d-none");
                        var prin = JSON.parse(rt.g);
                        $.each(prin, function(i, item){
                            //var sp = $("<span class='badge badge-primary badge-list mr-1' id='t"+i+"'>" + item + "</span>").hide();
                                $(".badge-prin-list").append("<span class='badge badge-primary badge-list mr-1' id='t"+i+"' style='display:none;'>" + item + "</span>");
                                //$("span#t"+i).hide();
                                var winner = item.split(" ");
                                $.ajax({  
                                    type:"POST",  
                                    url:"api/insertWinner.php",  
                                    data:{winid:winner[0],rid:rid},  
                                    success:function(data){ 
                                      //console.log(data); 
                                       //$('#employee_table').html(data);
                                       setTimeout(() => {
                                           $("#t"+i).html(item).fadeIn('slow');
                                       }, 1000);
                                         
                                    }  
                                 }); 
                            
                        });
                        
                    }
                    if (rt.f){
                        $(".row-repl").removeClass("d-none");
                        var repl = JSON.parse(rt.h);
                        $.each(repl, function(i, item){
                            $(".badge-repl-list").append("<span class='badge badge-primary badge-list mr-1' id='t"+i+"'>" + item + "</span>");
                            
                            
                        });
                    }
                    
                    setTimeout(function(){
                        window.location.href = "index.php";
                    }, 10000);   
                    
                }
            }
        });
    });

});