import "./style.css";
import Parser from "./src/Velvet/Parser";

const P = new Parser("h1 Hello world");
console.log(P.getAST());
