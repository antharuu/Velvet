import { DefaultConfig } from "./Types/Config.js";
import VelvetConfig from "./VelvetConfig.js";
/**
 * Get indent from line
 *
 * @param line line
 * @param tabSize tab size in config
 * @return indent
 */
export function getIndentOf(line, tabSize = undefined) {
    if (!tabSize)
        tabSize = DefaultConfig.tabSize;
    let tab = /^(\t)/, indent = 0;
    if (tabSize === 2) {
        tab = /^(  )/;
    }
    else if (tabSize === 4) {
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
export function getBlocksOf(velvetCode) {
    var _a, _b, _c;
    let lines = [];
    if (Array.isArray(velvetCode)) {
        lines = velvetCode;
    }
    else {
        lines = getLinesOf(velvetCode);
    }
    const blocks = [];
    let mainLine = "", currentIndent = getIndentOf(mainLine), current_block = [];
    function currentBlockEnd() {
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
            mainLine = (_a = lines.shift()) !== null && _a !== void 0 ? _a : "";
            current_block = [];
        }
        const line = (_b = lines[0]) !== null && _b !== void 0 ? _b : "";
        if (currentIndent < getIndentOf(line)) {
            current_block.push((_c = lines.shift()) !== null && _c !== void 0 ? _c : "");
        }
        else {
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
export function getLinesOf(velvetCode) {
    return velvetCode.split(/[\n]/).filter((str) => str.trim().length > 0);
}
/**
 * Remove an indentation from lines
 *
 * @param lines lines to remove indent
 */
function removeIndentOf(lines) {
    return lines.map((line) => {
        return line.replace(getTabRegex(), "");
    });
}
/**
 * Get the tab size and return regex
 *
 * @returns Tab regex
 */
function getTabRegex(forceTabSize = undefined) {
    const tabSize = forceTabSize !== null && forceTabSize !== void 0 ? forceTabSize : VelvetConfig.get().tabSize;
    if (tabSize == 2) {
        return /^(  )/;
    }
    else if (tabSize == 4) {
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
export function getRegexOf(regex, string) {
    var _a;
    let m, groups = null;
    while ((m = regex.exec(string)) !== null && !groups) {
        // This is necessary to avoid infinite loops with zero-width matches
        if (m.index === regex.lastIndex) {
            regex.lastIndex++;
        }
        groups = (_a = m.groups) !== null && _a !== void 0 ? _a : {};
    }
    return groups !== null && groups !== void 0 ? groups : {};
}
//# sourceMappingURL=Tools.js.map