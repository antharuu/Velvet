import { AST, VNode, VTag } from "./Types/AST.js";
export default class Converter {
    static getHTML(ast: AST): string;
    static getHtmlFromLine(node: VNode): string;
    static getFromTag(node: VTag): string;
}
//# sourceMappingURL=Converter.d.ts.map