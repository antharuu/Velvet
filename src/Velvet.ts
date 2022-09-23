import Converter from "./Velvet/Converter";
import Parser from "./Velvet/Parser";

/**
 * Velvet preprocessor
 */
export default class Velvet {
	/**
	 * Convert Velvet code into valid HTML
	 *
	 * @param velvetCode Velvet code input
	 * @returns Html outpout
	 */
	static parse(velvetCode: string): string {
		const P = new Parser(velvetCode);
		return Converter.getHTML(P.getAST());
	}
}
