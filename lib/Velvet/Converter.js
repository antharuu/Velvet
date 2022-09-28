"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var Converter = /** @class */ (function () {
    function Converter() {
    }
    Converter.getHTML = function (ast) {
        console.log(ast);
        var html = "";
        ast.forEach(function (node) {
            html += "\n" + Converter.getHtmlFromLine(node);
        });
        return html.trim();
    };
    Converter.getHtmlFromLine = function (node) {
        if (typeof node === "string") {
            return node;
        }
        else if (node.hasOwnProperty("tag")) {
            return Converter.getTagFrom(node);
        }
        return "";
    };
    Converter.getTagFrom = function (node) {
        var content = "";
        if (node.children) {
            content = Converter.getHTML(node.children);
        }
        return "<".concat(node.tag, ">").concat(content, "</").concat(node.tag, ">");
    };
    return Converter;
}());
exports.default = Converter;
