const hamburger=document.querySelector(".hamburger")
hamburger.addEventListener("click",()=>{
    const click=document.querySelector(".signin")
    click.style.display=(click.style.display === 'none' || click.style.display === '') ? 'flex' : 'none';

})
 
 