"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.getRegexOf = exports.getLinesOf = exports.getBlocksOf = exports.getIndentOf = void 0;
const Config_1 = require("./Types/Config");
const VelvetConfig_1 = __importDefault(require("./VelvetConfig"));
/**
 * Get indent from line
 *
 * @param line line
 * @param tabSize tab size in config
 * @return indent
 */
function getIndentOf(line, tabSize = undefined) {
    if (!tabSize)
        tabSize = Config_1.DefaultConfig.tabSize;
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
exports.getIndentOf = getIndentOf;
/**
 * Get the usable blocks from string
 *
 * @param velvetCode input velvet code
 * @returns array of blocks
 */
function getBlocksOf(velvetCode) {
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
exports.getBlocksOf = getBlocksOf;
/**
 * Get the usable lines from string
 *
 * @param velvetCode input velvet code
 * @returns array of lines
 */
function getLinesOf(velvetCode) {
    return velvetCode.split(/[\n]/).filter((str) => str.trim().length > 0);
}
exports.getLinesOf = getLinesOf;
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
    const tabSize = forceTabSize !== null && forceTabSize !== void 0 ? forceTabSize : VelvetConfig_1.default.get().tabSize;
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
function getRegexOf(regex, string) {
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
exports.getRegexOf = getRegexOf;
//# sourceMappingURL=Tools.js.map