import { AST, VNode, VTag } from "./Types/AST.js";

export default class Converter {
	static getHTML(ast: AST): string {
		let html = "";

		ast.forEach((node: VNode) => {
			html += "\n" + Converter.getHtmlFromLine(node);
		});

		return html.trim();
	}

	static getHtmlFromLine(node: VNode): string {
		if (typeof node === "string") {
			return node;
		} else if (node.hasOwnProperty("tag")) {
			return Converter.getTagFrom(node);
		}
		return "";
	}

	static getTagFrom(node: VTag): string {
		let content = "";
		if (node.children) {
			content = Converter.getHTML(node.children);
		}
		return `<${node.tag}>${content}</${node.tag}>`;
	}
}
