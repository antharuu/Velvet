const links = document.querySelectorAll("p > a");

links.forEach(function (link) {
    link.setAttribute("data-value", link.text);
    link.setAttribute("target", "_blank");
});