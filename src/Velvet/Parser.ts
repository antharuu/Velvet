import { getBlocksOf, getRegexOf } from "./Tools";
import { AST, TempBlock } from "./Types/AST";

/**
 * Parse the Velvet code into AST *(Abstract Syntax Tree)*
 */
export default class Parser {
	static lineRegex: RegExp = /^(?<tag>[\w]+)( ?)(?<line_content>.*)?/;

	/**
	 * Convert a string into an usable AST
	 * @param velvetCode String to be converted in AST
	 */
	static convert(velvetCode: string): AST {
		return Parser.blockToAST(getBlocksOf(velvetCode));
	}

	static blockToAST(blocks: TempBlock[], indent: number = 0): AST {
		const ast: AST = [];
		blocks.forEach((block) => {
			const res = getRegexOf(Parser.lineRegex, block.line) ?? "";
			if (res.tag) {
				const children = this.blockToAST(block.block, indent + 1);
				ast.push({
					tag: res.tag,
					children:
						res.line_content?.trim().length > 0
							? [res.line_content, ...children]
							: children,
					indent,
				});
			}
		});
		return ast;
	}
}
