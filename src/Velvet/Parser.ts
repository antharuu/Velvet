import { BlockTree } from "./Types/BlockTree";
import { AST, VTag } from "./Types/AST";
import BTConverter from "./BTConverter";

/**
 * Parse the Velvet code into AST
 */
export default class Parser {
	ast: AST;
	lines: string[];
	blockTree: BlockTree;

	/**
	 * @param velvetCode Velvet input code
	 */
	constructor(velvetCode: string) {
		this.ast = [];
		this.lines = [];
		this.blockTree = BTConverter.convert(velvetCode);
	}

	/**
	 * Get tag from regex
	 *
	 * @param regexResult
	 * @param indent
	 * @returns
	 */
	getTagFrom(regexResult: { [key: string]: string }, indent = 0): VTag {
		return {
			tag: regexResult.tag.trim(),
			childs: [regexResult.rest],
			indent,
		};
	}
}
