import { getBlocksOf } from "./Tools";
import { AST, TempBlock } from "./Types/AST";

/**
 * Parse the Velvet code into AST *(Abstract Syntax Tree)*
 */
export default class Parser {
	/**
	 * Convert a string into an usable AST
	 * @param velvetCode String to be converted in AST
	 */
	static convert(velvetCode: string): AST {
		const ast: AST = [];

		Parser.blockToAST(getBlocksOf(velvetCode));

		return ast;
	}

	static blockToAST(block: TempBlock): AST {
		return [];
	}
}
