import { AST, TempBlock } from "./Types/AST";
/**
 * Parse the Velvet code into AST *(Abstract Syntax Tree)*
 */
export default class Parser {
    static lineRegex: RegExp;
    /**
     * Convert a string into an usable AST
     * @param velvetCode String to be converted in AST
     */
    static convert(velvetCode: string): AST;
    static blockToAST(blocks: TempBlock[], indent?: number): AST;
}
//# sourceMappingURL=Parser.d.ts.map