import { error } from "./Error";
import { LineRegex, RegexParse } from "./Regex";
import { AST, VTag } from "./Types/AST";
import { Config, DefaultConfig } from "./Types/Config";

/**
 * Parse the Velvet code into AST
 */
export default class Parser {
  ast: AST;
  lines: string[];
  config: Config;

  /**
   * @param velvetCode Velvet input code
   * @param config Config object
   */
  constructor(velvetCode: string, config: Config = {}) {
    this.config = {
      ...config,
      ...DefaultConfig,
    };
    this.ast = [];
    this.lines = [];
    this.setAST(velvetCode);
  }

  /**
   * Transform velvet code into AST
   *
   * @param velvetCode Velvet input code
   */
  setAST(velvetCode: string) {
    this.lines = velvetCode.split("\n");
    let indent = 0;
    this.lines.forEach((line: string, i: number): void => {
      line = line.trim();
      if (line.length > 0) {
        const r = RegexParse(line, LineRegex);
        if (r === null) {
          error("Parser", `Cant parse the #${i} line: "${line}"`);
        } else {
          const node: VTag = this.getTagFrom(r, indent);
          this.ast.push(node);
        }
      }
    });
  }

  /**
   * Get tag from regex
   *
   * @param regexResult
   * @param indent
   * @returns
   */
  getTagFrom(regexResult: { [key: string]: string }, indent = 0): VTag {
    return { tag: regexResult.tag.trim(), childs: [regexResult.rest], indent };
  }

  /**
   * Get the Asbtract syntax tree
   *
   * @returns AST
   */
  getAST(): AST {
    return this.ast;
  }
}
