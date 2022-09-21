import Parser from "./Parser";
import { AST } from "./Types/AST";

test("Get simple h1 AST", () => {
  const P = new Parser("h1 Hello world");
  const wantedAST: AST = [{ tag: "h1", childs: ["Hello world"] }];
  expect(P.getAST()).toStrictEqual(wantedAST);
});
