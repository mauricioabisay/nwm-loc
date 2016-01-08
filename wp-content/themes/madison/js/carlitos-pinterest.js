//document.getElementsByClassName("image").innerHTML = "HOLAAAAAA";
window.onload = function() {
   imagenesPinterest();
};
window.onresize = function() {
   imagenesPinterest(); 
};

function imagenesPinterest() {
    var elementos = document.getElementsByClassName('image');
    for(var i = 0; i < elementos.length; i++) {
        var e = elementos.item(i);
        var imageSrc = e.style.backgroundImage.replace(/url\((['"])?(.*?)\1\)/gi, '$2').split(',')[0];
        
        var image = new Image();
        image.src = imageSrc;
        var ruta = image.src;
        var width = image.width;
        var height = image.height;
        var ratio = width/height;
        var divancho = e.offsetWidth;
        var nuevoAlto = divancho/ratio;
       /*
        console.log(image.src);
        console.log("alto original " + height);
        console.log("ancho columna " + divancho);
        console.log("nuevo alto " + nuevoAlto);
        console.log("alto " +  e.parentNode.style.height);*/
        e.innerHTML ="<img src=" + ruta + ">"
        e.parentNode.style.height = "40px !important";
    }
    
  }