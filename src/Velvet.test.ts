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
