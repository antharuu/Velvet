import { TempBlock } from "./Types/AST";
import { DefaultConfig, TabSize } from "./Types/Config";
import VelvetConfig from "./VelvetConfig";

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
		tab = /^(  )/;
	} else if (tabSize === 4) {
		tab = /^(    )/;
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

	const blocks: TempBlock[] = [];
	let mainLine = "",
		currentIndent = getIndentOf(mainLine),
		current_block: string[] = [];

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
			mainLine = lines.shift() ?? "";
			current_block = [];
		}
		const line = lines[0] ?? "";

		if (currentIndent < getIndentOf(line)) {
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
function removeIndentOf(lines: string[]): string[] {
	return lines.map((line) => {
		return line.replace(getTabRegex(), "");
	});
}

/**
 * Get the tab size and return regex
 *
 * @returns Tab regex
 */
function getTabRegex(forceTabSize: TabSize | undefined = undefined): RegExp {
	const tabSize = forceTabSize ?? VelvetConfig.get().tabSize;
	if (tabSize == 2) {
		return /^(  )/;
	} else if (tabSize == 4) {
		return /^(    )/;
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
		limit = 0,
		groups: { [key: string]: string } | null = null;
	while ((m = regex.exec(string)) !== null && !groups) {
		// This is necessary to avoid infinite loops with zero-width matches
		if (m.index === regex.lastIndex) {
			regex.lastIndex++;
		}
		groups = m.groups ?? {};
		limit++;
	}
	return groups ?? {};
}
