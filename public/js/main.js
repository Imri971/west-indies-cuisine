

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

        
        function onClickBtnLike(event){
        event.preventDefault(); //Empêcher l'évènement actuel 
        const url = this.href;
        const spanCount = this.querySelector('span.js-likes'); //recupere le span avec la class js-likes dans l'élément actuel
        const spanLabel = this.querySelector('span.js-label');
        const icone = this.querySelector('i');
        axios.get(url).then(function(response){
                spanCount.textContent = response.data.likes;
                if (response.data.likes <= 1){
                spanLabel.textContent = "like";
                }else{
                spanLabel.textContent = "likes";
                }

                if(icone.classList.contains('fas')) //Si la class de l'icone contient 'fas'
                {
                icone.classList.replace('fas','far'); //Acceder à toutes les classes d'un élément et remplacer certain items
                }else 
                {
                icone.classList.replace('far','fas');
                }
        }).catch(function(error){
                if(error.response.status === 403){
                window.alert("Vous ne pouvez pas liker si vous n'êtes pas connecté")
                }
        });
        }


        document.querySelectorAll('a.js-like').forEach( function(link){
        link.addEventListener('click', onClickBtnLike);
        })

            
