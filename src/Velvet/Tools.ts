import { TempBlock } from "./Types/AST.js";
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
			blocks.push({
				line: mainLine.trimStart(),
				block: getBlocksOf(removeIndentOf(current_block)),
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
