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
        return `<${node.tag}${getAttributes(node)}>${content}</${node.tag}>`;
    }
}
/**
 * Get the attributes string of a node
 *
 * @param node Node to get the attributes from
 * @returns Attributes string
 */
export function getAttributes(node) {
    var _a;
    let attributesStr = "";
    if ((_a = node === null || node === void 0 ? void 0 : node.attributes) !== null && _a !== void 0 ? _a : false) {
        Object.keys(node.attributes).forEach((name) => {
            if (node === null || node === void 0 ? void 0 : node.attributes) {
                const value = node === null || node === void 0 ? void 0 : node.attributes[name];
                if (value !== null) {
                    attributesStr += ` ${name}="${value}"`;
                }
                else {
                    attributesStr += ` ${name}`;
                }
            }
        });
    }
    return attributesStr;
}
//# sourceMappingURL=Converter.js.map