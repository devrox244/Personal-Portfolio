//js for appearing and disappearing text
var typed = new Typed('#element', {
    strings: ['I am an aspiring Web Developer....'],
    typeSpeed: 50,
});


//js for contents in about
function opencon(tab){
    var links = document.getElementsByClassName("tab-links");
    var contents = document.getElementsByClassName("tab-content");

    for(link of links){
        link.classList.remove("active-title");
    }
    for(content of contents){
        content.classList.remove("active-tab");
    }
    event.currentTarget.classList.add("active-title");
    document.getElementById(tab).classList.add("active-tab");
}