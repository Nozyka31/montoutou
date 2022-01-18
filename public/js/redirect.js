
let formLogin = document.getElementById("formLogin");
let url = document.getElementById("url");

if(url.innerHTML != "toto")
{
    formLogin.addEventListener("submit", function() {
        return new Promise(resolve => {
        setTimeout(function() {
            resolve("rapide");
            window.location.href = url.innerHTML;
        }, 1000);
        });
    });
}