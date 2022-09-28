export default class Converter {
    static getHTML(ast) {
        let html = "";
        ast.forEach((node) => {
            html += "\n" + Converter.getHtmlFromLine(node);
        });
        return html.trim();
    }
    static getHtmlFromLine(node) {
        if (typeof node === "string") {
            return node;
        }
        else if (node.hasOwnProperty("tag")) {
            return Converter.getTagFrom(node);
        }
        return "";
    }
    static getTagFrom(node) {
        let content = "";
        if (node.children) {
            content = Converter.getHTML(node.children);
        }
        return `<${node.tag}>${content}</${node.tag}>`;
    }
}
//# sourceMappingURL=Converter.js.map