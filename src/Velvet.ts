import Converter from "./Velvet/Converter";
import Parser from "./Velvet/Parser";

export default class Velvet {
  static parse(velvetCode: string): string {
    const P = new Parser(velvetCode);
    return Converter.getHTML(P.getAST());
  }
}
