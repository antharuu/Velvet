const links = document.querySelectorAll("a");

links.forEach(function (link) {
    link.setAttribute("data-value", link.text);
});