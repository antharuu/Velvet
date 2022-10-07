import Parser from "./Parser";
import { getBlocksOf } from "./Tools";
import { AST } from "./Types/AST";

test("Should convert simple line to ast", () => {
	expect(Parser.blockToAST(getBlocksOf("h1 Hello world"))).toStrictEqual([
		{
			tag: "h1",
			children: ["Hello world"],
			indent: 0,
		},
	] as AST);
});

test("Should convert simple lines to ast", () => {
	expect(
		Parser.blockToAST(
			getBlocksOf(`h1 Hello
	small world`)
		)
	).toStrictEqual([
		{
			tag: "h1",
			children: [
				"Hello",
				{
					tag: "small",
					children: ["world"],
					indent: 1,
				},
			],
			indent: 0,
		},
	] as AST);
});

test("Should convert multiples lines and blocks to ast", () => {
	expect(
		Parser.blockToAST(
			getBlocksOf(`h1 Hello
	small world
		a !
	span -
		a i think
h2 But
	small maybe
		i not
		b or
		i maybe
			a yes !
	span i'm not sure`)
		)
	).toStrictEqual([
		{
			tag: "h1",
			children: [
				"Hello",
				{
					tag: "small",
					children: [
						"world",
						{
							tag: "a",
							children: ["!"],
							indent: 2,
						},
					],
					indent: 1,
				},
				{
					tag: "span",
					children: [
						"-",
						{
							tag: "a",
							children: ["i think"],
							indent: 2,
						},
					],
					indent: 1,
				},
			],
			indent: 0,
		},
		{
			tag: "h2",
			children: [
				"But",
				{
					tag: "small",
					children: [
						"maybe",
						{
							tag: "i",
							children: ["not"],
							indent: 2,
						},
						{
							tag: "b",
							children: ["or"],
							indent: 2,
						},
						{
							tag: "i",
							children: [
								"maybe",
								{
									tag: "a",
									children: ["yes !"],
									indent: 3,
								},
							],
							indent: 2,
						},
					],
					indent: 1,
				},
				{
					tag: "span",
					children: ["i'm not sure"],
					indent: 1,
				},
			],
			indent: 0,
		},
	] as AST);
});

test("Should work with simple empty attributes", () => {
	expect(
		Parser.blockToAST(getBlocksOf("h1(disabled) Hello world"))
	).toStrictEqual([
		{
			tag: "h1",
			children: ["Hello world"],
			attributes: {
				disabled: null,
			},
			indent: 0,
		},
	] as AST);

	expect(
		Parser.blockToAST(
			getBlocksOf(
				"h1(disabled href='www.google.com' alt='Test (enfin je crois)') Hello world"
			)
		)
	).toStrictEqual([
		{
			tag: "h1",
			children: ["Hello world"],
			attributes: {
				disabled: null,
				alt: "Test (enfin je crois)",
				href: "www.google.com",
			},
			indent: 0,
		},
	] as AST);
});
