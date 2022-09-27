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
export function getBlocksOf(velvetCode: string): TempBlock[] {
	const lines = getLinesOf(velvetCode),
		blocks: TempBlock[] = [];
	let mainLine = "",
		currentIndent = getIndentOf(mainLine),
		current_block: string[] = [];

	function currentBlockEnd(): void {
		if (mainLine.trim().length > 0) {
			blocks.push({
				line: mainLine,
				block: current_block,
			});
		}
		mainLine = "";
	}

	while (lines.length > 0) {
		if (mainLine.length == 0) {
			mainLine = lines.shift() ?? "";
			currentIndent = getIndentOf(mainLine);
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
