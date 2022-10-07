import { AST, VAttributes, VNode, VTag } from "./Types/AST.js";

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
		}
		return Converter.getFromTag(node);
	}

	static getFromTag(node: VTag): string {
		/* c8 ignore next */
		if (node.tag.trim().length === 0) return "";
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
export function getAttributes(node: VTag): string {
	let attributesStr = "";
	if (node?.attributes ?? false) {
		Object.keys(node.attributes as VAttributes).forEach((name) => {
			if (node?.attributes) {
				const value = node?.attributes[name];
				if (value !== null) {
					attributesStr += ` ${name}="${value}"`;
				} else {
					attributesStr += ` ${name}`;
				}
			}
		});
	}
	return attributesStr;
}
