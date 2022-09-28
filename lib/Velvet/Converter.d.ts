import { AST, VNode, VTag } from "./Types/AST";
export default class Converter {
    static getHTML(ast: AST): string;
    static getHtmlFromLine(node: VNode): string;
    static getTagFrom(node: VTag): string;
}