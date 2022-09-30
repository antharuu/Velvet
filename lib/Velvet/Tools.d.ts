import { BlockAttr, TempBlock } from "./Types/AST.js";
import { TabSize } from "./Types/Config.js";
/**
 * Get indent from line
 *
 * @param line line
 * @param tabSize tab size in config
 * @return indent
 */
export declare function getIndentOf(line: string, tabSize?: TabSize | undefined): number;
/**
 * Get the usable blocks from string
 *
 * @param velvetCode input velvet code
 * @returns array of blocks
 */
export declare function getBlocksOf(velvetCode: string | string[]): TempBlock[];
/**
 * Get the usable lines from string
 *
 * @param velvetCode input velvet code
 * @returns array of lines
 */
export declare function getLinesOf(velvetCode: string): string[];
/**
 * Remove an indentation from lines
 *
 * @param lines lines to remove indent
 */
export declare function removeIndentOf<T extends string | string[]>(lines: T): T;
/**
 * Get the tab size and return regex
 *
 * @returns Tab regex
 */
export declare function getTabRegex(forceTabSize?: TabSize | undefined): RegExp;
/**
 * Return groups from given regex and string
 *
 * @param regex used regex
 * @param string string to use regex
 * @returns finded groups
 */
export declare function getRegexOf(regex: RegExp, string: string): {
    [key: string]: string;
};
export declare function getBlockAttrOf(block: TempBlock[]): BlockAttr;
//# sourceMappingURL=Tools.d.ts.map