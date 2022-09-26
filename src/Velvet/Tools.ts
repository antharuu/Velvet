import { DefaultConfig, TabSize } from "./Types/Config";

/**
 * Get indent from line
 *
 * @param line line
 * @param tabSize tab size in config
 * @return indent
 */
export function getIdent(line: string, tabSize: TabSize | undefined): number {
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
 * Get the usable lines from string
 *
 * @param velvetCode input velvet code
 * @returns array of lines
 */
export function getLinesOf(velvetCode: string): string[] {
	return velvetCode.split(/[\n]/).filter((str) => str.trim().length > 0);
}
