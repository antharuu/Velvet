import { DefaultConfig, TabSize } from "./Types/Config";

/**
 * Get indent from line
 *
 * @param line line
 * @param tabSize tab size in config
 * @return indent
 */
export function getIdent(line: string, tabSize: TabSize | undefined): number {
	const tab = getTabRegex(tabSize);
	let indent = 0;

	if (line.length !== line.trim().length) {
		while (tab.test(line)) {
			indent++;
			line = line.replace(tab, "");
		}
	}

	return indent;
}

/**
 * Remove one indentation to line
 *
 * @param line input line
 * @param tabSize tab size
 * @returns line with -1 indentation
 */
export function removeIndent(
	line: string,
	tabSize: TabSize | undefined
): string {
	const tab = getTabRegex(tabSize);
	return line.replace(tab, "");
}

/**
 * Return a regex of tabsize
 *
 * @param tabSize tabsize
 * @returns tabsize in regex
 */
function getTabRegex(tabSize: TabSize | undefined): RegExp {
	if (!tabSize) tabSize = DefaultConfig.tabSize;

	if (tabSize === 2) {
		return /^(  )/;
	} else if (tabSize === 4) {
		return /^(    )/;
	}
	return /^(\t)/;
}
