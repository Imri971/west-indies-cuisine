

        // let btn = document.getElementById('circle');
        // let estActif= false;
        // function click() {
        //     if(!estActif){
        //         document.getElementById('circle').style.backgroundColor= 'blue';
        //     }else 
        //     {
        //         document.getElementById('circle').style.backgroundColor = 'red';  
        //     }
        //     estActif = !estActif;
            
        // }
        // btn.addEventListener('click', click)
        var active = false;
        $('.menu_reply').on("click", function() {
                
                if(active) {
                        $('.reply').css("display","none");
                }else{
                        $('.reply').css("display","block"); 
                }
                active= !active;
               
                
            });
            
