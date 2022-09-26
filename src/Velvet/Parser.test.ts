import Parser from "./Parser";
import { AST } from "./Types/AST";

test.todo("should return a simple block", () => {
	expect(Parser.convert(`h1 Hello`)).toStrictEqual([
		{ tag: "h1", children: ["Hello"], indent: 0 },
	] as AST);
});
