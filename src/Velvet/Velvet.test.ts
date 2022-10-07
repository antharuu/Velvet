import Velvet from "./Velvet";

test("Parse simple tag", () => {
	expect(Velvet.parse("h1 Hello world")).toBe("<h1>Hello world</h1>");
});

test("Remove empty lines", () => {
	expect(
		Velvet.parse(`h1 Hello world
h2 How are you ?`)
	).toBe(`<h1>Hello world</h1>
<h2>How are you ?</h2>`);
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

test("Simple sub tab", () => {
	expect(
		Velvet.parse(`h1
	span Hello world
`)
	).toBe("<h1><span>Hello world</span></h1>");
});

test("Remove useless spaces/tabs at start", () => {
	expect(
		Velvet.parse(`	 h1
		
				  span Hello world
`)
	).toBe("<h1><span>Hello world</span></h1>");
});

test("With a simple attribute", () => {
	expect(Velvet.parse("a(href=#) Hello world")).toBe(
		'<a href="#">Hello world</a>'
	);
	expect(Velvet.parse("a(href=# disabled) Hello world")).toBe(
		'<a href="#" disabled>Hello world</a>'
	);
	expect(Velvet.parse("a(href=#) Hello (world)")).toBe(
		'<a href="#">Hello (world)</a>'
	);
	expect(Velvet.parse('h1(data-test="title") Hello world')).toBe(
		'<h1 data-test="title">Hello world</h1>'
	);
});
