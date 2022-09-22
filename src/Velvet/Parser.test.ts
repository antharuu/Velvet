import Parser from "./Parser";
import { AST } from "./Types/AST";

describe("Indentation", () => {
	test("With tabsize: tab", () => {
		const P2 = new Parser("", { tabSize: "tab" });
		expect(P2.getIdent("h1 Hello world")).toEqual(0);
		expect(P2.getIdent("	h1 Hello world")).toEqual(1);
		expect(P2.getIdent("		h1 Hello world")).toEqual(2);
	});
	test("With tabsize: 2", () => {
		const P = new Parser("", { tabSize: 2 });
		expect(P.getIdent("h1 Hello world")).toEqual(0);
		expect(P.getIdent("  h1 Hello world")).toEqual(1);
		expect(P.getIdent("    h1 Hello world")).toEqual(2);
	});
	test("With tabsize: 4", () => {
		const P2 = new Parser("", { tabSize: 4 });
		expect(P2.getIdent("h1 Hello world")).toEqual(0);
		expect(P2.getIdent("    h1 Hello world")).toEqual(1);
		expect(P2.getIdent("        h1 Hello world")).toEqual(2);
	});
});

test("Get simple h1 AST", () => {
	const P = new Parser("h1 Hello world");
	const wantedAST: AST = [{ tag: "h1", childs: ["Hello world"], indent: 0 }];
	expect(P.getAST()).toStrictEqual(wantedAST);
});

test.todo("Get indentation of blocks", () => {
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
