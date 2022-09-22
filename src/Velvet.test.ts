import Velvet from "./Velvet";

test("Parse simple tag", () => {
	expect(Velvet.parse("h1 Hello world")).toBe("<h1>Hello world</h1>");
});

test("Remove empty lines", () => {
	expect(
		Velvet.parse(`
	h1 Hello world
	
	h2 How are you ? 
	
	`)
	).toBe(`<h1>Hello world</h1>
<h2>How are you ?</h2>`);
});

test.todo("Simple sub tab", () => {
	expect(
		Velvet.parse(`h1
	span Hello world
`)
	).toBe(`<h1><span>Hello world</span></h1>`);
});
