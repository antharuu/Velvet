import { TempBlock } from "./Types/AST";
import { TabSize } from "./Types/Config";
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
 * Return groups from given regex and string
 *
 * @param regex used regex
 * @param string string to use regex
 * @returns finded groups
 */
export declare function getRegexOf(regex: RegExp, string: string): {
    [key: string]: string;
};
//# sourceMappingURL=Tools.d.ts.map