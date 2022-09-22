import { error } from "./Error";
import { LineRegex, RegexParse } from "./Regex";
import { AST, VNode, VTag } from "./Types/AST";

export default class Parser {
  ast: AST;
  lines: string[];

  constructor(velvetcode: string) {
    this.ast = [];
    this.lines = [];
    this.setAST(velvetcode);
  }

  setAST(velvetcode: string) {
    this.lines = velvetcode.split("\n");
    let indent = 0;
    this.lines.forEach((line: string, i: number): void => {
      line = line.trim();
      if (line.length > 0) {
        const r = RegexParse(line, LineRegex);
        if (r === null) {
          error("Parser", `Cant parse the #${i} line: "${line}"`);
        } else {
          const node: VTag = { tag: r.tag.trim(), childs: [r.rest], indent };
          this.ast.push(node);
        }
      }
    });
  }

  getAST(): AST {
    return this.ast;
  }
}
