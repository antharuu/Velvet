import { getAttributesOf, getBlocksOf, getRegexOf } from "./Tools.js";
import { AST, TempBlock, VNode } from "./Types/AST.js";

/**
 * Parse the Velvet code into AST *(Abstract Syntax Tree)*
 */
export default class Parser {
	static lineRegex = /^(?<tag>[\w]+)( ?)(?<line_content>.*)?/;
	static attributesRegex = /^(?<attributes>\((?<attribute>.*)\))/;

	/**
	 * Convert a string into an usable AST
	 * @param velvetCode String to be converted in AST
	 */
	static convert(velvetCode: string): AST {
		return Parser.blockToAST(getBlocksOf(velvetCode));
	}

	static blockToAST(blocks: TempBlock[], indent = 0): AST {
		const ast: AST = [];
		blocks.forEach((block) => {
			/* c8 ignore next */
			const res = getRegexOf(Parser.lineRegex, block.line) ?? "";
			if (res.tag) {
				const hasLineContent = res.line_content?.trim().length > 0;
				let attributes = {};
				if (hasLineContent) {
					const { line: c_line, attributes: c_attrs } =
						getAttributesOf(res?.line_content.trim());
					if (Object.keys(c_attrs).length > 0) {
						res.line_content = c_line;
						attributes = c_attrs;
					}
				}
				const children = this.blockToAST(block.block, indent + 1);
				const current: VNode = {
					tag: res.tag,
					children: hasLineContent
						? [res.line_content, ...children]
						: children,
					indent,
				};
				if (Object.keys(attributes).length > 0)
					current.attributes = attributes;
				ast.push(current);
			}
		});
		return ast;
	}
}
