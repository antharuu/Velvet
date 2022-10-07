import { getBlockAttrOf, getBlocksOf, getRegexOf } from "./Tools.js";
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
				const { current_block, attributes } = getBlockAttrOf(
					block.block
				);
				const children = this.blockToAST(current_block, indent + 1);
				const current: VNode = {
					tag: res.tag,
					children:
						res.line_content?.trim().length > 0
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
