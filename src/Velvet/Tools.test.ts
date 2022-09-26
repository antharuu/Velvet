import { getIdent } from "./Tools";

describe("Indentation", () => {
	test("With tabsize: tab", () => {
		expect(getIdent("h1 Hello world", "tab")).toEqual(0);
		expect(getIdent("	h1 Hello world", "tab")).toEqual(1);
		expect(getIdent("		h1 Hello world", "tab")).toEqual(2);
	});
	test("With tabsize: 2", () => {
		expect(getIdent("h1 Hello world", 2)).toEqual(0);
		expect(getIdent("  h1 Hello world", 2)).toEqual(1);
		expect(getIdent("    h1 Hello world", 2)).toEqual(2);
	});
	test("With tabsize: 4", () => {
		expect(getIdent("h1 Hello world", 4)).toEqual(0);
		expect(getIdent("    h1 Hello world", 4)).toEqual(1);
		expect(getIdent("        h1 Hello world", 4)).toEqual(2);
	});
});
