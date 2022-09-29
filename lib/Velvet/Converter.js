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
        return Converter.getFromTag(node);
    }
    static getFromTag(node) {
        /* c8 ignore next */
        if (node.tag.trim().length === 0)
            return "";
        let content = "";
        if (node.children) {
            content = Converter.getHTML(node.children);
        }
        return `<${node.tag}>${content}</${node.tag}>`;
    }
}
//# sourceMappingURL=Converter.js.map