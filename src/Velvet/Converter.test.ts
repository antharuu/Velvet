import Converter from "./Converter.js";

test("Get HTML from simple AST", () => {
	expect(
		Converter.getHTML([
			{
				tag: "h1",
				children: ["Hello world"],
				indent: 0,
			},
		])
	).toBe("<h1>Hello world</h1>");
});

test("Get HTML from nested AST", () => {
	expect(
		Converter.getHTML([
			{
				tag: "h1",
				children: [
					"Hello ",
					{
						tag: "a",
						children: ["world"],
						indent: 1,
					},
					" !",
				],
				indent: 0,
			},
		])
			.split("\n")
			.join("") // replaceAll polyfill
	).toBe("<h1>Hello <a>world</a> !</h1>");
});

test("Get html from line", () => {
	expect("Hello world").toEqual("Hello world");
	expect(
		Converter.getHtmlFromLine({
			tag: "h1",
			children: ["Hello world"],
			indent: 0,
		})
	).toEqual("<h1>Hello world</h1>");
});

test("Get from tag", () => {
	expect(
		Converter.getHtmlFromLine({
			tag: "h1",
			children: ["Hello world"],
			indent: 0,
		})
	).toEqual("<h1>Hello world</h1>");
	expect(
		Converter.getHtmlFromLine({
			tag: "",
			children: [""],
			indent: 0,
		})
	).toEqual("");
});

test("Get with attributes", () => {
	expect(
		Converter.getHtmlFromLine({
			tag: "h1",
			children: ["Hello world"],
			attributes: {
				disabled: null,
			},
			indent: 0,
		})
	).toEqual("<h1 disabled>Hello world</h1>");

	expect(
		Converter.getHtmlFromLine({
			tag: "h1",
			children: ["Hello world"],
			attributes: {
				disabled: null,
				"aria-label": "Hello world",
			},
			indent: 0,
		})
	).toEqual('<h1 disabled aria-label="Hello world">Hello world</h1>');
});
