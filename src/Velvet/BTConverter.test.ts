import BTConverter from "./BTConverter";
import Parser from "./Parser";
import { BlockTree } from "./Types/BlockTree";

describe("Basic blocks", () => {
	test("Get sub block", () => {
		expect(
			BTConverter.getSubBlockOf(`h1
	span Hello`)
		).toStrictEqual([
			{
				line: "h1",
				block: [
					{
						line: "span Hello",
						block: [],
					},
				],
			},
		] as BlockTree);
	});

	test("Get sub block multilines", () => {
		expect(
			BTConverter.getSubBlockOf(`h1
	span Hello
	small World`)
		).toStrictEqual([
			{
				line: "h1",
				block: [
					{ line: "span Hello", block: [] },
					{ line: "small World", block: [] },
				],
			},
		] as BlockTree);
	});
});

test.todo("Get simple depth block tree", () => {
	const vlvtCode = `h1
a Hello world`;
	const wantedABT: BlockTree = [
		{
			line: "h1",
			block: [
				{
					line: "a Hello world",
					block: [],
				},
			],
		},
	];
	const P = new Parser(vlvtCode);
	// expect(P.getBlockTree()).toStrictEqual(wantedABT);
});

test.todo("Get simple depth block tree", () => {
	const vlvtCode = `h1
span
	small
		a Hello world`;
	const wantedABT: BlockTree = [
		{
			line: "h1",
			block: [
				{
					line: "span",
					block: [
						{
							line: "small",
							block: [
								{
									line: "a Hello world",
									block: [],
								},
							],
						},
					],
				},
			],
		},
	];
	const P = new Parser(vlvtCode);
	// expect(P.getBlockTree()).toStrictEqual(wantedABT);
});
