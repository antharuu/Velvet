import { getBlocksOf, getRegexOf } from "./Tools.js";
/**
 * Parse the Velvet code into AST *(Abstract Syntax Tree)*
 */
export default class Parser {
    /**
     * Convert a string into an usable AST
     * @param velvetCode String to be converted in AST
     */
    static convert(velvetCode) {
        return Parser.blockToAST(getBlocksOf(velvetCode));
    }
    static blockToAST(blocks, indent = 0) {
        const ast = [];
        blocks.forEach((block) => {
            var _a, _b;
            const res = (_a = getRegexOf(Parser.lineRegex, block.line)) !== null && _a !== void 0 ? _a : "";
            if (res.tag) {
                const children = this.blockToAST(block.block, indent + 1);
                ast.push({
                    tag: res.tag,
                    children: ((_b = res.line_content) === null || _b === void 0 ? void 0 : _b.trim().length) > 0
                        ? [res.line_content, ...children]
                        : children,
                    indent,
                });
            }
        });
        return ast;
    }
}
Parser.lineRegex = /^(?<tag>[\w]+)( ?)(?<line_content>.*)?/;
//# sourceMappingURL=Parser.js.map