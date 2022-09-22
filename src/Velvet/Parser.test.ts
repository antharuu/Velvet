import Parser from "./Parser";
import { AST } from "./Types/AST";

test("Get simple h1 AST", () => {
	const P = new Parser("h1 Hello world");
	const wantedAST: AST = [{ tag: "h1", childs: ["Hello world"], indent: 0 }];
	expect(P.getAST()).toStrictEqual(wantedAST);
});

test("Get indent of simple line", () => {
	const P = new Parser("", { tabSize: 2 });
	expect(P.config.tabSize).toEqual(2);

	expect(P.getIdent("h1 Hello world")).toEqual(0);
	expect(P.getIdent("  h1 Hello world")).toEqual(1);
	expect(P.getIdent("    h1 Hello world")).toEqual(2);

	const P2 = new Parser("", { tabSize: 2 });
	expect(P2.getIdent("    h1 Hello world")).toEqual(1);
});

test.skip("Get indentation of blocks", () => {
	const P = new Parser(`h1
	small
		span OK`);
	const wantedAST: AST = [
		{
			tag: "h1",
			childs: [
				{
					tag: "small",
					childs: [{ tag: "span", childs: ["OK"], indent: 2 }],
					indent: 1,
				},
			],
			indent: 0,
		},
	];
	expect(P.getAST()).toBe(wantedAST);
});
