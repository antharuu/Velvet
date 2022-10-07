import { BlockAttr, TempBlock, VAttributes } from "./Types/AST.js";
import { DefaultConfig, TabSize } from "./Types/Config.js";
import VelvetConfig from "./VelvetConfig.js";

/**
 * Get indent from line
 *
 * @param line line
 * @param tabSize tab size in config
 * @return indent
 */
export function getIndentOf(
	line: string,
	tabSize: TabSize | undefined = undefined
): number {
	if (!tabSize) tabSize = DefaultConfig.tabSize;

	let tab = /^(\t)/,
		indent = 0;
	if (tabSize === 2) {
		tab = /^( {2})/;
	} else if (tabSize === 4) {
		tab = /^( {4})/;
	}

	if (line.length !== line.trim().length) {
		while (tab.test(line)) {
			indent++;
			line = line.replace(tab, "");
		}
	}

	return indent;
}

/**
 * Get the usable blocks from string
 *
 * @param velvetCode input velvet code
 * @returns array of blocks
 */
export function getBlocksOf(velvetCode: string | string[]): TempBlock[] {
	let lines: string[] = [];
	const attributes: VAttributes[] = [];

	if (Array.isArray(velvetCode)) {
		lines = velvetCode;
	} else {
		lines = getLinesOf(velvetCode);
	}

	let mainLine = "",
		current_block: string[] = [];
	const blocks: TempBlock[] = [],
		currentIndent = getIndentOf(mainLine);

	function currentBlockEnd(): void {
		if (mainLine.trim().length > 0) {
			const c_block = getBlocksOf(removeIndentOf(current_block));

			// Get Html attributes from line
			const attr = getAttributesOf(mainLine);
			if (attr) {
				attributes.push(attr);
			}

			blocks.push({
				line: mainLine.replace(/^(\s*)/, ""),
				block: c_block,
			});
		}
		mainLine = "";
	}

	while (lines.length > 0) {
		if (mainLine.length == 0) {
			/* c8 ignore next */
			mainLine = lines.shift() ?? "";
			current_block = [];
		}
		const line = lines[0] ?? "";

		if (currentIndent < getIndentOf(line)) {
			/* c8 ignore next */
			current_block.push(lines.shift() ?? "");
		} else {
			currentBlockEnd();
		}
	}
	currentBlockEnd();

	return blocks;
}

/**
 * Get the usable lines from string
 *
 * @param velvetCode input velvet code
 * @returns array of lines
 */
export function getLinesOf(velvetCode: string): string[] {
	return velvetCode.split(/[\n]/).filter((str) => str.trim().length > 0);
}

/**
 * Remove an indentation from lines
 *
 * @param lines lines to remove indent
 */
export function removeIndentOf<T extends string | string[]>(lines: T): T {
	if (typeof lines === "string") {
		return lines.replace(getTabRegex(), "") as T;
	} else {
		return lines.map((line) => {
			return line.replace(getTabRegex(), "");
		}) as T;
	}
}

/**
 * Get the tab size and return regex
 *
 * @returns Tab regex
 */
export function getTabRegex(
	forceTabSize: TabSize | undefined = undefined
): RegExp {
	const tabSize = forceTabSize ?? VelvetConfig.get().tabSize;
	if (tabSize == 2) {
		return /^( {2})/;
	} else if (tabSize == 4) {
		return /^( {4})/;
	}
	return /^(\t)/;
}

/**
 * Return groups from given regex and string
 *
 * @param regex used regex
 * @param string string to use regex
 * @returns finded groups
 */
export function getRegexOf(
	regex: RegExp,
	string: string
): { [key: string]: string } {
	let m,
		groups: { [key: string]: string } | null = null;
	while ((m = regex.exec(string)) !== null && !groups) {
		// This is necessary to avoid infinite loops with zero-width matches
		if (m.index === regex.lastIndex) {
			regex.lastIndex++;
		}
		groups = m.groups ?? {};
	}
	return groups ?? {};
}

export function getBlockAttrOf(block: TempBlock[]): BlockAttr {
	return { current_block: block, attributes: {} };
}

/**
 * Get attributes from line
 *
 * @param line line to get attributes
 * @returns attributes
 */
export function getAttributesOf(lineStr: string): {
	line: string;
	attributes: VAttributes;
} {
	const attributesObj: VAttributes = {};

	const { line, attributes } = getPartsOfLine(lineStr);

	if (attributes.length === 0) {
		return { line, attributes: attributesObj };
	}

	return {
		line: line.replace(attributes, "").replace(/^ */, ""),
		attributes: attributesObj,
	};
}

/**
 * Get parts of line
 *
 * @param lineStr line to get parts
 * @returns parts
 */
export function getPartsOfLine(lineStr: string): {
	line: string;
	attributes: string;
} {
	const line = "",
		attributes = "";
	if (lineStr.startsWith("(")) {
		let parenthesis = 0;
		for (let i = 0; i < lineStr.length; i++) {
			const char = lineStr[i];
			if (char === "(") {
				parenthesis++;
			} else if (char === ")") {
				parenthesis--;
			}

			if (parenthesis === 0) {
				return {
					line: lineStr.slice(i + 1).replace(/^ */, ""),
					attributes: lineStr
						.slice(0, i + 1)
						.replace(/^\(/, "")
						.replace(/\)$/, ""),
				};
			}
		}
	}

	return { line, attributes };
}
