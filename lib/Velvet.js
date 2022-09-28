"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
var Converter_1 = __importDefault(require("./Velvet/Converter"));
var Parser_1 = __importDefault(require("./Velvet/Parser"));
/**
 * Velvet preprocessor
 */
var Velvet = /** @class */ (function () {
    function Velvet() {
    }
    /**
     * Convert Velvet code into valid HTML
     *
     * @param velvetCode Velvet code input
     * @returns Html outpout
     */
    Velvet.parse = function (velvetCode) {
        return Converter_1.default.getHTML(Parser_1.default.convert(velvetCode));
    };
    return Velvet;
}());
exports.default = Velvet;
