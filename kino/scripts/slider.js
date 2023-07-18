var slides = [];

window.addEventListener("load",_=>{
    window['slide1'] = document.getElementById("slide1");
    window['slide2'] = document.getElementById("slide2");
});

var currentSlide = 0;

function slide(){
    slide1.style.animation = "slide-out";
    slide2.style.animation = "slide-in";
    setTimeout(swap,1500);
}

function swap(){
    currentSlide++;
    slide1.innerHTML = slides[currentSlide%slides.length];
    slide2.innerHTML = slides[(currentSlide+1)%slides.length];
    slide1.style.animation = "";
    slide2.style.animation = "";
}
setInterval(slide, 5000);