import Converter from "./Converter.js";
import Parser from "./Parser.js";

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
		return Converter.getHTML(Parser.convert(velvetCode));
	}
}
