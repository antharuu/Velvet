import { error } from "./Error";
import { LineRegex, RegexParse } from "./Regex";
import { AST, VTag } from "./Types/AST";
import { Config, DefaultConfig } from "./Types/Config";

/**
 * Parse the Velvet code into AST
 */
export default class Parser {
	ast: AST;
	lines: string[];
	config: Config;

	/**
	 * @param velvetCode Velvet input code
	 * @param config Config object
	 */
	constructor(velvetCode: string, config: Config = {}) {
		this.config = {
			...DefaultConfig,
			...config,
		};
		this.ast = [];
		this.lines = [];
		this.setAST(velvetCode);
	}

	/**
	 * Transform velvet code into AST
	 *
	 * @param velvetCode Velvet input code
	 */
	setAST(velvetCode: string) {
		this.lines = velvetCode.split("\n");
		let indent = 0;
		let blockChilds: AST = [];
		this.lines.forEach((line: string, i: number): void => {
			indent = this.getIdent(line);
			line = line.trimStart();
			if (line.length > 0) {
				const r = RegexParse(line, LineRegex);
				if (r === null) {
					error("Parser", `Cant parse the #${i} line: "${line}"`);
				} else {
					const node: VTag = this.getTagFrom(r, indent);
					this.ast.push(node);
				}
			}
		});
	}
	/**
	 * Get indent from line
	 *
	 * @param line line
	 * @return indent
	 */
	getIdent(line: string): number {
		let tab = /^(\t)/,
			indent = 0;
		if (this.config.tabSize === 2) {
			tab = /^(  )/;
		} else if (this.config.tabSize === 4) {
			tab = /^(    )/;
		}

		while (tab.test(line)) {
			indent++;
			line.replace(tab, "");
		}

		return indent;
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

	/**
	 * Get the Asbtract syntax tree
	 *
	 * @returns AST
	 */
	getAST(): AST {
		return this.ast;
	}
}
