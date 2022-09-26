import { TempBlock } from "./Types/AST";
import { DefaultConfig, TabSize } from "./Types/Config";

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
export function getBlocksOf(velvetCode: string): TempBlock {
	const lines = getLinesOf(velvetCode),
		mainLine = lines.shift() ?? "",
		currentIndent = getIndentOf(mainLine),
		block: string[] = [];
	lines.forEach((line) => {
		if (currentIndent < getIndentOf(line)) {
			block.push(line);
		}
	});
	return {
		line: mainLine,
		block,
	};
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
