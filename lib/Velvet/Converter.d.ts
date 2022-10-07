import { AST, VNode, VTag } from "./Types/AST.js";
export default class Converter {
    static getHTML(ast: AST): string;
    static getHtmlFromLine(node: VNode): string;
    static getFromTag(node: VTag): string;
}
/**
 * Get the attributes string of a node
 *
 * @param node Node to get the attributes from
 * @returns Attributes string
 */
export declare function getAttributes(node: VTag): string;
//# sourceMappingURL=Converter.d.ts.map