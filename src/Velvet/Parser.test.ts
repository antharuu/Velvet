import Parser from "./Parser";
import { AST } from "./Types/AST";

test("Get simple h1 AST", () => {
  const P = new Parser("h1 Hello world");
  const wantedAST: AST = [{ tag: "h1", childs: ["Hello world"], indent: 0 }];
  expect(P.getAST()).toStrictEqual(wantedAST);
});

test("Get indentation of blocks", () => {
  expect("").toBe("");
});
