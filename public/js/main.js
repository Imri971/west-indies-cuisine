
var $collectionHolder;

var $addNewItem = $('<button type="button" class="btn btn-success mb-2">Add a step</button>');

$(document).ready(function() {
    
    $collectionHolder= $('#step-list');
    // append the add new item to the collectionHolder
    $collectionHolder.append($addNewItem);
     
    // add remove button to existing items
    $collectionHolder.find('.panel').each(function (){
        addRemoveButton($(this));

        
    });

    //handle the click event for addNewItem
    $addNewItem.click( function (e){
        // create a new form and append it to the collectionHolder
        e.preventDefault();

        // handle the click event of the remove button

        // $(e.target).parents('.panel')
        // console.log( $(e.target))
        addNewForm();
    })
    
});

function addNewForm(){
    // getting the prototype
    var prototype = $collectionHolder.data('prototype');
    //get the index
    var index = $collectionHolder.data('widget-counter')
    
    // Create the form
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    let newFormSpotId = 'id="recipe_steps_' + index + '_spot"'
    let newFormSpotWithValue = newFormSpotId + ' value="' + (index+1) +'"'
    newForm = newForm.replace(newFormSpotId, newFormSpotWithValue)

    $collectionHolder.data('widget-counter', ++index);
    
    //create the panel
    var $panel = $('<div class="panel panel-warning"><div class="panel-heading"></div></div>');

    //create the panel-body and append the form to it
    var $panelBody = $('<div class="panel-body"></div>').append(newForm);
    //append the body to the panel
    $panel.append($panelBody);
    //append the removebutton to the new panel
    addRemoveButton($panel);

    //append the panel to the addNewItem
    $panel.hide().insertBefore($addNewItem).slideDown();
    // $addNewItem.before($panel);
    
}

//add new items (experience forms)

//remove them

function addRemoveButton($panel) {
    // create remove button
    var $removeButton = $('<button type="button" class="btn btn-danger mb-2">Remove</button>');
    //appending the removebutton to the panel footer
    var $panelFooter = $('<div class="panel-footer"></div>').append($removeButton);

    // handle the click event of the remove button

    $removeButton.click(function (e){
        $(e.target).parents('.panel').slideUp(1000, function (){
            $(this).remove();
        });
        
    });


    // append the footer to the panel

    $panel.append($panelFooter);
}
    
        
        var active = false;
        $('.menu_reply').on("click", function() {
                
                if(active) {
                        $('.reply').css("display","none");
                }else{
                        $('.reply').css("display","block"); 
                }
                active= !active;
               
                
            });
            $('.recipe_reply').on("click", function() {
                
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

        //Gallery
        $(".gallery").magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: {
              enabled: true
            }
          });


$(window).on("scroll", function() {
    if($(window).scrollTop() > 20){
        
       
        $('.navbar-page').css("background","#1a1a1a");
        
    }
    else {
    $('.navbar-page').css("background","none ");
    }
});

