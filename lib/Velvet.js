"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const Converter_1 = __importDefault(require("./Velvet/Converter"));
const Parser_1 = __importDefault(require("./Velvet/Parser"));
/**
 * Velvet preprocessor
 */
class Velvet {
    /**
     * Convert Velvet code into valid HTML
     *
     * @param velvetCode Velvet code input
     * @returns Html outpout
     */
    static parse(velvetCode) {
        return Converter_1.default.getHTML(Parser_1.default.convert(velvetCode));
    }
}
exports.default = Velvet;
//# sourceMappingURL=Velvet.js.map