const add_cart = document.querySelectorAll('.add_cart');
const form_cart =document.querySelector('#check_out_form');


if(add_cart){
    add_cart.forEach(element => {
    element.addEventListener('click',()=>{
        let added_item=element.parentNode.cloneNode(true);
        element.parentNode.classList.add('disabledbtn');
        form_cart.prepend(added_item);
        added_item.children[5].removeAttribute('disabled'); 
        ActiveRemove();
       
    });
    });
}
function ActiveRemove(){
    const remove_cart = document.querySelectorAll('.remove_cart');
    if(remove_cart){

        remove_cart.forEach(element =>{

            element.addEventListener('click',()=>{

                let id = element.getAttribute('id');
                document.querySelector('#parent_'+id).classList.remove('disabledbtn');
                element.parentNode.remove();

            })

        })

    }
}