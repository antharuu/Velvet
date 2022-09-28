"use strict";
var __spreadArray = (this && this.__spreadArray) || function (to, from, pack) {
    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
        if (ar || !(i in from)) {
            if (!ar) ar = Array.prototype.slice.call(from, 0, i);
            ar[i] = from[i];
        }
    }
    return to.concat(ar || Array.prototype.slice.call(from));
};
Object.defineProperty(exports, "__esModule", { value: true });
var Tools_1 = require("./Tools");
/**
 * Parse the Velvet code into AST *(Abstract Syntax Tree)*
 */
var Parser = /** @class */ (function () {
    function Parser() {
    }
    /**
     * Convert a string into an usable AST
     * @param velvetCode String to be converted in AST
     */
    Parser.convert = function (velvetCode) {
        return Parser.blockToAST((0, Tools_1.getBlocksOf)(velvetCode));
    };
    Parser.blockToAST = function (blocks, indent) {
        var _this = this;
        if (indent === void 0) { indent = 0; }
        var ast = [];
        blocks.forEach(function (block) {
            var _a, _b;
            var res = (_a = (0, Tools_1.getRegexOf)(Parser.lineRegex, block.line)) !== null && _a !== void 0 ? _a : "";
            if (res.tag) {
                var children = _this.blockToAST(block.block, indent + 1);
                ast.push({
                    tag: res.tag,
                    children: ((_b = res.line_content) === null || _b === void 0 ? void 0 : _b.trim().length) > 0
                        ? __spreadArray([res.line_content], children, true) : children,
                    indent: indent,
                });
            }
        });
        return ast;
    };
    Parser.lineRegex = /^(?<tag>[\w]+)( ?)(?<line_content>.*)?/;
    return Parser;
}());
exports.default = Parser;
