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
        tab = /^( {2})/;
    }
    else if (tabSize === 4) {
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
export function getBlocksOf(velvetCode) {
    var _a, _b, _c;
    let lines = [];
    if (Array.isArray(velvetCode)) {
        lines = velvetCode;
    }
    else {
        lines = getLinesOf(velvetCode);
    }
    let mainLine = "", current_block = [];
    const blocks = [], currentIndent = getIndentOf(mainLine);
    function currentBlockEnd() {
        if (mainLine.trim().length > 0) {
            const c_block = getBlocksOf(removeIndentOf(current_block));
            blocks.push({
                line: mainLine.replace(/^(\s*)/, ""),
                block: c_block,
            });
        }
        mainLine = "";
    }
    while (lines.length > 0) {
        if (mainLine.length == 0) {
            /* c8 ignore next */
            mainLine = (_a = lines.shift()) !== null && _a !== void 0 ? _a : "";
            current_block = [];
        }
        const line = (_b = lines[0]) !== null && _b !== void 0 ? _b : "";
        if (currentIndent < getIndentOf(line)) {
            /* c8 ignore next */
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
export function removeIndentOf(lines) {
    if (typeof lines === "string") {
        return lines.replace(getTabRegex(), "");
    }
    else {
        return lines.map((line) => {
            return line.replace(getTabRegex(), "");
        });
    }
}
/**
 * Get the tab size and return regex
 *
 * @returns Tab regex
 */
export function getTabRegex(forceTabSize = undefined) {
    const tabSize = forceTabSize !== null && forceTabSize !== void 0 ? forceTabSize : VelvetConfig.get().tabSize;
    if (tabSize == 2) {
        return /^( {2})/;
    }
    else if (tabSize == 4) {
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
/**
 * Get attributes from line
 * @param lineStr line to get attributes
 * @returns attributes
 */
export function getAttributesOf(lineStr) {
    var _a;
    const attributesObj = {};
    const { line, attributes } = getPartsOfLine(lineStr);
    if (attributes.length > 0) {
        // Split with regex this attributes "disabled href='www.google.com' alt='Test (enfin je crois)'"
        const attrRegex = /(?<name>[a-zA-Z0-9-]+)(?:\s*=\s*(?<value>"([^"]*)"|'([^']*)'|([^'"\s]+)))?/g;
        let m;
        while ((m = attrRegex.exec(attributes)) !== null) {
            if (m.index === attrRegex.lastIndex) {
                attrRegex.lastIndex++;
            }
            if (m.groups) {
                const name = m.groups.name;
                const value = (_a = m.groups.value) !== null && _a !== void 0 ? _a : null;
                attributesObj[name] = removeStringQuote(value);
            }
        }
        return { line, attributes: attributesObj };
    }
    return {
        line: line.replace(attributes, "").replace(/^ */, ""),
        attributes: attributesObj,
    };
}
/**
 * Get parts of line
 *
 * @param lineStr line to get parts
 * @returns parts
 */
export function getPartsOfLine(lineStr) {
    const line = "", attributes = "";
    if (lineStr.startsWith("(")) {
        let parenthesis = 0;
        for (let i = 0; i < lineStr.length; i++) {
            const char = lineStr[i];
            if (char === "(") {
                parenthesis++;
            }
            else if (char === ")") {
                parenthesis--;
            }
            if (parenthesis === 0) {
                return {
                    line: lineStr.slice(i + 1).replace(/^ */, ""),
                    attributes: lineStr
                        .slice(0, i + 1)
                        .replace(/^\(/, "")
                        .replace(/\)$/, "")
                        .trim(),
                };
            }
        }
    }
    return { line, attributes };
}
/**
 * Remove quotes from strings
 * Remove only if surrounded by same quotes
 *
 * @param str string to remove quotes
 * @returns string without quotes
 */
export function removeStringQuote(str) {
    if (str === null)
        return str;
    if (str.startsWith('"') && str.endsWith('"')) {
        return str.slice(1, -1);
    }
    else if (str.startsWith("'") && str.endsWith("'")) {
        return str.slice(1, -1);
    }
    return str;
}
//# sourceMappingURL=Tools.js.map