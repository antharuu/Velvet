import {
	getBlocksOf,
	getIndentOf,
	getLinesOf,
	getRegexOf,
	getTabRegex,
	removeIndentOf,
} from "./Tools";
import { TempBlock } from "./Types/AST";

describe("Indentation", () => {
	test("With tabsize: tab", () => {
		expect(getIndentOf("h1 Hello world", "tab")).toEqual(0);
		expect(getIndentOf("	h1 Hello world", "tab")).toEqual(1);
		expect(getIndentOf("		h1 Hello world", "tab")).toEqual(2);
	});
	test("With tabsize: 2", () => {
		expect(getIndentOf("h1 Hello world", 2)).toEqual(0);
		expect(getIndentOf("  h1 Hello world", 2)).toEqual(1);
		expect(getIndentOf("    h1 Hello world", 2)).toEqual(2);
	});
	test("With tabsize: 4", () => {
		expect(getIndentOf("h1 Hello world", 4)).toEqual(0);
		expect(getIndentOf("    h1 Hello world", 4)).toEqual(1);
		expect(getIndentOf("        h1 Hello world", 4)).toEqual(2);
	});
});

test("Get lines from string", () => {
	expect(getLinesOf(`h1 Hello`)).toStrictEqual(["h1 Hello"]);

	expect(
		getLinesOf(`h1 Hello
	h2 World`)
	).toStrictEqual(["h1 Hello", "	h2 World"]);

	expect(
		getLinesOf(`h1 Hello
		
	h2 World`)
	).toStrictEqual(["h1 Hello", "	h2 World"]);

	expect(
		getLinesOf(`h1 Hello
		
	h2 World
		a Et le reste !`)
	).toStrictEqual(["h1 Hello", "	h2 World", "		a Et le reste !"]);
});

test("Get blocks from string", () => {
	expect(getBlocksOf(`h1 Hello world`)).toStrictEqual([
		{
			line: "h1 Hello world",
			block: [],
		},
	] as TempBlock[]);

	expect(
		getBlocksOf(`h1 Hello
	span

		a world`)
	).toStrictEqual([
		{
			line: "h1 Hello",
			block: [{ line: "span", block: [{ line: "a world", block: [] }] }],
		},
	] as TempBlock[]);

	expect(
		getBlocksOf(
			`h1 Hello
	span

		a world 2
h2 Block
	span 2
	a
		small coucou`
		)
	).toStrictEqual([
		{
			line: "h1 Hello",
			block: [
				{ line: "span", block: [{ line: "a world 2", block: [] }] },
			],
		},
		{
			line: "h2 Block",
			block: [
				{ line: "span 2", block: [] },
				{ line: "a", block: [{ line: "small coucou", block: [] }] },
			],
		},
	] as TempBlock[]);
});

test("Get empty block", () => {
	expect(getBlocksOf("")).toStrictEqual([]);
});

test("Remove indent of string", () => {
	expect(removeIndentOf<string>("h1 Hello world")).toBe("h1 Hello world");
	expect(removeIndentOf<string>("	h1 Hello world")).toBe("h1 Hello world");
	expect(removeIndentOf<string>("		h1 Hello world")).toBe("	h1 Hello world");
	expect(removeIndentOf<string>("		h1 Hello world	")).toBe("	h1 Hello world	");
});

test("Remove indent of string array", () => {
	expect(removeIndentOf(["	h1 Hello world"])).toStrictEqual([
		"h1 Hello world",
	]);
	expect(
		removeIndentOf(["	h1 Hello world", "	h2", "		a how are you ?"])
	).toStrictEqual(["h1 Hello world", "h2", "	a how are you ?"]);
});

test("Get good regex for tab", () => {
	expect(getTabRegex()).toStrictEqual(/^(\t)/);
	expect(getTabRegex(2)).toStrictEqual(/^(  )/);
	expect(getTabRegex(4)).toStrictEqual(/^(    )/);
});

test("Get regex groups of simple line", () => {
	expect(
		getRegexOf(/^(?<tag>[\w]+) ?(?<line_content>.*)/, "h1 Hello world !")
	).toHaveProperty("tag");
	expect(
		getRegexOf(/^(?<tag>[\w]+) ?(?<line_content>.*)/, "h1 Hello world !")
	).toHaveProperty("line_content");
});

test("Get empty object for invalid line or regex", () => {
	expect(getRegexOf(/^([\w]+) ?(.*)/, "h1 Hello world !")).toStrictEqual({});
	expect(getRegexOf(/^([\w]+) ?(.*)/, "")).toStrictEqual({});
});
